<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

  public function __construct()
  {
    $this->safeFunctionsU = array('start');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    $method = $_SERVER['REQUEST_METHOD'];
    if($method == "OPTIONS") {
      die();
      // no hay que hacer DIE en CI porque no cierra cnx con db
    }

    parent::__construct();
    $this->load->config('app', TRUE);
    $this->load->library('Encryption');
    $this->load->model('AppMainModel', 'MApp');  
    $this->load->model('ApiModel', 'Api');
    // $this->load->model('CartModel', 'Cart');
  }


  public function start()
  {

    $categories = $this->Api->GetCategories();
    $sliderhome = $this->Api->GetSlider();
    $sections = $this->Api->GetSection();
    $faq = $this->Api->GetFaq();
    $sizes = $this->Api->SelectSize();
    $config = $this->Api->GetConfig();
    $provinces = $this->Api->GetProvinces();
    // $cart = $this->Cart->Start(7);
    
    // sistema de cache via json como en infonews

    foreach ($categories as $key => $value) {
      $categories[$key]->file = $value->file ? thumb($value->file,500,500) : false;
    }

    foreach ($sliderhome as $key => $value) {
      $sliderhome[$key]->file = $value->file ? thumb($value->file,650,388) : false;
      $sliderhome[$key]->subtitle = substr($value->subtitle, 0, 76);
    }
    
    foreach ($sections as $key => $value) {
      $sections[$key]->accordion = false;
      if($value->id == 10) {
        $sections[$key]->accordion = $faq ? $faq : array();
      }
    }
    
    $data = [];
    $data['categories'] = $categories;
    $data['sliderhome'] = $sliderhome;
    $data['sections'] = $sections;
    $data['sizes'] = $sizes;
    $data['provinces'] = $provinces;
    $data['config'] = array();

    foreach ($config as $key => $value) {
      $data['config'][$value->var] = $value->value;
    }

    echo json_encode($data);
    
    return;
  }


 // public function search()
 //  {
 //    $this->load->model('ApiModel', 'Api');

 //    $idc = $this->input->post('id_category') ? $this->input->post('id_category') : false;
    
 //    $page = (int)$this->input->post('page');
 //    $limit = 8;
 //    $start = ($limit + 1) * $page;

 //    $products = $this->Api->GetProducts(false,$idc,$limit,$start);
 //    $title = $this->Api->GetCatTitle($idc);
 //    if(!$title)
 //      die('error');

 //    foreach ($products as $key => $value) {
 //      $products[$key]->file = $value->file ? thumb($value->file,500,500) : false;
 //    }
    

 //    $data = [];
 //    $data['products'] = $products;
    

 //    // echo '<pre>';
 //    // print_r($data);
 //    // echo '</pre>';

 //    echo json_encode($data);
    
 //    return;

 //  }

  public function product()
  {
    $this->load->model('ApiModel', 'Api');

    $idp = $this->uri->segment(3,0) ? $this->uri->segment(3,0) : false;

    $product = $this->Api->GetProduct($idp);
    

    $product->file = $product->file ? thumb($product->file,500,500) : false;

    $gallery = $this->Api->gallery($product->id_gallery);
    $product->gallery = count($gallery) ? $gallery : false;
    $product->price = round($product->price);
    $product->text = nl2br($product->text);

    if ($product->gallery) {
      foreach ($product->gallery as $key => $value) {
        $product->gallery[$key]->file = $value->file ? thumb($value->file,500,500) : false;
      }
    }
    
    $data = [];
    $related = json_decode($product->related);
    unset($product->related);
    $data['product'] = $product;
    $data['related'] = array();
    $faq = $this->Api->GetFaq($product->id,$product->id_category);
    $data['faq'] = $faq;


    if( count($related) ) {
      if( $limit = 5 - count($related) ) {
        $filters = array(
          'size' => $product->id_size,
        );

        $extra_related = $this->Api->GetProducts($filters, false, $limit);
      }
      if( $related ) {

        foreach ($related as $r) {
          $product = $this->Api->GetProduct($r);
          $product->file = $product->file ? thumb($product->file,500,500) : 'img/default.svg';
          $product->price = round($product->price);
          if($product)
          {
            $data['related'][] = $product;
          }
        }
      }
      if(isset($extra_related) && isset($extra_related)) {
        foreach ($extra_related['result'] as $key => $value) {
          
          $value->file = $value->file ? thumb($value->file,500,500) : 'img/default.svg';
          $value->price = round($value->price);
          $data['related'][] = $value;
        }
      }
    }
    

    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';

    echo json_encode($data);

    return;
  }

