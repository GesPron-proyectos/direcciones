<?php 
$tipo = $this->input->post('tipo');
$observacion = $this->input->post('observacion');
$tipo_vehiculo = $this->input->post('tipo_vehiculo');
$marca = $this->input->post('marca');
$modelo = $this->input->post('modelo');
$n_motor = $this->input->post('n_motor');
$color = $this->input->post('color');
$inscripcion = $this->input->post('inscripcion');
if ($idregistro!=''){
	$tipo = $bien->tipo;
	$observacion = $bien->observacion;
	$tipo_vehiculo = $bien->tipo_vehiculo;
	$marca = $bien->marca;
	$modelo = $bien->modelo;
	$n_motor = $bien->n_motor;
	$color = $bien->color;
	$inscripcion = $bien->inscripcion;
}
?>
<?php echo form_open(site_url().'/admin/gestion/guardar_bien/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
<tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo bien:<?php else:?>Editar bien #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="bienes">Cerrar</a><?php endif;?></h3><br></td></tr>
<tr>
    <td>Tipo:</td>
    <td>
    <?php echo form_dropdown('tipo', $tipos_bienes,  $tipo);?>
    <?php echo form_error('tipo','<br><span class="error">','</span>');?>
    </td>
</tr>

<tr>
    <td>Observación:</td>
    <td>
    <input name="observacion" value="<?php echo $observacion;?>">
    <?php echo form_error('observacion','<br><span class="error">','</span>');?>
    </td>
</tr>
<?php if($nodo->nombre == 'fullpay'):?>
<tr class="vehiculo"<?php if ($tipo!=1){echo ' style="display:none"';}?>>
    <td>Tipo Vehículo:</td>
    <td>
    <?php echo form_dropdown('tipo_vehiculo', $tipos_vehiculos,  $tipo);?>
    <?php echo form_error('tipo_vehiculo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr class="vehiculo"<?php if ($tipo!=1){echo ' style="display:none"';}?>>
    <td>Marca:</td>
    <td>
    <input name="marca" value="<?php echo $marca;?>">
    <?php echo form_error('marca','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr class="vehiculo"<?php if ($tipo!=1){echo ' style="display:none"';}?>>
    <td>Modelo:</td>
    <td>
    <input name="modelo" value="<?php echo $modelo;?>">
    <?php echo form_error('modelo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr class="vehiculo"<?php if ($tipo!=1){echo ' style="display:none"';}?>>
    <td>Nº motor:</td>
    <td>
    <input name="n_motor" value="<?php echo $n_motor;?>">
    <?php echo form_error('n_motor','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr class="vehiculo"<?php if ($tipo!=1){echo ' style="display:none"';}?>>
    <td>Color:</td>
    <td>
    <input name="color" value="<?php echo $color;?>">
    <?php echo form_error('color','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr class="vehiculo"<?php if ($tipo!=1){echo ' style="display:none"';}?>>
    <td>Inscripción:</td>
    <td>
    <input name="inscripcion" value="<?php echo $inscripcion;?>">
    <?php echo form_error('inscripcion','<br><span class="error">','</span>');?>
    </td>
</tr>
<?php endif;?>
<tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
</table>
<?php echo form_close();?>