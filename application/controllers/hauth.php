<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HAuth extends MY_Controller
{

	private $data;

	public function register($provider, $user_profile = array())
	{
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean|max_length[64]');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|max_length[64]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		if($this->form_validation->run() === TRUE)
		{
			//Get user data ready for registering
			$provider = $this->input->post('provider');
			$username = trim($this->input->post('first_name') . ' ' . $this->input->post('last_name'));
			$email = $this->input->post('email');
			$password = $this->config->item('user_password', 'ion_auth');
			
			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
			);
			if($this->input->post('facebook_id')) $additional_data['facebook_id'] = $this->input->post('facebook_id');
			if($this->input->post('twitter_id')) $additional_data['twitter_id'] = $this->input->post('twitter_id');
			if($provider == 'Twitter')
			{
				$additional_data['twitter_username'] = $this->input->post('hauth_username');
				$additional_data['bio'] = $this->input->post('hauth_description');
			}

			//Try to register user
			if($this->ion_auth->register($username, $password, $email, $additional_data, array(), strtolower($provider)))
			{
				$this->session->set_flashdata('confirm', 'Account created.  Welcome to IWAAT.com');
				
				$this->ion_auth->login($email, $password, false);
				
				$redirect = ($this->session->flashdata('redirect') !== false) ? $this->session->flashdata('redirect') : '/account/profile';
				redirect($redirect, 'refresh');
			}
			else
			{
				$this->data['notifications']['error'] = $this->ion_auth->errors();
			}
		}
		else
		{
			$this->data['notifications']['error'] = $this->form_validation->get_errors();
		}
		
		$this->data['provider'] = $provider;
		$this->data['user_data'] = array(
			'first_name'		=> $user_profile->firstName,
			'last_name'			=> $user_profile->lastName,
			'email'				=> $user_profile->email,
			'hauth_id'			=> $user_profile->identifier,
			'hauth_username'	=> $user_profile->displayName,
			'hauth_description'	=> $user_profile->description
		);

		if($provider == 'Twitter')
		{
			list($this->data['user_data']['first_name'], $this->data['user_data']['last_name']) = explode(" ", $user_profile->firstName, 2);
		}

		$this->set_css('hauth.css');
		$this->load->view('/hauth/hauth_register', $this->data);
	}

	public function connect($provider)
	{
		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata('message', 'Please log in or register.');
			redirect('/login_register');
		}

		try
		{
			$this->load->library('HybridAuthLib');

			if($this->hybridauthlib->serviceEnabled($provider))
			{
				$service = $this->hybridauthlib->authenticate($provider);

				if($service->isUserConnected())
				{
					$user_profile = $service->getUserProfile();

					if($this->ion_auth->hauth_user_check($provider, $user_profile->identifier))
					{
						$this->session->set_flashdata('error', 'There is already a user account connected to this ' . $provider . ' profile.');
						redirect('/account/profile');
					}
					else
					{
						$loggedin_user = $this->ion_auth->user()->row();
						$provider_id_column = strtolower($provider) . '_id';
						$user_update = array(
								$provider_id_column => $user_profile->identifier
						);
						if($provider == 'Twitter')
						{
							$user_update['twitter_username'] = $user_profile->displayName;
							$user_update['bio'] = $user_profile->description;
						}
						$this->ion_auth->update(null, $user_update);

						$this->session->set_flashdata('confirm', $provider . ' profile connected.');
						redirect('/account/profile');
					}
				}
			}
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('error', 'Error connecting ' . $provider . ' profile.');
			redirect('/account/profile');
		}
	}

	public function disconnect($provider)
	{
		if (!$this->ion_auth->logged_in())
		{
			$this->session->set_flashdata('message', 'Please log in or register.');
			redirect('/login_register');
		}

		//Check that there is another way to login to account (password or 2nd hauth provider) before disconnecting
		$login_options = $this->ion_auth->login_options();
		if($this->ion_auth->login_options() < 2)
		{
			show_404();
		}

		$provider_id_column = strtolower($provider) . '_id';
		$user_update = array(
			$provider_id_column => 0
		);
		$this->ion_auth->update(null, $user_update);

		$this->session->set_flashdata('confirm', $provider . ' profile disconnected');
		redirect('/account/profile');


		$provider_id_column = strtolower($provider) . '_id';
		$this->ion_auth->update(null, array($provider_id_column => 0));
	}

	public function login($provider)
	{
		log_message('debug', "controllers.HAuth.login($provider) called");

		try
		{
			log_message('debug', 'controllers.HAuth.login: loading HybridAuthLib');
			$this->load->library('HybridAuthLib');

			if ($this->hybridauthlib->serviceEnabled($provider))
			{
				log_message('debug', "controllers.HAuth.login: service $provider enabled, trying to authenticate.");
				$service = $this->hybridauthlib->authenticate($provider);

				if ($service->isUserConnected())
				{

					$user_profile = $service->getUserProfile();

					//Does this user exist?  Check by trying to login
					if($this->ion_auth->login($user_profile->identifier, '', false, strtolower($provider)))
					{
						$this->session->set_flashdata('confirm', $this->ion_auth->messages());
						
						$redirect = ($this->session->flashdata('redirect') !== false) ? $this->session->flashdata('redirect') : '/account/profile';
						redirect($redirect, 'refresh');
					}
					else
					{
						$this->ion_auth->clear_errors();

						//If not redirect to register page
						$this->register($provider, $user_profile);
					}

					log_message('debug', 'controller.HAuth.login: user authenticated.');

					log_message('info', 'controllers.HAuth.login: user profile:'.PHP_EOL.print_r($user_profile, TRUE));

					$data['user_profile'] = $user_profile;
				}
				else // Cannot authenticate user
				{
					$this->session->set_flashdata('error', 'Error logging in with ' . $provider . '.');
					redirect('/login_register');
				}
			}
			else // This service is not enabled.
			{
				$this->session->set_flashdata('error', 'Error logging in with ' . $provider . '.');
				redirect('/login_register');
			}
		}
		catch(Exception $e)
		{
			/*
			$error = 'Unexpected error';
			switch($e->getCode())
			{
				case 0 : $error = 'Unspecified error.'; break;
				case 1 : $error = 'Hybriauth configuration error.'; break;
				case 2 : $error = 'Provider not properly configured.'; break;
				case 3 : $error = 'Unknown or disabled provider.'; break;
				case 4 : $error = 'Missing provider application credentials.'; break;
				case 5 : log_message('debug', 'controllers.HAuth.login: Authentification failed. The user has canceled the authentication or the provider refused the connection.');
				         //redirect();
				         if (isset($service))
				         {
				         	log_message('debug', 'controllers.HAuth.login: logging out from service.');
				         	$service->logout();
				         }
				         show_error('User has cancelled the authentication or the provider refused the connection.');
				         break;
				case 6 : $error = 'User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.';
				         break;
				case 7 : $error = 'User not connected to the provider.';
				         break;
			}

			if (isset($service))
			{
				$service->logout();
			}
			
			log_message('error', 'controllers.HAuth.login: '.$error);
			show_error('Error authenticating user.');
			*/

			$this->session->set_flashdata('error', 'Error logging in with ' . $provider . '.');
			redirect('/login_register');
		}
	}

	public function endpoint()
	{
		log_message('debug', 'controllers.HAuth.endpoint called.');
		log_message('info', 'controllers.HAuth.endpoint: $_REQUEST: '.print_r($_REQUEST, TRUE));

		if ($_SERVER['REQUEST_METHOD'] === 'GET')
		{
			log_message('debug', 'controllers.HAuth.endpoint: the request method is GET, copying REQUEST array into GET array.');
			$_GET = $_REQUEST;
		}

		log_message('debug', 'controllers.HAuth.endpoint: loading the original HybridAuth endpoint script.');
		require_once APPPATH.'/third_party/hybridauth/index.php';
	}

}

/* End of file test.php */
/* Location: ./application/controllers/test.php */