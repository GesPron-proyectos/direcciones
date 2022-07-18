<?php


        $config['smtp_crypto'] = 'tls'; 

       //Indicamos el protocolo a utilizar
        $config['protocol'] = 'smtp';
         
       //El servidor de correo que utilizaremos
        $config["smtp_host"] = 'smtp.office365.com';
         
       //Nuestro usuario
        $config["smtp_user"] = 'prueba@gespron.cl';
         
       //Nuestra contraseña
        $config["smtp_pass"] = 'Gespron2020';   
         
       //El puerto que utilizará el servidor smtp
        $config["smtp_port"] = '587';
        
       //El juego de caracteres a utilizar
        $config['charset'] = 'utf-8';
 
       //Permitimos que se puedan cortar palabras
        $config['wordwrap'] = TRUE;
         
       //El email debe ser valido 
       $config['validate'] = true;