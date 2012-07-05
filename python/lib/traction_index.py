from lib import app_model
import math
from pprint import pprint

class TractionIndex:

	fields = (
		'facebook_share_count',
		'seomoz_authority',
		'compete_unique_visitors',
		'alexa_pageviews_per_million'
	)

	field_aggrs = {}

	def __init__(self):
		self.get_field_aggrs()

	def get_field_aggrs(self):
		field_aggrs = app_model.get_field_aggrs(self.fields)
		for field_aggr in field_aggrs:
			self.field_aggrs[field_aggr.type] = {'min':field_aggr.min, 'max':field_aggr.max}

		return True

	def get_traction_index(self, app_id):
		if not self.field_aggrs:
			self.get_field_aggrs()

		sub_indices = {}

		app_external_data = app_model.get_app_external_data(self.fields, app_id)
		for app_data in app_external_data:
			if not app_data.data:
				continue

			sub_index_val = (math.log(app_data.data) - math.log(self.field_aggrs[app_data.type]['min'])) / (math.log(self.field_aggrs[app_data.type]['max']) - math.log(self.field_aggrs[app_data.type]['min']))
			if sub_index_val > 0:
				sub_indices[app_data.type] = sub_index_val

		if len(sub_indices) == 0:
			return False

		sub_indices_product = 1
		for sub_index_type, sub_index_val in sub_indices.items():
			sub_indices_product *= sub_index_val
		traction_value = math.pow(sub_indices_product, (1 / float(len(sub_indices))))
		traction_index = round(traction_value * 100)

		return traction_index