<? if( !AJAX ) $this->load->view("common/header") ?>
<div class="widget-app-element" id="main">
<form class="widget-app-element-form" id="widget-form-<?= $wgetId ?>" method="post" action="<?= base_url() . ($idItem ? "{$appController}/{$appFunction}/element/{$idItem}" . ($quickOpen ? "/quick" : "") : "{$appController}/{$appFunction}/element/new") ?>" role="form">
  <input type="hidden" value="0" name="goback" class="form-post-goback" />
  <div class="row page-title-row">
        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
      <h1 class="page-title txt-color-blueDark"><?= $appTitleIco ?><?= prep_app_title($appTitle) ?></h1>
    </div>
    <? $this->load->view("app/element/buttons-header", array('alt' => true)) ?>   
      </div>
  <section class="widget-form-content">
    <div class="row">
        </div>
    <div class="clear-sm"></div>
    <div class="well-white smart-form">
      <fieldset>
        <div class="row">

<? $field = 'mail'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('E-mail'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>  
  <? $field = 'name'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Nombre'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
<? $field = 'lastname'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Apellido'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>   
  <? $field = 'address'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Dirección'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
<? $field = 'postal_code'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Código Postal'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
<? $field = 'province'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Provincia'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
<? $field = 'city'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Ciudad'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
<? $field = 'phone'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Teléfono'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>   
<? $field = 'id_gim'; $this->load->view('app/form', array('item' => array(
    'type' => 'select',
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'data' => $select['SelectGim'],
    'label' => $this->lang->line('Gimnasio'),
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'value' => $dataItem[$field],
    'placeholder' => ''
  ))) ?>
<? $field = 'id_state'; $this->load->view('app/form', array('item' => array(
    'type' => 'select',
    'columns' => 4,
    'form' => $wgetId,
    'name' => $field,
    'data' => $select['SelectCartState'],
    'label' => $this->lang->line('Estado'),
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'value' => $dataItem[$field],
    'placeholder' => ''
  ))) ?>
<? $field = 'id_shipping'; $this->load->view('app/form', array('item' => array(
    'type' => 'select',
    'columns' => 4,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'data' => $select['SelectCartShipping'],
    'label' => $this->lang->line('Envío'),
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'value' => $dataItem[$field],
    'placeholder' => ''
  ))) ?>


<? $field = 'created'; $this->load->view('app/form', array('item' => array(
    'columns' => 2,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Fecha creación'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
<? $field = 'modified'; $this->load->view('app/form', array('item' => array(
    'columns' => 2,
    'disabled' => true,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Fecha modificación'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>

  <?php $items = $dataItem['items']; ?>
  <div class="clearfix"></div>
  <div class="col col-md-12">
  <h1>Pedido: </h1>

    <table class="table table-hover">
      <thead>
        <tr>
          <th>Producto</th>
          <th></th>
          <th>Categoría</th>
          <th>Tamaño</th>
          <th>Cantidad</th>
          <th>Costo individual</th>
          <th>Costo c/descuento</th>
          <th>Costo</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $count_items = 0;
         foreach ($items as $key => $item):
        $count_items = $count_items + $item->items ?>
        <tr>
          <th><img src="<?= thumb($item->file,100,100) ?>" style="background:gray;with:100px;height:100px;"></th>
          <th style=" vertical-align: middle; "><strong><?= $item->title ?></strong></th>          
          <th style=" vertical-align: middle; "><small><?= $item->category ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= $item->size ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= $item->items ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= round($item->cost_base,2) ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= round($item->cost,2) ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= round($item->cost * $item->items,2) ?></small></th>          
        </tr>
        <?php endforeach ?>
      </tbody>
      <thead>
        <tr>
          <th colspan="5">Subtotal</th>
          <th colspan="1"><?= $count_items ?></th>
          <th colspan="2"><?= $dataItem['subtotal'] ?></th>
        </tr>
        <?php if ($dataItem['coupon_type'] == 1): ?>
        <tr>
          <th colspan="5">Cupón de descuento</th>
          <th colspan="1"><?= $dataItem['coupon_value'] ?>%</th>
          <th colspan="2">-<?= $dataItem['desc1'] ?></th>
        </tr>
        <?php elseif ($dataItem['coupon_type'] == 2): ?>
        <tr>
          <th colspan="5">Vale de descuento</th>
          <th colspan="1"></th>
          <th colspan="2">-<?= $dataItem['desc1'] ?></th>
        </tr>
        <?php endif ?>
        <?php if ($dataItem['id_gim']): ?>
        <tr>
          <th colspan="5">Descuento por gimnasio</th>
          <th colspan="1"></th>
          <th colspan="2">-<?= $dataItem['gim_discount'] ?></th>
        </tr>
        <?php endif ?>
        <tr>
          <th colspan="1">Envio</th>
          <th colspan="5" style="color:grey"><?= $dataItem['province'] ?></th>
          <th colspan="2"><?= $dataItem['shipping_cost'] ?></th>
        </tr>
        <tr>
          <th colspan="5">IVA</th>
          <th colspan="1"></th>
          <th colspan="2"><?= $dataItem['iva'] ?></th>
        </tr>
        <tr>
          <th colspan="5">Total</th>
          <th colspan="1"></th>
          <th colspan="2" style="color:green">$ <?= $dataItem['total'] ?></th>
        </tr>
        <tr>
          <th colspan="1">Comisión gimasio</th>
          <th colspan="5" style="color:grey"><?= $dataItem['gim'] ?></th>
          <th colspan="2" style="color:red">$ <?= $dataItem['gim_comission'] ?></th>
        </tr>
      </thead>
    </table>

  </div>
<div class="clearfix"></div>
<? $field = 'comments'; $this->load->view('app/form', array('item' => array(
    'type' => 'textarea',
    'height' => 100,
    'columns' => 12,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Comentarios'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>


      </div>
      </fieldset>
      <div class="clear-sm"></div>
    </div>
    <? $this->load->view("app/element/buttons-footer") ?>   
  </section>     
</form>
</div>
<script>
$(document).ready(function() {
  var formGlobal = $('#widget-form-<?= $wgetId ?>');  
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
      /*'id_gim': 'required',
      'id_state': 'required',
      'id_shipping': 'required',
      'name': 'required',
      'lastname': 'required',
      'address': 'required',
      'postal_code': 'required',
      'province': 'required',
      'city': 'required',
      'phone': 'required',
      'mail': 'required',
      'created': 'required',
      'modified': 'required',
      'coupon_1': 'required',
      'subtotal': 'required',
      'desc1': 'required',
      'total': 'required' */     
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
    
});
</script>
<? $this->load->view("common/footer") ?>