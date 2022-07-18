<?php 
	if($_POST){
		$rut = $_POST['rut'];
		$nombre = $_POST['nombre']; 
		$apellidop = $_POST['apellidop']; 
		$apellidom = $_POST['apellidom'];
		$usuario = $_POST['usuario']; 
		$password = $_POST['password'];
		$sistema = $_POST['sistema'];
	}

	if (count($lists)>0){
	  	$id = $lists->id;
	  	$rut = $lists->rut;
	  	$sistema = $lists->sistema;
   		$nombre = $lists->nombres;
  		$apellidop = $lists->ape_pat;
  		$apellidom = $lists->ape_mat;
    	$usuario = $lists->usuario;
	  	$password = $lists->password;
	}
?>

<form action="<?php echo site_url().'/admin/abogado/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">
<div class="titulo">
    <?php if (validation_errors()!='' && (isset($_POST['enviar']) && $_POST['enviar']!='')): ?>
    <span class="alerta"></span><span class="error">Faltan campos por completar</span> 
    <?php endif;?>
    <span class="flechita"></span>
    <div class="clear"></div>
</div>
<div class="blq">
	<div class="dos">
		<div class="cont-form">
			<label style="width:135px!important; float:left">Rut abogado*:</label>
			<input id="rut_abogado" name="rut" type="text" value="<?php echo $rut;?>" style="width:150px;">
			<div class="clear"></div>
			<span class="error" id="error_rut"></span>
		</div>
		<div class="clear"></div>
		<div class="cont-form">
            <label for="nombres" style="width:135px;">Nombres:</label>
            <input id="nombres" name="nombre" type="text" value="<?php echo $nombre;?>" style="width:150px;">
		</div>
		<div class="clear"></div>
        <div class="cont-form">
              <label for="apellidos" style="width:135px;">Apellido Paterno:</label>
              <input id="apellidos" name="apellidop" type="text" value="<?php echo $apellidop;?>" style="width:150px;">
        </div>
		<div class="clear"></div>
        <div class="cont-form">
              <label for="apellidos" style="width:135px;">Apellido Materno:</label>
              <input id="apellidos" name="apellidom" type="text" value="<?php echo $apellidom;?>" style="width:150px;">
        </div>
		<div class="clear"></div>
		<div class="cont-form">
            <label for="sistema" style="width:135px;">Sistema:</label>
			<select id="sistema" name="sistema" style="width:162px;">
				<option value="0">Seleccione</option>
				<option value="CAE" <?php if($sistema == 'CAE') echo 'selected';?>>CAE</option>
				<option value="CAT" <?php if($sistema == 'CAT') echo 'selected';?>>CAT</option>
				<option value="SUPERIR" <?php if($sistema == 'SUPERIR') echo 'selected';?>>SUPERIR</option>
			</select>
            <div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="cont-form">
            <label for="usuario" style="width:135px;">Usuario:</label>
            <input id="usuario" name="usuario" type="text" value="<?php echo $usuario;?>" style="width:195px;">
            <div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="cont-form">
            <label for="password" style="width:135px;">Contraseña:</label>
            <input id="password" name="password" type="text" value="<?php echo $password;?>" style="width:195px;">
            <div class="clear"></div>
		</div>
		<div class="clear"></div>
    </div><!--dos-->

    <div class="clear"></div>
    <input type="submit" value="Guardar" name="enviar" class="boton" style="width:99%; float:left;">
    <div class="clear"></div>
</div>
</form>

<script src="<?php echo base_url()?>js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>js/jquery.rut.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function(){ 

		$("#rut_abogado").rut().on('rutInvalido', function(e) {
			$(this).val('');
			 //$('#submit').attr('disabled', 'disabled');
			 $('#error_rut').html("Rut incorrrecto.");
			 //$('#submit').hide();
		});


		$("#rut_abogado").rut().on('rutValido', function(e, rut, dv) {
			//$('#submit').removeAttr('disabled');
			$('#error_rut').html('');
			//$('#submit').show();
		});


		//$("#rut").rut({ validateOn: 'focusin' });

		//$( "#rut" ).focus();

});

</script>
<?php echo form_open(site_url().'/admin/abogado/guardar_correo/'.$id); ?>
<table class="stable" width="100%">
  <tr><td colspan="4"></td></tr>
  <tr>
      <td></td>
      <td></td>    
  </tr> 
  <tr>
<style type="text/css">
    .custom_input{
		width:573px;/*use according to your need*/
		height:23px;/*use according to your need*/
	}
</style>
  </tr>
  <tr><td colspan="4"></td></tr>
  </table>
  <?php echo form_close();?>

