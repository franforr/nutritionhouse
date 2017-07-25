<script type="text/javascript">
var DataTableFn = function(){
  var colFilter = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18];
  
  <? $this->load->view("script/datatable/config.js") ?>
  
  configDT.fnServerParams = function ( aoData ) {
    aoData.push( { "name": "filter-id_gim", "value": $('#id_gimFormSelect<?= $wgetId ?>').val() } );
    aoData.push( { "name": "filter-id_state", "value": $('#id_stateFormSelect<?= $wgetId ?>').val() } );
    aoData.push( { "name": "filter-id_shipping", "value": $('#id_shippingFormSelect<?= $wgetId ?>').val() } );
    aoData.push( { "name": "filter-text", "value": $('#textFormInput<?= $wgetId ?>').val() } );

    <? $this->load->view("script/datatable/order.js") ?>
  };
  configDT.aoColumns = [
      { "sTitle": "<?= $this->lang->line("Nº") ?>", "mData": "code", "sType": "string"},
      { "sTitle": "<?= $this->lang->line("Estado") ?>", "mData": "state", "sType": "string"},
          { "bVisible": true, "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("E-mail") ?>", "mData": "mail", "sType": "string"},
  { "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("Nombre") ?>", "mData": "name", "sType": "string"},
    { "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("Apellido") ?>", "mData": "lastname", "sType": "string"},
    { "sTitle": "<?= $this->lang->line("Gimnasio") ?>", "mData": "gim", "sType": "string"},
    { "bVisible": false, "sTitle": "<?= $this->lang->line("Envío") ?>", "mData": "shipping", "sType": "string"},
    { "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("Dirección") ?>", "mData": "address", "sType": "string"},
    { "bVisible": false, "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("Fecha creación") ?>", "mData": "created", "sType": "html", "mRender" : function( data, type, full ){ 
      if(!data || data == '0000-00-00 00:00') return '-';
      return Date.fromMysql(data).format("dd/MM/yyyy hh:mm:ss");
    }},
    { "bVisible": false, "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("Fecha modificación") ?>", "mData": "modified", "sType": "html", "mRender" : function( data, type, full ){ 
      if(!data || data == '0000-00-00 00:00') return '-';
      return Date.fromMysql(data).format("dd/MM/yyyy hh:mm:ss");
    }},
    { "bVisible": false, "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("Descuento") ?>", "mData": "coupon_1", "sType": "string"},
    { "bVisible": false, "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("Subtotal") ?>", "mData": "subtotal", "sType": "string"},
    { "bVisible": false, "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("Descuento") ?>", "mData": "desc1", "sType": "string"},
    { "bVisible": true, "sClass": "text-align-center", "sTitle": "<?= $this->lang->line("Total") ?>", "mData": "total", "sType": "string"},
    { "sTitle": "<?= $this->lang->line("Acciones") ?>", "sWidth": "60px", "mData": "id", "bSortable": false, "bSearchable": false, "sType": "html", "mRender" : function( data, type, full ){ 
      return '<ul class="table-actions smart-form">' +         
      '<li><a title="<?= $this->lang->line($this->MApp->secure->edit ? "Editar" : "Ver") ?>" href="<?= base_url() . "{$appController}/{$appFunction}" ?>/element/' + data + '" class="btn btn-xs btn-default edit-button" type="button"><i class="fa fa-actions <?= $this->MApp->secure->edit ? "fa-pencil" : "fa-search" ?>"></i></a></li>' +
            '</ul>';
    }}
  ];
  
  <? $this->load->view("script/datatable/script.js") ?>  
  
};
$(document).ready(function() {
  setTimeout(DataTableFn, 10);
});
</script>