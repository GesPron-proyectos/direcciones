<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
<script src="<?php echo base_url()?>js/jquery.modal.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>css/jquery.modal.min.css">
<script src="<?php echo base_url()?>js/alertify.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>css/alertify.min.css">
<div class="table-m-sep">
  <div class="table-m-sep-title"> 
    <div class="table-m-sep-tools">
      <span class="editar">Editar</span>
      <span class="subir">Subir</span>
      <span class="bajar">Bajar</span>
      <span class="publicado">Publicado</span>
      <span class="despublicado">Despublicado</span>
      <span class="eliminar">Eliminar</span>
    </div>
  </div>
</div>
<form action="<?php echo site_url().'/admin/doc/cargar/';?>" method="post">
    <div class="titulo">
        <strong style="float:left; margin-right:10px;">Buscar por Rut</strong> 
        <?php if (validation_errors()!='' && (isset($_POST['enviar']) && $_POST['enviar']!='')): ?>
        <span class="alerta"></span><span class="error">Faltan campos por completar</span> 
        <?php endif;?>
        <span class="flechita"></span>
        <div class="clear"></div>
    </div>

    <div class="blq"><br/>
    <div class="cont-form">
        <label style="width:40px!important; float:left">Rut*:</label>
        <input id="rut" name="rut" type="text" value="<?php echo $rut;?>" style="width:150px;float: left;">
        <div class="campo" style="width:150px;float: left;margin-left: 10px;">
            <input id="btn-buscar" type="button" name="buscar" value="Buscar" class="boton">
        </div>
        <div class="clear"></div>
        <span class="error" id="error_rut"></span>
    </div>
    <div class="clear"></div>

    <div id="modalUser" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="tabla-listado">
          <div class="content_tabla">
            <table id="tpagare" class="listado" width="100%">
              <thead>
                <tr class="menu" style="line-height:20px; height:50px;">
                  <td width="8%" class="nombre">Nº Operación</td>
                  <td width="12%" class="nombre">Rol / Tribunal</td>   
                  <td width="8%" class="nombre">Mandante</td>
                  <td width="9%" class="nombre">Deudor RUT</td>
                  <td width="9%" class="nombre">Nombre Deudor</td>
                  <td width="10%" class="nombre">Procurador</td>
                  <td width="12%" class="nombre">Estado Cuenta</td>
                  <?php if ($nodo->nombre=='fullpay'):?>
                  <td width="12%" class="nombre">Comuna</td>
                  <td width="9%" class="nombre">Fecha asignación</td>
                  <td width="9%" class="nombre">Días transcurridos</td>
                  <?php endif;?>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="titulo">
    <strong style="float:left; margin-right:10px;">Datos de documento</strong>
    <span class="flechita"></span>
    <div class="clear"></div>
</div>

