<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CartModel extends CI_Model
{
  public
    $table = 'cart',
    $id = 0,
    $items = array(),
    $gim = array(),
    $coupon = array(),
    $subtotal = 0,
    $gim_discount = 0,
    $id_province = 0,
    $discount = 0,
    $shipping_cost = 0,
    $gim_comission = 0,
    $discount_per_products = 0,
    $id_gim = 0,
    $total = 0;


  public function Start($id_cart = 0)
  {
    $this->id = $id_cart;

    if ($this->id == 0) {
      $this->id = $this->CreateCart();

      return $this;
    } else {
      $cart = $this->GetCart($this->id);
      if( ! $cart ) {

       return $this->Start(0);
      } else {
        return $this;
      }
    }

  }

  public function CreateCart()
  {
    $data = array(
      'created' => date('Y-m-d H:i:s'),
      'id_state' => 1
    );
    $sql = $this->db->insert_string('cart', $data);
    $this->db->query($sql);
    $id = $this->db->insert_id();

    $this->db->where("t.id_cart = '{$id}'");
    $update = $this->db->update('cart as t',array( 
      'code'=>str_pad($id, 5, 0, STR_PAD_LEFT)
    ));
    
    return $id;
  }

  public function GetCart( $id = 0 )
  {
    $this->id = $id ? $id : $this->id;

    $this->db->select("t.code,
                       t.comments,
                       t.subtotal,
                       t.total,
                       t.desc1,
                       t.coupon_1,
                       t.modified,
                       t.id_coupon,
                       t.id_gim,
                       t.id_province,
                       t.id_shipping,
                       t.name,
                       t.lastname,
                       t.address,
                       t.postal_code,
                       t.city,
                       t.phone,
                       t.mail,
                       pvs.province,
                       pvs.shipping as shipping_cost,
                       cst.state");
    $this->db->where("t.id_cart = '{$this->id}'");
    $this->db->join('cart_shipping cs', 't.id_shipping = cs.id_shipping', 'left');
    $this->db->join('provinces pvs', 't.id_province = pvs.id_province', 'left');
    $this->db->join('cart_state cst', 't.id_state = cst.id_state', 'left');
    $data = $this->db->get('cart t')->row();

// var_dump($data);die;

    if (! $data) 
     return false;
   
    $this->id_cart = $this->id;
    $this->id_gim = $data->id_gim;
    $this->id_province = $data->id_province;
    $this->id_shipping = $data->id_shipping;
    $this->name = $data->name;
    $this->lastname = $data->lastname;
    $this->address = $data->address;
    $this->postal_code = $data->postal_code;
    $this->city = $data->city;
    $this->phone = $data->phone;
    $this->mail = $data->mail;
    $this->comments = $data->comments;
    $this->total = $data->total;
    $this->subtotal = $data->subtotal;
    $this->discount_percent = $data->desc1;
    $this->discount_money = $data->coupon_1;
    $this->modified = $data->modified;
    $this->state = $data->state;
    $this->id_coupon = $data->id_coupon;
    $this->province = $data->province;
    $this->shipping_cost = $data->shipping_cost;
    $this->coupon = $this->GetCoupon(0,1,$this->id_coupon);
    $this->items = $this->ListItems();
    $this->count_items = count($this->items);
    $this->count_total = $this->TotalItems();
    $this->UpdateTotals();
    return $this;
  }

  public function ListItems( $id = 0 )
  {
    if( !$id ) $id = $this->id;
    if( !$id ) 
      return array();
    $sql = "select ci.id_item as iditem, 
    p.id_product as id, 
    ci.id_item,
    ci.id_cart,
    ci.id_product,
    ci.items,
    ci.items as quantity,
    f.file as file,
    ci.cost,
    ci.cost as price,
    ci.cost_base as price_base,
    p.title as title,
    p.id_state as state
    from cart_item ci
    left join product p on p.id_product = ci.id_product
    left join nz_file f on f.id_file = p.id_file
    where 
    ci.id_cart = '{$id}'
    order by ci.id_item asc";

    $r = $this->db->query($sql)->result();
    if($r) {
      foreach ($r as $key => $value) {
        $r[$key]->file = $value->file ? thumb($value->file,500,500) : false;
      }
    }

    return $r;
  }

  public function TotalItems()
  {
    $sql = "select sum(items) as total from cart_item ci where ci.id_cart = '{$this->id}' and ci.active = '1'";

    $total = $this->db->query($sql)->row()->total;
    return $total ? $total : 0;
  }

public function AddProduct( $product = 0, $items = 1  )
  {
    if(!$items) $items = 1;
    if( !$this->id ) $this->id;
    $info = $this->ProductInfo($product);

    if(!$info) return false;

    if( $items > 100 ) $items = 100;

    if( $itemret = $this->ItemExistsId($product) )
    {
      $sql = "update cart_item ci 
      set ci.items = (ci.items + {$items}), ci.cost = '{$info->cost}'
      where ci.id_cart = '{$this->id}'";
      $this->db->query($sql);

      if( ! $this->InCart($product) )
        $this->RemoveItem($product);

      $this->UpdateTotals();

      return $itemret;
    }    

    $sql = $this->db->insert_string('cart_item', array(
      'id_cart' => $this->id,
      'id_product' => $product,
      'items' => $items,
      'cost' => $info->cost
    ));
    $this->db->query($sql);

    $this->UpdateTotals();

    return $this->db->insert_id();
  }

public function ProductInfo($id) {

    $this->db->select("
      p.id_product as id,
      p.cost as cost
      ");
    $this->db->where("p.active = 1");
    $this->db->where("p.id_product = {$id}");

    $r = $this->db->get('product p');

    return $r->row();
  }

public function ItemExistsId( $product = 0 )
  {
          $sql = "select ci.id_item from cart_item ci where ci.id_cart = '{$this->id}' and ci.id_product = '{$product}'";
   
    $row = $this->db->query($sql)->row();
    return $row ? $row->id_item : 0;
  }

  public function InCart( $product = 0 )
  {
    
      $sql = "select ci.items from cart_item ci where ci.id_cart = '{$this->id}' and ci.id_product = '{$product}'";
    $row = $this->db->query($sql)->row();
    return $row ? $row->items : 0;
  }

  public function RemoveItem( $id_product = 0 )
  {
    if( !$this->id ) 
      return;
    $sql = "delete from cart_item where id_product = '{$id_product}' and id_cart = '{$this->id}'";
    return $this->db->query($sql);
  }

public function UpdateTotals() {

    $NetCost = 0;
    $Discount = 0;
    $DiscountTable = array(
      (object) array('min' => 1000, 'discount' => 10),
      (object) array('min' => 3500, 'discount' => 15),
      (object) array('min' => 7500, 'discount' => 20),
      (object) array('min' => 15000, 'discount' => 25),
    );
    $sql = "select ci.id_item as id, ci.items, p.cost, p.no_discount
    from cart_item ci 
    left join product p on p.id_product = ci.id_product
    where ci.id_cart = '{$this->id}'";
    $products = $this->db->query($sql)->result();
    foreach ($products as $p) $NetCost += $p->cost * $p->items; 

    foreach ($DiscountTable as $DiscountValue) 
    {
      if($NetCost >= $DiscountValue->min )
        $Discount = $DiscountValue->discount;
    }
    foreach ($products as $p) 
    {
      $RealCost = $p->cost;
      if(!$p->no_discount) $RealCost = $RealCost - ($RealCost * $Discount / 100);

      $this->db->update('cart_item', array('cost_base' => $p->cost, 'cost' => $RealCost), "id_item = '{$p->id}'" );
    }

    $this->subtotal = $this->SumCost();
    $this->total = $this->SumCost(1);
    $this->discount_per_products = $this->subtotal - $this->total;

    // if($this->coupon) {
    //   if($this->coupon->id_type == 1) {
    //     $this->discount = $this->total * (int)$this->discount_percent / 100;
    //   } else {
    //     $this->discount = $this->discount_money;
    //   }
      
    //   // var_dump($this);
    //   $this->total = $this->total - $this->discount;
    // }

    
      // var_dump($this);die;
    if($this->id_gim) {
      $sql = $this->db->query("SELECT value as r FROM `config` WHERE var = 'gim_discount'")->row();
      $gim_discount_porcent = $sql->r;

      $this->gim_discount = $this->total * (float)$gim_discount_porcent / 100;
      $this->total = $this->total - $this->gim_discount;
    }

    $sql = $this->db->query("SELECT value as r FROM `config` WHERE var = 'min_to_free_shipping'")->row();
    $min_to_free_shipping = $sql->r;

    if( $this->total < $min_to_free_shipping ) {
      $this->total = $this->total + $this->shipping_cost;
    } else {
      $this->shipping_cost = 0;
    }

    $sql = $this->db->query("SELECT value as r FROM `config` WHERE var = 'gim_comission'")->row();
    $gim_comission_precent = $sql->r;

    // $this->iva = $this->total*0.21;
    // $this->total = $this->total + $this->iva;
    $this->gim_comission = round($this->total * $gim_comission_precent / 100,2);

    $this->saveTotals();


    return $this;
  }



  public function GetGim($code,$password)
  {
   $sql = "select t.id_gim as id_gim, t.name as name, t.code as code, t.active as active, f.file as file
      from gim t
      left join nz_file f on f.id_file = t.id_file
      where t.active = '1'
      AND t.code = '$code'
      AND t.password = '$password'
      ";
      return $this->db->query($sql)->row();
  }

   public function SumCost( $discount = false )
  {
    $total = 0;
    if( $this->id ) 
    {
      $column = $discount ? 'cost' : 'cost_base';
      $sql = "select SUM(ci.$column * ci.items) as total 
      from cart_item ci 
      left join product p on p.id_product = ci.id_product
      where ci.id_cart = '{$this->id}'";
      $total = $this->db->query($sql)->row()->total;
    }    
    return $total;
  }

  public function UpdateProduct(  $id_item = 0, $id_product = 0, $items = 1 )
  {
    if( !$this->id ) $this->id;
    $info = $this->ProductInfo($id_product);


    // if( $itemret = $this->ItemExistsId($product, $size) )
    // {
      $sql = "update cart_item ci 
      set ci.items = {$items} 
      where ci.id_cart = '{$this->id}' and ci.id_item = '{$id_item}'";
      $this->db->query($sql);
      
      // if( ! $this->ItemExistsId($id_product) )
      //   $this->RemoveItem($id_item);

      $this->items = $this->ListItems();

// print_r($sql);

      return $id_item;
    // } 

  }

  public function GetCoupon( $code = 0, $force = 0, $id_coupon = 0 )
  {
    $date = date("Y-m-d");
    $sql = "
     select co.id_coupon, co.id_type, co.name, co.code, co.value
     from coupon co
     left join coupon_type t on t.id_type = co.id_type";
    if ($code) $sql .= " where co.code = '{$code}'";
    if ($id_coupon) $sql .= " where co.id_coupon = '{$id_coupon}'";
    if(!$force) $sql .= " and total >= used and expire >= '{$date}'";
    return $this->db->query($sql)->row();
    
  }

  public function AddDiscount( $id_cart = 0, $coupon = false )
  {
    if ($coupon->id_type == 1) {
      $sql = "update cart c 
      set c.id_coupon = '{$coupon->id_coupon}', c.desc1 = '{$coupon->value}', c.coupon_1 = 0
      where c.id_cart = '{$id_cart}'
      "; 
      $query = $this->db->query($sql);
    } else  {
      $sql = "update cart c 
      set c.id_coupon = '{$coupon->id_coupon}', c.coupon_1 = '{$coupon->value}', c.desc1 = 0
      where c.id_cart = '{$id_cart}'
      "; 
      $query = $this->db->query($sql);
    }
     if ($query) {
       $sql = "update coupon c 
      set c.used = c.used +1
      where c.id_coupon = '{$coupon->id_coupon}'
      "; 
       $this->db->query($sql);
    }
    $this->coupon = $coupon;

    $this->UpdateTotals();

    return $this;
  }
  public function saveTotals()
  {
    $data_update = array(
      'subtotal'=> $this->Cart->subtotal,
      'total'=> $this->Cart->total,
      'gim_discount'=> $this->Cart->gim_discount,
      'shipping_cost'=> $this->Cart->shipping_cost,
      'gim_comission'=> $this->Cart->gim_comission,
    );

    $this->db->where("t.id_cart = '{$this->Cart->id}'");
    $update = $this->db->update('cart as t',$data_update);
  }

  public function AddGim($id_cart=0,$id_gim=0)
  {
    $data_update = array(
      'id_gim'=> $id_gim,
    );

    $this->db->where("t.id_cart = '{$this->Cart->id}'");
    $update = $this->db->update('cart as t',$data_update);
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
      lj4.province as province,     
      lj3.value as coupon_value      
      FROM {$this->table} as t      
      LEFT JOIN gim lj0 on t.id_gim = lj0.id_gim       
      LEFT JOIN cart_state lj1 on t.id_state = lj1.id_state       
      LEFT JOIN cart_shipping lj2 on t.id_shipping = lj2.id_shipping       
      LEFT JOIN coupon lj3 on t.id_coupon = lj3.id_coupon    
      LEFT JOIN provinces lj4 on t.id_province = lj4.id_province      
   
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
           t.cost_base as cost_base,
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