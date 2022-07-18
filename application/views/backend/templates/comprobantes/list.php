

<div class="table-m-sep">

  <div class="table-m-sep-title">

  <h2><strong>Comprobante (<?php echo number_format($total,0,',','.');?>)</strong></h2>

      <?php $this->load->view('backend/templates/mod/cat_tools');?>

  </div>

</div>

<div class="agregar-noticia">
<div class="clear height"></div>

</div><!-- agregar-noticia -->
<?php if (count($lists)>0): ?>
<div class="tabla-listado">




  <?php include APPPATH.'views/backend/templates/comprobantes/list_tabla.php';?>

<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>



</div>


<?php endif;?>

<?php //$this->load->view('backend/templates/mod/paginacion'); ?>    