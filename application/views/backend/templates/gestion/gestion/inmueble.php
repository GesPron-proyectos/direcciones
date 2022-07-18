<?php $this->load->view('backend/templates/gestion/gestion/inmueble_form');?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.min.css">


<style>.td_inm{text-align: center;}</style>
<div id="box-form-inmueble"> </div>
<table class="stable" style="width:100%;">

<tr><td><h3>Listado Inmuebles:</h3><br></td></tr>
<tr>
    <td>
    <table class="tabla_list cell-border" style="width:100%;">
        <thead>
        <tr class="col_list">
        	<th>#</th>
            <th>Observación</th>
            <?php if($nodo->nombre == 'fullpay'):?>
            <th>Dirección</th>
            <th>Comuna</th>
            <th>Inscripción</th>
            <th>Avalúo</th>
            <?php endif;?>
             <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
              <th>Gestión</th>
              <?php endif;?>
        </tr>
        </thead>
        <tbody>

        <?php
        $i = 0; 
        foreach ($inmueb as $inmueble):?>
            <tr>
            <td class="td_inm">#<?php echo $inmueble->idinmueble;?></td>
            <td class="td_inm"><?php echo $inmueble->observacion;?></td>
            <td class="td_inm"><?php echo $inmueble->direccion;?></td>
            <td class="td_inm"><?php echo $inmueble->nombre;?></td>
            <td class="td_inm"><?php echo $inmueble->nombre_inscripcion;?></td>
            <td class="td_inm"><?php echo $inmueble->avaluo;?></td>
            
            <td class="td_inm">
                <a href="<?php echo site_url('admin/gestion/editar_inmueble/'.$id.'/'.$inmueble->idinmueble);?>" class="edited" data-id="<?php echo $inmueble->idinmueble;?>" data-gtab="inmueble">Editar</a>

                <a href="<?php echo site_url('admin/gestion/eliminar_inmueble/'.$id.'/'.$inmueble->idinmueble);?>" class="delete">Eliminar</a>
            </td>
            <?php $i++;?>
            <?php endforeach;?>
           </tr>
        <tbody>
</table>
  </td>
</tr>
</table>

<script src="<?php echo base_url();?>js/datatables.min.js"></script>
<script src="<?php echo base_url();?>js/datatables.js"></script>
<script type="text/javascript">

    $(document).on("click",".closed",function(e){
        var gtab = $(this).data('gtab');
        $("#box-form-"+gtab).html('');
        $("#tbinmueble tbody tr").each(function(index){
            $(this).removeClass('current');
        });
        return false;
    });

    $(document).on("click",".edited", function(e){
        tr = $(this).parent('td').parent('tr');
        $("#tbinmueble tbody tr").each(function(index){
            $(this).removeClass('current');
        });
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