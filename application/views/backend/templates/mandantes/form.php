<?php 

$id=''; $razon_social=''; $rut=''; $razon_social_completa=''; $representante_nombre=''; $representante_apepat = ''; $representante_apemat ='';$nombre_fantasia = ''; $fecha_escritura_publica = ''; $numero_repetorio=''; $notaria = ''; $representante_direccion= ''; $representante_direccion_n = '';
$representante_comuna = ''; $representante_ciudad = ''; $codigo_mandante ='';
$n_pagare_automatico = '';
$fecha_escritura_apoderado = '';
$notaria_apoderado = '';

if($_POST){

     
	$codigo_mandante = $_POST['codigo_mandante'];
	$razon_social = $_POST['$razon_social'];
	$rut = $_POST['$rut'];
	$razon_social_completa = $_POST['razon_social_completa'];
	$representante_nombre = $_POST['representante_nombre'];
	$representante_apepat = $_POST['representante_apepat'];
	$representante_apemat = $_POST['representante_apemat'];	
	$nombre_fantasia = $_POST['nombre_fantasia'];
	$fecha_escritura_publica = $_POST['fecha_escritura_publica'];
	$numero_repetorio = $_POST['numero_repetorio'];
	$notaria = $_POST['notaria'];	
	$representante_direccion = $_POST['representante_direccion'];
	$representante_direccion_n = $_POST['representante_direccion_n'];
	$representante_comuna = $_POST['representante_comuna'];
	$representante_ciudad = $_POST['representante_ciudad'];
	
	$fecha_escritura_apoderado = $_POST['fecha_escritura_apoderado'];
	$notaria_apoderado = $_POST['notaria_apoderado'];
	$n_pagare_automatico = $_POST['n_pagare_automatico'];
	

}

//print_r($lists);

if (count($lists)>0){

	$id = $lists->id; 
     
	$razon_social = $lists->razon_social;
	
	$rut = $lists->rut;
	
	$codigo_mandante = $lists->codigo_mandante;
	
	$razon_social_completa = $lists->razon_social_completa;
	
	$representante_nombre = $lists->representante_nombre;
	
	$representante_apepat = $lists->representante_apepat;
	
	$representante_apemat = $lists->representante_apemat;
	
	$nombre_fantasia = $lists->nombre_fantasia;
	
	$fecha_escritura_publica = $lists->fecha_escritura_publica;
	
	$numero_repetorio = $lists->numero_repetorio;
	
	$notaria = $lists->notaria;
	
	$representante_direccion = $lists->representante_direccion;
	
	$representante_direccion_n = $lists->representante_direccion_n;
	$representante_comuna = $lists->representante_comuna;
	$representante_ciudad = $lists->representante_ciudad;
	$fecha_escritura_apoderado = $lists->fecha_escritura_apoderado;
	$notaria_apoderado = $lists->notaria_apoderado;
	$n_pagare_automatico = $lists->n_pagare_automatico;

}

?>

<script src="<?php echo base_url()?>js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>js/jquery.rut.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function(){ 

	/*$("#categoria").keyup(function(){

		var ss=$(this).val();

		if (ss.length>70){$("#mrubro").html("error");}

		else{$("#mcategoria").html(70-(ss.length)+" caracteres");}

	});*/



});

</script>

<form action="<?php echo site_url().'/admin/mandantes/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">

<?php //echo 'aa '.validation_errors().form_error('username').form_error('password').form_error('perfil');?>

    

<div class="titulo">

    <strong style="float:left; margin-right:10px;">* Son campos obligatorios</strong> 

    <span class="flechita"></span>

    <div class="clear"></div>

</div>

