from bs4 import BeautifulSoup
import requests
import robotparser
import urlparse
import re
import pprint
import site_crawler

class SiteCrawler:

	REQUEST_TIMEOUT = 20

	USER_AGENT = 'IWAATbot/1.0 +http://www.iwaat.com/'

	EXCLUDE_TERMS = ['terms', 'privacy']

	INCLUDE_TERMS = ['about', 'overview', 'feature', 'tour', 'how it works', 'faq']

	MAX_LINKS = 10

	soup = None

	url = None

	url_parsed = None

	url_redirected = None

	robots_parsed = None

	links = None

	def __init__(self, user_agent=None, robots_parsed=None):
		if user_agent:
			self.set_user_agent(user_agent)

	def set_url(self, url):
		self.url = url

		self.url_parsed = urlparse.urlparse(self.url)
		if not self.url_parsed.scheme:
			self.url = 'http://' + self.url
			self.url_parsed = urlparse.urlparse(url)
			if not self.url_parsed.scheme:
				return False

		try:
			headers = {'User-Agent': self.USER_AGENT}
			response = requests.get(url, headers=headers, allow_redirects=True, timeout=self.REQUEST_TIMEOUT)
			if not response.status_code == requests.codes.ok:
				return False

			response.encoding = 'UTF8'

			self.url_redirected = response.url

			self.soup = BeautifulSoup(response.text)
			if not self.soup:
				return False

		except Exception, e:
			return False

		self.get_robots()

		return True

	def set_user_agent(self, user_agent):
		self.USER_AGENT = user_agent

	def get_robots(self):
		if self.robots_parsed:
			return self.robots_parsed

		robots_url = self.url_parsed.scheme + '://' + self.url_parsed.netloc + '/robots.txt'

		try:
			headers = {'User-Agent': self.USER_AGENT}
			response = requests.get(robots_url, headers=headers, allow_redirects=True, timeout=self.REQUEST_TIMEOUT)
			if not response.status_code == requests.codes.ok:
				return False

			self.robots_parsed = robotparser.RobotFileParser()
			self.robots_parsed.set_url(robots_url)
			self.robots_parsed.read()
		except Exception, e:
			return False

	def get_page_title(self):
		title = self.soup.title
		if title:
			return self.clean_text(title.string)
		else:
			return False

	def get_page_keywords(self):
		keywords = self.soup.find("meta", {"name":"keywords"})
		if keywords and keywords.has_key('content'):
			return self.clean_text(keywords['content'])
		else:
			return False

	def get_page_description(self):
		description = self.soup.find("meta", {"name":"description"})
		if description:
			return self.clean_text(description['content'])
		else:
			return False

	def get_page_text(self, max_text_length=10000):
		page_text = self.soup.findAll(text=True)

		def filter_tags(elem):
			if elem.parent.name in ['style', 'script', '[document]', 'head', 'title']:
				return ''
			result = re.sub('<!--.*-->', ' ', elem.encode('utf-8', 'replace'), flags=re.DOTALL)
			result = ' '.join(result.split())
			return result

		visible_elements = [filter_tags(elem) for elem in page_text]
		visible_text = ' '.join(' '.join(visible_elements).split())
		return self.clean_text(visible_text[10:max_text_length])

	def get_link_text(self, max_text_length=10000):
		link_text = ''

		self.get_links()

		for link in self.links[:self.MAX_LINKS]:
			crawler = site_crawler.SiteCrawler(user_agent=self.USER_AGENT)
			if not crawler.set_url(link):
				continue
			page_text = crawler.get_page_text(max_text_length=max_text_length)
			if page_text:
				link_text = link_text + page_text + "\n\n"

		return link_text

	def get_links(self):
		if self.links:
			return self.links

		homepage_host = '.'.join(self.url_parsed.netloc.split('.')[-2:])

		self.links = []
		for link in self.soup.findAll('a'):
			link_href = link.get('href')

			if not link_href:
				continue

			link_parsed = urlparse.urlparse(link_href)

			#relative link
			if not link_parsed.netloc:
				link_url = urlparse.urljoin(self.url_redirected, link_href)
			#absolute link
			else:
				link_host = '.'.join(link_parsed.netloc.split('.')[-2:])
				if not link_host == homepage_host:
					continue;

				link_url = link_parsed.geturl()

			if link_url == self.url or link_url == self.url_redirected or link_url in self.links:
				continue

			if self.robots_parsed and not self.robots_parsed.can_fetch(self.USER_AGENT, link_url.encode('utf-8')):
				continue

			#get link url's last segment
			if link_parsed.path[-1:] == '/':
				last_segment = link_parsed.path.split('/')[-2]
			else:
				last_segment = link_parsed.path.split('/')[-1]


			
			exclude_term_found = False
			for term in self.EXCLUDE_TERMS:
				if link.string and term in link.string.encode('utf-8'):
					exclude_term_found = True
					break
				if term in last_segment:
					exclude_term_found = True
					break
			if exclude_term_found:
				continue

			include_term_found = False
			for term in self.INCLUDE_TERMS:
				if link.string and term in link.string.encode('utf-8') and len(link.string) < 20:
					include_term_found = True
					break
				if term in last_segment and len(last_segment) < 20:
					include_term_found = True
					break

			if include_term_found:
				self.links.append(link_url)

	def clean_text(self, text=''):
		try:
			return ''.join(i for i in text if ord(i)<128)
		except:
			return ''