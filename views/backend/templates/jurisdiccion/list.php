   <?php $jurisdiccion = ''; if (isset($_REQUEST['jurisdiccion'])){$jurisdiccion = $_REQUEST['jurisdiccion'];} ?>


<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Jurisdicción (<?php echo $total;?>)</strong></h2>
      <?php $this->load->view('backend/templates/mod/cat_tools');?>
  </div>
</div>


<div class="agregar-noticia">
	<div class="agregar">
		<a href="<?php echo site_url()?>/admin/<?php echo $current ?>/form" class="nueva">Crear Nueva Jurisdicción</a>
	</div>
	<div class="clear height"></div>
    <form action="<?php echo site_url().'/admin/jurisdicciones';?>" method="post">
    <label style="width:135px; float:left">Jurisdicciones:</label>
      <input id="jurisdiccion" name="jurisdiccion" type="text" value="<?php echo $jurisdiccion;?>" style="width:300px;">
   <input type="submit" name="Buscar" value="Buscar" class="boton" style="width:7%;">
    </form>
     <div class="clear"></div>
     <div class="clear"></div>
</div> 

<br>
<?php if (count($lists)>0): ?>
<?php // echo $this->pagination->create_links(); ?>
<div class="clear"></div>

<div class="tabla-listado">
    <div class="content_tabla">
	   <?php include APPPATH.'views/backend/templates/'.$current.'/list_tabla.php';?>
   </div>
	
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

</div>

<?php endif;?>  

<?php // echo $this->pagination->create_links(); ?>

