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
    $this->load->model('CartModel', 'Cart');
  }


  public function start($id_cart)
  {

    $categories = $this->Api->GetCategories();
    $sliderhome = $this->Api->GetSlider();
    $sections = $this->Api->GetSection();
    $faq = $this->Api->GetFaq();
    $sizes = $this->Api->SelectSize();
    $config = $this->Api->GetConfig();
    $provinces = $this->Api->GetProvinces();

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
    $data['cart'] = (int)$id_cart ? $this->Cart->GetCart($id_cart) : $this->Cart->Start();
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
    if($this->input->post('gim')!='false')
      $related = array_merge(json_decode($product->related_gim), $related);

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

  public function add_item_cart()
  {
    if($this->input->post())
    {
     $this->Cart->id = (int)$this->input->post('id_cart');
     $id_product = (int)$this->input->post('id_product');
     $amount = (int)$this->input->post('amount');

      if( !is_int($id_product) || !is_int($amount)) return;

      $r = $this->Cart->AddProduct($id_product,$amount);

      echo json_encode(array( 'error'=>!$r, 'cart'=> $this->Cart->GetCart() ));
    }

  }

  public function remove_item_cart()
  {
     if($this->input->post())
      {
        $this->Cart->id = (int)$this->input->post('id_cart');
        $id_product = (int)$this->input->post('id_product');
        if( !is_int($id_product) ) return;

        $h = $this->Cart->RemoveItem($id_product);


          echo json_encode(array( 'error'=>0, 'cart'=> $this->Cart->GetCart() ));
      }
  }

  public function update_item_cart() {
    if($this->input->post()) {
      $this->Cart->id = (int)$this->input->post('id_cart');
      $id_item = (int)$this->input->post('id_item');
      $id_product = (int)$this->input->post('id_product');
      $amount = (int)$this->input->post('amount');
      
      
      if( !is_int($id_item) || !is_int($amount) || $amount<=0) return;

      $h = $this->Cart->UpdateProduct($id_item, $id_product, $amount);

      echo json_encode(array( 'error'=>0, 'cart'=> $this->Cart->GetCart() ));
    }
  }

  public function set_coupon($id_cart,$code) {

    $this->load->model('CartModel', 'Cart');
    $this->Cart->GetCart($id_cart);
    $coupon = $this->Cart->GetCoupon($code);

    if(isset($data->gim)) {
      $this->Cart->gim = $data->gim;
    }

    if ($coupon) {
      $discount = $this->Cart->AddDiscount($this->Cart->id, $coupon);
      echo json_encode(array( 'error'=>0, 'cart'=> $this->Cart, 'coupon'=>$coupon ));  
    }
    else
      echo json_encode(array( 'error'=>1, 'message'=> 'Este cupón no es válido' ));  
    
  }
  public function add_gim($id_cart) {

    $this->load->model('CartModel', 'Cart');
    $this->Cart->GetCart($id_cart);
    $gim = $this->Cart->GetGim($this->input->post('code'), $this->input->post('password'));

    if ($gim) {
      $this->Cart->AddGim($id_cart, $gim->id_gim);
      $gim->file = $gim->file ? thumb($gim->file,200,150) : false;
      echo json_encode(array( 'error'=>0, 'gim'=> $gim ));  
    }
    else
      echo json_encode(array( 'error'=>1, 'message'=> 'Gimnasio o contraseña incorrectas.' ));  
    
  }
  public function remove_gim($id_cart) {
    $this->load->model('CartModel', 'Cart');
    $this->Cart->GetCart($id_cart);

    $this->Cart->AddGim($id_cart, 0);
    echo json_encode(array( 'error'=>0, 'cart'=> $this->Cart ));  
    
  }

  public function set_user($id_cart) {

    if($this->input->post())
    {
      $this->load->model('CartModel', 'Cart');
      $data = json_decode($this->input->post('data'));

      $this->Cart->GetCart($id_cart);

      $data_update = array(
        'id_province'=> $this->input->post('id_province'),
        'id_shipping'=> $this->input->post('id_shipping') ? $this->input->post('id_shipping') : 1,
        'name'=> $this->input->post('name'),
        'lastname'=>$this->input->post('lastname'),
        'address'=>$this->input->post('address'),
        'postal_code'=>$this->input->post('postal_code'),
        'city'=>$this->input->post('city'),
        'phone'=>$this->input->post('phone'),
        'mail'=>$this->input->post('mail'),
      );


      $this->db->where("t.id_cart = '{$this->Cart->id}'");
      $update = $this->db->update('cart as t',$data_update);

      // foreach ($data_update as $key => $value) {
      //   $this->Cart->$key = $value;
      // }
      $this->Cart->GetCart($id_cart);

      echo json_encode(array( 'error'=>0, 'cart'=>$this->Cart ));
    }
  }

  public function confirm_buy($id_cart) {

    if($this->input->post())
    {
      $this->load->model('CartModel', 'Cart');
      $data = json_decode($this->input->post('data'));

      $this->Cart->GetCart($id_cart);

      $data_update = array(
        'id_state'=>2,
        'modified'=>date('Y-m-d H:i:s'),
      );

      $this->db->where("t.id_cart = '{$this->Cart->id}'");
      $update = $this->db->update('cart as t',$data_update);

      $this->load->model('MailModel', 'Mail');
      $dataElement = $this->Cart->DataElement($id_cart);
      $title = 'Nuevo Pedido Nº'.$dataElement['code'];
      $text = '<p>Hemos recibido tu pedido, una continuación detallamos todos los detalles para que lleves el control del mismo.</p><p>Vamos a mantenerte al tanto de todo el proceso. Gracias!</p>';
      $this->Mail->SendCart( array($dataElement['mail']),$dataElement,$title,$text );
      echo json_encode(array( 'error'=>0 ));
    }
  }
}

