<div class="table-m-sep">
  <div class="table-m-sep-title">
    <h2><strong>Mandantes (<?php echo number_format($total,0,',','.');?>)</strong></h2>
    <?php $this->load->view('backend/templates/mod/cat_tools');?>
  </div>
</div>
<div class="agregar-noticia">
  <div class="agregar"> <a class="nueva" href="<?php echo site_url();?>/admin/mandantes/form/">Crear Nuevo Cliente</a> </div>
  <!-- agregar -->
  
  <div class="clear height"></div>
</div>
<!-- agregar-noticia -->

<div class="tabla-listado">
<table class="listado" width="100%">
<tr class="menu">
  <td class="nombre">Razón Social</td>
  <td class="herramientas">Herramientas</td>
</tr>
<?php if (count($lists)>0): ?>
<div class="content_tabla">
  <?php include APPPATH.'views/backend/templates/mandantes/list_tabla.php';?>
</div>
<?php endif;?>
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
</table>
</div>
<?php //$this->load->view('backend/templates/mod/paginacion'); ?>
