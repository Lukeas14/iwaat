import requests
import feedparser
import pprint
from dateutil.parser import parse
import tweepy
import pprint
from bs4 import BeautifulSoup

class ExternalData:

	user_agent = "IWAATbot/1.0 +http://www.iwaat.com/"

	twitter_tweet_url = "https://twitter.com/#!/%s/status/%s"

	def __init__(self, app):
		self.app = app

		self.app_urls = {}
		for app_url in self.app.app_urls:
			self.app_urls[app_url.type] = app_url.url

		feedparser.USER_AGENT = self.user_agent

	def get_blog_posts(self, post_limit=20, post_max_length=10000, latest_blog_post_link=False):

		if 'rss' not in self.app_urls or not self.app_urls['rss']:
			return False

		feed_data = feedparser.parse(self.app_urls['rss'])

		if feed_data.bozo == 1 or not feed_data.entries or len(feed_data.entries) < 1:
			return False

		feed_entries = []
		for entry in feed_data.entries[:post_limit]:

			#Stop when link is found in database
			if latest_blog_post_link and entry.link == latest_blog_post_link['link']:
				break

			feed_entry = {
				'type'		: 'blog_post',
				'app_id'	: self.app.id
			}

			if entry.has_key('title'):
				feed_entry['title'] = entry.title

			if entry.has_key('link'):
				feed_entry['link'] = entry.link

			if entry.has_key('updated'):
				feed_entry['time_posted'] = parse(entry.updated)

			if entry.has_key('summary'):
				feed_entry['content'] = entry.summary[:post_max_length]
			elif entry.has_key('content'):
				feed_entry['content'] = entry.content[:post_max_length]
			else:
				continue

			feed_entry['content'] = ' '.join(''.join(BeautifulSoup(feed_entry['content']).findAll(text=True)).split())

			feed_entries.append(feed_entry)

		return feed_entries

	def get_twitter_timeline(self, tweet_limit=20):

		if 'twitter' not in self.app_urls or not self.app_urls['twitter']:
			return False

		try:
			timeline = tweepy.api.user_timeline(id=self.app_urls['twitter'], count=tweet_limit)
		except tweepy.error.TweepError:
			return False

		app_tweets = []
		for tweet in timeline:
			app_tweet = {
				'type'		: 'app_tweet',
				'app_id'	: self.app.id
			}

			if tweet.id:
				app_tweet['twitter_tweet_id'] = tweet.id

			if tweet.text:
				app_tweet['text'] = tweet.text

			if tweet.created_at:
				app_tweet['time_posted'] = tweet.created_at

			if tweet.user.screen_name:
				app_tweet['twitter_screen_name'] = tweet.user.screen_name

			if tweet.user.name:
				app_tweet['twitter_name'] = tweet.user.name

			if tweet.user.profile_image_url:
				app_tweet['twitter_profile_image'] = tweet.user.profile_image_url

			app_tweets.append(app_tweet)

		return app_tweets
