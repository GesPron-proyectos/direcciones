<?php
  $monto_a            = $this->input->post('monto');
  $id_prefrencia_a    = $this->input->post('preferencia_idpreferencia');
  $id_verif_a         = $this->input->post('tipo_verificacion_idtipo_verificacion');
  $razon_social_a     = $this->input->post('mandantes_id');
  $presentado_check   = $this->input->post('presentado');
  $agregado_check     = $this->input->post('agregado');

if ($idregistro!=''){
  $monto_a            = $acreedor->monto;
  $id_prefrencia_a    = $acreedor->preferencia_idpreferencia;
  $id_verif_a         = $acreedor->tipo_verificacion_idtipo_verificacion;
  $razon_social_a     = $acreedor->mandantes_id;
  $presentado_check   = $acreedor->presentado;
  $agregado_check     = $acreedor->agregado;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="<?php echo base_url()?>js/alertify.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>css/alertify.min.css">
<style type="text/css">
    .custom_input{
    width:573px;/*use according to your need*/
    height:23px;/*use according to your need*/
}
</style>
<body>
<?php echo form_open(site_url().'/admin/gestion/guardar_acreedor/'.$id.'/'.$idregistro, array('id' => 'form_save_acr')); ?>

<table class="stable" width="100%">
  <tr><td colspan="4"><h3 id="acr_titulo">Ingresar nuevo Acreedor:</h3><br></td></tr>
   <tr>
      <td>Acreedor:</td>
      <td>
        <?php echo form_dropdown('mandantes_id', $sct_razon_social, $razon_social_a, 'id="mandantes_id" data-id="'.$id.'" class = "mi-selector"');?>
        <?php echo form_error('mandantes_id','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
      <td>Monto:</td>
      <td>
      <input id="monto" autocomplete="off" name="monto" type="text" value="<?php echo $monto_a; ?>" style="width:131px;">
      <?php echo form_error('monto', '<span class="error">', '</span>');?>
      </td>
  </tr>
  <tr>
      <td>Preferencia:</td>
      <td>
        <?php echo form_dropdown('preferencia_idpreferencia', $sct_preferencia, $id_prefrencia_a, 'id="preferencia_id" data-id="'.$id.'" class = "mi-selector"');?>
        <?php echo form_error('preferencia_idpreferencia','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
      <td>Verificación:</td>
      <td>
      <?php echo form_dropdown('tipo_verificacion_idtipo_verificacion', $sct_verificacion, $id_verif_a, 'id="tipo_verificacion_id" data-id="'.$id.'" class = "mi-selector"');?>
        <?php echo form_error('tipo_verificacion_idtipo_verificacion','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
    <td>Etapa:</td>
    <td>
    <?php 
        echo form_dropdown('id_etapa', $etado_acreedor, $id_estado_ac_a, 'id="id_etapa" class="estado_acreedor"');
        echo form_dropdown('id_etapa2', $sub_etapa, $cuenta->id_tribunal_ex, 'id="id_etapa2" class="sub_estado_acreedor"');
    ?>
    <input type="text" id="fecha_pres_verif" name="fecha_pres_verif" <?php if($id_estado_ac_a != 1 && $id_estado_ac_a != 2) echo 'style="display:none;"' ?> value="<?php echo $fecha_pres_verif;?>">
    </td>
  </tr>
  <tr>
    <td>Observación:</td>
    <td>
    <?php echo form_textarea(array('id'=>'observaciones', 'name'=>'observaciones', 'class'=>'custom_input'), $observaciones_m );?>
    <?php echo form_error('observaciones','<br><span class="error">','</span>');?>
    </td>
  </tr>
  <tr><td colspan="2"><br><input type="button" id="btn_new" value="Crear Nuevo" style="float:right;cursor:pointer;"></td></tr>
  <input type="hidden" id="id_etapa_acreedor" name="id_etapa_acreedor">
  <input type="hidden" id="id_acreedor" name="id_acreedor" value="<?php echo $idregistro; ?>">
  </table>
  <?php echo form_close();?>
</body>
</html>
  <script>
    //Plugin Select2
    jQuery(document).ready(function($){
    $(document).ready(function() {
        $('.mi-selector').select2();

        $('#btn_new').click(function(e){
            if ($('#mandantes_id').val() == 0){
              alertify.dialog('alert').set({transitionOff: true, message: 'El campo acreedor es un campo obligatorio.'}).show();
              e.stopPropagation();
              return false;
            }
            else if ($('#monto').val() == 0 || $('#monto').val() == ''){
              alertify.dialog('alert').set({transitionOff: true, message: 'El campo monto debe ser mayor que cero.'}).show();
              e.stopPropagation();
              return false;
            }
            else if ($('#preferencia_id').val() == 0){
              alertify.dialog('alert').set({transitionOff: true, message: 'El campo preferencia es un campo obligatorio.'}).show();
              e.stopPropagation();
              return false;
            }
            else if ($('#tipo_verificacion_id').val() == 0){
              alertify.dialog('alert').set({transitionOff: true, message: 'El campo verificación es un campo obligatorio.'}).show();
              e.stopPropagation();
              return false;
            }
            else
              $('#form_save_acr').submit();
        });

      });
    });

    $("#id_etapa").change(function(){
        if($(this).val() == 1 || $(this).val() == 2){
            $('#fecha_pres_verif').css({'display': 'inline'});
        }
        else
            $('#fecha_pres_verif').css({'display': 'none'});
    });

    //Formato Datepicker
    $(".datepicker, #fecha_pres_verif").datepicker({
      dateFormat:'dd-mm-yy'
    });

    //Formato para monto con puntos
    $('#monto').keyup(function(event) {
        $(this).val(function(index, value) {
            return value
            .replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        });
    });

  </script>