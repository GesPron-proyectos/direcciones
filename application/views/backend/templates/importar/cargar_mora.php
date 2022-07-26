
<script type="text/javascript">
$(document).ready(function(){ 
	$('#archivo_1').change(function(){
		var xx=$(this).val();
		$('#ton-archivo_1').attr({'value':xx});
	});

	$("#btn-importar").click(function(){
		if(confirm("Está seguro que desea importar las direcciones")){
			$("#myform").submit();
		}
		else return false;
	});
});
</script>

<?php $url_form = ''; if (!empty($id)){$url_form = $id;}?>
<?php echo form_open_multipart(site_url().'/admin/importar/cargar_excel_mora/guardar_archivo/'.$url_form);?>

<?php //echo 'aa '.validation_errors().form_error('username').form_error('password').form_error('perfil');?>

    

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

    <input type="submit" value="Subir Archivo" class="boton" style="width:10px; float:left;">

    <div class="clear"></div>
    
	<div class="clear height"></div>
</div><!--blq-->  
<?php echo form_close();?>
<div class="titulo">
<strong style="float:left; margin-right:10px;">Importador masivo de cuentas desde archivo</strong> 
</div>
<?php $url_form = ''; if (!empty($id)){$url_form = $id;}?>

<div class="blq">
	<div class="dos">
	<?php echo form_open(site_url().'/admin/importar/cargar_excel_mora/importar_archivo/'.$url_form, array('id' => 'myform'));?>
		<div class="campos fila" >
		<?php if ($archivo!=''):?>
		<div class="cont-form">
			<label style="width: 180px; float: left">Archivo para importación:</label>
			<div class="clear"></div>
			<a href="<?php echo base_url().$archivo;?>?s=<?php echo rand(100000,999999)?>">Ver archivo cargado</a>
		</div>
	 	<?php endif;?>
		</div>
		<div class="cont-form">
		<input type="button" id="btn-importar" value="Importar" class="boton" style="width:140px; float:left;">
		</div>
		<div class="clear height"></div>
	<?php echo form_close();?>
	<?php if ($id):?>
	<div class="cont-form">
	Importación exitosa.<br>
	Datos actualizados, cuenta(s): <?php echo $cuentas_update; ?>.<br>
	Datos actualizados, días mora: <?php echo $mora_update; ?>.<br>
	Datos insertados, etapa(s): <?php echo $cuentas_insert; ?>.
	</div>
	<?php endif?>
	</div>
	
	<div class="clear height"></div>
</div><!-- blq -->


