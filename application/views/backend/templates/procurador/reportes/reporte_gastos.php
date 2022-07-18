<div class="table-m-sep">

  <div class="table-m-sep-title">

  <h2><strong>Gastos (<?php echo number_format($total,0,',','.');?>)</strong></h2>

  </div>

</div>

<div class="agregar-noticia">

<div class="">

<?php 

$rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
$n_boleta = ''; if (isset($_REQUEST['n_boleta'])){$n_boleta = $_REQUEST['n_boleta'];}
$id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
$fecha_month = '0';if (isset($_REQUEST['fecha_month'])){$fecha_month = $_REQUEST['fecha_month'];}
$fecha_year = '0';if (isset($_REQUEST['fecha_year'])){$fecha_year = $_REQUEST['fecha_year'];}
$fecha_f_month = '0';if (isset($_REQUEST['fecha_f_month'])){$fecha_f_month = $_REQUEST['fecha_f_month'];}
$fecha_f_year = '0';if (isset($_REQUEST['fecha_f_year'])){$fecha_f_year = $_REQUEST['fecha_f_year'];}

	echo form_open(site_url().'/admin/cuentas/reporte/gastos/',array('id' => 'form_reg'));

  echo '<div class="campo">';
	echo form_label('Rut Receptor', 'rut'/*,$attributes*/);
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
	echo form_label('Nº Boleta', 'n_boleta'/*,$attributes*/);
	echo form_input('n_boleta', $n_boleta);
	echo form_error('n_boleta');
  echo '</div>';

  echo '<div class="campo">';
	echo form_label('Desde', 'fecha_year'/*,$attributes*/);
  echo month_dropdown('fecha_month',$fecha_month).year_dropdown('fecha_year',$fecha_year,2010);
  echo form_error('fecha_year');
  echo '</div>';
  
  echo '<div class="campo">';
  echo form_label('Hasta', 'fecha_f_year'/*,$attributes*/);
  echo month_dropdown('fecha_f_month',$fecha_f_month).year_dropdown('fecha_f_year',$fecha_f_year,2010);
  echo form_error('fecha_f_year');
  echo '</div>';

  echo '<div class="campo">';
  echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
  echo '</div>';

	echo form_close();

?>

<a href="<?php echo site_url();?>/admin/cuentas/reporte/gastos/exportar<?php echo $suffix;?>" class="ico-excel">Exportar a CSV</a>

<div class="clear"></div>
</div><!-- campo -->

<div class="clear height"></div>

</div>

<?php if (count($lists)>0): ?>

<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">

<table class="listado" width="100%">

<tr class="menu" style="line-height:20px; height:50px;">

  <td width="8%" class="nombre">Fecha</td>

  <td width="12%" class="nombre">Mandante</td>

  <td width="9%" class="nombre">Nº Boleta</td>

  <td width="14%" class="nombre">Rut Receptor</td>

  <td width="19%" class="nombre">Nombre Receptor</td>

  <td width="10%" class="nombre">Monto</td>

  <td width="10%" class="nombre">Retención (10%)</td>

  <td width="17%" class="nombre">Descripción</td>

</tr>

<div class="content_tabla">


<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">

  <td width="8%" height="28"><?php echo date("d-m-Y", strtotime($val->fecha));?></td>

  <td width="12%"><?php echo $val->razon_social;?></td>

  <td width="9%"><?php echo $val->n_boleta;?></td>

  <td width="14%"><?php echo $val->rut_receptor;?></td>

  <td width="19%"><?php echo $val->nombre_receptor;?></td>

  <td width="10%" style="text-align:right; padding-right:70px;"><?php echo '$'.number_format($val->monto,0,',','.');?></td>

  <td width="10%" style="text-align:right; padding-right:34px;"><?php echo '$'.number_format($val->retencion,0,',','.');?></td>

  <td width="17%"><?php echo $val->descripcion;?></td>

</tr>

<?php ++$i;endforeach;?>

</table>

</div>

<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>





<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>

