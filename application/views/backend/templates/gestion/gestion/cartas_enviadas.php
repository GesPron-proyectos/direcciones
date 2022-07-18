<?php
  $instituciones_idinstituciones = $this->input->post('instituciones_idinstituciones');
  $fecha_envio                   = $this->input->post('fecha_envio');
  
if ($idregistro!=''){

  $fecha_envio        = date('d-m-Y',strtotime($cenviadas->fecha_envio));
  $id_institutucion_c = $cenviadas->razon_social;
  $instituciones_idinstituciones = $cenviadas->instituciones_idinstituciones;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<script src="<?php echo base_url()?>js/alertify.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>css/alertify.min.css">
<style type="text/css">
    .custom_input{
    width:573px;/*use according to your need*/
    height:23px;/*use according to your need*/  
    }
    .resp_positiva, .resp_negativa{
      width: 15px;
      height: 15px;
      border-radius: 53px 53px 53px 53px;
      -moz-border-radius: 53px 53px 53px 53px;
      -webkit-border-radius: 53px 53px 53px 53px;
      border: 0px solid #000000;
      float: right;
    }
    .resp_negativa{ background-color: red; }
    .resp_positiva{ background-color: green; }
    td.details{ padding:0 !important; }
    .details, table.details, table.details tr:last-child, table.details tr td{
      background-color: #eee;
      border: none;
    }
    .fa-plus{ 
      font-size: 20px;
      cursor: pointer;
    }
</style>
<body>

<!-- Select Option Todas las instituciones -->
<br/></br>
<?php echo form_open(site_url().'/admin/gestion/guardar_institucion_c/'.$id.'/'.$idregistro, array('id' => 'form_cartas')); ?>
  <span>Instituciones:</span>
      <?php echo form_dropdown('instituciones_idinstituciones', $sct_institucion, $instituciones_idinstituciones, 'data-id="'.$id.'" id="sct_2_cartas" class="id_inst"');?>
      <?php echo form_error('instituciones_idinstituciones','<br><span class="error">','</span>');?>
  <input type="button" name="btn_agregar_inst" id="btn_agregar_inst" style="cursor:pointer;" value="Agregar">
<div class="divRes"></div>
<?php echo form_close(); ?><!--Cierre Registro de nueva institución prioritaria-->
<!--Tabla Registro Cartas Enviadas-->
</br></br></br>
<div class="box-form-cartas_enviadas"></div>
<table id="tbinstituciones" class="tabla_list cell-border" data-page-length='25'>
    <thead>
      <tr class="col_list">
        <th style="width: 20px;">#</th>
        <th>Instituciones Prioritarias</th>
        <th>Fecha/Respuesta</th>
        <th>Fecha Envío</th>
        <th>Respuesta</th>
        <th>Envío Cartas</th>
        <th style="width: 44px;">Gestión</th>
      </tr>
    </thead>
    <tbody id="rows">
      <?php
      $i = 0;
      foreach ($cartas_enviadas as $key=>$cartas): ?>
        <tr id="tr_<?php echo $cartas->instituciones_idinstituciones; ?>" class="tr_cartas">
          <?php echo form_open(site_url().'/admin/gestion/guardar_cartas/'.$id.'/'.$idregistro); ?>
          <td class="num_list"><?php $carta_institucion = $cartas->instituciones_idinstituciones;?>
          <?php if(count($cartas->cartas) > 1): ?>
            <label onclick="openCartas(<?php echo $cartas->instituciones_idinstituciones; ?>)" class="fa fa-plus">+</label>
          <?php endif; ?>
          </td>
          <td class="acreed_list">
            <?php echo $carta_rsocial = $cartas->razon_social;?>
            <input type="hidden" name="instituciones_idinstituciones" value="<?php echo $cartas->instituciones_idinstituciones;?>">
          </td>
          <td class="fechap_list">
            
            <?php 
              echo $cartas->fecha_ultimo_envio;
              if ($cartas->estado == 0)
                echo "<div></div>";
              elseif($cartas->estado == 1)
                echo "<div class='resp_positiva';></div>";
              else
                echo "<div class='resp_negativa';></div>";
            ?>
          </td>
          <td class="fechap_list">
            <input type="" autocomplete="off" id="" class="datepicker" name="fecha_envio" required>
          </td>
          <td>
            <select name="estado" id="<?php echo $cartas->idcartas_enviadas; ?>" data-tipo="cartas_enviadas">
              <option value="0" selected="selected">Seleccionar</option>
              <option value="1">Respuesta Positiva</option>
              <option value="2">Respuesta Negativa</option>
            </select>
            <div style="display:inline" id="response_cartas_enviadas_<?php echo $cartas->idcartas_enviadas;?>"></div>
          </td>
          <td style="padding-left: 2%;">
            <input type='submit' name='enviar_carta' style="cursor:pointer;" value='Enviar Carta'>
          </td>
          <td>
            <a href="<?php echo site_url('admin/gestion/eliminar_carta/'.$id.'/'.$cartas->idcartas_enviadas);?>" style="padding-left: inherit;" class="delete">
              Eliminar
            </a>
          </td>
        </tr>
        <?php
            $idc = $cartas->instituciones_idinstituciones;
            $cartas = $cartas->cartas; 
            if(count($cartas) > 1): ?>
            <tr id="ca_<?php echo $idc; ?>" style="display:none;">
              <td class="details" colspan="6">
                <table class="details">
                  <tbody> 
                  <?php $pos = 1;
                    $respuesta = array('Sin Respuesta', 'Respuesta Positiva', 'Respuesta Negativa');
                    foreach ($cartas as $key=>$cenviadas):?>
                    <?php if($cenviadas->fecha_envio != '0000-00-00'): ?>
                      <tr id="row-<?php echo $cenviadas->idcartas_enviadas; ?>" class="tr_inst">
                        <td class="acreed_list">Carta #<?php echo $pos++;?></td>
                        <?php 
                          //echo $respuesta[$cenviadas->estado]; 
                          /*if ($cenviadas->estado == 0)
                            echo "<div></div>";
                          elseif($cenviadas->estado == 1)
                            echo "<div class='resp_positiva';></div>";
                          else
                            echo "<div class='resp_negativa';></div>"; */
                        ?>
                        <td class="fechap_list">Enviada: <?php echo date('d-m-Y', strtotime($cenviadas->fecha_envio));?></td>
                        <td width="70px">
                          <a href="<?php echo site_url('admin/gestion/eliminar_carta/'.$id.'/'.$cenviadas->idcartas_enviadas);?>" style="padding-left: inherit;" class="delete">
                            Eliminar
                          </a>
                        </td>
                      </tr>
                  <?php endif; endforeach; ?>
                  </tbody>
                </table>
              </td>
            </tr>
        <?php endif; ?>
      <?php $i++; ?>  
      <?php echo form_close();?>
      <?php endforeach;?>
  </tbody>
</table>
</br></br>
</body>
</html>
<script>
    //Plugin Select 2
    $(document).ready(function() {
        //$('.id_inst').select2();
        $("#btn_agregar_inst").click(function(){
          var inst = $('#sct_2_cartas').val();
          if(inst == 0){
            alertify.dialog('alert').set({transitionOff:true,message: 'El campo instituciones es obligatorio.'}).show();
            return false;
          }
          else if(validarRepetido(inst)){
            alertify.dialog('alert').set({transitionOff:true,message: 'La institución no se puede repetir.'}).show();
            return false;
          }
          else
            $("#form_cartas").submit();
        });
    });

    //Formato Datepicker
    $(".datepicker").datepicker({
        dateFormat:'dd-mm-yy',
        maxDate: "+0m +0d",
    });

    function validarRepetido(id_inst){
        var repet = false;
        $("#tbinstituciones tbody tr").each(function(){
            var id = $(this).prop('id').substring(3);
            if(id == id_inst)
              repet = true;
        });
        return repet;
    }

    function openCartas(id){
      //alert(id);
        var cerrar = '<i onclick="closeCartas('+id+')" class="fa fa-plus">-</i>';
        $("#tbinstituciones tbody").find('#tr_'+id).find('td:nth-child(2)').html(cerrar);
        $("#ca_"+id).fadeIn('slow', function(){
          $("#ca_"+id).css({'display': 'table-row'});
        });
    }

    function closeCartas(id){
        var cerrar = '<i onclick="openCartas('+id+')" class="fa fa-plus">+</i>';
        $("#tbinstituciones tbody").find('#tr_'+id).find('td:nth-child(2)').html(cerrar);
        $("#ca_"+id).fadeOut('slow', function(){
          $("#ca_"+id).css({'display': 'none'});
        });
    }
</script>