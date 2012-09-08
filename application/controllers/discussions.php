<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Discussions extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	public function question(){
		$this->load->model('app');
		$this->load->model('question');

		//Get app data
		$app_slug = $this->uri->segment(2);
		$this->data['app'] = $this->app->get_cached_app($app_slug);
		if(empty($this->data['app']) || $this->data['app']['status'] != 'active'){
			show_404();
		}

		//Get question data
		$question_display_id = $this->uri->segment(3);
		if(!$this->data['question'] = $this->question->get_question($question_display_id)){
			show_404();
		}

		//Get user data
		$this->data['user'] = $this->ion_auth->user($this->data['question']['user_id'])->row_array();

		//echo"<pre>";print_r($this->data['user']);print_r($this->data['question']);echo"</pre>";

		$this->set_css(array('discussions.css'));

		$this->load->view('question', $this->data);
	}

	public function add_question(){
		if(!$this->ion_auth->logged_in()){
			$this->session->set_flashdata('message', 'Please log in or register to write or edit a question.');
			$this->session->set_flashdata('redirect', $this->uri->uri_string());
			redirect('/login_register', 'location');
		}

		$this->load->model(array('app', 'question'));
		$this->load->helper(array('form', 'url'));

		//Get user data
		$this->data['user'] = $this->ion_auth->user()->row_array();

		$this->data['question_action'] = $this->uri->segment(2);

		if($this->data['question_action'] == 'add'){
			//Get app data
			$app_slug = $this->uri->segment(3);
			$this->data['app'] = $this->app->get_cached_app($app_slug);
			if(empty($this->data['app']) || $this->data['app']['status'] != 'active'){
				show_404();
			}
		}
		elseif($this->data['question_action'] == 'edit'){
			$question_display_id = $this->uri->segment(3);
			$this->data['user_question'] = $this->question->get_user_question($this->data['user']['id'], $this->data['app']['id']);
		}
		else{
			show_404();
		}

		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->load->library('form_validation');

			$validation_rules = array(
				array(
					'field' => 'title',
					'label' => 'Title',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'question',
					'label' => 'Question',
					'rules' => 'trim|required'
				)
			);
			$this->form_validation->set_rules($validation_rules);

			if($this->form_validation->run() === false){
				$this->data['notifications']['error'] = $this->form_validation->get_errors();
			}
			else{
				$question = array(
					'app_id' => $this->data['app']['id'],
					'user_id' => $this->data['user']['id'],
					'title' => $this->input->post('title'),
					'text' => $this->input->post('question')
				);

				if($this->data['question_action'] == 'add'){
					$result = $this->question->add_question($question);
					$display_id = $result['data']['display_id'];
					$confirm_message = 'Your question about ' . $this->data['app']['name'] . ' has been added.';
					$error_message = 'Your question about ' . $this->data['app']['name'] . ' was not added. Please try again.';
				}
				elseif($this->data['question_action'] == 'edit'){
					$result = $this->review->update_review($this->data['user_app_review']['_id'], $review);
					$display_id = $this->data['user_app_review']['display_id'];
					$confirm_message = 'Review for ' . $this->data['app']['name'] . ' edited.';
					$error_message = 'Review for ' . $this->data['app']['name'] . ' not edited. Please try again.';
				}

				if($result){
					$this->session->set_flashdata('confirm', $confirm_message);
					redirect('/question/' . $this->data['app']['slug'] . '/' . $display_id);
				}
				else{
					$this->data['notifications']['error'] = $error_message;
				}
			}
		}


		$this->set_css(array('redactor.css', 'discussions.css'));
		$this->set_js('redactor.js');

		$this->load->view('add_question', $this->data);
	}

	public function review(){
		$this->load->model('app');
		$this->load->model('review');

		$review_display_id = $this->uri->segment(2);

		if(!$this->data['review'] = $this->review->get_review($review_display_id)){
			show_404();
		}

		$this->data['user'] = $this->ion_auth->user($this->data['review']['user_id'])->row();
		$this->data['app'] = $this->app->get_cached_app($this->data['review']['app_id']);

		//echo"<pre>";print_r($this->data['review_user']);print_r($this->data['review_app']);echo"</pre>";

		$this->set_css(array('discussions.css'));

		$this->load->view('review', $this->data);
	}
	
	public function add_edit_review(){
		if(!$this->ion_auth->logged_in()){
			$this->session->set_flashdata('message', 'Please log in or register to write or edit a review.');
			$this->session->set_flashdata('redirect', $this->uri->uri_string());
			redirect('/login_register', 'location');
		}

		$this->load->model('app');
		$this->load->model('review');
		$this->load->helper(array('form', 'url'));

		//Get app data
		$app_slug = $this->uri->segment(3);
		$app = $this->app->get_cached_app($app_slug);
		if(empty($app) || $app['status'] != 'active'){
			show_404();
		}

		//Get user data
		$this->data['user_profile'] = $this->ion_auth->user()->row();

		$this->data['user_app_review'] = $this->review->get_user_app_review($this->data['user_profile']->id, $app['id']);

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->load->library('form_validation');

			$validation_rules = array(
				array(
					'field' => 'title',
					'label' => 'Title',
					'rules' => 'trim|required'
				),
				array(
					'field' => 'review',
					'label' => 'Review',
					'rules' => 'trim|required'
				)
			);
			$this->form_validation->set_rules($validation_rules);

			if($this->form_validation->run() === false){
				$this->data['notifications']['error'] = $this->form_validation->get_errors();
			}
			else{
				$review = array(
					'app_id' => $app['id'],
					'user_id' => $this->data['user_profile']->id,
					'title' => $this->input->post('title'),
					'text' => $this->input->post('review')
				);

				if($this->data['user_app_review']){
					$result = $this->review->update_review($this->data['user_app_review']['_id'], $review);
					$display_id = $this->data['user_app_review']['display_id'];
					$confirm_message = 'Review for ' . $app['name'] . ' edited.';
					$error_message = 'Review for ' . $app['name'] . ' not edited. Please try again.';
				}
				else{
					$result = $this->review->add_review($review);
					$display_id = $result['data']['display_id'];
					$confirm_message = 'Review for ' . $app['name'] . ' added.';
					$error_message = 'Review for ' . $app['name'] . ' not added. Please try again.';
				}

				if($result){
					$this->session->set_flashdata('confirm', $confirm_message);
					redirect('/discussions/review/' . $display_id);
				}
				else{
					$this->data['notifications']['error'] = $error_message;
				}
			}
		}

		$this->data['app'] = $app;

		$this->set_css(array('redactor.css', 'discussions.css'));
		$this->set_js('redactor.js');

		$this->load->view('add_edit_review', $this->data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */