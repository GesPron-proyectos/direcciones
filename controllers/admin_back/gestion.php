<?php
class Gestion extends CI_Controller {
	public $data = array();
	public $activo = 'S';
	protected $show_tpl = TRUE;
	public function Cuentas() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		 
		$this->load->helper ( 'date_html_helper' );
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'vehiculos_m' );
		$this->load->model ( 'personal_m' );
		$this->load->model ( 'inmueble_m' );
		$this->load->model ( 'cuentas_etapas_m' );
		$this->load->model ( 'cuentas_juzgados_m');		
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'tipo_productos_m' );
		$this->load->model ( 'administradores_m' );	
		$this->load->model ( 'estados_cuenta_m' );
		$this->load->model ( 'cuentas_pagos_m');
		$this->load->model ( 'comprobantes_m');
		$this->load->model ( 'cuentas_gastos_m');
		$this->load->model ( 'cuentas_historial_m');
		$this->load->model ( 'etapas_m' );	
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'documento_m' );
		$this->load->model ( 'log_etapas_m' );
		$this->load->model ( 'receptor_m' );
		$this->load->model ( 'diligencia_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'bienes_m' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'pagare_m' );
		$this->load->model ( 'tipo_productos_m' );
		$this->load->model ( 'documento_plantilla_m' );
		$this->load->model ( 'mail_m' );
		$this->load->model ( 'cuentas_contratos_m' );
		$this->load->model ( 'garantias_m' );
		//$this->load->model ( 'cinformadas_m' );		
		/*seters*/
		$this->data['current'] = 'gestion';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'gestion/';
		$this->data['lists'] = array();
		
	 	$t=$this->tribunales_m->order_by("tribunal","ASC")->get_many_by(array('activo'=>'S','padre ='=>'0'));
	    $this->data['tribunales']['']='Seleccionar..';
	 	foreach ($t as $obj) {$this->data['tribunales'][$obj->id] = $obj->tribunal;}

		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		$perf = $this->session->userdata("usuario_perfil");
		///$perf = 1;
		foreach ($a as $obj) {
			if($perf ==3)
			{
				//echo $obj->estado;
				if($obj->id==1 || $obj->id==6)
				{
					$this->data['estados_cuenta'][$obj->id] = $obj->estado;	
				}				
			}
			else
			{
				$this->data['estados_cuenta'][$obj->id] = $obj->estado;	
			}
		}
		
		$this->data['forma_pagos'] = array(''=>'Forma Pago','TF'=>'Transferencia','DP'=>'Depósito','CH'=>'Cheque','EF'=>'Efectivo');
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );
			 
		$nodo = $this->data['nodo'];
		
		$tabs = array();
		$tabs_nombres = array();
			$tabs_nombres['resumen'] 		= 'Resumen';
			$tabs_nombres['datos_cuenta'] 	= 'Datos de la Cuenta';
			$tabs_nombres['historial'] 		= 'Historial';
			if($nodo->nombre=='fullpay' ){$tabs_nombres['telefonos'] = 'Teléfonos y Email';}
			if($nodo->nombre=='swcobranza'){$tabs_nombres['telefonos'] = 'Teléfonos';}
			$tabs_nombres['direcciones'] 	= 'Direcciones';
			$tabs_nombres['bienes'] 		= 'Bienes';
			$tabs_nombres['garantias'] 		= 'Garantias';
			$tabs_nombres['gastos'] 		= 'Gastos';
			$tabs_nombres['documentos'] 	= 'Documentos';
			$tabs_nombres['etapas_juicio'] 	= 'Etapas de juicio';
	 		$tabs_nombres['pagares'] 		= 'Pagares';
	 		$tabs_nombres['pagos'] 			= 'Pagos';
	 		$tabs_nombres['mail']			= 'Email';
	 		//$tabs_nombres['juzgados']		= 'Juzgados';
	 		
		if ($nodo->nombre=='fullpay'){
			if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2){
				$tabs['resumen'] 		= 1;
				$tabs['etapas_juicio'] 	= 2;
				$tabs['documentos'] 	= 3;
				$tabs['datos_cuenta'] 	= 4;
				//$tabs['juzgados'] 		= 5;	
				$tabs['pagares'] 		= 5;
				$tabs['direcciones'] 	= 6;
				$tabs['bienes'] 		= 7;
				$tabs['garantias'] 		= 8;
				$tabs['telefonos'] 		= 9;
				//$tabs['mail']			= 9;
				$tabs['historial'] 		= 10;
				$tabs['gastos'] 		= 11;
				$tabs['pagos'] 			= 12;
				//Nuevo Carlos Rojas
			
			} else {
				$tabs['resumen'] 		= 1;
				$tabs['datos_cuenta'] 	= 2;
                $tabs['pagares'] 		= 3;
				$tabs['etapas_juicio'] 	= 4;
				$tabs['direcciones'] 	= 5;
				$tabs['bienes'] 		= 6;
				
				$tabs['telefonos'] 		= 7;
				$tabs['mail']			= 8;
				$tabs['historial'] 		= 9;
				$tabs['gastos'] 		= 10;
				$tabs['documentos'] 	= 11;
				$tabs['garantias'] 		= 12;
			}
		}
		if ($nodo->nombre=='swcobranza'){
			if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2){
				$tabs['resumen'] 		= 1;
				$tabs['datos_cuenta'] 	= 2;
				$tabs['pagos'] 			= 3;
				$tabs['historial'] 		= 4;
				$tabs['etapas_juicio'] 	= 5;
				$tabs['gastos'] 		= 6;
				$tabs['pagares'] 		= 7;
				$tabs['telefonos'] 		= 8;
				$tabs['direcciones'] 	= 9;
				$tabs['bienes'] 		= 10;
				$tabs['mail']			= 11;
				$tabs['documentos'] 	= 12;
				$tabs['garantias'] 		= 13;
			
			} else {
				$tabs['resumen'] 		= 1;
				$tabs['datos_cuenta'] 	= 2;
				$tabs['etapas_juicio'] 	= 3;
				$tabs['direcciones'] 	= 4;
				$tabs['bienes'] 		= 5;
				$tabs['garantias'] 		= 6;
				$tabs['telefonos'] 		= 7;
				//$tabs['mail']			= 7;
				$tabs['historial'] 		= 8;
				$tabs['gastos'] 		= 9;
				$tabs['documentos'] 	= 10;
			}
		}
		$this->tabs = $tabs;
		$this->data['tabs'] = $tabs;
		$this->data['tabs_nombres'] = $tabs_nombres;
		$this->data['total_tabs'] = 13;
		
	}
	function _unset_sessions_success(){
		$this->session->unset_userdata('success_gasto');
		$this->session->unset_userdata('success_cuenta');
		$this->session->unset_userdata('success_historial');
		$this->session->unset_userdata('success_telefono');
		$this->session->unset_userdata('success_direccion');
		$this->session->unset_userdata('success_garantias');
		$this->session->unset_userdata('success_bien');
		$this->session->unset_userdata('success_pagare');
		$this->session->unset_userdata('success_etapa');
		$this->session->unset_userdata('success_pagos');
		$this->session->unset_userdata('success_juzgados');
		
	}
	
	function guardar_telefono($id_cuenta,$id=''){
		//$this->output->enable_profiler(TRUE);
		$tab = $this->tabs['telefonos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->form_validation->set_rules('tipo','tipo','trim|required|xss_clean');
		$this->form_validation->set_rules('numero','Número','trim|required|xss_clean');
		$this->form_validation->set_rules('observacion','Observación','trim|xss_clean');
		if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save['id_cuenta'] = $id_cuenta;
			$fields_save['tipo'] = $this->input->post('tipo');
			$fields_save['numero'] = $this->input->post('numero');
			$fields_save['observacion'] = $this->input->post('observacion');
			$fields_save['estado'] = 0;
			$this->telefono_m->save_default($fields_save,$id);
			
			$this->session->set_userdata('success_telefono','El teléfono se ha guardado exitosamente');
			
			redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			$this->index($id_cuenta,$tab,$id);
		}
	}
	
	function guardar_direccion($id_cuenta,$id=''){
		//$this->output->enable_profiler(TRUE);
		
		$tab = $this->tabs['direcciones'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('direccion','Dirección','trim|required|xss_clean');
		$this->form_validation->set_rules('id_comuna','Comuna','trim|required|xss_clean');
		$this->form_validation->set_rules('observacion','Observación','trim|xss_clean');
		$this->form_validation->set_rules('tipo','Tipo','trim|xss_clean');
		if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save['id_cuenta'] = $id_cuenta;
			$fields_save['direccion'] = $this->input->post('direccion');
			$fields_save['id_comuna'] = $this->input->post('id_comuna');
			$fields_save['observacion'] = $this->input->post('observacion');
			$fields_save['tipo'] = $this->input->post('tipo');
			$fields_save['estado'] = 0;
			$this->direccion_m->save_default($fields_save,$id);
			$this->session->set_userdata('success_direccion','La dirección se ha guardado exitosamente');
			
			redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			$this->index($id_cuenta,$tab,$id);
		}
	}
	
	
	
	function guardar_garantia($id_cuenta,$id=''){
		//$this->output->enable_profiler(TRUE);
		
		$tab = $this->tabs['garantias'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('tipo_','tipo_','trim|required|xss_clean');
		//$this->form_validation->set_rules('observacion','Observación','trim|xss_clean');
		if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save['id_cuenta'] = $id_cuenta;			
			
			$fields_save['estado'] = 0;
			//print_r($_POST);
			if ($this->input->post('tipo_')==1 && $this->input->post('tipo')==4){
				$fields_save['tipo_vehiculo'] = $this->input->post('tipo_vehiculo');
				$fields_save['marca'] = $this->input->post('marca');
				$fields_save['modelo'] = $this->input->post('modelo');
				$fields_save['n_motor'] = $this->input->post('n_motor');
				$fields_save['color'] = $this->input->post('color');
				$fields_save['inscripcion'] = $this->input->post('inscripcion');				
				$fields_save['fecha_cont'] = $this->input->post('FechaCont');
				$fields_save['n_repertorio'] = $this->input->post('Nrepertorio');				
				$fields_save['n_chachis'] = $this->input->post('nchasis');
				$fields_save['anio'] = $this->input->post('anio');
				$fields_save['placaunica'] = $this->input->post('placaunica');
				$fields_save['placapatente'] = $this->input->post('placapatente');
				$fields_save['fechaexigible'] = $this->input->post('fechaex');
				$this->vehiculos_m->save_default($fields_save,$id);
			}
			
			if ($this->input->post('tipo_')==1 && $this->input->post('tipo')==5){
				$fields_save['tipo'] = $this->input->post('tipo');	
				$fields_save['observacion'] = $this->input->post('observacion');				
				$this->vehiculos_m->save_default($fields_save,$id);
			}
			
			if ($this->input->post('tipo_')==2 && $this->input->post('tipo')==6){
				$fields_save['tipo'] = $this->input->post('tipo');	
				$fields_save['observacion'] = $this->input->post('observacion');				
				$this->inmueble_m->save_default($fields_save,$id);
			}
			
			if ($this->input->post('tipo_')==3 ){
				$fields_save['tipo'] = $this->input->post('tipo_');
				$fields_save['tipog'] = $this->input->post('tipog');
				$fields_save['nombreg'] = $this->input->post('nombreg');
				$fields_save['rutg'] = $this->input->post('rutg');
				$fields_save['domiciliog'] = $this->input->post('domiciliog');
				$this->personal_m->save_default($fields_save,$id);
			}
			
			$this->session->set_userdata('success_bien','El bien se ha guardado exitosamente');
			
			redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			$this->index($id_cuenta,$tab,$id);
		}
	}
	
	
	
	
	
	function guardar_bien($id_cuenta,$id=''){
		$this->output->enable_profiler(TRUE);
		$tab = $this->tabs['bienes'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('tipo','tipo','trim|required|xss_clean');
		$this->form_validation->set_rules('observacion','Observación','trim|xss_clean');
		if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save['id_cuenta'] = $id_cuenta;
			$fields_save['tipo'] = $this->input->post('tipo');
			$fields_save['observacion'] = $this->input->post('observacion');
			$fields_save['estado'] = 0;
			//print_r($_POST);
			if ($this->input->post('tipo')==4){
				$fields_save['tipo_vehiculo'] = $this->input->post('tipo_vehiculo');
				$fields_save['marca'] = $this->input->post('marca');
				$fields_save['modelo'] = $this->input->post('modelo');
				$fields_save['n_motor'] = $this->input->post('n_motor');
				$fields_save['color'] = $this->input->post('color');
				$fields_save['inscripcion'] = $this->input->post('inscripcion');
				
				//[FechaCont] => 22 [Nrepertorio] => 222222
				
				$fields_save['n_chachis'] = $this->input->post('FechaCont');
				$fields_save['anio'] = $this->input->post('Nrepertorio');
				
				$fields_save['n_chachis'] = $this->input->post('nchasis');
				$fields_save['anio'] = $this->input->post('anio');
				$fields_save['placaunica'] = $this->input->post('placaunica');
				$fields_save['placapatente'] = $this->input->post('placapatente');
				$fields_save['fechaexigible'] = $this->input->post('fechaex');
				//$fields_save['inscripcion'] = $this->input->post('tipo_');
				//$fields_save['inscripcion'] = $this->input->post('tipo');
			}
			$this->bienes_m->save_default($fields_save,$id);
			$this->session->set_userdata('success_bien','El bien se ha guardado exitosamente');
			
			redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			$this->index($id_cuenta,$tab,$id);
		}
	}
	
	function guardar_mail($id_cuenta,$id=''){
		//$this->output->enable_profiler(TRUE);
		$tab = $this->tabs['telefonos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('mail','mail','trim|valid_email|required|xss_clean');
	  if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save['id_cuenta'] = $id_cuenta;
			$fields_save['mail'] = $this->input->post('mail');
			$this->mail_m->save_default($fields_save,$id);
			$this->session->set_userdata('success_mail','El bien se ha guardado exitosamente');
			
			redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			$this->index($id_cuenta,$tab,$id);
		}
	}
		
	function guardar_interes($id_cuenta){
		//$this->output->enable_profiler(TRUE);
		
		//echo 'llega';
		//die();
		
		$tab = $this->tabs['pagos'];
		$this->_unset_sessions_success();
		//$cuentas = $this->cuentas_m->get_by( array('id_cuenta'=>$id_cuenta) );
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('intereses','intereses','trim');
	  if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			//$fields_save['id_cuenta'] = $id_cuenta;
			$fields_save['intereses'] = $this->input->post('intereses');
			$this->cuentas_m->save_default($fields_save,$id_cuenta);
			//$this->session->set_userdata('success_mail','El bien se ha guardado exitosamente');
			
			redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			$this->index($id_cuenta,$tab);
		}
	}
	
	function guardar_historial($id_cuenta,$id=''){
		
		//$this->output->enable_profiler(TRUE);
		
		$tab = $this->tabs['historial'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('observaciones','Observación','trim|required|xss_clean');
		
		
		if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save['id_cuenta'] = $id_cuenta;
			if ($id==''){
				$fields_save['fecha'] = date('Y-m-d H:i:s');
			}
			$fields_save['observaciones'] = $this->input->post('observaciones');
			$this->cuentas_historial_m->save_default($fields_save,$id);
			$this->session->set_userdata('success_historial','La observación ha sido registrada exitosamente');
			
			redirect('admin/gestion/index/'.$id_cuenta.'/'.$tab);
		} else {
			$this->index($id_cuenta,$tab,$id);
		}
	}

	function guardar_juzgado($id_cuenta,$id=''){
		
	    //print_r($this);
		//print_r($_POST);
		$this->_unset_sessions_success();

		$cta=$this->cuentas_m->get_by(array('id' => $id_cuenta));
	    $tab = $this->tabs['datos_cuenta'];	  
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('id_distritonew', 'Distrito', 'trim|required|xss_clean');
		$this->form_validation->set_rules('id_tribunal', 'Tribunal', 'trim|required|xss_clean');
		$this->form_validation->set_rules('rolE', 'Rol', 'trim|xss_clean');
		//$this->form_validation->set_rules('obs_administrador', 'Observacion Administrador', 'trim|xss_clean');
		//die();
		if ($this->form_validation->run() == TRUE){

			$fields_save = array();     
			
			$fields_save['id_cuenta'] = $id_cuenta;
			$fields_save['id_distrito'] = $this->input->post('id_distritonew');			
			$fields_save['id_juzgado'] = $this->input->post('id_tribunal');
			$fields_save['numero_rol'] = $this->input->post('rolE');
			
			$cuenta_etapa = $this->cuentas_juzgados_m->save_default($fields_save,$id);
		 	$this->session->set_userdata('success_juzgados','El Juzgado ha sido guardado exitosamente');

			//Nuevo Carlos Rojas 7/6/16
			$fields_save_cuenta = array();     
			if ($this->input->post('id_tribunal')>0) { $fields_save_cuenta['id_tribunal'] = $this->input->post('id_tribunal');}
			if ($this->input->post('id_distritonew')>0) { $fields_save_cuenta['id_distrito'] = $this->input->post('id_distritonew');}
			if ($this->input->post('rolE')!='') { $fields_save_cuenta['rol'] = $this->input->post('rolE');}
			
			//print_r($fields_save);
			//print_r($fields_save_cuenta);
			//print_r($id_cuenta);
			
			$this->cuentas_m->save_default($fields_save_cuenta,$id_cuenta);
			//$this->output->enable_profiler(TRUE);
			//redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			
			$this->index($id_cuenta,$tab,$id);
		}
		
	}

	public function eliminar_juzgado($id_cuenta,$id){
		
		$tab = $this->tabs['datos_cuenta'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->cuentas_juzgados_m->eliminar_juzgado($id);
		
		$this->session->set_userdata('success_mail','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}

	function guardar_cuenta($id_cuenta){

		// $this->output->enable_profiler(TRUE);
	    $tab = $this->tabs['datos_cuenta'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('receptor','Receptor','trim|xss_clean');
		//$this->form_validation->set_rules('id_tribunal','id_tribunal','trim|xss_clean');
		$this->form_validation->set_rules('id_tribunal', 'Tribunal', 'trim|xss_clean');
		$this->form_validation->set_rules('id_distrito','id_distrito','trim|xss_clean');
		
		//nuevos Carlos Rojas
		$this->form_validation->set_rules('id_distritoE', 'DistritoE', 'trim|xss_clean');
		$this->form_validation->set_rules('id_tribunalE','id_tribunalE','trim|xss_clean');
		$this->form_validation->set_rules('rolE','RolE','trim|xss_clean');

		//print_r($_POST);echo "<br><br>";
		//print_r($_REQUEST);echo "<br><br>";		
		
		
		$this->form_validation->set_rules('id_mandante','id_mandante','trim|xss_clean');
		$this->form_validation->set_rules('rol','Rol','trim|xss_clean');
		$this->form_validation->set_rules('rol1','Rol','trim|xss_clean');
		$this->form_validation->set_rules('numero_contrato','Número Contrato','trim');
		$this->form_validation->set_rules('id_castigo','Castigo','trim|xss_clean');
		$this->form_validation->set_rules('rol1_y','Rol año','trim|xss_clean');
		$this->form_validation->set_rules('rol2','Rol2','trim|xss_clean');
		$this->form_validation->set_rules('rol2_y','Rol2 año','trim|xss_clean');
		$this->form_validation->set_rules('fecha_termino_suspension', 'trim|xss_clean');
		$this->form_validation->set_rules('no_llamar','No llamar','trim|xss_clean');
		if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2){
			$this->form_validation->set_rules('id_procurador','Procurador','trim|xss_clean');
			$this->form_validation->set_rules('id_mandante','Mandante','trim|xss_clean');
			$this->form_validation->set_rules('tipo_demanda','Tipo demanda','trim|xss_clean');
			$this->form_validation->set_rules('exorto','Exhorto','trim|xss_clean');
			$this->form_validation->set_rules('id_estado_cuenta','Estado cuenta','trim|xss_clean');
			$this->form_validation->set_rules('fecha_escritura_personeria','Escritura Personería','trim|xss_clean');
			$this->form_validation->set_rules('fecha_asignacion','Fecha Asignación','trim|xss_clean');
			$this->form_validation->set_rules('notaria_personeria','Notaría Personería','trim|xss_clean');
			$this->form_validation->set_rules('titular_personeria','Titular Personería','trim|xss_clean');
			$this->form_validation->set_rules('notificaciones','Notificaciones','trim|xss_clean');
			$this->form_validation->set_rules('medio_contacto','Medio Contacto','trim|xss_clean');
			$this->form_validation->set_rules('medio_contacto_otro','Medio contacto otro','trim|xss_clean');
			$this->form_validation->set_rules('medio_informado','Medio informado','trim|xss_clean');
			$this->form_validation->set_rules('medio_informado_otro','Medio informado otro','trim|xss_clean');
			$this->form_validation->set_rules('numero_contrato','Número Contrato','trim');			
		}
		
		if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save_contrato = array();
			$estado_cuenta = array();
			$exh_not = false;
			$cuenta_aux = $this->cuentas_m->get_cuentas($id_cuenta);
			if ($this->input->post('receptor')!='') { $fields_save['receptor'] = $this->input->post('receptor');}
			if ($this->input->post('id_tribunal')>0) { $fields_save['id_tribunal'] = $this->input->post('id_tribunal');}
			if ($this->input->post('id_distrito')>0) { $fields_save['id_distrito'] = $this->input->post('id_distrito');}
			if ($this->input->post('rol')!='') { $fields_save['rol'] = $this->input->post('rol');}
			if ($this->input->post('rol1')!='') { $fields_save['rol1'] = $this->input->post('rol1');}
			if ($this->input->post('rol1_y')!='') { $fields_save['rol1_y'] = $this->input->post('rol1_y');}
			if ($this->input->post('rol2')!='') { $fields_save['rol2'] = $this->input->post('rol2');}
			if ($this->input->post('rol2_y')!='') { $fields_save['rol2_y'] = $this->input->post('rol2_y');}
			if ($this->input->post('id_mandante')!='') { $fields_save['id_mandante'] = $this->input->post('id_mandante');}
			if ($this->input->post('id_castigo')!='') { $fields_save['id_castigo'] = $this->input->post('id_castigo');}
			
			
			
			//die();
			
			
			//Nuevo Carlos Rojas 7/6/16
			$fields_save['id_distrito_ex'] = $this->input->post('id_distritoEx');
			$fields_save['id_tribunal_ex'] = $this->input->post('id_tribunalE');
			$fields_save['rolE'] = $this->input->post('rolE');
			$fields_save['operacion'] = $this->input->post('noperacion');
					
			//die();
			//if ($this->input->post('id_distritoE')!='') { $fields_save['id_mandante'] = $this->input->post('id_distritoE');}
			//if ($this->input->post('id_tribunalE')!='') { $fields_save['id_castigo'] = $this->input->post('id_tribunalE');}
			
			
			//if ($this->input->post('numero_contrato')!='') { $fields_save['numero_contrato'] = $this->input->post('numero_contrato');}
			
			$fields_save['notaria_personeria'] = $this->input->post('notaria_personeria');
		    $fields_save['medio_contacto'] = $this->input->post('medio_contacto');
			$fields_save['medio_contacto_otro'] = $this->input->post('medio_contacto_otro');
			$fields_save['medio_informado'] = $this->input->post('medio_informado');
			$fields_save['medio_informado_otro'] = $this->input->post('medio_informado_otro');
			
			if(isset($_POST['no_llamar']) && $_POST['no_llamar'] == '1'){
			$fields_save['no_llamar'] = $this->input->post('no_llamar');
			}else{
			$fields_save['no_llamar'] = '0';	
			}
			if ($this->input->post('id_estado_cuenta')!='') { 
					$fields_save['id_estado_cuenta'] = $this->input->post('id_estado_cuenta');
						
			if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2){
				if ($this->input->post('id_procurador')!='') { $fields_save['id_procurador'] = $this->input->post('id_procurador');}
				if ($this->input->post('tipo_demanda')!='') { $fields_save['tipo_demanda'] = $this->input->post('tipo_demanda');}
				if ($this->input->post('exorto')!='') { $fields_save['exorto'] = $this->input->post('exorto');}
								
				
					
					if (count($cuenta_aux)==1 && $cuenta_aux->id_estado_cuenta!=$this->input->post('id_estado_cuenta')){
						$estado_cuenta = $this->estados_cuenta_m->get_by(array('id'=>$this->input->post('id_estado_cuenta')));
						if ($cuenta_aux->id_estado_cuenta==1){
							$exh_not = true;
						}
					}
				}
				if ($this->input->post('fecha_escritura_personeria')!='') { $fields_save['fecha_escritura_personeria'] = date('Y-m-d',strtotime($this->input->post('fecha_escritura_personeria')));}
			    if ($this->input->post('fecha_asignacion')!='') { $fields_save['fecha_asignacion'] = date('Y-m-d',strtotime($this->input->post('fecha_asignacion')));}		
				if ($this->input->post('notaria_personeria')!='') { $fields_save['notaria_personeria'] = $this->input->post('notaria_personeria');}
				if ($this->input->post('titular_personeria')!='') { $fields_save['titular_personeria'] = $this->input->post('titular_personeria');}
			    if ($this->input->post('fecha_termino_suspension')!='') { 
			    	$fields_save['fecha_termino_suspension'] = date('Y-m-d',strtotime($this->input->post('fecha_termino_suspension')));
			    } else {
			    	$fields_save['fecha_termino_suspension'] = '';
			    }		
			
				$notificaciones = '';
				$notificaciones = '0';
			
				 if(isset($_POST['notificaciones'])){ 
				        $notificaciones = '1';		
						$fields_save['notificaciones'] = $notificaciones;
				}else{
				
					$fields_save['notificaciones'] = $notificaciones;
			   }
				
			}
			//print_r($fields_save);
			$this->cuentas_m->save_default($fields_save,$id_cuenta);
				if($id_cuenta == ''){
					$id_cuenta = $this->db->insert_id();
				} 
			/*
				echo "ss";
				echo $_REQUEST['noperacion'];
				print_r($_REQUEST);
				echo "<br><br>";
				//print_r($this->input->post('id_tribunalE'));
				print_r($this->input->post);
				echo "<br><br>";
				print_r($fields_save);
				echo "<br><br>";
				//echo 'fdfsdfsdfs'.' '.$id_cuenta;
				echo $this->db->last_query();
				die();
			*/
			
			$nodo = $this->nodo_m->get_by( array('activo'=>'S') );
			//echo $nodo->nombre;
			if ($nodo->nombre=='fullpay'){
				if (count($estado_cuenta)==1 && $estado_cuenta->id_etapa!=''){
					$fields_save = array();
					$fields_save['id_cuenta'] = $id_cuenta;
					$fields_save['id_etapa'] = $estado_cuenta->id_etapa;
					$fields_save['fecha_etapa'] = date('Y-m-d');
					$fields_save['observaciones'] = 'Asignación automática por cambio de estado de la cuenta';
					$this->cuentas_etapas_m->save_default($fields_save,'');
					$this->cuentas_m->save_default(array('id_etapa'=>$fields_save['id_etapa'],'fecha_etapa'=>$fields_save['fecha_etapa']),$id_cuenta);
					if ($exh_not){
						$subject = 'Cambio de cuenta '.$cuenta_aux->rut.' de Vigente a Exhorto';
						$to = 'exhortos@fullpay.cl';
						$cc = '';
						$this->_alert_email($to,$cc,$subject,'',false);
					}
				}
			}
			
			
			
			if ($nodo->nombre=='fullpay'){
				$cuentas_contratos = $this->cuentas_contratos_m->get_by(array('id_cuenta'=>$id_cuenta));
			 if(count($cuentas_contratos) > 0 ){
					$fields_save_contrato['numero_contrato'] = $this->input->post('numero_contrato');
					$this->cuentas_contratos_m->save_default($fields_save_contrato,$cuentas_contratos->id);
					
				} else {
					$fields_save_contrato['numero_contrato'] = $this->input->post('numero_contrato');
					$fields_save_contrato['id_cuenta'] = $id_cuenta;
					$this->cuentas_contratos_m->save_default($fields_save_contrato,'');
				}
			}
						
			$this->session->set_userdata('success_cuenta','Los datos han sido actualizados exitosamente');
		    redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			$this->index($id_cuenta,$tab);
		}
	}
	
	function guardar_gasto($id_cuenta,$id=''){
		
		//$this->output->enable_profiler(TRUE);
		$tab = $this->tabs['gastos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$estado_retencion = '';
		$this->form_validation->set_rules('item_gasto', 'Íem gasto', 'trim|required|xss_clean');
		$this->form_validation->set_rules('id_diligencia', 'Diligencia', 'trim|required|xss_clean');
		$this->form_validation->set_rules('id_estado_pago', 'Pagado/Pendiente', 'trim|xss_clean');
		$this->form_validation->set_rules('fecha', 'Fecha', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fecha_ingreso_banco','Fecha Ingreso Banco','trim|xss_clean');
		$this->form_validation->set_rules('fecha_recepcion','Fecha Recepción','trim|xss_clean');
		$this->form_validation->set_rules('n_boleta', 'Nº Boleta', 'trim|xss_clean');
		$this->form_validation->set_rules('id_receptor', 'Receptor', 'trim|xss_clean');
		$this->form_validation->set_rules('monto','Monto','trim|required|xss_clean');
		$this->form_validation->set_rules('retencion','Retención','trim|xss_clean');
		$this->form_validation->set_rules('estado_retencion','Estado retención','trim|xss_clean');
		
		
		if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save['id_cuenta'] = $id_cuenta;
			$fields_save['id_estado_pago'] = 0;
			$fields_save['item_gasto'] = $this->input->post('item_gasto');
			$fields_save['id_diligencia'] = $this->input->post('id_diligencia');
			$fields_save['id_estado_pago'] = $this->input->post('id_estado_pago');
			$fields_save['n_boleta'] = $this->input->post('n_boleta');
		    $fields_save['fecha'] = date('Y-m-d',strtotime($this->input->post('fecha')));
			$fields_save['fecha_ingreso_banco'] = date('Y-m-d',strtotime($this->input->post('fecha_ingreso_banco')));
			$fields_save['fecha_recepcion'] = date('Y-m-d',strtotime($this->input->post('fecha_recepcion')));
			$fields_save['id_receptor'] = $this->input->post('id_receptor');
			$fields_save['monto'] = $this->input->post('monto');
			if(isset($_POST['estado_retencion']) && $_POST['estado_retencion'] == '1'){
			$fields_save['retencion'] = $this->input->post('retencion');
			}else{
			$fields_save['retencion'] = '0';	
			}
			if(isset($_POST['estado_retencion']) && $_POST['estado_retencion'] == 'S'){ 
				        $estado_retencion = '1';		
						$fields_save['estado_retencion'] = $estado_retencion;
						}else{
						$fields_save['estado_retencion'] = $estado_retencion;
					   }
			  if ($id!=''){
				$gastos = $this->cuentas_gastos_m->get_by(array('id'=>$id));
			}
			
			$this->cuentas_gastos_m->save_default($fields_save,$id);
			if ($this->input->post('monto')>0){
				if ($id==''){
					$this->db->query('UPDATE 0_cuentas SET monto_gasto_new = monto_gasto_new + ('.$this->input->post('monto').') WHERE id='.$id_cuenta );
				} else {
					$this->db->query('UPDATE 0_cuentas SET monto_gasto_new = monto_gasto_new - ('.$gastos->monto.') WHERE id='.$id_cuenta );
					$this->db->query('UPDATE 0_cuentas SET monto_gasto_new = monto_gasto_new + ('.$this->input->post('monto').') WHERE id='.$id_cuenta );
				}
				
			}
			
			$this->session->set_userdata('success_gasto','El gasto ha sido guardado exitosamente');			
			redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			//echo validation_errors('<div class="error">', '</div>')
			$this->index($id_cuenta,$tab,$id);
		}
		
	}
	
	function guardar_pagare($id_cuenta,$id=''){
                // $this->output->enable_profiler(TRUE);

        $this->load->model ( 'nodo_m' );
        $this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );
        $nodo = $this->data['nodo'];


        $tab = $this->tabs['pagares'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('id_tipo_producto', 'Tipo Producto', 'trim||xss_clean');
		$this->form_validation->set_rules('n_pagare', 'Nº Pagaré', 'trim|xss_clean');


        if( $nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ) {
            $this->form_validation->set_rules('fecha_asignacion', 'Fecha Asignación', 'trim|xss_clean');
        }

        if( $nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ) {
		$this->form_validation->set_rules('fecha_suscripcion', 'Fecha Suscripción', 'trim|xss_clean');
        }



		$this->form_validation->set_rules('fecha_vencimiento', 'Fecha Vencimiento', 'trim||xss_clean');
		$this->form_validation->set_rules('monto_deuda', 'Monto', 'trim|xss_clean');
		$this->form_validation->set_rules('tasa_interes', 'Tasa Interés', 'trim|xss_clean');
		$this->form_validation->set_rules('numero_cuotas', 'Número Cuotas', 'trim|xss_clean');
		$this->form_validation->set_rules('valor_primera_cuota','Valor primera cuota', 'trim|xss_clean');
		$this->form_validation->set_rules('valor_ultima_cuota','Valor ultima cuota', 'trim|xss_clean');
		$this->form_validation->set_rules('vencimiento_primera_cuota', 'Vencimiento primera cuota', 'trim|xss_clean');
		$this->form_validation->set_rules('vencimiento_restantes_cuotas','Vencimiento restantes cuotas', 'trim|xss_clean');
		$this->form_validation->set_rules('nombre_aval','Nombre aval', 'trim|xss_clean');
		$this->form_validation->set_rules('ultima_cuota_pagada','última cuota poagada', 'trim|xss_clean');
		$this->form_validation->set_rules('fecha_pago_ultima_cuota','pago última cuota', 'trim|xss_clean');
		$this->form_validation->set_rules('valor_ultima_cuota_pagada','valor útima cuota', 'trim|xss_clean');
		$this->form_validation->set_rules('saldo_deuda','Saldo deuda', 'trim|xss_clean');
		$this->form_validation->set_rules('tasa_interes_anual','Tasa interes anual', 'trim|xss_clean');
		
		if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save['idcuenta'] = $id_cuenta;
			$fields_save['id_tipo_producto'] = $this->input->post('id_tipo_producto');
			$fields_save['n_pagare'] = $this->input->post('n_pagare');
			$fields_save['monto_deuda'] = $this->input->post('monto_deuda');
			$fields_save['tasa_interes'] = $this->input->post('tasa_interes');
			$fields_save['numero_cuotas'] = $this->input->post('numero_cuotas');
			$fields_save['valor_primera_cuota'] = $this->input->post('valor_primera_cuota');
			$fields_save['valor_ultima_cuota'] = $this->input->post('valor_ultima_cuota');

            if( $nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ) {
                $fields_save['fecha_suscripcion'] = date('Y-m-d', strtotime($this->input->post('fecha_suscripcion')));
            }

            if( $nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ) {
                $fields_save['fecha_asignacion'] = date('Y-m-d', strtotime($this->input->post('fecha_asignacion')));
            }

            $fields_save['fecha_vencimiento'] = date('Y-m-d',strtotime($this->input->post('fecha_vencimiento')));
			$fields_save['vencimiento_primera_cuota'] = date('Y-m-d',strtotime($this->input->post('vencimiento_primera_cuota')));
			$fields_save['vencimiento_restantes_cuotas'] = date('Y-m-d',strtotime($this->input->post('vencimiento_restantes_cuotas')));
			$fields_save['nombre_aval'] = $this->input->post('nombre_aval');
			$fields_save['ultima_cuota_pagada'] = $this->input->post('ultima_cuota_pagada');
			$fields_save['fecha_pago_ultima_cuota'] = date('Y-m-d',strtotime($this->input->post('fecha_pago_ultima_cuota')));
			$fields_save['valor_ultima_cuota_pagada'] = $this->input->post('valor_ultima_cuota_pagada');
			$fields_save['saldo_deuda'] = $this->input->post('saldo_deuda');
			$fields_save['tasa_interes_anual'] = $this->input->post('tasa_interes_anual');
			
			if ($id!=''){
				$pagare = $this->pagare_m->get_by(array('idpagare'=>$id));
			}
			$this->pagare_m->save_default($fields_save,$id);
			
			if ($this->input->post('monto_deuda')>0){

              // $this->db->query('UPDATE 0_cuentas SET monto_deuda = monto_deuda + ('.str_replace('.','',$this->input->post('monto_deuda')).') WHERE id='.$id_cuenta );
				if ($id==''){
                    $this->db->query('UPDATE 0_cuentas SET monto_deuda = monto_deuda + ('.str_replace('.','',$this->input->post('monto_deuda')).') WHERE id='.$id_cuenta );
				} else {

                    $this->db->query('UPDATE 0_cuentas SET monto_deuda = monto_deuda - ('.str_replace('.','',$pagare->monto_deuda).') WHERE id='.$id_cuenta );
					$this->db->query('UPDATE 0_cuentas SET monto_deuda = monto_deuda + ('.str_replace('.','',$this->input->post('monto_deuda')).') WHERE id='.$id_cuenta );
				}
			}
			
			
			$this->session->set_userdata('success_pagare','El pagare ha sido guardado exitosamente');			
			redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			
			$this->index($id_cuenta,$tab,$id);
		}
		
	}
	
	function guardar_etapa($id_cuenta,$id=''){
		
		$tab = $this->tabs['etapas_juicio'];
		$this->_unset_sessions_success();
		// $this->output->enable_profiler(TRUE);
		$cta=$this->cuentas_m->get_by(array('id' => $id_cuenta));
	  
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('id_etapa', 'Etapa Juicio', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fecha_etapa', 'Fcha Etapa', 'trim|required|xss_clean');
		$this->form_validation->set_rules('observaciones', 'Observacion', 'trim|xss_clean');
		//$this->form_validation->set_rules('obs_administrador', 'Observacion Administrador', 'trim|xss_clean');
		
		if ($this->form_validation->run() == TRUE){

		
		//print_r($cta);
			//if($cta->id_etapa == $this->input->post('id_etapa')){
			/*if($this->input->post('id_etapa')!=''){
				
		     $this->session->set_userdata('Esta etapa ya ha sido ingresada anteriormente');
		     redirect('admin/gestion/index/'.$id_cuenta);	
			}else*/
			{
			
			$fields_save = array();
			
			$fields_save['id_cuenta'] = $id_cuenta;
			if ($this->input->post('id_etapa')=='otro'){
				$fields_save['id_etapa'] = $this->input->post('etapa_otro');
			} else {
				$fields_save['id_etapa'] = $this->input->post('id_etapa');
			}
			
			//$fields_save['obs_administrador'] = $this->input->post('obs_administrador');
			$fields_save['fecha_etapa'] = date('Y-m-d',strtotime($this->input->post('fecha_etapa')));
			$fields_save['observaciones'] = $this->input->post('observaciones');
			//print_r($fields_save);
			$cuenta_etapa = $this->cuentas_etapas_m->save_default($fields_save,$id);
			$this->cuentas_m->save_default(array('id_etapa'=>$fields_save['id_etapa'],'fecha_etapa'=>$fields_save['fecha_etapa']),$id_cuenta);
			}
			
			//die();
			 //$this->output->enable_profiler(TRUE); 
			
			//$this->db->where( array( 'id_cuenta'=>$id_cuenta ) );
			$this->db->where('id_cuenta',$id_cuenta );
			$this->db->where('activo', 'S');	
			$this->db->select('id_etapa, fecha_etapa');
			$this->db->from('2_cuentas_etapas');
			$this->db->order_by("fecha_etapa", "desc");
			$this->db->limit(1, 0);
			$query = $this->db->get();
			$dat = $query->result();
			//echo $this->db->last_query();
			//print_r($dat);
			$id_etapas="";
			$fecha_etapa="";
			foreach ($dat as $obj) {
				$fecha_etapa = $obj->fecha_etapa;
				$id_etapas = $obj->id_etapa;
			}
			//echo $id_etapas;
			//echo $fecha_etapa;
			
			$this->cuentas_m->save_default(array('id_etapa'=>$id_etapas,'fecha_etapa'=>$fecha_etapa),$id_cuenta);


            $nodo = $this->nodo_m->get_by( array('activo'=>'S') );
			//echo $nodo->nombre;
			//print_r($_POST).'<br>';
			$etapa_diligencia = $this->etapas_m->get_by(array('id'=>$this->input->post('id_etapa')));
			//print_r($etapa_diligencia);
			//echo $this->db->last_query();			
			//$diligencia = $this->diligencia_m->get_by(array('id'=>$etapa_diligencia->id_diligencia));
					//print_r($nodo);
					//fullpay
            if ($nodo->nombre=='swcobranza'){
			//if ($nodo->nombre=='fullpay'){
                $etapa_diligencia = $this->etapas_m->get_by(array('id'=>$this->input->post('id_etapa')));
							
				//print_r($etapa_diligencia);
				//echo $etapa_diligencia->id;
                if(count($etapa_diligencia)==1 && $etapa_diligencia->id >0){
                    $diligencia = $this->diligencia_m->get_by(array('id'=>$etapa_diligencia->id));
					//echo $this->db->last_query();
					//print_r($diligencia);
                    $fields_save = array();
                    $fields_save['fecha'] = date('Y-m-d');
                    $fields_save['id_cuenta'] = $id_cuenta;
                    $fields_save['id_estado_pago'] = '-1';
                    $fields_save['id_diligencia'] = $etapa_diligencia->id_diligencia;
                    $this->cuentas_gastos_m->save_default($fields_save);
                }
            }
			//print_r($fields_save);
			
			$this->session->set_userdata('success_etapas','La etapa de juicio ha sido guardado exitosamente');			
			redirect('admin/gestion/index/'.$id_cuenta);
		} else {
			
			//$this->index($id_cuenta,$tab,$id);
		}
		
	}

	function index($id_cuenta='',$tab='1',$idregistro=''){
			
		//$this->output->enable_profiler(TRUE);
		$view='gestion'; 
		$nodo = $this->nodo_m->get_by( array('activo'=>'S') );
		if ($this->session->userdata('tab')){$tab=$this->session->userdata('tab');}
		/*
		 * ESTADOS PARA DIRECCIONES, TELEFONOS Y BIENES
		*/
		$castigados = array(''=>'Seleccionar','1'=>'No Catigado','2'=>'Castigo');
		$estados = array('0'=>'Sin confirmación','1'=>'Vigente','2'=>'No Vigente');
		$tipos = array('0'=>'Tipo','1'=>'Particular','2'=>'Comercial','3'=>'Celular','4'=>'Otro');
		$tipos_bienes = array('0'=>'Tipo','1'=>'Vehículo','2'=>'Inmueble');
		$tipos_vehiculos = array('0'=>'Automovil','1'=>'Moto','2'=>'Furgón','3'=>'Camioneta','4'=>'Stationwagon','5'=>'Otro');
		$tipos_direcciones = array(''=>'Sin confirmar...') + array('1'=>'Dirección Particular','2'=>'Dirección Comercial','3'=>'Otra','4'=>'Negativa');
		
		/*
		 * INFORMACIóN GENERAL DE LA CUENTA
		 */
		
		$medios_contactos = array(''=>'Seleccionar','1'=>'Se acerca a oficina','2'=>'Envía correo electrónico','3'=>'Se acerca a CMR','4'=>'Llama a oficina');
		$medios_informados = array(''=>'Seleccionar','1'=>'Teléfono','2'=>'Notificación Judicial','3'=>'Embargo','4'=>'Carta con Demanda','5'=>'Carta Notificado','6'=>'Iniciativa propia','7'=>'Retiro','8'=>'Correo electrónico','9'=>'Carta Asignación cartera');
	
		$cuenta = $this->cuentas_m->get_cuentas($id_cuenta); ///  aqui todos los join  293 	
		
		$procuradores = array(0=>'Seleccionar');
		$a=$this->administradores_m->get_many_by(array('activo'=>'S'));
		foreach ($a as $obj) {
			$procuradores[$obj->id] = $obj->nombres.' '.$obj->apellidos;
		}
		
		//$receptores = array(''=>'Seleccionar...')+$this->receptor_m->dropdown('nombre');
		
		$receptores = array(0=>'Seleccionar');
		$a=$this->receptor_m->get_many_by(array('activo'=>'S'));
		//print_r($a);
		foreach ($a as $obj) {
			$receptores[$obj->id] = $obj->nombre.' '.$obj->appat;
		}
		
		//print_r($receptores);
		$distritos = array();
		$a=$this->tribunales_m->get_many_by( array( "padre" => '0') );
		$distritos[0]='Seleccionar';
		foreach ($a as $obj) {$distritos[$obj->id] = $obj->tribunal;}
		
		$distritos = array();
		$this->db->where('padre', 0);
		$this->db->where('activo', 'S');
		$this->db->order_by('tribunal', 'ASC');
		$arr = $this->tribunales_m->get_all();
		$distritos[''] = 'Seleccionar';
		foreach($arr as $key=>$val){
			$distritos[$val->id]= $val->tribunal;
		}

		$tribunales = array();
		$a=$this->tribunales_m->order_by("id","ASC")->get_many_by( array( "padre" => $cuenta->id_distrito) );
		$tribunales[0]='Seleccionar';
		foreach ($a as $obj) {$tribunales[$obj->id] = $obj->tribunal;}	
		
		$tribunalesE = array();
		$a=$this->tribunales_m->order_by("id","ASC")->get_many_by( array( "padre" => $cuenta->id_distrito_ex) );
		$tribunalesE[0]='Seleccionar';
		foreach ($a as $obj) {$tribunalesE[$obj->id] = $obj->tribunal;}
		
		$tribunalesRoles = array();
		$this->tribunales_m->order_by("id","ASC");
		$a=$this->tribunales_m->order_by("id","ASC")->get_many_by( array( "padre" => $cuenta->id_distrito) );
		$tribunalesRoles[0]='Seleccionar';
		foreach ($a as $obj) {$tribunalesRoles[$obj->id] = $obj->tribunal;}
		
		$tipogarantias = array();
		//$a=$this->garantias_m->get_all();
		$a=$this->garantias_m->get_many_by(array('padre'=>'0'));
		$tipogarantias[0]='Seleccionar';
		foreach ($a as $obj) {$tipogarantias[$obj->id] = $obj->nombre;}
		//print_r($tipogarantias);
		
		/*
	   * GASTOS
		 * */
		$diligencias = array();
		if ($this->input->post('item_gasto')!=''){
			$a=$this->diligencia_m->get_many_by(array('item_gasto'=>$this->input->post('item_gasto')));
		} else {
			$a=$this->diligencia_m->get_all();
		}
		
		//***Mandantes
		$mandantes = array();
		$man=$this->mandantes_m->order_by("razon_social","DESC")->get_many_by(array('activo'=>'S'));
		$mandantes[0]='Seleccionar';
		foreach ($man as $obj) {$mandantes[$obj->id] = $obj->codigo_mandante.' ('.$obj->razon_social.')';}
		//**
		
		/*
		 * CUENTAS PAGOS 
		 * */		
		$cuentas_pagos = $this->cuentas_pagos_m->get_cuentas_pagos($id_cuenta);
		
		$diligencias[0]='Seleccionar';
		foreach ($a as $obj) {$diligencias[$obj->id] = $obj->nombre;}	
		
		$this->db->where(array('activo' => 'S'));
		$gastos = $this->cuentas_gastos_m->order_by("fecha","DESC")->get_many_by( array("id_cuenta" => $id_cuenta,"activo"=>"S") );
		$gastos = $this->cuentas_gastos_m->get_gastos_cuentas($id_cuenta);
		
		$estado_pago = array('2'=>'Pendiente','1'=>'Pagado');
		
	    $gasto_cuenta = $this->cuentas_m->gastos_cuentas($id_cuenta);
		
		//print_r($gasto_cuenta);
		/*
		 * EMAIL
		 * */
		
		
		$mail = $this->mail_m->get_mails($id_cuenta);
		
		/*
		 * HISTORIAL
		 * */
		//$historiales=$this->cuentas_historial_m->order_by("fecha","DESC")->get_many_by( array("id_cuenta" => $id_cuenta,"activo"=>"S") );
		$historiales=$this->cuentas_historial_m->get_cuentas_etapas_historial($id_cuenta);
		
		
		/*
		$vehiculosg=$this->vehiculos_m->get_cuentas_vehiculos($id_cuenta);
		$inmueblesg=$this->inmuebles_m->get_cuentas_inmuebles($id_cuenta);
		$personal=$this->personal_m->get_cuentas_personal($id_cuenta);
		*/
		
		/*
		 * BIENES
		 * */
		$this->db->order_by('fecha_crea DESC');
		$this->db->where(array('activo' => 'S'));
		$prendas = $this->vehiculos_m->get_many_by( array('id_cuenta'=>$id_cuenta) );
		
		/*
		 * BIENES
		 * */
		$this->db->order_by('fecha_crea DESC');
		$this->db->where(array('activo' => 'S'));
		$inmueblesg = $this->inmueble_m->get_many_by( array('id_cuenta'=>$id_cuenta) );
				
		/*
		 * BIENES
		 * */
		$this->db->order_by('fecha_crea DESC');
		$this->db->where(array('activo' => 'S'));
		$personalg = $this->personal_m->get_many_by( array('id_cuenta'=>$id_cuenta) );
				
		/*
	    * DOCUMENTOS
		 * 
		 * */
		if ($nodo->nombre=='fullpay'){
			$c_ex = $cuenta->exorto;
			$c_tp = $cuenta->tipo_demanda;
			if ($c_ex == ''){$c_ex=0;}
			if ($c_tp == ''){$c_tp=0;}
			//echo $c_ex;
			//echo $c_tp;
			$this->db->where(array('exorto'=>$c_ex,'tipo_demanda'=>$c_tp,'por_defecto'=>1));
		}
		//print_r($this->documento_plantilla_m);
		$this->db->order_by('posicion ASC');
		$documentos_plantillas = $this->documento_plantilla_m->dropdown('nombre_documento');	
		//print_r($documentos_plantillas);
				
		$this->db->order_by('posicion ASC');
		$documentos_plantillas_todas = array(''=>'Seleccionar')+$this->documento_plantilla_m->dropdown('nombre_documento');
		
		if (count($documentos_plantillas)>1){ $documentos_plantillas = array(''=>'Seleccionar')+$documentos_plantillas; }	
		//print_r($documentos_plantillas_todas);
		$documentos=$this->documento_m->order_by("fecha_crea","DESC")->get_documento($id_cuenta);
				
		//$this->db->order_by('etapa ASC');
	    $documentos_etapas = array();
		$a=$this->etapas_m->order_by("etapa","ASC")->get_many_by( array('activo' =>'S'));
		$documentos_etapas[0]='Seleccionar';
		foreach ($a as $obj) {$documentos_etapas[$obj->id] = $obj->etapa;}
				
		/*
		 * DIRECCIONES
		 * */
		$comunas = array(''=>'Seleccionar')+$this->comunas_m->get_dropdown('');
		$direcciones = $this->direccion_m->get_direccion_cuenta( $id_cuenta);
		
		/*
		 * TELEFONOS
		 * */
		$this->db->order_by('fecha_crea DESC');
		$this->db->where(array('activo' => 'S'));
		$telefonos = $this->telefono_m->get_many_by( array('id_cuenta'=>$id_cuenta) );
		/*
		 * BIENES
		 * */
		$this->db->order_by('fecha_crea DESC');
		$this->db->where(array('activo' => 'S'));
		$bienes = $this->bienes_m->get_many_by( array('id_cuenta'=>$id_cuenta) );
				
		/*
		 * JUZGADOS
		 * */
		$juzgados = array();
		//$this->db->order_by('fecha_crea DESC');
		//$this->db->where(array('activo' => 'S'));
		//$juzgados = $this->cuentas_juzgados_m->get_many_by( array('id_cuenta'=>$id_cuenta));
		$juzgados = $this->cuentas_juzgados_m->get_cuentas_juzgados($id_cuenta);
		
		//print_r($this->cuentas_juzgados_m);
		//print_r($juzgados);
		/*
		 * PAGARÉS
		 * */
		
		$tipos_productos = array(''=>'Seleccionar...')+$this->tipo_productos_m->dropdown('tipo');
		$pagares = $this->pagare_m->get_pagares_cuentas( $id_cuenta );
		/*
		 * ETAPAS JUICIO
		 * */
		$etapas_juicio = array();//$this->etapa_m->dropdown();
		//$this->db->order_by('fecha_crea DESC');
		$etapas_juicio = array(''=>'Seleccionar...')+$this->etapas_m->get_dropdown_etapas(array('s1.activo'=>'S'));
		//$this->db->order_by('fecha_etapa DESC');
		
		
	    $etapas_juicio_cuenta = $this->cuentas_etapas_m->get_cuentas_etapas( $id_cuenta );//print_r($etapas_juicio_cuenta);
		$etapas_juicio_cuenta2 = $this->cuentas_etapas_m->get_cuentas_etapas2( $id_cuenta );
	    
		$this->db->limit(15);
		$this->db->order_by('id','DESC');
		$this->db->where( array('id_cuenta'=>$id_cuenta) );
		$log_etapas = $this->log_etapas_m->get_log();
		/*
		 * CUENTAS PAGOS
		 * */
		$this->db->order_by('fecha_pago DESC,fecha_crea DESC');
		$pagos = $this->cuentas_pagos_m->get_many_by(array('activo'=>'S','id_cuenta'=>$id_cuenta));
		
		$formas_pago = array( ''=>'Seleccionar','TF'=>'Transferencia','DP'=>'Depósito','CH'=>'Cheque','EF'=>'Efectivo' );
		$acuerdos_pago = array( ''=>'Seleccionar','1'=>'Abono','2'=>'Acuerdo' );
		
		/*
		 * NODO
		 * */
		
		//print_r($cuenta);
		/*
		 * PASO A LA VISTA
		 * */
		$this->data['tipos_direcciones'] = $tipos_direcciones;							
		$this->data['medios_informados'] =$medios_informados;		
		$this->data['medios_contactos']  =$medios_contactos;	
		$this->data['castigados']	= $castigados;            
		$this->data['estado_pago']	= $estado_pago;        
		$this->data['mandantes']	= $mandantes;
		$this->data['gasto_cuenta'] = $gasto_cuenta;
		$this->data['id'] = $id_cuenta;
		$this->data['cuenta'] = $cuenta;
		$this->data['documentos_etapas'] = $documentos_etapas;
		$this->data['procuradores'] = $procuradores;
		$this->data['gastos'] = $gastos;
		$this->data['tribunales'] = $tribunales;
		$this->data['tribunalesE'] = $tribunalesE;
		$this->data['tipogarantias'] = $tipogarantias;
		
		$this->data['prendas'] = $prendas;
		$this->data['inmueblesg'] = $inmueblesg;
		$this->data['personalg'] = $personalg;
				
		//print_r($tribunalesE);
		//print_r($tribunalesE);
		$this->data['receptores'] = $receptores;
		$this->data['distritos'] = $distritos;
		$this->data['diligencias'] = $diligencias;
		$this->data['historiales'] = $historiales;
		$this->data['juzgados'] = $juzgados;
		$this->data['comunas'] = $comunas;
		$this->data['direcciones'] = $direcciones;
		$this->data['telefonos'] = $telefonos;
		$this->data['bienes'] = $bienes;
		$this->data['estados'] = $estados;
		$this->data['tipos'] = $tipos;
		$this->data['mail'] = 	$mail;
	    $this->data['tipos_bienes'] = $tipos_bienes;
	    $this->data['tipos_vehiculos'] = $tipos_vehiculos;
		$this->data['documentos'] = $documentos;
		$this->data['documentos_plantillas'] = $documentos_plantillas;
		$this->data['documentos_plantillas_todas'] = $documentos_plantillas_todas;
		
		
		$this->data['pagares'] = $pagares;
		$this->data['tipos_productos'] = $tipos_productos;
		$this->data['etapas_juicio'] = $etapas_juicio;
		$this->data['etapas_juicio_cuenta'] = $etapas_juicio_cuenta;
		$this->data['etapas_juicio_cuenta2'] = $etapas_juicio_cuenta2;
		$this->data['log_etapas'] = $log_etapas;
		$this->data['cuentas_pagos'] = $cuentas_pagos;
		$this->data['pagos'] = $pagos;
		$this->data['formas_pago'] = $formas_pago;
		$this->data['acuerdos_pago'] = $acuerdos_pago;
		$this->data['plantilla'].= $view;
		$this->data['tab']= $tab;
		$this->data['nodo'] = $nodo;	
		$this->data['id_cuenta'] = $id_cuenta;
		$this->data['idregistro'] = $idregistro;
		$this->load->view ( 'backend/index', $this->data );
	}
		
	
	public function eliminar_historial ($id_cuenta,$id){
		
		$tab = $this->tabs['historial'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->cuentas_historial_m->eliminar_historial($id);
		
		$this->session->set_userdata('success_historial','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}
	
	
   public function eliminar_direccion($id_cuenta,$id){
		
		$tab = $this->tabs['direcciones'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->direccion_m->eliminar_direccion($id);
		
		$this->session->set_userdata('success_direccion','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}
	
	public function eliminar_telefono($id_cuenta,$id){
		
		$tab = $this->tabs['telefonos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->telefono_m->eliminar_telefono($id);
		
		$this->session->set_userdata('success_telefono','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}
	
	public function eliminar_mail($id_cuenta,$id){
		
		$tab = $this->tabs['mail'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->mail_m->eliminar($id);
		
		$this->session->set_userdata('success_mail','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}
	
	
	
    public function eliminar_bien($id_cuenta,$id){
		
		$tab = $this->tabs['bienes'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->bienes_m->eliminar_bienes($id);
		
		$this->session->set_userdata('success_bien','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
	}
	
	
    public function eliminar_gastos($id_cuenta,$id){
		
		$tab = $this->tabs['gastos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->cuentas_gastos_m->eliminar_gasto($id);
		
		$this->session->set_userdata('success_bien','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}
	public function eliminar_pagos($id_cuenta,$id){
		
		$tab = $this->tabs['pagos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->cuentas_pagos_m->eliminar($id);
		
		$this->session->set_userdata('success_bien','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}
	
 public function eliminar_pagares($id_cuenta,$id){
		
		$tab = $this->tabs['pagares'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->pagare_m->eliminar_pagare($id);
		
		$this->session->set_userdata('success_pagares','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}
	
	
    public function eliminar_documento($id_cuenta,$id){
		
		$tab = $this->tabs['documentos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->documento_m->eliminar_documento($id);
		
		$this->session->set_userdata('success_documentos','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}
	
	
	
  public function eliminar_etapa_juicio($id_cuenta,$id){
		
		$tab = $this->tabs['etapas_juicio'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		
		$this->cuentas_etapas_m->eliminar_etapa_juicio($id);
		
		$this->session->set_userdata('success_etapas_juicio','Se ha eliminado el registro exitosamente');			
		redirect('admin/gestion/index/'.$id_cuenta);
		
	}
	public function guardar_pagos($idcuenta,$id=''){
		
		$tab = $this->tabs['pagos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('id_acuerdo_pago', 'Tipo de Abono', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fecha_pago', 'Fecha', 'trim|required|xss_clean');
		$this->form_validation->set_rules('monto_pagado','Monto','trim|required|xss_clean');
		$this->form_validation->set_rules('n_comprobante_interno', 'Comprobante', 'trim|xss_clean');
		$this->form_validation->set_rules('forma_pago', 'Forma de Pago', 'trim|xss_clean');
		
		
		if ($this->form_validation->run() == TRUE){
			
			$nodo = $this->nodo_m->get_by( array('activo'=>'S') );
			
			
			
			$fields_save = array();
			$fields_save['id_cuenta'] = $idcuenta;
			$fields_save['id_acuerdo_pago'] = $this->input->post('id_acuerdo_pago');
			$fields_save['monto_pagado'] = $this->input->post('monto_pagado');
			$fields_save['n_comprobante_interno'] = $this->input->post('n_comprobante_interno');
			$fields_save['fecha_pago'] = date('Y-m-d',strtotime($this->input->post('fecha_pago')));
			$fields_save['forma_pago'] = $this->input->post('forma_pago');
			if ($id!=''){
				$pagos = $this->cuentas_pagos_m->get_by(array('id'=>$id));
			}
			$this->cuentas_pagos_m->save_default($fields_save,$id);
			if ($this->input->post('monto_pagado')>0){
				if ($id==''){
					$this->db->query("UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new + (".$this->input->post('monto_pagado')."), fecha_ultimo_pago='".$fields_save['fecha_pago']."' WHERE id=".$idcuenta );
				} else {
					$this->db->query("UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new - (".$pagos->monto_pagado."), fecha_ultimo_pago='".$fields_save['fecha_pago']."' WHERE id=".$idcuenta );
					$this->db->query("UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new + (".$this->input->post('monto_pagado')."), fecha_ultimo_pago='".$fields_save['fecha_pago']."' WHERE id=".$idcuenta );
				}
			}
			if ($nodo->nombre=='swcobranza' && $id==''){
				$cuenta = $this->cuentas_model->get_by(array('id'=>$idcuenta));
				if (count($cuenta)==1){
					if ($cuenta->id_estado_cuenta!=5){
						$this->cuentas_m->save_default(array('id_estado_cuenta'=>9),$idcuenta);
					}
				}
			}
			$this->session->set_userdata('success_abono','El abono ha sido guardado exitosamente');			
			redirect('admin/gestion/index/'.$idcuenta);
		} else {
			
			$this->index($idcuenta,$tab);
		}
	}

    function guardar_intereses($id_cuenta){
        $tab = $this->tabs['pagos'];
        $this->_unset_sessions_success();
        $this->session->set_userdata('tab',$tab);
        if (1==1){

            $fields_save = array();
            $fields_save['intereses'] = $this->input->post('intereses');
            $this->cuentas_m->save_default($fields_save,$id_cuenta);
            redirect('admin/gestion/index/'.$id_cuenta);
        } else {
            $this->index($id_cuenta,$tab);
        }
    }
	public function edit_pagos($idcuenta,$id=''){
		
		//$this->output->enable_profiler(TRUE);
		
		$tab = $this->tabs['pagos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('id_acuerdo_pago', 'Tipo de Abono', 'trim|xss_clean');
		$this->form_validation->set_rules('fecha_pago', 'Fecha', 'trim|required|xss_clean');
		$this->form_validation->set_rules('monto_pagado','Monto','trim|xss_clean');
		$this->form_validation->set_rules('n_comprobante_interno', 'Comprobante', 'trim|xss_clean');
		$this->form_validation->set_rules('forma_pago', 'Forma de Pago', 'trim|xss_clean');
		
		
		if ($this->form_validation->run() == TRUE){
			
			$nodo = $this->nodo_m->get_by( array('activo'=>'S') );
			
			$fields_save = array();
			$fields_save['id_cuenta'] = $idcuenta;
			$fields_save['id_acuerdo_pago'] = $this->input->post('id_acuerdo_pago');
			$fields_save['monto_pagado'] = $this->input->post('monto_pagado');
			$fields_save['n_comprobante_interno'] = $this->input->post('n_comprobante_interno');
			$fields_save['fecha_pago'] = date('Y-m-d',strtotime($this->input->post('fecha_pago')));
			$fields_save['forma_pago'] = $this->input->post('forma_pago');
			/*if ($idcuenta!=''){
				$pagos = $this->cuentas_pagos_m->get_by(array('id'=>$id));
			}*/
			$this->cuentas_pagos_m->save_default($fields_save,$id);
			
			$this->session->set_userdata('success_abono','El abono ha sido editado exitosamente');			
			redirect('admin/gestion/index/'.$idcuenta);
		} else {
			
			$this->index($idcuenta,$tab);
		}
	}
	
	
	
	
	
	
	
	
	public function guardar_convenio($idcuenta){
		
		$tab = $this->tabs['pagos'];
		$this->_unset_sessions_success();
		$this->session->set_userdata('tab',$tab);
		$this->form_validation->set_rules('n_cuotas', 'Cuotas', 'trim|required|is_natural_no_zero|xss_clean');
		$this->form_validation->set_rules('n_cuotas_real', 'Cuotas', 'trim|xss_clean');
		$this->form_validation->set_rules('valor_cuota','Valor Cuota','trim|is_natural_no_zero|required|xss_clean');
		$this->form_validation->set_rules('valor_cuota_real', 'Valor Cuota', 'trim|xss_clean');
		$this->form_validation->set_rules('dia_vencimiento_cuota', 'Día Vencimiento', 'trim|is_natural_no_zero|required|xss_clean');
		$this->form_validation->set_rules('fecha_primer_pago', 'Forma de Pago', 'trim|required|xss_clean');
		
		if ($this->form_validation->run() == TRUE){
			$fields_save = array();
			$fields_save['n_cuotas'] = $this->input->post('n_cuotas');
			$fields_save['n_cuotas_real'] = $this->input->post('n_cuotas');
			if ($this->input->post('n_cuotas_real')!=''){
				$fields_save['n_cuotas_real'] = $this->input->post('n_cuotas_real');
			}
			$fields_save['valor_cuota'] = $this->input->post('valor_cuota');
			$fields_save['valor_cuota_real'] = $this->input->post('valor_cuota');
			if ($this->input->post('valor_cuota_real')!=''){
				$fields_save['valor_cuota_real'] = $this->input->post('valor_cuota_real');
			}
			$fields_save['fecha_primer_pago'] = date('Y-m-d',strtotime($this->input->post('fecha_primer_pago')));
			$fields_save['dia_vencimiento_cuota'] = $this->input->post('dia_vencimiento_cuota');
			$this->cuentas_m->save_default($fields_save,$idcuenta);
			
			$this->session->set_userdata('success_abono','El convenio ha sido guardado exitosamente');			
			redirect('admin/gestion/index/'.$idcuenta);
		} else {
			echo validation_errors();
			$this->index($idcuenta,$tab);
		}
	}
	
	public function informar_reg($id,$tipo,$idregistro){
		$this->data['id'] = $id;
		$this->data['idregistro'] = $idregistro;
		$this->load->view ( 'backend/templates/procurador/informar.php', $this->data );
	}
	public function loadform($id,$tipo,$idregistro){
		if ($tipo=='mail' || $tipo=='direccion' || $tipo=='bienes' || $tipo=='telefonos'){
			$estados = array('0'=>'Sin confirmación','1'=>'Vigente','2'=>'No Vigente');
			$this->data['estados'] = $estados;
		}
		if ($tipo=='mail'){
			$mail = $this->mail_m->get_by( array('id'=>$idregistro) );
			$this->data['mail'] = $mail;
		}
		if ($tipo=='direccion'){
			$comunas = array(''=>'Seleccionar')+$this->comunas_m->get_dropdown('');
			$direccion = $this->direccion_m->get_by( array('id'=>$idregistro) );
			$this->data['comunas'] = $comunas;
			$this->data['direccion'] = $direccion;
		}
		if ($tipo=='telefonos'){
			$tipos = array('0'=>'Tipo','1'=>'Particular','2'=>'Comercial','3'=>'Celular','4'=>'Otro');
			$telefono = $this->telefono_m->get_by( array('id'=>$idregistro) );
			$this->data['telefono'] = $telefono;
			$this->data['tipos'] = $tipos;
		}
		if ($tipo=='bienes'){
			$tipos_bienes = array('0'=>'Tipo','1'=>'Vehículo','2'=>'Inmueble');
			$bien = $this->bienes_m->get_by( array('id'=>$idregistro) );
			$this->data['tipos_bienes'] = $tipos_bienes;
			$this->data['bien'] = $bien;
		}
		if ($tipo=='historiales'){
			$historial = $this->cuentas_historial_m->get_by( array('id'=>$idregistro) );
			$this->data['historial'] = $historial;
		}
		if ($tipo=='etapas_juicio'){	
			//$this->output->enable_profiler(TRUE);
			$etapas_juicio = array(''=>'Seleccionar...')+$this->etapas_m->get_dropdown_etapas(array('s1.activo'=>'S'));
			
			$etapa_juicio_cuenta = $this->cuentas_etapas_m->get_by( array('id'=>$idregistro) );

			$this->data['etapas_juicio'] = $etapas_juicio;
			$this->data['etapa_juicio_cuenta'] = $etapa_juicio_cuenta;
			
		}
		if ($tipo=='pagares'){
			$tipos_productos = array(''=>'Seleccionar...')+$this->tipo_productos_m->dropdown('tipo');
			$pagare = $this->pagare_m->get_by( array('idpagare'=>$idregistro) );
			$this->data['tipos_productos'] = $tipos_productos;
			$this->data['pagare'] = $pagare;
		}
		
		if ($tipo=='gastos'){
			$diligencias = array();
			if ($this->input->post('item_gasto')!=''){
				$a=$this->diligencia_m->many_by(array('item_gasto'=>$this->input->post('item_gasto')));
			} else {
				$a=$this->diligencia_m->get_all();
			}
			$diligencias[0]='Seleccionar';
			foreach ($a as $obj) {$diligencias[$obj->id] = $obj->nombre;}	
			$receptores = array(''=>'Seleccionar...')+$this->receptor_m->dropdown('nombre');
			$gasto = $this->cuentas_gastos_m->get_by( array('id'=>$idregistro) );
			
			$estado_pago = array(''=>'Seleccionar','1'=>'Pagado','2'=>'Pendiente');
			
			$this->data['estado_pago'] = $estado_pago;
			$this->data['diligencias'] = $diligencias;
			$this->data['gasto'] = $gasto;
			$this->data['receptores'] = $receptores;
			
		}
		if ($tipo=='pagos'){
			$formas_pago = array( ''=>'Seleccionar','TF'=>'Transferencia','DP'=>'Depósito','CH'=>'Cheque','EF'=>'Efectivo' );
			$acuerdos_pago = array( ''=>'Seleccionar','1'=>'Abono','2'=>'Acuerdo' );
			$pago = $this->cuentas_pagos_m->get_by( array('id'=>$idregistro) );
			$this->data['formas_pago'] = $formas_pago;
			$this->data['acuerdos_pago'] = $acuerdos_pago;
			$this->data['pago'] = $pago;
		}
		
		$this->data['id'] = $id;
		$this->data['idregistro'] = $idregistro;
		$this->load->view ( 'backend/templates/gestion/gestion/'.$tipo.'_form', $this->data );
	}
	function actualizar_estado($id,$tipo){
		$fields_save = array();
		$save = false;
		$fields_save['estado'] = $this->input->post('estado');
		if ($tipo=='direccion'){
			$save = $this->direccion_m->save_default($fields_save,$id);
		}
		if ($tipo=='telefono'){
			$save = $this->telefono_m->save_default($fields_save,$id);
		}
		if ($tipo=='bien'){
			$save = $this->bienes_m->save_default($fields_save,$id);
		}
		if ($tipo=='mail'){
			$save = $this->mail_m->save_default($fields_save,$id);
		}
		if ($save){
			echo 'Guardado';//'<img src="'.base_url().'img/ico-cheked.png">';
		}
	}



        function actualizar_tipo_direccion($id,$tipo){
        $fields_save = array();
       $save = false;

           $fields_save['ipo'] = $this->input->post('tipo');

            if ($tipo=='dir') {

                // echo $this->input->post('tipo').'sdfdf';
               // die();

        $save = $this->direccion_m->save_default($fields_save,$id);

        }
            if ($save){
                echo 'Guardado';//'<img src="'.base_url().'img/ico-cheked.png">';
            }

    }


	
	function historial_etapas(){
		//$this->output->enable_profiler(TRUE);
		$cols[] = 'e.id AS id';
		$cols[] = 'e.etapa AS etapa';
		$cols[] = 'YEAR(ce.fecha_etapa) AS y';
		$cols[] = 'MONTH(ce.fecha_etapa) AS m';
		$cols[] = 'COUNT(ce.id) AS cuantos';
		$where = array();
		$where['ce.activo'] = 'S';
		$where['ce.id_etapa >'] = 0;
		$group_by = 'e.id,YEAR(ce.fecha_etapa),MONTH(ce.fecha_etapa)';
		$order_by = 'etapa ASC';
		$etapas = $this->cuentas_etapas_m->get_cuentas_etapas_resumen($cols,$where,$group_by,$order_by);

		$result = array();
		foreach ($etapas as $key=>$val){
			$result[$val->id]['etapa'] = $val->etapa;
			$result[$val->id]['historial'][$val->y.'-'.$val->m]['cuantos'] = $val->cuantos; 
		}
		
		$this->data['etapas'] = $result;
		$this->data['plantilla'] = 'gestion/historial_etapas';
		$this->load->view ( 'backend/index', $this->data );
	}
	function proyeccion_convenios(){

        //$this->output->enable_profiler(TRUE);
		$cols = array();
		$where = array();
		$where['c.dia_vencimiento_cuota >'] = 0;
		$where['c.id_estado_cuenta'] = 5;
		$group_by = '';
		$order_by = 'c.fecha_primer_pago ASC';
		$cuentas = $this->cuentas_m->get_cuentas_listado($cols,$where,$group_by,$order_by);
		
		$cols[] = 'c.id AS id';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'sec.estado AS estado_cuenta';
		$cols[] = 'c.monto_deuda AS monto_deuda';
		$cols[] = 'YEAR(cp.fecha_pago) AS y';
		$cols[] = 'MONTH(cp.fecha_pago) AS m';
		$cols[] = 'cp.fecha_pago AS fecha_pago';
		$cols[] = 'SUM(cp.monto_pagado) AS monto_pagado';
		$where = array('YEAR(cp.fecha_pago)'=>date('Y'),'MONTH(cp.fecha_pago)'=>date('n'));
		//$where['c.dia_vencimiento_cuota >'] = 0;
		$where['cp.activo'] = 'S';



		$group_by = 'cp.id_cuenta';
		$order_by = 'cp.fecha_pago ASC';
		$cuentas_pagos = $this->cuentas_pagos_m->get_pagos($cols,$where,$group_by,$order_by);
		
		$pagos = array();
		foreach ($cuentas_pagos as $key=>$val){
			$pagos[$val->id]['monto_pagado'] = $val->monto_pagado; 
		}
		
		$this->data['pagos'] = $pagos;
		$this->data['cuentas'] = $cuentas;
		$this->data['plantilla'] = 'gestion/proyeccion_convenios';
		$this->load->view ( 'backend/index', $this->data );
	}
	function historial_pagos(){
		//$this->output->enable_profiler(TRUE);
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'sec.estado AS estado_cuenta';
		$cols[] = 'c.monto_deuda AS monto_deuda';
		$cols[] = 'YEAR(cp.fecha_pago) AS y';
		$cols[] = 'MONTH(cp.fecha_pago) AS m';
		$cols[] = 'cp.fecha_pago AS fecha_pago';
		$cols[] = 'SUM(cp.monto_pagado) AS monto_pagado';
		$where = array();
		//$where['c.dia_vencimiento_cuota >'] = 0;
		$where['cp.activo'] = 'S';
		$group_by = 'cp.id_cuenta,YEAR(cp.fecha_pago),MONTH(cp.fecha_pago)';
		$order_by = 'cp.fecha_pago ASC';
		$cuentas = $this->cuentas_pagos_m->get_pagos($cols,$where,$group_by,$order_by);
		
		foreach ($cuentas as $key=>$val){
			$result[$val->rut]['monto_deuda'] = $val->monto_deuda;
			$result[$val->rut]['estado_cuenta'] = $val->estado_cuenta;
			//echo $val->y.'-'.$val->m.'<br>';
			$result[$val->rut]['pagos'][$val->y.'-'.$val->m]['monto_pagado'] = $val->monto_pagado; 
		}
		
		$this->data['cuentas'] = $result;
		$this->data['plantilla'] = 'gestion/proyeccion_pagos';
		$this->load->view ( 'backend/index', $this->data );
	}
	public function _alert_email($to,$cc,$subject='',$output=null,$debug=false){
		$nodo = $this->nodo_m->get_by( array('activo'=>'S') );
		$this->load->library('email');
		if ($nodo->nombre=='swcobranza'){
			$this->email->from('noreply@hmycia.cl', 'HMYCIA');
		} elseif ($nodo->nombre=='fullpay'){
			$this->email->from('noreply@fullpay.cl', 'FULLPAY');
		}
		$this->email->to($to);
		$this->email->cc($cc);
		$this->email->subject($subject);
		$this->email->message($output);
		$this->email->send();
		if ($debug){
			echo $this->email->print_debugger();
		}
	}


    public function fecha_termino_suspension()
    {

        //echo 'ghdfgdf';
        //die();


        //$fecha_termino_suspension = '2016-12-31';

        //$this->input->post('fecha_termino_suspension');
        $result = array();

        $cols = array();
        $cols[] = 'c.id AS id';
        $cols[] = 'c.activo AS activo';
        $cols[] = 'usu.rut AS rut';
        $cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
        $cols[] = 'DATEDIFF(NOW(),`c`.`fecha_termino_suspension`) AS `termino_suspension` ';
        $this->db->where("DATEDIFF(NOW(),`c`.`fecha_termino_suspension`) = '0'");
        $this->db->where("c.activo = 'S'");

        $this->db->select($cols);
        $this->db->join('0_usuario usu', 'usu.id = c.id_usuario');
        $this->db->from("0_cuentas c");


        // $this->db->join("2_cuentas_etapas ce","ce.id_etapa =c.id_etapa AND c.id=ce.id_cuenta AND ce.id_etapa<>0 AND ce.activo='S'","left");
        //$where['c.activo'] = 'S';
        $query = $this->db->get();
        $result = $query->result();
        $output = '';
            if (count($result) > 0) {
        foreach ($result as $key => $val) {
            $fields_save = array('id_estado_cuenta' => 1, 'fecha_termino_suspension' => 'NULL');
            $this->cuentas_m->save_default($fields_save, $val->id);
            $output = 'la cuenta del rut ';
        }
             }
        $debug = false;
        if ($output!=''){
            $subject = 'Alertas de deudores sin convenios que han abonado hace más de 60 días';
            $to = 'hedy@mattheiycia.cl,psalamanca@mattheiycia.cl,avenegas@mattheiycia.cl';
            $cc = 'ricardo.carrasco.p@gmail.com';
            $this->_alert_email($to,$cc,$subject,$output,$debug);
        }
    }



}

?>