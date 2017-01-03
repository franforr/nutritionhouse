<? if( !AJAX ) $this->load->view("common/header") ?>
<div class="widget-app-element" id="main">
<form class="widget-app-element-form" id="widget-form-<?= $wgetId ?>" method="post" action="<?= base_url() . ($idItem ? "{$appController}/{$appFunction}/element/{$idItem}" . ($quickOpen ? "/quick" : "") : "{$appController}/{$appFunction}/element/new") ?>" role="form">
  <input type="hidden" value="0" name="goback" class="form-post-goback" />
  <div class="row page-title-row">
        
    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
      <h1 class="page-title txt-color-blueDark"><?= $appTitleIco ?><?= prep_app_title($appTitle) ?></h1>
    </div>
      </div>
  <section class="widget-form-content">
    <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
        <div class="onoffswitch-container">
          <span class="onoffswitch-title"><?= $this->lang->line("Estado") ?></span> 
          <span class="onoffswitch">
            <input name="active" value="1" type="checkbox" <? if($dataItem['active'] == 1 || (!$idItem && !$this->input->post())): ?>checked="checked"<? endif ?> class="onoffswitch-checkbox" id="activeForm<?= $wgetId ?>">
            <label class="onoffswitch-label" for="activeForm<?= $wgetId ?>"> 
              <span class="onoffswitch-inner" data-swchon-text="ON" data-swchoff-text="OFF"></span> 
              <span class="onoffswitch-switch"></span>
            </label> 
          </span>
        </div>
      </div>
      <? $this->load->view("app/element/buttons-header") ?>   
          </div>
    <div class="clear-sm"></div>
    <div class="well-white smart-form">
      <fieldset>
        <div class="row">

 <? $field = 'title'; $this->load->view('app/form', array('item' => array(
    'columns' => 6,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Título'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>   
  <? $field = 'cost'; $this->load->view('app/form', array('item' => array(
    'type' => 'number',
    'columns' => 2,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Precio'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
  <? $field = 'promotion'; $this->load->view('app/form', array('item' => array(
    'columns' => 2,
    'type' => 'checkbox',
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('En promoción'),
    'value' => $field,
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'checked' => ($dataItem[$field] > 0)
  ))) ?>  
  <? $field = 'highlight'; $this->load->view('app/form', array('item' => array(
    'columns' => 2,
    'type' => 'checkbox',
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Destacado'),
    'value' => $field,
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'checked' => ($dataItem[$field] > 0)
  ))) ?>  
<? $field = 'id_category'; $this->load->view('app/form', array('item' => array(
    'type' => 'select',
    'columns' => 5,
    'form' => $wgetId,
    'name' => $field,
    'data' => $select['SelectProductCategory'],
    'label' => $this->lang->line('Categoría'),
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'value' => $dataItem[$field],
    'placeholder' => ''
  ))) ?>

<? $field = 'id_size'; $this->load->view('app/form', array('item' => array(
    'type' => 'select',
    'columns' => 4,
    'form' => $wgetId,
    'name' => $field,
    'data' => $select['SelectProductSize'],
    'label' => $this->lang->line('Tamaño'),
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'value' => $dataItem[$field],
    'placeholder' => ''
  ))) ?>
  <? $field = 'id_state'; $this->load->view('app/form', array('item' => array(
    'type' => 'select',
    'columns' => 3,
    'form' => $wgetId,
    'name' => $field,
    'data' => $select['SelectProductState'],
    'label' => $this->lang->line('Stock'),
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'value' => $dataItem[$field],
    'placeholder' => ''
  ))) ?>
<? $field = 'text'; $this->load->view('app/form', array('item' => array(
    'type' => 'textarea',
    'height' => 160,
    'columns' => 10,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Descripción'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
<? $field = 'id_file'; $this->load->view('app/form', array('item' => array(
    'type' => 'filemanager',
    'columns' => 4,
    'form' => $wgetId,
    'name' => $field,
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'allow-navigation' => isset($gallery['default'][$field]['allow-navigation']) ? $gallery['default'][$field]['allow-navigation'] : false,
    'default-location' => isset($gallery['default'][$field]['folder']) ? $gallery['default'][$field]['folder'] : ( isset($gallery['folder']) ? $gallery['folder'] : 0 ),
    'data' => $dataItem,
    'prefix' => 'fm1',
    'label' => $this->lang->line('Imagen'),
    'value' => $dataItem[$field],
    'placeholder' => ''
  ))) ?>
<? $field = 'id_gallery'; $this->load->view('app/form', array('item' => array(
    'type' => 'gallery',
    'columns' => 12,
    'form' => $wgetId,
    'name' => $field,
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'allow-navigation' => isset($gallery['default'][$field]['allow-navigation']) ? $gallery['default'][$field]['allow-navigation'] : false,
    'default-location' => isset($gallery['default'][$field]['folder']) ? $gallery['default'][$field]['folder'] : ( isset($gallery['folder']) ? $gallery['folder'] : 0 ),
    'data' => $dataItem,
    'prefix' => 'fmg1',
    'label' => $this->lang->line('Galería'),
    'value' => $dataItem[$field],
    'placeholder' => ''
  ))) ?>


</div>
<div style="margin-top: 15px">
  <? $field = 'id_product_related'; $this->load->view('app/form', array('item' => array(
      'type' => 'select',
      'columns' => 9,
      'form' => $wgetId,
      'name' => $field,
      'data' => $this->Data->SelectProduct(),
      'label' => $this->lang->line('Productos complementarios'),
      'placeholder' => ''
    ))) ?> 
    <div class="col col-inset col-3">
         <span style="margin-top:20px" class="btn btn-primary add-product"><i class="glyphicon glyphicon-plus"></i> Agregar</span>
      </div>
      <div style="clear:both"></div>
        <ul class="products-list">
        </ul>
</div>

      </fieldset>
      <div class="clear-sm"></div>
    </div>
    <? $this->load->view("app/element/buttons-footer") ?>   
  </section>     
</form>
</div>
<? $this->load->view("script/filemanager/includes") ?>
<script>
$(document).ready(function() {
  var formGlobal = $('#widget-form-<?= $wgetId ?>');

$('.form-post-id_product_related', formGlobal).attr('name', '');
  var createProductRelated = function(id, text){
    var li = $('<li/>');
    li.html(text + '<span class="delete-item" style="cursor:pointer;margin-left:20px"><i class="glyphicon glyphicon-trash"></i></span><input type="hidden" value="' + id + '" name="related[]">')
    li.css('margin-bottom', '5px');
    $('.delete-item', li).click(function(){
      li.remove();
    })
    $('.products-list').append(li);
  };
  $('.add-product', formGlobal).click(function(){
    if(!$('.form-post-id_product_related', formGlobal).val())
      return;
    createProductRelated($('.form-post-id_product_related', formGlobal).val(), $('.form-post-id_product_related option:selected').text());
  });
   <? if($idItem):
   $products = json_decode($dataItem['related']);
   if($products && count($products)):
    foreach($products as $tid): $t = $this->model->DataElement($tid); ?>
    createProductRelated('<?= $t['id_product'] ?>','<?= addslashes($t['title']) ?>');
  <? endforeach  ?>
  <? endif  ?>
  <? endif  ?>

<? if(!$this->MApp->secure->edit):?>
  formGlobal.addClass('form-disabled');
  formGlobal.submit(function(e){
    e.preventDefault();
    e.stopPropagation();
    return false;
  });
<? else: ?>
  <? if($idItem && !$quickOpen): ?>
  App.changeURI('<?= base_url() . "{$appController}/{$appFunction}/element/{$idItem}" ?>');
  <? endif ?>
  formGlobal.validate({ 
    rules : {
      /*'id_category': 'required',
      'id_state': 'required',
      'id_size': 'required',
      'title': 'required',
      'cost': 'required',
      'id_gallery': 'required' */     
    },
    messages : {
    }
  });  
  
  $('.btn.save-action',formGlobal).click(function(){
    $('.form-post-goback', formGlobal).val('0');
    formGlobal.submit();
  });
  $('.btn.save-action-close', formGlobal).click(function(){
    $('.form-post-goback', formGlobal).val('1');
    formGlobal.submit();
  });
  <? endif ?>  
  <? if($quickOpen): ?>
  $('.action-close', formGlobal).click(function(e){
    e.preventDefault();
    window.close();
    return false;
  });
  <? endif ?>
      <? $field = 'id_file'; $this->load->view('script/filemanager/file.js', array('item' => array(
    'form' => $wgetId,
    'name' => $field
  ))) ?>
    <? $field = 'id_gallery'; $this->load->view('script/filemanager/gallery.js', array('item' => array(
    'form' => $wgetId,
    'name' => $field
  ))) ?>
  
});
</script>
<? $this->load->view("common/footer") ?>