<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>LLamados por realizar (<?php echo number_format($total,0,',','.');?>)</strong></h2>
      <?php $this->load->view('backend/templates/mod/cat_tools');?>
  </div>
</div>
<?php 
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];} ?>
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

	$fecha_asignacion_month=''; if (isset($_REQUEST['fecha_asignacion_month'])){$fecha_asignacion_month = $_REQUEST['fecha_asignacion_month'];}
	$fecha_asignacion_year=''; if (isset($_REQUEST['fecha_asignacion_year'])){$fecha_asignacion_year = $_REQUEST['fecha_asignacion_year'];}
	$fecha_ultimo_pago_day=''; if (isset($_REQUEST['fecha_ultimo_pago_day'])){$fecha_ultimo_pago_day = $_REQUEST['fecha_ultimo_pago_day'];}
	$fecha_ultimo_pago_month=''; if (isset($_REQUEST['fecha_ultimo_pago_month'])){$fecha_ultimo_pago_month = $_REQUEST['fecha_ultimo_pago_month'];}
	$fecha_ultimo_pago_year='0'; if (isset($_REQUEST['fecha_ultimo_pago_year'])){$fecha_ultimo_pago_year = $_REQUEST['fecha_ultimo_pago_year'];}
	$dia_convenio =''; if (isset($_REQUEST['dia_convenio'])){$dia_convenio = $_REQUEST['dia_convenio'];}
	$fecha_ultima_llamada_day=''; if (isset($_REQUEST['fecha_ultima_llamada_day'])){$fecha_ultima_llamada_day = $_REQUEST['fecha_ultima_llamada_day'];}
	$fecha_ultima_llamada_month=''; if (isset($_REQUEST['fecha_ultima_llamada_month'])){$fecha_ultima_llamada_month = $_REQUEST['fecha_ultima_llamada_month'];}
	$fecha_ultima_llamada_year='0'; if (isset($_REQUEST['fecha_ultima_llamada_year'])){$fecha_ultima_llamada_year = $_REQUEST['fecha_ultima_llamada_year'];}
    $rut =''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
    $tipo_llamada =''; if (isset($_REQUEST['tipo_llamada'])){$tipo_llamada = $_REQUEST['tipo_llamada'];}



	echo form_open(site_url().'/admin/'.$current.'/',array('id' => 'form_reg'));
	echo '<div class="campo">';
	echo form_label('Fecha Pagare', 'fecha_asignacion_month');
	echo month_dropdown('fecha_asignacion_month',$fecha_asignacion_month).year_dropdown('fecha_asignacion_year',$fecha_asignacion_year,2014,date('Y')+1);
	echo '</div>';
	

	echo '<div class="campo">';
	echo form_label('Fecha Último Pago', 'fecha_ultimo_pago_month');
	echo day_dropdown('fecha_ultimo_pago_day',$fecha_ultimo_pago_day).month_dropdown('fecha_ultimo_pago_month',$fecha_ultimo_pago_month).year_dropdown('fecha_ultimo_pago_year',$fecha_ultimo_pago_year,2014,date('Y')+1);
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Día Convenio', 'dia_convenio');
	echo day_dropdown('dia_convenio',$dia_convenio);
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Fecha Última Llamada', 'fecha_ultima_llamada_day');
	echo day_dropdown('fecha_ultima_llamada_day',$fecha_ultima_llamada_day).month_dropdown('fecha_ultima_llamada_month',$fecha_ultima_llamada_month).year_dropdown('fecha_ultima_llamada_year',$fecha_ultima_llamada_year,2014,date('Y')+1);
	echo '</div>';
	
	echo '<div class="campo">';
    echo form_dropdown('id_mandante', $mandantes, $id_mandante);
	echo form_error('id_mandante');
	echo '</div>';

    echo '<div class="campo">';
    echo form_dropdown('tipo_llamada', $tipo_llamadas, $tipo_llamada);
    echo form_error('tipo_llamada');
    echo '</div>';

    echo '<div class="campo">';
    echo form_label('Rut Deudor', 'rut'/*,$attributes*/);
    echo form_input('rut', $rut);
    echo form_error('rut');
    echo '</div>';
	
	
	echo '<div class="campo">';
	echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
	echo '</div>';
	echo form_close();

?>

     <?php if($nodo->nombre == 'fullpay'){ ?>
    <a href="<?php echo site_url();?>/admin/llamadas/exportador_cuentas_llamadas_fullpay?rut=<?php echo $rut?>&id_mandante=<?php echo $id_mandante;?>"  class="ico-excel" style="width:150px;">Exportar a Excel</a>
     <?php } ?>



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
	
    <?php include APPPATH.'views/backend/templates/llamadas/list_tabla.php';?>
	  
	  
	</div>
	
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

</div>

<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>

