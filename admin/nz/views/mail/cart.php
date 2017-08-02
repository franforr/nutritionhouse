<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title><?= $title ?> - <?= $this->config->item('client', 'app') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
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
<h1 style=" display:block; border:0 ; border-bottom:none; padding:20px 20px; background-color:#FFF; font-size:20px; color:#333333; font-family:Arial, Helvetica, sans-serif; float: left" class="mcenter">Pedido Nº <?= $cart['code'] ?></h1>
<img src="<?= layout('') ?>logo-print.png" height="70" style="margin:10px; margin-left:20px; float:right; display:block" alt="<?= $this->config->item('client', 'app') ?>" class="mcenter"/>
</td>
<td bgcolor="#EFEFEF">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#EFEFEF">&nbsp;</td>
<td width="600" bgcolor="#EFEFEF">
<div style=" display:block; border:0 ; border-bottom:none; padding:15px 20px; background-color:#FFF; font-size:12px; color:#333333; font-family:Arial, Helvetica, sans-serif">

<div style="font-family: 'Arial';">
	<?= $title ? '<h2 style="margin-top:0">'.$title.'</h2>' : '' ?>
	<?= $message ?>
	<?php $items = $cart['items']; ?>

	<h2>Datos personales</h2>
	<b>Nombre:</b> <?= $cart['name'] ?> <br>
	<b>Apellido:</b> <?= $cart['lastname'] ?> <br>
	<b>Dirección:</b> <?= $cart['address'] ?> <br>
	<b>Departamento:</b> <?= $cart['province'] ?> <br>
	<b>Ciudad:</b> <?= $cart['city'] ?> <br>
	<b>Teléfono:</b> <?= $cart['phone'] ?> <br>
	<b>Código postal:</b> <?= $cart['postal_code'] ?> <br>

	<hr style="border:0;border-bottom:1px dotted gray;">
	<h2>Datos del pedido</h2>
	<?php if ($cart['id_gim']): ?>
		<b>Has realizado este pedido en gimnasio: </b> <?= $cart['gim'] ?> <br>
	<?php endif ?>

	<b>Has indicado como método de envío: </b> <?= $cart['shipping'] ?> <br>
	<b>El pedido se encuentra en estado: </b> <?= $cart['state'] ?> <br>

	<hr style="border:0;border-bottom:1px dotted gray;">

	<h2>Detalles del pedido</h2>

    <table class="table table-hover" style="text-align:left;">
      <thead>
        <tr>
          <th>Producto</th>
          <th></th>
          <th>Categoría</th>
          <th>Tamaño</th>
          <th>Cantidad</th>
          <th>Costo individual</th>
          <th>Costo c/descuento</th>
          <th>Costo</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $count_items = 0;
         foreach ($items as $key => $item):
        $count_items = $count_items + $item->items ?>
        <tr>
          <th><img src="<?= thumb($item->file,100,100) ?>" style="background:gray;with:100px;height:100px;"></th>
          <th style=" vertical-align: middle; "><strong><?= $item->title ?></strong></th>          
          <th style=" vertical-align: middle; "><small><?= $item->category ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= $item->size ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= $item->items ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= round($item->cost_base,2) ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= round($item->cost,2) ?></small></th>          
          <th style=" vertical-align: middle; "><small><?= round($item->cost * $item->items,2) ?></small></th>          
        </tr>
        <?php endforeach ?>
      </tbody>
      <thead>
        <tr>
          <th colspan="5">Subtotal</th>
          <th colspan="1"><?= $count_items ?></th>
          <th colspan="2"><?= $cart['subtotal'] ?></th>
        </tr>
        <?php if ($cart['coupon_type'] == 1): ?>
        <tr>
          <th colspan="5">Cupón de descuento</th>
          <th colspan="1"><?= $cart['coupon_value'] ?>%</th>
          <th colspan="2">-<?= $cart['desc1'] ?></th>
        </tr>
        <?php elseif ($cart['coupon_type'] == 2): ?>
        <tr>
          <th colspan="5">Vale de descuento</th>
          <th colspan="1"></th>
          <th colspan="2">-<?= $cart['desc1'] ?></th>
        </tr>
        <?php endif ?>
        <?php if ($cart['id_gim']): ?>
        <tr>
          <th colspan="5">Descuento por gimnasio</th>
          <th colspan="1"></th>
          <th colspan="2">-<?= $cart['gim_discount'] ?></th>
        </tr>
        <?php endif ?>
        <tr>
          <th colspan="1">Envio</th>
          <th colspan="5" style="color:grey"><?= $cart['province'] ?></th>
          <th colspan="2"><?= $cart['shipping_cost'] ?></th>
        </tr>
        <tr>
          <th colspan="5">Total</th>
          <th colspan="1"></th>
          <th colspan="2" style="color:green">$ <?= $cart['total'] ?></th>
        </tr>

      </thead>
    </table>

</div>

</div>
<div style="display:block; background-color:#999999; padding:5px 20px;font-size:12px; color:#fff; font-family:Arial, Helvetica, sans-serif">
<p><small>Si usted no se ha registrado en <?= $this->config->item('client', 'app') ?> ignore este correo
 o reportelo respondiendo este mail.</small></p>

</div>
<?/*<p style="font-size:9px; padding:10px 20px; text-align:center; color:#999999; line-height: 12px; font-family:Arial, Helvetica, sans-serif">Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum Texto disclaimer lorem ipsum.</p>*/?>
</table>
</body>
</html>