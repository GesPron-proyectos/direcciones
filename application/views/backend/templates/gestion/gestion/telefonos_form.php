 <?php 
    $tipo_m = $this->input->post('tipo');
	 $observacion_m = $this->input->post('observacion');
	 $numero_m = $this->input->post('numero');
	 $mail_cuenta = $this->input->post('mail');
if ($idregistro!=''){
	$direccion_m = $telefono->tipo;
	$observacion_m  = $telefono->observacion;
	$numero_m  = $telefono->numero;
	$mail_cuenta = $mail->mail;
	}
?>  

<?php echo form_open(site_url().'/admin/gestion/guardar_telefono/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
<tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo teléfono:<?php else:?>Editar teléfono #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="telefonos">Cerrar</a><?php endif;?></h3><br></td></tr>
<tr>
    <td>Tipo:</td>
    <td>
    <?php echo form_dropdown('tipo', $tipos, $tipo_m);?>
    <?php echo form_error('tipo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Teléfono:</td>
    <td>
    <input name="numero" value="<?php echo $numero_m;?>">
    <?php echo form_error('numero','<br><span class="error">','</span>');?>
    </td>
</tr>

<tr>
    <td>Observación:</td>
    <td>
    <input name="observacion" value="<?php echo $observacion_m;?>">
    <?php echo form_error('observacion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
<tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
</table>
<?php echo form_close();?>

