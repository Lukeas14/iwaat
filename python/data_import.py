from lib import env_constants, app_model, twitter_data, memcache
from bs4 import BeautifulSoup
import requests
from lib.external_data import ExternalData
from lib.twitter_data import TwitterData
from lib.traction_index import TractionIndex
import datetime
from dateutil.relativedelta import relativedelta
import pprint

print "Starting data import..."

while True:
	twitter_data = TwitterData()

	mc = memcache.Client(['127.0.0.1:11211'], debug=0)

	traction_index = TractionIndex()

	twitter_queue = {}

	last_import_queue = []

	print 'Retrieving app import queue...'
	app_queue = app_model.get_app_import_queue(queue_size=10)
	for app in app_queue:

		print app.name + ' - ' + str(app.id)

		external_data = ExternalData(app)

		#Add to last import queue
		last_import_queue.append(app.id)

		#Get Compete data
		compete_data = external_data.get_compete_data()
		if compete_data:
			for data_type, data_val in compete_data.items():
				app_model.set_external_data(app.id, data_type, data_val)

		#Get Alexa data
		alexa_data = external_data.get_alexa_data()
		if alexa_data:
			for data_type, data_val in alexa_data.items():
				app_model.set_external_data(app.id, data_type, data_val)

		#Get Facebook share data
		facebook_data = external_data.get_facebook_data()
		if facebook_data:
			for data_type, data_val in facebook_data.items():
				app_model.set_external_data(app.id, data_type, data_val)
		
		#Get SEOmoz data
		seomoz_data = external_data.get_seomoz_data()
		if seomoz_data:
			for data_type, data_val in seomoz_data.items():
				app_model.set_external_data(app.id, data_type, data_val)

		#Get site data
		site_data = external_data.get_site_data()
		if site_data:
			for data_type, data_val in site_data.items():
				if data_val:
					app_model.set_external_media(app.id, data_type, data_val)
		
		#Limit number of tweets from today to 10
		date_range = [
			datetime.datetime.today(),
			datetime.datetime.today() + datetime.timedelta(days=1)
		]
		offset_app_tweet = app_model.get_offset_app_tweet(app.id, 10, '_id', date_range)
		if offset_app_tweet:
			app_model.delete_app_tweets(app.id, offset_app_tweet['_id'], '$lte', date_range)

		#Remove old tweets. Limit to 100 per app
		offset_app_tweet = app_model.get_offset_app_tweet(app.id, 100, '_id')
		if offset_app_tweet:
			app_model.delete_app_tweets(app.id, offset_app_tweet['_id'], '$lte')

		#Get twitter id if it doesn't already exist
		if app.has_twitter_screen_name():
			twitter_queue[external_data.app_urls['twitter'].lower()] = app.id

		#Get blog posts
		post_link = app_model.get_latest_blog_post_link(app.id)
		blog_posts = external_data.get_blog_posts(post_limit=50, post_max_length=10000, latest_blog_post_link=post_link)
		if blog_posts:
			app_model.set_discussions(blog_posts)

		#Remove old blog posts. Limit 50 per app
		offset_blog_post = app_model.get_offset_blog_post(app.id, 50, ['time_posted'])
		if offset_blog_post and 'time_posted' in offset_blog_post:
			app_model.delete_blog_posts(app.id, offset_blog_post['time_posted'], '$lte')

		#Get traction index
		app_traction_index = traction_index.get_traction_index(app.id)
		if app_traction_index:
			app_model.set_app_data(app.id, 'popularity_index', app_traction_index)

		#Clear app cache
		mc.delete('app_' + app.slug)


	#set last_import
	print 'Settings last import...'
	if len(last_import_queue) >= 1:
		app_model.set_last_import(last_import_queue)

	#get twitter ids
	print 'Retrieving twitter data...'
	twitter_id_chunk_size = 99
	twitter_id_chunks = [twitter_queue.keys()[i:i+twitter_id_chunk_size] for i in range(0, len(twitter_queue.keys()), twitter_id_chunk_size)]
	for twitter_ids in twitter_id_chunks:
		twitter_user_data = twitter_data.get_twitter_user_data(twitter_ids)
		for twitter_user in twitter_user_data:
			if twitter_user.id:
				app_model.set_app_url(twitter_queue[twitter_user.screen_name.lower()], 'twitter_id', twitter_user.id)
