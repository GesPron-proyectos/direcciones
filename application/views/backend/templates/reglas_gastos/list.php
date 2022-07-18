<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Etapas (<?php echo number_format($total,0,',','.');?>)</strong></h2>
      <?php $this->load->view('backend/templates/mod/cat_tools');?>
  </div>
</div>


<div class="agregar-noticia">
	<div class="agregar">
		<a href="<?php echo site_url()?>/admin/<?php echo $current ?>/form/" class="nueva">Crear Nueva Regla</a>
	</div>
	<div class="clear height"></div>
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