<div class="blq">
    <div class="dos" style="float: left;width:45%;">
      <div class="cont-form">
            <label for="nombres" style="width:135px;">Nombres y apellidos:</label>
            <input id="nombres" name="nombres" type="text" style="width:350px;" readonly="readonly">
      </div>
      <div class="clear"></div>
      <div class="cont-form">
          <label for="rut_user" style="width:135px;">Rut:</label>
          <input id="rut_user" name="rut_user" type="text" style="width:150px;" readonly="readonly">
      </div>
      <div class="clear"></div>
    	<div class="cont-form">
          <label for="nrooper" style="width:135px;">Nro operación:</label>
          <input id="operacion" name="operacion" type="text" style="width:150px;" readonly="readonly">
    	</div>
      <div class="clear"></div>
      <div class="cont-form">
          <label for="exhorto" style="width:135px;">Exhorto:</label>
          <select id="exhorto" name="exhorto" style="width:162px;">
              <option value="-">Seleccionar</option>
              <option value="1">Con Exhorto</option>
              <option value="0">Sin Exhorto</option>
          </select>
      </div>
      <div class="clear"></div> 
    </div>
    <div style="float: left;width:25%;">
        <div style="margin-top:10px;"></div>
        <div class="cont-form">
            <label for="pagare1" style="width:135px;">Monto pagare 1:</label>
            <input id="pagare1" name="pagare1" type="text" style="width:150px;">
        </div>
        <div class="clear"></div>
        <div class="cont-form">
            <label for="pagare2" style="width:135px;">Monto pagare 2:</label>
            <input id="pagare2" name="pagare2" type="text" style="width:150px;">
        </div>
        <div class="clear"></div>
        <div class="cont-form">
            <label for="pagare3" style="width:135px;">Monto pagare 3:</label>
            <input id="pagare3" name="pagare3" type="text" style="width:150px;">
        </div>
        <div class="clear"></div>
        <div class="cont-form">
            <label for="pagare4" style="width:135px;">Monto pagare 4:</label>
            <input id="pagare4" name="pagare4" type="text" style="width:150px;">
        </div>
        <div class="clear"></div>
    </div>
    <div class="dos" style="float:left;width:30%;">
        <div class="cont-form">
            <label for="totalp" style="width:135px;">Total de pesos:</label>
            <input id="totalp" name="totalp" type="text" style="width:150px;">
        </div>
        <div class="clear"></div>
        <div class="cont-form">
            <label for="totaluf" style="width:135px;">Total UF:</label>
            <input id="totaluf" name="totaluf" type="text" style="width:150px;">
        </div>
        <div class="clear"></div>
        <div class="cont-form">
            <label for="valoruf" style="width:140px;margin:-6px 0 6px;">Valor UF fecha vcto/present:</label>
            <input id="valoruf" name="valoruf" type="text" style="width:150px;">
        </div>
        <div class="cont-form" style="margin-top:-10px;">
          <label for="propia" style="width:135px;">Tipo Demanda:</label>
          <select id="propia" name="propia" style="width:162px;">
              <option value="-">Seleccionar</option>
              <option value="1">Propia</option>
              <option value="0">Cedida</option>
          </select>
        </div>
        <div class="clear"></div> 
    </div>
    <div class="clear"></div>
    <br/><br/>
    <div style="float:left;width:25%;">
      <div class="cont-form" style="margin-top:0px;">
          <label for="jurisdiccion" style="width:135px;">Jurisdicción:</label>
          <select id="jurisdiccion" name="jurisdiccion" style="width:162px;">
              <option value="">Seleccionar</option>
              <?php foreach($jurisdiccion as $key=>$val):?>
                <option value="<?php echo $val->id?>" <?php if ($val->id==$this->input->post('jurisdiccion')) {echo 'selected';}?>><?php echo $val->jurisdiccion?></option>
              <?php endforeach;?>
          </select>
        </div>
        <div class="clear"></div>
    </div>
    <div style="float:left;width:25%;">
      <div class="cont-form">
        <label style="width:121px!important; float:left">Fecha suscripción:</label>
        <input type="text" name="fsuscrip" id="fsuscrip" data-date-format="dd-mm-yyyy"/>
        <div class="clear"></div>
      </div>
    </div>
    <div style="float:left;width:25%;">
      <div class="cont-form">
        <label style="width:129px!important; float:left">Fecha vencimiento:</label>
        <input type="text" name="fvenc" id="fvenc" data-date-format="dd-mm-yyyy" readonly="readonly"/>
        <div class="clear"></div>
      </div>
    </div>
    <div style="float:left;width:25%;">
      <div class="cont-form">
        <label style="width:132px!important; float:left">Fecha presentación:</label>
        <input type="text" name="fpres" id="fpres" data-date-format="dd-mm-yyyy"/>
        <div class="clear"></div>
      </div>
    </div>
    <div style="float:left;width:100%;">
      <div class="cont-form" style="width:99%;">
          <label for="domicilio" style="width:100%;">Domicilio pagare:</label>
          <textarea id="domicilio" name="domicilio" style="width:100%;height:100px;resize:none;margin-top:3px;"></textarea>
      </div>
    </div>
    <div class="clear"></div>
    <input type="button" id="btn-crear" value="Crear" name="enviar" class="boton" style="width:100%; float:left;"/>
    <br/><br/>
</div>

<div class="titulo">
    <strong style="float:left; margin-right:10px;">Registros sin Guardar</strong>
    <span class="flechita"></span>
    <div class="clear"></div>
</div>

