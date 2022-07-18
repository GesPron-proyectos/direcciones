<div class="mainmenu">
	<ul>
		<li class="<?php if($this->uri->segment(4) == 'documentos' || $this->uri->segment(4) == ''):?>current<?php endif;?>"><a href="<?php echo site_url()?>/admin/doc/all/documentos">Documentos</a></li>
		<li class="<?php if($this->uri->segment(4) == 'zip'):?>current<?php endif;?>"><a href="<?php echo site_url()?>/admin/doc/all/zip">Archivos Zip</a></li>
		<li class="<?php if($this->uri->segment(4) == 'plantillas'):?>current<?php endif;?>"><a href="<?php echo site_url()?>/admin/doc/all/plantillas">Plantillas</a></li>
	</ul>
</div>

<?php if ($tipo == 'plantillas'):?>
<script src="<?php echo base_url()?>js/jquery.form.js"></script>

<style>
.progress-striped .bar {
    background-color: #149BDF;
    background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
    background-size: 40px 40px;
}
.progress .bar {
    -moz-box-sizing: border-box;
    background-color: #0E90D2;
    background-repeat: repeat-x;
    box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.15) inset;
    color: #FFFFFF;
    float: left;
    font-size: 12px;
    height: 100%;
    text-align: center;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    transition: width 0.6s ease 0s;
    width: 0;
}
.pekecontainer{
	clear:both;
	padding-top: 10px;
}

