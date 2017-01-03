<div class="jarviswidget-editbox widget-datatable-filters">
  <fieldset class="smart-form">

    <div class="row">
          
      <section class="col-filter col col-4">
        <label for="id_categoryFormSelect<?= $wgetId ?>" class="label"><?= $this->lang->line('Categoría') ?></label>
        <label class="select">
          <?= form_dropdown('', $select['SelectProductCategory'], '', "id='id_categoryFormSelect{$wgetId}'") ?>
          <i></i>
        </label>
      </section>    
      <section class="col-filter col col-4">
        <label for="id_stateFormSelect<?= $wgetId ?>" class="label"><?= $this->lang->line('Stock') ?></label>
        <label class="select">
          <?= form_dropdown('', $select['SelectProductState'], '', "id='id_stateFormSelect{$wgetId}'") ?>
          <i></i>
        </label>
      </section>    
      <section class="col-filter col col-4">
        <label for="id_sizeFormSelect<?= $wgetId ?>" class="label"><?= $this->lang->line('Tamaño') ?></label>
        <label class="select">
          <?= form_dropdown('', $select['SelectProductSize'], '', "id='id_sizeFormSelect{$wgetId}'") ?>
          <i></i>
        </label>
      </section>
      <section class="col-filter col col-3">
        <label for="promotionFormChk<?= $wgetId ?>" class="checkbox">
          <input id='promotionFormChk<?= $wgetId ?>' value='1' type='checkbox' class='post' name='promotion' />
          <i></i>
          <?= $this->lang->line('Solo promociones') ?>
        </label>
      </section>
      <section class="col-filter col col-3">
        <label for="highlightFormChk<?= $wgetId ?>" class="checkbox">
          <input id='highlightFormChk<?= $wgetId ?>' value='1' type='checkbox' class='post' name='highlight' />
          <i></i>
          <?= $this->lang->line('Solo destacados') ?>
        </label>
      </section>
      <section class="col-filter col col-3">
        <label for="activeFormChk<?= $wgetId ?>" class="checkbox">
          <input id='activeFormChk<?= $wgetId ?>' value='1' type='checkbox' class='post' name='active' />
          <i></i>
          <?= $this->lang->line('Solo activos') ?>
        </label>
      </section>
      </div> 
      <div class="row">
      <section class="col col-4">
        <label class="label"><?= $this->lang->line("Contenido") ?></label>
        <label class="input">
          <input type="text" id="textFormInput<?= $wgetId ?>" placeholder="<?= $this->lang->line("Escriba una palabra") ?>">
        </label>
      </section>
      <section class="col col-6">
        <button type="button" id="button-datatable-search<?= $wgetId ?>" class="btn btn-primary pull-left element-no-label">
          <?= $this->lang->line("Buscar") ?>
        </button>
      </section>
    </div>
     
    
  </fieldset>
</div>