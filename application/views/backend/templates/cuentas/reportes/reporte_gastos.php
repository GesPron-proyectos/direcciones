<div class="table-m-sep">

  <div class="table-m-sep-title">

  <h2><strong>Gastos (<?php echo number_format($total,0,',','.');?>)</strong></h2>

  </div>

</div>

<div class="agregar-noticia">

<div class="">

<?php 

$rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
$rut_deudor = ''; if (isset($_REQUEST['rut_deudor'])){$rut_deudor = $_REQUEST['rut_deudor'];}
$rut_parcial = ''; if (isset($_REQUEST['rut_parcial'])){$rut_parcial = $_REQUEST['rut_parcial'];}
$n_boleta = ''; if (isset($_REQUEST['n_boleta'])){$n_boleta = $_REQUEST['n_boleta'];}
$id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
$fecha_month = '0';if (isset($_REQUEST['fecha_month'])){$fecha_month = $_REQUEST['fecha_month'];}
$fecha_year = '0';if (isset($_REQUEST['fecha_year'])){$fecha_year = $_REQUEST['fecha_year'];}
$fecha_f_month = '0';if (isset($_REQUEST['fecha_f_month'])){$fecha_f_month = $_REQUEST['fecha_f_month'];}
$fecha_f_year = '0';if (isset($_REQUEST['fecha_f_year'])){$fecha_f_year = $_REQUEST['fecha_f_year'];}
$id_receptor = '0';if (isset($_REQUEST['id_receptor'])){$id_receptor = $_REQUEST['id_receptor'];}
$id_tribunal = '0';if (isset($_REQUEST['id_tribunal'])){$id_tribunal = $_REQUEST['id_tribunal'];}
$rol= ''; if (isset($_REQUEST['rol'])){$rol = $_REQUEST['rol'];}

  echo form_open(site_url().'/admin/cuentas/reporte/gastos/',array('id' => 'form_reg'));
  echo '<div class="campo">';
	echo form_label('Rut Deudor', 'rut_deudor'/*,$attributes*/);
	echo form_input('rut_deudor', $rut_deudor);
	echo form_error('rut_deudor');
  echo '</div>';
  
  
  echo '<div class="campo">';
	echo form_label('Rut Parcial', 'rut_parcial'/*,$attributes*/);
	echo form_input('rut_parcial', $rut_parcial);
	echo form_error('rut_parcial');
  echo '</div>';
  
  
  echo '<div class="campo">';
	echo form_label('Receptores', 'id_receptor'/*,$attributes*/);
	echo form_dropdown('id_receptor', $receptores, $id_receptor);
	echo form_error('id_receptor');
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
	echo form_label('Tribunal', 'id_tribunal'/*,$attributes*/);
	echo form_dropdown('id_tribunal', $tribunales, $id_tribunal);
	echo form_error('id_receptor');
  echo '</div>';
  
  echo '<div class="campo">';
  echo form_label('Rol', 'rol');
  echo form_input('rol', $rol,'id="rol"');
  echo form_error('rol');
  echo '</div>';

  echo '<div class="campo">';
  echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
  echo '</div>';

	echo form_close();

?>

<?php if($nodo->nombre == 'swcobranza'): ?>
<a href="<?php echo site_url();?>/admin/cuentas/reporte/gastos/exportar<?php echo $suffix;?>" class="ico-excel">Exportar a CSV</a>
<?php endif;?>

<?php if($nodo->nombre == 'fullpay'): ?>
    <a href="<?php echo site_url();?>/admin/cuentas/exportador_gastos<?php echo $suffix;?>" class="ico-excel">Exportar a Excel</a>
    <!-- <a href="<?php // echo site_url();?>/admin/cuentas/exportador_gastos?rut_deudor=<?php // echo $rut_deudor?>&rut_parcial=<?php // echo $rut_parcial;?>&id_receptor=<?php // echo $id_receptor;?>&id_mandante=<?php // echo $id_mandante;?>&id_procurador=<?php // echo $id_procurador;?>&rol=<?php // echo $rol;?>&id_tribunal=<?php // echo $id_tribunal;?>&fecha_month=<?php // echo $fecha_month;?>&fecha_year=<?php // echo $fecha_year;?>&fecha_f_month=<?php // echo $fecha_f_month;?>&fecha_f_month=<?php // echo $fecha_f_month;?>" class="ico-excel">Exportar a Excel</a> -->
<?php endif;?>

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

    <td width="8%" class="nombre">ID</td>

  <td width="8%" class="nombre">Fecha</td>

  <td width="12%" class="nombre">Mandante</td>

  <td width="9%" class="nombre">Nº Boleta</td>

  <td width="14%" class="nombre">Rut Deudor</td>
  
  <td width="19%" class="nombre">Nombre Receptor</td>

  <td width="10%" class="nombre">Monto</td>

  <td width="10%" class="nombre">Retención (10%)</td>

  <td width="17%" class="nombre">Descripción</td>
  
  <td width="17%" class="nombre">Tribunal</td>
  
    <td width="17%" class="nombre">Rol</td>

</tr>

<div class="content_tabla">


<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">

    <td width="12%"><?php echo $val->id;?></td>

  <td width="8%" height="28"><?php echo date("d-m-Y", strtotime($val->fecha));?></td>

    <td> <!--input name="chk-<?php echo $check_id;?>" type="checkbox" value="" class="check"--><a href="<?php echo site_url().'/admin/gestion/index/'.$val->id;?>" title=""><?php if($nodo->nombre == 'fullpay') { ?>
            <?php    if($val->clase_html == 1  ) {  ?>
                <?php echo '<span class="red">'.$val->codigo_mandante.'</span>' ?> <?php  } else {  echo $val->codigo_mandante; } ?>      </td>
    <?php } ?></td>

  <td width="9%"><?php echo $val->n_boleta;?></td>

  <td width="14%"><?php echo $val->rut;?></td>
  
 <td width="19%"><?php echo $val->nombre_recep;?></td>

  <td width="10%" style="text-align:right; padding-right:70px;"><?php echo '$'.number_format($val->monto,0,',','.');?></td>

  <td width="10%" style="text-align:right; padding-right:34px;"><?php echo '$'.number_format($val->retencion,0,',','.');?></td>

  <td width="17%"><?php echo $val->descripcion;?></td>
  
  <td width="17%"><?php echo $val->tribunal;?></td>
  
  <td width="17%"><?php echo $val->rol;?></td>

</tr>

<?php ++$i;endforeach;?>

</table>

</div>

<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>





<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>

