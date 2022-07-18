<script src="<?php echo base_url()?>js/alertify.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>css/alertify.min.css">
<script type="text/javascript">

	$(document).ready(function(){ 
		$('#btn-imp').click(function(){
			var id_proc = $('#id_abogado').val();
			if(id_proc == 0){
				alertify.dialog('alert').set({transitionOff:true, message: 'Seleccione un abogado.'}).show();
				return false;
			}  
			else
				$("#form_imp").submit();
		});
		
		$('#archivo_1').change(function(){
			var xx=$(this).val();
			$('#ton-archivo_1').attr({'value':xx});
		});

	});
	
</script>
<?php $url_form = ''; if (!empty($id)){$url_form = $id;}?>
<?php echo form_open_multipart(site_url().'/admin/importar/cargar_excel/guardar_archivo/'.$url_form);?>

<?php if($_REQUEST['abog']) $id_abogado = $_REQUEST['abog']; ?>

    <?php // print_r($errors); ?>  

<div class="titulo">

    <strong style="float:left; margin-right:10px;">Archivo para carga masiva de cuentas</strong> 

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
    <span class="formato" style="margin-left:144px;">Formato: xls - Peso máximo: 5 MB.</span>
 	</div>

     <div class="clear"></div>

    </div><!--dos-->

    <div class="clear height"></div>

    <input type="submit" value="Subir Archivo" class="boton" style="width:140px; float:left;">
	<div class="clear height"></div>
	
	<a href="<?php echo base_url().'/uploads/Estructura_Carga_Masiva.xls';?>?s=<?php echo rand(100000,999999)?>">Descargar estructura</a>

    <div class="clear"></div>
    
	<div class="clear height"></div>
	<input type="hidden" id="pjud" name="pjud" value="<?php echo $id_abogado; ?>" />
</div><!--blq-->  
<?php echo form_close();?>
<div class="titulo">
<strong style="float:left; margin-right:10px;">Importador masivo de cuentas desde archivo</strong> 
</div>
<?php $url_form = ''; if (!empty($id)){$url_form = $id;}?>

<div class="blq">
	<div class="dos">
	<?php echo form_open(site_url().'/admin/importar/cargar_excel/importar_archivo/'.$url_form);?>
		<div class="campos fila" >
		<?php if ($archivo!=''):?>
	 	 	<div class="cont-form">
		  <label style="width:140px;float:left">Procurador*:</label>
	      <div class="clear"></div>
	      <?php echo form_dropdown('id_abogado', $abogados, $id_abogado, 'id="id_abogado"');?>
	      <div class="clear"></div>
	      <?php form_error('id_abogado','<span class="error">', '</span>');?> 
		</div>
		<div class="cont-form">
			<label style="width: 180px; float: left">Archivo para importación:</label>
			<div class="clear"></div>
			<a href="<?php echo base_url().$archivo;?>?s=<?php echo rand(100000,999999)?>">Ver archivo cargado</a>
		</div>
	 	<?php endif;?>
		</div>
		<div class="cont-form">
		<input type="submit" value="Importar" class="boton" style="width:140px; float:left;">
		</div>
		<div class="clear height"></div>
		<input type="hidden" id="bandera" name="bandera" value="<?php echo $bandera; ?>" />
	<?php echo form_close();?>
	<?php if ($operacion):?>
	<div class="cont-form">
	Importación exitosa.<br>
	Datos importados: <?php echo $cuentas_insert;?> CAUSAS.<br>
	<!--Datos actualizados: <?php echo $cuentas_update;?> CAUSAS.-->
	</div>
	<?php endif?>
	</div>
	
	<div class="clear height"></div>
</div><!-- blq -->