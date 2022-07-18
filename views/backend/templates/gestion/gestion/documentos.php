<table class="stable" width="100%">
     <tr><td colspan="4"><h3>Generar documento:</h3><br></td></tr>
    <tr>
    <td colspan="4">
    
    <?php echo form_open(site_url().'/admin/doc/generardoc/'.$id); ?>
    <?php echo form_dropdown('tipo_documento', $documentos_plantillas,'' )?>
    <input type="text" name="fecha" class="datepicker" value="<?php echo date('d/m/Y');?>">
  
    
    <input type="hidden" name="id_cuenta" value="<?php echo $id?>" >
    <input type="submit" value="Generar">
    <?php echo form_close();?>
    <?php if (count($documentos_plantillas)>0):?>
    <hr />
    <h4>Todos las plantillas</h4>
    <?php echo form_open(site_url().'/admin/doc/generardoc/'.$id); ?>
    <?php echo form_dropdown('tipo_documento', $documentos_plantillas_todas,'' )?>
    <input type="text" name="fecha" class="datepicker" value="<?php echo date('d/m/Y');?>">
    <input type="hidden" name="id_cuenta" value="<?php echo $id?>" >
    <input type="submit" value="Generar">
    <?php echo form_close();?>
    <?php endif;?>
    </td>
    </tr>
    <tr><td colspan="4"><hr style="border:1px solid #CDCCCC"><br><h3>Ingresar un nuevo documento:</h3><br></td></tr>
    <tr>
        <td>Archivo:</td>
        <td>
        <input type="hidden" name="otro_upload" value="<?php echo $cuenta->id;?>" />
        <input type="file" id="file5" name="file5" style="float:left;"/>
        
        </td>
    </tr>
    
    <tr>
    <tr><td colspan="2"><h3>Documentos:</h3><br></td></tr>
    <tr>
        <td colspan="2">
        <div id="documentos_all_table">
        <?php $this->load->view('backend/templates/gestion/gestion/documento_tabla'); ?>
        </div>
    </td></tr>
</table>
<script type="text/javascript" src="<?php echo base_url()?>js/pekeUpload.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	 $("#file5").pekeUpload({
		 	theme:'bootstrap',
			btnText:"Subir Nuevo Documento.", 
			allowedExtensions:"docx|doc|pdf|xlsx|xls",
			invalidExtError:"Tipo de archivo inválido.",
			showFilename:false,
			url:"<?php echo base_url()?>index.php/admin/doc/upload/<?php echo $id?>", 
			onFileSuccess:function(file,data){
				setTimeout(function(){
					$(".pekecontainer").fadeOut(function() {
						$(this).html('').fadeIn();
					});
					reload_documentos();
				}, 3000); 
		    },onFileError:function(file,error){
				setTimeout(function(){
					$(".pekecontainer").fadeOut(function() {
						$(this).html('').fadeIn();
					});
			}, 3000); 
      }});

      $(".btn-upload").css('marginTop','10px');
      $(".btn-upload").css('marginLeft','13px');
      $(".btn-upload").show();
	  function reload_documentos(){
		$.ajax({
				type: 'post',
				url: '<?php echo base_url()?>index.php/admin/doc/reload_doc_procurador/<?php echo $id?>',
				data: '',
				success: function (data) {
					$('#documentos_all_table').html(data);
				},
				error: function(objeto, quepaso, otroobj) {
				},
		});
	}
});
</script>