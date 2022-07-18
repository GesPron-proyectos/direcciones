<?php $this->load->view('backend/templates/gestion/gestion/cartas_enviadas_form');?>
</br>
<div style="padding-left: 7%;">_____________________________________________________________________________________________________________________________________________________________</div>
</br></br>
<div style="padding-left: 40%;"><u><h2>Listado Cartas Enviadas</h2></u></span></div>
<div style="padding-left: 7%;">_____________________________________________________________________________________________________________________________________________________________</div>
</br></br>
<div class="box-form-cartas_enviadas"></div>
<?php echo form_open(site_url().'/admin/gestion/actualizar_cartas/', array('id' => 'form_act_cartas')); ?>
<div>
    <h3>Filtrar por:</h3><br/>
    <div class="">
      <label for="filt_inst">Instituciones:</label>
      <?php echo form_dropdown('filt_inst', $sct_institucion, 0, 'id="filt_inst" class="id_inst"'); ?>
    </div><br/>
    <div class="">
      <label for="filt_estado" style="width:90px;float:left;">Estado:</label>
      <select id="filt_estado" class="estado">
        <option value="" selected="selected">Seleccionar</option>
        <option value="0">Sin Respuesta</option>
        <option value="1">Respuesta Positiva</option>
        <option value="2">Respuesta Negativa</option>
      </select>
    </div><br/>
    <div class="">
      <label for="filt_fecha" style="width:90px;float:left;">Fecha envio:</label>
      <input type="text" id="filt_fecha" autocomplete="off">
    </div>
    <div>____________________________________________________</div><br/>
    <div>
        <h3>Asignar respuesta:</h3><br/>
        <div class="">
          <input type="text" autocomplete="off" id="f_envio" name="f_envio">
          <select id="change_estado" name="change_estado" class="estado">
            <option value="" selected="selected">Seleccionar</option>
            <option value="1">Respuesta Positiva</option>
            <option value="2">Respuesta Negativa</option>
          </select>
          <input type="button" name="btn_agregar_resp" id="btn_agregar_resp" style="cursor:pointer;" value="Enviar">
        </div><br/>
    </div>
<input type="hidden" id="id_cuenta" name="id_cuenta" value="<?php echo $id; ?>" />
<hr style="float:left;width:100%;margin-bottom:10px;border-bottom:1px solid #ccc;border-top:none;" />
</div>
<?php if($nodo->nombre == 'fullpay' && count($cartas_enviadas) > 0){ ?></br>
<a id="exp_cartas" href="<?php echo site_url();?>/admin/gestion/exportador_cartas_enviadas_fullpay?id_cuenta=<?php echo $id; ?>" class="ico-excel" style="margin-left:0;width:150px;color:#578698;">Exportar a Excel</a>
<div class="show_a" style="display:none;">
    <label>
        <input type="checkbox" id="checkAllC" name="checkAllC"/>
        <div>Resultado del filtro aplicado directamente en la tabla</div>
    </label>
</div>
<?php } ?>
<table id="tbenviadas" class="cell-border" data-page-length='25'>
  <thead>
      <tr class="col_list">
        <th width="10px"><input type="checkbox" id="checkAll" name="checkAll" style="width:15px"/></th>
        <th>Código</th>
        <th>Instituciones Prioritarias</th>
        <th>Respuesta</th>
        <th>Fecha Envío</th>
        <th style="width: 44px;">Gestión</th>
      </tr>
  </thead>
  <tbody>
  <?php
    $respuesta = array('Sin Respuesta', 'Respuesta Positiva', 'Respuesta Negativa');
    foreach ($cartas_enviadas as $key=>$cenviadas):?>
    <?php if($cenviadas->fecha_envio != '0000-00-00'): ?>
      <tr id="row-<?php echo $cenviadas->idcartas_enviadas; ?>" class="tr_inst">
        <td class="num_list">
        <?php if($cenviadas->estado == 0){ ?>
          <input type="checkbox" name="cartas[]" value="<?php echo $cenviadas->idcartas_enviadas;?>" onclick="verCheck()" style="width:15px"/>
          <?php } ?>
        </td>
        <td class="num_list"><?php echo $cenviadas->codigo;?></td>
        <td class="acreed_list"><?php echo $cenviadas->razon_social;?></td>
        <td class="acreed_list">
        <?php 
          echo $respuesta[$cenviadas->estado]; 
          /*if ($cenviadas->estado == 0)
            echo "<div></div>";
          elseif($cenviadas->estado == 1)
            echo "<div class='resp_positiva';></div>";
          else
            echo "<div class='resp_negativa';></div>"; */
        ?>    
        </td>
        <td class="fechap_list"><?php echo date('d-m-Y', strtotime($cenviadas->fecha_envio));?></td>
        <td>
          <a href="<?php echo site_url('admin/gestion/eliminar_carta/'.$id.'/'.$cenviadas->idcartas_enviadas);?>" style="padding-left: inherit;" class="delete">
            Eliminar
          </a>
        </td>
      </tr>
  <?php endif; endforeach; ?>
  </tbody>
