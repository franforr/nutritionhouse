<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CartModel extends CI_Model
{
  public
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
                       t.id_user,
                       t.comments,
                       t.subtotal,
                       t.total,
                       t.desc1,
                       t.coupon_1,
                       t.modified,
                       t.id_coupon,
                       cst.state");
    $this->db->where("t.id_cart = '{$this->id}'");
    $this->db->join('cart_shipping cs', 't.id_shipping = cs.id_shipping', 'left');
    $this->db->join('cart_state cst', 't.id_state = cst.id_state', 'left');
    $data = $this->db->get('cart t')->row();

    if (! $data) 
     return false;
   
    $this->id_user = $data->id_user;
    $this->comments = $data->comments;
    $this->total = $data->total;
    $this->subtotal = $data->subtotal;
    $this->discount_percent = $data->desc1;
    $this->discount_money = $data->coupon_1;
    $this->modified = $data->modified;
    $this->state = $data->state;
    $this->id_coupon = $data->id_coupon;
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

    
    if($this->gim) {
      $sql = $this->db->query("SELECT value as r FROM `config` WHERE var = 'gim_discount'")->row();
      $gim_discount_porcent = $sql->r;

      $this->gim_discount = $this->total * (float)$gim_discount_porcent / 100;
      $this->total = $this->total - $this->gim_discount;
    }

      //     if(cart.total < Data.config.min_to_free_shipping && cart.user && cart.user.id_province ) {
      //   var province = section(cart.user.id_province,Data.provinces);

      //   cart.user.province = province.province;
      //   cart.shipping_cost = parseInt(province.shipping);
      //   cart.total = cart.total + cart.shipping_cost;
      // }

    $sql = $this->db->query("SELECT value as r FROM `config` WHERE var = 'min_to_free_shipping'")->row();
    $min_to_free_shipping = $sql->r;

    if( $this->total < $min_to_free_shipping && $this->id_province && $this->id_shipping == 2 ) {
      $sql = $this->db->query("SELECT shipping as r FROM `provinces` WHERE id_province = '{$this->id_province}'")->row();
      $this->shipping_cost = (float)$sql->r;

      $this->total = $this->total + $this->shipping_cost;
    }

    $sql = $this->db->query("SELECT value as r FROM `config` WHERE var = 'gim_comission'")->row();
    $gim_comission_precent = $sql->r;

    // $this->iva = $this->total*0.21;
    // $this->total = $this->total + $this->iva;
    $this->gim_comission = round($this->total * $gim_comission_precent / 100,2);

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
    // $this->saveTotals();

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

}