<?php

class CouponModel extends AppModel {

  function __construct()
  {
    parent::__construct();
    $this->table = "coupon";    
  }
  
  public function ListItems()
  {
    $where = $this->ListWhere(true);
    $init = $this->input->post('iDisplayStart') ? $this->input->post('iDisplayStart') : 0;
    $perpage = $this->input->post('iDisplayLength') ? $this->input->post('iDisplayLength') : 10;
    $orderby = $this->input->post('filter-sort-column') ? $this->input->post('filter-sort-column') : $this->mconfig['order-column'];
    $ascdesc = $this->input->post('filter-sort-type') ? $this->input->post('filter-sort-type') : $this->mconfig['order-type'];
    $sql = "SELECT t.id_coupon as id, t.*,
    lj0.type as type    
    FROM {$this->table} as t    
    LEFT JOIN coupon_type lj0 on t.id_type = lj0.id_type      
    WHERE $where 
    ORDER BY `{$orderby}` {$ascdesc} LIMIT {$init}, {$perpage}";
    return $this->db->query($sql)->result();
  }  
  
  public function ListTotal($filter = false)
  {
    $where = $this->ListWhere($filter);
    $sql = "SELECT count(*) as total 
    FROM {$this->table} as t    
    LEFT JOIN coupon_type lj0 on t.id_type = lj0.id_type 
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
    if($this->input->post('filter-id_type'))
      $sql .= " AND t.id_type = '". $this->input->post('filter-id_type') ."'";
    if($this->input->post('filter-active'))
      $sql .= " AND t.active = '1'";
    if($text)
      $sql .= " AND ( t.name like '%{$text}%'  OR  t.code like '%{$text}%'  OR t.id_coupon = '{$text}') ";   
    if($this->input->post('filter-id'))
      $sql .= " AND t.id_coupon = '". $this->input->post('filter-id') ."'";  
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
      'SelectCouponType' => $this->Data->SelectCouponType('', $this->lang->line('Selecciona una opción')),      
    );
  }
  
  public function ValidationRules()
  {
    return array(
      array(
       'field'   => 'id_type', 
       'label'   => $this->lang->line('Tipo'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'name', 
       'label'   => $this->lang->line('Nombre'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'code', 
       'label'   => $this->lang->line('Código'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'value', 
       'label'   => $this->lang->line('Valor'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'total', 
       'label'   => $this->lang->line('Total'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'used', 
       'label'   => $this->lang->line('Usados'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'expire', 
       'label'   => $this->lang->line('Expiración'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'active', 
       'label'   => $this->lang->line('Activo'), 
       'rules'   => 'trim'
      ),
    );
  }
  
  public function Name( $id = 0 )
  {
    $id = $id ? $id : $this->id;
    $sql = "SELECT name as `name`
    FROM {$this->table}

    WHERE id_coupon = '{$id}'";
    $query = $this->db->query($sql);
    $row = $query->row();
    return clean_title($row->name);
  }
  
  public function Duplicate( $id = 0 )
  {    
    $sql = "select * from {$this->table} where id_coupon = '{$id}'";
    $row = $this->db->query($sql)->row_array();  
    if(!$row) return false;
    unset($row['id_coupon']);    
        
    $sql = $this->db->insert_string($this->table, $row );
    $this->db->query($sql); 
    $idn =  $this->db->insert_id();
    return $idn;
  }
  
  public function SavePost()
  {
    if(!$this->MApp->secure->edit) return;
    $data = array(
      'id_type' => $this->input->post('id_type'),
      'name' => $this->input->post('name'),
      'code' => $this->input->post('code'),
      'value' => $this->input->post('value'),
      'total' => $this->input->post('total'),
      'used' => $this->input->post('used'),
      'expire' => human_to_mysql($this->input->post('expire')),
      'active' => $this->input->post('active') ? 1 : 0,
    );
    if( $this->id )
      $sql = $this->db->update_string($this->table, $data, "id_coupon = '{$this->id}'" );
    else
      $sql = $this->db->insert_string($this->table, $data );
    $this->db->query($sql); 
    return $this->id ? $this->id : $this->db->insert_id();
  }
  
  public function Delete( $id = 0 )
  {
    if(!$this->MApp->secure->delete) return false;
    $sql = "DELETE FROM {$this->table}
    WHERE id_coupon = '{$id}'";
    $this->db->query($sql);
    return true;
  }
    
  public function DataElement( $id = 0, $null = false)
  {
    $ret = array();
    if($id)
    {
      $sql = "SELECT t.id_coupon as id, t.*,
      lj0.type as type      
      FROM {$this->table} as t      
      LEFT JOIN coupon_type lj0 on t.id_type = lj0.id_type       
      WHERE t.id_coupon = '{$id}' 
      LIMIT 0, 1";
      $ret = $this->db->query($sql)->row_array();
      if($ret) return $ret;
      if($null) return false;
    }    
    $ret['id_type'] = $this->input->post() ? $this->input->post('id_type') : '';
    $ret['name'] = $this->input->post() ? $this->input->post('name') : '';
    $ret['code'] = $this->input->post() ? $this->input->post('code') : '';
    $ret['value'] = $this->input->post() ? $this->input->post('value') : '';
    $ret['total'] = $this->input->post() ? $this->input->post('total') : '';
    $ret['used'] = $this->input->post() ? $this->input->post('used') : '';
    $ret['expire'] = $this->input->post() ? human_to_mysql($this->input->post('expire')) : date('Y-m-d');
    $ret['active'] = $this->input->post('active') ? 1 : 0;
    return $ret;
  }

}