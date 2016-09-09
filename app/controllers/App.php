<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends APP_Controller {

	public 
		$routes = array(
			'404' => array('section'=> '404', 'theme'=> 'default'),
			'home' => array('section'=> 'home', 'theme'=> 'default', 'pager'=>'/'),
			'ejemplo' => array('section'=> 'example', 'theme'=> 'default', 'pager'=>'ejemplo'),
		);

	public function index( $route = 'home' )
	{		

	    if( !isset( $this->routes[$route] ) )
	        return $this->error404();
	    
	    $data_section = $this->routes[$route];

	    $this->data['section'] = $data_section['section'];
	    $this->data['theme'] = $data_section['theme'];
	    $this->data['theme_path'] = 'themes/'. $this->data['theme'] .'/';

	    $this->lang->load('web');
	    $this->load->helper('date');
	    $this->load->model('DataModel', 'Data');

	    $params = array();

	    if( !$this->input->is_ajax_request() ) {

	      $params['headers']['head-title'] = '';
	      $params['headers']['title'] = '';
	      $params['headers']['description'] = '';
	      $params['headers']['keywords'] = '';

	      $params['headers']['favicon'] = '';
	      $params['headers']['share-image'] = '';

	      $params['header'] = true;
	      $params['html'] = true;
	      $params['footer'] = true;
	    }

	    $this->load->view( $this->data['theme_path'] . "base", $params );

	}


	public function error404()
	{
	    return $this->index('404');
	}

}