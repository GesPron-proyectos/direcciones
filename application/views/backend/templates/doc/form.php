<?php 

$id=''; $perfil='0'; $username=''; $password=''; $nombres=''; $apellidos=''; $correo=''; $cargo=''; $rut_procurador='';

if($_POST){

	$perfil=$_POST['perfil']; $username=$_POST['username']; $password=$_POST['password']; 
	
	$rut_procurador = $_POST['rut_procurador'];

	$nombres=$_POST['nombres']; $apellidos=$_POST['apellidos']; $correo=$_POST['correo']; $cargo=$_POST['cargo'];

}

if (count($lists)>0){

	$id=$lists->id;

	$perfil=$lists->perfil;

	$username=$lists->username;

	$password=$lists->password;

	$nombres=$lists->nombres;

	$apellidos=$lists->apellidos;

	$correo=$lists->correo;

	$cargo=$lists->cargo;
	
	$rut_procurador=$lists->rut_procurador;

}

?>

<script type="text/javascript">

$(document).ready(function(){ 

	/*$("#categoria").keyup(function(){

		var ss=$(this).val();

		if (ss.length>70){$("#mrubro").html("error");}

		else{$("#mcategoria").html(70-(ss.length)+" caracteres");}

	});*/

});

</script>

<form action="<?php echo site_url().'/admin/administradores/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">

<?php //echo 'aa '.validation_errors().form_error('username').form_error('password').form_error('perfil');?>

<div class="titulo">

 <strong style="float:left; margin-right:10px;">Seleccione el Perfil del Usuario</strong> 

  <?php if (validation_errors()!='' && (isset($_POST['enviar']) && $_POST['enviar']!='')): ?>

  <span class="alerta"></span><span class="error">Faltan campos por completar</span> 

  <?php endif;?>

  <span class="flechita"></span>

  <div class="clear"></div>

</div>

<div class="blq">

	<div class="cont-form">
        <label style="width:135px!important; float:left">Perfil*:</label>
        <?php echo form_dropdown('perfil', $perfiles, $perfil);?>
        <div class="clear"></div>
        <?php echo form_error('perfil', '<span class="error">', '</span>');?>
	</div>
    <div class="clear"></div>
    
    
    <div class="cont-form">
        <label style="width:135px!important; float:left">Rut Procurador*:</label>
        <input id="rut_procurador" name="rut_procurador" type="text" value="<?php echo $rut_procurador;?>" style="width:150px;">
        <div class="clear"></div>
        <span class="error" id="error_rut"></span>
	</div>
    <div class="clear"></div>

</div>        

<div class="titulo">

    <strong style="float:left; margin-right:10px;">Complete el nombre de usuario y contraseña</strong>

    <?php if (validation_errors()!='' && (isset($_POST['enviar']) && $_POST['enviar']!='')): ?>

    <span class="alerta"></span><span class="error">Faltan campos por completar</span> 

    <?php endif;?>

    <span class="flechita"></span>

    <div class="clear"></div>

</div>

<div class="blq">

    <div class="dos">
	  <div class="cont-form">
        <label for="categoria" style="width:135px!important;">Nombre de Usuario*:</label>
        <input id="username" name="username" type="text" value="<?php echo $username;?>" style="width:150px;">
        <div class="clear"></div>
        <?php echo form_error('username', '<span class="error">', '</span>');?>
	</div>
      <div class="clear"></div>
      <div class="cont-form">
        <label for="password" style="width:135px;">Contraseña*:</label>
        <input id="password" name="password" type="text" value="<?php echo $password;?>" style="width:150px;">
       <div class="clear"></div>
        <?php echo form_error('password', '<span class="error">', '</span>');?>
	  </div>
      <div class="clear"></div>

    </div><!--dos-->

    <div class="clear"></div>

    <input type="submit" value="Guardar" id="submit" name="enviar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq-->  

<div class="titulo">

    <strong style="float:left; margin-right:10px;">Datos Personales</strong>

    <span class="flechita"></span>

    <div class="clear"></div>

</div>

<div class="blq">

    <div class="dos">
      <div class="cont-form">
            <label for="nombres" style="width:135px;">Nombres:</label>
            <input id="nombres" name="nombres" type="text" value="<?php echo $nombres;?>" style="width:150px;">
      </div>
      <div class="clear"></div>
        <div class="cont-form">
              <label for="apellidos" style="width:135px;">Apellidos:</label>
              <input id="apellidos" name="apellidos" type="text" value="<?php echo $apellidos;?>" style="width:150px;">
        </div>
      <div class="clear"></div>
      	<div class="cont-form">
      <label for="correo" style="width:135px;">Cargo:</label>
      <input id="cargo" name="cargo" type="text" value="<?php echo $cargo;?>" style="width:150px;">
	</div>
     <div class="clear"></div>
      <div class="cont-form">
            <label for="correo" style="width:135px;">Correo:</label>
            <input id="correo" name="correo" type="text" value="<?php echo $correo;?>" style="width:195px;">
            <div class="clear"></div>
            <?php echo form_error('correo', '<span class="error">', '</span>');?>
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

		$("#rut_procurador").rut().on('rutInvalido', function(e) {
			$(this).val('');
			 //$('#submit').attr('disabled', 'disabled');
			 $('#error_rut').html("Rut incorrrecto.");
			 //$('#submit').hide();
		});


		$("#rut_procurador").rut().on('rutValido', function(e, rut, dv) {
			//$('#submit').removeAttr('disabled');
			$('#error_rut').html('');
			//$('#submit').show();
		});


		//$("#rut").rut({ validateOn: 'focusin' });

		//$( "#rut" ).focus();

});

</script>

