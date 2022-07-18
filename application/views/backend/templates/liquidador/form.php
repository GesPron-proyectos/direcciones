<?php 

$id='';
$rut='';
$representante_nombre='';
$representante_apepat = '';
$representante_apemat ='';

if($_POST){

	$rut = $_POST['$rut'];
	$representante_nombre = $_POST['representante_nombre'];
	$representante_apepat = $_POST['representante_apepat'];
	$representante_apemat = $_POST['representante_apemat'];
	

}

//print_r($lists);

if (count($lists)>0){

	$id = $lists->id; 
     
	$rut = $lists->rut;
	
	$representante_nombre = $lists->representante_nombre;
	
	$representante_apepat = $lists->representante_apepat;
	
	$representante_apemat = $lists->representante_apemat;

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

<form action="<?php echo site_url().'/admin/liquidador/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">

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
      <label for="representante_nombre" style="width:195px;">Nombre *:</label>
      <input id="representante_nombre" name="representante_nombre" type="text" value="<?php echo $representante_nombre; ?>" style="width:150px;" required>
      <?php echo form_error('representante_nombre', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	
	<div class="cont-form">
      <label for="representante_apepat" style="width:195px;">Apellido Paterno *:</label>
      <input id="representante_apepat" name="representante_apepat" type="text" value="<?php echo $representante_apepat; ?>" style="width:150px;" required>
      <?php echo form_error('representante_apepat', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	<div class="cont-form">
      <label for="representante_apemat" style="width:195px;">Apellido Materno:</label>
      <input id="representante_apemat" name="representante_apemat" type="text" value="<?php echo $representante_apemat; ?>" style="width:150px;">
      <?php echo form_error('representante_apemat', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>  
            
        
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

