<?php echo form_open(site_url().'/admin/gestion/guardar_historial/'.$id.'/'.$idregistro); ?>
<?php 

$observaciones = $this->input->post('observaciones');

if ($idregistro!=''){
	$observaciones = $historial->observaciones;
}
?>
<table class="stable" width="100%">
<tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nueva observación al historial:<?php else:?>Editar historial #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="historiales">Cerrar</a><?php endif;?></h3><br></td></tr>
<tr>
    <td>Observación:</td>
    <td>
    <textarea name="observaciones" style="width:180px"><?php echo $observaciones;?></textarea>
    <?php echo form_error('observaciones','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>

<tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
</table>
<?php echo form_close();?>
