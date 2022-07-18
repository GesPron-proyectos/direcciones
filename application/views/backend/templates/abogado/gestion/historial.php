<table class="stable" width="100%">
    <?php echo form_open(site_url().'/admin/procurador/guardar_historial/'.$id); ?>
    <tr><td colspan="4"><h3>Ingresar un nueva observación al historial:</h3><br></td></tr>
    <tr>
        <td>Observación:</td>
        <td>
        <textarea name="observaciones" style="width:180px"><?php echo $this->input->post('observaciones');?></textarea>
        <?php echo form_error('observaciones','<br><span class="error">','</span>');?>
        </td>
    </tr>
    <tr>
    
    <tr><td colspan="2"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
    <?php echo form_close();?>
    <tr><td colspan="2"><hr style="border:1px solid #CDCCCC"><br><h3>Historial:</h3><br></td></tr>
    <tr>
        <td colspan="2">
        <table class="stable grilla" width="100%">
            <tr class="titulos-tabla">
                <td>Fecha</td>
                <td>Observación</td>
                <td>Usuario</td>
            </tr>
            <?php if (count($historiales)>0):?>
            <?php foreach ($historiales as $key=>$historial):?>
                <tr>
                <td><?php echo date('d-m-Y H:i:s',strtotime($historial->fecha));?></td>
                <td><?php echo $historial->observaciones;?></td>
                <td><?php echo $historial->nombres.' '.$historial->apellidos;?></td>
                </tr>
            <?php endforeach;?>
            <?php else:?>
            <tr><td colspan="4">No hay registros ingresados</td></tr>
            <?php endif;?>
        </table>
    </td></tr>
</table>