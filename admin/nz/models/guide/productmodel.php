<?php

class ProductModel extends AppModel {

  function __construct()
  {
    parent::__construct();
    $this->table = "product";    
  }
  
  public function ListItems()
  {
    $where = $this->ListWhere(true);
    $init = $this->input->post('iDisplayStart') ? $this->input->post('iDisplayStart') : 0;
    $perpage = $this->input->post('iDisplayLength') ? $this->input->post('iDisplayLength') : 10;
    $orderby = $this->input->post('filter-sort-column') ? $this->input->post('filter-sort-column') : $this->mconfig['order-column'];
    $ascdesc = $this->input->post('filter-sort-type') ? $this->input->post('filter-sort-type') : $this->mconfig['order-type'];
    $sql = "SELECT t.id_product as id, t.*,
    lj0.category as category,
    lj1.state as state,
    lj2.size as size,
    lj3.file as fm1file, lj3.id_type as fm1type, lj3.name as fm1name, 
    (select count(*) as total from nz_gallery_file gf where gf.id_gallery  = t.id_gallery) as fmg1    
    FROM {$this->table} as t    
    LEFT JOIN product_category lj0 on t.id_category = lj0.id_category      
    LEFT JOIN product_state lj1 on t.id_state = lj1.id_state      
    LEFT JOIN product_size lj2 on t.id_size = lj2.id_size      
    LEFT JOIN nz_file lj3 on t.id_file = lj3.id_file      
    WHERE $where 
    ORDER BY `{$orderby}` {$ascdesc} LIMIT {$init}, {$perpage}";
    return $this->db->query($sql)->result();
  }  
  
