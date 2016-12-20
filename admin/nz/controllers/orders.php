<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class orders extends AppController {

  public 
    $cfg = array();

  public function __construct()
  {
    parent::__construct();
    $this->cfg['title'] = $this->lang->line('Pedidos');
  }

  public function cart()
  {
    $this->cfg['subtitle'] = $this->lang->line('Listado');
    $this->cfg['folder'] = 9;
    $this->load->library("abm", $this->cfg);
  }

  public function coupon()
  {
    $this->cfg['subtitle'] = $this->lang->line('Descuentos');
    $this->cfg['folder'] = 10;
    $this->load->library("abm", $this->cfg);
  }

}