</table>
<?php echo form_close();?>
<script type="text/javascript">
  function verCheck(){
    var tds = 'ok';
    $("#tbenviadas input[type=checkbox]").each(function(){
      if(!$(this).is(':checked')){
        tds='nok';
      }
    });
    //alert(tds);
    if(tds == 'ok'){
      $("#checkAll").prop('checked', true);
    }else{
      $("#checkAll").prop('checked', false);
    }
  }

$(document).ready(function(){
  var table = $('#tbenviadas').DataTable({
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

  $("#tbenviadas_filter input").keyup(function(e){
      $("#checkAllC").prop({'checked': false});
      $("#checkAllC").click();
      if($(this).val() == '')
          $("#checkAllC").prop({'checked': false});
      else
          $("#checkAllC").prop({'checked': true});
  });
  
  $("#checkAllC").click(function(){
      if($(this).is(':checked')){
          //Limpio siempre
          var url = $("#exp_cartas").prop('href');
          var arr = url.split('&idcartas=');
          $("#exp_cartas").prop({'href': arr[0]});
          //Comienzo
          var arr = '';
          var trs = table.rows({order:'index', search:'applied'}).nodes();
          for(var i=0;i<trs.length;i++){
              var id = $(trs[i]).attr('id').substring(4);
              arr += id + ',';
          }
          if(arr == ''){
              alertify.dialog('alert').set({transitionOff: true, message: 'No hay campos para mostrar.'}).show();
              return false;
          }
          else{
              var url = $("#exp_cartas").prop('href');
              $("#exp_cartas").prop({'href': url + '&idcartas='+arr.substring(0, arr.length - 1)});
          }
      }else{
          var url = $("#exp_cartas").prop('href');
          var arr = url.split('&idcartas=');
          $("#exp_cartas").prop({'href': arr[0]});
      }
  });

  var respuestas = Array('Sin Respuesta', 'Respuesta Positiva', 'Respuesta Negativa');
  $("#filt_inst").change(function(){
      var id_inst = $(this).val();
      var id_cuenta = $("#id_cuenta").val();
      var estado = $("#filt_estado").val();
      var fecha = $("#filt_fecha").val();
      var actionData = {'id_inst': id_inst, 'id_cuenta': id_cuenta, 'estado': estado, 'fecha': fecha}
      $.ajax({
        type: "POST",
        dataType: 'json',
        data: actionData,
        url: "<?php echo base_url()?>index.php/admin/gestion/buscar_cartas",
        success: function(response){
          i=1;
          $('#tbenviadas tbody').empty();
          $.each(response.result, function(id, value){
              //console.log(value.id);
              var cl = ' class="a">';
              if (i%2==0) cl = ' class="b">';
              if(value.estado == 0){
                var tr = '<tr id="row-'+value.id+'"'+cl+
                         '<td style="text-align:center;"><input type="checkbox" name="cartas[]" value="'+ value.idcartas_enviadas+
                         '" onclick="verCheck()" style="width:15px"/></td>' +
                         '<td style="text-align:center;">' + value.codigo + '</td>' +
                         '<td>' + value.razon_social + '</td>' +
                         '<td>' + respuestas[value.estado] + '</td>' +
                         '<td style="text-align:center;">' + value.fecha_envio + '</td></tr>';
              }
              else{
                var tr = '<tr id="row-'+value.id+'"'+cl+
                         '<td style="text-align:center;"></td>' +
                         '<td style="text-align:center;">' + value.codigo + '</td>' +
                         '<td>' + value.razon_social + '</td>' +
                         '<td>' + respuestas[value.estado] + '</td>' +
                         '<td style="text-align:center;">' + value.fecha_envio + '</td></tr>';
              }
              $("#tbenviadas tbody").append(tr);
              i++;
          });
        }
  
      });
  });

  $("#filt_estado").change(function(){
      var estado = $(this).val();
      var id_cuenta = $("#id_cuenta").val();
      var id_inst = $("#filt_inst").val();
      var fecha = $("#filt_fecha").val();
      var actionData = {'id_inst': id_inst, 'id_cuenta': id_cuenta, 'estado': estado, 'fecha': fecha}
      $.ajax({
        type: "POST",
        dataType: 'json',
        data: actionData,
        url: "<?php echo base_url()?>index.php/admin/gestion/buscar_cartas",
        success: function(response){
          i=1;
          $('#tbenviadas tbody').empty();
          $.each(response.result, function(id, value){
              //console.log(value.id);
              var cl = ' class="a">';
              if (i%2==0) cl = ' class="b">';
              if(value.estado == 0){
                var tr = '<tr id="row-'+value.id+'"'+cl+
                         '<td style="text-align:center;"><input type="checkbox" name="cartas[]" value="'+ value.idcartas_enviadas+
                         '" onclick="verCheck()" style="width:15px"/></td>' +
                         '<td style="text-align:center;">' + value.codigo + '</td>' +
                         '<td>' + value.razon_social + '</td>' +
                         '<td>' + respuestas[value.estado] + '</td>' +
                         '<td style="text-align:center;">' + value.fecha_envio + '</td></tr>';
              }
              else{
                var tr = '<tr id="row-'+value.id+'"'+cl+
                         '<td style="text-align:center;"></td>' +
                         '<td style="text-align:center;">' + value.codigo + '</td>' +
                         '<td>' + value.razon_social + '</td>' +
                         '<td>' + respuestas[value.estado] + '</td>' +
                         '<td style="text-align:center;">' + value.fecha_envio + '</td></tr>';
              }
              $("#tbenviadas tbody").append(tr);
              i++;
          });
        }
  
      });
  });

  $("#filt_fecha").datepicker({
      dateFormat:'dd-mm-yy',
      maxDate: "+0m +0d",
      onSelect: function(dateStr){
        var fecha = $(this).val();
        var id_cuenta = $("#id_cuenta").val();
        var estado = $("#filt_estado").val();
        var filt_inst = $("#filt_inst").val();
        var actionData = {'filt_inst': filt_inst, 'id_cuenta': id_cuenta, 'estado': estado, 'fecha': fecha}
        $.ajax({
          type: "POST",
          dataType: 'json',
          data: actionData,
          url: "<?php echo base_url()?>index.php/admin/gestion/buscar_cartas",
          success: function(response){
            i=1;
            $('#tbenviadas tbody').empty();
            $.each(response.result, function(id, value){
                //console.log(value.id);
                var cl = ' class="a">';
                if (i%2==0) cl = ' class="b">';
                if(value.estado == 0){
                var tr = '<tr id="row-'+value.id+'"'+cl+
                         '<td style="text-align:center;"><input type="checkbox" name="cartas[]" value="'+ value.idcartas_enviadas+
                         '" onclick="verCheck()" style="width:15px"/></td>' +
                         '<td style="text-align:center;">' + value.codigo + '</td>' +
                         '<td>' + value.razon_social + '</td>' +
                         '<td>' + respuestas[value.estado] + '</td>' +
                         '<td style="text-align:center;">' + value.fecha_envio + '</td></tr>';
                }
                else{
                  var tr = '<tr id="row-'+value.id+'"'+cl+
                           '<td style="text-align:center;"></td>' +
                           '<td style="text-align:center;">' + value.codigo + '</td>' +
                           '<td>' + value.razon_social + '</td>' +
                           '<td>' + respuestas[value.estado] + '</td>' +
                           '<td style="text-align:center;">' + value.fecha_envio + '</td></tr>';
                }
                $("#tbenviadas tbody").append(tr);
                i++;
            });
          }
    
        });
      }
    });

    $("#checkAll").click(function(){
      if($(this).is(':checked')){
        $("#tbenviadas input[type=checkbox]").prop('checked', true);
      }else{
        $("#tbenviadas input[type=checkbox]").prop('checked', false);
      }
    });

    $("#f_envio").datepicker({
        dateFormat:'dd-mm-yy',
        maxDate: "+0m +0d"
    });

    $("#btn_agregar_resp").click(function(){
      var find = false;
      var fecha = $('#f_envio').val();
      var estado = $('#change_estado').val();

      $("#tbenviadas input[type=checkbox]").each(function() {
        if($(this).is(':checked')){
          find = true;
        }
      });

      if (find == false){
        alertify.dialog('alert').set({transitionOff: true, message: 'Debe seleccionar al menos una carta.'}).show();
        e.stopPropagation();
        return false;
      }
      else if(estado == ''){
        alertify.dialog('alert').set({transitionOff:true,message: 'Debe escoger una respuesta para asignar.'}).show();
        return false;
      }
      else if(fecha == ''){
        alertify.dialog('alert').set({transitionOff:true,message: 'Debe escoger una fecha de envio.'}).show();
        return false;
      }       
      else
        $("#form_act_cartas").submit();
    });
});
</script>