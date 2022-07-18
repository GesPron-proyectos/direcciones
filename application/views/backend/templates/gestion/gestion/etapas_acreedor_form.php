<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
<script src="<?php echo base_url()?>js/alertify.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>css/alertify.min.css">
<!--Para ejecutar Script Select2-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>-->
<!--Para ejecutar Script Select2-->
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>-->
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">-->
<script>

$(function() {
  $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(datetext){
            var d = new Date(); // for now
            var h = d.getHours();
            h = (h < 10) ? ("0" + h) : h;

            var m = d.getMinutes();
            m = (m < 10) ? ("0" + m) : m;

            var s = d.getSeconds();
            s = (s < 10) ? ("0" + s) : s;

            datetext = datetext + " " + h + ":" + m + ":" + s;
            $('.datepicker').val(datetext);
        },
    });
});

</script>

<?php 

$observaciones_m = $this->input->post('observaciones');     
$id_etapa_m = $this->input->post('id_etapa');   

$fecha_etapa_m = $this->input->post('fecha_etapa');
if ($fecha_etapa_m==''){ $fecha_etapa_m = date('d-m-Y H:i:s');}

if ($idregistro!=''){
  $observaciones_m = $etapa_juicio_cuenta->observaciones;
  $id_etapa_m =  $etapa_juicio_cuenta->id_etapa;
  $fecha_etapa_m = date('d-m-Y H:i:s',strtotime($etapa_juicio_cuenta->fecha_etapa));
}
?>  
 
<?php echo form_open(site_url().'/admin/gestion/guardar_etapa_acreedor/'.$id.'/'.$idregistro, array('id' => 'form_reg')); ?>
<table class="stable" width="100%">
  <tr><td colspan="4"><h3 id="etap_titulo">Ingresar un nueva Etapa de Acreedor:</h3><br></td></tr>
  <tr>
  <tr>
      <td>Etapa:</td>
      <td>
      <?php
        echo form_dropdown('id_etapa', $etado_acreedor, $id_estado_ac_a, 'id="id_etapa" class="estado_acreedor2"');
        echo form_dropdown('id_etapa2', $sub_etapa, $cuenta->id_tribunal_ex, 'id="id_etapa2" class="sub_estado_acreedor2"' );
        echo form_error('id_etapa','<br><span class="error">','</span>'); ?>
      </td>
      <td>Fecha Etapa:</td><td>
     <input type="text" class="datepicker" autocomplete="off" id="fecha_etapa" name="fecha_etapa" width="800" readonly value="<?php echo $fecha_etapa_m;?>"><?php echo form_error('fecha_etapa','<br><span class="error">','</span>'); ?>
    </td>
  </tr>
  <tr>
  <td>Observación:</td><td><?php echo form_textarea(array('id'=>'observaciones', 'name'=>'observaciones', 'class'=>'custom_input observ_etapa'), $observaciones_m );?>
  <?php echo form_error('observaciones','<br><span class="error">','</span>');?></td>
  <input type="hidden" id="id_etapa_acreedor" name="id_etapa_acreedor">
  <input type="hidden" id="id_acreedor" name="id_acreedor" value="<?php echo $id_acreedor; ?>">
  </tr>
  <tr><td colspan="4"><br><input id="btn-crear" type="button" value="Crear Nuevo" style="float:right;cursor:pointer;"></td></tr>
  </table>
  <?php echo form_close();?>
 <script type="text/javascript">

     $(document).ready(function(){
        if ($(".estado_acreedor2 option:selected").text() == 'Objetado')
          $(".sub_estado_acreedor2").css({'display': 'inline'});
        else
          $(".sub_estado_acreedor2").css({'display': 'none'});

        $('#btn-crear').click(function(){
            if ($('.estado_acreedor2').val() == 0){
              alertify.dialog('alert').set({transitionOff: true, message: 'El campo etapa es un campo obligatorio.'}).show();
              e.stopPropagation();
              return false;
            }
            else
              $('#form_reg').submit();
        });

        $(".estado_acreedor2").change(function() {
            $(".estado_acreedor2 option:selected").each(function() {
                idEstado = $('.estado_acreedor2').val();
                $.post("<?php echo base_url(); ?>index.php/admin/gestion/acreedor_estado", {
                    idEstado : idEstado
                }, function(data) {
                    $(".sub_estado_acreedor2").html(data);
                    if ($(".estado_acreedor2 option:selected").text() == 'Objetado')
                      $(".sub_estado_acreedor2").css({'display': 'inline'});
                    else
                      $(".sub_estado_acreedor2").css({'display': 'none'});
                });
            });
        });

    });


</script>