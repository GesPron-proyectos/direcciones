<?php $this->load->view('backend/templates/gestion/gestion/historiales_form'); ?>
<div id="box-form-historiales"></div>
<table class="stable" width="100%">
   <tr><td colspan="2"><h3>Historial:</h3><br></td></tr>
    <tr>
        <td colspan="2">
        <table class="stable grilla" width="100%">
            <tr class="titulos-tabla">
             <td>#</td>
                <td>Fecha</td>
                <td>Observación</td>
                <td>Usuario</td>
                <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2 ):?>
                <td>Gestión</td>
                <?php endif;?>
            </tr>
            <?php if (count($historiales)>0):?>
            <?php foreach ($historiales as $key=>$historial):?>
                <tr>
                 <td>#<?php echo $historial->id;?></td>
                <td><?php echo date('d-m-Y H:i:s',strtotime($historial->fecha));?></td>
                <td><?php echo $historial->observaciones;?></td>
                <td><?php echo $historial->nombres.' '.$historial->apellidos;?></td>
                <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>
                
              <a href="<?php echo site_url('admin/gestion/editar_historial/'.$id.'/'.$historial->id);?>" class="edit"  data-id="<?php echo $historial->id;?>" data-gtab="historiales" >Editar</a>
             <a href="<?php echo site_url('admin/gestion/eliminar_historial/'.$id.'/'.$historial->id);?>" class="delete">Eliminar</a></td>
                <?php endif;?>
                </tr>
            <?php endforeach;?>
            <?php else:?>
            <tr><td colspan="4">No hay registros ingresados</td></tr>
            <?php endif;?>
        </table>
    </td></tr>
</table>