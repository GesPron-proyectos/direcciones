<?php 

$id='';
$nombre='';
$rut ='';
$direccion= ''; 
$comuna = '';
$ciudad = '';
$telefono = '';
$celular = '';
$nombre_secretaria = '';
$id_tribunal = '';
$email = '';
$id_estado = '';
$celular_secretaria='';
$Telefono_secretaria='';
$ApeMat='';
$ApePat='';

if($_POST){
	
	$nombre=$_POST['nombre'];
	$rut=$_POST['rut'];
	$direccion=$_POST['direccion'];
	$comuna=$_POST['comuna'];
	$telefono=$_POST['telefono'];
	$ciudad=$_POST['ciudad'];
	$celular=$_POST['celular'];
	$nombre_secretaria=$_POST['nombre_secretaria'];
	$id_tribunal=$_POST['id_tribunal'];
	$email=$_POST['email'];
	$id_estado=$_POST['id_estado'];
	$celular_secretaria=$_POST['celular_secretaria'];
	$Telefono_secretaria=$_POST['Telefono_secretaria'];
	$ApeMat=$_POST['ApeMat'];
	$ApePat=$_POST['ApePat'];
	
}

if (count($lists)>0){
	
	//print_r($lists);
	
	$id=$lists->id; 
	$nombre = $lists->nombre;
	$rut = $lists->rut;
	$direccion = $lists->direccion;
	$comuna = $lists->comuna;
	$ciudad = $lists->ciudad;
	$telefono = $lists->telefono;
	$celular = $lists->celular;
	$nombre_secretaria = $lists->nombre_secretaria;
	$id_tribunal = $lists->id_tribunal;
	$email = $lists->email;
	$id_estado = $lists->id_estado;
	$celular_secretaria=$lists->celu_secre;
	$Telefono_secretaria=$lists->fono_secre;
	$ApeMat=$lists->apmat;
	$ApePat=$lists->appat;
}


?>

<form action="<?php echo site_url().'/admin/receptor/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post" autocomplete="off">
   
<div class="titulo">
    <strong style="float:left; margin-right:10px;">Complete los datos de la cuenta</strong>
     <?php if (validation_errors()!=''): ?>
    <span class="alerta"></span><span class="error">Faltan campos por completar</span> 
    <?php endif;?>
    <span class="flechita"></span>
    <div class="clear"></div>
</div>

