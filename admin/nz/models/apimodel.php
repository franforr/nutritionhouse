<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ApiModel extends CI_Model
{

public function GetCategories()
{
 $sql = "select p.*, f.file, p.id_category, p.category
    from product_category p
    left join nz_file f on f.id_file = p.id_file
    order by p.num
    ";
    return $this->db->query($sql)->result();
}

public function GetSlider()
{
 $sql = "select f.file, p.id_product as id, p.title as title, p.subtitle as subtitle
    from slider p
    left join nz_file f on f.id_file = p.id_file
    where p.active = '1'
    order by p.num
    ";
    return $this->db->query($sql)->result();
}

public function GetSection()
{
 $sql = "select p.id_section as id, p.title as title, p.text as text 
    from section p
    where p.active = '1'
    order by p.num
    ";
    return $this->db->query($sql)->result();
}
public function GetGim($code,$password)
{
 $sql = "select t*, f.file as file
     left join nz_file f on f.id_file = t.id_file

    from section t
    where t.active = '1'
    AND t.code = code
    AND t.password = password
    ";
    return $this->db->query($sql)->row();
}

public function SelectSize()
{
 $sql = "select ps.id_size as id, ps.size as el 
    from product_size ps
    where 1
    order by ps.num
    ";
    return $this->db->query($sql)->result();
}

public function GetFaq()
{
 $sql = "select p.id_faq as id, p.title as title, p.text as text 
    from faq p
    where p.active = '1'
    order by p.num
    ";
    return $this->db->query($sql)->result();
}


public function GetCatTitle($id_category = 0)
{
 $sql = "select c.category as title
    from product_category c
    where c.id_category = '{$id_category}' 
    ";
    return $this->db->query($sql)->row();
}

  public function GetProduct($id_product = 0)
  {
    $sql = "select f.file, p.id_gallery, p.id_product as id, pc.category as category, p.title as title, p.cost as price, p.text as text, p.id_state as state, p.related as related 
    from product p
    left join nz_file f on f.id_file = p.id_file
    left join product_category pc on pc.id_category = p.id_category
    where p.id_product = '{$id_product}' 
    ";
    return $this->db->query($sql)->row();
  }

  public function GetProducts( $filters = array(), $order = array(), $limit = 0, $start = 0)
  {
    $sql = "select p.id_product as id, p.id_category as id_category, pc.category as category, p.title as title, p.text as text, p.cost as price, f.file, p.id_state as state
    from product p
    left join nz_file f on f.id_file = p.id_file
    left join product_size ps on ps.id_size = p.id_size
    left join product_category pc on pc.id_category = p.id_category
    where p.active = '1'";

    if(count($filters)) {
      if( $filters['id_category'] )
        $sql .= " AND p.id_category = '{$filters['id_category']}'"; 
      if( $filters['keyword'] )
        $sql .= " AND p.title LIKE '%{$filters['keyword']}%' OR p.text LIKE '%{$filters['keyword']}%'";
      if( $filters['size'] )
        $sql .= " AND p.id_size = '{$filters['size']}'";
      if( $filters['price'] ) {
        $explode = explode(',', $filters['price']);
        $min = $explode[0];
        $max = $explode[1];
        $sql .= " AND p.cost >= $min AND p.cost <= $max";
      }
    }

    $sql .= " ORDER BY"; 

    if(count($order) && $order['order'] && $order['direction']) {
        $sql .= " {$order['order']} {$order['direction']} ,title ASC"; 
    } else {
      $sql .= " id ASC"; 
    }

    $count = $this->db->query($sql)->num_rows();

    // var_dump($sql);

    if($limit>0) $sql .= " LIMIT $start,$limit";

    $result = $this->db->query($sql)->result();
       
    return array('result'=>$result,'count'=>$count);
  }



  public function Gallery( $id = 0 )
  {
    $sql = "select f.file, f.id_file
    from nz_gallery_file s
    left join nz_file f on f.id_file = s.id_file
    where s.id_gallery = '{$id}'
    order by s.num";

    $r = $this->db->query($sql)->result();

    return $r;
  }  


}