from lib import env_constants, app_model, twitter_data

from bs4 import BeautifulSoup
import requests
from lib.external_data import ExternalData
from lib.twitter_data import TwitterData
import datetime
import pprint

r = requests.get('http://www.kiva.com')
#print r.url

soup = BeautifulSoup(r.text)
#print soup.title

while True:
	twitter_data = TwitterData()

	twitter_queue = {}

	last_import_queue = []

	print 'Retrieving app import queue...'
	app_queue = app_model.get_app_import_queue(queue_size=100)
	for app in app_queue:

		print app.name + ' - ' + str(app.id)

		external_data = ExternalData(app)

		#Add to last import queue
		last_import_queue.append(app.id)

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
		if offset_blog_post:
			app_model.delete_blog_posts(app.id, offset_blog_post['time_posted'], '$lte')


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
