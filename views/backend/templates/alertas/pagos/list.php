<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Cuentas (<?php echo number_format($total,0,',','.');?>)</strong></h2>
  </div>
</div>
<style>
table.listado tr,table.listado input, table.listado select {
    font-size: 11px;
    margin: 5px 0 5px 5px;
	line-height: 26px !important;
}
</style>
<?php if (count($lists)>0): ?>

<div class="tabla-listado">

	<div class="content_tabla">
<?php /*echo '<pre>'; print_r($lists); echo '</pre>';*/?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  
  <td width="13%" class="nombre">Mandante</td>
  <td width="13%" class="nombre">Deudor Rut</td>
  <td width="13%" class="nombre">Deudor Nombre</td>
  <td width="13%" class="nombre">Dias (Diferencia)</td>
  <td width="13%" class="nombre">Fecha Último pago</td>

</tr>
<?php $i=1; $check_id=1;
foreach ($lists as $key=>$val):?>

<tr id="tools_" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>">

	<td><?php echo $val->codigo_mandante;?></td>
	<td><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>"><?php echo $val->usuarios_rut;?></a></td>
	<td><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>"><?php echo $val->usuarios_nombres;?> <?php echo $val->usuarios_ap_pat;?> <?php echo $val->usuarios_ap_mat;?></a></td>
	<td style="color:#F00;text-align: center;"><?php echo $val->dias_diferencia;?></td>
	<td style="text-align: center;"><?php echo date("d-m-Y",strtotime($val->fecha_pago))?></td>

 <?php $row_current=array(); $row_current=$val; //include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
</tr>

<?php ++$i;endforeach;?>

</table>

<?php echo $this->pagination->create_links(); ?>
</div>
</div>
<?php endif;?>