<div class="titulo">

 <strong style="float:left; margin-right:10px;">Gererar Documentos</strong> 

  
  <span class="flechita"></span>

  <div class="clear"></div>

</div>

<?php 
//ini_set('display_errors', 1);
//print_r($_POST);
//echo urldecode($this->input->post('tipo_documento'));
?>
<div class="blq">

<form action="<?php echo base_url()?>index.php/admin/doc/buscarruts" method="post" autocomplete="off">

	<div class="cont-form">
		<label style="width:135px!important; float:left">Mandante*:</label>
		
		<select name="mandante">
			<option value="">Seleccionar</option>
		<?php foreach($mandantes as $key=>$val):?>
			<option value="<?php echo $val->id?>" <?php if ($val->id==$this->input->post('mandante')) {echo 'selected';}?>><?php echo $val->razon_social?></option>
		<?php endforeach;?>
		</select>
		<div class="clear"></div>
		<span id="error_rut" class="error"></span>
	</div>

	<div class="cont-form">
		<label style="width:135px!important; float:left">Tipo documento*:</label>
	<?php if ($val->id==$this->input->post('mandante')) {echo 'selected';}?>
		<?php echo form_dropdown('tipo_documento', $documentos,$this->input->post('tipo_documento'),'id="tipo_documento"' )?>
		<div class="clear"></div>
		<span id="error_documento" class="error" style="margin-left: 137px; display: block;"></span>	
	</div>	

	<div class="cont-form">
		<label style="width:135px!important; float:left">Estado Cuenta*:</label>
	
		<?php 
		$est_cuentas = array();
		foreach($estados_cuenta as $key=>$val){
			$est_cuentas[$val->id] = $val->estado;
		}?>		
		<?php echo form_dropdown('estado_cuenta', $est_cuentas, 1 )?>		
		<div class="clear"></div>
		<span id="error_rut" class="error"></span>
	</div>
	
	<div class="cont-form">
		<label style="width:135px!important; float:left">Fecha *:</label>
		<input type="text" name="fecha" id="fechaaaa" data-date-format="dd-mm-yyyy" value="<?php echo $this->input->post('fecha')?>">
		<div class="clear"></div>
	</div>

<?php if($nodo->nombre == 'fullpay'):?>
	<div class="cont-form">
		<label style="width:135px!important; float:left">Tipo demanda *:</label>
		<?php echo form_dropdown('tipo_demanda', array(''=>'-- Selecionar --','1'=>'Propia','0'=>'Cedida') )?>
		<div class="clear"></div>
	</div>

	<div class="cont-form">
		<label style="width:135px!important; float:left">Exhorto *:</label>
		
		<?php echo form_dropdown('exorto', array(''=>'-- Selecionar --','1'=>'Con exhorto','0'=>'Sin exhorto') )?>
		<div class="clear"></div>
	</div>
<?php endif; //end condicion pages?>
	
	<div class="clear"></div>
		
    <div class="cont-form">
	<?php 
	/*echo "<pre>";
	print_r($etapas);
	echo "<pre>";*/
	?>
		<label style="width:135px!important; float:left">Seleccionar cuentas en etapa De Juicio*:</label>
		<select name="id_etapa_original" id="id_etapa_original">
		<option value="">---</option>
		<?php foreach($etapas as $key=>$val):?>
			<option value="<?php echo $val->id?>" <?php if ($val->id==$this->input->post('id_etapa_original')) {echo 'selected';}?>><?php echo $val->codigo?> <?php echo $val->etapa?></option>
		<?php endforeach;?>
		</select>
		<div class="clear"></div>
		<span id="error_rut" class="error"></span>
	</div>
	
	<div class="cont-form">	
		<input type="submit" style="width:135px; float:left;" id="submit" class="boton" value="Buscar">	
	</div>
		
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('#fechaaaa').datepicker();
			$("#checkAll").click(function(){
				if($(this).is(':checked')){
					$("#cont input[type=checkbox]").prop('checked', true);
				}else{
					$("#cont input[type=checkbox]").prop('checked', false);
				}
			});			
		});
		function verCheck(){
			var tds = 'ok'
			$("#cont input[type=checkbox]").each(function() {
				if(!$(this).is(':checked')){
					tds='nok';
				}
			});
			if(tds == 'ok'){
				$("#checkAll").prop('checked', true);
			}else{
				$("#checkAll").prop('checked', false);
			}
		}
	</script>