<div class="blq"><br/>
<div class="cont-form">
    <div class="tabla-listado">
      <div class="content_tabla">
        <table id="tdpag" class="listado" width="100%">
          <thead>
            <tr class="menu" style="line-height:20px; height:50px;">
              <td width="14%" class="nombre">Demandado</td>
              <td width="8%" class="nombre">Rut</td>   
              <td width="8%" class="nombre">Total pesos</td>
              <td width="9%" class="nombre" style="display:none;">Con/Sin Exh</td>
              <td width="9%" class="nombre" style="display:none;">Juridisccion</td>
              <td width="10%" class="nombre">Domicilio pagare</td>
              <td width="8%" class="nombre">Pagare 1</td>
              <td width="8%" class="nombre">Pagare 2</td>
              <td width="8%" class="nombre">Pagare 3</td>
              <td width="8%" class="nombre">Pagare 4</td>
              <td width="8%" class="nombre">Total UF</td>
              <td width="9%" class="nombre" style="display:none;">Fecha Suscripcion</td>
              <td width="9%" class="nombre" style="display:none;">Fecha Vcto</td>
              <td width="9%" class="nombre" style="display:none;">Propia o Cedida</td>
              <td width="9%" class="nombre">Valor UF fecha vcto/present</td>
              <td width="9%" class="nombre">Fecha Creacion</td>
              <td width="9%" class="nombre" style="display:none;">Operacion</td>
              <td width="9%" class="nombre" style="display:none;">Id cuenta</td>
              <td width="9%" class="nombre" style="display:none;">Fecha presentacion</td>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
    <div class="clear"></div><br/>
    <input type="button" id="btn-save" value="Guardar" name="enviar" class="boton" style="width:100%; float:left;"/>
</div>
<div class="clear"></div>

<input type="hidden" id="id" name="id">
<input type="hidden" id="id_cuenta" name="id_cuenta">
</form>

<script type="text/javascript">

