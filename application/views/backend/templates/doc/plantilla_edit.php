<?php 
//$documento_plantilla_exorto = ''; 
//
//$id_documento_etapa = ''; if (isset($_REQUEST['id_documento_etapa'])){$id_documento_etapa = $_REQUEST['id_documento_etapa'];} 
?>

<div class="mainmenu">
	<ul>
		<li class="<?php if($this->uri->segment(4) == 'documentos' || $this->uri->segment(4) == ''):?>current<?php endif;?>"><a href="<?php echo site_url()?>/admin/doc/all/documentos">Documentos</a></li>
		<li class="<?php if($this->uri->segment(4) == 'zip'):?>current<?php endif;?>"><a href="<?php echo site_url()?>/admin/doc/all/zip">Archivos Zip</a></li>
		<li class="<?php if($this->uri->segment(4) == 'plantilla_edit'):?>current<?php endif;?>"><a href="<?php echo site_url()?>/admin/doc/all/plantillas">Plantillas</a></li>
	</ul>
</div>

<style>
div.dos label {
    text-align: right;
    width: 168px;
}
.campo{
	margin-bottom:16px;
}
input, select{
	width:300px;
}
</style>
<div id="content" class="content">

<form method="post" action="<?php echo site_url()?>/admin/doc/plantilla_edit/<?php echo $id_plantilla?>" enctype="multipart/form-data">
	<div class="titulo">
		<strong style="float:left; margin-right:10px;">Datos de la etapa</strong>
		<a name="top"> </a>
		<span class="flechita"></span>
	</div>
	
	<div class="blq">
		<div class="dos" style=" width:100%;">
		
			<div class="campo">
				<label for="etapa">Mandante: </label>
				<?php echo form_dropdown('id_mandante',$mandantes,$input_mandante)?>
			</div>
            
           <div class="campo">
				<label for="posicion">Posición: </label>
				<input type="text" value="<?php echo $posicion?>" name="posicion">
			</div>
            <div class="campo">
				<label for="input_nombre_documento">Nombre documento: </label>
				<input type="text" value="<?php echo $input_nombre_documento?>" name="input_nombre_documento">
			</div>
            
            
			
			<div class="campo">
              <?php echo form_label('Con Exorto', 'exorto', array('style' => 'width:160px; float:left'));?>
		<?php $exorto_check = FALSE; if ($documento_plantilla_exorto == '1'){$exorto_check = TRUE;}?>
		<?php echo form_checkbox(array('name'=>'exorto','class'=>'check','style'=>'width:25px'),'S',$exorto_check);?>
        <div class="clear"></div>
		<?php echo form_error('exorto', '<span class="error" style="margin-left:165px;">', '</span>');?>
			</div>
             <div class="campo">
            <?php if($nodo->nombre == 'fullpay') { ?>
           <?php echo form_label('Demanda Propia', 'tipo_demanda', array('style' => 'width:160px; float:left'));?>
		<?php $tipo_demanda_check = FALSE; if ($documento_plantilla_tipo_demanda == '1'){$tipo_demanda_check = TRUE;}?>
		<?php echo form_checkbox(array('name'=>'tipo_demanda','class'=>'check','style'=>'width:25px'),'S',$tipo_demanda_check);?>
        <div class="clear"></div>
		<?php echo form_error('tipo_demanda', '<span class="error" style="margin-left:165px;">', '</span>');?>
            <?php } ?>
            </div>
            
            <div class="campo">
            <?php if($nodo->nombre == 'fullpay') { ?>
           <?php echo form_label('Seleccionar por defecto', 'por_defecto', array('style' => 'width:160px; float:left'));?>
		<?php $por_defecto_check = FALSE; if ($documento_plantilla_por_defecto == '1'){$por_defecto_check = TRUE;}?>
		<?php echo form_checkbox(array('name'=>'por_defecto','class'=>'check','style'=>'width:25px'),'S',$por_defecto_check);?>
        <div class="clear"></div>
		<?php echo form_error('por_defecto', '<span class="error" style="margin-left:165px;">', '</span>');?>
            <?php } ?>
            </div>
            
            
         
            <div class="campo">
				<label for="etapa">Etapa: </label>
				<?php echo form_dropdown('id_documento_etapa',$documentos_etapas,$documento_plantilla_id_etapa)?>
			</div>
            
            
			<div class="campo">
				<label for="etapa">Subir Documento (Reemplaza el actual): </label>
				<input type="file" id="file" value="" name="file">
			</div>
			
			<div class="campo">
				<label for="etapa"></label>
				<input type="submit" value="Guardar" style="cursor:pointer;">
			</div>
			
		</div>
		<div class="clear"></div>
	</div>
	
</form>
</div>


