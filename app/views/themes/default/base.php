<?php if( isset($html) && $html ) $this->load->view('common/html') ?>
<?php if( isset($header) && $header ) tview('header') ?>
<main id="main" class="theme theme-<?= $this->data['theme'] ?> section-<?= $this->data['section'] ?>">
	<? tview( 'section/' . $this->data['section'] ); ?>
	<div id="data" 
		 data-url="<?= base_url(); ?>"
		 data-layout="<?= layout() ?>"
		 data-section="<?= $this->data['section']; ?>"
		 data-theme="<?= $this->data['theme']; ?>" ></div>

</main>
<?php if( isset($footer) && $footer ) tview('footer') ?>

