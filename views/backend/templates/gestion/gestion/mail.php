
<?php $this->load->view('backend/templates/gestion/gestion/mail_form');?>

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
               <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>Gestión</td>
                <?php endif;?>
            </tr>
          <?php if (count($mail)>0):?>
          <?php foreach ($mail as $key=>$mail):?>
              <tr>
              <td><?php echo $mail->id;?></td>
              <td><?php echo $mail->mail;?></td>
                <td><?php echo form_dropdown('estado', $estados,  $mail->estado,'class="estado" id="'.$mail->id.'" data-tipo="mail"');?><div style="display:inline" id="response_mail_<?php echo $mail->id;?>"></div></td>
              
       <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
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