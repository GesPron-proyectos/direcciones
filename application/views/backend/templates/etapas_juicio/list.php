  <?php $etapa = ''; if (isset($_REQUEST['etapa'])){$etapa = $_REQUEST['etapa'];} ?>
  <?php $codigo = ''; if (isset($_REQUEST['codigo'])){$codigo = $_REQUEST['codigo'];} ?>
<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Etapas (<?php echo number_format($total,0,',','.');?>)</strong></h2>
      <?php $this->load->view('backend/templates/mod/cat_tools');?>
  </div>
</div>

<div class="agregar-noticia">
	<div class="agregar">
		<a href="<?php echo site_url()?>/admin/<?php echo $current ?>/form/" class="nueva">Crear Nueva Etapa</a>
	</div>
	<div class="clear height"></div>
    <form action="<?php echo site_url().'/admin/etapas_juicio';?>" method="post">
    <label style="width:135px; float:left">Etapas Juicio:</label>
      <input id="etapa" name="etapa" type="text" value="<?php echo $etapa;?>" style="width:500px;">
         <div class="clear"></div>
          <label style="width:135px; float:left">Código:</label>
     <input id="codigo" name="codigo" type="text" value="<?php echo $codigo;?>" style="width:100px;">
   <input type="submit" name="Buscar" value="Buscar" class="boton" style="width:7%;">
    </form>
     <div class="clear"></div>
     <div class="clear"></div>  
</div>


<br>
<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>

<div class="tabla-listado">

	<div class="content_tabla">
	
	  <?php include APPPATH.'views/backend/templates/'.$current.'/list_tabla.php';?>
	  
	</div>
	
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

</div>

<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>

