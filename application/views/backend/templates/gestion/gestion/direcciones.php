<?php $this->load->view('backend/templates/gestion/gestion/direccion_form');?>

<div id="box-form-direccion"></div>
  <table class="stable" width="100%">

   <tr><td colspan="2"><h3>Direcciones:</h3><br></td></tr>
  <tr>
      <td colspan="2">
      <table class="stable grilla" width="100%">
          <tr class="titulos-tabla">
           <td>#</td>
              <td>Dirección</td>
              <td>Comunas</td>
              <td>Observación</td>
              <td>Estado</td>
              <td>Tipo Direccón</td>
               <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>Gestión</td>
                <?php endif;?>
          </tr>
          <?php if (count($direcciones)>0):?>
          <?php foreach ($direcciones as $key=>$direccion):?>
              <tr>
               <td>#<?php echo $direccion->id;?></td>
              <td><?php echo $direccion->direccion;?></td>
              <td><?php echo $direccion->nombre_comuna;?></td>
              <td><?php echo $direccion->observacion;?></td>
              <td><?php echo form_dropdown('estado', $estados,$direccion->estado,'class="estado" id="'.$direccion->id.'" data-tipo="direccion"');?><div style="display:inline" id="response_direccion_<?php echo $direccion->id;?>"></div>
              </td>
              <td><?php echo form_dropdown('tipo', $tipos_direcciones,$direccion->tipo,'class="tipo" id="'.$direccion->id.'" data-td="dir"');?><div style="display:inline" id="response_dir_<?php echo $direccion->id;?>"></div>
              </td>
              
              <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>
                 <a href="<?php echo site_url('admin/gestion/editar_direccion/'.$id.'/'.$direccion->id);?>" class="edit"  data-id="<?php echo $direccion->id;?>" data-gtab="direccion" >Editar</a>
                <a href="<?php echo site_url('admin/gestion/eliminar_direccion/'.$id.'/'.$direccion->id);?>" class="delete">Eliminar</a></td>
                <?php endif;?>

              <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="4">No hay registros ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>
