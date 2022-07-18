<div class="table-m-sep">
  <div class="table-m-sep-title">
 	 <h2><strong>Pagos (<?php echo number_format($total,0,',','.');?>)</strong></h2>
  </div>
</div>
<div class="agregar-noticia">
<div class="">
<?php 
$rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
$id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
$fecha_day = '0';if (isset($_REQUEST['fecha_day'])){$fecha_day = $_REQUEST['fecha_day'];}
$fecha_month = '0';if (isset($_REQUEST['fecha_month'])){$fecha_month = $_REQUEST['fecha_month'];}
$fecha_year = '0';if (isset($_REQUEST['fecha_year'])){$fecha_year = $_REQUEST['fecha_year'];}
$fecha_f_day = '0';if (isset($_REQUEST['fecha_f_day'])){$fecha_f_day = $_REQUEST['fecha_f_day'];}
$fecha_f_month = '0';if (isset($_REQUEST['fecha_f_month'])){$fecha_f_month = $_REQUEST['fecha_f_month'];}
$fecha_f_year = '0';if (isset($_REQUEST['fecha_f_year'])){$fecha_f_year = $_REQUEST['fecha_f_year'];}

	echo form_open(site_url().'/admin/cuentas/reporte/pagos/',array('id' => 'form_reg'));
	
  echo '<div class="campo">';
  echo form_label('Rut', 'rut'/*,$attributes*/);
	echo form_input('rut', $rut);
	echo form_error('rut');
  echo '</div>';

  echo '<div class="campo">';
	echo form_label('Procurador', 'id_procurador'/*,$attributes*/);
	echo form_dropdown('id_procurador', $procuradores, $id_procurador);
	echo form_error('id_procurador');
  echo '</div>';

  echo '<div class="campo">';
	echo form_label('Mandante', 'id_mandante'/*,$attributes*/);
	echo form_dropdown('id_mandante', $mandantes, $id_mandante);
	echo form_error('id_mandante');
	echo '</div>';

  echo '<div class="campo">';
	echo form_label('Desde', 'fecha_year'/*,$attributes*/);
  echo day_dropdown('fecha_day',$fecha_day).month_dropdown('fecha_month',$fecha_month).year_dropdown('fecha_year',$fecha_year,2010);
  echo form_error('fecha_year');
  echo '</div>';

  echo '<div class="campo">';
  echo form_label('Hasta', 'fecha_f_year'/*,$attributes*/);
  echo day_dropdown('fecha_f_day',$fecha_f_day).month_dropdown('fecha_f_month',$fecha_f_month).year_dropdown('fecha_f_year',$fecha_f_year,2010);
  echo form_error('fecha_f_year');
	echo '</div>';

  echo '<div class="campo">';
  echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
  echo '</div>';

	echo form_close();
?>
<div class="campo">
  <a href="<?php echo site_url();?>/admin/cuentas/reporte/pagos/exportar<?php echo $suffix;?>" class="ico-excel">Exportar a CSV</a>
</div>

<div class="clear"></div>

</div><!-- campo -->

<div class="clear height"></div>

</div>
<?php if (count($lists)>0): ?>

<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">

<table width="100%" height="90" class="listado">

<tr class="menu"  style="line-height:20px; height:50px;">
  <td width="14%" height="42" class="nombre">Mandante</td>
  <td width="12%" class="nombre">Rut</td>
  <td width="17%" class="nombre">Deudor</td>
  <td width="16%" class="nombre">Procurador</td>
  <td width="10%" class="nombre">Fecha de Pago</td>
  <td width="10%" class="nombre">Monto Remitido</td>
  <td width="9%" class="nombre">Saldo actual</td>
  <td width="12%" class="nombre">Nº Comprobante</td>
</tr>

<div class="content_tabla">

<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">
  <td width="14%" height="40"><?php echo $val->razon_social;?></td>
  <td width="12%"><?php echo $val->rut;?></td>
  <td width="17%"><?php echo $val->usr_nombres.' '.$val->usr_ap_pat.' '.$val->usr_ap_mat;?></td>
  <td width="16%"><?php echo $val->nombres.' '.$val->apellidos;?></td>
  <td width="10%"><?php echo date("d-m-Y", strtotime($val->fecha_pago));?></td>
  <td width="10%" style="text-align:right; padding-right:70px;"><?php echo '$'.number_format($val->monto_pagado-$val->honorarios,0,',','.');?></td>
  <td width="9%" style="text-align:right; padding-right:70px;">$<?php echo number_format($saldo[$val->id],0,',','.');?></td>
  <td width="12%" style="text-align:right; padding-right:70px;"><?php echo $val->n_comprobante_interno;?></td>
  <td width="0%"></td>
</tr>

<?php ++$i;endforeach;?>
</div>
</table>



<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>



</div>

<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>

