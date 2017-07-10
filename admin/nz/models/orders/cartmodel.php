<?php

class CartModel extends AppModel {

  function __construct()
  {
    parent::__construct();
    $this->table = "cart";    
  }
  
  public function ListItems()
  {
    $where = $this->ListWhere(true);
    $init = $this->input->post('iDisplayStart') ? $this->input->post('iDisplayStart') : 0;
    $perpage = $this->input->post('iDisplayLength') ? $this->input->post('iDisplayLength') : 10;
    $orderby = $this->input->post('filter-sort-column') ? $this->input->post('filter-sort-column') : $this->mconfig['order-column'];
    $ascdesc = $this->input->post('filter-sort-type') ? $this->input->post('filter-sort-type') : $this->mconfig['order-type'];
    $sql = "SELECT t.id_cart as id, t.*,
    lj0.name as gim,
    lj1.state as state,
    lj2.shipping as shipping    
    FROM {$this->table} as t    
    LEFT JOIN gim lj0 on t.id_gim = lj0.id_gim      
    LEFT JOIN cart_state lj1 on t.id_state = lj1.id_state      
    LEFT JOIN cart_shipping lj2 on t.id_shipping = lj2.id_shipping      
    WHERE $where 
    ORDER BY `{$orderby}` {$ascdesc} LIMIT {$init}, {$perpage}";
    return $this->db->query($sql)->result();
  }  
  
