<table class="stable" width="100%">
    <?php echo form_open(site_url().'/admin/procurador/guardar_cuenta/'.$id); ?>
    <tr><td colspan="4"><h3>Actualizar datos de la Cuenta:</h3><br></td></tr>
    
    <tr><td>Receptor:</td><td><?php echo form_dropdown('receptor', $receptores, $cuenta->receptor);?></td></tr>
    <tr>
        <td>Distrito:</td><td>
            <?php echo form_dropdown('id_tribunal', $tribunales, $cuenta->id_tribunal);?>
        </td>
    </tr>
    <tr>
        <td>Juzgado:</td>
        <td>
        <div id="ajax_id_distrito">
             <?php echo form_dropdown('id_distrito', $distritos, $cuenta->id_distrito);?>     
        </div><!-- ajax_id_distrito -->
        </td>
    </tr>
    <tr>
        <td>Rol:</td>
        <td>
            <input id="rol" name="rol" type="text" value="<?php echo $cuenta->rol;?>" style="width: 150px;">
        </td>
    </tr>
    <tr><td colspan="2"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
    <?php echo form_close();?>
</table>