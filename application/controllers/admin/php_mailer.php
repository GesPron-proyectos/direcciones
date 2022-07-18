<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Php_mailer extends CI_Controller {

    public function Php_mailer() { $this->__construct(); }

    function __construct(){
        parent::__construct();
        $this->load->library('session');

        //if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login'); }

        $this->load->model('procurador_m');
        
        $this->load->model('cuentas_m');
        $this->load->model('correo_m');
        $this->load->model('receptor_pjud');
    }
    
    function enviar_correo($data){ //print_r($data); die;

        $this->load->library('PHPMailerAutoload');
		$this->load->model('reg_correo_m');
		
        $this->reg_correo_m->delete_all();

        $array_abogs = array();
        $fecha_tramite = '';
        foreach ($data as $key => $value){
            foreach($value as $k => $v){
                foreach ($v as $w => $x){
                    if ($x->fecha!='' && $x->fecha!='0000-00-00')
                        $fecha_tramite = date("d-m-Y", strtotime($x->fecha));

                    if(isset($x->ubicacion)){
                        $procurador = $this->procurador_m->get_by(array('id' => $key));
                        $email = $procurador->correo;
                        $array_abogs[] = $email;
                    }
                }
            }
        }

        $abog = false;
        $arr_ids = $arr_ids_na = array(); 
        $enviados = $no_enviados = $causas_no_enviadas = $causas_enviadas = 0;
        foreach ($data as $key => $value){
            $procurador = $this->procurador_m->get_by(array('id' => $key));

            $email = $procurador->correo;

            $mail = new PHPMailer();
            //$mail->SMTPDebug = 2;
            //$mail->Debugoutput = 'html';

            $causas1 = $causas2 = 0;
            $cuerpo1 = $cuerpo2 = '';
            foreach($value as $s => $t){
                $find = $apel = false;
                foreach ($t as $x => $y){
					if(!in_array($y->id, $arr_ids)){
						$arr_ids[] = $y->id;
						if(!empty($y)){
							
							$arr_ids_na[] = $y->id_na;
							// || ($y->id_na && !(empty($y) && in_array($y->id_na, $arr_ids_na)))
							$link = $message = '';
							if(isset($y->ubicacion) && !$abog){
								$fecha_ingreso = $fecha_ubica = '';
								if($y->fecha_ingreso!='' && $y->fecha_ingreso!='0000-00-00'){
									$fecha_ingreso = date('d-m-Y', strtotime($y->fecha_ingreso));
								}
								if($y->fecha_ubica!='' && $y->fecha_ubica!='0000-00-00'){
									$fecha_ubica = date('d-m-Y', strtotime($y->fecha_ubica));
								}
								$cuerpo1.= "<tr><td>{$y->nro_ingreso}</td><td>{$fecha_ingreso}</td><td>{$y->ubicacion}</td>".
										  "<td>{$fecha_ubica}</td><td>{$y->corte}</td><td>{$y->caratulado}</td></tr>";
								
								$datos_cuentas = array();
								$datos_cuentas['seleccionado'] = 1;
								$this->cuentas_m->save_default($datos_cuentas, $y->id);
								$find = $apel = true;
								$causas1++;
							}
							if(!isset($y->ubicacion)){
								$fecha_etapa = '';
								if($y->fecha_etapa!='' && $y->fecha_etapa!='0000-00-00'){
									$fecha_etapa = date('d-m-Y', strtotime($y->fecha_etapa));
								}
								if($y->causa_reservada){
									$y->etapa = 'Causa Reservada - No es posible obtener mas información';
								}
								elseif($y->id_na && !$y->etapa_cuenta){
									$y->etapa_cuenta = 'Favor verificar si corresponde a su cartera, si no corresponde contactarse con mesa de operaciones';
								}
								
								if($y->id_na && $y->rut!=""){
									$message = 'Favor verificar si corresponde a su cartera, si no corresponde contactarse con mesa de operaciones';
									
								}
								
								if($y->id_mov){
									/*
									$receptor = $this->receptor_pjud->get_by(array('id_movimiento'=>$y->id_mov));
									if($receptor)
										$link = "http://200.73.113.95:81/estadodiario/index.php/admin/receptor_p/listar/{$y->id_mov}";
									*/
									if($y->id_na)
										$link = "http://200.73.113.95:81/estadodiario/index.php/admin/movimientos/listar/0/{$y->id_na}";
									else
										$link = "http://200.73.113.95:81/estadodiario/index.php/admin/movimientos/listar/{$y->id}";
								}
								
								if($link)
									$cuerpo2.="<tr><td>{$y->rol}</td><td>{$y->tribunal}</td><td>{$y->caratulado}</td>".
											  "<td><a href='{$link}' target='_blank'>link</a></td><td>{$y->rut}</td>".
											  "<td>{$fecha_etapa}</td><td>{$y->etapa_cuenta}</td><td>".
											  "{$message}</td><td>{$y->estado}</td>".
											  "<td>{$y->mandante}</td><td>{$y->procurador}</td></tr>";
								else
									$cuerpo2.="<tr><td>{$y->rol}</td><td>{$y->tribunal}</td><td>{$y->caratulado}</td>".
											  "<td></td><td>{$y->rut}</td>".
											  "<td>{$fecha_etapa}</td><td>{$y->etapa_cuenta}</td><td></td><td>{$y->estado}</td>".
											  "<td>{$y->mandante}</td><td>{$y->procurador}</td></tr>";
								$find = true;
								$causas2++;
							}
						}
					}
                }
                
            }
            $cuerpo1.= "<tr><td>TOTAL</td><td colspan='4'></td><td style='text-align:center;'>{$causas1}</td></tr>";
            $cuerpo2.= "<tr><td>TOTAL</td><td colspan='9'></td><td style='text-align:center;'>{$causas2}</td></tr>";
            
            if($find){
                $body1 ='<!DOCTYPE html>
                            <html>
                            <head>
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <style>
                    .alert {
                    padding: 20px;
                    background-color: #f44336;
                    color: white;
                    }
                    </style>
                            <title></title>

                        </head>
                        <body>
                        
                            <div style="padding:20px; margin-bottom:20px;" class="alert">
                            
                            <strong>Warning:</strong> Si algunas de las causas listadas no corresponde(n) a su cartera favor enviar al Procurador correspondiente con copia a la Mesa de Operaciones. Si no conoce al procurador enviar directamente a la mesa de operaciones.
                            </div>
                            <label style="margin-bottom:20px;">
                                Estimado(a), a continuación se detalla movimientos de estados diarios de causas:
                            </label><br/>
                            <table style="width:100%" border="1">
                              <tr>
                                <th>N° INGRESO</th>
                                <th>FECHA INGRESO</th>
                                <th>UBICACIÓN</th>
                                <th>FECHA UBICACIÓN</th>
                                <th>CORTE</th>
                                <th>CARATULADO</th>
                              </tr>'.
                                $cuerpo1
                            .'</table>
                        </body>
                        </html>';
                $body2 ='<!DOCTYPE html>
                            <html>
                            <head>
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <style>
                    .alert {
                    padding: 20px;
                    background-color: #f44336;
                    color: white;
                    }
                    </style>
                            <title></title>
                        </head>
                        <body>
                            <div style="padding:20px; margin-bottom:20px;" class="alert">
                            <strong>Warning:</strong> Si algunas de las causas listadas no corresponde(n) a su cartera favor enviar al Procurador correspondiente con copia a la Mesa de Operaciones. Si no conoce al procurador enviar directamente a la mesa de operaciones.
                            </div>
                            <label style="margin-bottom:20px;">
                                Estimado(a), a continuación se detalla movimientos de estados diarios de causas:
                            </label><br/>
                            <table style="width:100%" border="1">
                              <tr>
                                <th>ROL</th>
                                <th>TRIBUNAL</th>
                                <th>CARATULADO</th>
                                <th>MOV. PJUD</th>
                                <th>RUT</th>
                                <th>FECHA ETAPA</th>
                                <th>ETAPA</th>
                                <th>OBSERVACIONES</th>
                                <th>ESTADO</th>
                                <th>MANDANTE</th>
                                <th>PROCURADOR</th>
                              </tr>'.
                                $cuerpo2
                            .'</table>
                        </body>
                        </html>';

                $mail = Php_mailer::getPhpMailer($fecha_tramite);

                
                $correos_cc = $this->correo_m->get_many_by(array('id_procurador' => $key));
                foreach ($correos_cc as $a => $b){
                    //$mail->addCC($b->correo);
                }
                
                if($apel && !$abog){
                    //$mail->addAddress($email);
					$mail->addAddress("yvanega@gmail.com");
                    //$mail->addAddress('jorge.vasquez@gespron.cl');
                    foreach ($array_abogs as $val) {
                        $mail->addAddress($val);
                    }
                    unset($array_abogs);
                    $abog = true;
                    $mail->MsgHTML($body1);
                    $sent = $mail->send();
                    unset($mail);
                    if($causas2){
                        $mail = Php_mailer::getPhpMailer($fecha_tramite);
                        //$mail->addAddress($email);
						$mail->addAddress("yvanega@gmail.com");
                        //$mail->addAddress('jorge.vasquez@gespron.cl');
                        $mail->MsgHTML($body2);
                        $sent = $mail->send();
                    }
                }
                else{
                    //$mail->addAddress($email);
					$mail->addAddress("yvanega@gmail.com");
                    //$mail->addAddress('jorge.vasquez@gespron.cl');
                    $mail->MsgHTML($body2);
                    $sent = $mail->send();
                }
                
                if(!$sent){ 
                    $no_enviados++;
                    $causas_no_enviadas += intval($causas1) + intval($causas2);
                } else {
                    $enviados++;
                    $causas_enviadas += intval($causas1) + intval($causas2);
                }
            }
        }
                   
        ##################### Guardar Reg_correo ####################
        $fields_save = array();
        $fields_save['enviados'] = $enviados;
        $fields_save['no_enviados'] = $no_enviados;
        $fields_save['causa_enviada'] = $causas_enviadas;
        $fields_save['causas_no_enviada'] = $causas_no_enviadas;
		
        $this->reg_correo_m->save_default($fields_save);
		
        echo json_encode(array('result' => 1));
        die();
    }

    public function getPhpMailer($fecha_tramite){
        $mail = new PHPMailer;        
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->CharSet = 'UTF-8';
        $mail->SMTPSecure = "tls";
        $mail->Port = '587';
        $mail->SMTPAuth = true;
        $mail->Username = 'gespron.spa@gespron.onmicrosoft.com';
        $mail->Password = 'Spa2019@';
        $mail->WordWrap = 50;  
   
        $mail->setFrom('gespron.spa@gespron.onmicrosoft.com', 'ESTADO DIARIO');

        $mail->Subject = 'MOVIMIENTO ESTADO DIARIO '.$fecha_tramite;
        return $mail;
    }
 
}