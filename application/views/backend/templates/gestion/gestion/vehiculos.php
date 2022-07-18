<?php 
//Bien Inmueble
$observacion_in = $this->input->post('observacion');
$direccion_in   = $this->input->post('direccion');
$id_comuna_in   = $this->input->post('comuna_idcomuna');
$avaluo_in      = $this->input->post('avaluo');
$inscripcion_in = $this->input->post('inscripcion_idinscripcion');
//Otros Bienes
$observacion_b  = $this->input->post('observacion');
$otro_b         = $this->input->post('otros');

if ($idregistro!=''){
    //Bien Inmueble
    $observacion_in = $inmueble->observacion;
    $direccion_in   = $inmueble->direccion;
    $id_comuna_in   = $inmueble->comuna_idcomuna;
    $avaluo_in      = $inmueble->avaluo;
    $inscripcion_in = $inmueble->inscripcion_idinscripcion;

    //Otros Bienes
    $observacion_b  = $otrosbienes->observacion;
    $otro_b         = $otrosbienes->otros;
}

?>
<?php $this->load->view('backend/templates/gestion/gestion/vehiculos_form');?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/estilos_css.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>

<style>.td_inm{text-align: center;}</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="<?php echo base_url();?>js/datatables.min.js"></script>
<script src="<?php echo base_url();?>js/datatables.js"></script>
</br></br></br>
<div id="box-form-vehiculos"></div>
<?php if($nodo->nombre == 'fullpay' && count($vehic) > 0){ ?></br>
<a id="exp_veh" href="<?php echo site_url();?>/admin/gestion/exportador_vehiculos_fullpay?id_cuenta=<?php echo $id; ?>" class="ico-excel" style="margin-left:0;width:150px;color:#578698;">Exportar a Excel</a>
<div class="show_a" style="display:none;">
    <label>
        <input type="checkbox" id="checkAllV" name="checkAllV"/>
        <div>Resultado del filtro aplicado directamente en la tabla</div>
    </label>
</div>
<?php } ?>
<table id="tbvehiculos" class="cell-border">
    <thead>
    <tr class="col_list">
    	<th>#</th>
        <th>Observación</th>
        <?php if($nodo->nombre == 'fullpay'):?>
        <th>Tipo vehículo</th>
        <th>Patente</th>
        <th>Año</th>
        <th>Color</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Nº Motor</th>
        <th>Nº Chasis</th>
        <th>Inscripción</th>
        <th>Estado</th>
        <?php endif;?>
         <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
          <th>Gestión</th>
          <?php endif;?>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0; 
    if (count($vehic)>0):?>
    <?php foreach ($vehic as $key=>$vehiculos):?>
        <tr id="row-<?php echo $vehiculos->idvehiculo; ?>">
            <td>#<?php echo $vehiculos->idvehiculo;?></td>
            <td><?php echo $vehiculos->observacion;?></td>
            <td><?php echo $vehiculos->tipo_vehiculo;?></td>
            <td><?php echo $vehiculos->patente;?></td>
            <td><?php echo $vehiculos->anio;?></td>
            <td><?php echo $vehiculos->color;?></td>
            <td><?php echo $vehiculos->marca?></td>
            <td><?php echo $vehiculos->modelo;?></td>
            <td><?php echo $vehiculos->n_motor;?></td>
            <td><?php echo $vehiculos->n_chasis;?></td>
            <td><?php echo $vehiculos->inscripcion;?></td>
            <td><?php echo $vehiculos->estado;?></td>
            <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
            <td>
                <a href="<?php echo site_url('admin/gestion/editar_vehiculos/'.$id.'/'.$lists->idvehiculo);?>" class="edited" data-id="<?php echo $vehiculos->idvehiculo;?>" data-gtab="vehiculos">Editar</a>
                <a href="<?php echo site_url('admin/gestion/eliminar_vehiculo/'.$id.'/'.$vehiculos->idvehiculo);?>" class="delete">Eliminar</a>
            </td>
            <?php $i++;?>
            <?php endif;?>
            <?php endforeach;?>
            <?php endif;?>
        </tr>
    <tbody>