<div class="blq">
<div class="dos">
	<div class="cont-form">
      <label for="rut" style="width:195px;">Rut *:</label>
      <input id="rut" name="rut" type="text" value="<?php echo $rut; ?>" style="width:150px;">
	  <div class="clear" id="error_rut"></div>
	</div>
	<div class="clear"></div>
	
    
	<div class="cont-form">
      <label for="razon_social" style="width:195px;">Razon Social*:</label>
      <input id="razon_social" name="razon_social" type="text" value="<?php echo $razon_social; ?>" style="width:150px;">
	</div>
	<div class="clear"></div>
	
	
	<div class="cont-form">
      <label for="razon_social_completa" style="width:195px;">Razon Social Completa:</label>
      <input id="razon_social_completa" name="razon_social_completa" type="text" value="<?php echo $razon_social_completa; ?>" style="width:150px;">
      <?php echo form_error('razon_social_completa', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
    
    
    <div class="cont-form">
      <label for="codigo_mandante" style="width:195px;">Código mandante:</label>
      <input id="codigo_mandante" name="codigo_mandante" type="text" value="<?php echo $codigo_mandante; ?>" style="width:150px;">
      <?php echo form_error('codigo_mandante', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
    
	<div class="cont-form">
      <label for="representante_nombre" style="width:195px;">Representante (Nombre):</label>
      <input id="representante_nombre" name="representante_nombre" type="text" value="<?php echo $representante_nombre; ?>" style="width:150px;">
      <?php echo form_error('representante_nombre', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	
	<div class="cont-form">
      <label for="representante_apepat" style="width:195px;">Representante (Apellido Paterno):</label>
      <input id="representante_apepat" name="representante_apepat" type="text" value="<?php echo $representante_apepat; ?>" style="width:150px;">
      <?php echo form_error('representante_apepat', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	<div class="cont-form">
      <label for="representante_apemat" style="width:195px;">Representante (Apellido Materno):</label>
      <input id="representante_apemat" name="representante_apemat" type="text" value="<?php echo $representante_apemat; ?>" style="width:150px;">
      <?php echo form_error('representante_apemat', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	
	<div class="cont-form">
      <label for="representante_direccion" style="width:195px;">Representante (Direccion):</label>
      <input id="representante_direccion" name="representante_direccion" type="text" value="<?php echo $representante_direccion;?>" style="width:150px;">
      N° <input id="representante_direccion_n" name="representante_direccion_n" type="text" value="<?php echo $representante_direccion_n; ?>" style="width:50px;">
      <?php echo form_error('representante_direccion', '<span class="error">', '</span>');?>
      <?php echo form_error('representante_direccion_n', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	<div class="cont-form">
      <label for="representante_comuna" style="width:195px;">Representante (Comuna):</label>
      <input id="representante_comuna" name="representante_comuna" type="text" value="<?php echo $representante_comuna; ?>" style="width:150px;">
      <?php echo form_error('representante_comuna', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	
	<div class="cont-form">
      <label for="representante_ciudad" style="width:195px;">Representante (Ciudad):</label>
      <input id="representante_ciudad" name="representante_ciudad" type="text" value="<?php echo $representante_ciudad; ?>" style="width:150px;">
      <?php echo form_error('representante_ciudad', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
		
	<div class="cont-form">
      <label for="nombre_fantasia" style="width:195px;">Nombre Fantasia:</label>
      <input id="nombre_fantasia" name="nombre_fantasia" type="text" value="<?php echo $nombre_fantasia; ?>" style="width:150px;">
      <?php echo form_error('nombre_fantasia', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	
	<div class="cont-form">
      <label for="fecha_escritura_publica" style="width:195px;">Fecha Escritura Publica <br>(En Palabras):</label>
      <input id="fecha_escritura_publica" name="fecha_escritura_publica" type="text" value="<?php echo $fecha_escritura_publica; ?>" style="width:150px;">
      <?php echo form_error('fecha_escritura_publica', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>

	<div class="cont-form">
      <label for="numero_repetorio" style="width:195px;">Numero Repetorio:</label>
      <input id="numero_repetorio" name="numero_repetorio" type="text" value="<?php echo $numero_repetorio; ?>" style="width:150px;">
      <?php echo form_error('numero_repetorio', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	<div class="cont-form">
      <label for="notaria" style="width:195px;">Notaria:</label>
      <input id="notaria" name="notaria" type="text" value="<?php echo $notaria; ?>" style="width:150px;">
      <?php echo form_error('notaria', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
    
    <div class="cont-form">
      <label for="fecha_escritura_apoderado" style="width:195px;">Fecha Escritura Apoderado:</label>
      <input id="fecha_escritura_apoderado" name="fecha_escritura_apoderado" type="text" value="<?php echo $fecha_escritura_apoderado; ?>" style="width:150px;">
      <?php echo form_error('fecha_escritura_apoderado', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
    
    <div class="cont-form">
      <label for="notaria_apoderado" style="width:195px;">Notaria Apoderado:</label>
      <input id="notaria_apoderado" name="notaria_apoderado" type="text" value="<?php echo $notaria_apoderado; ?>" style="width:150px;">
      <?php echo form_error('notaria_apoderado', '<span class="error">', '</span>');?>
	</div>
    <div class="clear"></div>
	
    <?php if($nodo->nombre=='swcobranza'): ?>
    
    	<div class="cont-form">
        <?php $n_check = FALSE; if ($n_pagare_automatico == '1'){$n_check = TRUE;}?>
		<?php  echo form_checkbox(array('name'=>'n_pagare_automatico','class'=>'check','style'=>'width:25px'),'1',$n_check);?> Automatico (Busca por Nº pagaré y monto)
       <div class="clear"></div>
	   		</div>
         <?php endif;  ?>   
            
        
	<div class="clear"></div>
    </div><!--dos-->

    <div class="clear"></div>

    <input type="submit" id="submit" value="Guardar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq-->  

</form>

<script type="text/javascript">

$(document).ready(function(){ 

		$("#rut").rut().on('rutInvalido', function(e) {
			$(this).val('');
			 //$('#submit').attr('disabled', 'disabled');
			 $('#error_rut').html("Rut incorrrecto.");
			 $('#error_rut').css('color', 'red');
			 $('#submit').hide();
		});


		$("#rut").rut().on('rutValido', function(e, rut, dv) {
			//$('#submit').removeAttr('disabled');
			$('#error_rut').html('');
			$('#submit').show();
		});


		$("#rut").rut({ validateOn: 'focusin' });

		$( "#rut" ).focus();

});

</script>

