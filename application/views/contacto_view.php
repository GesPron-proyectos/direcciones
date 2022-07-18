<h2>Contacto</h2>
<form action="<?=base_url("contacto/enviar")?>" method="post">
    Correo electronico: <br/>
    <input type="email" name="email" /><br/>
    Asunto: <br/>
    <input type="text" name="asunto" /><br/>
    Mensaje:<br/>
    <textarea name="mensaje"></textarea><br/>
    <input type="submit" name="submit" value="Enviar"/>



</form>
<?php
$this->system->sendmail2($to, $subject, $message);

?>
