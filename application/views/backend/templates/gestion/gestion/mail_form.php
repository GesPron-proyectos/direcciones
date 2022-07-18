<?php 
$mail_cuenta = $this->input->post('mail');
if ($idregistro!=''){
	$mail_cuenta = $mail->mail;
	}
?>  
  
  
  
  <?php echo form_open(site_url().'/admin/gestion/guardar_mail/'.$id.'/'.$idregistro); ?>
  <table class="stable" width="100%">
  <tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar un nuevo Mail:<?php else:?>Editar mail #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="mail">Cerrar</a><?php endif;?></h3><br></td></tr>
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