<table class="stable" width="100%">
  <?php echo form_open(site_url().'/admin/procurador/guardar_bien/'.$id); ?>
  <tr><td colspan="4"><h3>Ingresar un nuevo bien:</h3><br></td></tr>
  <tr>
      <td>Tipo:</td>
      <td>
      <?php echo form_dropdown('tipo', $tipos_bienes,  $this->input->post('tipo'));?>
      <?php echo form_error('tipo','<br><span class="error">','</span>');?>
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
              <td>Tipo</td>
              <td>Observación</td>
              <td>Estado</td>
          </tr>
          <?php if (count($bienes)>0):?>
          <?php foreach ($bienes as $key=>$bien):?>
              <tr>
              <td><?php echo $tipos_bienes[$bien->tipo];?></td>
              <td><?php echo $bien->observacion;?></td>
              <td><?php echo form_dropdown('estado', $estados,  $bien->estado, 'class="estado" id="'.$bien->id.'" data-tipo="bien"');?><div style="display:inline" id="response_bien_<?php echo $bien->id;?>"></div></td>
              </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="4">No hay registros ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>