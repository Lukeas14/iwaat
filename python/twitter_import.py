from lib import app_model
from lib.twitter_data import TwitterData
import pprint


apps = app_model.get_apps_with_twitter_id()
app_twitter_ids = {}
for app in apps:
	app_twitter_ids[app.twitter_id] = app.app_id

twitter_data = TwitterData()
twitter_data.stream_user_timeline(app_twitter_ids)
