from env import conf
import sqlalchemy
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker, relationship, backref
from sqlalchemy import Column, Integer, String, DateTime, Enum, Text, ForeignKey, sql, exc, func
import datetime
from dateutil.relativedelta import relativedelta
import pprint
import pymongo
from pymongo import Connection

#MySQL connection
mysql_url = 'mysql://' + conf['mysql']['user'] + ':' + conf['mysql']['pass'] + '@' + conf['mysql']['host'] + '/' + conf['mysql']['db_name']
engine = sqlalchemy.create_engine(mysql_url, echo=False)
Session = sessionmaker(bind=engine)
session = Session()
Base = declarative_base()

#MongoDB connection
connection = Connection(conf['mongodb']['host'], conf['mongodb']['port'])
iwaat_db = connection.iwaat
discussions_collection = iwaat_db.discussions

class AppUrl(Base):
	__tablename__ = 'app_urls'
	__table_args__ = {
		'mysql_engine': 'InnoDB',
		'mysql_charset': 'utf8'
	}

	id = Column(Integer, primary_key=True)
	app_id = Column(Integer, ForeignKey('apps.id'))
	type = Column(Enum('homepage', 'homepage_redirect', 'blog', 'rss', 'twitter', 'affiliate'))
	url = Column(Text)

	def __init__(self, app_id, type, url):
		self.app_id = app_id
		self.type = type
		self.url = url

	def __repr__(self):
		return "<App('%s')>" % (self.url)

class AppExternalData(Base):
	__tablename__ = 'app_external_data'
	__table_args__ = {  
		'mysql_engine': 'InnoDB',
		'mysql_charset': 'utf8'
	}

	id = Column(Integer, primary_key=True)
	app_id = Column(Integer, ForeignKey('apps.id'))
	type = Column(String(64))
	data = Column(Integer)
	time_added = Column(DateTime)

	def __init__(self, app_id, type, data, time_added):
		self.app_id = app_id
		self.type = type
		self.data = data
		self.time_added = time_added

class AppExternalMedia(Base):
	__tablename__ = 'app_external_media'
	__table_args__ = {
		'mysql_engine': 'InnoDB',
		'mysql_charset': 'utf8'
	}

	id = Column(Integer, primary_key=True)
	app_id = Column(Integer, ForeignKey('apps.id'))
	type = Column(String(64))
	data = Column(Text)
	time_added = Column(DateTime)

	def __init__(self, app_id, type, data, time_added):
		self.app_id = app_id
		self.type = type
		self.data = data
		self.time_added = time_added

class App(Base):
	__tablename__ = 'apps'
	__table_args__ = {  
		'mysql_engine': 'InnoDB',
		'mysql_charset': 'utf8'
	}

	id = Column(Integer, primary_key=True)
	name = Column(String)
	slug = Column(String)
	last_import = Column(DateTime)
	status = Column(Enum('status', 'inactive', 'pending_review'))
	popularity_index = Column(Integer)

	app_urls = relationship("AppUrl", order_by="AppUrl.id", backref="AppUrl")
	app_external_data = relationship("AppExternalData", order_by="AppExternalData.id", backref="AppExternalData")

	def has_twitter_screen_name(self):
		for app_url in self.app_urls:
			if app_url.type == 'twitter':
				return True
		return False

	def __init__(self, name):
		self.name = name

	def __repr__(self):
		return "<App('%s')>" % (self.name)

def get_app(app_id):
	app_result = session.query(App).\
		filter(App.id == app_id)
	return app_result[0]

def get_app_import_queue(queue_size = 15):
	app_queue = session.query(App).\
		filter(App.status.in_(['active','pending_review'])).\
		order_by(App.last_import.asc()).offset(0)\
		[:queue_size]
	return app_queue

def get_latest_app_tweet(app_id):
	if not app_id:
		return False
	latest_app_tweet = discussions_collection.find_one({'app_id':app_id, 'type':'app_tweet'}, sort=[('time_posted', pymongo.DESCENDING)])
	return latest_app_tweet

def get_offset_app_tweet(app_id, offset = 10, fields=['_id'], date_range = False):
	if not app_id or not offset or not fields:
		return False
	if date_range:
		offset_app_tweet = discussions_collection.find_one({'app_id':app_id, 'type':'app_tweet', 'time_posted':{'$gte':date_range[0], '$lte':date_range[1]}}, sort=[('time_posted', pymongo.DESCENDING)], fields=fields, skip=offset)
	else:
		offset_app_tweet = discussions_collection.find_one({'app_id':app_id, 'type':'app_tweet'}, sort=[('time_posted', pymongo.DESCENDING)], fields=fields, skip=offset)
	return offset_app_tweet

