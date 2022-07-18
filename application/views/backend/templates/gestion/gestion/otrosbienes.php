<?php $this->load->view('backend/templates/gestion/gestion/otrosbienes_form');?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/estilos_css.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.min.css">

<script src="<?php echo base_url();?>js/datatables.min.js"></script>
<script src="<?php echo base_url();?>js/datatables.js"></script>
<style>.td_inm{text-align: center;}</style>

<div id="box-form-otrosbienes"></div>
  <table class="stable" style="width: 100%;">
  <tr>
    <td><h3>Otros Bienes:</h3><br></td>
  </tr>
  <tr>
    <td>
    <table class="tabla_list cell-border" style="width: 900px;">
        <thead>
        <tr class="col_list">
        	<th>#</th>
            <th>Observación</th>
            <th>Otros Bienes</th>
            <th>Gestión</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $i = 0; 
        if (count($obienes)>0):?>
        <?php foreach ($obienes as $otrosbienes):?>
            <tr>
            <td class="td_inm">#<?php echo $otrosbienes->idotros_bienes;?></td>
            <td class="td_inm"><?php echo $otrosbienes->observacion;?></td>
            <td class="td_inm"><?php echo $otrosbienes->otros;?></td>
            <td class="td_inm">
                <a href="<?php echo site_url('admin/gestion/editar_bien/'.$id.'/'.$otrosbienes->idotros_bienes);?>" class="edited" data-id="<?php echo $otrosbienes->idotros_bienes;?>" data-gtab="otrosbienes">Editar</a>

                <a href="<?php echo site_url('admin/gestion/eliminar_otro_bien/'.$id.'/'.$otrosbienes->idotros_bienes);?>" class="delete">Eliminar</a>
            </td>
            <?php $i++;?>
            <?php endforeach;?>   
        <tbody>
            </tr>
            <?php endif;?>
    </table>
  </td></tr>
</table>

<script>

$(document).on("click",".closed",function(e){
        var gtab = $(this).data('gtab');
        $("#box-form-"+gtab).html('');
        $(".stable tr").removeClass('current');
        return false;
    });

$(document).on("click",".edited",function(e){
        tr = $(this).parent('td').parent('tr');
        tr.addClass('current');
        var id = $(this).data('id');
        var gtab = $(this).data('gtab');
         $.ajax({
           type: 'post',
           url: '<?php echo site_url()?>/admin/gestion/loadform/<?php echo $id;?>/'+gtab+'/'+id,
           success: function (data) {
              $("#box-form-"+gtab).html(data);
           },
       });
       return false;
    });

</script>