<?php 

$id ='';
$razon_social ='';
$alias = ''; 
$nombre = '';
$representante_direccion = '';
$representante_direccion_n = '';

if($_POST){

     
	$razon_social = $_POST['razon_social'];
	$alias = $_POST['alias'];	
	$nombre = $_POST['nombre'];	
	$representante_direccion = $_POST['representante_direccion'];
	$representante_direccion_n = $_POST['representante_direccion_n'];


}

//print_r($lists);

if (count($lists)>0){

	$id = $lists->id; 
     
	$razon_social = $lists->razon_social;
	
	$alias = $lists->alias;
	
	$nombre = $lists->nombre;
	
	$representante_direccion = $lists->representante_direccion;

	$representante_direccion_n = $lists->representante_direccion_n;


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

<form action="<?php echo site_url().'/admin/institucion/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">

<?php //echo 'aa '.validation_errors().form_error('username').form_error('password').form_error('perfil');?>


<div class="titulo">

    <strong style="float:left; margin-right:10px;">* Son campos obligatorios</strong> 

    <span class="flechita"></span>

    <div class="clear"></div>

</div>

<div class="blq">
<div class="dos">
    
	<div class="cont-form">
      <label for="razon_social" style="width:195px;">Razon Social *:</label>
      <input id="razon_social" name="razon_social" type="text" value="<?php echo $razon_social; ?>" style="width:150px;" requerid>
	</div>
	<div class="clear"></div>


	<div class="cont-form">
      <label for="nombre" style="width:195px;">Tipo Institución:</label>
      <input id="nombre" name="nombre" type="text" value="<?php echo $nombre; ?>" style="width:150px;">
      <?php echo form_error('nombre', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>


	<div class="cont-form">
      <label for="alias" style="width:195px;">Alias:</label>
      <input id="alias" name="alias" type="text" value="<?php echo $alias; ?>" style="width:150px;">
      <?php echo form_error('alias', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>
	
	
	<div class="cont-form">
      <label for="representante_direccion" style="width:195px;">Dirección:</label>
      <input id="representante_direccion" name="representante_direccion" type="text" value="<?php echo $representante_direccion;?>" style="width:150px;">
      N° <input id="representante_direccion_n" name="representante_direccion_n" type="text" value="<?php echo $representante_direccion_n; ?>" style="width:50px;">
      <?php echo form_error('representante_direccion', '<span class="error">', '</span>');?>
      <?php echo form_error('representante_direccion_n', '<span class="error">', '</span>');?>
	</div>
	<div class="clear"></div>  
            
        
	<div class="clear"></div>
    </div><!--dos-->

    <div class="clear"></div>

    <input type="submit" id="submit" value="Guardar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq-->  

</form>
