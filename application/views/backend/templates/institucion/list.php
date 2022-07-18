<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/estilos_css.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/datatables.min.js"></script>
<script src="<?php echo base_url();?>js/datatables.js"></script>
<style>div.content {
    margin-bottom: 135px;
}
.subCartas {
  background-color:#7bde46;
  -moz-border-radius:13px;
  -webkit-border-radius:13px;
  border-radius:9px;
  border:1px solid #18ab29;
  display:inline-block;
  cursor:pointer;
  color:#ffffff;
  font-family:Arial;
  font-size:15px;
  padding:7px 15px;
  text-decoration:none;
  text-shadow:0px 1px 0px #2f6627;
}
.subCartas:hover {
  background-color:#5cbf2a;
}
.subCartas:active {
  position:relative;
  top:1px;
}

</style>
<div class="table-m-sep">

  <div class="table-m-sep-title">

    <h2><strong>Instituciones (<?php echo number_format($total,0,',','.');?>)</strong></h2>

  </div>

</div>

<div class="agregar-noticia">

  <div class="agregar"> <a class="nueva" href="<?php echo site_url();?>/admin/institucion/form/">Crear Nueva Institucion</a> </div>

  <!-- agregar -->

  <div class="clear height"></div>

</div>

<!-- agregar-noticia -->

<div class="tabla-listado">

<table class="tabla_list cell-border" width="100%">
<thead>
  <tr class="col_list">

    
    <th class="nombre"><label>Razón Social</label></th>
    <th class="nombre" style= "width: 160px;"><label>Alias</label></th>
    <th class="nombre"><label>Dirección Institución</label></th>
    <th class="herramientas"><label>Herramientas</label></th>

  </tr>
</thead>
<tbody>

<?php if (count($lists)>0): ?>

<div class="content_tabla">

  <?php include APPPATH.'views/backend/templates/institucion/list_tabla.php';?>

</div>

<?php endif;?>

<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
</tr>
</tbody>
</table></br>

<!--
<div style="float: right;"><input type="submit" class="subCartas" id="enviar" name="subPriori" value="Prioritarios"></div>
</form>


</div>
-->

<?php //$this->load->view('backend/templates/mod/paginacion'); ?>
    <script type="text/javascript">

/*      var selected = new Array();

       $(document).ready(function() {
        $("input:checkbox:checked").each(function() {
             alert($(this).val());
        });
});*/


    $(document).ready(function() {
  $('#enviar').click(function() {
    // defines un arreglo
    var selected = [];
    $(":checkbox[name=page]").each(function() {
      if (this.checked) {
        // agregas cada elemento.
        selected.push($(this).val());
      }
    });
    if (selected.length) {

      $.ajax({
        cache: false,
        type: 'post',
        dataType: 'json', // importante para que 
        data: selected, // jQuery convierta el array a JSON
        url: 'roles/paginas',
        success: function(data) {
          alert('datos enviados');
        }
      });

      // esto es solo para demostrar el json,
      // con fines didacticos
      alert(JSON.stringify(selected));

    } else
      alert('Debes seleccionar al menos una opción.');

    return false;
  });
});

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