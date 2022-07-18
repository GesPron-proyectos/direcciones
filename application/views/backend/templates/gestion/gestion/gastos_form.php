<?php echo form_open(site_url().'/admin/gestion/guardar_gasto/'.$id.'/'.$idregistro); ?>

<?php 
$estado_retencion = $this->input->post('estado_retencion');
$id_estado_pago = $this->input->post('id_estado_pago');
$item_gasto = $this->input->post('item_gasto');
$id_diligencia = $this->input->post('id_diligencia');
if($this->input->post('fecha')){
$fecha = $this->input->post('fecha');
}else{
$fecha = date("Y-m-d");
}
if ($fecha!=''){ $fecha = date('d-m-Y',strtotime($fecha));}


if($this->input->post('fecha_ingreso_banco')){
$fecha_ingreso_banco = $this->input->post('fecha_ingreso_banco');
}else{
$fecha_ingreso_banco = date("Y-m-d");
}
if ($fecha_ingreso_banco!=''){ $fecha_ingreso_banco = date('d-m-Y',strtotime($fecha_ingreso_banco));}


if($this->input->post('fecha_recepcion')){
$fecha_recepcion = $this->input->post('fecha_recepcion');
}else{
$fecha_recepcion = date("Y-m-d");
}
if ($fecha_recepcion!=''){ $fecha_recepcion = date('d-m-Y',strtotime($fecha_recepcion));}



$n_boleta = $this->input->post('n_boleta');

  $id_receptor = '';
  if($this->input->post('id_receptor')){
  		if (count($gasto) == 1){ 
     		$id_receptor = $gasto->receptor;	
		} else {
			$id_receptor = $this->input->post('id_receptor');
		}
    } elseif (isset($cuenta->receptor)){
		$id_receptor = $cuenta->receptor;
	}


$monto = $this->input->post('monto');
$retencion = $this->input->post('retencion');

if ($idregistro!=''){	
    $item_gasto = $gasto->item_gasto;
	$id_diligencia = $gasto->id_diligencia;
	$fecha = date('d-m-Y',strtotime($gasto->fecha));
	$fecha_ingreso_banco = date('d-m-Y',strtotime($gasto->fecha_ingreso_banco));
	$fecha_recepcion = date('d-m-Y',strtotime($gasto->fecha_recepcion));
	$n_boleta = $gasto->n_boleta;
	$estado_retencion= $gasto->estado_retencion;
	$monto = $gasto->monto;
	$id_estado_pago = $gasto->id_estado_pago;
	$retencion = $gasto->retencion;
}
?>



<table class="stable" width="100%">
  
  <tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo gasto:<?php else:?>Editar gasto #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="gastos">Cerrar</a><?php endif;?></h3><br></td></tr>
  <tr>
      <td>Ítem Gasto:</td>
      <td>
      <?php echo form_dropdown('item_gasto', array( ''=>'Seleccionar','Receptor'=>'Receptor','otro'=>'Otro' ), $item_gasto,' class="selector_tres" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('item_gasto','<br><span class="error">','</span>');?>
      </td>
      <td>Diligencia:</td>
      <td>
      <?php echo form_dropdown('id_diligencia', $diligencias, $id_diligencia,' class="selector_cuatro" autocomplete="off" data-id="'.$id.'" id="diligencia_p" ');?>
      <?php echo form_error('id_diligencia','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
  <td>Fecha:</td><td><input type="text" class="datepicker" name="fecha" value="<?php echo $fecha?>"><?php echo form_error('fecha','<br><span class="error">','</span>');?></td>
  <td>Nº Boleta:</td><td><?php echo form_input(array('name'=>'n_boleta','style'=>'width:135px'), $n_boleta);?>
  <?php echo form_error('n_boleta','<br><span class="error">','</span>');?></td></tr>
  <tr>
      <td>Receptor:</td><td><?php echo form_dropdown('id_receptor', $receptores, $id_receptor);?>
      <?php echo form_error('id_receptor','<br><span class="error">','</span>');?></td>
      
   </tr>
   
   
      <td>Monto:</td><td><?php echo form_input(array('name'=>'monto'), $monto, 'style="width:86px; text-align:right;" id="gasto_monto"');?>
      <?php echo form_error('monto','<br><span class="error">','</span>');?></td>
      <td>
	  <?php if ($nodo->nombre=='fullpay'):?>
		  <?php $gastos_check = FALSE; if ($estado_retencion == '1'){$gastos_check = TRUE;}?>
          <?php echo form_checkbox(array('name'=>'estado_retencion','id'=>'chk_estado_retencion','class'=>'check','style'=>'width:25px'),'1',$gastos_check);?> Aplicar
          <?php echo form_error('estado_retencion', '<span class="error" style="margin-left:165px;">', '</span>');?>
      <?php endif;?>
      Retención 10%:</td><td><?php echo form_input( 'retencion', $retencion , 'style="width:86px; text-align:right;" id="gasto_retencion"');?>
      <?php echo form_error('retencion','<br><span class="error">','</span>');?>
      </td>
  </tr>
  
  <tr>
   <?php if ($nodo->nombre=='fullpay'):?>
 <td>Fecha Ingreso Banco :</td><td><input type="text" class="datepicker" name="fecha_ingreso_banco" value="<?php echo $fecha_ingreso_banco?>"><?php echo form_error('fecha_ingreso_banco','<br><span class="error">','</span>');?></td>
     <?php endif;?>  
       
        <?php if ($nodo->nombre=='fullpay'):?>
     <td>Pagado/pendiente:</td>
     <td><?php echo form_dropdown('id_estado_pago', $estado_pago, $id_estado_pago);?>
      <?php echo form_error('id_estado_pago','<br><span class="error">','</span>');?></td>
   </tr>   
  <?php endif;?>  
  
   <tr>
   <?php if ($nodo->nombre=='fullpay'):?>
 <td>Fecha recepción :</td><td><input type="text" class="datepicker" name="fecha_recepcion" value="<?php echo $fecha_recepcion?>"><?php echo form_error('fecha_recepcion','<br><span class="error">','</span>');?></td>
     <?php endif;?>  
   
   </tr>  
  
  
  
  <tr><td colspan="4"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
  </table>
  
  
  
  
  <?php echo form_close();?>
  <script type="text/javascript">
$(document).ready(function(){
	$(".datepicker").datepicker({ format: 'dd-mm-yyyy',});
});
</script>