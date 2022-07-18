<?php 

class Importar extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library ( 'session' );
		$this->load->helper ( 'date_html_helper' );
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'receptor_m' );
		

		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		$this->data['current'] = 'cuentas';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'cuentas/';
		$this->data['lists'] = array();
		$this->data['nodo'] = $this->nodo = $this->nodo_m->get_by( array('activo'=>'S') );	
	
		$array_return = array ();
		$this->array_return['cuentas_insert'] = 0;
		$this->array_return['no_insert'] = 0;
		$this->array_return['usuarios_insert'] = 0;
		$this->array_return['usuarios_update'] = 0;
	}
	
	public function importar_excel_nodo_swcobranza(){
		
		//$this->output->enable_profiler ( TRUE );
		//echo 'entro';
		
		$this->load->helper ( 'excel_reader2' );
		$this->load->model ( 'abogados_m' );
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'tribunales_pjud_m');
		$this->load->model ( 'pagare_m' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ('direccion_m' );
		$this->load->model ('comunas_m' );
		$this->load->model ('etapas_m' );
		$this->load->model ('administradores_m' );
		$this->load->model ('cuentas_historial_m' );
		$this->load->model ('cuentas_etapas_m' );
		
		$this->data ['operacion'] = FALSE;
		$rows_insert = array ();
		$uploadpath = "./uploads/importar.xls";
		
		
		$excel = new Spreadsheet_Excel_Reader ( $uploadpath );
		$rowcount = $excel->rowcount ( $sheet_index = 0 );
		$colcount = $excel->colcount ( $sheet_index = 0 );
		
		
		for($i = 2; $i <= $rowcount; $i ++) {
			
			$arreglo_cuenta = array ();
			
			$razon_social  = '';
			$rut = '';
			$dv = '';
			$apellido_paterno = '';
			$apellido_materno = '';
			$nombre = '';
			$telefono = '';
			$telefono_comercial = '';
			$celular = '';
			$comuna = '';
			$numero_pagare = '';
			$monto_pagare = '';
			$fecha_vencimiento_pagare = '';
			$distrito = '';
			$tribunal = '';
			$rol = '';
			$fecha_asignacion = '';
			$producto = '';
			$estado = '';
			$procurador = '';
			$monto_deuda_fecha_mora = '';
			$fecha_mora = '';
			
			$razon_social = trim ( $excel->val ( $i, 'A', 0 ) );
			$rut = trim ( str_replace ( '.', '', $excel->val ( $i, 'B', 0 ) ) );
			$dv = trim ( $excel->val ( $i, 'C', 0 ) );
			$rut = $rut.'-'.$dv;
			$apellido_paterno = trim ( $excel->val ( $i, 'D', 0 ) );
			$apellido_materno = trim ( $excel->val ( $i, 'E', 0 ) );
			$nombre = trim ( $excel->val ( $i, 'F', 0 ) );
			$telefono = trim ( $excel->val ( $i, 'G', 0 ) );
			$telefono_comercial = trim ( $excel->val ( $i, 'H', 0 ) );
			$celular = utf8_encode ( trim ( $excel->val ( $i, 'I', 0 ) ) );
			$direccion_particular = trim ( $excel->val ( $i, 'J', 0 ) );
			$direccion_comercial = trim ( $excel->val ( $i, 'K', 0 ) );
			$comuna_particular = utf8_encode ( trim ( $excel->val ( $i, 'L', 0 ) ) );
			$comuna_particular =  utf8_encode ($comuna_particular);
			$comuna_comercial = utf8_encode ( trim ( $excel->val ( $i, 'M', 0 ) ) );
			$comuna_comercial =  utf8_encode ($comuna_comercial);
			
			
			$numero_pagare = trim ( str_replace ( '.', '', $excel->val ( $i, 'N', 0 ) ) );
			$monto_pagare = trim ( $excel->val ( $i, 'O', 0 ) );
			$fecha_vencimiento_pagare = trim ( $excel->val ( $i, 'P', 0 ) );
			if($fecha_vencimiento_pagare != ''){
			$fecha_vencimiento_pagare = date ( "Y-m-d", strtotime ( $fecha_vencimiento_pagare ) );
			}
			$distrito = trim ( $excel->val ( $i, 'Q', 0 ) );
			$tribunal = trim ( $excel->val ( $i, 'R', 0 ) );
			$rol = trim ( $excel->val ( $i, 'S', 0 ) );
			
			if($fecha_asignacion != ''){
			$fecha_asignacion = trim ( $excel->val ( $i, 'T', 0 ) );
			}
			if($fecha_asignacion != ''){
			$fecha_asignacion = date ( "Y-m-d", strtotime ( $fecha_asignacion ) );
		    }
			
		    $producto = trim ( $excel->val ( $i, 'U', 0 ) );
			$estado = trim ( $excel->val ( $i, 'V', 0 ) );
			
			$procurador = trim ( $excel->val ( $i, 'W', 0 ) );
			$monto_deuda_fecha_mora = trim ( $excel->val ( $i, 'X', 0 ) );
			
			$fecha_mora = trim ( $excel->val ( $i, 'Y', 0 ) );
		    if($fecha_mora != ''){
			$fecha_mora = date ( "Y-m-d", strtotime ( $fecha_mora ) );
		    }
			
		    
		    $codigo_etapa = trim ( $excel->val ( $i, 'Z', 0 ) );
		    $fecha_etapa = trim ( $excel->val ( $i, 'AA', 0 ) );
		    
			//MANDNATE
			$idmandante = '';
			$arreglo_mandante = array();
		    $arreglo_mandante ['codigo_mandante'] = utf8_encode ( $razon_social );
		   // print_r($arreglo_mandante);
		    //die();
		    $idmandante = $this->revisar_mandante($arreglo_mandante);
			
		       if ($idmandante!=''){
				//USUARIO
			       	$datos_usuario = array();
					$datos_usuario['nombre'] = $nombre;
					$datos_usuario['apellido_paterno'] = $apellido_paterno;
					$datos_usuario['apellido_materno'] = $apellido_materno;
					$datos_usuario['rut'] = $rut;
				$idusuario = $this->revisar_usuario($rut,$datos_usuario);
				if ($idusuario!=''){
					//CUENTA 
				$datos_cuenta = array();
				$iddistrito ='';
				$distrito = $this->tribunales_m->get_by ( array ('tribunal' => $distrito ));
				if (count($distrito) == '1'){
				$iddistrito=$distrito->id;   
				}
								
				$idtribunal ='';
                $tribunall = $this->tribunales_m->get_by ( array ('tribunal' => $tribunal ));

                  if (count($tribunall) == '1' ){
                	$idtribunal=$tribunall->id;
                	}
                
                $idprocurador = $procurador;//$this->administradores_m->get_by ( array ('id' => $procurador ));
                $datos_cuentas['id_procurador'] = $idprocurador;//$idprocurador;
				
                $datos_cuentas['id_tribunal'] = $idtribunal;
                $datos_cuentas['id_distrito'] = $iddistrito;
				
				  
				 if ($fecha_asignacion !=  ''){  
				$datos_cuentas['fecha_asignacion'] = $fecha_asignacion;
				}else{
				$datos_cuentas['fecha_asignacion'] = date ( "Y-m-d");
				}
				 
				
			    if ($fecha_mora != ''){
			    	$datos_cuentas['fecha_ultimo_pago'] = $fecha_mora;
			    } else {
			    	$datos_cuentas['fecha_ultimo_pago'] ='';
			    }
				
				
				if ($rol!=''){
					$rol_aux = explode('-',$rol);
					if (count($rol)==2){
						if (in_array($rol[1],array(2010,2011,2012,2013,2014,2015))){
							$datos_cuentas['rol1'] = $rol_aux[0];
							$datos_cuentas['rol1_y'] = $rol_aux[1];		
						} else {
							$datos_cuentas['rol1'] = $rol_aux;
						}
					} else {
						$datos_cuentas['rol1'] = $rol_aux;
					}
					$datos_cuentas['rol'] = $rol;	
				}
				if ($estado!=''){
					$datos_cuentas['id_estado_cuenta'] = $estado;
				}
				
			//	print_r($datos_cuentas);
              // die(); 				
				//$idprocurador='';
			    $idcuenta = $this->revisar_cuentas($idmandante,$idusuario,$datos_cuentas,$idprocurador);

				//echo $idcuenta.'====antes del if'.'<br>'; 
				// FIN CUENTAS	
					 if ($idcuenta!=''){
				//echo $idcuenta.'====despues del if'.'<br>';		
					 	//INICIO TELEFONO
					 	$datos_telefono = array();
					  
				        $datos_telefono['id_cuenta'] = $idcuenta; 
					    $datos_telefono['numero'] = $telefono;
					    $datos_telefono['tipo'] = 1;
					    $this->revisar_telefonos($idcuenta,$datos_telefono);
						$datos_telefono['id_cuenta'] = $idcuenta; 
					    $datos_telefono['numero'] = $telefono_comercial;
					    $datos_telefono['tipo'] = 2;
					    $this->revisar_telefonos($idcuenta,$datos_telefono);
					    $datos_telefono['id_cuenta'] = $idcuenta;  
					    $datos_telefono['numero'] = $celular;
					    $datos_telefono['tipo'] = 3;
                        //print_r($datos_telefono);
					    //die();
					    $this->revisar_telefonos($idcuenta,$datos_telefono);
						// FIN TELEFONO

					    // INICIO DIRECCION
					    $datos_direccion = array();
                        $idcomuna = ''; 
                        $comuna = $this->comunas_m->get_by(array('nombre' => $comuna_particular)); 
				        if (count($comuna) == '1' ){
	                     $idcomuna=$comuna->id;		
                        } 
				          
                         
                         $datos_direccion['tipo'] = '1';
                         $datos_direccion['id_cuenta'] = $idcuenta;
                         $datos_direccion['id_comuna'] = $idcomuna;   
	           			 $datos_direccion['direccion'] = $direccion_particular;
						 
	           			 $this->revisar_direccion($idcuenta,$datos_direccion);
							
			    		$idcomuna = ''; 
				        $comuna = $this->comunas_m->get_by(array('nombre' => $comuna_comercial)); 
				
				
						if (count($comuna) == '1' ){
	               			$idcomuna=$comuna->id;		
	                       } 			
				        $datos_direccion['tipo'] = '2';
				 		$datos_direccion['direccion'] = $direccion_comercial;  
				 		$datos_direccion['id_comuna'] = $idcomuna;
				 		$this->revisar_direccion($idcuenta,$datos_direccion);
					    
				 		
				 		$datos_pagare = array();
						$idpagare = ''; 
				        $pagare = $this->pagare_m->get_by(array('monto_deuda' => $monto_pagare)); 
				
				     	if (count($pagare) == '1' ){
	               			$idpagare=$pagare->idpagare;		
	                    } 
						
	                    $datos_pagare['fecha_asignacion'] = $fecha_vencimiento_pagare;     
	                    $datos_pagare['fecha_vencimiento'] = $fecha_vencimiento_pagare;  
				 		$datos_pagare['n_pagare'] = $numero_pagare;
				 		$datos_pagare['monto_deuda'] = $monto_pagare;   
	                    $datos_pagare['idcuenta'] = $idcuenta;    
	                    
	                    /*echo '<pre>';
						print_r($datos_pagare);
						echo '</pre>';*/
						// ( $datos_pagare['fecha_vencimiento']) se repite en feha de asignacion 
						// si el numero de pagare viene '' se tiene q concatenar el rut mas guion 1 
				 		$this->revisar_pagare($idcuenta,$datos_pagare,$idmandante);
				 		
				 	 	if ($fecha_etapa!='' && $codigo_etapa!=''){
				 	 		$datos_etapas = array();
				 	 		$datos_etapas['fecha_etapa'] = date('Y-m-d',strtotime($fecha_etapa));
				 	 		$this->revisar_etapas_cuentas_nuevo($datos_etapas,$codigo_etapa, $idcuenta);
				 	 	}
					 } 
				}
			 }
			
		
		}
	}

	// INICIO USUARIO 
	public function revisar_usuario($rut,$datos=array()){
		$idusuario = '';
		
	   if ($rut!='') {
			$id = $this->usuarios_m->get_by ( array ('rut' => $rut, 'activo'=>'S' ) );
			if (! empty ( $rut )) {
				$arreglo_usuario ['rut'] = $rut;
			}



			if ($datos['apellido_paterno']!='') {
				$arreglo_usuario ['ap_pat'] = utf8_encode ( $datos['apellido_paterno'] );
			}
		  	if ($datos['apellido_materno']!='') {
				$arreglo_usuario ['ap_mat'] = utf8_encode ( $datos['apellido_materno'] );
			}




		   	if ($datos['nombre']!='') {
				$arreglo_usuario ['nombres'] = utf8_encode ( $datos['nombre'] );
			}
			
			 if($this->data['nodo']->nombre == 'fullpay'){
				if ($datos['ciudad']!='') {
					$arreglo_usuario ['ciudad'] = utf8_encode ( $datos['ciudad'] );
			    }
		 	}
			
			if($this->data['nodo']->nombre == 'fullpay'){
				if ($datos['direccion']!='') {
					$arreglo_usuario ['direccion'] = utf8_encode ( $datos['direccion'] );
			    }
		 	}
			
			$usuario = $this->usuarios_m->get_by ( array ('rut' => $rut ) );

		
			   
		if (count($usuario) == '1' ){
	     $idusuario=$usuario->id;		
	      }
			 if ($idusuario != '') {
				$arreglo_usuario = array_merge ( $arreglo_usuario, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->usuarios_m->update ( $idusuario, $arreglo_usuario, TRUE, TRUE );
			    $this->array_return['usuarios_update']++;
			 } else {
				$arreglo_usuario = array_merge ( $arreglo_usuario, array ('fecha_crea' => date ( 'Y-m-d H:i:s' )) );
				$idusuario=$this->usuarios_m->insert ( $arreglo_usuario, TRUE, TRUE );
			    $this->array_return['usuarios_insert']++;
			 }
		}
		
		return $idusuario;
     }
	// TERMINO USUARIO

	// INICIO MANDANTE 
	public function revisar_mandante($datos=array()){
		$idmandante = '';
	
	//$arreglos_mandante['razon_social'] = utf8_encode ( $datos['razon_social'] );
			
		
	$mandante = $this->mandantes_m->get_by(array('codigo_mandante' => $datos['codigo_mandante']));
	
	if (count($mandante) == '1' ){
	$idmandante=$mandante->id;		
	}
	
	
	if ($idmandante != '') {
				$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->mandantes_m->update($idmandante,$datos, TRUE, TRUE );
			} else {
				$adatos = array_merge ($datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
			 $idmandante = $this->mandantes_m->insert ( $datos, TRUE, TRUE );
			}
		return $idmandante;
	}
	//TERMINO MANDANTE
	

	public function revisar_cuentas_aux($datos=array()){

		$data = $this->tribunales_pjud_m->get_by(array ('tribunal' => $datos['tribunal']));
		//print_r($data);
		//die;
		if( !empty($data)){
			$id_tribunal = $data->id_tribunal_pjud;
		}else{
			$id_tribunal = 0;
		}
		$cuenta = $this->cuentas_m->get_by(array("rol" => $datos['rol'], 'id_tribunal' => $id_tribunal));
		return $cuenta->id;
	}

	#################### ESTADO DIARIO - REVISAR CUENTAS #####################################
	#######################################################################
	//CUENTAS 
	public function revisar_cuentas($datos=array()){
		$idcuenta = '';
		
		//echo $datos['fecha_asignacion'].'<br>';
		//die();
		if (array_key_exists ( 'rol', $datos ) && $datos ['rol'] != '') {
			$datos_cuentas ['rol'] = $datos ['rol'];
		}

		if (array_key_exists ( 'tribunal', $datos ) && $datos ['tribunal'] != '') {
			$datos_cuentas ['tribunal'] = $datos ['tribunal'];
		}

		if (array_key_exists ( 'caratulado', $datos ) && $datos ['caratulado'] != '') {
			$datos_cuentas ['caratulado'] = $datos ['caratulado'];
		}

		if (array_key_exists ( 'fecha_ingreso', $datos ) && $datos ['fecha_ingreso'] != '') {
			$datos_cuentas ['fecha_ingreso'] = $datos ['fecha_ingreso'];
		}

		if (array_key_exists('id_abogado', $datos) && $datos['id_abogado'] != '') {
           $datos_cuentas['id_abogado'] = $datos['id_abogado'];
		}

		##############################################
		//echo $this->data['nodo']->nombre.' '.$idusuario.' '.$tipo_demanda.' '.$exorto.'<br>';

		$datos_cuentas ['activo'] = "S";

		$tribunal_pjud = trim($datos_cuentas['tribunal']);
		//print_r($tribunal_pjud);
		//die;
	    $data = $this->tribunales_pjud_m->get_by(array ('tribunal' => $tribunal_pjud));
		//print_r($data);
		//die;
		if( !empty($data)){
			$id_tribunal = $data->id_tribunal_pjud;
		}else{
			$id_tribunal = 0;
		}
	  
		$datos_cuentas = array_merge($datos_cuentas, array('id' => $idcuenta, 'fecha_crea' => date ( 'Y-m-d H:i:s' ),'id_tribunal' => $id_tribunal));

		$where = array();
		$rol = explode('-', $datos_cuentas['rol']);

		$this->cae = $this->load->database("cae", TRUE);
		$this->cat = $this->load->database("cat", TRUE);
		$this->sup = $this->load->database("superir", TRUE);

		$res = $this->tribunales_pjud_m->get_by(array ('tribunal' => $tribunal_pjud));

		if($rol[0] == 'C'){
			$where['cta.letraC'] = 1;
			$a = $this->cae->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
			if(!empty($a))
				$where['cta.anio'] = $a[0]->id;
			if($rol[1])
				$where['cta.rol'] = $rol[1];
			if(!empty($res))
				$where['cta.id_tribunal'] = $res->id_s_tribunales;
		}
		elseif($rol[0] == 'E'){
			$where['cta.letraE'] = 2;
			$a = $this->cae->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
			if(!empty($a))
				$where['cta.anioE'] = $a[0]->id;
			if($rol[1])
				$where['cta.rolE'] = $rol[1];
			if(!empty($res))
				$where['cta.id_tribunal_ex'] = $res->id_s_tribunales;
		}

		//Consultar la base de datos del CAE
		$result1 = $this->cae->select('
									u.rut,
									MAX(2ce.fecha_etapa) as fecha_etapa,
									se.etapa,
									ma.codigo_mandante as mandante,
									es.estado,
									adm.nombres,
									adm.apellidos,
								')
							  ->from("0_cuentas cta")
							  ->join("0_usuarios u","u.id=cta.id_usuario", "left")
							  ->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id", "left")
							  ->join("s_etapas se","se.id=cta.id_etapa", "left")
							  ->join("0_mandantes ma","ma.id=cta.id_mandante", "left")
							  ->join("s_estado_cuenta es","es.id=cta.id_estado_cuenta", "left")
							  ->join("0_administradores adm","adm.id=cta.id_procurador", "left")
							  ->where($where)
							  ->get()
							  ->result();

		if($rol[0] == 'C'){
			$where['cta.letraC'] = 1;
			$a = $this->cat->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
			if(!empty($a))
				$where['cta.anio'] = $a[0]->id;
			if($rol[1])
				$where['cta.rol'] = $rol[1];
			if(!empty($res))
				$where['cta.id_tribunal'] = $res->id_s_tribunales;
		}
		elseif($rol[0] == 'E'){
			$where['cta.letraE'] = 2;
			$a = $this->cat->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
			if(!empty($a))
				$where['cta.anioE'] = $a[0]->id;
			if($rol[1])
				$where['cta.rolE'] = $rol[1];
			if(!empty($res))
				$where['cta.id_tribunal_ex'] = $res->id_s_tribunales;
		}

		$result2 = $this->cat->select('
									u.rut,
									MAX(2ce.fecha_etapa) as fecha_etapa,
									se.etapa,
									ma.codigo_mandante as mandante,
									es.estado,
									adm.nombres,
									adm.apellidos,
								')
							  ->from("0_cuentas cta")
							  ->join("0_usuarios u","u.id=cta.id_usuario", "left")
							  ->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id", "left")
							  ->join("s_etapas se","se.id=cta.id_etapa", "left")
							  ->join("0_mandantes ma","ma.id=cta.id_mandante", "left")
							  ->join("s_estado_cuenta es","es.id=cta.id_estado_cuenta", "left")
							  ->join("0_administradores adm","adm.id=cta.id_procurador", "left")
							  ->where($where)
							  ->get()
							  ->result();

		if($rol[0] == 'C'){
			$where['cta.letraC'] = 1;
			$a = $this->sup->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
			if(!empty($a))
				$where['cta.anio'] = $a[0]->id;
			if($rol[1])
				$where['cta.rol'] = $rol[1];
			if(!empty($res))
				$where['cta.id_tribunal'] = $res->id_s_tribunales;
		}
		elseif($rol[0] == 'E'){
			$where['cta.letraE'] = 2;
			$a = $this->sup->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
			if(!empty($a))
				$where['cta.anioE'] = $a[0]->id;
			if($rol[1])
				$where['cta.rolE'] = $rol[1];
			if(!empty($res))
				$where['cta.id_tribunal_ex'] = $res->id_s_tribunales;
		}

		$result3 = $this->sup->select('
									u.rut,
									MAX(2ce.fecha_etapa) as fecha_etapa,
									se.etapa,
									ma.codigo_mandante as mandante,
									es.estado,
									adm.nombres,
									adm.apellidos,
								')
							  ->from("0_cuentas cta")
							  ->join("0_usuarios u","u.id=cta.id_usuario", "left")
							  ->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id", "left")
							  ->join("s_etapas se","se.id=cta.id_etapa", "left")
							  ->join("0_mandantes ma","ma.id=cta.id_mandante", "left")
							  ->join("s_estado_cuenta es","es.id=cta.id_estado_cuenta", "left")
							  ->join("0_administradores adm","adm.id=cta.id_procurador", "left")
							  ->where($where)
							  ->get()
							  ->result();
		$result4 = array_merge($result1, $result2, $result3);
		$result = array();
		foreach ($result4 as $key => $value){ //print_r($value);
			if($value->rut != '')
				$result[] = $value;
		}
		if(empty($result))
			$result = $result1;
		//echo 'ROL-->'.$datos_cuentas['rol'].'TRIBUNAL PJUD--->'.$datos_cuentas['id_tribunal'].'TRIBUNAL--->'.$res->id;
		//print_r($result4); print_r($result);
		
	    $aux_rol = $datos_cuentas['rol'];
		$aux_trb = $datos_cuentas['id_tribunal'];
		$cuenta = $this->cuentas_m->get_by(array("rol" => $aux_rol, 'id_tribunal' => $aux_trb));
		$cuenta_na = $this->cuentas_na->get_by(array("rol" => $aux_rol, 'id_tribunal' => $aux_trb));
		
		if(($cuenta && $cuenta->id_abogado != $datos_cuentas['id_abogado']) || ($cuenta_na && $cuenta_na->id_abogado != $datos_cuentas['id_abogado']) || !($cuenta && $cuenta_na)){
			if($result[0]->rut == ''){
				if($datos_cuentas['id_abogado'] == 1) //CINTHYA
					$idcuenta = $this->cuentas_aux->save_default($datos_cuentas);
				elseif(!$cuenta_na){
					$idabog = $datos_cuentas['id_abogado'];
					$datos_abog = array();
					$abogad = $this->abogados_m->get_by(array("id" => $idabog));
					$datos_abog['total_na'] = intval($abogad->total_na) + 1;
					$this->abogados_m->update($idabog, $datos_abog, false, true);
					$idcuenta = $this->cuentas_na->save_default($datos_cuentas);
				}
				else
					$this->array_return['no_insert']++;
			}
			else{
				$datos_cuentas['rut'] = $result[0]->rut;
				$datos_cuentas['etapa'] = $result[0]->etapa;
				$datos_cuentas['fecha_etapa'] = $result[0]->fecha_etapa;
				$datos_cuentas['mandante'] = $result[0]->mandante;
				$datos_cuentas['estado'] = $result[0]->estado;
				$datos_cuentas['nombres'] = $result[0]->nombres.' '.$result[0]->apellidos;
				if($datos_cuentas['id_abogado'] == 1){ //CINTHYA
					$passed = false;
					if($cuenta){
						if(in_array($cuenta->id_abogado, array(2, 3, 4))){ //(No se inserta)
							$passed = true;
							$id_abogado = 1;
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => 1));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($id_abogado, $datos_abog, false, true);
						}
					}
					if($cuenta_na){
						if(in_array($cuenta_na->id_abogado, array(2, 3, 4))){ //(No se inserta)
							$passed = true;
							$id_abogado = 1;
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => 1));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($id_abogado, $datos_abog, false, true);
						}
					}
					if(!$passed){
		    			$idcuenta = $this->cuentas_m->save_default($datos_cuentas);
		    			$this->array_return['cuentas_insert']++;
					}
					else
						$this->array_return['no_insert']++;
		    	}
		    	else{
					$id_abogado = $datos_cuentas['id_abogado'];
					$passed = false;
					if($cuenta){
						//echo $id_abogado.'--->'.$cuenta->id_abogado;
						if($id_abogado == 2 && $cuenta->id_abogado == 1){ //ISIS (Quitamos a Cinthya)
							$this->cuentas_m->delete($cuenta->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($cuenta->id_abogado, $datos_abog, false, true);
						}
						elseif($id_abogado == 2 && $cuenta->id_abogado == 4){ //ISIS (No insertamos a isis)
							$passed = true;
							//$datos_abog = array();
							//$abogad = $this->abogados_m->get_by(array("id" => $id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							//$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							//$this->abogados_m->update($id_abogado, $datos_abog, false, true);
							/*
							$this->cuentas_m->delete($cuenta->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($cuenta->id_abogado, $datos_abog, false, true);*/
						}
						elseif($id_abogado == 2 && $cuenta->id_abogado == 3){ //ISIS y LESLIE (No insertamos a isis)
							$passed = true;
							//$datos_abog = array();
							//$abogad = $this->abogados_m->get_by(array("id" => $id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							//$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							//$this->abogados_m->update($id_abogado, $datos_abog, false, true);
						}
						elseif($id_abogado == 3 && $cuenta->id_abogado == 1){ //LESLIE (Quitamos a cinthya)
							$this->cuentas_m->delete($cuenta->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($cuenta->id_abogado, $datos_abog, false, true);
						}
						elseif($id_abogado == 3 && $cuenta->id_abogado == 2){ //LESLIE e Isis (Quitamos a isis)
							$this->cuentas_m->delete($cuenta->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($cuenta->id_abogado, $datos_abog, false, true);
						}
						
						elseif($id_abogado == 3 && $cuenta->id_abogado == 4){ //LESLIE (No insertamos)
							$passed = true;
							/*
							$this->cuentas_m->delete($cuenta->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($cuenta->id_abogado, $datos_abog, false, true);*/
						}
						elseif($id_abogado == 4 && $cuenta->id_abogado == 1){ //Carolina (Quitamos a Cinthya)
							$this->cuentas_m->delete($cuenta->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($cuenta->id_abogado, $datos_abog, false, true);
						}
						elseif($id_abogado == 4 && in_array($cuenta->id_abogado, array(2, 3))){ //CAROLINA (Quitamos a isis o leslie)
							$this->cuentas_m->delete($cuenta->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($cuenta->id_abogado, $datos_abog, false, true);
							/*
							$passed = true;
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($id_abogado, $datos_abog, false, true);*/
						}
						else{
							//echo $id_abogado.'--->'.$cuenta->id_abogado;
							//echo '|';
							$passed = true;
						}
					}
					if($cuenta_na){
						if($id_abogado == 2 && $cuenta_na->id_abogado == 1){ //ISIS (Quitamos a Cinthya)
							$this->cuentas_na->delete($cuenta_na->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta_na->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$datos_abog['total_na'] = intval($abogad->total_na) - 1;
							$this->abogados_m->update($cuenta_na->id_abogado, $datos_abog, false, true);
						}
						elseif($id_abogado == 2 && $cuenta_na->id_abogado == 4){ //ISIS (No insertamos a isis)
							$passed = true;
							//$datos_abog = array();
							//$abogad = $this->abogados_m->get_by(array("id" => $id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							//$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							//$this->abogados_m->update($id_abogado, $datos_abog, false, true);
							/*
							$this->cuentas_na->delete($cuenta_na->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta_na->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$datos_abog['total_na'] = intval($abogad->total_na) - 1;
							$this->abogados_m->update($cuenta_na->id_abogado, $datos_abog, false, true);*/
						}
						elseif($id_abogado == 2 && $cuenta_na->id_abogado == 3){ //ISIS y LESLIE (No insertamos a isis)
							$passed = true;
							//$datos_abog = array();
							//$abogad = $this->abogados_m->get_by(array("id" => $id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							//$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							//$this->abogados_m->update($id_abogado, $datos_abog, false, true);
						}
						elseif($id_abogado == 3 && $cuenta_na->id_abogado == 1){ //LESLIE (Quitamos a cinthya)
							$this->cuentas_na->delete($cuenta_na->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta_na->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$datos_abog['total_na'] = intval($abogad->total_na) - 1;
							$this->abogados_m->update($cuenta_na->id_abogado, $datos_abog, false, true);
						}
						elseif($id_abogado == 3 && $cuenta_na->id_abogado == 2){ //LESLIE e Isis (Quitamos a isis)
							$this->cuentas_na->delete($cuenta_na->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta_na->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$datos_abog['total_na'] = intval($abogad->total_na) - 1;
							$this->abogados_m->update($cuenta_na->id_abogado, $datos_abog, false, true);
						}
						
						elseif($id_abogado == 3 && $cuenta_na->id_abogado == 4){ //LESLIE (No insertamos)
							$passed = true;
							/*
							$this->cuentas_na->delete($cuenta_na->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta_na->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$datos_abog['total_na'] = intval($abogad->total_na) - 1;
							$this->abogados_m->update($cuenta_na->id_abogado, $datos_abog, false, true);*/
						}
						elseif($id_abogado == 4 && $cuenta_na->id_abogado == 1){ //Carolina (Quitamos a Cinthya)
							$this->cuentas_na->delete($cuenta_na->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta_na->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$datos_abog['total_na'] = intval($abogad->total_na) - 1;
							$this->abogados_m->update($cuenta_na->id_abogado, $datos_abog, false, true);
						}
						elseif($id_abogado == 4 && in_array($cuenta_na->id_abogado, array(2, 3))){//CAROLINA(Quitamos a Isis o leslie)
							$this->cuentas_na->delete($cuenta_na->id);
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $cuenta_na->id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$datos_abog['total_na'] = intval($abogad->total_na) - 1;
							$this->abogados_m->update($cuenta_na->id_abogado, $datos_abog, false, true);
							/*
							$passed = true;
							$datos_abog = array();
							$abogad = $this->abogados_m->get_by(array("id" => $id_abogado));
							//$datos_abog['total_registros'] = intval($abogad->total_registros) - 1;
							$datos_abog['total_elim'] = intval($abogad->total_elim) + 1;
							$this->abogados_m->update($id_abogado, $datos_abog, false, true);*/
						}
						else{
							//echo $id_abogado.'--->'.$cuenta->id_abogado;
							//echo '|';
							$passed = true;
						}
					}
					if(!$passed && $result[0]->rut != ''){
			    		$idcuenta = $this->cuentas_m->save_default($datos_cuentas);
			    		$this->array_return['cuentas_insert']++;
			    	}
			    	else
			    		$this->array_return['no_insert']++;	
				}
			}
		}
	    //print_r($datos_cuentas);
		//die;
		return $idcuenta;
	 }
	// TERMINAR CUENTAS 
	
	// FIN HISTORIAL CUENTA 
	
	//EMPEZAR TELÉFONO
	// $tipos = array('0'=>'Tipo','1'=>'Particular','2'=>'Comercial','3'=>'Celular','4'=>'Otro');
	public function revisar_telefonos($idcuenta,$datos = array()){
	$idtelefono= '';
            
	$telefono = $this->telefono_m->get_by ( array ('id_cuenta' => $idcuenta, 'numero'=>$datos['numero'], 'tipo'=>$datos['tipo'] ) );

	//print_r($telefono);
	//die(); 
		if (count($telefono) == '1' ){
	     $idtelefono=$telefono->id;		
	      }	
		
	      if($datos['numero'] != ''){
	 if ($idtelefono != '') {
				$datos_telefonos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->telefono_m->update ( $idtelefono, $datos, TRUE, TRUE );
			} else {
				$datos_telefonos = array_merge ($datos, array ('id' => $idtelefono, 'fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->telefono_m->insert($datos+array('estado'=>0),TRUE,TRUE );
			}	
		return $idtelefono;      
	  }
	}
	// TERMINO DE TELEFONOS
	
	public function revisar_direccion($idcuenta,$datos=array()){
	
	    $iddireccion= '';		
		$direccion = $this->direccion_m->get_by (array('id_cuenta' => $idcuenta, 'direccion'=>$datos['direccion']/*,'id_comuna' => $datos['id_comuna']*/));
    
		//print_r($direccion);
	
		if (count($direccion) == '1' ){
	     $iddireccion=$direccion->id;		
	      }
		
	    if ($iddireccion != '') {
				$datos_direcciones = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->direccion_m->update ( $iddireccion, $datos, TRUE, TRUE );
			} else {
				$datos_direcciones = array_merge ($datos, array ( 'fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->direccion_m->insert($datos+array('estado'=>0),TRUE,TRUE );
			}	
		return $iddireccion;   
	      
	 }
	
	public function revisar_etapas($datos=array()){
		$idetapa = '';
		
		$etapa = $this->etapas_m->get_by ( array ('LCASE(etapa)' => strtolower ( $datos ['etapa'] ) ) );
		
		if (count ( $etapa ) == '1') {
			$idetapa = $etapa->id;
		}
		
		/*if ($idetapa != '') {
			$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
			$this->etapas_m->update ( $idetapa, $datos, TRUE, TRUE );
		} else {
			$datos_etapas = array_merge ( $datos, array ('id' => $idetapa, 'fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
			$idetapa = $this->etapas_m->insert ( $datos, TRUE, TRUE );
		}*/
		return $idetapa;
	
	}
	 
	public function revisar_etapas_cuentas_nuevo($datos=array(),$codigo_etapa,$idcuenta){

		$etapa = $this->etapas_m->get_by(array('codigo'=>$codigo_etapa,'activo'=>'S'));

		if (count($etapa)==1){
			
			$datos ['id_etapa'] = $etapa->id;
			if ($idcuenta!='' && $datos ['id_etapa']!='' && $datos['fecha_etapa']!=''){
				
				$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->cuentas_m->update ( $idcuenta, $datos, TRUE, TRUE );
				
				if ($idcuenta != '') {
					$datos ['id_cuenta'] = $idcuenta;
				}
				
				$datos = array_merge ( $datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->cuentas_etapas_m->insert ( $datos, TRUE, TRUE );
				
				
			}
		}
	}
	
	public function revisar_etapas_cuentas($datos=array(),$idcuenta,$idetapa){
		$idetapa_cuenta = '';
		
		$cuentas_etapa = $this->cuentas_etapas_m->get_by ( array ('id_cuenta' => $idcuenta, 'id_etapa' => $idetapa, 'fecha_etapa' => $datos ['fecha_etapa'] ) );
		
		if (count ( $cuentas_etapa ) == '1') {
			$idetapa_cuenta = $cuentas_etapa->id;
		}
		
		if ($idcuenta != '') {
			$datos ['id_cuenta'] = $idcuenta;
		}
		$datos ['id_etapa'] = $idetapa;
		
		if ($idetapa != '' && $idetapa != '0') {
			if ($idetapa_cuenta != '') {
				$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->cuentas_etapas_m->update ( $idetapa_cuenta, $datos, TRUE, TRUE );
			} else {
				// echo 'entro en el insert';
				$datos = array_merge ( $datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->cuentas_etapas_m->insert ( $datos, TRUE, TRUE );
			}
			return $idetapa_cuenta;
		}
	}
	 
	public function revisar_pagare($idcuenta,$datos,$idmandante){
		
		$idpagare = '';
		
	 	if (isset($datos['monto_deuda']) && $datos['monto_deuda']>0){
			$datos['monto_deuda'] = trim ( str_replace ( ',', '.', $datos['monto_deuda'] ) );


//FECHA SUSCRIPCIÓN 	FECHA AUTORIZACIÓN NOTARIO	N° CUOTAS	VALOR CUOTAS	INTERÉS	FECHA 1° VENCIMIENTO 	
//CUOTAS PAGADAS 	1° CUOTA IMPAGA	VENCIMIENTO 1° CUOTA IMPAGA 	SALDO INSOLUTO 			
		/*
						$datos_pagares ['fecha_suscripcion'] = $fecha_suscripcion;				
						$datos_pagares ['numero_cuotas'] = $n_cuota;
						$datos_pagares ['valor_primera_cuota'] = $valor_cuota;
						$datos_pagares ['fecha_vencimiento'] = $fecha_vencimiento1;
						$datos_pagares ['ultima_cuota_pagada'] = $cuota_pagada;
						$datos_pagares ['fecha_pago_ultima_cuota'] = $venvimiento_prim_couota_impaga;
						$datos_pagares ['valor_ultima_cuota'] = $prim_cuota_impaga;
						$datos_pagares ['saldo_deuda'] = $saldo_insoluto;
						$datos_pagares ['tasa_interes'] = $interes;
						$datos_pagare ['fecha_autorizacon_notario'] = $fecha_autorizacon_notario;
						*/
//print_r($datos_pagares);
            $mandante = $this->mandantes_m->get_by ( array('id'=>$idmandante));
            if (count($mandante)==1 && $mandante->n_pagare_automatico==1 && $datos['n_pagare']!=''){
                $pagare = $this->pagare_m->get_by ( array ('idcuenta' => $idcuenta, 'n_pagare'=>$datos['n_pagare'], 'monto_deuda' => $datos ['monto_deuda'] ) );
            } else {
                $pagare = $this->pagare_m->get_by ( array ('idcuenta' => $idcuenta, 'monto_deuda' => $datos ['monto_deuda'] ) );
            }

			//print_r($pagare);
			if ($datos['n_pagare']!=''){
				$datos['n_pagare'] = trim ( str_replace ( '.', '', $datos['n_pagare'] ) );
			}
	
			if (count ( $pagare ) == '1') {
				$idpagare = $pagare->idpagare;
			}
			
			
			if ($idpagare != '') {
				if ($datos['monto_deuda']>0){
					$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
					//print_r($datos);
					$this->pagare_m->update ( $idpagare, $datos+array('idcuenta'=>$idcuenta,'activo'=>'S'), TRUE, TRUE );
				}
			} else {
				$datos = array_merge ( $datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->pagare_m->insert ( $datos+array('idcuenta'=>$idcuenta,'activo'=>'S'), TRUE, TRUE );
			}
	 	}
		return $idpagare;
	
	}


	/*
	  
	public function revisar_tribunales($datos){
	
	    $idtribunal= '';
		 
		// if($nodo->nombre=='fullpay'){
		$tribunal = $this->tribunales_m->get_by(array( 'abr' => $datos['abr'] ));
		// }
	   

	      
	   
		if (count($tribunal) == '1' ){
	       $idtribunal=$tribunal->id;		
	    }	
			
		return $idtribunal;   	
	      
	}  
*/

	
	public function revisar_distrito_tanner($datos){
	
	    $idtribunal= '';
		 
		// if($nodo->nombre=='fullpay'){
		$tribunal = $this->tribunales_m->get_by(array( 'tribunal' => $datos['abr'], 'padre' => '0' ));
		// }
	    
		/*elseif($nodo->nombre=='swcobranza'){
		$tribunal = $this->tribunales_m->get_by(array( 'tribunal' => $datos['abr'] ));
		}*/
	      
	   
		if (count($tribunal) == '1' ){
	       $idtribunal=$tribunal->id;		
	    }	
			/*if ($idprocurador != '') {
				$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->procurador_m->update ( $idprocurador, $datos, TRUE, TRUE );
			} else {
                $datos = array_merge ($datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->procurador_m->insert($datos,TRUE,TRUE );
			}	*/
		return $idtribunal;   	
	      
	  }  	
	
	public function revisar_tribunales_tanner($datos){
	
	    $idtribunal= '';
		 
		// if($nodo->nombre=='fullpay'){
		$tribunal = $this->tribunales_m->get_by(array( 'tribunal' => $datos['abr'], 'padre' => $datos['jurisdiccion'] ));
		// }
	   
		/*elseif($nodo->nombre=='swcobranza'){
		$tribunal = $this->tribunales_m->get_by(array( 'tribunal' => $datos['abr'] ));
		}*/
	      
	   
		if (count($tribunal) == '1' ){
	       $idtribunal=$tribunal->id;		
	    }	
			/*if ($idprocurador != '') {
				$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->procurador_m->update ( $idprocurador, $datos, TRUE, TRUE );
			} else {
                $datos = array_merge ($datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->procurador_m->insert($datos,TRUE,TRUE );
			}	*/
		return $idtribunal;   	
	      
	  }  	
	  
	public function revisar_procurador($datos){
	
	    $idprocurador= '';
		 
	    $procurador = $this->administradores_m->get_by(array( 'id' => $datos['id'] ));
	
	    if (count($procurador) == '1' ){
	     $idprocurador=$procurador->id;		
	    }		 	
	    //print_r($procurador);
		/*if ($idprocurador != '') {
			$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
			$this->procurador_m->update ( $idprocurador, $datos, TRUE, TRUE );
		} else {
			$datos = array_merge ($datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
			$this->procurador_m->insert($datos,TRUE,TRUE );
		}*/	
		//print_r($procurador);
		//echo $this->db->last_query();	
		//echo $idprocurador;
		return $idprocurador;   	
	      
	  }  	
	   
	public function importar_excel_nodo_fullpay_tanner(){
		
		//$this->output->enable_profiler ( TRUE );
		//echo 'entrrrrrro';
		

		$this->load->helper ( 'excel_reader2' );
		$array_return = array ();
		$array_return['cuentas_insert'] = 1;
		$array_return['cuentas_update'] = 1;
		$array_return['usuarios_insert'] = 1;
		$array_return['usuarios_update'] = 1;
		
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'administradores_m' );
		$this->load->model ( 'cuentas_historial_m' );
		$this->load->model ( 'pagare_m' );
		$this->load->model ( 'vehiculos_m' );
		$this->load->model ( 'personal_m' );
		$this->load->model ( 'inmueble_m' );
		
		$this->data ['operacion'] = FALSE;
		
		$rows_insert = array ();
		
		// ojo ss
		$uploadpath = "./uploads/importar.xls";
		
		$excel = new Spreadsheet_Excel_Reader ( $uploadpath );
		$rowcount = $excel->rowcount ( $sheet_index = 0);
		$colcount = $excel->colcount ( $sheet_index = 0 );
		//print_r($excel);
		//echo $rowcount;
		for($i = 2; $i <= $rowcount; $i ++) {
			
			//echo $i.'==';	
			$arreglo_cuenta = array();			

			$mandante = '';
			$rut = '';
			$id = '';
			$dv = '';
			$apellido_paterno = '';
			$apellido_materno = '';
			$nombres = '';
			$telefono = '';
			$celular = '';
			$fecha_asignacion = '';
			$tipo_dda = '';
			$exhorto = '';
			$tribunal = '';
			$rol = '';
			$procurador = '';
			$estado = '';
			
			$razon_social = trim ( $excel->val ( $i, 'A', 0 ) );
		    $id = trim ( $excel->val ( $i, 'B', 0 ) );	
			$rut = trim ( str_replace ( '.', '', $excel->val ( $i, 'C', 0 ) ) );
	
			
			//$rut = $rut.'-'.$dv;
			$apellido_paterno = trim ( $excel->val ( $i, 'D', 0 ) );
			$apellido_materno = trim ( $excel->val ( $i, 'E', 0 ) );
			$nombres = trim ( $excel->val ( $i, 'F', 0 ) );
			$telefono = trim ( $excel->val ( $i, 'G', 0 ) );
			$celular = utf8_encode ( trim ( $excel->val ( $i, 'H', 0 ) ) );
			$direccion = trim ( $excel->val ( $i, 'I', 0 ) );
			//$numero_direccion = trim ( $excel->val ( $i, 'J', 0 ) );
			//$villa = trim ( $excel->val ( $i, 'K', 0 ) );
			$comuna = trim ( $excel->val ( $i, 'J', 0 ) );
			$comuna =  utf8_encode ($comuna);
			$ciudad = trim ( $excel->val ( $i, 'K', 0 ) );
			$direccion = $direccion;// . ' ' . $numero_direccion . ' ' . $villa;
			$fecha_asignacion = trim ( $excel->val ( $i, 'L', 0 ) );
			if ($fecha_asignacion != '') {
				$fecha_asignacion = date ( "Y-m-d", strtotime ( $fecha_asignacion ) );
			}
			$tipo_dda = trim ( $excel->val ( $i, 'M', 0 ) );
			$exhorto = trim ( $excel->val ( $i, 'N', 0 ) );

            $rol = trim ( $excel->val ( $i, 'O', 0 ) );
			$tribunal = trim ( $excel->val ( $i, 'P', 0 ) );
			$jurisdiccion = trim ( $excel->val ( $i, 'Q', 0 ) );

            $estado = trim ( $excel->val ( $i, 'R', 0 ) );
			$procurador = trim ( $excel->val ( $i, 'S', 0 ) );

            $rolexhorto = trim ( $excel->val ( $i, 'T', 0 ) );
			$tribunalexhorto = trim ( $excel->val ( $i, 'U', 0 ) );
			$jurisdiccionexhorto = trim ( $excel->val ( $i, 'V', 0 ) );

			//pagare1
			$n_pagare1 = trim ( $excel->val ( $i, 'W', 0 ) );
			$monto_pagare1 = trim ( $excel->val ( $i, 'X', 0 ) );
			/*$fecha_asignacion1 = trim ( $excel->val ( $i, 'Y', 0 ) );
			if ($fecha_asignacion1 != '') {
				$fecha_asignacion1 = date ( "Y-m-d", strtotime ( $fecha_asignacion1 ) );
			}*/
			$fecha_suscripcion = trim ( $excel->val ( $i, 'Y', 0 ) );
			if ($fecha_suscripcion != '') {
				$fecha_suscripcion = date ( "Y-m-d", strtotime ( $fecha_suscripcion ) );
			}
            //FECHA SUSCRIPCIÓN 	FECHA AUTORIZACIÓN NOTARIO	N° CUOTAS	VALOR CUOTAS	INTERÉS	FECHA 1° VENCIMIENTO 	
			//CUOTAS PAGADAS 	1° CUOTA IMPAGA	VENCIMIENTO 1° CUOTA IMPAGA 

			$fecha_autorizacon_notario = trim ( $excel->val ( $i, 'Z', 0 ) );
            if ($fecha_autorizacon_notario != '') {
				$fecha_autorizacon_notario = date ( "Y-m-d", strtotime ( $fecha_asignacion1 ) );
			}
			
			$n_cuota = trim ( $excel->val ( $i, 'AA', 0 ) );
            $valor_cuota = trim ( $excel->val ( $i, 'AB', 0 ) );
			$interes = trim ( $excel->val ( $i, 'AC', 0 ) );
            $fecha_vencimiento1 = trim ( $excel->val ( $i, 'AD', 0 ) );
			if ($fecha_vencimiento1 != '') {
				$fecha_vencimiento1 = date ( "Y-m-d", strtotime ( $fecha_vencimiento1 ) );
			}
    		$cuota_pagada = trim ( $excel->val ( $i, 'AE', 0 ) );
            $prim_cuota_impaga = trim ( $excel->val ( $i, 'AF', 0 ) );
			$vencimiento_prim_cuota_impaga = trim ( $excel->val ( $i, 'AG', 0 ) );
            if ($vencimiento_prim_cuota_impaga != '') {
				$vencimiento_prim_cuota_impaga = date ( "Y-m-d", strtotime ( $vencimiento_prim_cuota_impaga ) );
			}
			$saldo_insoluto = trim ( $excel->val ( $i, 'AH', 0 ) );
			
			$n_pagare2 = trim ( $excel->val ( $i, 'AI', 0 ) );
			$monto_pagare2 = trim ( $excel->val ( $i, 'AJ', 0 ) );
			//$fecha_asignacion2 = trim ( $excel->val ( $i, 'AK', 0 ) );
			if ($fecha_asignacion2 != '') {
				$fecha_asignacion2 = date ( "Y-m-d", strtotime ( $fecha_asignacion1 ) );
			}
			$fecha_suscripcio2 = trim ( $excel->val ( $i, 'AK', 0 ) );
			if ($fecha_suscripcion2 != '') {
				$fecha_suscripcion2 = date ( "Y-m-d", strtotime ( $fecha_suscripcion2 ) );
			}


			$fecha_autorizacon_notario2 = trim ( $excel->val ( $i, 'AL', 0 ) );
            if ($fecha_autorizacon_notario2 != '') {
				$fecha_autorizacon_notario2 = date ( "Y-m-d", strtotime ( $fecha_asignacion2 ) );
			}
			$n_cuota2 = trim ( $excel->val ( $i, 'AM', 0 ) );
            $valor_cuota2 = trim ( $excel->val ( $i, 'AN', 0 ) );
			$interes2 = trim ( $excel->val ( $i, 'AO', 0 ) );
            $fecha_vencimiento2 = trim ( $excel->val ( $i, 'AP', 0 ) );
			if ($fecha_vencimiento2 != '') {
				$fecha_vencimiento2 = date ( "Y-m-d", strtotime ( $fecha_vencimiento2 ) );
			}
    		$cuota_pagada2 = trim ( $excel->val ( $i, 'AQ', 0 ) );
            $prim_cuota_impaga2 = trim ( $excel->val ( $i, 'AR', 0 ) );
			$vencimiento_prim_cuota_impaga2 = trim ( $excel->val ( $i, 'AS', 0 ) );
            if ($vencimiento_prim_cuota_impaga2 != '') {
				$vencimiento_prim_cuota_impaga2 = date ( "Y-m-d", strtotime ( $vencimiento_prim_cuota_impaga2 ) );
			}
			$saldo_insoluto2 = trim ( $excel->val ( $i, 'AT', 0 ) );
			
			/*
			AI)   Nº PAGARE 		AJ)   MONTO PAGARE 		AK)   FECHA SUSCRIPCIÓN 		AL)   FECHA AUTORIZACIÓN NOTARIO		AM)   N° CUOTAS
			AN)   VALOR CUOTAS		AO)   INTERÉS			AP)   FECHA 1° VENCIMIENTO		AQ)   CUOTAS PAGADAS					AR)   1° CUOTA IMPAGA
			AS)   VENCIMIENTO 1° CUOTA IMPAGA				AT)   SALDO INSOLUTO
			*/
			
			$fecha_contr = trim ( $excel->val ( $i, 'AU', 0 ) );
			if ($fecha_contr != '') {
				$fecha_contr = date ( "Y-m-d", strtotime ( $fecha_contr ) );
			}
            $n_repertorio = trim ( $excel->val ( $i, 'AV', 0 ) );
			$tipo_vehiculo = trim ( $excel->val ( $i, 'AW', 0 ) );
            $marca = trim ( $excel->val ( $i, 'AX', 0 ) );
            $modelo = trim ( $excel->val ( $i, 'AY', 0 ) );
			$motor = trim ( $excel->val ( $i, 'AZ', 0 ) );
			$chasis = trim ( $excel->val ( $i, 'BA', 0 ) );
            $color = trim ( $excel->val ( $i, 'BB', 0 ) );
            $anio = trim ( $excel->val ( $i, 'BC', 0 ) );
			$placaunica = trim ( $excel->val ( $i, 'BD', 0 ) );
            $placapatente = trim ( $excel->val ( $i, 'BE', 0 ) );
            $fecha_exigible = trim ( $excel->val ( $i, 'BF', 0 ) );
			if ($fecha_exigible != '') {
				$fecha_exigible = date ( "Y-m-d", strtotime ( $fecha_exigible ) );
			}
			

			/*TIPO GARANTIA PERSONAL	
			NOMBRE GARANTE	
			RUT GARANTE	
			DOMICILIO GARANTE*/

			$tipogarante = trim ( $excel->val ( $i, 'BG', 0 ) );
			$nombregarante = trim ( $excel->val ( $i, 'BH', 0 ) );
            $rutgarante = trim ( $excel->val ( $i, 'BI', 0 ) );
            $domicioliogarante = trim ( $excel->val ( $i, 'BJ', 0 ) );
			
			//print_r($_POST);
			
			// INICIO MANDANTE
			$arreglos_mandante = array ();
			$arreglos_mandante ['codigo_mandante'] = $razon_social;
			//print_r($arreglos_mandante);
			$idmandante = '';
			$idmandante = $this->revisar_mandante ( $arreglos_mandante );
			// FIN MANDANTE
			if($idmandante=='')
			{
				$idmandante=$_POST['id_mandante'];
			}
			
			if ($idmandante != '') {
				// INICIO USUARIO
				$datos_usuario = array ();
				$datos_usuario ['nombre'] = $nombres;
				$datos_usuario ['apellido_paterno'] = $apellido_paterno;
				$datos_usuario ['apellido_materno'] = $apellido_materno;
				$datos_usuario ['rut'] = $rut;
				$datos_usuario ['ciudad'] = $ciudad;
				$datos_usuario ['direccion'] = $direccion;
				$idusuario = $this->revisar_usuario ( $rut, $datos_usuario );

				// FIN USUARIO
				//print_r($datos_usuario);
				//echo isset($datos_usuario)."s";
				if(!isset($nombres))
				{
					//echo $nombres;
				}else
				{
					//echo $nombres."no hay nada<br>";
					//break;					
				}
				//echo $procurador;					
				// INICIO PROCURADOR
				$idprocurador = '';
				$datos_procurador = array ();
				$datos_procurador ['id'] = $procurador;
				//echo $procurador;
				
				$idprocurador = $this->revisar_procurador ( $datos_procurador );
				if($idprocurador == ''){
				 $idprocurador = '8';
				} 
				
				//echo $idprocurador;
				// FIN PROCURADOR
				//die();
				// INICIO DISTRITO
				$idtribunal = '';
				$datos_tribunal = array ();
				$datos_tribunal ['abr'] = $jurisdiccion;				
				$idtribunal = $this->revisar_distrito_tanner ( $datos_tribunal );
				//echo $this->db->last_query();	
				// FIN TRIBUNAL
				
				//echo $idjurisdiccion;
				// INICIO TRIBUNAL
				$idjurisdiccion = '';
				$datos_tribunal = array ();
				$datos_tribunal ['abr'] = $tribunal;
				$datos_tribunal ['jurisdiccion'] = $idtribunal;
				$idjurisdiccion = $this->revisar_tribunales_tanner ( $datos_tribunal );
				//echo $this->db->last_query();	
				// FIN TRIBUNAL

				
				// DATOS CUENTA
				$datos_cuentas = array ();
				if ($idtribunal != '') {
					$datos_cuentas ['id_tribunal'] = $idtribunal;
				}
				if ($idtribunal != '') {
					$datos_cuentas ['id_distrito'] = $idjurisdiccion;
				}
				if ($idprocurador != '') {
					$datos_cuentas ['id_procurador'] = $idprocurador;
				}
				$datos_cuentas ['rol'] = $rol;
				
//ECHO $jurisdiccionexhorto;
				// INICIO DISTRITO
				$idtribunalExh = '';
				$datos_tribunal = array ();
				$datos_tribunal ['abr'] = $jurisdiccionexhorto;				
				$idtribunalExh = $this->revisar_distrito_tanner ( $datos_tribunal );
				// FIN TRIBUNAL	
				
				// INICIO TRIBUNAL
				$idjurisdiccionExh = '';
				$datos_jursdiccion = array ();
				$datos_tribunal ['abr'] = $tribunalexhorto;
				$datos_tribunal ['jurisdiccion'] = $idtribunalExh;
				$idjurisdiccionExh = $this->revisar_tribunales_tanner ( $datos_tribunal );
				// FIN TRIBUNAL			
				
                //DATOS EXHORTO
                if ($idtribunalExh != '') {
					$datos_cuentas ['id_tribunal_ex'] = $idjurisdiccionExh;
				}
				if ($idjurisdiccionExh != '') {
					$datos_cuentas ['id_distrito_ex'] = $idtribunalExh;
				}
				$datos_cuentas ['rolE'] = $rolexhorto;
				//FIN DATOS EXHORTO
				
				$datos_cuentas ['id_estado_cuenta'] = $estado;
				$datos_cuentas ['fecha_asignacion'] = $fecha_asignacion;
				
				
				if ($tipo_dda == 'P') {
					$datos_cuentas ['tipo_demanda'] = '1';
				} elseif ($tipo_dda == 'C') {
					$datos_cuentas ['tipo_demanda'] = '0';
				}
				if ($exhorto == 'C') {
					$datos_cuentas ['exorto'] = '1';
				} elseif ($exhorto == 'S') {
					$datos_cuentas ['exorto'] = '0';
				}
				if ($datos_cuentas ['fecha_asignacion'] != '') {
					$datos_cuentas ['fecha_asignacion'] = $fecha_asignacion;
				} else {
					$datos_cuentas ['fecha_asignacion'] = date ( 'Y-m-d H:i:s' );
				}
				if ($datos_cuentas ['fecha_asignacion'] != '') {
					$datos_cuentas ['fecha_asignacion'] = $fecha_asignacion;
				} else {
					$datos_cuentas ['fecha_asignacion'] = date ( 'Y-m-d H:i:s' );
				}
				
			
				//print_r($datos_cuentas);
				//$datos_cuentas ['noperacion'] = $id;
				$idcuenta = $this->revisar_cuentas_tanner ( $idmandante, $idusuario, $datos_cuentas, $idprocurador, $id );
				
				// INICIO GARANTIA VEHICULO
				$arreglos_garantia_vehiculo = array ();
				$arreglos_garantia_vehiculo ['codigo_mandante'] = $razon_social;

				$idmandante = '';
				$idmandante = $this->revisar_mandante ( $arreglos_garantia_vehiculo );
				// FIN GARANTIA
				

				//echo $this->db->last_query();	
				if ($idcuenta != '') {
					// INICIO TELEFONO
					$datos_telefono = array ();
					$datos_telefono ['id_cuenta'] = $idcuenta;
					$datos_telefono ['numero'] = $telefono;
					$datos_telefono ['tipo'] = 1;
					$this->revisar_telefonos ( $idcuenta, $datos_telefono );
					$datos_telefono ['id_cuenta'] = $idcuenta;
					$datos_telefono ['numero'] = $celular;
					$datos_telefono ['tipo'] = 3;
					$this->revisar_telefonos ( $idcuenta, $datos_telefono );
					//print_r($datos_telefono);
					//die();
					// FIN TELEFONO
					
					// INICIO DIRECCION
					$datos_direccion = array ();
					$idcomuna = '';
					$comuna = $this->comunas_m->get_by ( array ('nombre' => $comuna ) );
					if (count ( $comuna ) == '1') {
						$idcomuna = $comuna->id;
					}
					
					$datos_direccion ['tipo'] = '1';
					$datos_direccion ['id_cuenta'] = $idcuenta;
					$datos_direccion ['id_comuna'] = $idcomuna;
					$datos_direccion ['direccion'] = $direccion;
					// print_r($datos_direccion); 
					$this->revisar_direccion ( $idcuenta, $datos_direccion );
			
					if ($monto_pagare1!=''){
						if ($n_pagare1==''){
							$n_pagare1=$rut.'-1';
						}
						$datos_pagare = array ();
						$datos_pagare ['n_pagare'] = $n_pagare1;
						$datos_pagare ['monto_deuda'] = $monto_pagare1;
						$datos_pagare ['fecha_asignacion'] = $fecha_asignacion1;
						$datos_pagare ['fecha_suscripcion'] = $fecha_suscripcion;				
						$datos_pagare ['fecha_autorizacion'] = $fecha_autorizacon_notario;
						$datos_pagare ['numero_cuotas'] = $n_cuota;
						$datos_pagare ['valor_primera_cuota'] = $valor_cuota;
						$datos_pagare ['fecha_vencimiento'] = $fecha_vencimiento1;
						$datos_pagare ['ultima_cuota_pagada'] = $cuota_pagada;
						$datos_pagare ['fecha_pago_ultima_cuota'] = $vencimiento_prim_cuota_impaga;
						$datos_pagare ['valor_ultima_cuota'] = $prim_cuota_impaga;
						$datos_pagare ['saldo_deuda'] = $saldo_insoluto;
						$datos_pagare ['tasa_interes'] = $interes;							
						
						$this->revisar_pagare ( $idcuenta, $datos_pagare, $idmandante );
					
					}
					if ($monto_pagare2!=''){
						if ($n_pagare2==''){
							$n_pagare2=$rut.'-2';
						}
						
						$datos_pagare = array ();
						$datos_pagare ['n_pagare'] = $n_pagare2;
						$datos_pagare ['monto_deuda'] = $monto_pagare2;
						$datos_pagare ['fecha_asignacion'] = $fecha_asignacion2;
						$datos_pagare ['fecha_suscripcion'] = $fecha_suscripcion2;				
						$datos_pagare ['fecha_autorizacion'] = $fecha_autorizacon_notario2;
						$datos_pagare ['numero_cuotas'] = $n_cuota2;
						$datos_pagare ['valor_primera_cuota'] = $valor_cuota2;
						$datos_pagare ['fecha_vencimiento'] = $fecha_vencimiento2;
						$datos_pagare ['ultima_cuota_pagada'] = $cuota_pagada2;
						$datos_pagare ['fecha_pago_ultima_cuota'] = $vencimiento_prim_cuota_impaga2;
						$datos_pagare ['valor_ultima_cuota'] = $prim_cuota_impaga2;
						$datos_pagare ['saldo_deuda'] = $saldo_insoluto2;
						$datos_pagare ['tasa_interes'] = $interes2;							
						
						/*
						$datos_pagare = array ();
						$datos_pagare ['n_pagare'] = $n_pagare2;
						$datos_pagare ['monto_deuda'] = $monto_pagare2;
						$datos_pagare ['fecha_asignacion'] = $fecha_asignacion2;
						$datos_pagare ['fecha_vencimiento'] = $fecha_vencimiento2;*/
						$this->revisar_pagare ( $idcuenta, $datos_pagare, $idmandante );
					}
					
					if ($fecha_contr!=''){
								
						$datos_garantia_vehiculo ['fecha_cont'] 		= $fecha_contr;
						$datos_garantia_vehiculo ['n_repertorio'] 		= $n_repertorio;
						$datos_garantia_vehiculo ['tipo_vehiculo'] 		= $tipo_vehiculo;
						$datos_garantia_vehiculo ['marca'] 				= $marca;						
						$datos_garantia_vehiculo ['modelo'] 			= $modelo;
						$datos_garantia_vehiculo ['n_motor'] 			= $motor;
						$datos_garantia_vehiculo ['n_chachis'] 			= $chasis;
						$datos_garantia_vehiculo ['color'] 				= $color;
						$datos_garantia_vehiculo ['anio'] 				= $anio;
						$datos_garantia_vehiculo ['placaunica'] 		= $placaunica;
						$datos_garantia_vehiculo ['placapatente'] 		= $placapatente;
						$datos_garantia_vehiculo ['fechaexigible'] 		= $fecha_exigible;
						//print_r($datos_garantia_vehiculo);
						$this->revisar_garantias ( $idcuenta, $datos_garantia_vehiculo, $idmandante  );
					}
				
					if ($tipogarante!=''){
								
						$datos_aval_vehiculo ['tipog'] 		= $tipogarante;
						$datos_aval_vehiculo ['nombreg'] 	= $nombregarante;
						$datos_aval_vehiculo ['rutg'] 		= $rutgarante;
						$datos_aval_vehiculo ['domiciliog']	= $domicioliogarante;						
						
						$this->revisar_aval_garantias ( $idcuenta, $datos_aval_vehiculo, $idmandante  );
					}				
				}
			}
		} // FIN FOR
	

	}
	
	// INICIO USUARIO 
	public function revisar_aval_garantias($idcuenta,$datos=array(),$idmandante){
		$idgarantia = '';
		//print_r($datos);
	 	if (isset($datos['tipog'])){
			//$datos['monto_deuda'] = trim ( str_replace ( ',', '.', $datos['monto_deuda'] ) );

            $mandante = $this->mandantes_m->get_by ( array('id'=>$idmandante));
            if (count($mandante)==1 && $datos['tipog']!=''){
                $garantia = $this->personal_m->get_by ( array ('id_cuenta' => $idcuenta, 'tipog'=>$datos['tipog'], 'rutg' => $datos ['rutg'] ) );
            } else {
                $garantia = $this->personal_m->get_by ( array ('id_cuenta' => $idcuenta, 'tipog' => $datos ['tipog'] ) );
            }

			//print_r($garantia);			
	
			if (count ( $garantia ) == '1') {
				$idgarantia = $garantia->id;
			}
			
			if ($idgarantia != '') {
				if ($datos['tipog']!=''){
					$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
					//print_r($datos);
					$this->personal_m->update ( $idgarantia, $datos+array('id_cuenta'=>$idcuenta,'activo'=>'S'), TRUE, TRUE );
					
				}
			} else {
				$datos = array_merge ( $datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->personal_m->insert ( $datos+array('id_cuenta'=>$idcuenta,'activo'=>'S'), TRUE, TRUE );
			}
			//echo $this->db->last_query();	
	 	}
		return $idcuenta;
     }
	// TERMINO USUARIO
	
	// INICIO USUARIO 
	public function revisar_garantias($idcuenta,$datos=array(),$idmandante){
		$idgarantia = '';
		
	 	if (isset($datos['fecha_cont']) && $datos['fecha_cont']>0){
			//$datos['monto_deuda'] = trim ( str_replace ( ',', '.', $datos['monto_deuda'] ) );

            $mandante = $this->mandantes_m->get_by ( array('id'=>$idmandante));
            if (count($mandante)==1 && $datos['fecha_cont']!=''){
                $garantia = $this->vehiculos_m->get_by ( array ('id_cuenta' => $idcuenta, 'fecha_cont'=>$datos['fecha_cont'], 'n_motor' => $datos ['n_motor'] ) );
            } else {
                $garantia = $this->vehiculos_m->get_by ( array ('id_cuenta' => $idcuenta, 'fecha_cont' => $datos ['fecha_cont'] ) );
            }

			//print_r($pagare);
			
	
			if (count ( $garantia ) == '1') {
				$idgarantia = $garantia->id;
			}
			
			if ($idgarantia != '') {
				if ($datos['fecha_cont']>0){
					$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
					//print_r($datos);
					$this->vehiculos_m->update ( $idgarantia, $datos+array('id_cuenta'=>$idcuenta,'activo'=>'S'), TRUE, TRUE );
				}
			} else {
				$datos = array_merge ( $datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->vehiculos_m->insert ( $datos+array('id_cuenta'=>$idcuenta,'activo'=>'S'), TRUE, TRUE );
			}
	 	}
		return $idcuenta;
     }
	// TERMINO USUARIO
	
    ###################### IMPORTAR EXCEL ESTADO DIARIO ###########################
	###############################################################################
	public function importar_excel_nodo_fullpay($abogado=''){
		//$this->output->enable_profiler ( TRUE );
		$uploadpath = "./uploads/importar.xls";
		$this->load->library('PHPExcel');
		$excel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($uploadpath);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$array_return = array ();
		$array_return['cuentas_insert'] = 0;
		$array_return['no_insert'] = 0;
		$array_return['usuarios_insert'] = 0;
		$array_return['usuarios_update'] = 0;
		
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'abogados_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'cuentas_aux' );
		$this->load->model ( 'cuentas_na' );
		$this->load->model ( 'tribunales_pjud_m');
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'administradores_m' );
		$this->load->model ( 'cuentas_historial_m' );
		$this->load->model ( 'pagare_m' );
		
		$this->data ['operacion'] = FALSE;
		
		$rows_insert = array();

		$excel = $objReader->load($uploadpath);

		$excel->setActiveSheetIndex(2);
		$sheet = $excel->getActiveSheet();
		$rowcount = $sheet->getHighestRow();

		$count = 0; $cad = '';
		for($i = 2; $i <= $rowcount; $i ++) {
			//echo $i.'==';	
			$rol = '';
			$tribunal = '';
			$fecha_ingreso = '';
			$caratulado = '';
		
			$rol = trim ( $sheet->getCell ('A'. $i) ->getValue());
			$fecha_ingreso = trim ( $sheet->getCell ('B'. $i) ->getValue());
			$caratulado = trim ( $sheet->getCell ('C'. $i) ->getValue());
			if($fecha_ingreso != ''){
				$fecha = explode('/', $fecha_ingreso);
				if(count ($fecha) == 3)
				$fecha_ingreso = $fecha[0]. '-' .$fecha[1]. '-' .$fecha[2];
				$fecha = explode('-', $fecha_ingreso);
				if(count ($fecha) == 3)
				$fecha_ingreso = $fecha[0]. '-' .$fecha[1]. '-' .$fecha[2];
				//print_r($fecha_ingreso);
				$fecha_ingreso = date ( "Y-m-d", strtotime ( $fecha_ingreso ) );
				//print_r($fecha_ingreso);
				//die;
			}
			$tribunal = trim ( $sheet->getCell ('D'. $i )->getValue());
		
			$datos_cuentas ['rol'] = $rol;
			$datos_cuentas ['tribunal'] = $tribunal;
			$datos_cuentas ['fecha_ingreso'] = $fecha_ingreso;
			$datos_cuentas ['caratulado'] = $caratulado;
			$datos_cuentas['id_abogado'] = $abogado;

			if($rol != '' && $tribunal != '')
				$idcuenta = $this->revisar_cuentas($datos_cuentas, $rol);
				//$idcuenta = $this->revisar_cuentas_aux($datos_cuentas, $rol);
			else
				break;
			$count++;
			//$cad .= $idcuenta.',';
		} // FIN FOR
		//die($cad);
		
		$no_insert = $this->array_return['no_insert'];
		$datos_abog = array();
		$id_abogado = $datos_cuentas['id_abogado'];
		$abogad = $this->abogados_m->get_by(array("id" => $id_abogado));
		$datos_abog['total_registros'] = $count;
		$datos_abog['total_elim'] = intval($abogad->total_elim) + intval($no_insert);
		$datos_abog['total_import'] = intval($this->array_return['cuentas_insert']) + intval($no_insert);
		$this->abogados_m->update($id_abogado, $datos_abog, false, true);
	}

	public function importar_excel_nodo_fullpay_etapas(){
	  
	   // $this->output->enable_profiler ( TRUE );
	   // echo 'entro';
		
		$this->load->helper ( 'excel_reader2' );
		$array_return = array ();
		$array_return ['usuarios_insert'] = 0;
		$array_return ['usuarios_update'] = 0;
		
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ('comunas_m' );
		$this->load->model ('etapas_m' );
		$this->load->model ('administradores_m' );
		$this->load->model ('cuentas_etapas_m' );
		$this->load->model ('cuentas_historial_m' );       
	
		$this->data ['operacion'] = FALSE;
		
		$rows_insert = array ();
		$rows_insert = array ();

		// ojo ss
		$uploadpath = "./uploads/importar.xls";

		
		$excel = new Spreadsheet_Excel_Reader ( $uploadpath );
		$rowcount =  $excel->rowcount ( $sheet_index = 0);   
		$colcount = $excel->colcount ( $sheet_index = 0 );	

		for($i = 2; $i <= $rowcount; $i++) {
			
			//echo $i.'==';	
			$razon_social  = '';
			$rut = '';
			$procurador = '';
			$tribunal = '';
			$rol = '';
			$apellido_paterno = '';
			$apellido_materno = '';
			$nombre = '';
			$comuna = '';
			$ciudad = '';
			$fecha_asignacion = '';
			$tipo_dda = '';
			$exhorto = '';
			$etapas ='';
			$fecha = '';
			
			
			$razon_social = trim ( $excel->val ( $i, 'A', 0 ) );
			$rut = trim ( str_replace ( '.', '', $excel->val ( $i, 'B', 0 ) ) );
			
			$procurador = trim ( $excel->val ( $i, 'C', 0 ) );
			$tribunal = trim ( $excel->val ( $i, 'D', 0 ) );
			$rol = trim ( $excel->val ( $i, 'E', 0 ) );
			$apellido_paterno = trim ( $excel->val ( $i, 'F', 0 ) );
			$apellido_materno = trim ( $excel->val ( $i, 'G', 0 ) );
			$nombre = trim ( $excel->val ( $i, 'H', 0 ) );
			$comuna= utf8_encode ( trim ( $excel->val ( $i, 'I', 0 ) ) );
			$ciudad = utf8_encode ( trim ( $excel->val ( $i, 'J', 0 ) ) );
			$fecha_asignacion = trim ( $excel->val ( $i, 'K', 0 ) );
			if($fecha_asignacion != ''){
			$fecha_asignacion = date ( "Y-m-d", strtotime ( $fecha_asignacion ) );
			}
			$tipo_dda = trim ( $excel->val ( $i, 'L', 0 ) );
			$exhorto = trim ( $excel->val ( $i, 'M', 0 ) );
			$etapas = trim ( $excel->val ( $i, 'N', 0 ) );
			$fecha = trim ( $excel->val ( $i, 'O', 0 ) );
			$fecha = date ( "Y-m-d", strtotime ( $fecha ) );
		
	  
	        // mandante
	      	$arreglos_mandante = array();
		  	$arreglos_mandante ['razon_social'] = $razon_social;
			
			
			$idmandante ='';    
			$idmandante = $this->revisar_mandante($arreglos_mandante); 
	              
            // usuario 	
			$datos_usuario = array();
			$datos_usuario['nombre'] = $nombre;
			$datos_usuario['apellido_paterno'] = $apellido_paterno;
			$datos_usuario['apellido_materno'] = $apellido_materno;
			$datos_usuario['rut'] = $rut;
			$idusuario = $this->revisar_usuario($rut,$datos_usuario);
		
			//procurador
			$idprocurador = '';
			$datos_procurador = array();
			$datos_procurador['id'] = utf8_encode($procurador);
			$idprocurador = $this->revisar_procurador($datos_procurador);
			    
			//tribunal 
			$idtribunal ='';
			$datos_tribunal =array();
			$datos_tribunal['abr'] = $tribunal; 
			$idtribunal = $this->revisar_tribunales($datos_tribunal); 
				
			// die();
	        // cuenta
			$datos_cuentas = array ();
			$datos_cuentas['id_tribunal'] = $idtribunal;
			$datos_cuentas['id_procurador'] = $idprocurador;
			$datos_cuentas['rol'] = $rol;
			$datos_cuentas['fecha_asignacion'] = $fecha_asignacion;
			     
		    if ($tipo_dda == 'PROPIA') {
				$datos_cuentas ['tipo_demanda'] = '1';
			} elseif ($tipo_dda == 'CEDIDA') {
				$datos_cuentas ['tipo_demanda'] = '0';
			}
		          
		    // print_r($datos_cuentas);  
		    // die();
		         
		    if ($exhorto == 'C') {
				$datos_cuentas ['exorto'] = '1';
			} elseif ($exhorto == 'S') {
				$datos_cuentas ['exorto'] = '0';
			}
			if ($datos_cuentas ['fecha_asignacion'] != '') {
				$datos_cuentas ['fecha_asignacion'] = $fecha_asignacion;
			} else {
				$datos_cuentas ['fecha_asignacion'] = date ( 'Y-m-d H:i:s' );
			} 
   	
			$idcuenta = $this->revisar_cuentas($idmandante,$idusuario,$datos_cuentas,$idprocurador);
            
			   
			//etapa 
			$idetapa ='';
			$datos_etapas =array();
			$datos_etapas['etapa'] = $etapas; 
			$idetapa = $this->revisar_etapas($datos_etapas); 
			   
			   
			$idetapa_cuenta = '';
			$datos_etapas_cuentas = array();
			$datos_etapas_cuentas['fecha_etapa'] = $fecha;
			if ($datos_etapas_cuentas ['fecha_etapa'] != '') {
				$datos_etapas_cuentas ['fecha_etapa'] = $fecha;
			}
				
				$idetapa_cuenta = $this->revisar_etapas_cuentas( $datos_etapas_cuentas,$idcuenta,$idetapa);
		}		
      }
      
    public function reglas_gastos(){
        
      	//$this->output->enable_profiler(TRUE);
		$this->load->model ( 'gasto_regla_m' );
	    $this->load->model ( 'mandantes_m' );
        $this->load->model ( 'diligencia_m' );
	    
		$this->load->helper ( 'excel_reader2' );
		
		$array_return = array ();
		$array_return ['gasto_regla_insert'] = 0;
		$array_return ['gasto_regla_update'] = 0;
		
		
		$this->data ['operacion'] = FALSE;
		
		$importacion=uniqid();
		
		//echo 'INICIO IMPORTACIÃ“N<br>';
		//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		$rows_insert = array ();
		$uploadpath = "./uploads/reglas_gastos.xls";
		$excel = new Spreadsheet_Excel_Reader ( $uploadpath );
		$rowcount = $excel->rowcount ( $sheet_index = 0 );
		$colcount = $excel->colcount ( $sheet_index = 0 );
		
		//echo $rowcount.'---<br>';
		//$this->db->truncate('categoria'); 
		//$this->db->truncate('producto');
		//$this->db->truncate('pedido');
		//$this->db->truncate('pedido_producto');
		
		//$this->db->delete('image', array('tipo' => 'producto')); 
		
		//for($i = 48; $i <= 48; $i ++) {
		for($i = 2; $i <= $rowcount; $i++) {//$rowcount
			
			//if ($i%10==0){echo date('d-m-Y H:i:s').' - Leyendo fila '.$i.'...<br>';}
			
			
			$mandante = ''; //A
			$rango1 = ''; //B
			$rango2 = ''; //C
		    $item_gasto = ''; //D
			$diligencia = ''; //E
			$monto_gasto = ''; //F
		
			$mandante = trim ( $excel->val ( $i, 'A', 0 ) );
			$rango1 = trim ( $excel->val ( $i, 'B', 0 ) );
			$rango2 = trim ( $excel->val ( $i, 'C', 0 ) );
			$item_gasto = trim ( $excel->val ( $i, 'D', 0 ) );
			$diligencia = trim ( $excel->val ( $i, 'E', 0 ) );
			$monto_gasto  = trim ( $excel->val ( $i, 'F', 0 ) );
			$monto_gasto = str_replace(array('$',',','.'),'',$monto_gasto);

			$diligencia =  utf8_encode ( $diligencia );
			
			$mandante = $this->mandantes_m->get_by(array('razon_social' => $mandante));
	        $diligencia = $this->diligencia_m->get_by(array('nombre' => $diligencia));

	         $idmandante = '';
	          if (count($mandante) == '1' ){
	   			$idmandante=$mandante->id;		
			   }
	
			  $iddiligencia = ''; 
		       if (count($diligencia) == '1' ){
	   			$iddiligencia=$diligencia->id;		
			   }
			  
			    $idgasto_regla ='';
	         	$idgasto_regla = $this->gasto_regla_m->search_id_record_exist(array('id_diligencia' => $iddiligencia ,'id_mandante' => $idmandante,'rango1'=>$rango1,'rango2'=>$rango2), 'idgasto_regla');				   
			   
			// arr_reglas_gastos
			$arr_reglas_gastos = array ();
			
				if ($idmandante != '' ) {
				$arr_reglas_gastos ['id_mandante'] = $idmandante;
				}
			    
				if ($iddiligencia != '' ) {
				$arr_reglas_gastos ['id_diligencia'] = $iddiligencia;
				}
			
			if ($rango1 != '' ) {
			$arr_reglas_gastos ['rango1'] = $rango1;
			}	
			
			if ($rango2 != '' ) {
			$arr_reglas_gastos ['rango2'] = $rango2;
			}	
			
			if ($monto_gasto != '' ) {
			$arr_reglas_gastos ['monto_gasto'] = $monto_gasto;
			}	
			
		    if ($item_gasto != '' ) {
			$arr_reglas_gastos ['item_gasto'] = $item_gasto;
			}
			
			
			//print_r($arr_reglas_gastos);
		    
			
			
			if ($idgasto_regla == ''|| $idgasto_regla == NULL ) {
				//insert
				$arr_reglas_gastos = array_merge ( $arr_reglas_gastos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				
				//print_r($arr_reglas_gastos);
			    //die();
			   $idgasto_regla = $this->gasto_regla_m->insert ( $arr_reglas_gastos, TRUE, TRUE ); //retorna idpartida ingresada
				$array_return ['gasto_regla_insert'] ++; //contabiliza cuantos ingresos
			} else {
				//update
				$arr_categoria = array_merge ( $arr_reglas_gastos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->gasto_regla_m->update ( $idgasto_regla, $arr_reglas_gastos, TRUE, TRUE );
				$array_return ['gasto_regla_update'] ++; //contabiliza cuantas actualizaciones
			}
       }//for
		if ($array_return ['gasto_regla_insert'] > 0 or $array_return ['gasto_regla_update'] > 0) {
			$array_return ['operacion'] = TRUE;
		
		}
		if ($array_return ['gasto_regla_insert'] > 0 or $array_return ['gasto_regla_update'] > 0) {
			$array_return ['operacion'] = TRUE;
		
		}
	 
      }
    // 12345  
    public function comunas_distritos(){
        
      	//$this->output->enable_profiler(TRUE);
		$this->load->model ( 'comunas_m' );
	    $this->load->model ( 'tribunales_m' );
        $this->load->model ( 'comunas_tribunales_m' );
	    
		$this->load->helper ( 'excel_reader2' );
		
		$array_return = array ();
		$array_return ['comunas_distritos_insert'] = 0;
		$array_return ['comunas_distritos_update'] = 0;
		$array_return ['comunas_insert'] = 0;
		$array_return ['comunas_update'] = 0;
		
		$this->data ['operacion'] = FALSE;
		
		//$importacion=uniqid();
		
		//echo 'INICIO IMPORTACIÃ“N<br>';
		//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		$rows_insert = array ();
		$uploadpath = "./uploads/comuna_distrito.xls";
		$excel = new Spreadsheet_Excel_Reader ( $uploadpath );
		$rowcount = $excel->rowcount ( $sheet_index = 0 );
		$colcount = $excel->colcount ( $sheet_index = 0 );
		
		
		for($i = 2; $i <= $rowcount; $i++) {//$rowcount
			
			$comuna = ''; //A
			$distrito = ''; //B
			
		    $comuna = trim ( $excel->val ( $i, 'A', 0 ) );
			$distrito = trim ( $excel->val ( $i, 'B', 0 ) );
			$comuna =  utf8_encode ( $comuna );
			$distrito =  utf8_encode ( $distrito );
			
			$comuna_s = $this->comunas_m->get_by(array('nombre' => $comuna));
	        $distrito = $this->tribunales_m->get_by(array('tribunal' => $distrito));

	            $idcomuna = '';
	            if (count($comuna_s) == '1' ){
	   			$idcomuna=$comuna_s->id;		
			   }
	
			  $iddistrito = ''; 
		       if (count($distrito) == '1' ){
	   			$iddistrito=$distrito->id;		
			   }
			  
			   $arr_comunas = array ();
		           
			   if ( $comuna != '' ) {
				$arr_comunas['nombre'] =  $comuna;
				} 

			   

				if ($idcomuna == '' || $idcomuna == NULL) {
				$arr_comunas = array_merge ( $arr_comunas, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$idcomunas= $this->comunas_m->insert ( $arr_comunas, TRUE, TRUE ); //retorna idpartida ingresada
				$array_return ['comunas_insert'] ++; //contabiliza cuantos ingresos
			} else {
				$arr_categoria = array_merge ( $arr_comunas, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->comunas_m->update ( $idcomuna, $arr_comunas, TRUE, TRUE );
				$array_return ['comunas_update'] ++; //contabiliza cuantas actualizaciones
			}  
			   
			   
			   
			
			    $idcomunas_distritos ='';
	         	$idcomunas_distritos = $this->comunas_tribunales_m->search_id_record_exist(array('id_comuna' => $idcomuna ,'id_distrito' => $iddistrito), 'id');				   
			   
			// arr_reglas_gastos
			$arr_comunas_distritos = array ();
			
				if ($iddistrito != '' ) {
				$arr_comunas_distritos ['id_distrito'] = $iddistrito;
				}
			    
				if ($idcomuna != '' ) {
				$arr_comunas_distritos ['id_comuna'] = $idcomuna;
				}
			
			//	if($idcomuna != '0' && $iddistrito != '0' ){
				
			if ($idcomunas_distritos == '' || $idcomunas_distritos == NULL ) {
				//insert
				$arr_comunas_distritos = array_merge ( $arr_comunas_distritos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				
			   $idcomunas_distritos = $this->comunas_tribunales_m->insert ( $arr_comunas_distritos, TRUE, TRUE ); //retorna idpartida ingresada
				$array_return ['comunas_distritos_insert'] ++; //contabiliza cuantos ingresos
			} else {
				//update
				$arr_categoria = array_merge ( $arr_comunas_distritos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->comunas_tribunales_m->update ( $idcomunas_distritos, $arr_comunas_distritos, TRUE, TRUE );
				$array_return ['comunas_distritos_update'] ++; //contabiliza cuantas actualizaciones
			} 
		//  }       
		}//for
		
       if ($array_return ['comunas_distritos_insert'] > 0 or $array_return ['comunas_distritos_update'] > 0) {
			$array_return ['operacion'] = TRUE;
		
		}
		if ($array_return ['comunas_distritos_insert'] > 0 or $array_return ['comunas_distritos_update'] > 0) {
			$array_return ['operacion'] = TRUE;
		
		}
	 
    }
   
	function cargar_archivoreceptor( $accion = ''){
	  	
	  if (!$this->session->userdata('usuario_id')){redirect('login');}
		$this->load->model ( 'archivoreceptor_m' );
		$this->data['plantilla'] = 'receptor/';
		$ids = $_POST['id'];
		$view = 'form/editar/'.$ids;
		$this->data['plantilla'].=$view;
		$this->data['archivos'] = array();
		$this->data['error'] = array();
		$this->data['archivo'] = '';
		$this->data['operacion'] = FALSE;
		/* cargar archivo*/
		
		 if ($accion == 'guardar_archivo'){
			$nombre_archivo = date('YmdHis');		
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = '*';
			$config['max_size']	= '5120';
			/*$config['max_width']  = '2048';
			$config['max_height']  = '1080';*/
			$this->load->library('upload', $config);
			//print_r($_POST);
			if (! $this->upload->do_upload ("archivo_1")) {
				$this->data['error'] = array ('error' => $this->upload->display_errors () );
			} else {
				$rut = $_POST['id'];
				$this->data['archivos'] = array($this->upload->data()); 
				//print_r($this->data['archivos']);
				//echo $this->data['archivos'][0]['file_name'];
				$rutaf = './archivos_receptores/'.$rut.'-'.$this->data['archivos'][0]['file_name'];
				$nombref = $rut.'-'.$this->data['archivos'][0]['file_name'];
				rename ( $this->data['archivos'][0]['full_path'], $rutaf );
				if (is_file($this->data['archivos'][0]['full_path'])){
					//unlink ( $this->data['archivos'][0]['full_path'] );
				}
				//file_receptor
				//$datos = array_merge ( $datos, array ('rut' => $rut, 'nombre' => $nombref ) );				
				//$this->archivoreceptor_m->insert( $datos, TRUE, FALSE );
				
				$fields_saves['rut'] = $rut;
				$fields_saves['nombre'] = $nombref;				
				$this->archivoreceptor_m->save_default($fields_saves,$id);
				//$id = $this->db->insert_id();
				
			}
		}
		//print_r($this->data);
		//$this->load->view ( 'backend/index', $this->data );
		redirect('admin/receptor/form/editar/'.$ids);
		//$this->output->enable_profiler ( TRUE );
    }  
      
    function cargar_excel( $accion = ''){
	  	//$this->output->enable_profiler ( TRUE );
	  if (!$this->session->userdata('usuario_id')){redirect('login');}
		$this->load->model ( 'abogados_m' );
		$this->data['plantilla'] = 'importar/';
		$view = 'cargar';
		$this->data['plantilla'].=$view;
		$this->data['archivos'] = array();
		$this->data['error'] = array();
		$this->data['archivo'] = '';
		$this->data['operacion'] = FALSE;
		/* cargar archivo*/
		
		 if ($accion == 'guardar_archivo'){
			$nombre_archivo = date('YmdHis');		
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = '*';
			$config['max_size']	= '5120';
			/*$config['max_width']  = '2048';
			$config['max_height']  = '1080';*/
			$this->load->library('upload', $config);
			
			if (! $this->upload->do_upload ("archivo_1")) {
				$this->data['error'] = array ('error' => $this->upload->display_errors () );
			} else {
				$this->data['archivos'] = array($this->upload->data());  
				rename ( $this->data['archivos'][0]['full_path'], './uploads/importar.xls' );
				if (is_file($this->data['archivos'][0]['full_path'])){
					//unlink ( $this->data['archivos'][0]['full_path'] );
				}
			}
		}
		///////////////////////////////////////
		if (is_file('./uploads/importar.xls')){ $this->data['archivo'] = './uploads/importar.xls'; }
		
	
		$abogados = array(0=>'Seleccionar');
		$a = $this->abogados_m->get_many_by(array('activo'=>'S'));
		foreach ($a as $obj) {
			$abogados[$obj->id] = $obj->nombres.' '.$obj->ape_pat;
		}

		$this->data['abogados'] = $abogados;

		


		if ($accion == 'importar_archivo'){
			$this->form_validation->set_rules('id_abogado', 'Abogado', 'trim|required|is_natural_no_zero');
			if ($this->form_validation->run() == TRUE){
				$array_return = array();
				//echo 'entra';

				$abogado = $this->input->post('id_abogado');
				if( $this->nodo->nombre == 'fullpay'){
					$this->importar_excel_nodo_fullpay($abogado);
					//die();


				}
				if( $this->nodo->nombre == 'swcobranza'){
					//echo 'entra swcobranza';
					$this->importar_excel_nodo_swcobranza();
				}
				
			    $this->data['usuarios_insert'] = $this->array_return['usuarios_insert'];
				$this->data['usuarios_update'] = $this->array_return['usuarios_update'];
				$this->data['cuentas_insert'] = $this->array_return['cuentas_insert'];
				//$this->data['cuentas_update'] = $this->array_return['cuentas_update'];
				$this->data['operacion'] = TRUE;
			} else {
				//echo 'falla mandante';
			}
		}
		$this->load->view ( 'backend/index', $this->data );
	}	
	
	function cargar_tanner( $accion = ''){
	  	//$this->output->enable_profiler ( TRUE );
	  if (!$this->session->userdata('usuario_id')){redirect('login');}
		$this->load->model ( 'mandantes_m' );
		$this->data['plantilla'] = 'importar/';
		$view = 'cargar_tanner';
		$this->data['plantilla'].=$view;
		$this->data['archivos'] = array();
		$this->data['error'] = array();	
		$this->data['archivo'] = '';
		$this->data['operacion'] = FALSE;
		/* cargar archivo*/
		
		 if ($accion == 'guardar_archivo'){
			$nombre_archivo = date('YmdHis');		
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = '*';
			$config['max_size']	= '5120';
			/*$config['max_width']  = '2048';
			$config['max_height']  = '1080';*/
			$this->load->library('upload', $config);
			
			if (! $this->upload->do_upload ("archivo_1")) {
				$this->data['error'] = array ('error' => $this->upload->display_errors () );
			} else {
				$this->data['archivos'] = array($this->upload->data());  
				rename ( $this->data['archivos'][0]['full_path'], './uploads/importar.xls' );
				if (is_file($this->data['archivos'][0]['full_path'])){
					//unlink ( $this->data['archivos'][0]['full_path'] );
				}
			}
		}
		///////////////////////////////////////
		if (is_file('./uploads/importar.xls')){ $this->data['archivo'] = './uploads/importar.xls'; }
		
		$a=$this->mandantes_m->get_many_by(array('activo'=>'S'));
		$this->data['mandantes'][0]='Seleccionar Mandante';
		foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}
		
		if ($accion == 'importar_archivo'){
			$this->form_validation->set_rules('id_mandante', 'Mandante', 'trim|required|is_natural_no_zero');
			if ($this->form_validation->run() == TRUE){
				$array_return = array();
				//echo 'entra';
				if( $this->nodo->nombre == 'fullpay'){
					$this->importar_excel_nodo_fullpay_tanner();
					
				}
				if( $this->nodo->nombre == 'swcobranza'){
					//echo 'entra swcobranza';
					$this->importar_excel_nodo_swcobranza();
				}
				
			    $this->data['usuarios_insert'] = $this->array_return['usuarios_insert'];
				$this->data['usuarios_update'] = $this->array_return['usuarios_update'];
				$this->data['cuentas_insert'] = $this->array_return['cuentas_insert'];
				$this->data['cuentas_update'] = $this->array_return['cuentas_update'];
				$this->data['operacion'] = TRUE;
				//die();
			} else {
				//echo 'falla mandante';
			}
		}
		$this->load->view ( 'backend/index', $this->data );
	}	
		
    function cargar_excel_etapa( $accion = ''){
	  	//$this->output->enable_profiler ( TRUE );
	  if (!$this->session->userdata('usuario_id')){redirect('login');}
		$this->load->model ( 'mandantes_m' );
		$this->data['plantilla'] = 'importar/';
		$view = 'cargar_etapa';
		$this->data['plantilla'].=$view;
		$this->data['archivos'] = array();
		$this->data['error'] = array();
		$this->data['archivo'] = '';
		$this->data['operacion'] = FALSE;
		/* cargar archivo*/

		if ($accion == 'guardar_archivo'){
			$nombre_archivo = date('YmdHis');		
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = '*';
			$config['max_size']	= '5120';
			/*$config['max_width']  = '2048';
			$config['max_height']  = '1080';*/
			$this->load->library('upload', $config);
			
			if (! $this->upload->do_upload ("archivo_1")) {
				$this->data['error'] = array ('error' => $this->upload->display_errors () );
			} else {
				$this->data['archivos'] = array($this->upload->data());  
				rename ( $this->data['archivos'][0]['full_path'], './uploads/importar_etapa.xls' );
				if (is_file($this->data['archivos'][0]['full_path'])){
					//unlink ( $this->data['archivos'][0]['full_path'] );
				}
			}
		}
		///////////////////////////////////////
		if (is_file('./uploads/importar_etapa.xls')){ $this->data['archivo'] = './uploads/importar_etapa.xls'; }
		
		$a=$this->mandantes_m->get_all();
		$this->data['mandantes'][0]='Seleccionar Mandante';
		foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->razon_social;}
		
		if ($accion == 'importar_archivo'){
			$this->form_validation->set_rules('id_mandante', 'Mandante', 'trim|required|is_natural_no_zero');
			if ($this->form_validation->run() == TRUE){
				$array_return = array();
				//echo 'entra';
				if( $this->nodo->nombre == 'fullpay'){
					$this->importar_excel_nodo_fullpay();
				}
				
				$this->data['usuarios_insert'] = $this->array_return['usuarios_insert'];
				$this->data['usuarios_update'] = $this->array_return['usuarios_update'];
				$this->data['cuentas_insert'] = $this->array_return['cuentas_insert'];
				$this->data['cuentas_update'] = $this->array_return['cuentas_update'];
				$this->data['operacion'] = TRUE;
			} else {
				//echo 'falla mandante';
			}
		}
		$this->load->view ( 'backend/index', $this->data );
	}
		
	function cargar_excel_falabella( $accion = ''){
	  	//$this->output->enable_profiler ( TRUE );
	  if (!$this->session->userdata('usuario_id')){redirect('login');}
		$this->load->model ( 'mandantes_m' );
		$this->data['plantilla'] = 'importar/';
		$view = 'cargar_falabella';
		$this->data['plantilla'].=$view;
		$this->data['archivos'] = array();
		$this->data['error'] = array();
		$this->data['archivo'] = '';
		$this->data['operacion'] = FALSE;
		/* cargar archivo*/

		if ($accion == 'guardar_archivo_falabella'){
			$nombre_archivo = date('YmdHis');		
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = '*';
			$config['max_size']	= '5120';
			/*$config['max_width']  = '2048';
			$config['max_height']  = '1080';*/
			$this->load->library('upload', $config);
			
			if (! $this->upload->do_upload ("archivo_1")) {
				$this->data['error'] = array ('error' => $this->upload->display_errors () );
			} else {
				$this->data['archivos'] = array($this->upload->data());  
				rename ( $this->data['archivos'][0]['full_path'], './uploads/falabella.xls' );
				if (is_file($this->data['archivos'][0]['full_path'])){
					//unlink ( $this->data['archivos'][0]['full_path'] );
				}
			}
		}
		///////////////////////////////////////
		if (is_file('./uploads/falabella.xls')){ $this->data['archivo'] = './uploads/falabella.xls'; }
		
		$a=$this->mandantes_m->get_many_by( array('activo'=>'S','codigo_mandante !='=>''));
		$this->data['mandantes'][0]='Seleccionar Mandante';
		foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}
		
		if ($accion == 'importar_archivo'){
			$this->form_validation->set_rules('id_mandante', 'Mandante', 'trim|required|is_natural_no_zero');
			if ($this->form_validation->run() == TRUE){
				$array_return = array();
				//echo 'entra';
				/*if( $this->nodo->nombre == 'fullpay'){
					$this->importar_excel_nodo_fullpay();
				}*/
				
				$this->data['usuarios_insert'] = $this->array_return['usuarios_insert'];
				$this->data['usuarios_update'] = $this->array_return['usuarios_update'];
				$this->data['cuentas_insert'] = $this->array_return['cuentas_insert'];
				$this->data['cuentas_update'] = $this->array_return['cuentas_update'];
				$this->data['operacion'] = TRUE;
			} else {
				//echo 'falla mandante';
			}
		}
		$this->load->view ( 'backend/index', $this->data );
	}
	
	
	
	  
	  
}