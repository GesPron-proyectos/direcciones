<?php 
$observacion_in = $this->input->post('observacion');
$direccion_in   = $this->input->post('direccion');
$id_comuna_in   = $this->input->post('comuna_idcomuna');
$avaluo_in      = $this->input->post('avaluo');
$inscripcion_in = $this->input->post('inscripcion_idinscripcion');

if ($idregistro!=''){
    $observacion_in = $inmueble->observacion;
    $direccion_in   = $inmueble->direccion;
    $id_comuna_in   = $inmueble->comuna_idcomuna;
    $avaluo_in      = $inmueble->avaluo;
    $inscripcion_in = $inmueble->inscripcion_idinscripcion;
}
?>
<?php echo form_open(site_url().'/admin/gestion/guardar_bien_inmueble/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
<tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo Inmueble:<?php else:?>Editar Inmueble #<?php echo $idregistro;?> <a href="#" class="closed" style="float:right;" data-gtab="inmueble">Cerrar</a><?php endif;?></h3><br></td></tr>
<tr>
    <td>Direccion:</td>
    <td>
    <input name="direccion" style="width:338px;" value="<?php echo $direccion_in;?>">
    <?php echo form_error('direccion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Comuna:</td>
    <td>
    <?php echo form_dropdown('comuna_idcomuna', $sct_comuna, $id_comuna_in,' class="mi-selector" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('comuna_idcomuna','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Inscripción:</td>
    <td>
    <?php echo form_dropdown('inscripcion_idinscripcion', $sct_inscripcion, $inscripcion_in,' class="mi-selector" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('inscripcion_idinscripcion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Avalúo:</td>
    <td>
    <input id="avaluo" name="avaluo" value="<?php echo $avaluo_in;?>">
    <?php echo form_error('avaluo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Observación:</td>
    <td>
    <textarea name="observacion"><?php echo $observacion_in; ?></textarea>
    <?php echo form_error('observacion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
</table>
<?php echo form_close();?>
<script>
    //función para puntos en montos
    $('#avaluo').keyup(function(event) {
        $(this).val(function(index, value) {
            return value
            .replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
});
</script>