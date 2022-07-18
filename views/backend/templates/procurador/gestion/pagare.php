<table class="stable" width="100%">
  <?php echo form_open(site_url().'/admin/procurador/guardar_pagare/'.$id); ?>
  <tr><td colspan="4"><h3>Ingresar un nuevo pagaré:</h3><br></td></tr>
  <tr>
      <td>Tipo de Producto:</td>
      <td>
      <?php echo form_dropdown('id_tipo_producto', $tipos_productos, $this->input->post('id_tipo_producto'),' class="" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('id_tipo_producto','<br><span class="error">','</span>');?>
      </td>
      <td>Nº Pagaré:</td>
      <td>
      <input type="text" class="n_pagare" name="n_pagare" value="<?php echo $this->input->post('n_pagare')?>">
	  <?php echo form_error('n_pagare','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
  <td co>Fecha Asignación:</td><td><input type="text" class="datepicker" name="fecha_asignacion" value="<?php echo $this->input->post('fecha_asignacion')?>"><?php echo form_error('fecha_asignacion','<br><span class="error">','</span>');?></td>
  <td>Monto Pagaré:</td><td><?php echo form_input(array('name'=>'monto_deuda','style'=>'width:135px'), $this->input->post('monto_deuda'));?>
  <?php echo form_error('monto_deuda','<br><span class="error">','</span>');?></td></tr>
  <tr><td colspan="4"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
  <?php echo form_close();?>
  <tr><td colspan="4"><hr style="border:1px solid #CDCCCC"><br><h3>Listado de Pagarés:</h3><br></td></tr>
  <tr>
      <td colspan="4">
      <table class="stable grilla" width="100%">
          <tr class="titulos-tabla">
              <td>Fecha</td>
              <td>Tipo de Producto</td>
              <td>Nº</td>
              <td>Monto</td>
          </tr>
          <?php if (count($pagares)>0):?>
          <?php foreach ($pagares as $key=>$pagare):?>
              <tr>
              <td><?php echo date('d-m-Y',strtotime($pagare->fecha_asignacion));?></td>
              <td><?php echo $pagare->tipo;?></td>
              <td><?php echo $pagare->n_pagare;?></td>
              <td><?php echo number_format($pagare->monto_deuda,0,',','.');?></td>
              </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="7">No hay pagarés ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>