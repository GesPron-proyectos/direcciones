<table class="stable" width="100%">
<?php echo form_open(site_url().'/admin/procurador/guardar_telefono/'.$id); ?>
<tr><td colspan="4"><h3>Ingresar un nuevo teléfono:</h3><br></td></tr>
<tr>
    <td>Tipo:</td>
    <td>
    <?php echo form_dropdown('tipo', $tipos,  $this->input->post('tipo'));?>
    <?php echo form_error('tipo','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Teléfono:</td>
    <td>
    <input name="numero" value="<?php echo $this->input->post('numero');?>">
    <?php echo form_error('numero','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>
    <td>Observación:</td>
    <td>
    <input name="observacion" value="<?php echo $this->input->post('observacion');?>">
    <?php echo form_error('observacion','<br><span class="error">','</span>');?>
    </td>
</tr>
<tr>

<tr><td colspan="2"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
<?php echo form_close();?>
<tr><td colspan="2"><hr style="border:1px solid #CDCCCC"><br><h3>Direcciones:</h3><br></td></tr>
<tr>
    <td colspan="2">
    <table class="stable grilla" width="100%">
        <tr class="titulos-tabla">
            <td>Tipo</td>
            <td>Teléfono</td>
            <td>Observación</td>
            <td>Estado</td>
        </tr>
        <?php if (count($telefonos)>0):?>
        <?php foreach ($telefonos as $key=>$telefono):?>
            <tr>
            <td><?php echo $tipos[$telefono->tipo];?></td>
            <td><?php echo $telefono->numero;?></td>
            <td><?php echo $telefono->observacion;?></td>
            <td><?php echo form_dropdown('estado', $estados,  $telefono->estado,'class="estado" id="'.$telefono->id.'" data-tipo="telefono"');?><div style="display:inline" id="response_telefono_<?php echo $telefono->id;?>"></div></td>
            </tr>
        <?php endforeach;?>
        <?php else:?>
        <tr><td colspan="4">No hay registros ingresados</td></tr>
        <?php endif;?>
    </table>
</td></tr>
</table>