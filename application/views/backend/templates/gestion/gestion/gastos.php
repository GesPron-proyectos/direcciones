  <?php $this->load->view('backend/templates/gestion/gestion/gastos_form');?>
  <div id="box-form-gastos"></div>
  <table class="stable" width="100%">
  <tr><td colspan="4"><h3>Listado de gastos:</h3><br></td></tr>
  <tr>
      <td colspan="4">
      <table class="stable grilla" width="100%">
          <tr class="titulos-tabla">
           <td>#</td>
              <td>Estado</td>
              <td>Fecha</td>
              <td>Ítem</td>
              <td>Diligencia</td>
              <td>Nº Boleta</td>
              <td>Receptor</td>
              <td>Rol</td>
              <td>Monto</td>
              <td>Retención</td>
              <?php if ($nodo->nombre=='fullpay'):?>
              <td>Fecha Recepción</td>
              <td>Fecha Ingreso Banco</td>
              <td>Estado Pago</td>
              <?php endif;?>
              
               <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>Gestión</td>
                <?php endif;?>
           </tr>
          <?php if (count($gastos)>0):?>
          <?php foreach ($gastos as $key=>$gasto):?>
              <tr>
                <td>#<?php echo $gasto->id;?></td>
                  <td><?php if ($gasto->id_estado_pago==-1){echo 'Pendiente';} else {echo 'Ingresado';}?></td>
              <td><?php echo date('d-m-Y',strtotime($gasto->fecha));?></td>
              <td><?php echo $gasto->item_gasto;?></td>
              <td><?php echo $gasto->diligencia;?></td>
              <td><?php echo $gasto->n_boleta;?></td>
              <td><?php echo $gasto->nombre_receptor;?></td>
               <td><?php echo $gasto->rol;?></td>
              <td><?php echo number_format($gasto->monto,0,',','.');?></td>
              <td><?php echo number_format($gasto->retencion,0,',','.');?></td>
                <?php if ($nodo->nombre=='fullpay'):?>
              <td><?php echo date('d-m-Y',strtotime($gasto->fecha_recepcion));?></td>
              <td><?php echo date('d-m-Y',strtotime($gasto->fecha_ingreso_banco));?></td>
              <td> <?php if($gasto->id_estado_pago=='2'){ echo 'Pendiente'; }elseif($gasto->id_estado_pago=='1'){ echo 'Pagado'; } ?>
               <?php endif;?>
               <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>
                
                 <a href="<?php echo site_url('admin/gestion/editar_gastos/'.$id.'/'.$gasto->id);?>" class="edit"  data-id="<?php echo $gasto->id;?>" data-gtab="gastos" >Editar</a>
                 <a href="<?php echo site_url('admin/gestion/eliminar_gastos/'.$id.'/'.$gasto->id);?>" class="delete">Eliminar</a></td>
                <?php endif;?>
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
	$(document).on("change","#gasto_monto",function(e){
		var monto = parseInt($(this).val());
		var retencion = 0;
		if ($(this).val()!=''){retencion = Math.round(monto*0.1,0);}
		$('#gasto_retencion').val(retencion);
	});
});

</script>