</table>
</br></br></br>
<div><u><h2>Inmuebles</h2></u></div>
</br></br>
<?php echo form_open(site_url().'/admin/gestion/guardar_bien_inmueble/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
<tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo Inmueble:<?php else:?>Editar Inmueble #<?php echo $idregistro;?> <a href="#" class="closed" style="float:right;" data-gtab="inmueble">Cerrar</a><?php endif;?></h3><br></td></tr>
<tr>
    <td>Dirección:</td>
    <td>
    <input name="direccion" style="width:338px;" value="<?php echo $direccion_in;?>">
    <?php echo form_error('direccion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Comuna:</td>
    <td>
    <?php echo form_dropdown('comuna_idcomuna', $sct_comuna, $id_comuna_in,' class="mi-selector" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('comuna_idcomuna','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Inscripción:</td>
    <td>
    <?php echo form_dropdown('inscripcion_idinscripcion', $sct_inscripcion, $inscripcion_in,' class="mi-selector" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('inscripcion_idinscripcion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Avalúo:</td>
    <td>
    <input name="avaluo" id="avaluo" value="<?php echo $avaluo_in;?>">
    <?php echo form_error('avaluo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Observación:</td>
    <td>
    <textarea name="observacion" value="<?php echo $observacion_in;?>"></textarea>
    <?php echo form_error('observacion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
</table>
<?php echo form_close();?>
<!--Lista Inmueble-->
</br></br></br>
<div id="box-form-inmueble"></div>
<?php if($nodo->nombre == 'fullpay' && count($inmueb) > 0){ ?></br>
<a id="exp_inmb" href="<?php echo site_url();?>/admin/gestion/exportador_inmuebles_fullpay?id_cuenta=<?php echo $id; ?>" class="ico-excel" style="margin-left:0;width:150px;color:#578698;">Exportar a Excel</a>
<div class="show_a" style="display:none;">
    <label>
        <input type="checkbox" id="checkAllI" name="checkAllI"/>
        <div>Resultado del filtro aplicado directamente en la tabla</div>
    </label>
</div>
<?php } ?>
<table id="tbinmueble" class="cell-border" style="width: 100%;">
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
        <tr id="row-<?php echo $inmueble->idinmueble; ?>">
        <td class="td_inm">#<?php echo $inmueble->idinmueble;?></td>
        <td><?php echo $inmueble->observacion;?></td>
        <td><?php echo $inmueble->direccion;?></td>
        <td><?php echo $inmueble->nombre;?></td>
        <td><?php echo $inmueble->nombre_inscripcion;?></td>
        <td><?php echo $inmueble->avaluo;?></td>
        <td class="td_inm">
            <a href="<?php echo site_url('admin/gestion/editar_inmueble/'.$id.'/'.$inmueble->idinmueble);?>" class="edited" data-id="<?php echo $inmueble->idinmueble;?>" data-gtab="inmueble">Editar</a>
            <a href="<?php echo site_url('admin/gestion/eliminar_inmueble/'.$id.'/'.$inmueble->idinmueble);?>" class="delete">Eliminar</a>
        </td>
        <?php $i++;?>
        <?php endforeach;?>
       </tr>
    <tbody>
</table>
</br></br></br>
<div><u><h2>Otros Bienes</h2></u></div>
</br></br>
<!--Formulario Otros Bienes -->
<?php echo form_open(site_url().'/admin/gestion/guardar_bienes/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
<tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo Bien:<?php else:?>Editar Otros Bienes #<?php echo $idregistro;?> <a href="#" class="closed" style="float:right;" data-gtab="otrosbienes">Cerrar</a><?php endif;?></h3><br></td></tr>
<tr>
    <td>Otros Bienes:</td>
    <td>
    <input type="text" name="otros" style="width:500px;" value="<?php echo $otro_b;?>">
    <?php echo form_error('otros','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Observación:</td>
    <td>
    <textarea name="observacion" value="<?php echo $observacion_b;?>"></textarea>
    <?php echo form_error('observacion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>
    <input id="id_cuenta_b" name="id_cuenta_b" type="hidden" value="<?php echo $idcuenta_b; ?>">
    </td>
</tr>
<tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
</table>
<?php echo form_close(); ?>
<!--Lista Bienes-->
<div id="box-form-otrosbienes"></div>
<?php if($nodo->nombre == 'fullpay' && count($obienes) > 0){ ?></br>
<a id="exp_otros" href="<?php echo site_url();?>/admin/gestion/exportador_obienes_fullpay?id_cuenta=<?php echo $id; ?>" class="ico-excel" style="margin-left:0;width:150px;color:#578698;">Exportar a Excel</a>
<div class="show_a" style="display:none;">
    <label>
        <input type="checkbox" id="checkAllO" name="checkAllO"/>
        <div>Resultado del filtro aplicado directamente en la tabla</div>
    </label>
</div>
<?php } ?>
<table id="tbotrosbienes" class="cell-border" style="width:100%;">
    <thead>
    <tr class="col_list">
        <th>#</th>
        <th>Observación</th>
        <th>Otros Bienes</th>
        <th width="120px">Gestión</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0; 
    if (count($obienes)>0):?>
    <?php foreach ($obienes as $otrosbienes):?>
        <tr id="row-<?php echo $otrosbienes->idotros_bienes; ?>">
        <td class="td_inm">#<?php echo $otrosbienes->idotros_bienes;?></td>
        <td><?php echo $otrosbienes->observacion;?></td>
        <td><?php echo $otrosbienes->otros;?></td>
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
<script>
    var table_v = $('#tbvehiculos').DataTable({
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

    var table_i = $('#tbinmueble').DataTable({
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

    var table_o = $('#tbotrosbienes').DataTable({
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

    $("#tbvehiculos_filter input").keyup(function(e){
        $("#checkAllV").prop({'checked': false});
        $("#checkAllV").click();
        if($(this).val() == '')
            $("#checkAllV").prop({'checked': false});
        else
            $("#checkAllV").prop({'checked': true});
    });

    $("#tbinmueble_filter input").keyup(function(e){
        $("#checkAllI").prop({'checked': false});
        $("#checkAllI").click();
        if($(this).val() == '')
            $("#checkAllI").prop({'checked': false});
        else
            $("#checkAllI").prop({'checked': true});
    });

    $("#tbotrosbienes_filter input").keyup(function(e){
        $("#checkAllO").prop({'checked': false});
        $("#checkAllO").click();
        if($(this).val() == '')
            $("#checkAllO").prop({'checked': false});
        else
            $("#checkAllO").prop({'checked': true});
    });

    $("#checkAllV").click(function(){
      if($(this).is(':checked')){
          //Limpiar siempre
          var url = $("#exp_veh").prop('href');
          var arr = url.split('&idveh=');
          $("#exp_veh").prop({'href': arr[0]});
          //Comienzo
          var arr = '';
          var trs = table_v.rows({order:'index', search:'applied'}).nodes();
          for(var i=0;i<trs.length;i++){
              var id = $(trs[i]).attr('id').substring(4);
              console.log(id);
              arr += id + ',';
          }
          if(arr == ''){
              alertify.dialog('alert').set({transitionOff: true, message: 'No hay campos para mostrar.'}).show();
              return false;
          }
          else{
              var url = $("#exp_veh").prop('href'); alert(arr);
              $("#exp_veh").prop({'href': url + '&idveh='+arr.substring(0, arr.length - 1)});
          }
      }else{
          var url = $("#exp_veh").prop('href');
          var arr = url.split('&idveh=');
          $("#exp_veh").prop({'href': arr[0]});
      }
    });

    $("#checkAllI").click(function(){
      if($(this).is(':checked')){
          //Limpio siempre
          var url = $("#exp_inmb").prop('href');
          var arr = url.split('&idinmueble=');
          $("#exp_inmb").prop({'href': arr[0]});
          //Comienzo
          var arr = '';
          var trs = table_i.rows({order:'index', search:'applied'}).nodes();
          for(var i=0;i<trs.length;i++){
              var id = $(trs[i]).attr('id').substring(4);
              arr += id + ',';
          }
          if(arr == ''){
              alertify.dialog('alert').set({transitionOff: true, message: 'No hay campos para mostrar.'}).show();
              return false;
          }
          else{
              var url = $("#exp_inmb").prop('href');
              $("#exp_inmb").prop({'href': url + '&idinmueble='+arr.substring(0, arr.length - 1)});
          }
      }else{
          var url = $("#exp_inmb").prop('href');
          var arr = url.split('&idinmueble=');
          $("#exp_inmb").prop({'href': arr[0]});
      }
    });

    $("#checkAllO").click(function(){
      if($(this).is(':checked')){
          //Limpiar siempre
          var url = $("#exp_otros").prop('href');
          var arr = url.split('&idotrosb=');
          $("#exp_otros").prop({'href': arr[0]});
          //Comienzo
          var arr = '';
          var trs = table_o.rows({order:'index', search:'applied'}).nodes();
          for(var i=0;i<trs.length;i++){
              var id = $(trs[i]).attr('id').substring(4);
              arr += id + ',';
          }
          if(arr == ''){
              alertify.dialog('alert').set({transitionOff: true, message: 'No hay campos para mostrar.'}).show();
              return false;
          }
          else{
              var url = $("#exp_otros").prop('href');
              $("#exp_otros").prop({'href': url + '&idotrosb='+arr.substring(0, arr.length - 1)});
          }
      }else{
          var url = $("#exp_otros").prop('href');
          var arr = url.split('&idotrosb=');
          $("#exp_otros").prop({'href': arr[0]});
      }
    });

    $(document).on("click",".closed", function(e){
        var gtab = $(this).data('gtab');
        $("#box-form-"+gtab).html('');
        $("#tbvehiculos tbody tr").each(function(index){
            $(this).removeClass('current');
        });
        $("#tbinmueble tbody tr").each(function(index){
            $(this).removeClass('current');
        });
        $("#tbotrosbienes tbody tr").each(function(index){
            $(this).removeClass('current');
        });
        return false;
    });

    $(document).on("click",".edited", function(e){
        tr = $(this).parent('td').parent('tr');
        $("#tbvehiculos tbody tr").each(function(index){
            $(this).removeClass('current');
        });
        $("#tbinmueble tbody tr").each(function(index){
            $(this).removeClass('current');
        });
        $("#tbotrosbienes tbody tr").each(function(index){
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
    
    $('#avaluo').keyup(function(event) {
        $(this).val(function(index, value) {
            return value
            .replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
});
</script>