<?php
$color_bg = '';
$color = '';
$estado_msg = '';

?>

<?php if($nodo->nombre=='fullpay'){ ?> 
<?php if ($cuenta->id_estado_cuenta==1){ $color_bg = "#BDD95E"; $color = "#758B22"; $estado_msg = "VIGENTE"; }?>
<?php if ($cuenta->id_estado_cuenta==2){ $color_bg = "#999"; $color = "#666"; $estado_msg = "Rechazo INGRESA"; }?>
<?php if ($cuenta->id_estado_cuenta==3){ $color_bg = "#1186B6"; $color = "#00365F"; $estado_msg = "SUSPENDIDO"; }?>
<?php if ($cuenta->id_estado_cuenta==4){ $color_bg = "#DD808C"; $color = "#9E0404"; $estado_msg = "TERMINADO"; }?>
<?php if ($cuenta->id_estado_cuenta==5){ $color_bg = "#B8A0BD"; $color = "#7A318D"; $estado_msg = "DEVUELTO"; }?>
<?php if ($cuenta->id_estado_cuenta==6){ $color_bg = "#036"; $color = "#fff"; $estado_msg = "EXHORTO"; }?>
<?php if ($cuenta->id_estado_cuenta==7){ $color_bg = "#036"; $color = "#fff"; $estado_msg = "Dev. Documentos"; }?>
<?php if ($cuenta->id_estado_cuenta==8){ $color_bg = "#FF4500"; $color = "#fff"; $estado_msg = "AVENIMIENTO"; }?>
<?php if ($cuenta->id_estado_cuenta==9){ $color_bg = "#0078ff"; $color = "#fff"; $estado_msg = "EXCEPCIONES"; }?>
<?php if ($cuenta->id_estado_cuenta==10){ $color_bg = "#8FFF0F"; $color = "#fff"; $estado_msg = "REINGRESO"; }?>
<?php if ($cuenta->id_estado_cuenta==11){ $color_bg = "#28B463"; $color = "#fff"; $estado_msg = "LIQUIDACION"; }?>
<?php }?>

<?php if($nodo->nombre=='swcobranza'){ ?> 
<?php if ($cuenta->id_estado_cuenta==1){ $color_bg = "#BDD95E"; $color = "#758B22"; $estado_msg = "VIGENTE"; }?>
<?php if ($cuenta->id_estado_cuenta==2){ $color_bg = "#999"; $color = "#666"; $estado_msg = "ABANDONADO"; }?>
<?php if ($cuenta->id_estado_cuenta==3){ $color_bg = "#1186B6"; $color = "#00365F"; $estado_msg = "SUSPENDIDO"; }?>
<?php if ($cuenta->id_estado_cuenta==4){ $color_bg = "#DD808C"; $color = "#9E0404"; $estado_msg = "TERMINADO"; }?>
<?php if ($cuenta->id_estado_cuenta==5){ $color_bg = "#B8A0BD"; $color = "#7A318D"; $estado_msg = "CONVENIO"; }?>
<?php if ($cuenta->id_estado_cuenta==6){ $color_bg = "#EDB2AC"; $color = "#E64128"; $estado_msg = "CONVENIO INCUMPLIDO"; }?>
<?php if ($cuenta->id_estado_cuenta==7){ $color_bg = "#AFE3F6"; $color = "#00A8E7"; $estado_msg = "SUSPENDIDO CON CONVENIO"; }?>
<?php if ($cuenta->id_estado_cuenta==8){ $color_bg = "#59D2E4"; $color = "#3C9EE4"; $estado_msg = "GPVE RECHAZADA"; }?>
<?php if ($cuenta->id_estado_cuenta==9){ $color_bg = "#8E3E06"; $color = "#E9B431"; $estado_msg = "ABONO"; }?>
<?php }?>

<div style="width:500px; margin-left:5px; height:25px; border:1px solid <?php echo $color;?>; padding:5px; background:<?php echo $color_bg;?>;">
	<span style="color:<?php echo $color;?>; font-size:18px; text-align:center; float:left; width:490px;">ESTADO DE LA CUENTA: <?php echo $estado_msg;?></span>
</div>
<div class="clear"></div>
<table class="stable grilla" width="100%">
    <thead>
        <tr class="titulos-tabla">
            <td>#</td>
            <td>Usuario</td>
            <td>Estado de la Cuenta</td>
            <td>Fecha</td>
        </tr>
    </thead>
    <tbody>
        <?php $pos=1; foreach ($estados_cuentas as $key=>$value):?>
        <tr>
            <td><?php echo $pos++;?></td>
            <td><?php echo $value->nombres.' '.$value->apellidos;?></td>
            <td><?php echo $value->estado; ?></td>
            <td><?php echo date('d/m/Y', strtotime($value->fecha_crea)); ?></td>
        <tr>
        <?php endforeach; ?>
    </tbody>
</table>