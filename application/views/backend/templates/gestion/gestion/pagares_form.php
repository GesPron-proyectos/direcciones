<?php 
$id_tipo_producto = $this->input->post('id_tipo_producto');
$n_pagare = $this->input->post('n_pagare');

if($nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ) {
    $fecha_suscripcion = $this->input->post('fecha_suscripcion');
}

$fecha_vencimiento = $this->input->post('fecha_vencimiento');
$monto_deuda = $this->input->post('monto_deuda');
$tasa_interes = $this->input->post('tasa_interes');
$numero_cuotas = $this->input->post('numero_cuotas');
$valor_ultima_cuota = $this->input->post('valor_ultima_cuota');
$valor_primera_cuota = $this->input->post('valor_primera_cuota');
$vencimiento_primera_cuota = $this->input->post('vencimiento_primera_cuota');
$vencimiento_restantes_cuotas = $this->input->post('vencimiento_restantes_cuotas');
$nombre_aval = $this->input->post('nombre_aval');
$ultima_cuota_pagada = $this->input->post('ultima_cuota_pagada');
$fecha_pago_ultima_cuota = $this->input->post('fecha_pago_ultima_cuota');
$valor_ultima_cuota_pagada = $this->input->post('valor_ultima_cuota_pagada');
$saldo_deuda = $this->input->post('saldo_deuda');
$tasa_interes_anual = $this->input->post('tasa_interes_anual');

/* if($nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ) {
    if ($fecha_suscripcion != '') {
        $fecha_suscripcion = date('d/m/Y', strtotime($fecha_suscripcion));
    }
} */

if($nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ) {
    if ($fecha_asignacion != '') {
        $fecha_asignacion = date('d/m/Y', strtotime($fecha_asignacion));
    }
}


