<table class="listado" width="100%">

<tr class="menu">
  <td class="nombre">Nº Comprobante</td>
  <td class="nombre">Mandante</td>
  <td class="nombre">Cuenta</td>
  <td class="nombre">Monto</td>
  <td class="nombre">Fecha</td>
</tr>

<div class="content_tabla">
<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">
  <td><?php echo $val->id;?></td>
  <td><?php echo $val->razon_social;?></td>
  <td>

  <a href="<?php echo site_url().'/admin/cuentas/form/editar/'.$val->id_cuenta;?>" title=""><?php echo $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat;?></a></td>
  <td><?php echo '$'.number_format($val->monto,0,',','.');?></td>
  <td><?php echo date("d-m-Y", strtotime($val->fecha_pago));?></td>


</tr>

<?php ++$i;endforeach;?>

</table>
</div>