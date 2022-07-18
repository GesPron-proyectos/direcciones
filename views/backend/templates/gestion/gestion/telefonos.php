<?php 
    $mail_cuenta = $this->input->post('mail');
if ($idregistro!=''){
	
	$mail_cuenta = $mail->mail;
	}
?>  



<?php $this->load->view('backend/templates/gestion/gestion/telefonos_form');?>
<div id="box-form-telefonos">  </div>
<table class="stable" width="100%">
<tr><td colspan="2"><h3>Teléfono:</h3><br></td></tr>
<tr>
    <td colspan="2">
    <table class="stable grilla" width="100%">
        <tr class="titulos-tabla">
            <td>#</td>
            <td>Tipo</td>
            <td>Teléfono</td>
            <td>Observación</td>
            <td>Estado</td>
             <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>Gestión</td>
                <?php endif;?>
         </tr>
        <?php if (count($telefonos)>0):?>
        <?php foreach ($telefonos as $key=>$telefono):?>
            <tr>
            <td>#<?php echo $telefono->id;?></td>
            <td><?php echo $tipos[$telefono->tipo];?></td>
            <td><?php echo $telefono->numero;?></td>
            <td><?php echo $telefono->observacion;?></td>
            <td><?php echo form_dropdown('estado', $estados,  $telefono->estado,'class="estado" id="'.$telefono->id.'" data-tipo="telefono"');?><div style="display:inline" id="response_telefono_<?php echo $telefono->id;?>"></div></td>
             <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>
                <a href="<?php echo site_url('admin/gestion/editar_telefono/'.$id.'/'.$telefono->id); ?>" class="edit"  data-id="<?php echo $telefono->id;?>" data-gtab="telefonos" >Editar</a>
                <a href="<?php echo site_url('admin/gestion/eliminar_telefono/'.$id.'/'.$telefono->id);?>" class="delete">Eliminar</a></td>
                <?php endif;?>
            </tr>
         <?php endforeach;?>
        <?php else:?>
        <tr><td colspan="4">No hay registros ingresados</td></tr>
        <?php endif;?>
    </table>
</td></tr>
</table>


</ hr>

<?php if($nodo->nombre=='fullpay'){ ?>
<?php echo form_open(site_url().'/admin/gestion/guardar_mail/'.$id.'/'.$idregistro); ?>
  <table class="stable" width="100%">
  <tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo bien:<?php else:?>Editar mail #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="mail">Cerrar</a><?php endif;?></h3><br></td></tr>
  <tr>
      <td>Mail:</td>
      <td>
      <input name="mail" value="<?php echo $mail_cuenta;?>">
    <?php echo form_error('mail','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
  
  <tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
 
 </table>
  <?php echo form_close();?>


<div id="box-form-mail"></div>
  <table class="stable" width="100%">
  <tr><td colspan="2"><h3>Mails:</h3><br></td></tr>
  <tr>
      <td colspan="2">
      <table class="stable grilla" width="100%">
          <tr class="titulos-tabla">
             <td>#</td>
                <td>Mail</td>
                  <td>Estado</td>
               <?php if ($this->session->userdata("usuario_perfil")==1):?>
                <td>Gestión</td>
                <?php endif;?>
            </tr>
          <?php if (count($mail)>0):?>
          <?php foreach ($mail as $key=>$mail):?>
              <tr>
              <td><?php echo $mail->id;?></td>
              <td><?php echo $mail->mail;?></td>
                <td><?php echo form_dropdown('estado', $estados,  $mail->estado,'class="estado" id="'.$mail->id.'" data-tipo="mail"');?><div style="display:inline" id="response_mail_<?php echo $mail->id;?>"></div></td>
              
       <?php if ($this->session->userdata("usuario_perfil")==1):?>
                <td>
                 <a href="<?php echo site_url('admin/gestion/editar_mail/'.$id.'/'.$mail->id);?>" class="edit"  data-id="<?php echo $mail->id;?>" data-gtab="mail" >Editar</a>
                 <a href="<?php echo site_url('admin/gestion/eliminar_mail/'.$id.'/'.$mail->id);?>" class="delete">Eliminar</a></td>
                <?php endif;?>
             </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="4">No hay registros ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>

<?php }?>