<?php
include_once "./application/core/EMP_Encrypt.php";
require_once 'application/libraries/PHPExcel.php';

class Importar extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->library ( 'session' );
		$this->load->library('PHPExcel');
		$this->load->library ( 'form_validation' );
		$this->load->helper ( 'date_html_helper' );
		$this->load->helper ( 'excel_reader2' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'receptor_m' );
	//	$this->load->model ( 'direcciones_m' );
		$this->load->model ( 'procurador_m');
		

		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		$this->data['current'] = 'cuentas';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'cuentas/';
		$this->data['lists'] = array();
		$this->data['nodo'] = $this->nodo = $this->nodo_m->get_by( array('activo'=>'S') );	
	
		$this->array_return['cuentas_insert'] = 0;
		$this->array_return['no_insert'] = 0;
		$this->array_return['usuarios_insert'] = 0;
		$this->array_return['usuarios_update'] = 0;
		$this->array_return['elimin'] = 0;
		$this->array_return['cuentas_update'] = 0;
		$array_return = array ();

	}

	// INICIO USUARIO 
	public function revisar_usuario($rut, $datos=array()){
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
		$encrypt = new encrypt();
		$passed = false;
		$idcuenta = '';
		
		//echo $datos['fecha_asignacion'].'<br>';
		//die();
		if (array_key_exists ( 'rol', $datos ) && $datos ['rol'] != '') {
			$datos_cuentas ['rol'] = $datos['rol'];
		}

		if (array_key_exists ( 'tribunal', $datos ) && $datos ['tribunal'] != '') {
			$datos_cuentas ['tribunal'] = $datos['tribunal'];
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

		$datos_cuentas ['activo'] = "S";

		$tribunal_pjud = trim($datos_cuentas['tribunal']);
		
		$cuenta = $this->cuentas_m->get_by(array("rol" => $datos['rol'], 'tribunal' => $datos['tribunal']));
		if(!$cuenta){
			//print_r($tribunal_pjud);
			//die;
			$res = $this->tribunales_pjud_m->get_by(array ('tribunal' => $tribunal_pjud));
			//print_r($data);
			//die;
			if( !empty($res)){
				$id_tribunal = $res->id_tribunal_pjud;
			}else{
				$id_tribunal = 0;
			}
		  
			$datos_cuentas = array_merge($datos_cuentas, array('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'id_tribunal' => $id_tribunal));

			$where = array();
			$rol = explode('-', $datos_cuentas['rol']);
			
			$id_abogado = $datos_cuentas['id_abogado'];
			
			$abogad = $this->abogados_m->get_by(array("id" => $id_abogado));
			if($abogad){
				$sist = strtolower(substr($abogad->sistema, 0, 3));
			
				if(!empty($res)){
					$this->cae = $this->load->database("cae", TRUE);
					$this->cat = $this->load->database("cat", TRUE);
					$this->sup = $this->load->database("superir", TRUE);

					if($rol[0] == 'C'){
						$where['cta.letraC'] = 1;
						$a = $this->cae->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
						if(!empty($a))
							$where['cta.anio'] = $a[0]->id;
						if($rol[1])
							$where['cta.rol'] = $rol[1];
						$where['cta.id_tribunal'] = $res->id_s_tribunales;
					}
					elseif($rol[0] == 'E'){
						$where['cta.letraE'] = 2;
						$a = $this->cae->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
						if(!empty($a))
							$where['cta.anioE'] = $a[0]->id;
						if($rol[1])
							$where['cta.rolE'] = $rol[1];
						$where['cta.id_tribunal_ex'] = $res->id_s_tribunales;
					}

					$sistema = '';
					//Consultar la base de datos del CAE
					$result1 = $this->cae->select('
												u.rut,
												MAX(2ce.fecha_etapa) as fecha_etapa,
												se.etapa,
												ma.codigo_mandante as mandante,
												es.estado,
												adm.nombres,
												adm.apellidos,
												2cd.id_comuna,
											')
										  ->from("0_cuentas cta")
										  ->join("0_usuarios u","u.id=cta.id_usuario", "left")
										  ->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id", "left")
										  ->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left")
										  ->join("s_etapas se","se.id=cta.id_etapa", "left")
										  ->join("0_mandantes ma","ma.id=cta.id_mandante", "left")
										  ->join("s_estado_cuenta es","es.id=cta.id_estado_cuenta", "left")
										  ->join("0_administradores adm","adm.id=cta.id_procurador", "left")
										  ->where($where)
										  ->get()
										  ->result();

					if($result1 && $result1[0]->rut != '')
						$sistema = 'cae';

					if($rol[0] == 'C'){
						$where['cta.letraC'] = 1;
						$a = $this->cat->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
						if(!empty($a))
							$where['cta.anio'] = $a[0]->id;
						if($rol[1])
							$where['cta.rol'] = $rol[1];
						$where['cta.id_tribunal'] = $res->id_s_tribunales;
					}
					elseif($rol[0] == 'E'){
						$where['cta.letraE'] = 2;
						$a = $this->cat->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
						if(!empty($a))
							$where['cta.anioE'] = $a[0]->id;
						if($rol[1])
							$where['cta.rolE'] = $rol[1];
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
												2cd.id_comuna,
												cta.id_marcas_especiales
											')
										  ->from("0_cuentas cta")
										  ->join("0_usuarios u","u.id=cta.id_usuario", "left")
										  ->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id", "left")
										  ->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left")
										  ->join("s_etapas se","se.id=cta.id_etapa", "left")
										  ->join("0_mandantes ma","ma.id=cta.id_mandante", "left")
										  ->join("s_estado_cuenta es","es.id=cta.id_estado_cuenta", "left")
										  ->join("0_administradores adm","adm.id=cta.id_procurador", "left")
										  ->where($where)
										  ->get()
										  ->result();

					if($result2 && $result2[0]->rut != '')
						$sistema = 'cat';

					if($rol[0] == 'C'){
						$where['cta.letraC'] = 1;
						$a = $this->sup->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
						if(!empty($a))
							$where['cta.anio'] = $a[0]->id;
						if($rol[1])
							$where['cta.rol'] = $rol[1];
						$where['cta.id_tribunal'] = $res->id_s_tribunales;
					}
					elseif($rol[0] == 'E'){
						$where['cta.letraE'] = 2;
						$a = $this->sup->select("*")->from('anio a')->where("anio", $rol[2])->get()->result();
						if(!empty($a))
							$where['cta.anioE'] = $a[0]->id;
						if($rol[1])
							$where['cta.rolE'] = $rol[1];
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
												2cd.id_comuna,
											')
										  ->from("0_cuentas cta")
										  ->join("0_usuarios u","u.id=cta.id_usuario", "left")
										  ->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id", "left")
										  ->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left")
										  ->join("s_etapas se","se.id=cta.id_etapa", "left")
										  ->join("0_mandantes ma","ma.id=cta.id_mandante", "left")
										  ->join("s_estado_cuenta es","es.id=cta.id_estado_cuenta", "left")
										  ->join("0_administradores adm","adm.id=cta.id_procurador", "left")
										  ->where($where)
										  ->get()
										  ->result();

					if($result3 && $result3[0]->rut != '')
						$sistema = 'sup';

					$result4 = array_merge($result1, $result2, $result3);
					$result = array();
					foreach ($result4 as $key => $value){ //print_r($value);
						if($value->rut != '')
							$result[] = $value;
					}
					if(empty($result))
						$result = $result1;

					$aux_rol = $datos_cuentas['rol'];
					$aux_trb = $datos_cuentas['id_tribunal'];
					$cuenta = $this->cuentas_m->get_by(array("rol" => $aux_rol, 'id_tribunal' => $aux_trb));
					$cuenta_na = $this->cuentas_na->get_by(array("rol" => $aux_rol, 'id_tribunal' => $aux_trb));
					
					$sist_aux = '';
					$abogad = $this->abogados_m->get_by(array("id" => $cuenta_na->id_abogado));
					if($abogad)
						$sist_aux = strtolower(substr($abogad->sistema, 0, 3));
					
					if($result[0]->rut == ''){ //ESTO ES UN N/A
						$datos_cuentas['id_comuna'] = $result[0]->id_comuna;
						if($result[0]->id_marcas_especiales)
							$datos_cuentas['id_marcas_especiales'] = $result[0]->id_marcas_especiales;
						if($id_abogado == 1){ //CINTHYA
							$idcuenta = $this->cuentas_aux->save_default($datos_cuentas);
							$this->array_return['no_insert']++;
						}
						elseif(!$cuenta_na){
							$datos_cuentas['sistema'] = $sist;
							$idcuenta = $this->cuentas_na->save_default($datos_cuentas);
							$this->array_return['cuentas_insert']++;
						}
						elseif($cuenta_na){
							// 2 -> cat, 3 -> cae, 4 -> superir
							if($sist == 'cat' && $sist_aux == 'sup'){ //CAROLINA e ISIS (Eliminamos a carolina) OK
								$datos_cuentas['sistema'] = $sist;
								$this->cuentas_na->delete($cuenta_na->id);
								$this->array_return['elimin']++;
							}
							elseif($sist == 'cat' && $sist_aux == 'cae'){ //ISIS y LESLIE (No insertamos a isis)
								$passed = true;
							}
							elseif($sist == 'cae' && $sist_aux == 'cat'){ //LESLIE e Isis (Quitamos a isis)
								$datos_cuentas['sistema'] = $sist;
								$this->cuentas_na->delete($cuenta_na->id);
								$this->array_return['elimin']++;
							}
							elseif($sist == 'cae' && $sist_aux == 'sup'){ //LESLIE y CAROLINA (Eliminamos a carolina) OK
								$datos_cuentas['sistema'] = $sist;
								$this->cuentas_na->delete($cuenta_na->id);
								$this->array_return['elimin']++;
							}
							elseif($sist == 'sup' && in_array($sist_aux, array('cat', 'cae'))){//CAROLINA (No insertamos a karolina) OK
								$passed = true;
							}
							if(!$passed){
								if($sistema)
									$datos_cuentas['sistema'] = $sistema;
								$idcuenta = $this->cuentas_na->save_default($datos_cuentas);
								$this->array_return['cuentas_insert']++;
							}
							else
								$this->array_return['no_insert']++;	
						}
					}
					else{ //CUENTAS NORMAL
						$abogad_aux = $this->abogados_m->get_by(array("id" => $id_abogado));
						if($abogad_aux){
							$idabog = $abogad_aux->id;
							$datos_abog = array();
							$datos_abog['cruce_gespron'] = intval($abogad_aux->cruce_gespron) + 1;
							$this->abogados_m->update($idabog, $datos_abog, false, true);
						}

						$datos_cuentas['rut'] = $result[0]->rut;
						$datos_cuentas['etapa'] = $result[0]->etapa;
						$datos_cuentas['fecha_etapa'] = $result[0]->fecha_etapa;
						$datos_cuentas['mandante'] = $result[0]->mandante;
						$datos_cuentas['estado'] = $result[0]->estado;
						$datos_cuentas['id_comuna'] = $result[0]->id_comuna;
						$nombres = $encrypt->desencriptar($result[0]->nombres);
						if($result[0]->apellidos){
							$apellidos = $encrypt->desencriptar($result[0]->apellidos);
							$datos_cuentas['nombres'] = strtoupper(trim($nombres).' '.trim($apellidos));
						}
						else
							$datos_cuentas['nombres'] = strtoupper(trim($nombres));
						if($result[0]->id_marcas_especiales)
							$datos_cuentas['id_marcas_especiales'] = $result[0]->id_marcas_especiales;
						
						// 2 -> cat, 3 -> cae, 4 -> superir
						if($id_abogado == 1){ //CINTHYA
							$passed = false;
							if($cuenta){
								if(in_array($sist_aux, array('cat', 'cae', 'sup'))){ //(No se inserta)
									$passed = true;
								}
							}
							if(!$passed){
								$datos_cuentas['sistema'] = $sistema;
								$idcuenta = $this->cuentas_m->save_default($datos_cuentas);
								$this->array_return['cuentas_insert']++;
							}
							else
								$this->array_return['no_insert']++;
						}
						else{
							$passed = false;
							if($cuenta){
								if($sist == 'cat' && $cuenta->id_abogado == 1){ //ISIS (Quitamos a Cinthya)
									$this->cuentas_m->delete($cuenta->id);
									$this->array_return['elimin']++;
								}
								elseif($sist == 'cat' && $sist_aux == 'cae'){ //ISIS y LESLIE (No insertamos a isis)
									$passed = true;
								}
								elseif($sist == 'cat' && $sist_aux == 'sup'){ //ISIS y CAROLINA (Eliminar a Carolina)
									$this->cuentas_m->delete($cuenta->id);
									$this->array_return['elimin']++;
								}
								elseif($sist == 'cae' && $cuenta->id_abogado == 1){ //LESLIE (Quitamos a cinthya)
									$this->cuentas_m->delete($cuenta->id);
									$this->array_return['elimin']++;
								}
								elseif($sist == 'cae' && $sist_aux == 'cat'){ //LESLIE e Isis (Quitamos a isis)
									$this->cuentas_m->delete($cuenta->id);
									$this->array_return['elimin']++;
								}
								elseif($sist == 'cae' && $sist_aux == 'sup'){ //LESLIE y Carolina (Eliminar a Carolina)
									$this->cuentas_m->delete($cuenta->id);
									$this->array_return['elimin']++;
								}
								elseif($sist == 'sup' && $cuenta->id_abogado == 1){ //Carolina (Quitamos a Cinthya)
									$this->cuentas_m->delete($cuenta->id);
								}
								elseif($sist == 'sup' && in_array($sist_aux, array('cat', 'cae'))){ //CAROLINA (No insertamos)
									$this->cuentas_m->delete($cuenta->id);
									$this->array_return['elimin']++;
								}
							}
							if(!$passed && $result[0]->rut != ''){
								$datos_cuentas['sistema'] = $sistema;
								$idcuenta = $this->cuentas_m->save_default($datos_cuentas);
								$this->array_return['cuentas_insert']++;
							}
							else
								$this->array_return['no_insert']++;	
						}
					}
				}
				else{ // NO TIENE TRIBUNAL
						$idcuenta = $this->cuentas_na->save_default($datos_cuentas);
						$this->array_return['cuentas_insert']++;
				}
			}
			//print_r($datos_cuentas);
			//die;
			return $idcuenta;
		}
	}
	// TERMINAR CUENTAS 
	
	// FIN HISTORIAL CUENTA 
	#################### importar direcciones##################

	public function importar_excel_dir(){
		ini_set('precision', '20');
		//echo "---->".$tipo; die;
		//$this->load->helper ( 'excel_reader2' );
		$array_return = array ();
	
		$this->data ['operacion'] = FALSE;
		
		$rows_insert = array();
		
		//Limpiar errores
		//$this->error_castigo_m->delete_all();

		// ojo ss
		$uploadpath = "./uploads/importar_direcciones.xlsx";
		
		$this->load->library('PHPExcel');
		$inputFileType = PHPExcel_IOFactory::identify($uploadpath);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		
		$excel = $objReader->load($uploadpath);

		$excel->setActiveSheetIndex(0);
		$sheet = $excel->getActiveSheet();
		$rowcount = $sheet->getHighestRow();
		$columncount = $sheet->getHighestColumn();


		for($i = 2; $i <= $rowcount; $i++) {
			$rut = trim($sheet->getCellByColumnAndRow('A'. $i)->getValue());
			$dv = trim ( $sheet->getCellByColumnAndRow('B'. $i)->getValue());
			$cuenta_rut = trim ( $sheet->getCellByColumnAndRow('C'. $i)->getValue());
			$datos = trim ( $sheet->getCellByColumnAndRow('D'. $i)->getValue());
		
			
			$direcciones = array(
				'Rut'  => $rut,
      			'Dv'   => $dv,
    			'Cuenta_rut'    => $cuenta_rut,
    			'Datos'  => $datos,
			);
			$this->procurador_m->save_default($fields_save, $direcciones->id);
							$this->array_return['cuentas_update'];
			//consultar cuentas por operacion
			
		}
	}

	public function importar_excel_dias_mora(){
		ini_set('precision', '20');
		$array_return = array ();
		$array_return['cuentas_insert'] = 0;
		$array_return['cuentas_update'] = 0;
	//	$array_return['mora_update'] = 0;
		$array_return['usuarios_insert'] = 0;
		$array_return['usuarios_update'] = 0;
	
		$this->data ['cuentas'] = FALSE;
		
		$rows_insert = array ();
		
		// ojo ss
		$uploadpath = "./uploads/importar_direcciones.xls";
		$this->load->library('PHPExcel');
        $inputFileType = PHPExcel_IOFactory::identify($uploadpath);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$excel = $objReader->load($uploadpath);
		$excel = $excel->getSheet(1);  //Numero de la hoja activa a importar
		$rowcount = $excel->getHighestRow();
		//$highestcolumn = $excel->getHighestColumn();
		
		$id_usuario = $this->session->userdata('usuario_id');
		
		if($this->$dato=array())
		
		for($i = 2; $i <= $rowcount; $i ++){
			
			$rut = trim($sheet->getCell('A'. $i)->getValue());
			$dv = trim ( $sheet->getCell('B'. $i)->getValue());
			$cuenta_rut = trim ( $sheet->getCell('C'. $i)->getValue());
			$datos = trim ( $sheet->getCell('D'. $i)->getValue());
		
			
			$dato = array();
		
			$this->procurador_m->save_default($fields_save, $id);
							$this->array_return['cuentas_update']++;
		
		} // FIN FOR 
	}
	function cargar_excel_mora( $accion = ''){
		//$this->output->enable_profiler ( TRUE );
	if (!$this->session->userdata('usuario_id')){redirect('login');}
	  $this->load->model ('procurador_m');
	  $this->data['plantilla'] = 'importar/';
	  $view = 'cargar_mora';
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
			  rename ( $this->data['archivos'][0]['full_path'], './uploads/importar_direcciones.xls' );
			  if (is_file($this->data['archivos'][0]['full_path'])){
				  //unlink ( $this->data['archivos'][0]['full_path'] );
			  }
		  }
	  }
	  ///////////////////////////////////////
	  if (is_file('./uploads/importar_direcciones.xls')){ $this->data['archivo'] = './uploads/importar_direcciones.xls'; }
	  
	  $a=$this->procurador_m->get_all();
	 //$this->data['procurador'][0]='Seleccionar Mandante';
	  foreach ($a as $obj) {$this->data['cuentas'][$obj->rut] = $obj->dv;}
	  
	  if ($accion == 'importar_archivo'){
		  $array_return = array();
		  if( $this->nodo->id == 'fullpay'){
			  $this->importar_excel_dias_mora();
		  }
		  
		  $this->data['cuentas_insert'] = $this->array_return['cuentas_insert'];
		  $this->data['cuentas_update'] = $this->array_return['cuentas_update'];
		  $this->data['rut'] = FALSE;
	  }
	  $this->load->view ( 'backend/index', $this->data );
  }


####### fin dir#######

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
		return $idetapa;
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
	  
	public function revisar_procurador($datos){
	
	    $idprocurador= '';
		 
	    $procurador = $this->administradores_m->get_by(array( 'id' => $datos['id'] ));
	
	    if (count($procurador) == '1' ){
	     $idprocurador=$procurador->id;		
	    }
		
		return $idprocurador;
	}
	###########################importar direcciones################################
	###############################################################################

	function form1($action='',$id=''){if (!$this->session->userdata('usuario_id')){redirect('login');}
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
			$this->data['error'] = array ('error' => $this->upload->display_errors() );
		} else {
			$this->data['archivos'] = array($this->upload->data());  
			rename ( $this->data['archivos'][0]['full_path'], './uploads/importar.xls' );
			if (is_file($this->data['archivos'][0]['full_path'])){
				//unlink ( $this->data['archivos'][0]['full_path'] );
			}
		}
		$this->data['id_abogado'] = $this->input->post('pjud');
		$this->data['bandera'] = $this->input->post('pjud');
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
			$abogado = $this->input->post('id_abogado');
			if( $this->nodo->nombre == 'fullpay'){
				$this->importar_excel_nodo_fullpay($abogado);
				//die();
			}
			if($this->input->post('bandera')){
				redirect('admin/cuentas');
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

	
	public function importar_excel_direcciones(){
		//ini_set('precision', '20');
		//echo "---->".$tipo; die;
		//$this->load->helper ( 'excel_reader2' );
		$array_return = array ();
	
		$this->data ['rut'] = FALSE;
		
		$rows_insert = array ();
		$rows_insert = array ();
		
		//Limpiar telefonos anteriores
		//$today = date("Y-m-d");
		//$this->db->select('dir.id');
		//$this->db->from('0_cuentas dir');
		//$this->db->where("fecha_borrado is null or fecha_borrado < '{$today}'");
		//$query = $this->db->get();
		//$bloqueados = $query->result();
		//foreach($bloqueados as $k => $v){
		//	$this->telefonos_bloqueados_m->delete_by(array('id' => $v->id));
		//}

		// ojo ss
		$uploadpath = "./uploads/importar_direcciones.xlsx";
		
		$this->load->library('PHPExcel');
		$inputFileType = PHPExcel_IOFactory::identify($uploadpath);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		
		$excel = $objReader->load($uploadpath);

		$excel->setActiveSheetIndex(0);
		$sheet = $excel->getActiveSheet();
		$rowcount = $sheet->getHighestRow();

		for($i = 1; $i <= $rowcount; $i++) {
			//$operacion = trim($sheet->getCell('A'. $i)->getValue());
			$rut = trim ($sheet->getCell('A'. $i)->getValue());
			$dv = trim($sheet->getCell('B'. $i)->getValue());
			$cuenta_rut = trim($sheet->getCell('C'. $i)->getValue());
			$datos = trim($sheet->getCell('D'. $i)->getValue());
			
			//consultar direcciones por rut
			if($rut){
				//Insertar dirrrr //t
				$procurador = $this->procurador_m->get_by(array("rut" => $rut));
				if($procurador){
					$procurador = $this->procurador_m->get_by(array("id"));
					if($procurador)
						$this->array_return['cuentas_update']++;
					else{
						$fields_save = array();
						$fields_save['rut'] = $rut;
						$fields_save['dv'] = $dv;
						$fields_save['cuenta_rut'] = $cuenta_rut;
						$fields_save['datos'] = $datos;
						$this->procurador_m->insert($fields_save, TRUE, TRUE);
						$this->array_return['cuentas_insert']++;
					}
				}
			}
			elseif($rut){
				$this->db->select('cta.*');
				$this->db->from('0_cuentas cta');
			  	//$this->db->join("0_usuarios usr","cta.id_usuario=usr.id");
				//$this->db->where("rut like '{$rut}%' and cta.activo = 'S'");
				$this->db->group_by('cta.id');
				$query = $this->db->get();
				$cuentas = $query->result();
				if($cuentas){
					foreach($cuentas as $k => $v){
						$bloqueado = $this->procurador_m->get_by(array("id" => $v->id, 'rut' => $rut));
						if($bloqueado)
							$this->array_return['cuentas_update']++;
						else{
							if($rut){
								$fields_save = array();
								$fields_save['rut'] = $rut;
								$fields_save['dv'] = $dv;
								$fields_save['cuenta_rut'] = $cuenta_rut;
								$fields_save['datos'] = $datos;
								$this->procurador_m->insert($fields_save, TRUE, TRUE);
								$this->array_return['cuentas_insert']++;
							}
							if($dv){
								$fields_save = array();
								$fields_save['rut'] = $rut;
								$fields_save['dv'] = $dv;
								$fields_save['cuenta_rut'] = $cuenta_rut;
								$fields_save['datos'] = $datos;
								$this->procurador_m->insert($fields_save, TRUE, TRUE);
								$this->array_return['cuentas_insert']++;
							}
						}
					}
				}
			}
		}
	}
	
    ###################### IMPORTAR EXCEL ESTADO DIARIO ###########################
	###############################################################################
	public function importar_excel_nodo_fullpay($abogado=''){
		//$this->output->enable_profiler ( TRUE );
		$uploadpath = "./uploads/importar.xls";
		$this->load->library('PHPExcel');
		$excel = new PHPExcel();
		$inputFileType = PHPExcel_IOFactory::identify($uploadpath);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		
		$registros = 0;
		$this->array_return['cuentas_insert'] = 0;
		$this->array_return['no_insert'] = 0;
		$this->array_return['usuarios_insert'] = 0;
		$this->array_return['usuarios_update'] = 0;
		$this->array_return['elimin'] = 0;
		
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

		if($abogado != 1){ //Distinto de Cinthya
			//CORTE SUPREMA
			$excel->setActiveSheetIndex(0);
			$sheet = $excel->getActiveSheet();
			$rowcount = $sheet->getHighestRow();
			
			$count = 0; $cad = '';
			for($i = 2; $i <= $rowcount; $i ++){
				$nro_ingreso = trim ( $sheet->getCell ('A'. $i) ->getValue());
				$tipo_recurso = trim ( $sheet->getCell ('B'. $i) ->getValue());
				$fecha_ingreso = trim ( $sheet->getCell ('C'. $i) ->getValue());
				if($fecha_ingreso != ''){
					$valores = explode('/', $fecha_ingreso);
					
					if(count($valores) == 3){
						if(intval($valores[1]) < 10){
							$_mes = intval($valores[1]);
							$_mes = '0'.$_mes;
						}
						else $_mes = $valores[1];
						if(checkdate($_mes, $valores[1], $valores[2])){
							$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
							$fecha_ingreso = date('Y-m-d', strtotime($fecha));
						}
					}
					elseif(is_numeric($valores[0])){
						$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("B".$i)->getValue());
						$fecha_ingreso = $fecha->format('Y-m-d');
					}
					else{
						$valores = explode('-', $fecha_ingreso);
						if(count($valores) == 3){
							if(intval($valores[1]) < 10){
								$_mes = intval($valores[1]);
								$_mes = '0'.$_mes;
							}
							else $_mes = $valores[1];
							if(checkdate($_mes, $valores[1], $valores[2])){
								$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
								$fecha_ingreso = date('Y-m-d', strtotime($fecha));
							}
						}
						elseif(is_numeric($valores[0])){
							$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("B".$i)->getValue());
							$fecha_ingreso = $fecha->format('Y-m-d');
						}
					}
				}
				$caratulado = trim ( $sheet->getCell ('D'. $i) ->getValue());

				if($nro_ingreso != ''){
					$datos_cuentas = array();
					$datos_cuentas['rol'] = $nro_ingreso;
					$datos_cuentas['tipo_recurso'] = $tipo_recurso;
					$datos_cuentas['fecha_ingreso'] = $fecha_ingreso;
					$datos_cuentas['caratulado'] = $caratulado;
					$datos_cuentas['sistema'] = 'supr';
		    		$this->cuentas_m->save_default($datos_cuentas);
					$this->array_return['cuentas_insert']++;
					$registros++;
	    		}
			}
			
			//APELACIONES
			$excel->setActiveSheetIndex(1);
			$sheet = $excel->getActiveSheet();
			$rowcount = $sheet->getHighestRow();

			$count = 0; $cad = '';
			for($i = 2; $i <= $rowcount; $i ++){
				$rol = trim ( $sheet->getCell ('A'. $i) ->getValue());
				$fecha_ingreso = trim ( $sheet->getCell ('B'. $i) ->getValue());
				if($fecha_ingreso != ''){
					$valores = explode('/', $fecha_ingreso);
					
					if(count($valores) == 3){
						if(intval($valores[1]) < 10){
							$_mes = intval($valores[1]);
							$_mes = '0'.$_mes;
						}
						else $_mes = $valores[1];
						if(checkdate($_mes, $valores[1], $valores[2])){
							$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
							$fecha_ingreso = date('Y-m-d', strtotime($fecha));
						}
					}
					elseif(is_numeric($valores[0])){
						$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("B".$i)->getValue());
						$fecha_ingreso = $fecha->format('Y-m-d');
					}
					else{
						$valores = explode('-', $fecha_ingreso);
						if(count($valores) == 3){
							if(intval($valores[1]) < 10){
								$_mes = intval($valores[1]);
								$_mes = '0'.$_mes;
							}
							else $_mes = $valores[1];
							if(checkdate($_mes, $valores[1], $valores[2])){
								$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
								$fecha_ingreso = date('Y-m-d', strtotime($fecha));
							}
						}
						elseif(is_numeric($valores[0])){
							$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("B".$i)->getValue());
							$fecha_ingreso = $fecha->format('Y-m-d');
						}
					}
				}
				$ubicacion = trim ( $sheet->getCell ('C'. $i) ->getValue());
				$fecha_ubica = trim ( $sheet->getCell ('D'. $i) ->getValue());
				if($fecha_ubica != ''){
					$valores = explode('/', $fecha_ubica);
					if(count($valores) == 3){
						if(intval($valores[1]) < 10){
							$_mes = intval($valores[1]);
							$_mes = '0'.$_mes;
						}
						else $_mes = $valores[1];
						if(checkdate($_mes, $valores[1], $valores[2])){
							$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
							$fecha_ubica = date('Y-m-d', strtotime($fecha));
						}
					}
					elseif(is_numeric($valores[0])){
						$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("B".$i)->getValue());
						$fecha_ubica = $fecha->format('Y-m-d');
					}
					else{
						$valores = explode('-', $fecha_ubica);
						if(count($valores) == 3){
							if(intval($valores[1]) < 10){
								$_mes = intval($valores[1]);
								$_mes = '0'.$_mes;
							}
							else $_mes = $valores[1];
							if(checkdate($_mes, $valores[1], $valores[2])){
								$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
								$fecha_ubica = date('Y-m-d', strtotime($fecha));
							}
						}
						elseif(is_numeric($valores[0])){
							$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("B".$i)->getValue());
							$fecha_ubica = $fecha->format('Y-m-d');
						}
					}
				}
				$corte = trim ( $sheet->getCell ('E'. $i) ->getValue());
				$caratulado = trim ( $sheet->getCell ('F'. $i) ->getValue());

				if($rol != ''){
					$datos_cuentas = array();
					$datos_cuentas['rol'] = $rol;
					$datos_cuentas['fecha_ingreso'] = $fecha_ingreso;
					$datos_cuentas['ubicacion'] = $ubicacion;
					$datos_cuentas['fecha_ubica'] = $fecha_ubica;
					$datos_cuentas['tribunal'] = $corte;
					$datos_cuentas['caratulado'] = $caratulado;
					$datos_cuentas['sistema'] = 'apel';
		    		$this->cuentas_m->save_default($datos_cuentas);
					$this->array_return['cuentas_insert']++;
					$registros++;
	    		}
			}
		}

		//CIVIL
		$excel->setActiveSheetIndex(2);
		$sheet = $excel->getActiveSheet();
		$rowcount = $sheet->getHighestRow();

		$count = 0; $cad = '';
		for($i = 2; $i <= $rowcount; $i ++){
			//echo $i.'==';	
			$rol = '';
			$tribunal = '';
			$fecha_ingreso = '';
			$caratulado = '';
		
			$rol = trim ( $sheet->getCell ('A'. $i) ->getValue());
			$fecha_ingreso = trim ( $sheet->getCell ('B'. $i) ->getValue());
			$caratulado = trim ( $sheet->getCell ('C'. $i) ->getValue());
			if($fecha_ingreso != ''){
				$valores = explode('/', $fecha_ingreso);
					
				if(count($valores) == 3){
					if(intval($valores[1]) < 10){
						$_mes = intval($valores[1]);
						$_mes = '0'.$_mes;
					}
					else $_mes = $valores[1];
					if(checkdate($_mes, $valores[1], $valores[2])){
						$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
						$fecha_ingreso = date('Y-m-d', strtotime($fecha));
					}
				}
				elseif(is_numeric($valores[0])){
					$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("B".$i)->getValue());
					$fecha_ingreso = $fecha->format('Y-m-d');
				}
				else{
					$valores = explode('-', $fecha_ingreso);
					if(count($valores) == 3){
						if(intval($valores[1]) < 10){
							$_mes = intval($valores[1]);
							$_mes = '0'.$_mes;
						}
						else $_mes = $valores[1];
						if(checkdate($_mes, $valores[1], $valores[2])){
							$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
							$fecha_ingreso = date('Y-m-d', strtotime($fecha));
						}
					}
					elseif(is_numeric($valores[0])){
						$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("B".$i)->getValue());
						$fecha_ingreso = $fecha->format('Y-m-d');
					}
				}
			}
			$tribunal = trim ( $sheet->getCell ('D'. $i )->getValue());
		
			$datos_cuentas ['rol'] = $rol;
			$datos_cuentas ['tribunal'] = $tribunal;
			$datos_cuentas ['fecha_ingreso'] = $fecha_ingreso;
			$datos_cuentas ['caratulado'] = $caratulado;
			$datos_cuentas['id_abogado'] = $abogado;

			if($caratulado != '/SOUTH CONE S.A.G.R.'){
				if($rol != '' && $tribunal != ''){
					$idcuenta = $this->revisar_cuentas($datos_cuentas, $rol);
					$registros++;
				}
				else
					break;
			}
			//$cad .= $idcuenta.',';
		} // FIN FOR
		//die($cad);

		//LABORAL
		$excel->setActiveSheetIndex(3);
		$sheet = $excel->getActiveSheet();
		$rowcount = $sheet->getHighestRow();

		$cad = '';
		for($i = 2; $i <= $rowcount; $i ++){
			$rit = trim ( $sheet->getCell ('A'. $i) ->getValue());
			$ruc = trim ( $sheet->getCell ('B'. $i) ->getValue());
			$fecha_ingreso = trim ( $sheet->getCell ('C'. $i) ->getValue());
			$caratulado = trim ( $sheet->getCell ('D'. $i) ->getValue());
			$tribunal = trim ( $sheet->getCell ('E'. $i) ->getValue());
			
			if($fecha_ingreso != ''){
				$valores = explode('/', $fecha_ingreso);
					
				if(count($valores) == 3){
					if(intval($valores[1]) < 10){
						$_mes = intval($valores[1]);
						$_mes = '0'.$_mes;
					}
					else $_mes = $valores[1];
					if(checkdate($_mes, $valores[1], $valores[2])){
						$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
						$fecha_ingreso = date('Y-m-d', strtotime($fecha));
					}
				}
				elseif(is_numeric($valores[0])){
					$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("C".$i)->getValue());
					$fecha_ingreso = $fecha->format('Y-m-d');
				}
				else{
					$valores = explode('-', $fecha_ingreso);
					if(count($valores) == 3){
						if(intval($valores[1]) < 10){
							$_mes = intval($valores[1]);
							$_mes = '0'.$_mes;
						}
						else $_mes = $valores[1];
						if(checkdate($_mes, $valores[1], $valores[2])){
							$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
							$fecha_ingreso = date('Y-m-d', strtotime($fecha));
						}
					}
					elseif(is_numeric($valores[0])){
						$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("C".$i)->getValue());
						$fecha_ingreso = $fecha->format('Y-m-d');
					}
				}
			}
			
			if($rit != ''){
				$datos_cuentas = array();
				$datos_cuentas['rit'] = $rit;
				$datos_cuentas['ruc'] = $ruc;
				$datos_cuentas['fecha_ingreso'] = $fecha_ingreso;
				$datos_cuentas['tribunal'] = $tribunal;
				$datos_cuentas['caratulado'] = $caratulado;
				$datos_cuentas['sistema'] = 'laboral';
				$datos_cuentas['id_abogado'] = $abogado;
				$res = $this->revisar_laboral($datos_cuentas);
				$registros++;
			}
		}
		
		//PENAL
		$excel->setActiveSheetIndex(4);
		$sheet = $excel->getActiveSheet();
		$rowcount = $sheet->getHighestRow();

		$count = 0; $cad = '';
		for($i = 2; $i <= $rowcount; $i ++){
			$rol_interno = trim ( $sheet->getCell ('A'. $i) ->getValue());
			$rol_unico = trim ( $sheet->getCell ('B'. $i) ->getValue());
			$tipo_causa = trim ( $sheet->getCell ('C'. $i) ->getValue());
			$tribunal = trim ( $sheet->getCell ('D'. $i) ->getValue());
			$caratulado = trim ( $sheet->getCell ('E'. $i) ->getValue());
			$estado = trim ( $sheet->getCell ('F'. $i) ->getValue());
		
			if($rol_interno != ''){
				$datos_cuentas = array();
				$datos_cuentas['rol_interno'] = $rol_interno;
				$datos_cuentas['rol_unico'] = $rol_unico;
				$datos_cuentas['tipo_causa'] = $tipo_causa;
				$datos_cuentas['tribunal'] = $tribunal;
				$datos_cuentas['caratulado'] = $caratulado;
				$datos_cuentas['estado'] = $estado;
				$datos_cuentas['sistema'] = 'penal';
				$datos_cuentas['id_abogado'] = $abogado;
				$res = $this->revisar_penal($datos_cuentas);
				$registros++;
			}
		}
		
		//COBRANZA
		/*
		$excel->setActiveSheetIndex(5);
		$sheet = $excel->getActiveSheet();
		$rowcount = $sheet->getHighestRow();

		$count = 0; $cad = '';
		for($i = 2; $i <= $rowcount; $i ++){
			$rol_interno = trim ( $sheet->getCell ('A'. $i) ->getValue());
			$rol_unico = trim ( $sheet->getCell ('B'. $i) ->getValue());
			$fecha_ingreso = trim ( $sheet->getCell ('C'. $i) ->getValue());
			$caratulado = trim ( $sheet->getCell ('D'. $i) ->getValue());
			$tribunal = trim ( $sheet->getCell ('E'. $i) ->getValue());
			
			if($fecha_ingreso != ''){
				$valores = explode('/', $fecha_ingreso);
					
				if(count($valores) == 3){
					if(intval($valores[1]) < 10){
						$_mes = intval($valores[1]);
						$_mes = '0'.$_mes;
					}
					else $_mes = $valores[1];
					if(checkdate($_mes, $valores[1], $valores[2])){
						$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
						$fecha_ingreso = date('Y-m-d', strtotime($fecha));
					}
				}
				elseif(is_numeric($valores[0])){
					$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("C".$i)->getValue());
					$fecha_ingreso = $fecha->format('Y-m-d');
				}
				else{
					$valores = explode('-', $fecha_ingreso);
					if(count($valores) == 3){
						if(intval($valores[1]) < 10){
							$_mes = intval($valores[1]);
							$_mes = '0'.$_mes;
						}
						else $_mes = $valores[1];
						if(checkdate($_mes, $valores[1], $valores[2])){
							$fecha = $valores[2].'-'.$_mes.'-'.$valores[0];
							$fecha_ingreso = date('Y-m-d', strtotime($fecha));
						}
					}
					elseif(is_numeric($valores[0])){
						$fecha = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell("C".$i)->getValue());
						$fecha_ingreso = $fecha->format('Y-m-d');
					}
				}
			}
			
			if($rol_interno != ''){
				$datos_cuentas = array();
				$datos_cuentas['rol_interno'] = $rol_interno;
				$datos_cuentas['rol_unico'] = $rol_unico;
				$datos_cuentas['fecha_ingreso'] = $fecha_ingreso;
				$datos_cuentas['tribunal'] = $tribunal;
				$datos_cuentas['caratulado'] = $caratulado;
				$datos_cuentas['sistema'] = 'cobranza';
				$datos_cuentas['id_abogado'] = $abogado;
				$this->cuentas_m->save_default($datos_cuentas);
				$this->array_return['cuentas_insert']++;
				$registros++;
			}
		}*/
		
		$today = date('Y-m-d');
		$abogados = $this->abogados_m->get_all();
		foreach($abogados as $k => $v){
			$ctas_m = $this->cuentas_m->get_many_by(array('id_abogado'=>$v->id));
			$ctas_na = $this->cuentas_na->get_many_by(array('id_abogado'=>$v->id));
			$datos_abog = array();
			if($v->id == $abogado){
				$datos_abog['total_registros_archivo'] = $registros;
				$datos_abog['total_registros'] = $this->array_return['cuentas_insert'];
				$datos_abog['total_import'] = count($ctas_m) + count($ctas_na);
				$datos_abog['total_na'] = count($ctas_na);
				//$datos_abog['total_elim'] = intval($this->array_return['elimin']);
			}
			if($v->total_import){
				$datos_abog['total_elim'] = $v->total_registros - count($ctas_m) - count($ctas_na);
				$datos_abog['total_import'] = count($ctas_m) + count($ctas_na);
				$datos_abog['total_na'] = count($ctas_na);
			}
			/*
			if($i == 3){
				echo '-->'.$v->total_registros.'--->'.count($ctas_m).'--->'.count($ctas_na);
			print_r($datos_abog); die;}*/
			if(!empty($datos_abog)){
				$datos_abog['fecha_gestion'] = $today;
				$this->abogados_m->update($v->id, $datos_abog, false, true);
			}
		}
	}
	
	public function revisar_penal($datos_cuentas){
		//DE MOMENTO SOLO ES CON SUPERIR
		$this->sup = $this->load->database("superir", TRUE);
		$where = array();
		$id_tribunal = 0;
		if($datos_cuentas['tribunal']){
			$res = $this->sup->select('tc.id, tc.tribunal')
						->from("tribunales_complementarios tc")
						->where(array ('tribunal' => strtoupper($datos_cuentas['tribunal']), 'id_tipo_causa' => 3)) //PENAL
						->get()
						->result();					
										
			//echo $this->sup->last_query();
			//print_r($res);
			//die;
			if( !empty($res)){
				$id_tribunal = $res[0]->id;
				$where['jc.id_tribunal'] = $res[0]->id;
			}
		}
		
		if($datos_cuentas['rit'])
			$where["CONCAT(tl.tipo,'-',jc.rol,'-',a.anio)"] = $datos_cuentas['rol_interno'];
		
		$result = $this->sup->select('
										jc.id,
										u.rut,
										2ce.fecha_etapa,
										2ce.observaciones as etapa,
										es.estado,
										adm.nombres,
										adm.apellidos,
										2cd.id_comuna,
									')
					->from("juicios_complementarios jc")
					->join("0_cuentas cta","jc.id_cuenta=cta.id", "left")
					->join("0_usuarios u","u.id=cta.id_usuario", "left")
					->join("juicio_complementario_etapas 2ce","2ce.id_cuenta=jc.id", "left")
					->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left")
					->join("estado_juicio es","es.id=jc.id_estado_cuenta", "left")
					->join("0_administradores adm","adm.id=jc.id_procurador", "left")
					->join("tipo_letra tl","jc.id_letra=tl.id","left")
					->join("anio a","jc.id_anio=a.id","left")
					->where("(
						(2ce.id_cuenta, 2ce.id) IN 
						(SELECT id_cuenta, MAX(id)
						FROM juicio_complementario_etapas 2ce
						WHERE activo = 'S' GROUP BY id_cuenta))")
					->where($where)
					->get()
					->result();
					
		if($result){
			$datos_cuentas['rut'] = $result[0]->rut;
			$datos_cuentas['id_tribunal'] = $id_tribunal;
			$datos_cuentas['estado'] = $result[0]->estado;
			$datos_cuentas['fecha_etapa'] = $result[0]->fecha_etapa;
			$datos_cuentas['etapa'] = $result[0]->etapa;
			if($result[0]->apellidos)
				$datos_cuentas['nombres'] = trim($result[0]->nombres).' '.trim($result[0]->apellidos);
			else
				$datos_cuentas['nombres'] = trim($result[0]->nombres);
		}
		
		$this->cuentas_m->save_default($datos_cuentas);
		$this->array_return['cuentas_insert']++;
	}
	
	public function revisar_laboral($datos_cuentas){
		//DE MOMENTO SOLO ES CON SUPERIR
		$this->sup = $this->load->database("superir", TRUE);
		$where = array();
		$id_tribunal = 0;
		if($datos_cuentas['tribunal']){
			$res = $this->sup->select('tc.id, tc.tribunal')
						->from("tribunales_complementarios tc")
						->where(array ('tribunal' => strtoupper($datos_cuentas['tribunal']), 'id_tipo_causa' => 2)) //LABORAL
						->get()
						->result();					
										
			//echo $this->sup->last_query();
			//print_r($res);
			//die;
			if( !empty($res)){
				$id_tribunal = $res[0]->id;
				$where['jc.id_tribunal'] = $res[0]->id;
			}
		}
		
		if($datos_cuentas['rit'])
			$where["CONCAT(tl.tipo,'-',jc.rol,'-',a.anio)"] = $datos_cuentas['rit'];
		
		$result = $this->sup->select('
										jc.id,
										u.rut,
										2ce.fecha_etapa,
										2ce.observaciones as etapa,
										es.estado,
										adm.nombres,
										adm.apellidos,
										2cd.id_comuna,
									')
					->from("juicios_complementarios jc")
					->join("0_cuentas cta","jc.id_cuenta=cta.id", "left")
					->join("0_usuarios u","u.id=cta.id_usuario", "left")
					->join("juicio_complementario_etapas 2ce","2ce.id_cuenta=jc.id", "left")
					->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left")
					->join("estado_juicio es","es.id=jc.id_estado_cuenta", "left")
					->join("0_administradores adm","adm.id=jc.id_procurador", "left")
					->join("tipo_letra tl","jc.id_letra=tl.id","left")
					->join("anio a","jc.id_anio=a.id","left")
					->where("(
						(2ce.id_cuenta, 2ce.id) IN 
						(SELECT id_cuenta, MAX(id)
						FROM juicio_complementario_etapas 2ce
						WHERE activo = 'S' GROUP BY id_cuenta))")
					->where($where)
					->get()
					->result();
		if(!$result){
			$result = $this->sup->select('
										jc.id,
										u.rut,
										2ce.fecha_etapa,
										2ce.observaciones as etapa,
										es.estado,
										adm.nombres,
										adm.apellidos,
										2cd.id_comuna,
									')
					->from("juicios_complementarios jc")
					->join("0_cuentas cta","jc.id_cuenta=cta.id", "left")
					->join("0_usuarios u","u.id=cta.id_usuario", "left")
					->join("juicio_complementario_etapas 2ce","2ce.id_cuenta=jc.id", "left")
					->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left")
					->join("estado_juicio es","es.id=jc.id_estado_cuenta", "left")
					->join("0_administradores adm","adm.id=jc.id_procurador", "left")
					->join("tipo_letra tl","jc.id_letra=tl.id","left")
					->join("anio a","jc.id_anio=a.id","left")
					->where($where)
					->get()
					->result();
		}
					
		if($result){
			$datos_cuentas['rut'] = $result[0]->rut;
			$datos_cuentas['id_tribunal'] = $id_tribunal;
			$datos_cuentas['estado'] = $result[0]->estado;
			$datos_cuentas['fecha_etapa'] = $result[0]->fecha_etapa;
			$datos_cuentas['etapa'] = $result[0]->etapa;
			$datos_cuentas['nombres'] = trim($result[0]->nombres).' '.trim($result[0]->apellidos);
		}
		
		if($result[0]->rut == '')
			$this->cuentas_na->save_default($datos_cuentas);
		else
			$this->cuentas_m->save_default($datos_cuentas);
		$this->array_return['cuentas_insert']++;
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
				$this->data['error'] = array ('error' => $this->upload->display_errors() );
			} else {
				$this->data['archivos'] = array($this->upload->data());  
				rename ( $this->data['archivos'][0]['full_path'], './uploads/importar.xls' );
				if (is_file($this->data['archivos'][0]['full_path'])){
					//unlink ( $this->data['archivos'][0]['full_path'] );
				}
			}
			$this->data['id_abogado'] = $this->input->post('pjud');
			$this->data['bandera'] = $this->input->post('pjud');
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
				$abogado = $this->input->post('id_abogado');
				if( $this->nodo->nombre == 'fullpay'){
					$this->importar_excel_nodo_fullpay($abogado);
					//die();
				}
				if($this->input->post('bandera')){
					redirect('admin/cuentas');
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
}