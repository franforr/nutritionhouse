<?php

class CategoryModel extends AppModel {

  function __construct()
  {
    parent::__construct();
    $this->table = "product_category";    
  }
  
  public function ListItems()
  {
    $where = $this->ListWhere(true);
    $init = $this->input->post('iDisplayStart') ? $this->input->post('iDisplayStart') : 0;
    $perpage = $this->input->post('iDisplayLength') ? $this->input->post('iDisplayLength') : 10;
    $orderby = $this->input->post('filter-sort-column') ? $this->input->post('filter-sort-column') : $this->mconfig['order-column'];
    $ascdesc = $this->input->post('filter-sort-type') ? $this->input->post('filter-sort-type') : $this->mconfig['order-type'];
    $sql = "SELECT t.id_category as id, t.*,
    lj0.file as fm1file, lj0.id_type as fm1type, lj0.name as fm1name    
    FROM {$this->table} as t    
    LEFT JOIN nz_file lj0 on t.id_file = lj0.id_file      
    WHERE $where 
    ORDER BY `{$orderby}` {$ascdesc} LIMIT {$init}, {$perpage}";
    return $this->db->query($sql)->result();
  }  
  
  public function ListTotal($filter = false)
  {
    $where = $this->ListWhere($filter);
    $sql = "SELECT count(*) as total 
    FROM {$this->table} as t    
    LEFT JOIN nz_file lj0 on t.id_file = lj0.id_file 
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
    if($this->input->post('filter-highlight'))
      $sql .= " AND t.highlight = '1'";
    if($this->input->post('filter-active'))
      $sql .= " AND t.active = '1'";
    if($text)
      $sql .= " AND ( t.category like '%{$text}%'  OR t.id_category = '{$text}') ";   
    if($this->input->post('filter-id'))
      $sql .= " AND t.id_category = '". $this->input->post('filter-id') ."'";  
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
    );
  }
  
  public function ValidationRules()
  {
    return array(
      array(
       'field'   => 'category', 
       'label'   => $this->lang->line('Categoría'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'id_file', 
       'label'   => $this->lang->line('Foto'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'highlight', 
       'label'   => $this->lang->line('Destacada en home'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'num', 
       'label'   => $this->lang->line('Orden'), 
       'rules'   => 'trim|numeric'
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
    $sql = "SELECT category as `name`
    FROM {$this->table}

    WHERE id_category = '{$id}'";
    $query = $this->db->query($sql);
    $row = $query->row();
    return clean_title($row->name);
  }
  
  public function Duplicate( $id = 0 )
  {    
    $sql = "select * from {$this->table} where id_category = '{$id}'";
    $row = $this->db->query($sql)->row_array();  
    if(!$row) return false;
    unset($row['id_category']);    
        
    $sql = $this->db->insert_string($this->table, $row );
    $this->db->query($sql); 
    $idn =  $this->db->insert_id();
    return $idn;
  }
  
  public function SavePost()
  {
    if(!$this->MApp->secure->edit) return;
    $data = array(
      'category' => $this->input->post('category'),
      'id_file' => $this->input->post('id_file') ? $this->input->post('id_file') : false,
      'highlight' => $this->input->post('highlight') ? 1 : 0,
      'num' => $this->input->post('num'),
      'active' => $this->input->post('active') ? 1 : 0,
    );
    if( $this->id )
      $sql = $this->db->update_string($this->table, $data, "id_category = '{$this->id}'" );
    else
      $sql = $this->db->insert_string($this->table, $data );
    $this->db->query($sql); 
    return $this->id ? $this->id : $this->db->insert_id();
  }
  
  public function Delete( $id = 0 )
  {
    if(!$this->MApp->secure->delete) return false;
    $sql = "DELETE FROM {$this->table}
    WHERE id_category = '{$id}'";
    $this->db->query($sql);
    return true;
  }
    
  public function DataElement( $id = 0, $null = false)
  {
    $ret = array();
    if($id)
    {
      $sql = "SELECT t.id_category as id, t.*,
      lj0.file as fm1file, lj0.id_type as fm1type, lj0.name as fm1name      
      FROM {$this->table} as t      
      LEFT JOIN nz_file lj0 on t.id_file = lj0.id_file      
      WHERE t.id_category = '{$id}' 
      LIMIT 0, 1";
      $ret = $this->db->query($sql)->row_array();
      if($ret) return $ret;
      if($null) return false;
    }    
    $ret['category'] = $this->input->post() ? $this->input->post('category') : '';
    $ret['id_file'] = $this->input->post() ? $this->input->post('id_file') : '';
    $ret['highlight'] = $this->input->post('highlight') ? 1 : 0;
    $ret['num'] = $this->input->post() ? $this->input->post('num') : '';
    $ret['active'] = $this->input->post('active') ? 1 : 0;
    return $ret;
  }

}