import sqlalchemy
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker, relationship, backref
from sqlalchemy import Column, Integer, String, DateTime, Enum, Text, ForeignKey, sql, exc
import datetime

import pymongo
from pymongo import Connection

#MySQL connection
engine = sqlalchemy.create_engine('mysql://jiwaatlucas:j23waati$lucas@localhost/iwaat', echo=False)
Session = sessionmaker(bind=engine)
session = Session()
Base = declarative_base()

#MongoDB connection
connection = Connection('localhost', 27017)
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

class App(Base):
	__tablename__ = 'apps'
	__table_args__ = {  
		'mysql_engine': 'InnoDB',
		'mysql_charset': 'utf8'
	}

	id = Column(Integer, primary_key=True)
	name = Column(String)
	last_import = Column(DateTime)
	status = Column(Enum('status', 'inactive', 'pending_review'))

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

def get_apps_with_twitter_id():
	query_result = session.query(App.id.label('app_id'), AppUrl.url.label('twitter_id')).\
		join(AppUrl, App.id == AppUrl.app_id).\
		filter(App.status.in_(['active','pending_review'])).\
		filter(AppUrl.type == 'twitter_id')

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
			update({'data':data, 'time_added':datetime.datetime.now})
		if update_result == 0:
			new_external_data = AppExternalData(app_id, type, data, datetime.datetime.now)
			session.add(new_external_data)
		session.commit()
	except exc.SQLAlchemyError:
		print 'Error'

	return update_result
