<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class APP_Controller extends CI_Controller
{

  public
    $data = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->config('app', TRUE);
    $this->load->library('Session');
    $this->lang->load('web');
    // $this->load->model('DataModel', 'Data');
    $this->load->library('EncryptionX', null, 'encryption');
    $this->encryption->key($this->config->item('encryption_key', 'app'));    


  }

}