<script type="text/javascript">
var DataTableFn = function(){
  var colFilter = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
  
  <? $this->load->view("script/datatable/config.js") ?>
  
  configDT.fnServerParams = function ( aoData ) {
    aoData.push( { "name": "filter-id_category", "value": $('#id_categoryFormSelect<?= $wgetId ?>').val() } );
    aoData.push( { "name": "filter-id_state", "value": $('#id_stateFormSelect<?= $wgetId ?>').val() } );
    aoData.push( { "name": "filter-id_size", "value": $('#id_sizeFormSelect<?= $wgetId ?>').val() } );
    aoData.push( { "name": "filter-id_gallery", "value": $('#id_galleryFormSelect<?= $wgetId ?>').val() } );
    if($('#promotionFormChk<?= $wgetId ?>').prop('checked'))
      aoData.push( { "name": "filter-promotion", "value": 1 } );
    if($('#highlightFormChk<?= $wgetId ?>').prop('checked'))
      aoData.push( { "name": "filter-highlight", "value": 1 } );
    if($('#activeFormChk<?= $wgetId ?>').prop('checked'))
      aoData.push( { "name": "filter-active", "value": 1 } );
    aoData.push( { "name": "filter-text", "value": $('#textFormInput<?= $wgetId ?>').val() } );
    <? $this->load->view("script/datatable/order.js") ?>
  };
  configDT.aoColumns = [
  { "sWidth": "60px", "sClass": "text-align-center widget-filemanager", "sTitle": "<?= $this->lang->line("Imagen") ?>", "mData": "fm1file", "sType":"html", "mRender" : function( data, type, full ){ 
      var type = 0;
      if(data) type = full["fm1type"];
      return (data ? '<a class="no-propagation" href="<?= upload() ?>'+ full["fm1file"] +'<?= thumb_version() ?>" target="_blank">' : '') + '<div data-type="'+type +'" class="file-info type-'+ type +'"><div class="file-ico">' + ((data  && type == 1) ? '<img src="<?= thumb_url() ?>'+ full["id_file"] +'<?= thumb_version() ?>" />' : '' ) +'</div></div>' + (data ? '</a>' : '');
    }},
    
     { "sClass": "text-align-center1", "sTitle": "<?= $this->lang->line("Producto") ?>", "mData": "title", "sType": "string"},
    { "sTitle": "<?= $this->lang->line("Categoría") ?>", "mData": "category", "sType": "string"},
    { "sClass": "text-align-center1", "sTitle": "<?= $this->lang->line("Precio") ?>", "mData": "cost", "sType": "string"},
    { "sTitle": "<?= $this->lang->line("Tamaño") ?>", "mData": "size", "sType": "string"},
    { "sTitle": "<?= $this->lang->line("Stock") ?>", "mData": "state", "sType": "string"},
    { "sClass": "text-align-center", "sWidth": "40px", "sTitle": "<?= $this->lang->line("Destacado en home") ?>", "mData": "highlight", "sType": "html", "mRender" : function( data, type, full ){ 
      if(!data || !parseInt(data)) return '<?= $this->lang->line("No") ?>';
      return '<?= $this->lang->line("Si") ?>';
    }},
    { "sClass": "text-align-center", "sWidth": "40px", "sTitle": "<?= $this->lang->line("Activo") ?>", "mData": "active", "sType": "html", "mRender" : function( data, type, full ){ 
      if(!data || !parseInt(data)) return '<?= $this->lang->line("No") ?>';
      return '<?= $this->lang->line("Si") ?>';
    }},
    { "sTitle": "<?= $this->lang->line("Acciones") ?>", "sWidth": "60px", "mData": "id", "bSortable": false, "bSearchable": false, "sType": "html", "mRender" : function( data, type, full ){ 
      return '<ul class="table-actions smart-form">' +         
      '<li><a title="<?= $this->lang->line($this->MApp->secure->edit ? "Editar" : "Ver") ?>" href="<?= base_url() . "{$appController}/{$appFunction}" ?>/element/' + data + '" class="btn btn-xs btn-default edit-button" type="button"><i class="fa fa-actions <?= $this->MApp->secure->edit ? "fa-pencil" : "fa-search" ?>"></i></a></li>' +
      <? if($this->model->mconfig['duplicate']): ?>'<li><a title="<?= $this->lang->line("Duplicar") ?>" href="<?= base_url() . "{$appController}/{$appFunction}" ?>/duplicate/' + data + '" class="btn btn-xs btn-default duplicate-button<?= ($this->model->mconfig['new-element'] && $this->MApp->secure->edit) ? "" : " disabled" ?>" type="button"><i class="fa fa-actions fa-copy"></i></a></li>' + <? endif ?>
      '<li><a title="<?= $this->lang->line("Eliminar") ?>" href="<?= base_url() . "{$appController}/{$appFunction}" ?>/delete/' + data + '" class="btn btn-xs btn-default delete-button<?= $this->MApp->secure->delete ? "" : " disabled" ?>" type="button"><i class="fa fa-actions fa-trash-o"></i></a></li>' + 
      '</ul>';
    }}
  ];
  
  <? $this->load->view("script/datatable/script.js") ?>  
  
};
$(document).ready(function() {
  setTimeout(DataTableFn, 10);
});
</script>