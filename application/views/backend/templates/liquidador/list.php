<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/estilos_css.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.min.css">

<script src="<?php echo base_url();?>js/datatables.min.js"></script>
<script src="<?php echo base_url();?>js/datatables.js"></script>
<div class="table-m-sep">

  <div class="table-m-sep-title">

    <h2><strong>Acreedores (<?php echo number_format($total,0,',','.');?>)</strong></h2>

  </div>

</div>

<div class="agregar-noticia">

  <div class="agregar"> <a class="nueva" href="<?php echo site_url();?>/admin/liquidador/form/">Crear Nuevo Liquidador</a> </div>

  <!-- agregar -->

  <div class="clear height"></div>

</div>

<!-- agregar-noticia -->



<div class="tabla-listado">

<table class="tabla_list cell-border" width="100%">
<thead>
<tr class="col_list">

	  <th class="nombre"><label>Nombre y Apellido Pat.</label></th>
    <th class="nombre"><label>Rut</label></th>
  	<th class="herramientas"><label>Herramientas</label></th>

</tr>
</thead>
<tbody>

<?php if (count($lists)>0): ?>

<div class="content_tabla">

  <?php include APPPATH.'views/backend/templates/liquidador/list_tabla.php';?>

</div>

<?php endif;?>

<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
</tr>
</tbody>
</table>


</div>

<?php //$this->load->view('backend/templates/mod/paginacion'); ?>

<script>

      $('.tabla_list').DataTable({
    language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },
  });

</script>