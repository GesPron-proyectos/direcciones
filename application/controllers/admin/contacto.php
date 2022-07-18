<?php
class contacto extends CI_Controller {
   public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }
     
   public function index(){
        $this->load->view('contacto_view');
   }
    
 

public  function sendmail2($to,$subject,$message,$reply=null,$nameReply=null){

  $this->load->library("MY_phpmailer");
   // $this->CI->load->library('MY_phpmailer');

    //$this->load->library('MY_phpmailer'); (If you use this function inside CI, use this instead above) I use CI->load above because my function is in a function_helper, not on controller

    $mail = new PHPMailer();
    $mail->IsSMTP(); //Definimos que usaremos o protocolo SMTP para envio.
    $mail->SMTPDebug = 0;
    $mail->CharSet = 'UTF-8';
    $mail->SMTPAuth = true; //Habilitamos a autenticaçăo do SMTP. (true ou false)
    $mail->SMTPSecure = "tls"; //Estabelecemos qual protocolo de segurança será usado.
    $mail->Host = "smtp.office365.com"; //Podemos usar o servidor do gMail para enviar.
    $mail->Port = 587; //Estabelecemos a porta utilizada pelo servidor do gMail.
    $mail->Username = "prueba@gespron.cl"; //Usuário do gMail
    $mail->Password = "Gespron2020"; //Senha do gMail

    if($reply != null and $nameReply != null){
        //add replyto if you send those values, so you can set other reply address than senders address
        $mail->AddReplyTo($reply, $nameReply);
    }

    $mail->SetFrom('prueba@gespron.cl', 'PRUEBA'); //Quem está enviando o e-mail.
    $mail->Subject =  $subject;
    $mail->IsHTML(true); 
    $mail->Body = $message;
    //$mail->AltBody = "Plain text.";
    $mail->AddAddress($to);

    /*If you want to put attachments that comes from a input FILE type name='file'.*/
    if (isset($_FILES['file']) &&
        $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $mail->AddAttachment($_FILES['file']['tmp_name'],
                             $_FILES['file']['name']);
    }

    if(!$mail->Send()) {
        // error occur - user your show method to show error
        set_msg('msgerro', $mail->ErrorInfo, 'erro');
    } else {
        // success occur - user your show method to show success
        set_msg('msgok', 'E-mail enviado.', 'sucesso');
    }

}




}


 
?>
