<?php
$lang = $this->config->item('lang', 'app');
$layoutversion = $this->config->item('layout-version', 'app');
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang ?>" lang="<?php echo $lang ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-language" content="<?php echo $lang ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="initial-scale=1.0" />
<?php $this->load->view('common/metatags') ?>
<link rel="icon" href="<?= layout('favicon.png' . $layoutversion) ?>" type="image/x-icon" />
<link rel="shortcut icon" href="<?= layout('favicon.png' . $layoutversion) ?>" type="image/x-icon" />
<meta name="theme-color" content="#e82a51">
<link rel="stylesheet" href="<?= layout('css/styles.css?v='.$layoutversion) ?>">

<script src="<?= layout('js/vendor/jquery-1.11.1.min.js?v='.$layoutversion) ?>"></script>
<script src="<?= layout('js/vendor/tether.min.js') ?>"></script>
<script src="<?= layout('js/vendor/page.js') ?>"></script>
<script src="<?= layout('js/vendor/jquery-nanobar.min.js?v='.$layoutversion) ?>"></script>
<script src="<?= layout('js/app.js?v='.$layoutversion) ?>"></script>
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php $this->load->view('common/analytics') ?>
</head>
<body id="app-body" class="app-body lang-<?php echo $lang ?>">
<wrapper>
<?php //$this->load->view('common/app-fb') ?>
<script>App.Init();</script>
<div id="routes" class="none"><? $routes = array();

		foreach ($this->routes as $key => $route) {
			if( isset($route['pager']) )
				array_push($routes, $route['pager']);
		}

	    echo json_encode( $routes ); ?></div>