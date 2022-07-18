<?php 
$observacion_b = $this->input->post('observacion');
$otro_b        = $this->input->post('otros');

if ($idregistro!=''){
    $observacion_b = $otrosbienes->observacion;
    $otro_b        = $otrosbienes->otros;
}

?>
<!--Para función SELECT2-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<?php echo form_open(site_url().'/admin/gestion/guardar_bienes/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
<tr>
    <td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo Bien:<?php else:?>Editar Otros Bienes #<?php echo $idregistro;?> <a href="#" class="closed" style="float:right;" data-gtab="otrosbienes">Cerrar</a><?php endif;?></h3><br>
    </td>
</tr>
<tr>
    <td>Otros Bienes:</td>
    <td>
        <input type="text" name="otros" style="width:500px;" value="<?php echo $otro_b;?>">
        <?php echo form_error('otros','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Observación:</td>
    <td>
        <textarea name="observacion"><?php echo $observacion_b;?></textarea>
        <?php echo form_error('observacion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>
        <input id="id_cuenta_b" name="id_cuenta_b" type="hidden" value="<?php echo $idcuenta_b; ?>">
    </td>
</tr>
<tr>
    <td colspan="2"><br>
        <input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right">
    </td>
</tr>
</table>
<?php echo form_close(); ?>