def get_offset_blog_post(app_id, offset = 10, fields=['_id']):
	if not app_id or not offset or not fields:
		return False
	offset_blog_post = discussions_collection.find_one({'app_id':app_id, 'type':'blog_post'}, sort=[('time_posted', pymongo.DESCENDING)], fields=fields, skip=offset)
	return offset_blog_post

def get_latest_blog_post_link(app_id):
	if not app_id:
		return False
	blog_post_link = discussions_collection.find_one({'app_id':app_id, 'type':'blog_post'}, fields=['link'], sort=[('time_posted', pymongo.DESCENDING)])
	return blog_post_link

def get_apps_with_twitter_id():
	query_result = session.query(App.id.label('app_id'), AppUrl.url.label('twitter_id')).\
		join(AppUrl, App.id == AppUrl.app_id).\
		filter(App.status.in_(['active','pending_review'])).\
		filter(AppUrl.type == 'twitter_id')
	return query_result

def get_field_aggrs(fields):
	query_result = session.query(AppExternalData.type, func.min(AppExternalData.data).label('min'), func.max(AppExternalData.data).label('max')).\
		group_by(AppExternalData.type).\
		filter(AppExternalData.type.in_(fields))
	return query_result

def get_app_external_data(fields, app_id):
	query_result = session.query(AppExternalData.type, AppExternalData.data, AppExternalData.time_added).\
		filter(AppExternalData.app_id == app_id).\
		filter(AppExternalData.type.in_(fields)).\
		filter(AppExternalData.time_added > (datetime.date.today() + relativedelta(months=-6)).isoformat()).\
		group_by(AppExternalData.type)
	return query_result

def set_discussion(discussion):
	return set_discussions([discussion])

def set_discussions(discussions):
	discussions_collection.insert(discussions)

def set_last_import(app_ids):
	try:
		update_result = session.query(App).\
			filter(App.id.in_(app_ids)).\
			update({'last_import':datetime.datetime.now()}, False)
		session.commit()
	except exc.SQLAlchemyError:
		return False

	return update_result

def set_app_data(app_id, col, val):
	try:
		update_result = session.query(App).\
			filter(App.id == app_id).\
			update({col:val})
	except exc.SQLAlchemyError, e:
		pprint.pprint(e)
		return False

	return update_result

def set_app_url(app_id, type, url):
	try:
		update_result = session.query(AppUrl).\
			filter_by(app_id = app_id, type = type).\
			update({'url':url})
		if update_result == 0:
			new_app_url = AppUrl(app_id, type, url)
			session.add(new_app_url)
		session.commit()
	except exc.SQLAlchemyError:
		return False

	return update_result

def set_external_data(app_id, type, data):
	try:
		update_result = session.query(AppExternalData).\
			filter_by(app_id = app_id, type = type).\
			update({'data':data, 'time_added':str(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"))})
		if update_result == 0:
			new_external_data = AppExternalData(app_id, type, data, str(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")))
			session.add(new_external_data)
		session.commit()
	except exc.SQLAlchemyError:
		print 'Error'

	return update_result

def set_external_media(app_id, type, data):
	try:
		update_result = session.query(AppExternalMedia).\
			filter_by(app_id = app_id, type = type).\
			update({'data':data, 'time_added':str(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"))})
		if update_result == 0:
			new_external_data = AppExternalMedia(app_id, type, data, str(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")))
			session.add(new_external_data)
		session.commit()
	except exc.SQLAlchemyError:
		print 'Error'

	return update_result

def delete_app_tweets(app_id, tweet_id, direction='$lte', date_range=False):
	if date_range:
		discussions_collection.remove({'app_id':app_id, 'time_posted':{'$gte':date_range[0], '$lte':date_range[1]}, '_id': {direction:tweet_id}})
	else:
		discussions_collection.remove({'app_id':app_id, '_id': {direction:tweet_id}})
	return True

def delete_blog_posts(app_id, time_posted, direction='$lte'):
	discussions_collection.remove({'app_id':app_id, 'time_posted':{direction:time_posted}})
	return True	