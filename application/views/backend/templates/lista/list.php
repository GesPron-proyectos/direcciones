  <?php $comuna = ''; if (isset($_REQUEST['comuna'])){$comuna = $_REQUEST['comuna'];} ?>

<div class="table-m-sep">
  <div class="table-m-sep-title">
    <h2><strong>Comunas (<?php echo count($lists)?>)</strong></h2>
    <?php $this->load->view('backend/templates/mod/cat_tools');?>
  </div>
</div>


<div class="agregar-noticia">
  <div class="agregar"> 
  	<a class="nueva" href="<?php echo site_url();?>/admin/lista/form">Crear Nueva Comuna</a>
  </div>
  <div class="clear height"></div>
   <form action="<?php echo site_url().'/admin/lista';?>" method="post">
    <label style="width:135px; float:left">Nombre Comuna:</label>
      <input id="comuna" name="comuna" type="text" value="<?php echo $comuna;?>" style="width:300px;">
   <input type="submit" name="Buscar" value="Buscar" class="boton" style="width:7%;">
    </form>
     <div class="clear"></div>
     <div class="clear"></div>

</div>
 



<div class="tabla-listado">

<?php // print_r($errors); ?>  

  <table class="listado" width="100%">
    <tr class="menu">
    	<td width="25%" class="nombre">Nombre</td>
        <?php if ($nodo->nombre=='fullpay'):?>
        <td width="25%" class="nombre">Tribunal</td>
           <?php endif;?>
        	<td width="25%" class="nombre">Herramientas</td>
    </tr>
    <?php if (count($lists)>0): ?>
	    <div class="content_tabla">
			<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>
			<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>" style="line-height: 28px;" >
			  <td width="11%"><?php echo $val->nombre;?></td>
              
              <?php if ($nodo->nombre=='fullpay'):?>
              <td width="11%">
			 <?php if($val->id_tribunal_padre > 0 ){ 
			  	echo $val->tribunal_padre; 
			  } ?>
              </td>
              <?php endif;?>
              
              <?php $row_current=array(); $row_current=$val; include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?> 
			</tr>
			<?php ++$i;endforeach;?>
	    </div>
    <?php endif;?>

    <?php $colspan=2;?>
  </table>
</div>
<?php //echo $this->pagination->create_links(); ?> 







