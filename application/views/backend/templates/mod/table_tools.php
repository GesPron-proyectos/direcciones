<td class="tools">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25">
    <a href="<?php echo site_url().'/admin/'.$current.'/form/editar/'.$row_current->id;?>" class="editar" title="editar"></a>

	</td>
    <td width="25">
    <?php  if ( $row_current->posicion != $posiciones[$row_current->field_categoria]['max_posicion'] and $row_current->field_categoria == $posiciones[$row_current->field_categoria]['field_categoria']):?>
    <a style="cursor:pointer;" class="subir xtool" rel="<?php echo $row_current->posicion?>" id="up/<?php echo $row_current->id;?>" title="field_categoria=<?php echo $row_current->field_categoria;?>&posicion"></a>
    <?php endif;?>
    </td>
    <td width="25">
    <?php if ( $row_current->posicion != $posiciones[$row_current->field_categoria]['min_posicion'] and $row_current->field_categoria == $posiciones[$row_current->field_categoria]['field_categoria'] ):?>
    <a style="cursor:pointer;" class="bajar xtool" rel="<?php echo $row_current->posicion?>" id="down/<?php echo $row_current->id;?>" title="field_categoria=<?php echo $row_current->field_categoria;?>&posicion"></a>
    <?php endif;?>
    </td>
    <td width="25">
    <?php if ($row_current->public=='S'):?>
    <a style="cursor:pointer;" class="publicado xtool" rel="N" id="actualizar/<?php echo $row_current->id;?>" title="public"></a>
    <?php else:?>
    <a style="cursor:pointer;" class="despublicado xtool" rel="S" id="actualizar/<?php echo $row_current->id;?>" title="public"></a>
    <?php endif;?>
    </td>
    <td width="25"><a style="cursor:pointer;" class="eliminar xtool" rel="N" id="actualizar/<?php echo $row_current->id;?>" title="activo"></a></td>
  </tr>
</table>

</td>