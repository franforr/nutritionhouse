<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title><?= $title ?> - <?= $this->config->item('client', 'app') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
	* {
		font-family: 'sans-serif';
	}
	@media screen and (max-width:600px) {
	  .mcenter {
	  	float: none;
	  	margin: 0 auto;
	  }
	}
</style>
</head>
<body style="background-color: #EFEFEF;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td bgcolor="#EFEFEF">&nbsp;</td>
<td width="600" height="50" bgcolor="#EFEFEF">&nbsp;</td>
<td bgcolor="#EFEFEF">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#EFEFEF">&nbsp;</td>
<td width="600" height="40" bgcolor="#ffffff">
<h1 style=" display:block; border:0 ; border-bottom:none; padding:20px 20px; background-color:#FFF; font-size:20px; color:#333333; font-family:Arial, Helvetica, sans-serif; float: left" class="mcenter"><?= $title ?></h1>
<img src="<?= layout('') ?>logo-print.png" height="70" style="margin:10px; margin-left:20px; float:right; display:block" alt="<?= $this->config->item('client', 'app') ?>" class="mcenter"/>
</td>
<td bgcolor="#EFEFEF">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#EFEFEF">&nbsp;</td>
<td width="600" bgcolor="#EFEFEF">
<div style=" display:block; border:0 ; border-bottom:none; padding:15px 20px; background-color:#FFF; font-size:12px; color:#333333; font-family:Arial, Helvetica, sans-serif">

<div>
	<?= $message ?>
</div>

<p><br/>Atentamente, staff de <?= $this->config->item('client', 'app') ?>. </p>
</div>
<div style="display:block; background-color:#999999; padding:5px 20px;font-size:12px; color:#fff; font-family:Arial, Helvetica, sans-serif">
<p><small>Si usted no se ha registrado en <?= $this->config->item('client', 'app') ?> ignore este correo
 o reportelo respondiendo este mail.</small></p>

</div>
<?/*<p style="font-size:9px; padding:10px 20px; text-align:center; color:#999999; line-height: 12px; font-family:Arial, Helvetica, sans-serif">Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum.</p>*/?>
</table>
</body>
</html>