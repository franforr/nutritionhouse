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
	    $this->data['icon_theme'] = 'default';
	    $this->data['theme_path'] = 'themes/'. $this->data['theme'] .'/';

	    $this->lang->load('web');
	    $this->load->helper('date');
	    $this->load->model('DataModel', 'Data');

	    if( !$this->input->is_ajax_request() ) {

	      $this->data['headers']['head-title'] = '';
	      $this->data['headers']['title'] = '';
	      $this->data['headers']['description'] = '';
	      $this->data['headers']['keywords'] = '';

	      $this->data['headers']['favicon'] = '';
	      $this->data['headers']['share-image'] = '';

	      $this->data['header'] = true;
	      $this->data['html'] = true;
	      $this->data['footer'] = true;
	    }

	    $this->load->view( $this->data['theme_path'] . "base", $this->data );

	}


	public function error404()
	{
	    return $this->index('404');
	}

}