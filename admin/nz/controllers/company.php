<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class company extends AppController {

  public 
    $cfg = array();

  public function __construct()
  {
    parent::__construct();
    $this->cfg['title'] = $this->lang->line('Empresas');
  }

  public function companyfn()
  {
    $this->cfg['subtitle'] = $this->lang->line('Empresas');
    $this->cfg['folder'] = 29;
    $this->load->library("abm", $this->cfg);
  }

}