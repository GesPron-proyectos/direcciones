<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/estilos_css.css">
<!--DataTable Registro y Gestión Acreedor-->
<table id="tabsd" class="display">
    <thead>
      <tr class="col_list">
        <th>id</th>
        <th>Fecha Presentado</th>
        <th>Monto</th>
        <th>Estado Acreedor</th>
        <th>Tipo Verificación</th>
        <th>Preferencia</th>
        <th>Gestión</th>
      </tr>
    </thead>
    <tbody>
        <?php
      $i = 0;
          foreach ($acreedores_list as $key=>$acreedor):?>
      <tr>
        <td class="num_list"><?php echo ($i+1);?></td>
    
        <td class="montoac_list"><?php echo $acreedor->monto;?></td>

        <td class="verif_list"><?php echo $acreedor->tipo_verificacion;?></td>
        <td class="pref_list"><?php echo $acreedor->preferencia;?></td>
        <td class="gestac_list">
            <a href="<?php echo site_url('admin/gestion/editar_acreedor/'.$id.'/'.$acreedor->idacreedor);?>" class="edit" data-id="<?php echo $acreedor->idacreedor;?>" data-gtab="acreedor">Editar</a>
            <a href="<?php echo site_url('admin/gestion/eliminar_acreedor/'.$id.'/'.$acreedor->idacreedor);?>" class="delete" style ="float: right;">Eliminar</a>
        </td>
        <?php $i++; ?>
        <?php endforeach;?>
      </tr>
    <tbody>
</table>
<script type="text/javascript">

$('#tabsd').DataTable({
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