public function search()
  {
    $this->load->model('ApiModel', 'Api');

    $idc = $this->input->post('id_category') ? $this->input->post('id_category') : false;

    $filters = array(
      'id_category' => $this->input->post('id_category') ? $this->input->post('id_category') : '',
      'keyword' => $this->input->post('keyword') ? $this->input->post('keyword') : '',
      'size' => $this->input->post('filter_size') ? $this->input->post('filter_size') : '',
      'price' => $this->input->post('filter_price') ? $this->input->post('filter_price') : '',
    );

    $order = array(
      'order' => $this->input->post('order') ? $this->input->post('order') : '',
      'direction' => $this->input->post('order_direction') ? $this->input->post('order_direction') : ''
    );

    $page = (int)$this->input->post('page');
    $perpage = 8;
    $start = ($perpage) * $page;

    $search = $this->Api->GetProducts($filters, $order, $perpage, $start);
    $result = $search['result'];

    foreach ($result as $key => $value)
    {
      $result[$key]->file = $value->file ? thumb($value->file,500,500) : 'img/default.svg';
      $result[$key]->price = round($value->price);
    }


    $data = [];
    $data['products'] = $result;
    $data['more'] = $search['count'] - $start - $perpage;

    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';

    echo json_encode($data);
    
    return;

  }

  // public function add_item_cart()
  // {
  //   if($this->input->post())
  //   {
  //    $this->Cart->id = (int)$this->input->post('id_cart');
  //    $id_product = (int)$this->input->post('id_product');
  //    $amount = (int)$this->input->post('amount');

  //     if( !is_int($id_product) || !is_int($amount)) return;

  //     $r = $this->Cart->AddProduct($id_product,$amount);

  //     echo json_encode(array( 'error'=>!$r, 'cart'=> $this->Cart->GetCart() ));
  //   }

  // }

    // public function remove_item_cart()
    // {
    //    if($this->input->post())
    //     {
    //       $this->Cart->id = (int)$this->input->post('id_cart');
    //       $id_item = (int)$this->input->post('id_item');
    //       if( !is_int($id_item) ) return;

    //       $h = $this->Cart->RemoveItem($id_item);


    //         echo json_encode(array( 'error'=>0, 'cart'=> $this->Cart->GetCart() ));
    //     }
    // }

// public function update_item_cart() {
//     if($this->input->post()) {
//       $this->Cart->id = (int)$this->input->post('id_cart');
//       $id_item = (int)$this->input->post('id_item');
//       $id_product = (int)$this->input->post('id_product');
//       $amount = (int)$this->input->post('amount');
      
      
//       if( !is_int($id_item) || !is_int($amount) || $amount<=0) return;

//       $h = $this->Cart->UpdateProduct($id_item, $id_product, $amount);

//       echo json_encode(array( 'error'=>0, 'cart'=> $this->Cart->GetCart() ));
//     }
//   }

  public function get_coupon($code) {

    $this->load->model('CartModel', 'Cart');
    $coupon = $this->Cart->GetCoupon($code);

    if ($coupon) {
    // $discount = $this->Cart->AddDiscount($this->Cart->id, $coupon);
      echo json_encode(array( 'error'=>0, 'coupon'=> $coupon ));  
    }
    else
      echo json_encode(array( 'error'=>1, 'message'=> 'Este cupón no es válido' ));  
    
  }
  public function get_gim() {

    $this->load->model('CartModel', 'Cart');
    $gim = $this->Cart->GetGim($this->input->post('code'), $this->input->post('password'));

    if ($gim) {
      $gim->file = $gim->file ? thumb($gim->file,200,150) : false;
      echo json_encode(array( 'error'=>0, 'gim'=> $gim ));  
    }
    else
      echo json_encode(array( 'error'=>1, 'message'=> 'Gimnasio o contraseña incorrectas.' ));  
    
  }

  public function confirm_buy() {

    if($this->input->post())
    {
      $this->load->model('CartModel', 'Cart');
      $data = json_decode($this->input->post('data'));


      $this->Cart->Start();
      $this->Cart->id_province = (isset($data->user)) ? $data->user->id_province : false;
      $this->Cart->id_shipping = (isset($data->user)) ? $data->user->id_shipping : 1;

      if(isset($data->gim)) {
        $this->Cart->gim = $data->gim;
      }

      foreach ($data->items as $k => $item) {
        $this->Cart->AddProduct($item->id_product,$item->quantity);
      }
      if(isset($data->coupon) && isset($data->coupon->code)) {
        $coupon = $this->Cart->GetCoupon($data->coupon->code, 1);

        if($coupon)
          $this->Cart->AddDiscount($this->Cart->id,$coupon);
      }
      // cart.subtotal = count_cost;
      // cart.total = count_cost - cart.discount;
      // cart.gim_discount = (cart.gim.active) ? Math.round(cart.total * 5 / 100, 2) : 0;
      // cart.total = cart.total - cart.gim_discount;
      // cart.iva = Math.round(cart.total * 21 / 100, 2);
      // cart.total = cart.total + cart.iva;


      $this->Cart->GetCart();

      $data_update = array(
        'id_gim'=>(isset($data->gim)) ? $data->gim->id_gim : false,
        'id_state'=>2,
        'id_coupon'=>(isset($coupon)) ? $coupon->id_coupon : false,
        'id_province'=>$this->Cart->id_province,
        'id_shipping'=>$this->Cart->id_shipping,
        'name'=>(isset($data->user)) ? $data->user->name : false,
        'lastname'=>(isset($data->user)) ? $data->user->lastname : false,
        'address'=>(isset($data->user)) ? $data->user->address : false,
        'postal_code'=>(isset($data->user)) ? $data->user->postal_code : false,
        'city'=>(isset($data->user)) ? $data->user->city : false,
        'phone'=>(isset($data->user)) ? $data->user->phone : false,
        'mail'=>(isset($data->user)) ? $data->user->mail : false,
        'desc1'=>(isset($this->Cart->discount)) ? $this->Cart->discount : false,
        'subtotal'=> $this->Cart->subtotal,
        'total'=> $this->Cart->total,
        'iva'=> $this->Cart->iva,
        'gim_discount'=> $this->Cart->gim_discount,
        'shipping_cost'=> $this->Cart->shipping_cost,
        'created'=>date('Y-m-d H:i:s'),
      );

      $this->db->where("t.id_cart = '{$this->Cart->id}'");
      $update = $this->db->update('cart as t',$data_update);

      echo json_encode(array( 'error'=>0 ));


    }
  }
}

