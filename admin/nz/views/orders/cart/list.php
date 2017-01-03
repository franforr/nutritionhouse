<? if( !AJAX ) $this->load->view("common/header") ?>
<div id="main">
  <div class="widget-app-table-list">
    <div class="row page-title-row">
      <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
        <h1 class="page-title txt-color-blueDark"><?= $appTitleIco ?><?= prep_app_title($appTitle) ?></h1>
      </div>
    </div>
    <div class="clear-sm"></div>
    <section>
      <div class="jarviswidget jarviswidget-color-blueDark jarviswidget-no-head" data-widget-editbutton="false">
        <div class="widget-datatable">
          <? $this->load->view("app/datatable/columns") ?>
          <? $this->load->view("{$appController}/{$appFunction}/list-filters") ?>
          <div class="widget-body no-padding">
            <table width="100%" id="datatable<?= $wgetId ?>" class="table table-striped table-hover">
              <thead></thead>                
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<? $this->load->view("script/datatable/includes") ?>
<? $this->load->view("{$appController}/{$appFunction}/list-script") ?>
<? $this->load->view("common/footer") ?>