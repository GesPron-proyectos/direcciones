<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">-->

<script>
$(function() {
  $('#datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(datetext){
            var d = new Date(); // for now
            var h = d.getHours();
            h = (h < 10) ? ("0" + h) : h ;

            var m = d.getMinutes();
            m = (m < 10) ? ("0" + m) : m ;

            var s = d.getSeconds();
            s = (s < 10) ? ("0" + s) : s ;

            datetext = datetext + " " + h + ":" + m + ":" + s;
            $('#datepicker').val(datetext);
        },
    });
});

</script>

 <?php 

  // $hora= date ("h:i:s");  
  //echo $hora;

// $time = time();
//echo date("d-m-Y H:i:s",$time);

$observaciones_m = $this->input->post('observaciones');     
$fecha_etapa_m = $this->input->post('fecha_etapa');
if ($fecha_etapa_m==''){ $fecha_etapa_m = date('d-m-Y H:i:s');}

if ($idregistro!='')
{
  $observaciones_m = $etapa_juicio_cuenta->observaciones;
$id_etapa_m = $this->input->post('id_etapa');   

	$id_etapa_m =  $etapa_juicio_cuenta->id_etapa;
	$fecha_etapa_m = date('d-m-Y H:i:s',strtotime($etapa_juicio_cuenta->fecha_etapa));


}
?>  
 
<?php echo form_open(site_url().'/admin/gestion/guardar_etapa/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
  <tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nueva Etapa de Juicio:<?php else:?>Editar Etapa de Juicio #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="direccion">Cerrar</a><?php endif;?></h3><br></td></tr>
  <tr>
      <td>Etapa:</td>
      <td>
      <?php echo form_dropdown('id_etapa', $etapas_juicio, $id_etapa_m,' class="select_dos" autocomplete="off" data-id="'.$id.'" ');?>
      <select style="display:none; display:inline" name="etapa_otro" class="otro_<?php echo $id?>">
        <option value="">Seleccionar</option>
      </select>
      <?php echo form_error('id_etapa','<br><span class="error">','</span>');?>
      </td>
      <td co>Fecha Etapa:</td><td>
	  <input type="text" id="datepicker" name="fecha_etapa" width="800" value="<?php echo $fecha_etapa_m;?>"><?php echo form_error('fecha_etapa','<br><span class="error">','</span>');?>
	  </td>
  </tr> 
  <tr>

  <style type="text/css">
    .custom_input{
    width:573px;/*use according to your need*/
    height:23px;/*use according to your need*/
}
  </style>
  
  <td>Observación:</td><td><?php echo form_textarea(array('name'=>'observaciones','class'=>'custom_input'),$observaciones_m );?>
  <?php echo form_error('observaciones','<br><span class="error">','</span>');?></td>
  </tr>
  <tr><td colspan="4"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
  </table>
  <?php echo form_close();?>