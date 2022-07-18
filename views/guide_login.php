<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Demo del script:	¿Cómo hacer un login de usuarios en php?</title>
</head>

<body style="margin-top:0px">
<?php echo form_open('admin/login'); ?>
<div class="Info">
	<p class="Titulo"><h1>Demo del script:	<a href="/php-login/">¿Cómo hacer un login de usuarios en php y codeigniter?</a></h1></p>
	<p>&nbsp;</p>	
</div>
<div id="LoginUsuarios">
	<div class="fila">
		<div class="LoginUsuariosCabecera">E-mail:</div>
		<div class="LoginUsuariosDato"><input type="text" name="userlogin" value="<?= set_value('userlogin'); ?>" size="25" /></div>
		<div class="LoginUsuariosError">
		<?
		if(isset($error)){
			echo "<p>".$error."</p>";
		}
		echo form_error('userlogin');
		?>
		</div>
	</div>		
	<div class="fila">
		<div class="LoginUsuariosCabecera">Contraseña:</div>
		<div class="LoginUsuariosDato"><input type="password" name="passwordlogin" value="<?= set_value('passwordlogin'); ?>" size="25" /></div>
		<div class="LoginUsuariosError"><?= form_error('passwordlogin');?></div>
	</div>
	<div class="fila">
		<div class="LoginUsuariosCabecera"></div>
		<div class="LoginUsuariosDato"></div>
	</div>		
	<div class="fila">
		<div class="LoginUsuariosCabecera"><input type="submit" value="Ingresar"></div>
		<div class="LoginUsuariosDato"></div>
	</div>		
</div>
</form>
<p>&nbsp;</p>
</body>
</html>