<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MailModel extends CI_Model {
  

	private 
		$mailjet = false;
		//$FromEmail = '';
		//$FromName = '';

	public function __construct()
  {
    parent::__construct();
  	require_once APPPATH . 'third_party/GuzzleHttp/functions_include.php';
		require_once APPPATH . 'third_party/GuzzleHttp/Psr7/functions_include.php';
		require_once APPPATH . 'third_party/GuzzleHttp/Promise/functions_include.php';
		require_once APPPATH . 'third_party/Psr/bootstrap.php';
		require_once APPPATH . 'third_party/GuzzleHttp/bootstrap.php';
		require_once APPPATH . 'third_party/Mailjet/bootstrap.php';
		$APIPublicKey = 'ec4f81b2894ce3aeb5f987effcf10fd5';
		$APISecretKey = '80fc6f04059c6c5231924f124c351041';
		$this->mailjet = new Mailjet\Client($APIPublicKey, $APISecretKey);
		//$this->FromEmail = 'hola@inmediative.com';
		//$this->FromName = 'Economicar';
  }

  	public function SendBasic( $recipients,$title,$message )
  	{
	    $data = array(
	    	'title'=>$title,
	    	'message'=>$message,
	    );
		$html = $this->load->view('mail/basic', $data, true);
		$recipients_array = array();
		foreach ($recipients as $key => $value) {
			$recipients_array[] = array('Email'=>$value);
		}

		$body = array(
		  	'FromEmail' => 'hola@inmediative.com',
		  	'FromName' => 'Nutrition House',
		  	'Subject' => $title,
		  	'Text-part' => strip_tags($html),
		  	'Html-part' => $html,
		  	'Recipients' => $recipients_array
		);

		$response = $this->mailjet->post(Mailjet\Resources::$Email, ['body' => $body]);

		return $response;

	}

	public function SendCart( $recipients,$cart,$title,$message )
	{
	    $data = array(
	    	'title'=>false,
	    	'message'=>$message,
	    	'cart'=>$cart,
	    );

		$html = $this->load->view('mail/cart', $data, true);
		$recipients_array = array();
		foreach ($recipients as $key => $value) {
			$recipients_array[] = array('Email'=>$value);
		}

		$body = array(
		  	'FromEmail' => 'hola@inmediative.com',
		  	'FromName' => 'Nutrition House',
		  	'Subject' => $title,
		  	'Text-part' => strip_tags($html),
		  	'Html-part' => $html,
		  	'Recipients' => $recipients_array
		);

		$response = $this->mailjet->post(Mailjet\Resources::$Email, ['body' => $body]);

		return $response;

  	}

}