if ($idregistro!=''){
	$id_tipo_producto = $pagare->id_tipo_producto;
	$n_pagare = $pagare->n_pagare;

if($nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ) {
    if ($pagare->fecha_suscripcion != '' && $pagare->fecha_suscripcion != '0000-00-00' && $pagare->fecha_suscripcion != '31-12-1969' && $pagare->fecha_suscripcion != '1969-12-31') {
        $fecha_suscripcion = date('d-m-Y', strtotime($pagare->fecha_suscripcion));
    }
}

    if($nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ) {
        if ($pagare->fecha_asignacion  != '' && $pagare->fecha_asignacion  != '31-12-1969' && $pagare->fecha_asignacion  != '0000-00-00' && $pagare->fecha_asignacion  != '1969-12-31') {
            $fecha_asignacion  = date('d-m-Y', strtotime($pagare->fecha_asignacion ));
        }
    }


    if ($pagare->fecha_vencimiento!='' && $pagare->fecha_vencimiento!='0000-00-00' && $pagare->fecha_vencimiento!='31-12-1969' && $pagare->fecha_vencimiento!='1969-12-31'){ $fecha_vencimiento = date('d-m-Y',strtotime($pagare->fecha_vencimiento)); } else { echo '-'; }
	$vencimiento_primera_cuota = date('d-m-Y',strtotime($pagare->vencimiento_primera_cuota));
	$vencimiento_restantes_cuotas = date('d-m-Y',strtotime($pagare->vencimiento_restantes_cuotas));
	$monto_deuda = $pagare->monto_deuda;
	$tasa_interes = $pagare->tasa_interes;
	$tasa_interes_anual = $pagare->tasa_interes_anual;
	$numero_cuotas = $pagare->numero_cuotas;
	$valor_primera_cuota = $pagare->valor_primera_cuota;	
	$valor_ultima_cuota = $pagare->valor_ultima_cuota;	
	$nombre_aval =  $pagare->nombre_aval;
	$ultima_cuota_pagada = $pagare->ultima_cuota_pagada;
	$fecha_pago_ultima_cuota = date('d-m-Y',strtotime($pagare->fecha_pago_ultima_cuota));
	$valor_ultima_cuota_pagada = $pagare->valor_ultima_cuota_pagada;
	$saldo_deuda = $pagare->saldo_deuda;
}
?>
<?php echo form_open(site_url().'/admin/gestion/guardar_pagare/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
<tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo pagaré:<?php else:?>Editar pagaré #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="pagares">Cerrar</a><?php endif;?></h3><br></td></tr>
<tr>
	<td>Tipo de Producto:</td>
	<td>
	<?php echo form_dropdown('id_tipo_producto', $tipos_productos, $id_tipo_producto,' class="" autocomplete="off" data-id="'.$id.'" ');?>
	<?php echo form_error('id_tipo_producto','<br><span class="error">','</span>');?>
	</td>
	<td></td>
	<td>
	</td>
</tr>
<tr>

 <?php    if($nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ) {     ?>
<td>Fecha Suscripción:</td><td><input type="text" class="datepicker" name="fecha_suscripcion" value="<?php echo $fecha_suscripcion?>"><?php echo form_error('fecha_suscripcion','<br><span class="error">','</span>');?></td>
<?php } ?>


    <?php    if($nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ) {     ?>
        <td>Fecha Asignacion:</td><td><input type="text" class="datepicker" name="fecha_asignacion" value="<?php echo $fecha_asignacion?>"><?php echo form_error('fecha_asignacion','<br><span class="error">','</span>');?></td>
    <?php } ?>


    <td>Fecha Vencimiento:</td><td><input type="text" class="datepicker" name="fecha_vencimiento" value="<?php echo $fecha_vencimiento?>"><?php echo form_error('fecha_vencimiento','<br><span class="error">','</span>');?></td></tr>
<tr>
<td>Nº Pagaré:</td><td><input type="text" class="n_pagare" name="n_pagare" value="<?php echo $n_pagare?>"><?php echo form_error('n_pagare','<br><span class="error">','</span>');?></td>
<td>Monto Pagaré:</td><td><?php echo form_input(array('name'=>'monto_deuda','style'=>'width:135px'), $monto_deuda);?>
<?php echo form_error('monto_deuda','<br><span class="error">','</span>');?></td>
</tr>

<?php if($nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ){ ?>
<td>Tasa de Interés Anual:</td><td><?php echo form_input(array('name'=>'tasa_interes_anual','style'=>'width:135px'), $tasa_interes_anual);?></td>
<?php } ?>

<?php if($nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ){ ?>
<div class="blq">
<tr>
<td>Tasa de Interés:</td><td><?php echo form_input(array('name'=>'tasa_interes','style'=>'width:135px'), $tasa_interes);?></td>

<td>Número de Cuotas:</td><td><?php echo form_input(array('name'=>'numero_cuotas','style'=>'width:135px'), $numero_cuotas);?></td>
</tr>

<tr>
<td>Valor primera cuota:</td><td><?php echo form_input(array('name'=>'valor_primera_cuota','style'=>'width:135px'), $valor_primera_cuota);?></td>
<td>Valor ultima cuota:</td><td><?php echo form_input(array('name'=>'valor_ultima_cuota','style'=>'width:135px'), $valor_ultima_cuota);?></td>
</tr>

<tr>
<td>Vencimiento primera cuota:</td><td><input type="text" class="datepicker" name="vencimiento_primera_cuota" value="<?php echo $vencimiento_primera_cuota?>"><?php echo form_error('vencimiento_primera_cuota','<br><span class="error">','</span>');?></td>
<td>Vencimiento restantes cuotas:</td><td><input type="text" class="datepicker" name="vencimiento_restantes_cuotas" value="<?php echo $vencimiento_restantes_cuotas?>"><?php echo form_error('vencimiento_restantes_cuotas','<br><span class="error">','</span>');?></td></tr>
<tr>

<tr>
<td>Nombre del aval:</td><td><?php echo form_input(array('name'=>'nombre_aval','style'=>'width:135px'), $nombre_aval);?></td>

<td>Última cuota pagada:</td><td><input type="text" class="ultima_cuota_pagada" name="ultima_cuota_pagada" value="<?php echo $ultima_cuota_pagada?>"><?php echo form_error('ultima_cuota_pagada','<br><span class="error">','</span>');?></td>
</tr>

<tr>
<td>Fecha pago última cuota:</td><td><input type="text" class="datepicker" name="fecha_pago_ultima_cuota" value="<?php echo $fecha_pago_ultima_cuota?>"><?php echo form_error('fecha_pago_ultima_cuota','<br><span class="error">','</span>');?></td>


<td>Valor última cuota pagada:</td><td><input type="text" class="valor_ultima_cuota_pagada" name="valor_ultima_cuota_pagada" value="<?php echo $valor_ultima_cuota_pagada?>"><?php echo form_error('valor_ultima_cuota_pagada','<br><span class="error">','</span>');?></td>
</tr>

<td>Saldo deuda:</td><td><input type="text" class="saldo_deuda" name="saldo_deuda" value="<?php echo $saldo_deuda?>"><?php echo form_error('saldo_deuda','<br><span class="error">','</span>');?></td>

</div>

<?php } ?>

<tr><td colspan="4"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
 </table>
<?php echo form_close();?>
<script type="text/javascript">
$(document).ready(function(){
	$(".datepicker").datepicker({ format: 'dd-mm-yyyy',});
});
</script>