  public function ListTotal($filter = false)
  {
    $where = $this->ListWhere($filter);
    $sql = "SELECT count(*) as total 
    FROM {$this->table} as t    
    LEFT JOIN product_category lj0 on t.id_category = lj0.id_category     
    LEFT JOIN product_state lj1 on t.id_state = lj1.id_state     
    LEFT JOIN product_size lj2 on t.id_size = lj2.id_size     
    LEFT JOIN nz_file lj3 on t.id_file = lj3.id_file 
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
    if($this->input->post('filter-id_category'))
      $sql .= " AND t.id_category = '". $this->input->post('filter-id_category') ."'";
    if($this->input->post('filter-id_state'))
      $sql .= " AND t.id_state = '". $this->input->post('filter-id_state') ."'";
    if($this->input->post('filter-id_size'))
      $sql .= " AND t.id_size = '". $this->input->post('filter-id_size') ."'";
    if($this->input->post('filter-id_gallery'))
      $sql .= " AND t.id_gallery = '". $this->input->post('filter-id_gallery') ."'";
    if($this->input->post('filter-promotion'))
      $sql .= " AND t.promotion = '1'";
    if($this->input->post('filter-highlight'))
      $sql .= " AND t.highlight = '1'";
    if($this->input->post('filter-active'))
      $sql .= " AND t.active = '1'";
    if($text)
      $sql .= " AND ( t.title like '%{$text}%'  OR  t.text like '%{$text}%'  OR  t.related like '%{$text}%'  OR t.id_product = '{$text}') ";   
    if($this->input->post('filter-id'))
      $sql .= " AND t.id_product = '". $this->input->post('filter-id') ."'";  
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
      'SelectProductCategory' => $this->Data->SelectProductCategory('', $this->lang->line('Selecciona una opción')),
      'SelectProductState' => $this->Data->SelectProductState('', $this->lang->line('Selecciona una opción')),
      'SelectProductSize' => $this->Data->SelectProductSize('', $this->lang->line('Selecciona una opción')),      
    );
  }
  
  public function ValidationRules()
  {
    return array(
      array(
       'field'   => 'id_category', 
       'label'   => $this->lang->line('Categoría'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'id_state', 
       'label'   => $this->lang->line('Stock'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'id_size', 
       'label'   => $this->lang->line('Tamaño'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'title', 
       'label'   => $this->lang->line('Título'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'text', 
       'label'   => $this->lang->line('Descripción'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'cost', 
       'label'   => $this->lang->line('Precio'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'id_file', 
       'label'   => $this->lang->line('Imagen'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'id_gallery', 
       'label'   => $this->lang->line('Galería'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'promotion', 
       'label'   => $this->lang->line('Promoción'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'highlight', 
       'label'   => $this->lang->line('Destacado en home'), 
       'rules'   => 'trim'
      ),
      // array(
      //  'field'   => 'related', 
      //  'label'   => $this->lang->line('Productos complementarios'), 
      //  // 'rules'   => 'trim'
      // ),
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
    $sql = "SELECT title as `name`
    FROM {$this->table}

    WHERE id_product = '{$id}'";
    $query = $this->db->query($sql);
    $row = $query->row();
    return clean_title($row->name);
  }
  
  public function Duplicate( $id = 0 )
  {    
    $sql = "select * from {$this->table} where id_product = '{$id}'";
    $row = $this->db->query($sql)->row_array();  
    if(!$row) return false;
    unset($row['id_product']);    
        if($row['id_gallery'])
    {
      $oldID = $row['id_gallery'];
      $row['id_gallery'] = $this->MApp->CreateGallery();
      $this->MApp->DuplicateGallery($oldID,$row['id_gallery']);
    }    
        
    $sql = $this->db->insert_string($this->table, $row );
    $this->db->query($sql); 
    $idn =  $this->db->insert_id();
    return $idn;
  }
  
  public function SavePost()
  {
    if(!$this->MApp->secure->edit) return;
    $data = array(
      'id_category' => $this->input->post('id_category'),
      'id_state' => $this->input->post('id_state'),
      'id_size' => $this->input->post('id_size'),
      'title' => $this->input->post('title'),
      'text' => $this->input->post('text'),
      'cost' => $this->input->post('cost'),
      'id_file' => $this->input->post('id_file') ? $this->input->post('id_file') : false,
      'id_gallery' => $this->input->post('id_gallery') ? $this->input->post('id_gallery') : false,
      'promotion' => $this->input->post('promotion') ? 1 : 0,
      'highlight' => $this->input->post('highlight') ? 1 : 0,
      'related' => json_encode($this->input->post('related')),
      'related_gim' => json_encode($this->input->post('related_gim')),
      'more_categories' => json_encode($this->input->post('more_categories')),
      'active' => $this->input->post('active') ? 1 : 0,
    );
    $gitems = explode(',', $this->input->post('id_gallery-items'));
    if($data['id_gallery'])
      $this->MApp->EmptyGallery($data['id_gallery']);
    if(count($gitems))
    {
      if(!$this->input->post('id_gallery'))
        $data['id_gallery'] = $this->MApp->CreateGallery();
      $this->MApp->AddGalleryItems($data['id_gallery'], $gitems);
    }    
    if( $this->id )
      $sql = $this->db->update_string($this->table, $data, "id_product = '{$this->id}'" );
    else
      $sql = $this->db->insert_string($this->table, $data );
    $this->db->query($sql); 
    return $this->id ? $this->id : $this->db->insert_id();
  }
  
  public function Delete( $id = 0 )
  {
    if(!$this->MApp->secure->delete) return false;
    $sql = "DELETE FROM {$this->table}
    WHERE id_product = '{$id}'";
    $this->db->query($sql);
    $this->MApp->DeleteGallery($data['id_gallery']);
    return true;
  }
    
  public function DataElement( $id = 0, $null = false)
  {
    $ret = array();
    if($id)
    {
      $sql = "SELECT t.id_product as id, t.*,
      lj0.category as category,
      lj1.state as state,
      lj2.size as size,
      lj3.file as fm1file, lj3.id_type as fm1type, lj3.name as fm1name      
      FROM {$this->table} as t      
      LEFT JOIN product_category lj0 on t.id_category = lj0.id_category       
      LEFT JOIN product_state lj1 on t.id_state = lj1.id_state       
      LEFT JOIN product_size lj2 on t.id_size = lj2.id_size       
      LEFT JOIN nz_file lj3 on t.id_file = lj3.id_file      
      WHERE t.id_product = '{$id}' 
      LIMIT 0, 1";
      $ret = $this->db->query($sql)->row_array();
      if($ret) return $ret;
      if($null) return false;
    }    
    $ret['id_category'] = $this->input->post() ? $this->input->post('id_category') : '';
    $ret['id_state'] = $this->input->post() ? $this->input->post('id_state') : '';
    $ret['id_size'] = $this->input->post() ? $this->input->post('id_size') : '';
    $ret['title'] = $this->input->post() ? $this->input->post('title') : '';
    $ret['text'] = $this->input->post() ? $this->input->post('text') : '';
    $ret['cost'] = $this->input->post() ? $this->input->post('cost') : '';
    $ret['id_file'] = $this->input->post() ? $this->input->post('id_file') : '';
    $ret['id_gallery'] = $this->input->post() ? $this->input->post('id_gallery') : '';
    $ret['promotion'] = $this->input->post('promotion') ? 1 : 0;
    $ret['highlight'] = $this->input->post('highlight') ? 1 : 0;
    $ret['related'] = $this->input->post() ? $this->input->post('related') : false;
    $ret['more_categories'] = $this->input->post() ? $this->input->post('more_categories') : false;
    $ret['active'] = $this->input->post('active') ? 1 : 0;
    return $ret;
  }
  
  public function DataCategory( $id = 0, $null = false)
  {
    $ret = array();
    if($id)
    {
      $sql = "SELECT t.id_category as id, t.*,
      lj0.file as fm1file, lj0.id_type as fm1type, lj0.name as fm1name      
      FROM {$this->table}_category as t      
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