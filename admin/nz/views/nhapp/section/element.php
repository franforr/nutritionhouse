<? if( !AJAX ) $this->load->view("common/header") ?>
<div class="widget-app-element" id="main">
<form class="widget-app-element-form" id="widget-form-<?= $wgetId ?>" method="post" action="<?= base_url() . ($idItem ? "{$appController}/{$appFunction}/element/{$idItem}" . ($quickOpen ? "/quick" : "") : "{$appController}/{$appFunction}/element/new") ?>" role="form">
  <input type="hidden" value="0" name="goback" class="form-post-goback" />
  <div class="row page-title-row">
        
    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
      <h1 class="page-title txt-color-blueDark"><i class="page-title-ico <?= $appTitleIco ?>"></i> <?= prep_app_title($appTitle) ?></h1>
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
        
<? $field = 'section'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Sección'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
<? $field = 'title'; $this->load->view('app/form', array('item' => array(
    'columns' => 4,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Título'),
    'value' => $dataItem[$field],
    'error' => $this->validation->error($field),
    'class' => $this->validation->error_class($field),
    'placeholder' => ''
  ))) ?>
<? $field = 'text'; $this->load->view('app/form', array('item' => array(
    'type' => 'textarea',
    'height' => 160,
    'columns' => 6,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Texto'),
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
<? $field = 'num'; $this->load->view('app/form', array('item' => array(
    'type' => 'number',
    'columns' => 2,
    'form' => $wgetId,
    'name' => $field,
    'label' => $this->lang->line('Orden'),
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
<? $this->load->view("script/filemanager/includes") ?>
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
      /*'section': 'required',
      'title': 'required',
      'num': 'required' */     
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
  
});
</script>
<? $this->load->view("common/footer") ?>