<div class="blq">
    <div class="dos">
        <div class="cont-form">
            <label for="rut" style="width:135px;">Rut*:</label>
            <input id="rut" name="rut" type="text" value="<?php echo $rut;?>" style="width:150px;">
            <?php form_error('rut', '<span class="error">', '</span>');?>
        </div>
        <div class="clear"></div>
		
        <div class="cont-form">
            <label for="nombre" style="width:135px;">Nombre*:</label>
            <input id="nombre" name="nombre" type="text" value="<?php echo $nombre;?>" style="width:150px;">
            <?php form_error('nombre', '<span class="error">', '</span>');?>
        </div>
        <div class="clear"></div>

	    <div class="cont-form">
            <label for="ApePat" style="width:135px;">Apellido Paterno*:</label>
            <input id="ApePat" name="ApePat" type="text" value="<?php echo $ApePat;?>" style="width:150px;">
            <?php form_error('ApePat', '<span class="error">', '</span>');?>
	    </div>
		<div class="clear"></div>

		<div class="cont-form">
			<label for="ApeMat" style="width:135px;">Apellido Materno*:</label>
			<input id="ApeMat" name="ApeMat" type="text" value="<?php echo $ApeMat;?>" style="width:150px;">
            <?php form_error('ApeMat', '<span class="error">', '</span>');?>
        </div>
        <div class="clear"></div>
   
   <?php if($nodo->nombre == 'fullpay'){ ?>
        <div class="cont-form">
            <label for="direccion" style="width:135px;">Dirección:</label>
            <input id="direccion" name="direccion" type="text" value="<?php echo $direccion;?>" style="width:150px;">
            <?php form_error('direccion', '<span class="error">', '</span>');?>
        </div>
        <div class="clear"></div>
      
        <div class="cont-form">
            <label for="telefono" style="width:135px;">Télefono:</label>
            <input id="telefono" name="telefono" type="text" value="<?php echo $telefono;?>" style="width:150px;">
            <?php form_error('telefono', '<span class="error">', '</span>');?>
        </div>
        <div class="clear"></div>
      
      <?php if ($nodo->nombre=='fullpay'):?>
       
      <div class="cont-form">
            <label for="celular" style="width:135px;">Celular:</label>
            <input id="celular" name="celular" type="text" value="<?php echo $celular;?>" style="width:150px;">
            <?php form_error('celular', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>
      
      <div class="cont-form">
            <label for="email" style="width:135px;">Email:</label>
            <input id="email" name="email" type="text" value="<?php echo $email;?>" style="width:150px;">
            <?php form_error('email', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>
      
      
       <div class="cont-form">
            <label for="comuna" style="width:135px;">Comuna:</label>
            <input id="comuna" name="comuna" type="text" value="<?php echo $comuna;?>" style="width:150px;">
            <?php form_error('comuna', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>

      <div class="cont-form">
            <label for="ciudad" style="width:135px;">	Ciudad*:</label>
            <input id="ciudad" name="ciudad" type="text" value="<?php echo $ciudad;?>" style="width:150px;">
            <?php form_error('ciudad', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>
      
       <div class="cont-form">
            <label for="nombre_secretaria" style="width:135px;">Nombre Secretaria*:</label>
            <input id="nombre_secretaria" name="nombre_secretaria" type="text" value="<?php echo $nombre_secretaria;?>" style="width:150px;">
            <?php form_error('nombre_secretaria', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>
      
       <div class="cont-form">
            <label for="Telefono_secretaria" style="width:135px;">Telefono Secretaria*:</label>
            <input id="Telefono_secretaria" name="Telefono_secretaria" type="text" value="<?php echo $Telefono_secretaria;?>" style="width:150px;">
            <?php form_error('Telefono_secretaria', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>

       <div class="cont-form">
            <label for="celular_secretaria" style="width:135px;">Celular Secretaria*:</label>
            <input id="celular_secretaria" name="celular_secretaria" type="text" value="<?php echo $celular_secretaria;?>" style="width:150px;">
            <?php form_error('celular_secretaria', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>
      
      
      <div class="cont-form">
        <label style="width: 140px; float: left">Jurisdicion*:</label>
        <div class="clear"></div>
        <?php echo form_dropdown('id_tribunal', $tribunales, $id_tribunal);?>
       <div class="clear"></div>
        <?php echo form_error('id_tribunal','<span class="error">', '</span>');?> 
    </div>
      
        <div class="clear"></div>
        
      <div class="cont-form">
        <label style="width: 140px; float: left">Vigente/No Vigente*:</label>
        <div class="clear"></div>
        <?php echo form_dropdown('id_estado', $estado_receptor, $id_estado);?>
       <div class="clear"></div>
        <?php echo form_error('id_estado','<span class="error">', '</span>');?> 
    </div>
      
      
      
       <?php endif;?>
      
      <div class="clear"></div>
      
      
   <?php } ?>

      </div><!--dos-->

    <div class="clear"></div>

    <input type="submit" value="Guardar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq--> 
</form>

<?php echo form_open_multipart(site_url().'/admin/importar/cargar_archivoreceptor/guardar_archivo/'.$url_form);?>
<input id="rut" name="rut" type="hidden" value="<?php echo $rut;?>" style="width:150px;">
<input id="id" name="id" type="hidden" value="<?php echo $id;?>" style="width:150px;">
<?php //echo 'aa '.validation_errors();?>

    <?php // print_r($errors); ?>  

<div class="titulo">
    <strong style="float:left; margin-right:10px;">Archivo Receptores</strong> 
    <?php if (count($error)>0):?>
    <span class="alerta"></span><span class="error"><?php echo $error['error'];?></span> 
    <?php endif;?>
    <span class="flechita"></span>
    <div class="clear"></div>
</div>

<div class="blq">

    <div class="dos">
	<div class="campos fila" >
	<?php $i=1;?>
    <label style="width:140px;" for="imagen">Subir Archivo:</label>
    <div class="fileinputs">
        <input id="archivo_<?php echo $i;?>" name="archivo_<?php echo $i;?>" type="file" class="file" width="133px">
        <div class="fakefile">
            <input id="ton-archivo_<?php echo $i;?>" style="margin-left:-134px; width:123px; cursor:pointer;">
        </div>
    </div>  
    <!--<span class="formato" style="margin-left:144px;">Formato: xls - Peso máximo: 5 MB.</span>-->
	<span class="formato" style="margin-left:144px;"></span>
 	</div>

     <div class="clear"></div>

    </div><!--dos-->

    <div class="clear height"></div>

    <input type="submit" value="Subir Archivo" class="boton" style="width:140px; float:left;">
	<div class="clear height"></div>
	
	<div class="tabla-listado">

    <table class="listado" width="100%">
	    <tr class="menu">

      <td width="25%" class="nombre">Archivo</td>
	  <td width="20%" class="nombre">Fecha</td>
       
    </tr>
<?php
 $i=1; $check_id=1;foreach ($listadofiles as $key=>$val): ?>
 
 <tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>"  >

  <td width="11%"><?php echo $val->nombre;?></td>
  <td width="11%"><?php echo $val->fecha;?></td> 
  
</tr>
<?php ++$i;endforeach;?>
</table>
	
	</div>
    <div class="clear"></div>
    
	<div class="clear height"></div>
</div><!--blq-->  
<?php echo form_close();?>

<div class="agregar-noticia">
	<div style="float:right;" class="agregar">
		<a href="<?php  echo site_url()?>/admin/receptor/index/<?php // echo $id_cuenta ?>" style="background:none;" class="nueva">volver a Receptores</a>
	</div>
	<div class="clear height"></div>
</div> 





