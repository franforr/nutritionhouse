<?php if( isset($headers) && $headers ):?>
<?= isset($headers['head']) ? $headers['head'] : "<head>" ?>

<meta charset="utf-8">
<meta name="robots" content="index,follow">
<title id="head-title"><?php echo $headers['head-title'] ?></title>
<meta name="twitter:title" content="<?php echo $headers['title'] ?>" />
<meta property="og:title" name="og:title" content="<?php echo $headers['title'] ?>" />
<meta id="head-description" name="description" content="<?php echo $headers['description'] ?>" />
<meta property="og:description" name="og:description" content="<?php echo $headers['description'] ?>" />
<meta name="twitter:description" content="<?php echo $headers['description'] ?>">
<meta id="head-keywords" name="keywords" content="<?php echo $headers['keywords'] ?>" /><?php else: ?>
<head>
<meta charset="utf-8">
<meta name="robots" content="index,follow">
<title id="head-title"><?php echo $this->config->item('client', 'app') ?></title>
<meta property="og:title" name="og:title" content="<?php echo $this->config->item('client', 'app') ?>" />
<meta id="head-description" name="description" content="" />
<meta property="og:description" name="og:description" content="" />
<meta id="head-keywords" name="keywords" content="" /><?php endif ?>
<?php if( isset($headers['og:image']) ):?>
<meta name="twitter:image" content="<?php echo $headers['share-image'] ?>" />
<meta property="og:image" name="og:image" content="<?php echo $headers['share-image'] ?>" />
<?php else: ?>
<meta name="twitter:image" content="<?= layout('default-share.png') ?>" />
<meta property="og:image" name="og:image" content="<?= layout('default-share.jpg') ?>" />
<?php endif ?>
<?php if( isset($headers['og:url']) ):?>
<meta property="og:url" name="og:url" content="<?php echo $headers['og:url'] ?>" />
<?php else: ?>
<meta property="og:url" name="og:url" content="<?php echo current_url() ?>" />
<?php endif ?>
<?php if( isset($headers['og:type']) ):?>
<meta property="og:type" name="og:type" content="<?= $headers['og:type'] ?>" />
<?php else: ?>
<meta property="og:type" name="og:type" content="website" />
<?php endif ?>
<meta property="fb:app_id" content="<?= $this->config->item('fb-app-id', 'app') ?>" />