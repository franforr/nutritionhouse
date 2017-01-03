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

    $this->load->model('ApiModel', 'Api');
    $this->load->model('CartModel', 'Cart');
  }


  public function start()
  {

    $categories = $this->Api->GetCategories();
    $homeproducts = $this->Api->GetHomeProducts();
    $sections = $this->Api->GetSection();
    $faq = $this->Api->GetFaq();
    $cart = $this->Cart->Start(15);
    
    // sistema de cache via json como en infonews

    foreach ($categories as $key => $value) {
      $categories[$key]->file = $value->file ? thumb($value->file,500,500) : false;
    }

		foreach ($homeproducts as $key => $value) {
      $homeproducts[$key]->file = $value->file ? thumb($value->file,500,500) : false;
    }
    

    $data = [];
    $data['categories'] = $categories;
    $data['homeproducts'] = $homeproducts;
    $data['sections'] = $sections;
    $data['faq'] = $faq;
    $data['cart'] = $cart;


    echo '<pre>';
    print_r($data);
    echo '</pre>';

    // echo json_encode($data);
    
    return;

  }


 public function category()
  {
    $this->load->model('ApiModel', 'Api');

    $idc = $this->uri->segment(3,0) ? $this->uri->segment(3,0) : false;
    

    $category = $this->Api->GetProducts($idc);
    $title = $this->Api->GetCatTitle($idc);
    if(!$title)
      die('error');

    foreach ($category as $key => $value) {
      $category[$key]->file = $value->file ? thumb($value->file,500,500) : false;
    }
    

    $data = [];
    $data['title'] = $title->title;
    $data['products'] = $category;
    

    echo '<pre>';
    print_r($data);
    echo '</pre>';

    // echo json_encode($data);
    
    return;

  }

  public function product()
  {
    $this->load->model('ApiModel', 'Api');

    $idp = $this->uri->segment(3,0) ? $this->uri->segment(3,0) : false;
    

    $product = $this->Api->GetProduct($idp);
    

    $product->file = $product->file ? thumb($product->file,500,500) : false;
    

    $data = [];
    $related = json_decode($product->related);
    unset($product->related);
    $data['product'] = $product;
    $data['related'] = array();

    foreach ($related as $r) {
      $product = $this->Api->GetProduct($r);
      $product->file = $product->file ? thumb($product->file,500,500) : false;
      if($product)
      {
        $data['related'][] = $product;
      }
    }
    

    echo '<pre>';
    print_r($data);
    echo '</pre>';

    // echo json_encode($data);
    
    return;

  }

public function search()
  {
    $this->load->model('ApiModel', 'Api');

    $search = $this->Api->GetProducts($this->input->post('keyword'));

   foreach ($search as $key => $value)
    {

    $search[$key]->file = $value->file ? thumb($value->file,500,500) : false;

    }


    $data = [];
    $data['search'] = $search;

    echo '<pre>';
    print_r($data);
    echo '</pre>';

    // echo json_encode($data);
    
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
          $id_item = (int)$this->input->post('id_item');
          if( !is_int($id_item) ) return;

          $h = $this->Cart->RemoveItem($id_item);


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

public function add_coupon() {
  if($this->input->post()) {
      $this->Cart->id = (int)$this->input->post('id_cart');
      $code = $this->input->post('code');
      $cart = $this->Cart->GetCart();

      
      if( !is_int($this->Cart->id) || !$code) return;
  
      $coupon = $this->Cart->GetCoupon($code);
      if ($coupon) {
      $discount = $this->Cart->AddDiscount($this->Cart->id, $coupon);
        echo json_encode(array( 'error'=>0, 'cart'=> $this->Cart->GetCart() ));  
      }
      else
        echo json_encode(array( 'error'=>1, 'message'=> 'Este cupón no es válido' ));  
    }
  }


public function confirm_buy() {
    if($this->input->post())
    {
      $id_cart = (int)$this->input->post('id_cart');
      $this->Cart->id = $id_cart;
      if( !is_int($id_cart) ) return;

      $this->load->library('form_validation');

      $this->form_validation->set_rules('name', $this->lang->line('Nombre'), 'trim|required');
      $this->form_validation->set_rules('lastname', $this->lang->line('Apellido'), 'trim|required');
      $this->form_validation->set_rules('mail', $this->lang->line('Mail'), 'trim|required|valid_email');
      $this->form_validation->set_rules('phone', $this->lang->line('Telefono'), 'trim|required');
      $this->form_validation->set_rules('address', $this->lang->line('Dirección'), 'trim|required');
      $this->form_validation->set_rules('cp', $this->lang->line('Código postal'), 'trim|required');
      $this->form_validation->set_rules('city', $this->lang->line('Ciudad'), 'trim|required');
      $this->form_validation->set_rules('province', $this->lang->line('Provincia'), 'trim|required');
      // $this->form_validation->set_rules('billing[country]', $this->lang->line('País'), 'trim|required');

      // $this->form_validation->set_rules('billing[address]', $this->lang->line('Dirección'), 'trim|required');

      // $this->form_validation->set_rules('terms', $this->lang->line('Términos y condiciones'), 'trim|required');
      // $this->form_validation->set_rules('right', $this->lang->line('Derecho de revocación'), 'trim|required');
      // $this->form_validation->set_rules('id_payment', $this->lang->line('Método de pago'), 'trim|required');

      // if( !$this->input->post('shipping-methods') ) {
      //   $this->form_validation->set_rules('address[name]', $this->lang->line('Nombre'), 'trim|required');
      //   $this->form_validation->set_rules('address[lastname]', $this->lang->line('Apellido'), 'trim|required');
      //   $this->form_validation->set_rules('address[mail]', $this->lang->line('Mail'), 'trim|required|valid_email');
      //   $this->form_validation->set_rules('address[phone]', $this->lang->line('Telefono'), 'trim|required');
      //   $this->form_validation->set_rules('address[address]', $this->lang->line('Dirección'), 'trim|required');
      //   $this->form_validation->set_rules('address[cp]', $this->lang->line('Código postal'), 'trim|required');
      //   $this->form_validation->set_rules('address[location]', $this->lang->line('Localidad'), 'trim|required');
      //   $this->form_validation->set_rules('address[province]', $this->lang->line('Provincia'), 'trim|required');
      //   $this->form_validation->set_rules('address[country]', $this->lang->line('País'), 'trim|required');

      // }

      if ($this->form_validation->run() == FALSE)
      {
        $data['error'] = 1;
        $data['inputErrors'] = json_encode($this->form_validation->error_array());
        echo json_encode($data);

      } else {


        $Cart = $this->Cart->GetCart($id_cart);
        

        switch ($this->input->post('shipping-methods')) {
          case 'billing':

            # enviar a mi dir de facutracion
            $shipping_data = $this->input->post('billing');
            $id_shipping = 2;
            // $id_address_country = $this->input->post('billing')['country'];
            break;
          
          default:
            # retira en tienda
            $shipping_data = null;
            $id_shipping = 1;
            // $id_address_country = $this->input->post('billing')['country'];
            break;
        }

        $data_update = array(
          // 'ip_user'=>$this->input->ip_address(),
          'id_state'=>2, /*ANALIZAR*/
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
}