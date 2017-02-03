<?php

class SliderModel extends AppModel {

  function __construct()
  {
    parent::__construct();
    $this->table = "slider";    
  }
  
  public function ListItems()
  {
    $where = $this->ListWhere(true);
    $init = $this->input->post('iDisplayStart') ? $this->input->post('iDisplayStart') : 0;
    $perpage = $this->input->post('iDisplayLength') ? $this->input->post('iDisplayLength') : 10;
    $orderby = $this->input->post('filter-sort-column') ? $this->input->post('filter-sort-column') : $this->mconfig['order-column'];
    $ascdesc = $this->input->post('filter-sort-type') ? $this->input->post('filter-sort-type') : $this->mconfig['order-type'];
    $sql = "SELECT t.id_slider as id, t.*,
    lj0.title as product,
    lj1.file as fm1file, lj1.id_type as fm1type, lj1.name as fm1name    
    FROM {$this->table} as t    
    LEFT JOIN product lj0 on t.id_product = lj0.id_product      
    LEFT JOIN nz_file lj1 on t.id_file = lj1.id_file      
    WHERE $where 
    ORDER BY `{$orderby}` {$ascdesc} LIMIT {$init}, {$perpage}";
    return $this->db->query($sql)->result();
  }  
  
  public function ListTotal($filter = false)
  {
    $where = $this->ListWhere($filter);
    $sql = "SELECT count(*) as total 
    FROM {$this->table} as t    
    LEFT JOIN product lj0 on t.id_product = lj0.id_product     
    LEFT JOIN nz_file lj1 on t.id_file = lj1.id_file 
    WHERE $where";
    return $this->db->query($sql)->row()->total;
  }
  
  private function ListWhere($filter = false)
  {
    $sql = "1";
    if(!$filter) 
      return $sql;  
    $text = $this->input->post('filter-text') ? $this->input->post('filter-text') : false;          
    if(!$text)      
      $text = $this->input->post('sSearch') ? $this->input->post('sSearch') : false;
    if($this->input->post('filter-id_product'))
      $sql .= " AND t.id_product = '". $this->input->post('filter-id_product') ."'";
    if($text)
      $sql .= " AND ( t.title like '%{$text}%'  OR  t.subtitle like '%{$text}%'  OR t.id_slider = '{$text}') ";   
    if($this->input->post('filter-id'))
      $sql .= " AND t.id_slider = '". $this->input->post('filter-id') ."'";  
    return $sql;
  }  
  
  public function JSON()
  {
    $total = $this->ListTotal();
    $total2 = $this->ListTotal(true);
    $json = $this->ListItems();
    $sEcho = $this->input->post('sEcho');
    return '{"sEcho":' . $sEcho . ',"iTotalRecords": '. $total .',"iTotalDisplayRecords": '. $total2 .',"aaData":' . json_encode($json) . '}';
  }
  
  public function DataSelects()
  {
    return array(
      'SelectProduct' => $this->Data->SelectProduct('', $this->lang->line('Selecciona una opción')),      
    );
  }
  
  public function ValidationRules()
  {
    return array(
      array(
       'field'   => 'id_product', 
       'label'   => $this->lang->line('Producto'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'title', 
       'label'   => $this->lang->line('Titulo'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'subtitle', 
       'label'   => $this->lang->line('Subtitulo'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'id_file', 
       'label'   => $this->lang->line('File'), 
       'rules'   => 'trim'
      ),
    );
  }
  
  public function Name( $id = 0 )
  {
    $id = $id ? $id : $this->id;
    $sql = "SELECT title as `name`
    FROM {$this->table}

    WHERE id_slider = '{$id}'";
    $query = $this->db->query($sql);
    $row = $query->row();
    return clean_title($row->name);
  }
  
  public function Duplicate( $id = 0 )
  {    
    $sql = "select * from {$this->table} where id_slider = '{$id}'";
    $row = $this->db->query($sql)->row_array();  
    if(!$row) return false;
    unset($row['id_slider']);    
        
    $sql = $this->db->insert_string($this->table, $row );
    $this->db->query($sql); 
    $idn =  $this->db->insert_id();
    return $idn;
  }
  
  public function SavePost()
  {
    if(!$this->MApp->secure->edit) return;
    $data = array(
      'id_product' => $this->input->post('id_product'),
      'title' => $this->input->post('title'),
      'subtitle' => $this->input->post('subtitle'),
      'id_file' => $this->input->post('id_file'),
      'num' => $this->input->post('num'),
      'active' => $this->input->post('active'),
    );
    if( $this->id )
      $sql = $this->db->update_string($this->table, $data, "id_slider = '{$this->id}'" );
    else
      $sql = $this->db->insert_string($this->table, $data );
    $this->db->query($sql); 
    return $this->id ? $this->id : $this->db->insert_id();
  }
  
  public function Delete( $id = 0 )
  {
    if(!$this->MApp->secure->delete) return false;
    $sql = "DELETE FROM {$this->table}
    WHERE id_slider = '{$id}'";
    $this->db->query($sql);
    return true;
  }
    
  public function DataElement( $id = 0, $null = false)
  {
    $ret = array();
    if($id)
    {
      $sql = "SELECT t.id_slider as id, t.*,
      lj0.title as product,
      lj1.file as fm1file, lj1.id_type as fm1type, lj1.name as fm1name      
      FROM {$this->table} as t      
      LEFT JOIN product lj0 on t.id_product = lj0.id_product       
      LEFT JOIN nz_file lj1 on t.id_file = lj1.id_file      
      WHERE t.id_slider = '{$id}' 
      LIMIT 0, 1";
      $ret = $this->db->query($sql)->row_array();
      if($ret) return $ret;
      if($null) return false;
    }    
    $ret['id_product'] = $this->input->post() ? $this->input->post('id_product') : '';
    $ret['title'] = $this->input->post() ? $this->input->post('title') : '';
    $ret['subtitle'] = $this->input->post() ? $this->input->post('subtitle') : '';
    $ret['id_file'] = $this->input->post() ? $this->input->post('id_file') : '';
    $ret['active'] = $this->input->post() ? $this->input->post('active') : '';
    $ret['num'] = $this->input->post() ? $this->input->post('num') : '';
    return $ret;
  }

}