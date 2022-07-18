<script type="text/javascript">
		$(document).ready(function(){
			$('#fechaaaa').datepicker();
		});
	</script>


<div class="table-m-sep">

  <div class="table-m-sep-title">

  <h2><strong>Cuentas (<?php echo number_format($total,0,',','.');?>)</strong></h2>



  </div>

</div>


<div class="agregar-noticia">
 
   <!-- <div class="agregar">
        <a class="nueva" href="<?php echo site_url();?>/admin/cuentas/form/">Crear Nueva Cuenta</a> 
    </div>
    <div class="clear"></div>
    --> 


<div class="">
<?php 


$rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
$nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];}
$ap_pat = ''; if (isset($_REQUEST['ap_pat'])){$ap_pat = $_REQUEST['ap_pat'];}
$id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
$id_distrito = ''; if (isset($_REQUEST['id_distrito'])){$id_distrito = $_REQUEST['id_distrito'];}
$id_tribunal= ''; if (isset($_REQUEST['id_tribunal'])){$id_tribunal = $_REQUEST['id_tribunal'];}
$nombre_demandado = ''; if (isset($_REQUEST['nombre_demandado'])){$nombre_demandado = $_REQUEST['nombre_demandado'];}
$apellido_demandado = ''; if (isset($_REQUEST['apellido_demandado'])){$apellido_demandado = $_REQUEST['apellido_demandado'];}
$receptor = ''; if (isset($_REQUEST['receptor'])){$receptor = $_REQUEST['receptor'];}
$mandante = ''; if (isset($_REQUEST['mandante'])){$mandante = $_REQUEST['mandante'];}
$fecha_asignacion = '';  if (isset($_REQUEST['fecha_asignacion'])){$fecha_asignacion = $_REQUEST['fecha_asignacion'];}
$rut_mandante = ''; if (isset($_REQUEST['rut_mandante'])){$rut_mandante = $_REQUEST['rut_mandante'];}
$id_etapa = ''; if (isset($_REQUEST['id_etapa'])){$id_etapa = $_REQUEST['id_etapa'];}
$id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta']; } 
$rol = ''; if (isset($_REQUEST['rol'])){$rol = $_REQUEST['rol']; }
$rolE = ''; if (isset($_REQUEST['rolE'])){$rolE = $_REQUEST['rolE']; }

$id_distritoE = ''; if (isset($_REQUEST['id_distritoE'])){$id_distritoE = $_REQUEST['id_distritoE'];}  
$id_tribunalE = ''; if (isset($_REQUEST['id_tribunalE'])){$id_tribunalE = $_REQUEST['id_tribunalE'];}

$Operacion = ''; if (isset($_REQUEST['Operacion'])){$Operacion = $_REQUEST['Operacion'];} 



	echo form_open(site_url().'/admin/'.$current.'/',array('id' => 'form_reg'));
	
	echo '<div class="campo">';
	echo form_label('N° Operacion', 'N° Operacion'/*,$attributes*/);
	echo form_input('Operacion', $Operacion);
	echo form_error('operacion');
	echo '</div>';

	
    echo '<div class="campo">';
	echo form_label('Rut Demandando', 'rut_mandante'/*,$attributes*/);
	echo form_input('rut_mandante', $rut_mandante);
	echo form_error('rut_mandante');
	echo '</div>'; 
	
	echo '<div class="campo">';
	echo form_label('Nombres Demandado', 'nombres'/*,$attributes*/);
	echo form_input('nombres', $nombres);
	echo form_error('nombres');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Ap. Paterno Demandado', 'ap_pat'/*,$attributes*/);
	echo form_input('ap_pat', $ap_pat);
	echo form_error('ap_pat');
	echo '</div>'; 

	echo '<div class="campo">';
	echo form_label('Ap. Materno Demandado', 'ap_mat'/*,$attributes*/);
	echo form_input('ap_mat', $ap_mat);
	echo form_error('ap_mat');
	echo '</div>'; 	
	
	// if( $this->session->userdata("usuario_perfil") != 3 )
	 //{
	echo '<div class="campo">';
	echo form_label('Procurador', 'id_procurador'/*,$attributes*/);
	echo form_dropdown('id_procurador', $procuradores, $id_procurador);
	echo form_error('id_procurador');
	echo '</div>';	
		
	 //}else{
		//echo form_hidden('id_procurador', $id_procurador);
	//}
	
	echo '<div class="campo">';
	echo form_label('Mandante', 'id_mandante'/*,$attributes*/);
	echo form_dropdown('id_mandante', $mandantes, $id_mandante);
	echo form_error('id_mandante');
	echo '</div>';
	


	echo '<div class="campo">';
	echo form_label('Estado', 'id_estado_cuenta'/*,$attributes*/);
	echo form_dropdown('id_estado_cuenta', $estados, $id_estado_cuenta);
	echo form_error('id_estado_cuenta');
	echo '</div>';


	echo '<div class="campo">';
	echo form_label('Distrito', 'id_distrito');
	echo form_dropdown('id_distrito', $distrito, $id_distrito);
	echo form_error('id_distrito');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Tribunal', 'id_tribunal');
	echo '<div id="anidadotribunal">';
	echo form_dropdown('id_tribunal', $tribunales, $id_tribunal);
	echo form_error('id_tribunal');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Rol', 'rol'/*,$attributes*/);
	echo form_input('rol', $rol);
	echo form_error('rol');
	echo '</div>'; 
	
	echo '<div class="campo">';
	echo form_label('Distrito Ex.', 'id_distritoE');
	echo form_dropdown('id_distritoE', $distrito, $id_distritoE);
	echo form_error('id_distritoE');
	echo '</div>';
	
	 
	echo '<div class="campo">';
	echo form_label('Tribunal Ex.', 'id_tribunalE');
	echo '<div id="anidadotribunalE">';
	echo form_dropdown('id_tribunalE', $tribunales);
	echo form_error('id_tribunalE');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Rol Exhorto', 'rolE');
	echo form_input('rolE', $rolE);
	echo form_error('rolE');
	echo '</div>';
	
	

	
	if($nodo->nombre == 'swcobranza' && $nodo->activo == 'S'){
	echo '<div class="campo">';
	echo form_label('Receptor', 'receptor'/*,$attributes*/);
	echo form_input('receptor', $receptor);
	echo form_error('receptor');
	echo '</div>';
	}

	
	
	
	if($nodo->nombre == 'swcobranza' && $nodo->activo == 'S' ){
	echo form_label('Fecha asignacion', 'fecha_asignacion'/*,$attributes*/); ?>
	<input type="text" name="fecha_asignacion" id="fechaaaa" data-date-format="dd-mm-yyyy" value="<?php echo $this->input->post('fecha_asignacion')?>">
    <?php echo form_error('fecha_asignacion');
	echo '</div>';
	}
	
	echo '<div class="campo">';
	echo form_label('Etapa de Juicio', 'id_etapa'/*,$attributes*/);
	echo form_dropdown('id_etapa', $etapas, $id_etapa);
	echo form_error('id_etapa');
	echo '</div>';

	
	echo '<div class="campo">';
	echo form_label('&nbsp;', ''/*,$attributes*/);
	echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
	echo '</div>';

 


	echo form_close();
	
?>
</div><!-- campo -->

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
});
</script>



<?php 

//print_r($lists);
if (count($lists)>0): ?>

<div class="clear"></div>
<div class="tabla-listado">


<div class="content_tabla">

  <?php include APPPATH.'views/backend/templates/procurador/list_tabla.php';?>

</div>

<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>



</div>

<?php endif;?>  



