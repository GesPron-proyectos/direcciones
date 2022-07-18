<tr class="b" style="border-top:1px solid #CDCCCC; background-color:#EAEAEA;">
  <td colspan="<?php echo $colspan;?>"><input type="checkbox" class="check all" name="all-<?php echo $check_id;?>"><span>Seleccionar Todos</span></td>
  <td>&nbsp;</td>
  <td class="tools">
    <a style="cursor:pointer;" class="despublicado mtool" rel="<?php echo LANG_UNPUBLIC;?>" id="<?php echo $check_id;?>" title="despublicar"></a>
    <a style="cursor:pointer;" class="publicado mtool" rel="<?php echo LANG_PUBLIC;?>" id="<?php echo $check_id;?>" title="publicar"></a>
	<a style="cursor:pointer;" class="eliminar mtool" rel="<?php echo LANG_DISABLE;?>" id="<?php echo $check_id;?>" title="eliminar"></a>
  </td>
</tr>