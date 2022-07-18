<table class="stable" width="100%">
  <?php echo form_open(site_url().'/admin/procurador/guardar_gasto/'.$id); ?>
  <tr><td colspan="4"><h3>Ingresar un nuevo gasto:</h3><br></td></tr>
  <tr>
      <td>Ítem Gasto:</td>
      <td>
      <?php echo form_dropdown('item_gasto', array( ''=>'Seleccionar','Receptor'=>'Receptor','otro'=>'Otro' ), $this->input->post('item_gasto'),' class="selector_tres" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('item_gasto','<br><span class="error">','</span>');?>
      </td>
      <td>Diligencia:</td>
      <td>
      <?php echo form_dropdown('diligencia', $diligencias, $this->input->post('diligencia'),' class="selector_cuatro" autocomplete="off" data-id="'.$id.'" id="diligencia_p" ');?>
      <?php echo form_error('diligencia','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
  <td co>Fecha:</td><td><input type="text" class="datepicker" name="fecha" value="<?php echo $this->input->post('fecha')?>"><?php echo form_error('fecha','<br><span class="error">','</span>');?></td>
  <td>Nº Boleta:</td><td><?php echo form_input(array('name'=>'n_boleta','style'=>'width:135px'), $this->input->post('n_boleta'));?>
  <?php echo form_error('n_boleta','<br><span class="error">','</span>');?></td></tr>
  <tr>
      <td>Receptor:</td><td><?php echo form_dropdown('id_receptor', $receptores, $this->input->post('rut_receptor'));?>
      <?php echo form_error('id_receptor','<br><span class="error">','</span>');?></td>
      
   </tr>
  <tr>
      <td>Monto:</td><td><?php echo form_input(array('name'=>'monto'), $this->input->post('monto'), 'style="width:86px; text-align:right;" id="gasto_monto"');?>
      <?php echo form_error('monto','<br><span class="error">','</span>');?></td>
      <td>Retención 10%:</td><td><?php echo form_input( 'retencion', $this->input->post('retencion') , 'style="width:86px; text-align:right;" id="gasto_retencion"');?>
      <?php echo form_error('retencion','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr><td colspan="4"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
  <?php echo form_close();?>
  <tr><td colspan="4"><hr style="border:1px solid #CDCCCC"><br><h3>Listado de gastos:</h3><br></td></tr>
  <tr>
      <td colspan="4">
      <table class="stable grilla" width="100%">
          <tr class="titulos-tabla">
              <td>Fecha</td>
              <td>Ítem</td>
              <td>Diligencia</td>
              <td>Nº Boleta</td>
              <td>Receptor</td>
              <td>Monto</td>
              <td>Retención</td>
          </tr>
          <?php if (count($gastos)>0):?>
          <?php foreach ($gastos as $key=>$gasto):?>
              <tr>
              <td><?php echo date('d-m-Y',strtotime($gasto->fecha));?></td>
              <td><?php echo $gasto->item_gasto;?></td>
              <td><?php echo $gasto->diligencia;?></td>
              <td><?php echo $gasto->n_boleta;?></td>
              <td><?php echo $gasto->nombre_receptor;?></td>
              <td><?php echo number_format($gasto->monto,0,',','.');?></td>
              <td><?php echo number_format($gasto->retencion,0,',','.');?></td>
              </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="7">No hay gastos ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>

<script type="text/javascript">
$(document).ready(function(){
	$(document).on("keyup","#gasto_monto",function(e){
		var monto = parseInt($(this).val());
		var retencion = 0;
		if ($(this).val()!=''){retencion = Math.round(monto*0.1,0);}
		$('#gasto_retencion').val(retencion);
	});
});

</script>