.btn-upload{
	background-color: #E8E8E8;
    border: 1px solid #CDCCCC;
    float: left;
    height: 36px;
    margin-right: 10px;
    margin-top: 5px;
    padding: 0px 37px 4px 42px !important;
	background: url("http://192.168.0.31/full_pay/img/ico-more.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
}
.edit{
	display:none;
}
.edit_tr{
	cursor:pointer;
}
.edit input,select{
	width:90%;
}
</style>
<div class="tabla-listado">
<div class="content_tabla">

<div class="table-m-sep" style="margin-top:15px !important;"><div class="table-m-sep-title"><h2><strong>Agregar Documento</strong></h2></div></div>

<div class="agregar-noticia" style="margin-top:0px !important;">

<span class="error" id="error_upload"></span>

	<form method="post" action="<?php echo site_url()?>/admin/doc/plantilla_upload" id="form_upload" autocomplete="off" enctype="multipart/form-data">
	
		<div class="campo">
			<label for="rut">Mandante</label>
			<?php echo form_dropdown('id_mandante',$mandantes,$this->input->post('id_mandante'))?>
		</div>
		
		<div class="campo">
			<label for="file">Archivo</label>
			<input type="file" id="file" style="width:300px;" value="" name="file">
		</div>
		
		<div class="campo">
			<label for="submit">&nbsp;</label>
			<input type="submit" value="Subir Plantilla" style="cursor: pointer;">
		</div>
		
		<div class="pekecontainer">
			<div class="file">
				<div class="filename"></div>
				<div class="progress progress-striped">
				<div style="display:none;" class="bar pekeup-progress-bar">0%</div>
				</div>
			</div>
		</div>
		
	</form>
	<div class="clear"></div>
</div>

<script>
$(document).ready(function(){

    $('#form_upload').submit(function() {
    	var file = $(this).find('#file').val();
    	if(file == ''){
			alert('Debe seleccionar un archivo.');
    	}else{
	        var box_status = $(this).find('.pekeup-progress-bar');
	        var options = {
	          beforeSend: function(){
	        	  box_status.show();
	              var percentVal = '0%';
	              box_status.html( percentVal );
	              box_status.css( 'width',percentVal );
	          },
	          uploadProgress: function(event, position, total, percentComplete) {
	              var percentVal = percentComplete + '%';
	              box_status.html( percentVal );
	              box_status.css('width',percentVal );
	          },
	          success: function(responseText, statusText, xhr, form){
	        	  box_status.css('width','100%' );
	          },
	          complete: function(xhr) {
		          continuar = false;
		          if( xhr.responseText != ''){
		        	  box_status.html( xhr.responseText );
		        	  if( xhr.responseText == 'error_extension'){
		        		  $('#error_upload').html('<b>Error, documento no se subio, El Documento Debe tener como extensión .docx</b>');
		        	  }else{
		        		  $('#error_upload').html('');
		        		  continuar = true;
		        	  }
		          }
	              box_status.delay(1000).fadeOut("slow", function() {
		              
		              if(continuar){
		            	  location.reload(); 
		              }
	            		
	              });
	              
	          }
	        }
	        $(this).ajaxSubmit(options);
    	}
        return false;
    });
    
});
</script>

<table width="100%" border="0" class="listado">
  <tr class="menu">
  	<td><b>Mandante</b></td>
    <td><b>Documento</b></td>
     
    
    <td><b>Descargar Documento</b></td>
    <td><b>Eliminar Documento</b></td>
    <td><b>Editar Documento</b></td>
  </tr>
  <?php $class = 'a';?>
  <?php foreach($documento_plantilla as $key=>$val): ?>
  	<tr class="<?php echo $class?>" style="font-size:12px;height:35px;" id="doc_<?php echo $val->documento_plantilla_id?>">
  	
	  	<td>
	  		<span class="text"><?php echo $val->mandantes_razon_social?></span>
	  	</td>
	  	
	    <td>
	    	<span class="text"><?php echo $val->documento_plantilla_nombre_documento?></span>
	    </td>
	    
	    
	    <td><a href="<?php echo base_url().'documentos_base/'.$val->documento_plantilla_path?>">Link</a></td>
	    <td><a data-id="<?php echo $val->documento_plantilla_id?>" class="delete_doc" href="<?php echo site_url().'/admin/doc/plantilla_delete/'.$val->documento_plantilla_id?>">Eliminar</a></td>
	    <td>
	    	<a href="<?php echo site_url()?>/admin/doc/plantilla_edit/<?php echo $val->documento_plantilla_id?>">Editar</a>
	    </td>
  	</tr>
  <?php if($class == 'a'){ $class = 'b'; }else{ $class = 'a'; }?>
  <?php endforeach;?>
</table>
</div>
</div>
<?php endif;?>


<?php if ($tipo == 'documentos'):?>
<div class="tabla-listado">
<div class="content_tabla">
<table width="100%" border="0" class="listado">
  <tr class="menu">
  	<td><b>Mandante</b></td>
    <td><b>Fecha Creacion</b></td>
    <td><b>Tipo Documento</b></td>
    <td><b>Etapa Juicio</b></td>
    
    <td><b>Rut</b></td>
    <td><b>Nombre</b></td>
    
    <td><b>Descargar Documento</b></td>
    <td><b>Eliminar Documento</b></td>
  </tr>
  <?php $class = 'a';?>
  <?php foreach($documentos as $key=>$val): ?> 
  <tr class="<?php echo $class?>" style="font-size:12px;height:35px;" id="doc_<?php echo $val->documento_iddocumento?>">
  <td><?php echo $val->mandantes_codigo_mandante?></td>
    <td><?php echo date("d-m-Y H:i:s", strtotime($val->documento_fecha_crea))?></td>
    <td><?php echo $val->documento_tipo_documento?></td>
    <td><?php echo $val->s_etapas_etapa?></td>
    
    <td><?php echo $val->usuarios_rut?></td>
    <td><?php echo $val->usuarios_nombres?> <?php echo $val->usuarios_ap_pat?> <?php echo $val->usuarios_ap_mat?></td>
    
    
    <td><a href="<?php echo base_url().'documentos/'.$val->documento_nombre_documento?>">Link</a></td>
    <td><a data-id="<?php echo $val->documento_iddocumento?>" class="delete_doc" href="<?php echo site_url().'/admin/doc/delete/'.$val->documento_iddocumento?>">Eliminar</a></td>
  </tr>
  <?php if($class == 'a'){ $class = 'b'; }else{ $class = 'a'; }?>
  <?php endforeach;?>
</table>
</div>
</div>
<?php endif;?>

<?php if ($tipo == 'zip'):?>
<div class="tabla-listado">
<div class="content_tabla">
<table width="100%" border="0" class="listado">
  <tr class="menu">
  	<td><b>Mandante</b></td>
    <td><b>Nombre Documento</b></td>
    <td><b>Fecha Creacion</b></td>
    <td><b>Tipo Documento</b></td>
    <td><b>Etapa Juicio</b></td>
    
    <td><b>Descargar Documento</b></td>
    <td><b>Eliminar Documento</b></td>
  </tr>
  <?php $class = 'a';?>
  <?php foreach($documentos_zip as $key=>$val): ?> 
  <tr class="<?php echo $class?>" style="font-size:12px;height:35px;" id="doc_<?php echo $val->documento_iddocumento?>">
  <td><?php echo $val->mandantes_razon_social?></td>
    <td><?php echo $val->documento_archivo_zip?></td>
    <td><?php echo date("d-m-Y H:i:s", strtotime($val->documento_fecha_crea))?></td>
    <td><?php echo $val->documento_tipo_documento?></td>
    <td><?php echo $val->s_etapas_etapa?></td>
    
    <td><a href="<?php echo base_url().'documentos_zip/'.$val->documento_archivo_zip?>">Link</a></td>
    <td><!-- <a data-id="<?php echo $val->documento_iddocumento?>" class="delete_doc" href="<?php echo site_url().'/admin/doc/delete/'.$val->documento_iddocumento?>">Eliminar</a> --></td>
  </tr>
  <?php if($class == 'a'){ $class = 'b'; }else{ $class = 'a'; }?>
  <?php endforeach;?>
</table>
</div>
</div>
<?php endif;?>


<script type="text/javascript">
$(document).ready(function(){
	
	$('.delete_doc').click(function(){ 
		$(this).html('Eliminando...');
		id_td = $(this).attr('data-id');
			$.ajax({
		        type: 'POST',
		        url: $(this).attr('href'),
		        data: '',
		        success: function (data) {
		        	var json_obj = $.parseJSON(data);
		    		if(json_obj.status == 'exito'){
		    			$("#doc_"+id_td).fadeOut();
		    		}else{
		    			$(this).html(json_obj.contenido);
		    		}
		        	
		        },
		        error: function(objeto, quepaso, otroobj) {
		        },
		    }); 
		return false;
	});

	
});
</script>