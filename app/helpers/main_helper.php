<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('layout'))
{
  function layout( $url = '' )
  {
    $CI =& get_instance();
    return $CI->config->item('base_url').'app/layout/' . trim($url,'/');
  }
}

if ( ! function_exists('tview'))
{
  function tview( $file, $html = false )
  {
    $CI =& get_instance();
    $CI->load->view( $CI->data['theme_path'] . $file, $html );
  }
}

if ( ! function_exists('href'))
{
  function href( $a )
  {
    $CI =& get_instance();
    return isset($CI->routes[$a]) && ($CI->routes[$a]['pager']) ? base_url($CI->routes[$a]['pager']) : $a;
  }
}


if ( ! function_exists('thumb'))
{
  function thumb( $file = '', $w = 0, $h = 0, $crop = true, $global = false)
  {
    $CI =& get_instance();
    $version = $CI->config->item('upload-version', 'app');
    $static = $CI->config->item('static', 'app');
    $base_url = $CI->config->item('base_url');    
    $name = explode('/',$file);
    $type = $crop ? "crop" : "thumb";
    $global = $global ? "1" : "0";
    $encrypt = $CI->encryption->encode("{$file}||{$w}||{$h}||{$type}||{$global}");

    if(ENVIRONMENT == 'development') 
      return $static . 'f/' . $encrypt . '/' . $name[count($name)-1]. $version;
    return $base_url . 'f/' . $encrypt . '/' . $name[count($name)-1]. $version;
  }
}


if ( ! function_exists('upload'))
{
  function upload( $file = '', $global = false )
  {
    $CI =& get_instance();
    $version = $CI->config->item('upload-version', 'app');
    $folder = $CI->config->item('uploads-global', 'app');
    if(!$global || !$folder)
      $folder = $CI->config->item('uploads', 'app');
    $base_url = $CI->config->item('base_url');
    return $base_url . $folder . $file . ($file ? $version : '');
  }
}

if ( ! function_exists('base64url_encode'))
{ 
  function base64url_encode($data = '') 
  { 
    return rtrim(strtr($data, '+/', '-_'), '='); 
  } 
} 

if ( ! function_exists('base64url_decode'))
{ 
  function base64url_decode($data = '') 
  { 
    return str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT); 
  } 
} 

if ( ! function_exists('prep_word_url'))
{
  function prep_word_url( $string, $spacer = "-" )
  {
    $string = rtrim(trim($string));
    // $string = character_limiter($string,40,'');
    $string = mb_strtolower($string, 'UTF-8');
    $string = preg_replace("/[ÁáÄäÂâ]/iu","a",$string);
    $string = preg_replace("/[ÉéËëÊê]/iu","e",$string);
    $string = preg_replace("/[ÍíÏïÎî]/iu","i",$string);
    $string = preg_replace("/[ÓóÖöÔô]/iu","o",$string);
    $string = preg_replace("/[ÚúÜüÛû]/iu","u",$string);
    $string = preg_replace("/[Ññ]/iu","n",$string);
    $string = trim(preg_replace("/[^ A-Za-z0-9_]/", " ", $string));
    $string = preg_replace("/[ \t\n\r]+/", $spacer, $string);
    $string = str_replace(" ", $spacer, $string);
    $string = preg_replace("/[ -]+/", $spacer, $string);
    return $string;
  }
}

if ( ! function_exists('mb_ucfirst'))
{
  function mb_ucfirst($string = '', $e = 'utf-8', $lower = true)
  { 
    if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string))
    { 
      if( $lower )
        $string = mb_strtolower($string, $e); 
      $upper = mb_strtoupper($string, $e); 
      preg_match('#(.)#us', $upper, $matches); 
      $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e); 
    } 
    else 
    {
      if( $lower )
        $string = strtolower($string); 
      $string = ucfirst($string); 
    }
    return $string; 
  } 
} 

if ( ! function_exists('get_mime'))
{
  function get_mime( $filename = '' )
  {
    if(!file_exists($filename) || !filesize($filename)) return false;
    $image_info = getimagesize($filename);
    if(!isset($image_info["mime"])) return false;
    return $image_info["mime"];  
  } 
} 
if ( ! function_exists('prep_cost'))
{
  function prep_cost( $cost = 0, $currency = true, $ivi = false )
  {
    return number_format(round($cost, 2), 2, '.', '')  .  ($currency ? " €" : "") . ($ivi ? " i.v.i." : "");
  } 
} 

if ( ! function_exists('prep_costF'))
{
  function prep_costF( $cost = 0, $currency = true, $ivi = true )
  {
    return number_format(round($cost, 2), 2, '\'', '') .  ($currency ? " €" : "") . ($ivi ? " i.v.i." : "");
  } 
} 


if ( ! function_exists('create_select_options'))
{
  function create_select_options( $query = false, $label = '' )
  {
    $data = array();
    if($label) $data[''] = $label;
    if($query)
    {
      foreach ($query->result_array() as $row)
      {
        $data[$row['id']] = $row['el'];
      }
    }
    return $data;
  }
}
