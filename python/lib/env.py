import sys

def get_environment():
	''' Get the environment name '''
	environments = {'development', 'testing', 'production'}

	if sys.argv:
		for argv in sys.argv:
			if argv in environments:
				return argv

	return 'production'

ENVIRONMENT = get_environment()
