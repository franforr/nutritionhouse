<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataModel extends CI_Model
{
  public 
  	$pconfig = false;

  public function __construct()
  {
		parent::__construct();
		// $languages = array(
		// 	'spanish' => '_es',
		// 	'english' => '_en',
		// );
		// $this->langdb = $languages[$this->config->item('language')];

  //   $this->config = $this->Config();
  }


  // public function GetStain($id)
  // {
  //   $this->db->where("id_stain = {$id}");
  //   $r = $this->db->get('product_stain');

  //   return $r->row();
  // }
  
  // public function GetDownloads() {
  //   $this->db->select("
  //     t.*,
  //     i.file as picture,
  //     i.name as type_file,
  //     f.file as file,
  //     ");

  //   $this->db->where("active = 1");
  //   $this->db->join('downloads_icon di', 'di.id_downloadsicon = t.id_downloadsicon', 'left');
  //   $this->db->join('nz_file i', 'i.id_file = di.id_file', 'left');
  //   $this->db->join('nz_file f', 'f.id_file = t.id_file', 'left');


  //   $r = $this->db->get("downloads as t")->result();

  //   return $r;
  // }

}