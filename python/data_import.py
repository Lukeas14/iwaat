from lib import env_constants, app_model, twitter_data

from bs4 import BeautifulSoup
import requests
from lib.external_data import ExternalData
from lib.twitter_data import TwitterData
import pprint

r = requests.get('http://www.kiva.com')
#print r.url

soup = BeautifulSoup(r.text)
#print soup.title

twitter_data = TwitterData()

twitter_queue = {}

last_import_queue = []

app_queue = app_model.get_app_import_queue(queue_size=100)
for app in app_queue:
	
	print app.name + ' - ' + str(app.id)
	
	external_data = ExternalData(app)

	last_import_queue.append(app.id)

	if app.has_twitter_screen_name():
		twitter_queue[external_data.app_urls['twitter'].lower()] = app.id

	#app_model.set_app_external_data(24, 'test_type', app.id)

	'''
	blog_posts = external_data.get_blog_posts(post_limit=20, post_max_length=10000)
	if blog_posts:
		pprint.pprint(blog_posts)
		#lib.models.set_discussions(blog_posts)
	
	app_tweets = external_data.get_twitter_timeline(tweet_limit=20)
	if app_tweets:
		lib.models.set_discussions(app_tweets)
	'''

#set last_import
print 'Settings last import...'
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