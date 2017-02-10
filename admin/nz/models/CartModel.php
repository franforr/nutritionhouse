<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CartModel extends CI_Model
{
  public
    $id = 0,
    $items = array(),
    $subtotal = 0,
    $discount = 0,
    $tax = 0,
    $total = 0;


  public function Start($id_cart = 0)
  {
    $this->id = $id_cart;

    if ($this->id == 0) {
      $this->id = $this->CreateCart();
    
    } else {
      $cart = $this->GetCart($this->id);

      if( ! $cart ) {
       return $this->Start(0);
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
    if( !$id ) $id = $this->id;

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
    $this->db->where("t.id_cart = {$this->id}");
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
    ci.cost
    from cart_item ci
    left join product p on p.id_product = ci.id_product
    where 
    ci.id_cart = '{$id}'
    order by ci.id_item asc";

    $r = $this->db->query($sql);

    return $r->result();
  }

  public function TotalItems()
  {
    $sql = "select sum(items) as total from cart_item ci where ci.id_cart = '{$this->id}' and ci.active = '1'";

    $total = $this->db->query($sql)->row()->total;
    return $total ? $total : 0;
  }

public function AddProduct( $product = 0, $items = 1  )
  {
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
        $this->RemoveItem($itemret);

      return $itemret;
    }    

    $sql = $this->db->insert_string('cart_item', array(
      'id_cart' => $this->id,
      'id_product' => $product,
      'items' => $items,
      'cost' => $info->cost
    ));
    $this->db->query($sql);

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

  public function RemoveItem( $iditem = 0 )
  {
    if( !$this->id ) 
      return;
    $sql = "delete from cart_item where id_item = '{$iditem}' and id_cart = '{$this->id}'";
    return $this->db->query($sql);
  }

public function UpdateTotals() {
    $SumCost = $this->SumCost();
    

    // $SumCost = $SumCost +  $this->shipping_cost;
    $this->subtotal = $SumCost;
    if($this->discount_percent) {
      $this->discount = round($SumCost * $this->discount_percent / 100, 2);
      $this->total = $this->subtotal - $this->discount;
    }
    if($this->discount_money) {
      $this->total = $SumCost - $this->discount_money;
      $this->discount = $this->discount_money;
    }
    // $this->discount = $discount;

    
   // $this->subtotal = round( 100 * $SumCost / (100+$this->iva), 2 );
  //  $this->tax = $this->total - $this->subtotal;
    return $this;
  }

  public function GetGim($code,$password)
  {
   $sql = "select t.*
      from gim t
      where t.active = '1'
      AND t.code = '$code'
      AND t.password = '$password'
      ";
      return $this->db->query($sql)->row();
  }

   public function SumCost( $symbol = true )
  {
    $total = 0;
    if( $this->id ) 
    {
      $sql = "select SUM(ci.cost * ci.items) as total 
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

  public function GetCoupon( $code = 0 )
  {
    
    $date = date("Y-m-d");
    $sql = "
     select co.id_coupon, co.id_type, co.name, co.code, co.value
     from coupon co
     left join coupon_type t on t.id_type = co.id_type
     where co.code = '{$code}' and total >= used and expire >= '{$date}'  
     ";
    return $this->db->query($sql)->row();
    
  }

  public function AddDiscount( $id_cart = 0, $coupon = false )
  {
    if ($this->id_coupon == $coupon->id_coupon) return;

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
  }

}