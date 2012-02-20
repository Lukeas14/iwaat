<?php
require_once(APPPATH . 'libraries/swift/swift_required.php');

class Swift_email{
	
	public $transport;
	public $swift;
	public $failures;
	
	function __construct(){
		$this->transport = Swift_SmtpTransport::newInstance(SENDGRID_HOST, SENDGRID_PORT);
		$this->transport->setUsername(SENDGRID_USER);
		$this->transport->setPassword(SENDGRID_PASS);
		
		$this->swift = Swift_Mailer::newInstance($this->transport);
	}
	
	public function send_email($params){
		$message = new Swift_Message($params['subject']);
		$message->setFrom($params['from']);
		$message->setBody($params['html'], 'text/html');
		$message->setTo($params['to']);
		$message->addPart($params['text'], 'text/plain');
		
		if($recipients = $this->swift->send($message, $failures)){
			return true;
		}
		else{
			$this->failures = $failures;
			return false;
		}
	}
	
}