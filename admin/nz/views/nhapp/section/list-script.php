<script type="text/javascript">
var DataTableFn = function(){
  var colFilter = [1, 2, 3, 4, 5, 6];
  
  <? $this->load->view("script/datatable/config.js") ?>
  
  configDT.fnServerParams = function ( aoData ) {
    if($('#activeFormChk<?= $wgetId ?>').prop('checked'))
      aoData.push( { "name": "filter-active", "value": 1 } );
    aoData.push( { "name": "filter-text", "value": $('#textFormInput<?= $wgetId ?>').val() } );
    <? $this->load->view("script/datatable/order.js") ?>
  };
  configDT.aoColumns = [
    { "sClass": "text-align-center1", "sTitle": "<?= $this->lang->line("Sección") ?>", "mData": "title", "sType": "string"},
    { "sClass": "text-align-center", "sWidth": "40px", "sTitle": "<?= $this->lang->line("Activo") ?>", "mData": "active", "sType": "html", "mRender" : function( data, type, full ){ 
      if(!data || !parseInt(data)) return '<?= $this->lang->line("No") ?>';
      return '<?= $this->lang->line("Si") ?>';
    }},
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