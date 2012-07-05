import datetime
import urllib
import hmac
import hashlib
import base64

class Alexa:



	aws_access_key = 'AKIAI7WCNVOFIFYCQOIA'

	aws_secret = '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'

	aws_host = 'awis.amazonaws.com'

	aws_path = '/'

	params = {
		'Action': 'UrlInfo',
		'ResponseGroup': 'TrafficData',
		'SignatureVersion': '2',
		'SignatureMethod': 'HmacSHA256'
	}

	def get_alexa_url(self, url):
		if not url:
			return False

		self.params['Url'] = url
		self.params['Timestamp'] = self.create_timestamp()
		self.params['AWSAccessKeyId'] = self.aws_access_key

		uri = self.create_uri(self.params)
		signature = self.create_signature()
		aws_url = "http://%s/?%s&Signature=%s" % (self.aws_host, uri, signature)

		return aws_url

	def create_timestamp(self):
		now = datetime.datetime.now()
		right_now = now + datetime.timedelta(hours=8)
		timestamp = right_now.isoformat()
		return timestamp + "Z"

	def create_uri(self, params):
		params = [(key, params[key])
			for key in sorted(params.keys())]
		return urllib.urlencode(params)

	def create_signature(self):
		uri = self.create_uri(self.params)
		msg = "\n".join(["GET", self.aws_host, self.aws_path, uri])
		hmac_signature = hmac.new(self.aws_secret, msg, hashlib.sha256)
		signature = base64.b64encode(hmac_signature.digest())
		return urllib.quote(signature)