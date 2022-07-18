<?php
class Api extends CI_Controller {
		
	public function Api() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'session' );
		//$this->output->enable_profiler(TRUE);
		//$this->token = 'PyeYtTrpx2FGdjyPALrPadJf'; //dev
		$this->token = 'g1E6rndWyCI0SyDXi37TGn5D';
		$this->username = 'fullpay';
		$this->password = sha1('CwKywE');
		$this->auth_session = 'waeeVw6yj9gpHDsMLzDNp27B';
		$this->version = '1.12';
		$this->load->model( 'administradores_m' );
		$this->load->model ( 'cuentas_m' );
	}
	public function index(){
		echo 'Versión '.$this->version.'<br>';
		echo 'Métodos:<br>';
		echo 'POST auth(token,username,password)<br>';
		echo 'GET  getAuthSession(user_token)<br>';
		echo 'GET  getCuentaEtapasProcurador(auth_session)<br>';
		echo 'GET  getCuentaEtapa(auth_session,idce)<br>';
		echo 'GET  getCuentaEtapas(auth_session,id_cuenta)<br>';
		echo 'GET  getEtapas(auth_session)<br>';
		echo 'GET  getTribunales(auth_session)<br>';
		echo 'POST insertCausa(id_cuenta,id_etapa,fecha_etapa,observaciones,api_status,api_id)<br>';
		echo 'ERRORES<BR>';
		echo '10: token de api incorrecto<br>';
		echo '20: parametros de usuario y password no válidos<br>';
		echo '21: usuario o password incorrectos<br>';
		echo '22: token de usuario incorrecto<br>';
		echo '23: auth_session incorrecto<br>';
		echo '31: causa no encontrada<br>';
	}
	
	public function _valida_login($username,$password){
		if ($username!='' && $password!=''){
			$adm = $this->administradores_m->get_by(array('username'=>$username,'password'=>$password,'activo'=>'S','public'=>'S'));
			if (count($adm)==1){
				$user_token = md5(uniqid());
				$fields_save = array();
				$fields_save['token'] = $user_token;
				$fields_save['auth_session'] = md5(uniqid());
				$fields_save['timestamp_token'] = date('Y-m-d H:i:s');
				$this->administradores_m->save_default($fields_save,$adm->id);
				
				echo json_encode(array('response'=>0,'user_token'=>$user_token));
			} else {
				echo json_encode(array('response'=>-1,'error'=>21));
			}
		} else {
			echo json_encode(array('response'=>-1,'error'=>20));
		}
	}
	public function auth(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$token = base64_decode($this->input->post('token'));
		if ($token==$this->token){
			return $this->_valida_login($username,$password);
		} else {
			echo json_encode(array('response'=>-1,'error'=>'10'));
		}
	}
	public function getAuthSession($token=null){
		$adm = $this->administradores_m->get_by(array('token'=>$token));
		if (count($adm)==1){
			echo json_encode(array('auth_session'=>$adm->auth_session));
		} else {
			echo json_encode(array('response'=>-1,'error'=>22));
		}
	}
	public function _validSession($auth_session=null){
		$adm = $this->administradores_m->get_by(array('auth_session'=>$auth_session));
		if (count($adm)==1){
			return true;
		} else {
			return false;
		}
	}
	public function insertCausa($auth_session=null){
		if ($this->_validSession($auth_session)){
			$adm = $this->administradores_m->get_by(array('auth_session'=>$auth_session));
			if ($this->input->post('id_cuenta')!='' && $this->input->post('id_etapa')!='' && $this->input->post('fecha_etapa')!=''){
				$fields_save = array();
					$fields_save['id_cuenta'] = $this->input->post('id_cuenta');
					$fields_save['id_etapa'] = $this->input->post('id_etapa');
					$fields_save['fecha_etapa'] = $this->input->post('fecha_etapa');
					$fields_save['observaciones'] = $this->input->post('observaciones');
					$fields_save['api_status'] = $this->input->post('api_status');
					$fields_save['api_id'] = $this->input->post('api_id');
					$this->load->model('cuentas_etapas_m');
					$this->cuentas_etapas_m->save_default($fields_save,$this->input->post('idce'));
					if ($this->input->post('idce')>0){
						$idce = $this->db->insert_id();
					} else {
						$idce = $this->input->post('idce');
					}
					$fields_save = array();
					$fields_save['id_etapa'] = $this->input->post('id_etapa');
					$fields_save['fecha_etapa'] = $this->input->post('fecha_etapa');
					$this->cuentas_m->save_default($fields_save,$this->input->post('id_cuenta'));
					echo json_encode(array('response'=>0,'idce'=>$idce));
			} else {
				echo json_encode(array('response'=>-1,'error'=>40));
			}
		} else {
			echo json_encode(array('response'=>-1,'error'=>23));
		}
	}
	public function getCuentaEtapa($auth_session=null,$idce=null){
		if ($this->_validSession($auth_session)){
			$adm = $this->administradores_m->get_by(array('auth_session'=>$auth_session));
			$this->load->model('cuentas_etapas_m');
			$cuenta_etapa = $this->cuentas_etapas_m->get_by(array('id'=>$idce));
			if (count($cuenta_etapa)==1){
				$put = array();
					$put['idce'] = $cuenta_etapa->id;
					$put['id_cuenta'] = $cuenta_etapa->id_cuenta;
					$put['id_etapa'] = $cuenta_etapa->id_etapa;
					$put['fecha_etapa'] = $cuenta_etapa->fecha_etapa;
					$put['observaciones'] = $cuenta_etapa->observaciones;
					$put['api_status'] = $cuenta_etapa->api_status;
					$put['api_id'] = $cuenta_etapa->api_id;
				echo json_encode(array('response'=>0,'etapa'=>$put));
			} else {
				echo json_encode(array('response'=>-1,'error'=>31));
			}
		} else {
			echo json_encode(array('response'=>-1,'error'=>23));
		}
	}
	public function getCuentaEtapas($auth_session=null,$id_cuenta=null){
		if ($this->_validSession($auth_session)){
			$adm = $this->administradores_m->get_by(array('auth_session'=>$auth_session));
			$this->load->model('cuentas_etapas_m');
			$cuenta_etapa = $this->cuentas_etapas_m->get_many_by(array('id_cuenta'=>$id_cuenta));
			if (count($cuenta_etapa)>0){
				$p = array();
				foreach ($cuenta_etapa as $ce){
					$put = array();
						$put['idce'] = $ce->id;
						$put['id_cuenta'] = $ce->id_cuenta;
						$put['id_etapa'] = $ce->id_etapa;
						$put['fecha_etapa'] = $ce->fecha_etapa;
						$put['observaciones'] = $ce->observaciones;
						$put['api_status'] = $ce->api_status;
						$put['api_id'] = $ce->api_id;
					$p[] = $put;
				}
				echo json_encode(array('response'=>0,'etapas'=>$p));
			} else {
				echo json_encode(array('response'=>-1,'error'=>32));
			}
		} else {
			echo json_encode(array('response'=>-1,'error'=>23));
		}
	}
	public function getCuentaEtapasProcurador($auth_session=null){
		if ($this->_validSession($auth_session)){
			$adm = $this->administradores_m->get_by(array('auth_session'=>$auth_session));
			$c = array();
			$causas = $this->cuentas_m->get_procurador_api(array('id_procurador'=>$adm->id));
			if (count($causas)>0){
				foreach ($causas as $causa){
					if ($causa->id_etapa!=null){
						$put = array();
						$put['idce'] = $causa->idce;
						$put['id_cuenta'] = $causa->id;
						$put['tribunal'] = $causa->tribunal;
						$put['rol'] = $causa->rol;
						$put['rut'] = $causa->rut;
						$put['nombres'] = $causa->nombres;
						$put['ap_pat'] = $causa->ap_pat;
						$put['id_etapa'] = $causa->id_etapa;
						$put['etapa'] = $causa->etapa;
						$put['fecha_etapa'] = $causa->fecha_etapa;
						$put['observaciones'] = $causa->observaciones;
						$put['exorto'] = $causa->exorto;
						$put['estado_cuenta'] = $causa->estado_cuenta;
						$put['sucesor'] = $causa->sucesor;
						$c[] = $put;
					}
				}
				
			}
			
			echo json_encode(array('response'=>0,'causas'=>$c));
		} else {
			echo json_encode(array('response'=>-1,'error'=>23));
		}
	}
	public function getTribunales($auth_session=null){
		if ($this->_validSession($auth_session)){
			$this->load->model ( 'tribunales_m' );
			$t = array();
			$tribunales = $this->tribunales_m->get_many_by(array('activo'=>'S'));
			if (count($tribunales)>0){
				foreach ($tribunales as $tribunal){
					$put = array();
					$put['id'] = $tribunal->id;
					$put['nombre'] = $tribunal->tribunal;
					$put['id_tribunal_padre'] = $tribunal->padre;
					$t[] = $put;
				}
				
			}
			echo json_encode(array('response'=>0,'tribunales'=>$t));
		}
	}
	public function getEtapas($auth_session=null){
		if ($this->_validSession($auth_session)){
			$this->load->model ( 'etapas_m' );
			$e = array();
			$etapas = $this->etapas_m->get_many_by(array('activo'=>'S'));
			if (count($etapas)>0){
				foreach ($etapas as $etapa){
					$put = array();
					$put['id'] = $etapa->id;
					$put['nombre'] = $etapa->etapa;
					$put['codigo'] = $etapa->codigo;
					$put['sucesor'] = $etapa->sucesor;
					$put['tipo'] = $etapa->tipo;
					$e[] = $put;
				}
				
			}
			echo json_encode(array('response'=>0,'etapas'=>$e));
		}
	}
}

?>