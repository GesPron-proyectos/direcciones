 
 <?php 
    $direccion_m = $this->input->post('direccion');
	 $observacion_m = $this->input->post('observacion');
	 $id_comuna_m = $this->input->post('id_comuna');
if ($idregistro!=''){
	$direccion_m = $direccion->direccion;
	$observacion_m  = $direccion->observacion;
	$id_comuna_m  = $direccion->id_comuna;
	}
?>  
 
 
 <?php echo form_open(site_url().'/admin/gestion/guardar_direccion/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
  <tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nueva dirección:<?php else:?>Editar dirección #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="direccion">Cerrar</a><?php endif;?></h3><br></td></tr>
  <tr>
      <td>Dirección:</td>
      <td>
      <input name="direccion" value="<?php echo $direccion_m;?>">
      <?php echo form_error('direccion','<br><span class="error">','</span>');?>
      </td>
  </tr>
   <tr>
      <td>Comuna:</td>
      <td>
      <?php echo form_dropdown('id_comuna', $comunas, $id_comuna_m,' class="" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('id_comuna','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
      <td>Observación:</td>
      <td>
      <input name="observacion" value="<?php echo $observacion_m;?>">
      <?php echo form_error('observacion','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
  
  <tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
  </table>
  
  <?php echo form_close();?>