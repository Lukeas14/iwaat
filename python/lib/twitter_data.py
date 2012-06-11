import tweepy
import sys
import pprint
from lib import app_model


class TwitterData:

	CONSUMER_KEY = 'q4Ol3UY5MizRpa3cadFcA'
	CONSUMER_SECRET = 'CVeic3IhM3dav39hLhnmXSlhGEPcHSHJYlBAAEx3ZYc'

	ACCESS_TOKEN = '471115836-5V7pluWSQCwnLdnsdCP0gg9VJuRKm48vhcFDi5pZ'
	ACCESS_TOKEN_SECRET = 'BA39Wg8aTNixm9mRaevkRr4AjKsFt1D0BLvJcIXaRE'

	twitter_tweet_url = "https://twitter.com/#!/%s/status/%s"

	def __init__(self, app=None):

		self.auth = tweepy.OAuthHandler(self.CONSUMER_KEY, self.CONSUMER_SECRET)
		self.auth.set_access_token(self.ACCESS_TOKEN, self.ACCESS_TOKEN_SECRET)
		self.api = tweepy.API(self.auth)

		if app:
			self.app = app

			self.app_urls = {}
			for app_url in self.app.app_urls:
				self.app_urls[app_url.type] = app_url.url

	def get_twitter_user_data(self, screen_names):
		if not screen_names:
			return False

		try:
			user_data = self.api.lookup_users(screen_names=screen_names)
			return user_data
		except tweepy.error.TweepError as e:
			print e.reason

	def stream_user_timeline(self, apps):
		self.apps = apps

		# Create a streaming API and set a timeout value of 60 seconds.
		stream_listener = self.StreamListener()
		stream_listener.save_tweet = self.save_tweet

		streaming_api = tweepy.streaming.Stream(self.auth, stream_listener, timeout=60, snooze_time=5.0)

		# Optionally filter the statuses you want to track by providing a list
		# of users to "follow".

		print "Filtering the public timeline"

		#streaming_api.sample()
		streaming_api.filter(follow=self.apps.keys())

	def truncate_url(self, url, length=15):
		if len(url) <= length:
			return url
		else:
			return url[:length] + '...'

	def integrate_entities(self, plain_text, entities, display_text='', offset=0):
		pprint.pprint(entities)
		
		display_text = ''
		offset = 0

		for entity_type, entity_list in entities.iteritems():
			if not entity_list:
				continue

			for entity in entity_list:
				entity_text = ''

				#Create entity text based on entity type
				if entity_type == 'user_mentions':
					entity_text = '<a href="http://twitter.com/' + entity['screen_name'] + '" rel="nofollow">@' + entity['screen_name'] + '</a>'
				elif entity_type == 'urls':
					entity_text = '<a href="' + entity['url'] + '" rel="nofollow">' + self.truncate_url(entity['display_url'], 20) + '</a>'
				elif entity_type == 'hashtags':
					entity_text = '<a href="http://twitter.com/search/%23' + entity['text'] + '" rel="nofollow">#' +  entity['text'] + '</a>'
				elif entity_type == 'media':
					entity_text == '<a href="' + entity['url'] + '" rel="nofollow">' + self.truncate_url(entity['display_url'], 20) + '</a>'

				if not entity_text:
					break

				display_text = display_text + plain_text[offset:entity['indices'][0]] + entity_text 

				offset = entity['indices'][1]

		display_text = display_text + plain_text[offset:]

		return display_text

	def save_tweet(self, status):
		if status.user.id_str in self.apps:
			app_tweet = {
				'type'		: 'app_tweet',
				'app_id'	: self.apps[status.user.id_str]
			}
			
		else:
			app_tweet = {
				'type'		: 'mention_tweet'
			}

			#Get app id
			for user_mention in status.entities['user_mentions']:
				if user_mention['id_str'] in self.apps:
					app_tweet['app_id'] = self.apps[user_mention['id_str']]
					break
		

		tweet_attributes = {
			'id'						: 'twitter_tweet_id',
			'text'						: 'twitter_tweet_text',
			'created_at'				: 'time_posted',
			'user.screen_name'			: 'twitter_screen_name',
			'user.name'					: 'twitter_full_name',
			'user.profile_image_url'	: 'twitter_profile_image'
		}
		for twitter_attr, discussion_attr in tweet_attributes.iteritems():
			if hasattr(status, twitter_attr):
				app_tweet[discussion_attr] = eval('status. ' + twitter_attr)

		app_tweet['text'] = self.integrate_entities(app_tweet['twitter_tweet_text'], status.entities)

		pprint.pprint(app_tweet)

	class StreamListener(tweepy.StreamListener):

	    def on_status(self, status):
	        try:
	        	#Don't save retweeted statuses
	        	if hasattr(status, 'retweeted_status'):
	        		return True

	        	#Don't save empty tweets
	        	if not status.text:
	        		print 'empty text'
	        		return True

	        	self.save_tweet(status)

	        except Exception, e:
	            print >> sys.stderr, 'Encountered Exception:', e
	            pass

	    def on_error(self, status_code):
	        print >> sys.stderr, 'Encountered error with status code:', status_code
	        return True # Don't kill the stream

	    def on_timeout(self):
	        print >> sys.stderr, 'Timeout...'
	        return True # Don't kill the stream