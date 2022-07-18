<?php 
$jurisdiccion=''; 

if($_POST){
	$jurisdiccion=$_POST['jurisdiccion'];	
}
if (count($lists)>0){
     $jurisdiccion=$lists->jurisdiccion;
	}

?>

<form action="<?php echo site_url().'/admin/jurisdiccion/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">

<div class="titulo">
   <strong style="float:left; margin-right:10px;">Complete los datos de la jurisdicción</strong> 
      <span class="flechita"></span>
    <div class="clear"></div>
</div>
<div class="blq">
    <div class="dos">
     <div class="clear"></div>
     <div class="cont-form">
          <label for="jurisdiccion" style="width:160px;" >Jurisdicción:</label>
          <input id="jurisdiccion" name="jurisdiccion" type="text" value="<?php echo $jurisdiccion;?>" style="width:160px;">
          <div class="clear"></div>
          <?php echo form_error('jurisdiccion', '<span class="error">', '</span>');?>
      </div>
     </div><!--dos-->
    <div class="clear"></div>
    <input type="submit" value="Guardar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq-->  

</form>

