

<div class="table-m-sep">

  <div class="table-m-sep-title">

  <h2><strong>Causas (<?php echo number_format($total,0,',','.');?>)</strong></h2>

  </div>

</div>

<div class="agregar-noticia">
    <div width="20%" class="agregar">
    <a class="nueva" href="<?php echo site_url();?>/admin/carteras/cruce_pjud/">CRUCE PEJUD</a>
    </div>
    <div class="clear"></div>
<div class="">

<?php // print_r($roles); ?>

<?php 

$rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
$nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];}
$ap_pat = ''; if (isset($_REQUEST['ap_pat'])){$ap_pat = $_REQUEST['ap_pat'];}
$id_abogado = ''; if (isset($_REQUEST['id_abogado'])){$id_abogado = $_REQUEST['id_abogado'];}
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
$bienes = ''; if (isset($_REQUEST['bienes'])){$bienes = $_REQUEST['bienes'];}
$rut_parcial = ''; if (isset($_REQUEST['rut_parcial'])){$rut_parcial = $_REQUEST['rut_parcial'];}
$id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta']; } 
$id_distrito = ''; if (isset($_REQUEST['id_distrito'])){$id_distrito = $_REQUEST['id_distrito'];}
$id_tribunal= ''; if (isset($_REQUEST['id_tribunal'])){$id_tribunal = $_REQUEST['id_tribunal'];}
$rol= ''; if (isset($_REQUEST['rol'])){$rol = $_REQUEST['rol'];}
$id_etapa = ''; if (isset($_REQUEST['id_etapa'])){$id_etapa = $_REQUEST['id_etapa'];}
//$id_comuna= ''; if (isset($_REQUEST['id_comuna'])){$id_comuna = $_REQUEST['id_comuna'];}
$nombre = ''; if (isset($_REQUEST['nombre'])){$nombre = $_REQUEST['nombre'];}

$fecha_asignacion_pagare = ''; if (isset($_REQUEST['fecha_asignacion_pagare'])){$fecha_asignacion_pagare = $_REQUEST['fecha_asignacion_pagare'];} 
$diferencia = ''; if (isset($_REQUEST['diferencia'])){$diferencia = $_REQUEST['diferencia'];} 
$tribunal = ''; if (isset($_REQUEST['tribunal'])){$tribunal = $_REQUEST['tribunal'];} 
$tribunalE = ''; if (isset($_REQUEST['tribunalE'])){$tribunalE = $_REQUEST['tribunalE'];} 
$operacion = ''; if (isset($_REQUEST['operacion'])){$operacion = $_REQUEST['operacion'];}  
$rolE = ''; if (isset($_REQUEST['rolE'])){$rolE = $_REQUEST['rolE'];}  
$id_distritoE = ''; if (isset($_REQUEST['id_distritoE'])){$id_distritoE = $_REQUEST['id_distritoE'];}  


$id_distritoE = ''; if (isset($_REQUEST['id_distritoE'])){$id_distritoE = $_REQUEST['id_distritoE'];}  

$id_tribunalE = ''; if (isset($_REQUEST['id_tribunalE'])){$id_tribunalE = $_REQUEST['id_tribunalE'];}



	echo form_open(site_url().'/admin/'.$current.'/',array('id' => 'form_reg'));

?>

</div><!-- campo -->

<?php
if($current == 'hist_cuentas'){
?>
<a href="<?php echo site_url();?>/admin/hist_cuentas/reporte/estados/exportar<?php echo $suffix;?>" class="ico-excel">Exportar a CSV</a>
<?php } ?>
<div class="clear height"></div>

</div>

<script type="text/javascript">
$(window).load(function() {
	
	/*$('select[name=id_distrito]').change(function(){
			$.ajax({
				type: 'post',
				url: '<?php echo site_url()?>/admin/tribunales/select/',
				data: 'id_tribunal='+$('select[name=id_distrito]').val(),
				success: function (data) {
					$('select[name=id_tribunal]').html( data )
				},
				error: function(objeto, quepaso, otroobj) {
				},
			});
	});*/  


	/*
	$(document).on("change", "select[name='id_distrito']", function(event){
		var id_distrito = $(this).val();
		$.ajax({	
		  type: 'GET',
		  url: '<?php echo site_url();?>/admin/tribunales/anidado/'+id_distrito,
		  success: function(data) {
			  $("#anidadotribunal").html(data);
		  },
		 
		});
	}); 

	$(document).on("change", "select[name='id_distritoE']", function(event){
		var id_distritoE = $(this).val();
		$.ajax({	
		  type: 'GET',
		  url: '<?php echo site_url();?>/admin/tribunales/anidadoE/'+id_distritoE,
		  success: function(data) {
			  $("#anidadotribunalE").html(data);
		  },
		 
		});
	}); 
*/	
});

</script>

<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">


<div class="content_tabla">
 <?php include APPPATH.'views/backend/templates/cuentas/list_tabla.php';?>
</div>

<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

</div>

<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>
<?php if($nodo->nombre == 'fullpay'):?>
<script type="text/javascript">
$(document).ready(function() {
 $("#rut").Rut({
 	on_error: function(){ alert('El R.U.T. es incorrecto. Formato: 11.111.111-1'); $("#rut").val('');  } 
 }); 
}); 
</script>
<?php endif;?>