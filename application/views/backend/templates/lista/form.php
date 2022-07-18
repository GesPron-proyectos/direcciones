<?php 

 $nombre=''; 
 $id_tribunal=''; 
 $id_tribunal_padre = '';

if($_POST){
    $nombre=$_POST['nombre']; 
	$id_tribunal= $_POST['id_tribunal']; 
	$id_tribunal_padre= $_POST['id_tribunal_padre']; 
}

if (count($lists)>0){
   $nombre=$lists->nombre;
   $id_tribunal=$lists->id_tribunal;
   $id_tribunal_padre = $lists->id_tribunal_padre;
}

?>


<form action="<?php echo site_url().'/admin/lista/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">
<div class="titulo">
    <strong style="float:left; margin-right:10px;">Complete los datos de la comuna</strong>
     <?php if (validation_errors()!=''): ?>
    <span class="alerta"></span><span class="error">Faltan campos por completar</span> 
    <?php endif;?>
    <span class="flechita"></span>
    <div class="clear"></div>
</div>

<div class="blq">
    <div class="dos">
      <div class="clear"></div>
      <div class="cont-form">
          <label for="comuna" style="width:135px;">Comuna*:</label>
          <input id="comuna" name="comuna" type="text" value="<?php echo $nombre;?>" style="width:210px;">
          <?php form_error('comuna');?>
      </div>
      <div class="clear"></div>
      
       <?php if ($nodo->nombre=='fullpay'):?>
      <div class="cont-form">
        <label style="width: 127px; float: left">Tribunal*:</label>
        <div class="clear"></div>
        <?php echo form_dropdown('id_tribunal_padre', $tribunales_padres, $id_tribunal_padre);?>
        <div class="clear"></div>
        <?php echo form_error('id_tribunal_padre','<span class="error">', '</span>');?>
    </div>
      <?php endif;?>
      
       <div class="clear"></div>
      
      </div><!--dos-->

    <div class="clear"></div>

    <input type="submit" value="Guardar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq-->  

</form>