  public function ListTotal($filter = false)
  {
    $where = $this->ListWhere($filter);
    $sql = "SELECT count(*) as total 
    FROM {$this->table} as t    
    LEFT JOIN gim lj0 on t.id_gim = lj0.id_gim     
    LEFT JOIN cart_state lj1 on t.id_state = lj1.id_state     
    LEFT JOIN cart_shipping lj2 on t.id_shipping = lj2.id_shipping 
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
    if($this->input->post('filter-id_gim'))
      $sql .= " AND t.id_gim = '". $this->input->post('filter-id_gim') ."'";
    if($this->input->post('filter-id_state'))
      $sql .= " AND t.id_state = '". $this->input->post('filter-id_state') ."'";
    if($this->input->post('filter-id_shipping'))
      $sql .= " AND t.id_shipping = '". $this->input->post('filter-id_shipping') ."'";
    if($text)
      $sql .= " AND ( t.name like '%{$text}%'  OR  t.lastname like '%{$text}%'  OR  t.address like '%{$text}%'  OR  t.postal_code like '%{$text}%'  OR  t.province like '%{$text}%'  OR  t.city like '%{$text}%'  OR  t.phone like '%{$text}%'  OR  t.mail like '%{$text}%'  OR  t.comments like '%{$text}%'  OR  t.coupon_1 like '%{$text}%'  OR t.id_cart = '{$text}') ";   
    if($this->input->post('filter-id'))
      $sql .= " AND t.id_cart = '". $this->input->post('filter-id') ."'";  
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
      'SelectGim' => $this->Data->SelectGim('', $this->lang->line('Selecciona una opción')),
      'SelectCartState' => $this->Data->SelectCartState('', $this->lang->line('Selecciona una opción')),
      'SelectCartShipping' => $this->Data->SelectCartShipping('', $this->lang->line('Selecciona una opción')),      
    );
  }
  
  public function ValidationRules()
  {
    return array(
      array(
       'field'   => 'id_gim', 
       'label'   => $this->lang->line('Gimnasio'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'id_state', 
       'label'   => $this->lang->line('Estado'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'id_shipping', 
       'label'   => $this->lang->line('Envío'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'name', 
       'label'   => $this->lang->line('Nombre'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'lastname', 
       'label'   => $this->lang->line('Apellido'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'address', 
       'label'   => $this->lang->line('Dirección'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'postal_code', 
       'label'   => $this->lang->line('Código Postal'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'province', 
       'label'   => $this->lang->line('Provincia'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'city', 
       'label'   => $this->lang->line('Ciudad'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'phone', 
       'label'   => $this->lang->line('Teléfono'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'mail', 
       'label'   => $this->lang->line('E-mail'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'created', 
       'label'   => $this->lang->line('Fecha creación'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'modified', 
       'label'   => $this->lang->line('Fecha modificación'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'comments', 
       'label'   => $this->lang->line('Comentarios'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'coupon_1', 
       'label'   => $this->lang->line('Descuento'), 
       'rules'   => 'trim'
      ),
      array(
       'field'   => 'subtotal', 
       'label'   => $this->lang->line('Subtotal'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'desc1', 
       'label'   => $this->lang->line('Descuento'), 
       'rules'   => 'trim|numeric'
      ),
      array(
       'field'   => 'total', 
       'label'   => $this->lang->line('Total'), 
       'rules'   => 'trim|numeric'
      ),
    );
  }
  
  public function Name( $id = 0 )
  {
    $id = $id ? $id : $this->id;
    $sql = "SELECT name as `name`
    FROM {$this->table}

    WHERE id_cart = '{$id}'";
    $query = $this->db->query($sql);
    $row = $query->row();
    return clean_title($row->name);
  }
  
  public function Duplicate( $id = 0 )
  {    
    $sql = "select * from {$this->table} where id_cart = '{$id}'";
    $row = $this->db->query($sql)->row_array();  
    if(!$row) return false;
    unset($row['id_cart']);    
        
    $sql = $this->db->insert_string($this->table, $row );
    $this->db->query($sql); 
    $idn =  $this->db->insert_id();
    return $idn;
  }
  
  public function SavePost()
  {
    if(!$this->MApp->secure->edit) return;
    $data = array(
                  'id_state' => $this->input->post('id_state'),
                  'comments' => $this->input->post('comments'),
                  );
    if( $this->id )
      $sql = $this->db->update_string($this->table, $data, "id_cart = '{$this->id}'" );
    else
      $sql = $this->db->insert_string($this->table, $data );
    $this->db->query($sql); 
    return $this->id ? $this->id : $this->db->insert_id();
  }
  
  public function Delete( $id = 0 )
  {
    if(!$this->MApp->secure->delete) return false;
    $sql = "DELETE FROM {$this->table}
    WHERE id_cart = '{$id}'";
    $this->db->query($sql);
    return true;
  }
    
  public function DataElement( $id = 0, $null = false)
  {
    $ret = array();
    if($id)
    {
      $sql = "SELECT t.id_cart as id, t.*,
      lj0.name as gim,
      lj1.state as state,
      lj2.shipping as shipping,
      lj3.name as coupon,      
      lj3.id_type as coupon_type,      
      lj3.value as coupon_value      
      FROM {$this->table} as t      
      LEFT JOIN gim lj0 on t.id_gim = lj0.id_gim       
      LEFT JOIN cart_state lj1 on t.id_state = lj1.id_state       
      LEFT JOIN cart_shipping lj2 on t.id_shipping = lj2.id_shipping       
      LEFT JOIN coupon lj3 on t.id_coupon = lj3.id_coupon       
      WHERE t.id_cart = '{$id}' 
      LIMIT 0, 1";
      $ret = $this->db->query($sql)->row_array();

      $ret['items'] = $this->CartItems($id);
      if($ret) return $ret;
      if($null) return false;
    }    
    $ret['id_gim'] = $this->input->post() ? $this->input->post('id_gim') : '';
    $ret['id_state'] = $this->input->post() ? $this->input->post('id_state') : '';
    $ret['id_shipping'] = $this->input->post() ? $this->input->post('id_shipping') : '';
    $ret['name'] = $this->input->post() ? $this->input->post('name') : '';
    $ret['lastname'] = $this->input->post() ? $this->input->post('lastname') : '';
    $ret['address'] = $this->input->post() ? $this->input->post('address') : '';
    $ret['postal_code'] = $this->input->post() ? $this->input->post('postal_code') : '';
    $ret['province'] = $this->input->post() ? $this->input->post('province') : '';
    $ret['city'] = $this->input->post() ? $this->input->post('city') : '';
    $ret['phone'] = $this->input->post() ? $this->input->post('phone') : '';
    $ret['mail'] = $this->input->post() ? $this->input->post('mail') : '';
    $ret['created'] = $this->input->post() ? $this->input->post('created') : '';
    $ret['modified'] = $this->input->post() ? $this->input->post('modified') : '';
    $ret['comments'] = $this->input->post() ? $this->input->post('comments') : '';
    $ret['coupon_1'] = $this->input->post() ? $this->input->post('coupon_1') : '';
    $ret['subtotal'] = $this->input->post() ? $this->input->post('subtotal') : '';
    $ret['desc1'] = $this->input->post() ? $this->input->post('desc1') : '';
    $ret['total'] = $this->input->post() ? $this->input->post('total') : '';

    return $ret;
  }
    
  public function CartItems( $id = 0 )
  {
    if($id)
    {
      $sql = "SELECT 
           t.id_item as id_item,
           t.id_product as id_product,
           t.items as items,
           t.cost as cost,
           p.title as title,
           p.text as text,
           pc.category as category,
           ps.size as size,
           nf.file as file
      FROM {$this->table}_item as t      
      LEFT JOIN product p on t.id_product = p.id_product       
      LEFT JOIN product_category pc on p.id_category = pc.id_category       
      LEFT JOIN product_size ps on p.id_size = ps.id_size    
      LEFT JOIN nz_file nf on p.id_file = nf.id_file    
      WHERE t.id_cart = '{$id}' AND t.active = 1";
      $ret = $this->db->query($sql)->result();
      if($ret) return $ret;
    }    

    return $ret;
  }

}