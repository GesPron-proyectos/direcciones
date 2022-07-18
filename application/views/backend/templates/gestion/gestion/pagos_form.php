<?php if ($nodo->nombre=='fullpay'):?>

    <div style="border:1px solid #cdcccc; margin:5px; padding:10px;background-color: #dfdcd9;">
        <h3>Detalle Acuerdo:</h3>
        <a href="#" id="mostrar-detalle-acuerdo-abonos" style="float: right; margin-top: -13px; text-decoration: underline;">Mostrar/Ocultar</a>
        </td>
        </tr>
    </div>
    <div id="caja-detalle-acuerdo-abonos">
    <table class="stable table-destacado" width="100%">
        <tr>
            <?php $fecha_pagare = ''; $diferencia_dias=''; $diferencia_dias_primer_pago = '';?>
            <td><strong>Fecha Pagaré:</strong></td><td><?php if (count($pagares)>0){$i=1;foreach ($pagares as $pagare){ if ($i==1){ $fecha_pagare = $pagare->fecha_asignacion; $diferencia_dias = $pagare->diferencia_dias; $diferencia_dias_primer_pago = $pagare->diferencia_dias_primer_pago; echo date('d-m-Y',strtotime($pagare->fecha_suscripcion)); } $i++;}}?>
            <td><strong>Fecha Último Pago:</strong></td><td><?php if (count($pagos)>0){$i=1; foreach ($pagos as $pago){ if ($i==1){ echo date('d-m-Y',strtotime($pago->fecha_pago)); } $i++;}}?></td>
        </tr>
        <tr>
            <td><strong>Total Pagaré:</strong></td>
            <td> <?php if($cuenta->monto_deuda != ''){ ?>  <?php echo number_format($cuenta->monto_deuda,0,',','.');?>  <?php } ?> </td>
            <td><strong>Abonado hasta hoy:</strong></td>
            <td><?php echo number_format($cuenta->monto_pagado_new,0,',','.');?></td>
        </tr>
        <tr>
            <td><strong>Deuda:</strong></td>
            <td><span style="color:#9E0404"><?php  $deuda = $cuenta->monto_deuda-$cuenta->monto_pagado_new; echo number_format($cuenta->monto_deuda-$cuenta->monto_pagado_new,0,',','.');?></span></td>
            <td><strong>Intereses</strong>

                <?php echo form_open(site_url().'/admin/gestion/guardar_interes/'.$id); ?>
                <input name="intereses" value="<?php echo $cuenta->intereses;?>">
                <br><input type="submit" value="<?php if ($idregistro==''):?>Guardar<?php else:?>Editar<?php endif;?>" style="float:right">   <?php echo form_close();?>     </td>

            <?php $deuda = $cuenta->monto_deuda;?>

            <td>
                <?php
                $intereses = $cuenta->intereses;
                ?>
            </td>
        </tr>
        <tr>
            <td><strong>Total Gastos:</strong></td>
            <td><?php echo number_format($cuenta->monto_gasto_new,0,',','.');?></td>
        </tr>
        <tr>
            <td><strong>Deuda Total (deuda+gastos+intereses):</strong></td>
            <td><?php echo number_format($cuenta->monto_deuda-$cuenta->monto_pagado_new+$cuenta->monto_gasto_new+$intereses,0,',','.');?></td>
        </tr>
    </table>
    </div>
<?php endif; ?>


<?php echo form_open(site_url().'/admin/gestion/guardar_pagos/'.$id.'/'.$idregistro); ?>

<?php

$id_acuerdo_pago = $this->input->post('id_acuerdo_pago');
$fecha_pago = $this->input->post('fecha_pago');
if ($fecha_pago!=''){ $fecha_pago = date('d-m-Y',strtotime($fecha_pago));}
$monto_pagado = $this->input->post('monto_pagado');
$n_comprobante_interno = $this->input->post('n_comprobante_interno');
$forma_pago = $this->input->post('forma_pago');

if ($idregistro!=''){
    $id_acuerdo_pago = $pago->id_acuerdo_pago;
    $fecha_pago = date('d-m-Y',strtotime($pago->fecha_pago));
    $monto_pagado = $pago->monto_pagado;
    $n_comprobante_interno = $pago->n_comprobante_interno;
    $forma_pago = $pago->forma_pago;
}
?>
<div style="border:1px solid #cdcccc; margin:5px; padding:10px; background-color: #dfdcd9;">
    <h3>Ingresar Nuevo:</h3>
    <a href="#" id="mostrar-nuevo-abono" style="float: right; margin-top: -13px; text-decoration: underline;">Mostrar/Ocultar</a>
    </td>
    </tr>
</div>
<div id="caja-nuevo-abono">
<table class="stable" width="100%">
    <tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo abono:<?php else:?>Editar abono #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="pagos">Cerrar</a><?php endif;?></h3><br></td></tr>
    <tr>

        <td>Tipo de Abono:</td>
        <td>
            <?php echo form_dropdown('id_acuerdo_pago', $acuerdos_pago, $id_acuerdo_pago,' class="" autocomplete="off" data-id="'.$id.'" ');?>
            <?php echo form_error('id_acuerdo_pago','<br><span class="error">','</span>');?>
        </td>
        <td>Fecha:</td><td><input type="text" class="datepicker" name="fecha_pago" value="<?php echo $fecha_pago?>"><?php echo form_error('fecha_pago','<br><span class="error">','</span>');?></td>
    </tr>
    <tr>
        <td>Monto:</td><td><?php echo form_input(array('name'=>'monto_pagado','style'=>'width:135px'), $monto_pagado);?>
            <?php echo form_error('monto_pagado','<br><span class="error">','</span>');?>
        <td>Nº Comprobante:</td><td><?php echo form_input(array('name'=>'n_comprobante_interno','style'=>'width:135px'), $n_comprobante_interno);?>
            <?php echo form_error('n_comprobante_interno','<br><span class="error">','</span>');?></td></tr>
    <tr>
        <td>Forma de Pago:</td>
        <td>
            <?php echo form_dropdown('forma_pago', $formas_pago, $forma_pago,' class="" autocomplete="off" data-id="'.$id.'" ');?>
            <?php echo form_error('forma_pago','<br><span class="error">','</span>');?>
        </td>
    </tr>
    
			<?php  if( $this->session->userdata("usuario_perfil") ==  1  && $nodo->nombre == 'swcobranza'):?>    
    <tr><td colspan="4"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
			<?php endif; ?>
            
            <?php  if( $nodo->nombre == 'fullpay'):?>    
    <tr><td colspan="4"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
			<?php endif; ?>

</table>
</div>
<?php echo form_close();?>
<script type="text/javascript">
    $(document).ready(function(){
        $(".datepicker").datepicker({ format: 'dd-mm-yyyy',});
        $(document).on("click","#mostrar-detalle-acuerdo-abonos",function(e){
            if ($("#caja-detalle-acuerdo-abonos").is(":visible")){
                $("#caja-detalle-acuerdo-abonos").hide();
            } else {
                $("#caja-detalle-acuerdo-abonos").show();
            }
            return false;
        });
        $(document).on("click","#mostrar-nuevo-abono",function(e){
            if ($("#caja-nuevo-abono").is(":visible")){
                $("#caja-nuevo-abono").hide();
            } else {
                $("#caja-nuevo-abono").show();
            }
            return false;
        });
    });
</script>