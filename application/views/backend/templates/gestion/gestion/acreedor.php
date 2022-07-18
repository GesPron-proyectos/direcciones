<?php $this->load->view('backend/templates/gestion/gestion/acreedor_form');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/estilos_css.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datatables.min.css">
<script src="<?php echo base_url();?>js/datatables.min.js"></script>
<script src="<?php echo base_url();?>js/datatables.js"></script>
<div>
    <br/><br/>
    <div class="">
        <label for="tipo_verificacion" style="width:90px;float:left;">Verificación:</label>
        <select name="tipo_verificacion" id="tipo_verificacion">
            <option value="0">Seleccionar</option>
            <option value="1">Periodo Ordinario</option>
            <option value="2">Periodo Extraordinario</option>
        </select>
    </div>
    <hr style="float:left;width:100%;margin-bottom:10px;border-bottom:1px solid #ccc;border-top:none;" />
</div>
<!--DataTable Registro y Gestión Acreedor-->
<input type="hidden" id="s_url" value="<?php echo site_url('admin/gestion/index/'.$id.'/') ?>">
<input type="hidden" id="tab_etapa" value="<?php echo $tab_etapa; ?>">
<input type="hidden" id="s_elim" value="<?php echo site_url('admin/gestion/eliminar_acreedor/'.$id.'/') ?>">
<input type="hidden" id="id_cuenta" name="id_cuenta" value="<?php echo $id; ?>" />
<div class="box-form-acreedor"></div>
<?php if($nodo->nombre == 'fullpay' && count($acreedores_list) > 0){ ?></br>
<a id="exp_acreedor" href="<?php echo site_url();?>/admin/gestion/exportador_acreedor_fullpay?id_cuenta=<?php echo $id; ?>" class="ico-excel" style="margin-left:0;width:150px;color:#578698;">Exportar a Excel</a>
<div class="show_a" style="display:none;">
    <label>
        <input type="checkbox" id="checkAll" name="checkAll"/>
        <div>Resultado del filtro aplicado directamente en la tabla</div>
    </label>
</div>
<?php } ?>
<table id="tbacreedores" class="cell-border">
    <thead>
      <tr class="col_list">
        <th>#</th>
        <!--<th width="50px">Código</th> -->
        <th width="250px">Acreedor</th>
        <!--<th>Fecha Etapa</th> -->
        <th>Monto</th>
        <th>%</th>
        <th>Etapa</th>
        <th width="100px">Fecha Presentado/Verificado</th>
        <th>Etapa Objetado</th>
        <th>Tipo Verificaci&oacute;n</th>
        <th>Preferencia</th>
        <th width="130px">Gesti&oacute;n</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    foreach ($acreedores_list as $key=>$acreedor):?>
        <tr id="row-<?php echo $acreedor->idacreedor; ?>">
            <td class="num_list"><?php echo $acreedor->idacreedor;?></td>
            <!--<td class="num_list"><?php echo $acreedor->codigo;?></td>-->
            <td class="acreed_list"><?php echo $acreedor->razon_social;?></td>
            <!--<td class="fechap_list"><?php echo $acreedor->fecha_etapa; ?></td>-->
            <td class="montoac_list"><?php echo $acreedor->monto;?></td>
            <td class="montoac_list"><?php echo $acreedor->porciento;?></td>
            <td class="estadoac_list"><?php echo $acreedor->etapa;?></td>
            <td class="estadoac_list"><?php echo $acreedor->fecha_pres_verif;?></td>
            <td class="estadoac_list"><?php echo $acreedor->sub_estado_acreedor;?></td>
            <td class="verif_list"><?php echo $acreedor->tipo_verificacion;?></td>
            <td class="pref_list"><?php echo $acreedor->preferencia;?></td>
            <td class="gestac_list">
                <a href="#" class="edit_acr" style="cursor:pointer;" data-id="<?php echo $acreedor->idacreedor?>" data-gtab="acreedor">Editar</a>
                <a href="#" class="edit_etap" style="cursor:pointer;" data-url="<?php echo site_url('admin/gestion/index/'.$id.'/'.$acreedor->idacreedor.'/'.$tab_etapa);?>">Etapas</a>
                <a href="<?php echo site_url('admin/gestion/eliminar_acreedor/'.$id.'/'.$acreedor->idacreedor);?>" class="delete" style ="float:right;">Eliminar
                </a>
            </td>
        </tr>
    <?php $i++; ?>
    <?php endforeach;?>
    <tfoot align="right">
        <tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
    </tfoot>
    <tbody>
