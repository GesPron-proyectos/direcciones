<?php $this->load->view('backend/templates/gestion/gestion/bienes_form');?>
<div id="box-form-bienes"></div>
<table class="stable" width="100%"> 
<tr><td colspan="2"><h3>Bienes:</h3><br></td></tr>
<tr>
    <td colspan="2">
    <table class="stable grilla" width="100%">
        <tr class="titulos-tabla">
        	<td>#</td>
            <td>Tipo</td>
            <td>Observación</td>
            <?php if($nodo->nombre == 'fullpay'):?>
            <td>Tipo vehículo</td>
            <td>Marca</td>
            <td>Modelo</td>
            <td>Nº Motor</td>
            <td>Color</td>
            <td>Inscripción</td>
            <?php endif;?>
            <td>Estado</td>
             <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
              <td>Gestión</td>
              <?php endif;?>
          </tr>
        <?php if (count($bienes)>0):?>
        <?php foreach ($bienes as $key=>$bien):?>
            <tr>
            <td>#<?php echo $bien->id;?></td>
            <td><?php echo $tipos_bienes[$bien->tipo];?></td>
            <td><?php echo $bien->observacion;?></td>
            <?php if($nodo->nombre == 'fullpay'):?>
            <td><?php echo $tipos_vehiculos[$bien->tipo_vehiculo];?></td>
            <td><?php echo $bien->marca;?></td>
            <td><?php echo $bien->modelo;?></td>
            <td><?php echo $bien->n_motor;?></td>
            <td><?php echo $bien->color;?></td>
            <td><?php echo $bien->inscripcion;?></td>
            <?php endif;?>
            <td><?php echo form_dropdown('estado', $estados,  $bien->estado, 'class="estado" id="'.$bien->id.'" data-tipo="bien"');?><div style="display:inline" id="response_bien_<?php echo $bien->id;?>"></div></td>
            
            <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
              <td>
               <a href="<?php echo site_url('admin/gestion/editar_bien/'.$id.'/'.$bien->id);?>" class="edit" data-id="<?php echo $bien->id;?>" data-gtab="bienes">Editar</a>
              <a href="<?php echo site_url('admin/gestion/eliminar_bien/'.$id.'/'.$bien->id);?>" class="delete">Eliminar</a></td>
              <?php endif;?>
           </tr>
        <?php endforeach;?>
        <?php else:?>
        <tr><td colspan="4">No hay registros ingresados</td></tr>
        <?php endif;?>
    </table>
</td></tr>
</table>