<?php 

    $observacion                       = $this->input->post('observacion');
    $n_motor                           = $this->input->post('n_motor');
    $n_chasis                          = $this->input->post('n_chasis');
    $patente                           = $this->input->post('patente');
    $anio_bienes_idanio_bienes         = $this->input->post('anio_bienes_idanio_bienes');
    $inscripcion                       = $this->input->post('inscripcion');
    $modelo_vehiculo_idmodelo_vehiculo = $this->input->post('modelo_vehiculo_idmodelo_vehiculo');
    $marca_vehiculo_idmarca_vehiculo   = $this->input->post('marca_vehiculo_idmarca_vehiculo');
    $estado_vehiculo_idestado_vehiculo = $this->input->post('estado_vehiculo_idestado_vehiculo');
    $color_vehiculo_idcolor_vehiculo   = $this->input->post('color_vehiculo_idcolor_vehiculo');
    $tipo_vehiculo_idtipo_vehiculo     = $this->input->post('tipo_vehiculo_idtipo_vehiculo');

if ($idregistro!=''){
	$observacion                       = $vehiculos->observacion;
	$n_motor                           = $vehiculos->n_motor;
    $n_chasis                          = $vehiculos->n_chasis;
	$patente                           = $vehiculos->patente;
    $inscripcion                       = $vehiculos->inscripcion;
	$tipo_vehiculo_idtipo_vehiculo     = $vehiculos->tipo_vehiculo_idtipo_vehiculo;
	$modelo_vehiculo_idmodelo_vehiculo = $vehiculos->modelo_vehiculo_idmodelo_vehiculo;
	$color_vehiculo_idcolor_vehiculo   = $vehiculos->color_vehiculo_idcolor_vehiculo;
	$anio_bienes_idanio_bienes         = $vehiculos->anio_bienes_idanio_bienes;
    $marca_vehiculo_idmarca_vehiculo   = $vehiculos->marca_vehiculo_idmarca_vehiculo; 
    $estado_vehiculo_idestado_vehiculo = $vehiculos->estado_vehiculo_idestado_vehiculo;

}
else{
?>
</br></br>
<div><u><h2>Vehículos</h2></u></span></div>
</br></br>
<?php } ?>
<?php echo form_open(site_url().'/admin/gestion/guardar_vehiculos/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%">
<tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo Vehículo:<?php else:?>Editar Vehículo #<?php echo $idregistro;?> <a href="#" class="closed" style="float:right;" data-gtab="vehiculos">Cerrar</a><?php endif;?></h3><br></td></tr>

<?php if($nodo->nombre == 'fullpay'):?>
    
<tr>
    <td>Tipo Vehículo:</td>
    <td>
        <?php echo form_dropdown('tipo_vehiculo_idtipo_vehiculo', $sct_tipo_vehiculo, $tipo_vehiculo_idtipo_vehiculo, 'id="select2_vehiculo"');?>
        <?php echo form_error('tipo_vehiculo_idtipo_vehiculo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Año:</td>
    <td>
    <?php echo form_dropdown('anio_bienes_idanio_bienes', $sct_anio, $anio_bienes_idanio_bienes, 'id="select2_anio"');?>
    <?php echo form_error('anio_bienes_idanio_bienes','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Estado:</td>
    <td>
        <?php echo form_dropdown('estado_vehiculo_idestado_vehiculo', $sct_estado, $estado_vehiculo_idestado_vehiculo,'id="select2_estado"');?>
        <?php echo form_error('estado_vehiculo_idestado_vehiculo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Marca:</td>
    <td>
        <?php echo form_dropdown('marca_vehiculo_idmarca_vehiculo', $sct_marca, $marca_vehiculo_idmarca_vehiculo, 'id="select2_marca"');?>
        <?php echo form_error('marca_vehiculo_idmarca_vehiculo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Modelo:</td>
    <td>
    <?php echo form_dropdown('modelo_vehiculo_idmodelo_vehiculo', $sct_modelo, $modelo_vehiculo_idmodelo_vehiculo, 'id="select2_modelo"');?>
    <?php echo form_error('modelo_vehiculo_idmodelo_vehiculo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Color:</td>
    <td>
        <?php echo form_dropdown('color_vehiculo_idcolor_vehiculo', $sct_color, $color_vehiculo_idcolor_vehiculo,'id="select2_color"');?>
        <?php echo form_error('color_vehiculo_idcolor_vehiculo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Patente:</td>
    <td>
    <input name="patente" value="<?php echo $patente;?>">
    <?php echo form_error('patente','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Nº Motor:</td>
    <td>
    <input name="n_motor" value="<?php echo $n_motor;?>">
    <?php echo form_error('n_motor','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Nº Chasis:</td>
    <td>
    <input name="n_chasis" value="<?php echo $n_chasis;?>">
    <?php echo form_error('n_chasis','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Inscripción:</td>
    <td>
    <input name="inscripcion" value="<?php echo $inscripcion;?>">
    <?php echo form_error('inscripcion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>
    <input id="id_cuenta_a" name="id_cuenta_a" type="hidden" value="<?php echo $idcuenta_a; ?>">
    </td>
</tr>
<tr>
    <td>Observación:</td>
    <td>
    <textarea name="observacion"><?php echo $observacion; ?></textarea>
    <?php echo form_error('observacion','<br><span class="error">','</span>');?>
    </td>
</tr>
<?php endif;?>
<tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
</table>
<?php echo form_close();?>

<script type="text/javascript">
    //Plugin Select2
    jQuery(document).ready(function($){

    $(document).ready(function() {
        $('#select2_tipo').select2({dropdownAutoWidth : true});
        
    });

    $(document).ready(function() {
        $('#select2_marca').select2({dropdownAutoWidth : true});
        
    });

    $(document).ready(function() {
        $('#select2_anio').select2({dropdownAutoWidth : true});
        
    });

    $(document).ready(function() {
        $('#select2_vehiculo').select2({dropdownAutoWidth : true});
        
    });

    $(document).ready(function() {
        $('#select2_modelo').select2({dropdownAutoWidth : true});
        
    });

    $(document).ready(function() {
        $('#select2_estado').select2({dropdownAutoWidth : true});
        
    });


    $(document).ready(function() {
        $('#select2_color').select2({dropdownAutoWidth : true});
        
    });

    $(document).ready(function() {
        //$('.mi-selector').select2({ width: 'resolve' });
    });

});
</script>