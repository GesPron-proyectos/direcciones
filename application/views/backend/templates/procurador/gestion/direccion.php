<table class="stable" width="100%">
  <?php echo form_open(site_url().'/admin/procurador/guardar_direccion/'.$id); ?>
  <tr><td colspan="4"><h3>Ingresar un nueva dirección:</h3><br></td></tr>
  <tr>
      <td>Dirección:</td>
      <td>
      <input name="direccion" value="<?php echo $this->input->post('direccion');?>">
      <?php echo form_error('direccion','<br><span class="error">','</span>');?>
      </td>
  </tr>
   <tr>
      <td>Comuna:</td>
      <td>
      <?php echo form_dropdown('id_comuna', $comunas, $this->input->post('id_comuna'),' class="" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('id_comuna','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
      <td>Observación:</td>
      <td>
      <input name="observacion" value="<?php echo $this->input->post('observacion');?>">
      <?php echo form_error('observacion','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
  
  <tr><td colspan="2"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
  <?php echo form_close();?>
  <tr><td colspan="2"><hr style="border:1px solid #CDCCCC"><br><h3>Direcciones:</h3><br></td></tr>
  <tr>
      <td colspan="2">
      <table class="stable grilla" width="100%">
          <tr class="titulos-tabla">
              <td>Dirección</td>
              <td>Comunas</td>
              <td>Observación</td>
              <td>Estado</td>
              <td>Tipo Dirección</td>
          </tr>
          <?php if (count($direcciones)>0):?>
          <?php foreach ($direcciones as $key=>$direccion):?>
              <tr>
              <td><?php echo $direccion->direccion;?></td>
              <td><?php echo $direccion->nombre_comuna;?></td>
              <td><?php echo $direccion->observacion;?></td>
              <td><?php echo form_dropdown('estado', $estados, $direccion->estado,'class="estado" id="'.$direccion->id.'" data-tipo="direccion"');?><div style="display:inline" id="response_direccion_<?php echo $direccion->id;?>"></div></td>
              <td><?php echo form_dropdown('tipo', $tipos_direcciones, $direccion->tipo,'class="tipo" id="'.$direccion->id.'" data-tipo="direccion"');?><div style="display:inline" id="response_direccion_<?php echo $direccion->id;?>"></div></td>
              </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="4">No hay registros ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>