</form>
<form action="<?php echo base_url()?>index.php/admin/doc/generardocnew" method="post" autocomplete="off">
	
	 
	<input type="hidden" name="mandante" id="mandante" value="<?php echo $this->input->post('mandante')?>">
	<input type="hidden" name="tipo_documento" id="tipo_documento" value="<?php echo $this->input->post('tipo_documento')?>">
	<input type="hidden" name="estado_cuenta" id="estado_cuenta" value="<?php echo $this->input->post('estado_cuenta')?>">
	<input type="hidden" name="tipo_demanda" id="tipo_demanda"  value="<?php echo $this->input->post('tipo_demanda')?>">
	<input type="hidden" name="exorto" id="exorto" value="<?php echo $this->input->post('exorto')?>">
	<input type="hidden" name="id_etapa_original" id="id_etapa_original" value="<?php echo $this->input->post('id_etapa_original')?>">
	<input type="hidden" name="fecha" id="fecha" value="<?php echo $this->input->post('fecha')?>">
	
	
	<div class="cont-form">
		<label style="width:135px!important; float:left">Demandado:</label>
		<!--<select size="15" style="width:735px!important; float:left"	name="Demandados[]" multiple>-->
			<?php if (count($datos) > 0): ?>
				<input type="checkbox" id="checkAll" value="" name="checkAll"/> Seleccioanr todos <br><br>
			<?php endif;?>
			<div style="height: 30em; width: 50em; overflow: auto;margin-left:9.7em;" id='cont'>			
			<?php foreach($datos as $key=>$val):?>
				<input type="checkbox" onclick='verCheck()' id="<?php echo $val->rut?>" value="<?php echo $val->rut?>" name="Demandados[]"/>
					<label for="<?php echo $val->rut?>"> <?php echo substr($val->nombres.' '.$val->ap_pat, 0, 20);?> <?php echo $val->rut?></label><br/>				
			<?php endforeach;?>
			</div>
		<!--</select>		-->
		<div class="clear"></div>
		<span id="error_rut" class="error"></span>
	</div>
		<div class="clear"></div>
	<div class="cont-form">	
		<input type="submit" style="width:135px; float:left;" id="submit" class="boton" value="Generar">	
	</div>
</form>
<script type="text/javascript">

$(document).on("change","#id_etapa,#tipo_documento",function(e){
	
	var val = $("#id_etapa").val();
	
	var val_documento = $("#tipo_documento").val();
	var text_documento = $("#tipo_documento option:selected").text();
		
	var parts = val_documento.split(".");
	
	//console.log(text_documento);
	//console.log(val);
	//console.log(parts[1]);

	if( parts[1] == 'docx' ){

		$('#error_documento').fadeOut();
		
		if(val == ''){
			//$('#submit').fadeOut();
			//$('#alerta_estapa_juicio').fadeIn();
		}else{
			//$('#alerta_estapa_juicio').fadeOut();
			//$('#submit').fadeIn();
		}
		
	}else{
		$('#error_documento').fadeIn();
		$('#error_documento').html('El documento ( '+text_documento+' ) <br>posee extensión doc,<br> solo se permite docuementos con extensión .docx ');
		//$('#submit').fadeOut();
		//$('#alerta_estapa_juicio').fadeOut();
	}
	
	
	
	return false;
});

</script>

<div class="clear"></div>
<?php if($exito != ''):?>
<?php echo $exito?>
<?php endif;?>

</div>