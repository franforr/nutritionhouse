<div class="jarviswidget-editbox widget-datatable-filters">
  <fieldset class="smart-form">
    <div class="row">    
      <section class="col-filter col col-2">
        <label for="id_gimFormSelect<?= $wgetId ?>" class="label"><?= $this->lang->line('Gimnasio') ?></label>
        <label class="select">
          <?= form_dropdown('', $select['SelectGim'], '', "id='id_gimFormSelect{$wgetId}'") ?>
          <i></i>
        </label>
      </section>    
      <section class="col-filter col col-2">
        <label for="id_stateFormSelect<?= $wgetId ?>" class="label"><?= $this->lang->line('Estado') ?></label>
        <label class="select">
          <?= form_dropdown('', $select['SelectCartState'], '', "id='id_stateFormSelect{$wgetId}'") ?>
          <i></i>
        </label>
      </section>    
      <section class="col-filter col col-2">
        <label for="id_shippingFormSelect<?= $wgetId ?>" class="label"><?= $this->lang->line('Envío') ?></label>
        <label class="select">
          <?= form_dropdown('', $select['SelectCartShipping'], '', "id='id_shippingFormSelect{$wgetId}'") ?>
          <i></i>
        </label>
      </section>          <section class="col col-4">
        <label class="label"><?= $this->lang->line("Contenido") ?></label>
        <label class="input">
          <input type="text" id="textFormInput<?= $wgetId ?>" placeholder="<?= $this->lang->line("Escriba una palabra") ?>">
        </label>
      </section>
    </div>
    <div class="row">
      <section class="col col-2">
        <button type="button" id="button-datatable-search<?= $wgetId ?>" class="btn btn-primary pull-left element-no-label">
          <?= $this->lang->line("Buscar") ?>
        </button>
      </section>
    </div>
  </fieldset>
</div>