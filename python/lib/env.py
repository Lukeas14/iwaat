import sys
from configobj import ConfigObj

def get_environment():
	''' Get the environment name '''
	environments = {'development', 'testing', 'production'}

	if sys.argv:
		for argv in sys.argv:
			if argv in environments:
				return argv

	return 'production'

ENVIRONMENT = get_environment()

config_obj = ConfigObj('lib/env.conf')
conf = config_obj[ENVIRONMENT]