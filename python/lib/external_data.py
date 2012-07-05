import requests
import json
import feedparser
import pprint
from dateutil.parser import parse
import tweepy
import pprint
import urlparse
from bs4 import BeautifulSoup
import site_crawler
from alexa import Alexa
from lsapi import lsapi
import locale
import datetime

class ExternalData:

	REQUEST_TIMEOUT = 60

	USER_AGENT = 'IWAATbot/1.0 +http://www.iwaat.com/'

	SEOMOZ_AUTHORITY_URL = 'http://lsapi.seomoz.com/linkscape/url-metrics/%s?AccessID=%s&Expires=%s&Signature=%s'
	SEOMOZ_ACCESS_ID = 'member-33f124b667'
	SEOMOZ_SECRET_KEY = 'd3a75efeac98bff3fdee353c90d6aaf3'

	FACEBOOK_SHARE_COUNT_URL = 'http://graph.facebook.com/?id=%s'

	COMPETE_UV_URL = 'http://apps.compete.com/sites/%s/trended/uv/?apikey=%s&latest=1'
	COMPETE_API_KEY = '2e391886a1969cb33b19a34d2aaab2cf'

	ALEXA_URL = 'http://awis.amazonaws.com/?AWSAccessKeyId=%s\
            &Action=UrlInfo\
            &ResponseGroup=Rank\
            &SignatureMethod=HmacSHA1\
            &SignatureVersion=2\
            &Timestamp=%s\
            &Url=%s\
            &Signature=Wz2UT%2BtCYZghLBmqtkfEpg%2FqrK8%3D'

	AWS_ACCESS_KEY = 'AKIAI7WCNVOFIFYCQOIA'
	AWS_SECRET_KEY = '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'

	def __init__(self, app):
		locale.setlocale( locale.LC_ALL, 'en_US.UTF-8' ) 

		self.app = app

		self.app_urls = {}
		for app_url in self.app.app_urls:
			self.app_urls[app_url.type] = app_url.url

		feedparser.USER_AGENT = self.USER_AGENT

	def get_compete_data(self):
		if 'homepage' not in self.app_urls or not self.app_urls['homepage']:
			return False

		compete_data = {}

		homepage_url = urlparse.urlparse(self.app_urls['homepage'])
 		compete_url = self.COMPETE_UV_URL % (homepage_url.netloc, self.COMPETE_API_KEY)
		try:
			json_response = requests.get(compete_url, timeout=self.REQUEST_TIMEOUT)
		except requests.exceptions.ConnectionError:
			return False

		response = json_response.json
		try:
			compete_data['compete_unique_visitors'] = response['data']['trends']['uv'][0]['value']
		except:
			return False

		return compete_data

	def get_alexa_data(self):
		if 'homepage' not in self.app_urls or not self.app_urls['homepage']:
			return False

		alexa_data = {}

		alexa_lib = Alexa()
		alexa_url = alexa_lib.get_alexa_url(self.app_urls['homepage'])

		try:
			response = requests.get(alexa_url, timeout=self.REQUEST_TIMEOUT)
		except requests.exceptions.ConnectionError:
			return False
		if not response.status_code == requests.codes.ok or len(response.text) > 100000:
			return False

		soup = BeautifulSoup(response.text, 'xml')
		try:
			page_views = soup.Alexa.TrafficData.UsageStatistics.Reach.PerMillion.Value.contents[0]
		except AttributeError:
			return False
		if page_views:
			try:
				page_views_per_million = locale.atof(page_views)
				if page_views_per_million > 0:
					alexa_data['alexa_pageviews_per_million'] = page_views_per_million
			except ValueError:
				pass

		return alexa_data

	def get_seomoz_data(self):
		if 'homepage' not in self.app_urls or not self.app_urls['homepage']:
			return False

		seomoz_data = {}

		homepage_url = urlparse.urlparse(self.app_urls['homepage'])
 	
		moz_lib = lsapi(self.SEOMOZ_ACCESS_ID, self.SEOMOZ_SECRET_KEY)
		response = moz_lib.urlMetrics(homepage_url.netloc)

		if response.has_key('upa') and response['upa'] > 0:
			seomoz_data['seomoz_authority'] = response['upa']

		return seomoz_data

	def get_facebook_data(self):
		if 'homepage' not in self.app_urls or not self.app_urls['homepage']:
			return False

		facebook_data = {}
		
		facebook_share_count_url = self.FACEBOOK_SHARE_COUNT_URL % (self.app_urls['homepage'])
		try:
			json_response = requests.get(facebook_share_count_url, timeout=self.REQUEST_TIMEOUT)
		except requests.exceptions.ConnectionError:
			return False
		response = json_response.json

		if response.has_key('shares') and response['shares'] > 0:
			facebook_data['facebook_share_count'] = response['shares']
		
		return facebook_data

	def get_blog_posts(self, post_limit=20, post_max_length=10000, latest_blog_post_link=False):

		if 'rss' not in self.app_urls or not self.app_urls['rss']:
			return False

		try:
			feed_data = feedparser.parse(self.app_urls['rss'])
		except:
			return False

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

	def get_site_data(self):
		if 'homepage' not in self.app_urls or not self.app_urls['homepage']:
			return False

		site_data = {}

		crawler = site_crawler.SiteCrawler(user_agent=self.USER_AGENT)

		if not crawler.set_url(self.app_urls['homepage']):
			return False

		site_data['homepage_title'] = crawler.get_page_title()
		site_data['homepage_keywords'] = crawler.get_page_keywords()
		site_data['homepage_description'] = crawler.get_page_description()

		site_text = ''

		homepage_text = crawler.get_page_text(max_text_length=10000)
		if homepage_text:
			site_text = site_text + homepage_text

		link_text = crawler.get_link_text(max_text_length=10000)
		if link_text:
			site_text = site_text = link_text
	
		site_data['site_text'] = ' '.join(list(set(site_text.split())))

		#pprint.pprint(site_data)

		return site_data

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