$(document).ready(function(){

    function validaFechas(date){
      var dtRegex = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
      if(dtRegex.test(date) == true || date == ''){
        if (date == '') return true;
        var evalDate = date.split('/');
        if(evalDate[0] != '00' && evalDate[1] != '00' && evalDate[2] != '0000'){
          return true;
        }
        else
          return false;
      }
      else
        return false;
    }

    $('#fsuscrip').datepicker({
        maxDate: "+0m +0d",
        onSelect: function(dateStr){         
            $("#fvenc").datepicker("destroy");
            $("#fvenc").val(dateStr);
            $("#fvenc").datepicker({ minDate: new Date(dateStr)});
        }
    });
    //$('#fvenc').datepicker();
    $('#fpres').datepicker();
    
    $('#fsuscrip').keyup(function(e){
        if($('#fsuscrip').val() == ''){
            $("#fvenc").datepicker("destroy");
            $("#fvenc").val('');
        }
    });

    cargar_datos_aux();

    $('#btn-buscar').click(function(){
      if($('#rut').val().trim() == ''){
        alertify.dialog('alert').set({transitionOff:true,message: 'Debe introducir un numero Rut para realizar la busqueda.'}).show();
        return false;
      }
      $("#tpagare tbody").empty();
        actionData = {'rut': $('#rut').val()}
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: actionData,
            url: "<?php echo base_url()?>index.php/admin/doc/cargar_by_rut",
            success: function(response){
                //var d = response.result;
                i=1;
                $.each(response.result, function(id, value){
                    //console.log(value.id);
                    var cl = ' class="a">';
                    if (i%2==0) cl = ' class="b">';
                    var tr = '<tr id="row-'+value.id+'"'+cl+
                             '<td style="float: left; width: 50px ! important;">'+
                             value.operacion + '</td>' +
                             '<td align="center">' + 
                             value.rol_trib + '</td>' +
                             '<td>' + value.codigo_mandante + '</td>' +
                             '<td>' + value.rut + '</td>' +
                             '<td>' + value.nombres + '</td>' +
                             '<td>' + value.procurador + '</td>' +
                             '<td>' + value.estado + '</td>' +
                             '<td>' + value.comuna + '</td>' +
                             '<td>' + value.fecha + '</td>' +
                             '<td>' + value.diferencia + '</td></tr>';
                    $("#tpagare tbody").append(tr);
                    i++;
                });

                $('#modalUser').modal('modal');
                $('#tpagare tbody tr').css({'cursor': 'pointer'});

                // Funcionamiento para escoger el registro
                $('#tpagare tbody tr').click(function(){
                    $('#tpagare tbody tr').each(function(id){
                        if (id % 2 == 0) 
                            $(this).css('background-color','#FFF');
                        else
                            $(this).css('background-color','#edebeb');
                    });
                    $(this).css('background-color', '#ccc');
                    $('#id_cuenta').val($(this).attr('id').replace('row-', ''));
                    $('#nombres').val($(this).find('td:eq(4)').text().trim());
                    $('#rut_user').val($(this).find('td:eq(3)').text().trim());
                    $('#operacion').val($(this).find('td:eq(0)').text().trim());

                    actionData = {'id_cuenta': $('#id_cuenta').val(), 'operacion': $('#operacion').val()}
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: actionData,
                        url: "<?php echo base_url()?>index.php/admin/doc/verif",
                        success: function(response){
                            var d = response.result;
                            $('#id').val(d.id);
                            $('#exhorto').val(d.exhorto?d.exhorto:'-');
                            $('#pagare1').val(d.pagare1);
                            $('#pagare2').val(d.pagare2);
                            $('#pagare3').val(d.pagare3);
                            $('#pagare4').val(d.pagare4);
                            $('#totalp').val(d.totalp);
                            $('#totaluf').val(d.totaluf);
                            $('#valoruf').val(d.valoruf);
                            $('#propia').val(d.propia?d.propia:'-');
                            $('#jurisdiccion').val(d.jurisdiccion);
                            $('#fsuscrip').val(d.fsuscrip);
                            $('#fvenc').val(d.fvenc);
                            $('#fpres').val(d.fpres);
                            $('#domicilio').val(d.domicilio);
                            $('#operacion').val(d.operacion);
                            $('#rut').val('');
                            $('#modalUser').find('a.close-modal').click();
                        }
                    });

                });
            }
        });
        //$('#modalUser').modal('modal');
    });

    $('#btn-save').click(function(e){
        e.preventDefault();
        var actionData = {}
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: actionData,
            url: "<?php echo base_url()?>index.php/admin/doc/save_datos_plantilla",
            success: function(response){
                var d = response.result;
                if (d.success == '1'){
                    $('#tdpag tbody').empty();
                    alertify.dialog('alert').set({transitionOff:true,message: 'Accion realizada con exito.'}).show();
                    cargar_datos_aux();
                }
            }
        });
    });

    $('#btn-crear').click(function(e){
        e.preventDefault();
        id = $('#id').val();
        idcuent = $('#id_cuenta').val();
        exhorto = $('#exhorto').val();
        pagare1 = $('#pagare1').val();
        pagare2 = $('#pagare2').val();
        pagare3 = $('#pagare3').val();
        pagare4 = $('#pagare4').val();
        total_p = $('#totalp').val();
        totaluf = $('#totaluf').val();
        valoruf = $('#valoruf').val();
        tipo_dm = $('#propia').val();
        jurisdc = $('#jurisdiccion').val();
        fechasc = $('#fsuscrip').val();
        fechavc = $('#fvenc').val();
        fechapr = $('#fpres').val();
        domicpg = $('#domicilio').val();
        operacn = $('#operacion').val();

        //Validar
        var nomb = $('#nombres').val().trim();
        var urut = $('#rut_user').val().trim();
        var oper = $('#operacion').val().trim();
        if(nomb == '' || urut == '' | oper == ''){
          alertify.dialog('alert').set({transitionOff:true,message: 'Debe buscar registro por Rut, para llenar los datos de la planilla.'}).show();
          return false;
        }

        if (validaFechas(fechasc) == false || validaFechas(fechavc) == false || validaFechas(fechapr) == false){
          alertify.dialog('alert').set({transitionOff:true,message: 'Verifique el formato de fecha, no es el correcto.'}).show();
          return false;
        }

        var actionData = { 'id_cuenta': idcuent, 'exhorto': exhorto, 'pagare1': pagare1, 'pagare2': pagare2, 'pagare3': pagare3,
                           'pagare4': pagare4, 'total_p': total_p, 'totaluf': totaluf, 'valoruf': valoruf, 'tipo_dm': tipo_dm, 
                           'jurisdc': jurisdc, 'fechasc': fechasc, 'fechavc': fechavc, 'domicpg': domicpg, 'operacion': operacn,
                           'id': id, 'fechapr': fechapr };
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: actionData,
            url: "<?php echo base_url()?>index.php/admin/doc/crear_datos_plantilla",
            success: function(response){
                var d = response.result;
                if (d.success == '1'){
                    $('#id_cuenta').val('');
                    $('#nombres').val('');
                    $('#rut_user').val('');
                    $('#exhorto').val('-');
                    $('#pagare1').val('');
                    $('#pagare2').val('');
                    $('#pagare3').val('');
                    $('#pagare4').val('');
                    $('#totalp').val('');
                    $('#totaluf').val('');
                    $('#valoruf').val('');
                    $('#propia').val('-');
                    $('#jurisdiccion').val('');
                    $('#fsuscrip').val('');
                    $('#fvenc').val('');
                    $('#fpres').val('');
                    $('#domicilio').val('');
                    $('#operacion').val('');
                    $('#tpagare tbody').empty();
                    alertify.dialog('alert').set({transitionOff:true,message: 'Accion realizada con exito.'}).show();
                    cargar_datos_aux();
                }
            }
        });
    });

    function cargar_datos_aux(){
      var actionData = {}

      $.ajax({
        type: "POST",
        dataType: 'json',
        data: actionData,
        url: "<?php echo base_url()?>index.php/admin/doc/cargar",
        success: function(response){
          i=1;
          $('#tdpag tbody').empty();
          $.each(response.result, function(id, value){
              //console.log(value.id);
              var cl = ' class="a">';
              if (i%2==0) cl = ' class="b">';
              var tr = '<tr id="row-'+value.id+'"'+cl+
                       '<td>'+ value.nombres + '</td>' +
                       '<td>' + value.rut + '</td>' +
                       '<td>' + value.total_pesos + '</td>' +
                       '<td style="display:none;">' + value.exhorto + '</td>' +
                       '<td style="display:none;">' + value.id_juridisccion + '</td>' +
                       '<td>' + value.domicilio_pagare + '</td>' +
                       '<td>' + value.pagare1 + '</td>' +
                       '<td>' + value.pagare2 + '</td>' +
                       '<td>' + value.pagare3 + '</td>' +
                       '<td>' + value.pagare4 + '</td>' +
                       '<td>' + value.total_uf + '</td>' +
                       '<td style="display:none;">' + value.fecha_suscripcion + '</td>' +
                       '<td style="display:none;">' + value.fecha_vencimiento + '</td>' +
                       '<td style="display:none;">' + value.tipo_demanda + '</td>' +
                       '<td>' + value.valor_uf_fecha_vencimiento + '</td>' +
                       '<td style="text-align:center;">' + value.fecha_crea + '</td>' +
                       '<td style="display:none;">' + value.operacion + '</td>' +
                       '<td style="display:none;">' + value.id_cuenta + '</td>' +
                       '<td style="display:none;">' + value.fecha_presentacion + '</td></tr>';
              $("#tdpag tbody").append(tr);
              i++;
          });

          $('#tdpag tbody tr').css({'cursor': 'pointer'});

          // Funcionamiento para escoger el registro
          $('#tdpag tbody tr').click(function(){
              $('#tdpag tbody tr').each(function(id){
                  if (id % 2 == 0) 
                      $(this).css('background-color','#FFF');
                  else
                      $(this).css('background-color','#edebeb');
              });

              $(this).css('background-color', '#ccc');
              $('#rut').val('');
              $('#id').val($(this).attr('id').replace('row-', ''));
              $('#nombres').val($(this).find('td:eq(0)').text().trim());
              $('#rut_user').val($(this).find('td:eq(1)').text().trim());
              $('#operacion').val($(this).find('td:eq(16)').text().trim());
              $('#exhorto').val($(this).find('td:eq(3)').text().trim());
              $('#pagare1').val($(this).find('td:eq(6)').text().trim());
              $('#pagare2').val($(this).find('td:eq(7)').text().trim());
              $('#pagare3').val($(this).find('td:eq(8)').text().trim());
              $('#pagare4').val($(this).find('td:eq(9)').text().trim());
              $('#totalp').val($(this).find('td:eq(2)').text().trim());
              $('#totaluf').val($(this).find('td:eq(10)').text().trim());
              $('#valoruf').val($(this).find('td:eq(14)').text().trim());
              $('#propia').val($(this).find('td:eq(13)').text().trim());
              $('#jurisdiccion').val($(this).find('td:eq(4)').text().trim());
              $('#fsuscrip').val($(this).find('td:eq(11)').text().trim());
              $('#fvenc').val($(this).find('td:eq(12)').text().trim());
              $('#domicilio').val($(this).find('td:eq(5)').text().trim());
              $('#id_cuenta').val($(this).find('td:eq(17)').text().trim());
              $('#fpres').val($(this).find('td:eq(18)').text().trim());
          });
        }
      });
    }
});
</script>