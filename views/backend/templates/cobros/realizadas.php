<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>LLamados realizados (<?php echo number_format($total,0,',','.');?>)</strong></h2>
      <?php $this->load->view('backend/templates/mod/cat_tools');?>
  </div>
</div>
<?php 
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];} ?>
<?php $rut =''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];} ?>
<!-- 
<div class="agregar-noticia">
	<div class="agregar">
		<a href="<?php echo site_url()?>/admin/<?php echo $current ?>/form/" class="nueva">Crear Nueva Etapa</a>
	</div>
	<div class="clear height"></div>
</div>
 -->


<div class="agregar-noticia">
<?php 

	$fecha_llamada_month=''; if (isset($_REQUEST['fecha_llamada_month'])){$fecha_llamada_month = $_REQUEST['fecha_llamada_month'];}
	$fecha_llamada_year=''; if (isset($_REQUEST['fecha_llamada_year'])){$fecha_llamada_year = $_REQUEST['fecha_llamada_year'];}

	echo form_open(site_url().'/admin/'.$current.'/realizadas',array('id' => 'form_reg'));
	echo '<div class="campo">';
	echo month_dropdown('fecha_llamada_month',$fecha_llamada_month).year_dropdown('fecha_llamada_year',$fecha_llamada_year,2014,date('Y')+1);
	echo '</div>';
	
	echo '<div class="campo">';
    echo form_dropdown('id_mandante', $mandantes, $id_mandante);
	echo form_error('id_mandante');
	echo '</div>';

	echo '<div class="campo">';
    echo form_label('Rut Deudor', 'rut'/*,$attributes*/);
    echo form_input('rut', $rut);
    echo form_error('rut');
    echo '</div>';
	
	echo '<div class="campo">';
    echo form_dropdown('tipo_llamada',$tipo_llamadas,$tipo_llamada);
	echo form_error('tipo_llamada');
	echo '</div>';


	echo '<div class="campo">';
	echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
	echo '</div>';

?>





</div>
<div class="clear"></div>
<script type="text/javascript">
		$(document).ready(function(){
			$('#date').datepicker();
		});
	</script>

<br>
<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>

<div class="tabla-listado">

	<div class="content_tabla">
	
   <style>
table.listado tr,table.listado input, table.listado select {
    font-size: 11px;
    margin: 5px 0 5px 5px;
	line-height: 12px !important;
}
</style>

<?php if (count($lists)>0): ?>


<?php /*echo '<pre>'; print_r($lists); echo '</pre>';*/?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  <td class="nombre">Mandante</td>
  <td class="nombre">Deudor Rut</td>
  <td class="nombre">Deudor Nombre</td>
  <td class="nombre">Fecha llamada</td>
  <td class="nombre">Observaciones</td>
  <td class="nombre">Fecha repetir llamada</td>
  <td class="nombre">Estados</td>
  <td>&nbsp;</td>

</tr>
<?php $i=1; $check_id=1; $tipos = array('1'=>'Particular','2'=>'Comercial','3'=>'Celular','4'=>'Otro'); $estados = array('0'=>'Sin confirmación','1'=>'Vigente');

foreach ($lists as $key=>$val): ?>

<tr id="tools_" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>">

	<td><?php echo $val->codigo_mandante;?></td>
    
 <td> <a href="<?php echo site_url()?>/admin/gestion/index/<?php echo $val->cuentas_id ?>" style="" class=""><?php echo $val->usuarios_rut;?></a></td>
  <td><a href="<?php echo site_url()?>/admin/gestion/index/<?php echo $val->cuentas_id ?>" style="" class=""><?php echo $val->usuarios_nombres;?> <?php echo $val->usuarios_ap_pat;?> <?php echo $val->usuarios_ap_mat;?></a></td>
  
	<td><?php if ($val->fecha!='0000-00-00' && $val->fecha!='' && $val->fecha!='1969-12-31'){echo date('d-m-Y',strtotime($val->fecha));}?>
	</td> 
    <td><?php echo $val->observacion;?></td>  
    <td><?php if ($val->fecha_repetir_llamada!='0000-00-00' && $val->fecha_repetir_llamada!='' && $val->fecha_repetir_llamada!='1969-12-31'){echo date('d-m-Y',strtotime($val->fecha_repetir_llamada));}?>
	</td>
    <td><?php if($val->tipo_llamada== '1'){  echo 'Compromiso de Pago'; } elseif($val->tipo_llamada== '2'){ echo 'Recado'; } elseif($val->tipo_llamada== '3'){ echo 'No corresponde'; } if($val->tipo_llamada== '4'){ echo 'Buzón'; } if($val->tipo_llamada== '5'){ echo 'No contesta'; } if($val->tipo_llamada== '6'){ echo 'No pago'; } if($val->tipo_llamada== '7'){ echo 'Visita de Oficina'; } else { echo '-'; }   ?>  </td>

  <?php $row_current=array(); $row_current=$val; //include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
</tr>

<?php ++$i;endforeach;?>

</table>
<?php endif;?>
	  
	  
	</div>
	
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

</div>

<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>

