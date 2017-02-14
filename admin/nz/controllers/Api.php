<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {

  public function __construct()
  {
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
    $this->load->model('CartModel', 'Cart');
  }


  public function start()
  {

    $categories = $this->Api->GetCategories();
    $sliderhome = $this->Api->GetSlider();
    $sections = $this->Api->GetSection();
    $faq = $this->Api->GetFaq();
    $sizes = $this->Api->SelectSize();
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
        $sections[$key]->accordion = $faq;
      }
    }
    
    $data = [];
    $data['categories'] = $categories;
    $data['sliderhome'] = $sliderhome;
    $data['sections'] = $sections;
    $data['sizes'] = $sizes;

    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';

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

    foreach ($related as $r) {
      $product = $this->Api->GetProduct($r);
      $product->file = $product->file ? thumb($product->file,500,500) : 'img/default.svg';
      $product->price = round($product->price);;
      if($product)
      {
        $data['related'][] = $product;
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

    $coupon = $this->Cart->GetCoupon($code);

    if ($coupon) {
    // $discount = $this->Cart->AddDiscount($this->Cart->id, $coupon);
      echo json_encode(array( 'error'=>0, 'coupon'=> $coupon ));  
    }
    else
      echo json_encode(array( 'error'=>1, 'message'=> 'Este cupón no es válido' ));  
    
  }
  public function get_gim() {

    $gim = $this->Cart->GetGim($this->input->post('code'), $this->input->post('password'));

    if ($gim) {
      echo json_encode(array( 'error'=>0, 'gim'=> $gim ));  
    }
    else
      echo json_encode(array( 'error'=>1, 'message'=> 'Gimnasio o contraseña incorrectas.' ));  
    
  }

  public function confirm_buy() {
    if($this->input->post())
    {
      $data = json_decode($this->input->post('data'));

      // $this->Cart->id = $id_cart;
      // if( !is_int($id_cart) ) return;


      // $this->form_validation->set_rules('name', $this->lang->line('Nombre'), 'trim|required');
      // $this->form_validation->set_rules('lastname', $this->lang->line('Apellido'), 'trim|required');
      // $this->form_validation->set_rules('mail', $this->lang->line('Mail'), 'trim|required|valid_email');
      // $this->form_validation->set_rules('phone', $this->lang->line('Telefono'), 'trim|required');
      // $this->form_validation->set_rules('address', $this->lang->line('Dirección'), 'trim|required');
      // $this->form_validation->set_rules('cp', $this->lang->line('Código postal'), 'trim|required');
      // $this->form_validation->set_rules('city', $this->lang->line('Ciudad'), 'trim|required');
      // $this->form_validation->set_rules('province', $this->lang->line('Provincia'), 'trim|required');

      // var_dump($data);die;

      $cart = array(
        'id_gim'=>$data->gim->id_gim,
        'id_state'=>2,
        'id_shipping'=>$data->user->id_shipping,
        'id_coupon'=>$data->coupon->id_coupon,
        // 'code'=>$data->,
        'name'=>$data->user->name,
        'lastname'=>$data->user->lastname,
        'address'=>$data->user->address,
        'postal_code'=>$data->user->postal_code,
        'province'=>$data->user->province,
        'city'=>$data->user->city,
        'phone'=>$data->user->phone,
        'mail'=>$data->user->mail,
        'coupon_1'=>$data->coupon->value,
        // 'subtotal'=>,
        // 'total'=>,
        'created'=>date('Y-m-d H:i:s'),
      );


      // $insert_user = $this->db->insert('user',$user);

      var_dump($cart);die;

        

        $data_update = array(
          // 'ip_user'=>$this->input->ip_address(),
          'id_state'=>2,
          'modified'=>date('Y-m-d H:i:s'),
          // 'id_payment'=>$this->input->post('id_payment'),
          'name'=>$this->input->post('name'),
          'lastname'=>$this->input->post('lastname'),
          'mail'=>$this->input->post('mail'),
          'address'=>$this->input->post('address'),
          'postal_code'=>$this->input->post('postal_code'),
          'province'=>$this->input->post('province'),
          'city'=>$this->input->post('city'),
          'phone'=>$this->input->post('phone'),
          'id_shipping'=>$id_shipping,
          'subtotal'=>$Cart->subtotal,
          // 'tax'=>$Cart->tax,
          'total'=>$Cart->total,
          // 'shipping'=>$this->Cart->ConutryShippingCost($id_address_country),
          // 'data'=>$data->data ? json_encode($data->data) : false,
          // 'payment_data'=> $this->input->post('billing') ? json_encode( $this->input->post('billing') )  : false,
          // 'shipping_data'=> json_encode($shipping_data),
        );

        $this->db->where("t.id_cart = '{$Cart->id}'");
        $update = $this->db->update('cart as t',$data_update);

        echo json_encode(array( 'error'=>0, 'callback'=> 'success-confirm-buy' ));


    }
  }
}
