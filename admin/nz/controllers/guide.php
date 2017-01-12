<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class guide extends AppController {

  public 
    $cfg = array();

  public function __construct()
  {

    parent::__construct();
    $this->cfg['title'] = $this->lang->line('Productos');
  }


  public function product()
  {
    $this->cfg['subtitle'] = $this->lang->line('Listado');
    $this->cfg['folder'] = 5;
    $this->load->library("abm", $this->cfg);
  }

  public function category()
  {
    $this->cfg['subtitle'] = $this->lang->line('Categorías');
    $this->cfg['folder'] = 6;
    $this->load->library("abm", $this->cfg);
  }

  public function size()
  {
    $this->cfg['subtitle'] = $this->lang->line('Tamaños');
    $this->cfg['folder'] = 7;
    $this->load->library("abm", $this->cfg);
  }

}