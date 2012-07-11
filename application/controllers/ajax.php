<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller {
	
	private $data = array();
	
	public function app_discussions(){
		$this->load->model('discussion');

		$this->data['app_id'] = $this->input->get('app_id');

		if(empty($this->data['app_id']) || !is_numeric($this->data['app_id'])){
			show_404();
		}

		header('Content-type: application/json');

		$options = array(
			'app_id' => $this->input->get('app_id'),
			'type' => 'blog_post'
		);

		$app_discussions = $this->discussion->get_app_discussions($options);
		
		$discussions = array();
		foreach($app_discussions as $discussion){
			$discussions[] = $discussion;
		}
		//echo"<pre>";print_r(iterator_to_array($app_discussions));echo"</pre>";
		//exit();
		echo json_encode($discussions);
		exit();

		$test = array(
		    array(
		    	'id'=>4324, 
		    	'state'=>'expanded',
		    	'type'=>'review',
		    	'title'=>'Califonifdsfdsfa Institute of Technology',
		    	'text'=>'oijdfjoij fjefoi efjewif wejfoie fio',
		        'source'=>'iwaat',
		        'username' => 'John Doe',
		        'user_slug'=>'john_doe',
		    	'user_avatar_url' => 'http://wiseheartdesign.com/page_attachments/0000/0062/default-avatar.png',
		        'vote_score' => 0,
		        'time_posted' => '2012-04-13 07:22:42',
		        'comments' => array(
		             array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),        )
		    ),
		    array(
		    	'id'=>4224, 
		    	'state'=>'expanded', 
		    	'type'=>'blog_post', 
		    	'title'=>'California State University - Dominguez Hills', 
		    	'text'=>'Lorem ipsume oijfoids jfoijdsoifoidsjf oijoi jfpjfpi3 iodsjf oisjfoidjfoiewofninvcvnewo Lorem ipsume oijfoids jfoijdsoifoidsjf oijoi jfpjfpi3 iodsjf oisjfoidjfoiewofninvcvnewo  fLorem ipsume oijfoids jfoijdsoifoidsjf oijoi jfpjfpi3 iodsjf oisjfoidjfoiewofninvcvnewo  f foi oihfoiewh foewhf oewhf',
		        'source'=>'iwaat',
		        'username' => 'Jane Doe',
		        'user_slug'=>'jane_doe',
		    	'user_avatar_url' => 'http://www.travelpod.com/bin/dashboard/default-avatar-female.png',
		        'vote_score' => 13,
		        'time_posted' => '2009-06-13 07:22:42',
		        'comments' => array(
		            array('id'=>323)
		        )
		    ),
		    array(
		    	'id'=>42324, 
		    	'state'=>'expanded', 
		    	'type'=>'review', 
		    	'title'=>'University of Southern California', 
		    	'text'=>'oijdfjoij fjefoi efjewif wejfoie fio',
		        'source'=>'iwaat',
		        'username' => 'Scott Smith',
		        'user_slug'=>'scott_smith',
		    	'user_avatar_url' => 'http://wiseheartdesign.com/page_attachments/0000/0062/default-avatar.png',
		        'vote_score' => 2,
		        'time_posted' => '2012-01-13 18:12:42',
		        'comments' => array(
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323),
		            array('id'=>323)
		        )
		    ),
		    array(
		    	'id'=>411324, 
		    	'state'=>'expanded', 
		    	'type'=>'review', 
		    	'title'=>'University of Southern California', 
		    	'text'=>'oijdfjoij fjefoi efjewif wejfoie fio',
		        'source'=>'iwaat',
		        'username' => 'Carol Rodriguez',
		        'user_slug'=>'carol_rodriguez21',
		    	'user_avatar_url' => 'http://wiseheartdesign.com/page_attachments/0000/0062/default-avatar.png',
		        'vote_score' => 4,
		        'time_posted' => '2012-06-29 12:33:42',
		        'comments' => array(
		            array('id'=>323),
		            array('id'=>323)
		        )
		    ),
		    array(
		        'id'=>4113224, 
		        'state'=>'expanded', 
		        'type'=>'review', 
		        'title'=>'University of Southern California', 
		        'text'=>'oijdfjoij fjefoi efjewif wejfoie fio',
		        'source'=>'iwaat',
		        'username' => 'Carol Rodriguez',
		        'user_slug'=>'carol_rodriguez21',
		        'user_avatar_url' => 'http://wiseheartdesign.com/page_attachments/0000/0062/default-avatar.png',
		        'vote_score' => 4,
		        'time_posted' => '2012-06-29 12:33:42',
		        'comments' => array(
		            array('id'=>323)
		        )
		    ),
		);
		echo json_encode($test);
		return;
	}

}