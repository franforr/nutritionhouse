<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataModel extends CI_Model
{

  public function SelectPromotionState( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_state as id, state as el FROM promotion_state $where order by el"), $all);
  }

  public function SelectInformationStyle( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_style as id, style as el FROM information_style $where order by el"), $all);
  }

  public function SelectProductCategory( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_category as id, category as el FROM product_category $where order by num"), $all);
  }

  public function SelectProductSub( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_sub as id, sub as el FROM product_sub $where order by num"), $all);
  }
  
  public function ResultProductSub( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return $this->db->query("SELECT id_sub as id, sub as el FROM product_sub $where order by num")->result();
  }

  public function SelectProductSub2( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_sub2 as id, sub2 as el FROM product_sub2 $where order by el"), $all);
  }
  
  public function ResultProductSub2( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return $this->db->query("SELECT id_sub2 as id, sub2 as el FROM product_sub2 $where order by el")->result();
  }

  public function ResultBrands( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return $this->db->query("SELECT id_brand as id, brand as el FROM brand $where order by el")->result();
  }

  public function SelectBrand( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_brand as id, brand as el FROM brand $where order by el"), $all);
  }

  public function SelectProductState( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_state as id, state as el FROM product_state $where order by el"), $all);
  }

  public function SelectUserState( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_state as id, state as el FROM user_state $where order by el"), $all);
  }

  public function SelectUserTreatment( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_treatment as id, treatment as el FROM user_treatment $where order by el"), $all);
  }

  public function SelectCountry( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_country as id, country as el FROM country $where order by el"), $all);
  }

  public function SelectCouponType( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_type as id, type as el FROM coupon_type $where order by el"), $all);
  }

  public function SelectUser( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_user as id, mail as el FROM user $where order by el"), $all);
  }

  public function SelectCartState( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_state as id, state as el FROM cart_state where id_state > 1 order by id"), $all);
  }

  public function SelectCartShipping( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_shipping as id, shipping as el FROM cart_shipping $where"), $all);
  }

  public function SelectStore( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_store as id, store as el FROM store $where order by num"), $all);
  }

  public function SelectCollection( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_collection as id, collection as el FROM collection c $where order by c.date desc"), $all);
  }

  public function SelectProduct( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_product as id, title as el FROM product c where active = '1'"), $all);
  }

  public function SelectProductCares( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_care as id, name as el FROM product_care $where order by el asc"), $all);
  }

  public function SelectCartPayment( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_payment as id, payment as el FROM cart_payment $where order by num"), $all);
  }

  public function SelectCivilStatus( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_civil_status as id, civil_status as el FROM cemaco_clients.civil_status $where order by id"), $all);
  }

  public function SelectGender( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_gender as id, gender as el FROM cemaco_clients.gender $where order by id"), $all);
  }

  public function SelectGeoProvince( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_province as id, province as el FROM cemaco_clients.geo_province $where order by el"), $all);
  }

  public function SelectGeoCanton( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_canton as id, canton as el FROM cemaco_clients.geo_canton $where order by el"), $all);
  }

  public function SelectGeoDistrict( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_district as id, district as el FROM cemaco_clients.geo_district $where order by el"), $all);
  }

  public function SelectWeddingsListState( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_state as id, state as el FROM weddings_list_state $where order by id"), $all);
  }
  
  public function getMonthString($number = 0)
  {  
    $number = round($number);
    $months = array();
    $months[1] = 'Enero';
    $months[2] = 'Febrero';
    $months[3] = 'Marzo';
    $months[4] = 'Abril';
    $months[5] = 'Mayo';
    $months[6] = 'Junio';
    $months[7] = 'Julio';
    $months[8] = 'Agosto';
    $months[9] = 'Septiembre';
    $months[10] = 'Octubre';
    $months[11] = 'Noviembre';
    $months[12] = 'Diciembre';
    return isset($months[$number]) ? $months[$number] : "";
  }


  public function SelectSizeType( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_type as id, type as el FROM size_type $where order by num"), $all);
  }

  public function SelectSizeTypeE( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_type as id, CONCAT(type,' - ',sizes) as el FROM size_type $where order by num"), $all);
  }


  public function SelectCompany( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_company as id, company as el FROM company $where order by el"), $all);
  }



  public function SelectProductSize( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_size as id, size as el FROM product_size $where order by el"), $all);
  }

  public function SelectGim( $where = '', $all = '' )
  {
    if( $where ) 
      $where = 'where '. $where;
    return create_select_options($this->db->query("SELECT id_gim as id, name as el FROM gim $where order by el"), $all);
  }

}