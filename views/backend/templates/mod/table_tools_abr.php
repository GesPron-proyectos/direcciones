<td class="tools" width="100%">

<table width="75%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td width="15%" height="20">

    <a href="<?php 
    /*
if ($current == 'hist_cuentas')
    	echo site_url().'/admin/cuentas/form/editar/'.$row_current->id.'';
    else
*/  
	if ($current=='cuentas'){
		echo site_url('admin/gestion/index/'.$val->id);
	} else {
		echo site_url().'/admin/'.$current.'/form/editar/'.$row_current->id; 
	}
    ?>" class="editar" title="editar"></a>
	</td>

    <td width="15%">
    <?php if ($this->session->userdata("usuario_perfil")<=2):?>
    <a style="cursor:pointer;" class="eliminar xtool" rel="N" id="actualizar/<?php echo $row_current->id;?>" title="activo"></a>
    <?php endif;?>
    </td>

  </tr>

</table>



</td>