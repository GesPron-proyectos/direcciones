<?php 
$padre=''; $tribunal='';  $posicion =''; $id_jurisdiccion ='';

if($_POST){
	$tribunal=$_POST['tribunal'];
	$padre=$_POST['padre'];
	//$id_comuna=$_POST['id_comuna'];
	$id_jurisdiccion=$_POST['id_jurisdiccion'];
	$posicion =$_POST['posicion'];
}
if (count($lists)>0){
	$padre=$lists->padre; 
	$tribunal=$lists->tribunal;
	//$id_comuna=$lists->id_comuna;
	$id_jurisdiccion=$lists->id_jurisdiccion;
	$posicion =$lists->posicion;
}

?>

<form action="<?php echo site_url().'/admin/tribunales/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">

<div class="titulo">
   <strong style="float:left; margin-right:10px;">Complete los datos del tribunal</strong> 
      <span class="flechita"></span>
    <div class="clear"></div>
</div>
<div class="blq">



    <div class="dos">

	<div class="cont-form">
		<?php echo form_label('Ingresar','ingresar',array('style' => 'width:160px;'));?>
		<select name="ingresar" id="ingresar">
			<option value="0">Seleccionar...</option>
			<option value="1">Jurisdiccion</option>
			<option value="2">Tribunal</option>
		</select>
    </div>
     <div class="clear"></div>
      <div class="cont-form" style="display:none" id="distritoid">
    	<?php echo form_label('Jurisdicción','padre',array('style' => 'width:160px;'));?>
    	<?php echo form_dropdown('padre',$tribunales,$padre);?>
           <div class="clear"></div>
    	<?php echo form_error('id', '<span class="error">','</span>');?>
    </div>
     <div class="clear"></div>
     <div class="cont-form" style="display:none" id="tribunalid">
          <label for="tribunal" style="width:160px;" id="idnombret" >Jurisdicción:</label>
          <input id="tribunal" name="tribunal" type="text" value="<?php echo $tribunal;?>" style="width:160px;">
          <div class="clear"></div>
          <?php echo form_error('tribunal', '<span class="error">', '</span>');?>
      </div>
    <div class="clear"></div>
    <div class="cont-form" style="display:none" id="posicionid">
          <label for="posicion" style="width:160px;" >Posición:</label>
          <input id="posicion" name="posicion" type="text" value="<?php echo $posicion;?>" style="width:160px;">
          <div class="clear"></div>
          <?php echo form_error('posicion', '<span class="error">', '</span>');?>
      </div>
      
       <div class="clear"></div>
     <div class="cont-form" style="display:none">
    	<?php echo form_label('Jurisdicciones', 'id_jurisdiccion', array('style' => 'width:160px;'));?>
    	<?php echo form_dropdown('id_jurisdiccion', $jurisdicciones, $id_jurisdiccion);?>
           <div class="clear"></div>
    	<?php echo form_error('id_jurisdiccion', '<span class="error">', '</span>');?>
    </div>
      
     </div><!--dos-->
    <div class="clear"></div>
    <input type="submit" value="Guardar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq-->  

</form>

<script type="text/javascript">
$(window).load(function() {
	
	$(document).on("change", "select[name='ingresar']", function(event){
		var id_tipo = $(this).val();
		
		$('#autos').show();
		$('#autos').css("autos", "block");
		switch(id_tipo)
		{
			case "1":
			$('#tribunalid').show();
			$('#distritoid').hide();
			$('#posicionid').show();
			$('#idnombret').text('Jurisdicción');	
			break;
			case "2":
			$('#posicionid').show();
			$('#idnombret').text('Tribunal');
			
			$('#tribunalid').show();
			$('#distritoid').show();
			break;
			
		}
	});

	
});
</script>