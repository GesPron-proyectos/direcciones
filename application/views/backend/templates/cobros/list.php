<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Cobros (<?php echo number_format($total,0,',','.');?>)</strong></h2>
      <?php $this->load->view('backend/templates/mod/cat_tools');?>
  </div>
</div>
<?php 
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];} ?>

<div class="agregar-noticia">
<?php 

	
$rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
$nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];}
$ap_pat = ''; if (isset($_REQUEST['ap_pat'])){$ap_pat = $_REQUEST['ap_pat'];}
$id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
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
	
	echo '<div class="campo">';
	echo form_label('Rut','rut_parcial'/*,$attributes*/);
	echo form_input('rut_parcial',$rut_parcial);
	echo form_error('rut_parcial');
	echo '</div>';

	echo '<div class="campo">';
	echo form_label('Nombres', 'nombres'/*,$attributes*/);
	echo form_input('nombres', $nombres);
	echo form_error('nombres');
	echo '</div>';

	echo '<div class="campo">';
	echo form_label('Ap. Paterno', 'ap_pat'/*,$attributes*/);
	echo form_input('ap_pat', $ap_pat);
	echo form_error('ap_pat');
	echo '</div>';

	
	echo '<div class="campo">';
	echo form_label('Mandante', 'id_mandante'/*,$attributes*/);
	echo form_dropdown('id_mandante', $mandantes, $id_mandante);
	echo form_error('id_mandante');
	echo '</div>';
	
	
	echo '<div class="campo">';
	echo form_label('Bienes', 'bienes'/*,$attributes*/);
	echo form_input('bienes', $bienes);
	echo form_error('bienes');
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
	echo form_dropdown('id_tribunal', $tribunales);
	echo form_error('id_tribunal');
	echo '</div>';
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Rol', 'rol'/*,$attributes*/);
	echo form_input('rol', $rol);
	echo form_error('rol');
	echo '</div>';
	
	  if ($nodo->nombre=='fullpay'):
	echo '<div class="campo">';
	echo form_label('Comunas', 'nombre'/*,$attributes*/);
	echo form_dropdown('nombre', $comunas, $nombre);
	echo form_error('nombre');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Tribunal Comuna', 'id_tribunal_comuna');
	echo form_dropdown('id_tribunal_comuna', $tribunal_comuna, $id_tribunal_comuna);
	echo form_error('id_tribunal_comuna');
	echo '</div>';
	 
	endif;
	
	echo '<div class="campo">';
	echo form_label('N° Operación', 'operacion');
	echo form_input('operacion', $operacion);
	echo form_error('operacion');
	echo '</div>';
	 
	echo '<div class="campo">';
	echo form_label('Rol Exhorto', 'rolE');
	echo form_input('rolE', $rolE);
	echo form_error('rolE');
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
	echo form_label(' .', ' ');
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
	
    <?php include APPPATH.'views/backend/templates/cobros/list_tabla.php';?>
	  
	  
	</div>
	
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

</div>

<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>