</table>
<script type="text/javascript">
    //$('#tbacreedores').destroy();
    var table = $('#tbacreedores').DataTable({
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
        footerCallback: function(row, data, start, end, display) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function(i){
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var monTotal = api
                .column(2)
                .data()
                .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                
            var porcTotal = api
                .column(3)
                .data()
                .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
                
            // Update footer by showing the total with the reference of the column index 
            $(api.column(0).footer()).html('Total');
            $(api.column(1).footer()).html('');
            $(api.column(2).footer()).html(monTotal);
            $(api.column(3).footer()).html(100);
            $(api.column(4).footer()).html('');
            $(api.column(5).footer()).html('');
            $(api.column(6).footer()).html('');
            $(api.column(7).footer()).html('');
            $(api.column(8).footer()).html('');
            //$(api.column(9).footer()).html('');
            //$(api.column(10).footer()).html('');
        }
    });

    $("#tipo_verificacion").change(function(){
        var arr = '';
        var total = 0;
        var s_url = $('#s_url').val();
        var s_elim = $('#s_elim').val();
        var id_cuenta = $("#id_cuenta").val();
        var tab_etapa = $('#tab_etapa').val();
        var tipo_verif = $(this).val();

        var actionData = {'tipo_verificacion': tipo_verif, 'id_cuenta': id_cuenta}
        $.ajax({
        type: "POST",
        dataType: 'json',
        data: actionData,
        url: "<?php echo base_url()?>index.php/admin/gestion/buscar_acreedor",
        success: function(response){
            i=1;
            $('#tbacreedores tbody').empty();
            $('#tbacreedores tbody:nth-child(2)').remove();
            $.each(response.result, function(id, value){
                //console.log(value.id);
                arr += value.idacreedor + ',';
                total += parseFloat(value.monto);
                var cl = ' class="a">';
                if (i%2==0) cl = ' class="b">';
              
                var tr ='<tr id="row-'+value.idacreedor+'"'+cl+
                        '<td style="text-align:center;">' + i + '</td>' +
                        '<td>' + value.razon_social + '</td>' +
                        '<td>' + value.monto + '</td>' +
                        '<td>' + value.porciento + '</td>' +
                        '<td>' + value.etapa + '</td>' +
                        '<td style="text-align:center;">' + value.fecha_pres_verif + '</td>' +
                        '<td style="text-align:center;">' + value.sub_estado_acreedor + '</td>' +
                        '<td style="text-align:center;">' + value.tipo_verificacion + '</td>' +
                        '<td style="text-align:center;">' + value.preferencia + '</td>' +
                        '<td class="gestac_list">' +
                        '<a href="#" class="edit_acr" style="cursor:pointer;" data-id="' + value.idacreedor + 
                        '" data-gtab="acreedor">Editar</a>' +
                        ' <a href="#" class="edit_etap" style="cursor:pointer;" data-url="'+ s_url + '/' + value.idacreedor + 
                        '/' + tab_etapa + '">Etapas</a>' + '<a href="' + s_elim + value.idacreedor + 
                        '" class="delete" style ="float:right;">Eliminar</a></td></tr>';
              $("#tbacreedores tbody").append(tr);
              i++;
            });

            $('#tbacreedores tfoot tr th:nth-child(3)').text(total);
            $('#tbacreedores tfoot tr th:nth-child(4)').text(100);
            //Limpiar siempre
            var url = $("#exp_acreedor").prop('href');
            var aux = url.split('&idacreedores=');
            $("#exp_acreedor").prop({'href': aux[0]});
            
            if(arr == ''){
                alertify.dialog('alert').set({transitionOff: true, message: 'No hay campos para mostrar.'}).show();
                return false;
            }
            else{
                var url = $("#exp_acreedor").prop('href');
                $("#exp_acreedor").prop({'href': url + '&idacreedores='+arr.substring(0, arr.length - 1)});
            }
        }

        });
    });

    $(window).load(function() {
        $("#tbacreedores_filter input").keyup(function(e){
            $("#checkAll").prop({'checked': false});
            $("#checkAll").click();
            if($(this).val() == '')
                $("#checkAll").prop({'checked': false});
            else
                $("#checkAll").prop({'checked': true});
            //table.footerCallback(table.row(), data, start, end, display);
            var total = porct = 0;
            $('#tbacreedores tbody tr').each(function(){
                total += parseFloat($(this).find('td:nth-child(3)').text());
                //porct += parseFloat($(this).find('td:nth-child(6)').text());
            });
            $('#tbacreedores tfoot tr th:nth-child(3)').text(total);
            $('#tbacreedores tfoot tr th:nth-child(4)').text(100);
            $('#tbacreedores tbody tr').each(function(){
                var val = $(this).find('td:nth-child(3)').text();
                var aux = ((parseFloat(val)/total)*100).toFixed(2);
                $(this).find('td:nth-child(4)').text(aux);
                //porct += parseFloat($(this).find('td:nth-child(6)').text());
            });
        });
        
        $("#checkAll").click(function(){
            if($(this).is(':checked')){
                //Limpiar siempre
                var url = $("#exp_acreedor").prop('href');
                var arr = url.split('&idacreedores=');
                $("#exp_acreedor").prop({'href': arr[0]});
                //Comienzo
                var arr = '';
                var trs = table.rows({order:'index', search:'applied'}).nodes();
                for(var i=0;i<trs.length;i++){
                    var id = $(trs[i]).attr('id').substring(4); //console.log(id);
                    arr += id + ',';
                }
                if(arr == ''){
                    alertify.dialog('alert').set({transitionOff: true, message: 'No hay campos para mostrar.'}).show();
                    return false;
                }
                else{
                    var url = $("#exp_acreedor").prop('href');
                    $("#exp_acreedor").prop({'href': url + '&idacreedores='+arr.substring(0, arr.length - 1)});
                }
            }else{
                var url = $("#exp_acreedor").prop('href');
                var arr = url.split('&idacreedores=');
                $("#exp_acreedor").prop({'href': arr[0]});
            }
        });
    });
</script>
