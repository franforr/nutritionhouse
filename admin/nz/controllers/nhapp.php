<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class nhapp extends AppController {

  public 
    $cfg = array();

  public function __construct()
  {
    parent::__construct();
    $this->cfg['title'] = $this->lang->line('Aplicación');
  }


  public function section()
  {
    $this->cfg['subtitle'] = $this->lang->line('Secciones');
    $this->cfg['folder'] = 2;
    $this->cfg['new-element'] = false;
    $this->load->library("abm", $this->cfg);
  }

  public function faq()
  {
    $this->cfg['subtitle'] = $this->lang->line('FAQ');
    $this->cfg['folder'] = 3;
    $this->load->library("abm", $this->cfg);
  }

  public function gim()
  {
    $this->cfg['subtitle'] = $this->lang->line('Gimnasios');
    $this->cfg['folder'] = 11;
    $this->load->library("abm", $this->cfg);
  }

}