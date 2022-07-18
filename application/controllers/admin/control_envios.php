<?php
class Control_envios extends CI_Controller {
	public $data = array();
	public $activo = 'S';
	protected $show_tpl = TRUE;
	public function Control_envios() { $this->__construct(); }
	function __construct() {
		parent::__construct();
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
 		//$this->db_cae = $this->load->database('cae', true);

		$this->load->helper ( 'date_html_helper' );
		
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'cuentas_m' ); $this->load->model ( 'cuentas_etapas_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'cinformadas_m' );		
		$this->load->model ( 'tipo_productos_m' );
		$this->load->model ( 'administradores_m' );
		$this->load->model ( 'cuentas_juzgados_m' );	
		$this->load->model ( 'estados_cuenta_m' );
		$this->load->model ( 'cuentas_pagos_m');
		$this->load->model ( 'comprobantes_m');
		$this->load->model ( 'cuentas_gastos_m');
		$this->load->model ( 'cuentas_historial_m');
		$this->load->model ( 'etapas_m' );	
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'distrito_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'receptor_m');
		$this->load->model ( 'documento_m' );
		$this->load->model ( 'pagare_m' );
		$this->load->model ( 'diligencia_m' );
		$this->load->model ( 'gasto_regla_m' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'bienes_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'receptor_m' );
		$this->load->model ( 'documento_plantilla_m' );
		$this->load->model ( 'log_etapas_m' );
		$this->load->model ( 'mail_m' );
		$this->load->model ( 'comunas_m');
		$this->load->model ( 'cuentas_contratos_m');
		$this->load->model ( 'movimiento_cuenta');		
		$this->load->model ( 'abogados_m');
		$this->load->model ( 'marcas_especiales_m');
		$this->load->model ( 'control_envios_m');
		$this->load->model ( 'configuracion_m');
		$this->load->model ( 'jurisdiccion_m');
		$this->load->model ( 'procurador_m');
		/*seters*/
		$this->data['current'] = 'control_envios';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'cuentas/';
		$this->data['lists'] = array();
		
		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
		$this->data['forma_pagos'] = array(''=>'Forma Pago','TF'=>'Transferencia','DP'=>'DepÃ³sito','CH'=>'Cheque','EF'=>'Efectivo');
		
		
		
		$c=$this->estados_cuenta_m->order_by('estado','ASC')->get_all(); 
		$this->data['estados'][-1]='Seleccionar..';
	 	foreach ($c as $obj) {$this->data['estados'][$obj->id] = $obj->estado;}
	 	
		$this->data['nodo'] = $this->nodo = $this->nodo_m->get_by( array('activo'=>'S') );
		
		$r=$this->cuentas_m->get_many_by(array('activo'=>'S','rol !='=>''));
	    $this->data['roles']['']='Seleccionar..';
	 	foreach ($r as $obj) {$this->data['roles'][$obj->rol] = $obj->rol;}
		
	 	$rep=$this->receptor_m->order_by('nombre','ASC')->get_all(); 
		$this->data['receptores'][-1]='Seleccionar..';
	 	foreach ($rep as $obj) {$this->data['receptores'][$obj->id] = $obj->nombre;}
	 	
	 	$v=$this->comunas_m->order_by("nombre","ASC")->get_many_by(array('activo'=>'S','nombre !='=>''));
	    $this->data['comunas']['']='Seleccionar..';
	 	foreach ($v as $obj) {$this->data['comunas'][$obj->nombre] = $obj->nombre;}
		
	 	
	 	$t=$this->tribunales_m->order_by("tribunal","ASC")->get_many_by(array('activo'=>'S','padre ='=>'0'));
	    $this->data['tribunales']['']='Seleccionar..';
	 	foreach ($t as $obj) {$this->data['tribunales'][$obj->id] = $obj->tribunal;}
	 	
	 	
		$tc=$this->tribunales_m->order_by("tribunal","ASC")->get_many_by(array('activo'=>'S','padre ='=>'0'));
	    $this->data['tribunal_comuna']['']='Seleccionar..';
	 	foreach ($tc as $obj) {$this->data['tribunal_comuna'][$obj->id] = $obj->tribunal;}
	 	
		//$this->output->enable_profiler(TRUE);
		//echo $this->session->userdata('logged_in').'cuentas';
	}
	
	function editar(){
		$id = $this->input->post('id_config');
		
		$config = $this->configuracion_m->get_by(array("id" => $id));
		
		if($config->sistema){
			$this->sistema = "";
			if($config->sistema == 'sup')
				$this->sistema = $this->load->database("superir", TRUE);
			elseif($config->sistema == 'prop' || $config->sistema == 'cat')
				$this->sistema = $this->load->database("cat", TRUE);
			elseif($config->sistema == 'cae')
				$this->sistema = $this->load->database("cae", TRUE);
			$arr_mandantes = $this->sistema->select('ma.codigo_mandante as mandante')
								  ->from("0_mandantes ma")
								  ->where(array('activo'=>'S'))
								  ->get()
								  ->result();

			//print_r($arr_mandantes); die;
			foreach ($arr_mandantes as $key => $value) {
				if($value->mandante != '' && !in_array($value->mandante, $mandantes)){
					if($sistema == 'prop'){
						if($value->mandante != 'CAT' && $value->mandante != 'TANNER')
							$mandantes[] = $value->mandante;
					}
					elseif($sistema == 'cat'){
						if($value->mandante == 'CAT')
							$mandantes[] = $value->mandante;
					}
					else
						$mandantes[] = $value->mandante;
				}
			}
			
			if($config->sistema != 'na'){
				if($config->tipo_causa == 1){
					$arr_estados = $this->sistema->select('es.estado')
										->from("s_estado_cuenta es")
										->where('activo', 'S')
										->get()
										->result();

					//print_r($arr_estados); die;

					foreach ($arr_estados as $key => $value) {
						if($value->estado != '' && !in_array($value->estado, $estados)){
							$estados[] = $value->estado;
						}
					}
				}
				else{
					$arr_estados = $this->sistema->select('es.estado')
										->from("estado_juicio es")
										->get()
										->result();

					//print_r($arr_estados); die;

					foreach ($arr_estados as $key => $value) {
						if($value->estado != '' && !in_array($value->estado, $estados)){
							$estados[] = $value->estado;
						}
					}
				}
				
				$results = $this->sistema->select('id, nombre')
							         ->from("s_comunas co")
							         ->where('activo', 'S')
							         ->get()
							         ->result();

				//print_r($arr_mandantes); die;
				foreach ($results as $key => $value) {
					$comunas[$value->id] = $value->nombre;
				}
				
				if(!$config->tipo_juicio || $config->tipo_juicio == 1){
					$arr_juris = $this->sistema->select('ju.id, ju.jurisdiccion')
							->from("jurisdiccion ju")
							->where('activo', 'S')
							->get()
							->result();
							
					foreach ($arr_juris as $key => $value) {
						$jurisdicciones[$value->id] = $value->jurisdiccion;
					}
				}
				else{
					$arr_juris = $this->sistema->select('ju.id, ju.jurisdiccion')
							->from("jurisdiccion_complementaria ju")
							->where('activo', 'S')
							->get()
							->result();
							
					foreach ($arr_juris as $key => $value) {
						$jurisdicciones[$value->id] = $value->jurisdiccion;
					}
				}
				
				if($config->tipo_juicio){
					$letras_arr = $this->sistema->select('tl.id, tl.tipo')
										->from("tipo_letra tl")
										->where('id_tipo_causa', $config->tipo_juicio)
										->get()
										->result();
										
					foreach ($letras_arr as $key => $value) {
						$letras[$value->id] = $value->tipo;
					}
				}
				else{
					$letras['C'] = 'C';
					$letras['D'] = 'D';
				}
			}
		} 
		
		echo json_encode(array('result' => array('config' => $config, 'mandantes' => $mandantes, 'estados' => $estados, 'comunas' => $comunas, 'jurisdicciones' => $jurisdicciones, 'letras' => $letras)));
		die();
	}
	
	public function cargarTribunales() {
		$this->sistema = '';
		$tribunales = array();
        $idtc = $this->input->post('idtc');
        $id_comuna = $this->input->post('id_comuna');
        $id_jurisdiccion = $this->input->post('id_jurisdiccion');
		$sistema = $this->input->post('sistema');

		if($sistema == 'sup')
			$this->sistema = $this->load->database("superir", TRUE);
		if($sistema == 'prop' || $sistema == 'cat')
			$this->sistema = $this->load->database("cat", TRUE);
		elseif($sistema == 'cae')
			$this->sistema = $this->load->database("cae", TRUE);
        
        if($this->sistema){
        	if($id_comuna)
	        	$results = $this->sistema->select('id, tribunal')
								         ->from("s_tribunales")
								         ->where('comuna_id', $id_comuna) //Todavia esto no está
								         ->get()
								         ->result();
			else{
				if($idtc == 1)
					$results = $this->sistema->select('id, tribunal')
											 ->from("s_tribunales")
											 ->where('jurisdiccion_id', $id_jurisdiccion)
											 ->get()
											 ->result();
				else
					$results = $this->sistema->select('id, tribunal')
											 ->from("tribunales_complementarios")
											 ->where('jurisdiccion_id', $id_jurisdiccion)
											 ->get()
											 ->result();
			}
			
			foreach ($results as $key => $value) {
				$tribunales[$key]['id'] = $value->id;
				$tribunales[$key]['name'] = $value->tribunal;
			}
        }

        echo json_encode(array('result' => array('tribunales' => $tribunales)));
		die();
    }

	function gen($action,$id){$this->index($action,$id);}

	function index($action='', $id='') {
		$view='list_control_envios'; 
		$config['uri_segment'] = '4';
		$this->data['current_pag'] = $this->uri->segment(4);
		
		$this->data['plantilla'].= $view;	
		$this->load->helper ( 'url' );	
		if ($action=='actualizar'){
			$this->cuentas_m->update($id,$_POST);
			$this->show_tpl = FALSE;
			$config['uri_segment'] = '6'; 
			$this->data['current_pag'] = $this->uri->segment(6);
		}
		if ($view=='list_control_envios'){
			/*where*/
			//$this->output->enable_profiler(TRUE);
			$where=array();
			$like = array();

	    	$order_by = '';
			
			if($_REQUEST['sistema_p']){
				$this->sistema = '';
				$sistema = $_REQUEST['sistema_p'];
				$where['sistema'] = $sistema;
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'sistema_p='.$_REQUEST['sistema_p'];

				if($sistema == 'sup')
					$this->sistema = $this->load->database("superir", TRUE);
				if($sistema == 'prop' || $sistema == 'cat')
					$this->sistema = $this->load->database("cat", TRUE);
				elseif($sistema == 'cae')
					$this->sistema = $this->load->database("cae", TRUE);

				$procuradores = array();
				$results = $this->sistema->select('id, nombres, apellidos')
										 ->from("0_administradores adm")
										 ->where('activo', 'S')
										 ->get()
										 ->result();

				//print_r($arr_mandantes); die;
				foreach ($results as $key => $value) {
					$procuradores[$key]->id = $value->id;
					$procuradores[$key]->name = $value->nombres.' '.$value->apellidos;
				}

				$this->data['procuradores'] = $procuradores;
			}

			if($_REQUEST['procurador_p']){
				$where['id_procurador'] = $_REQUEST['procurador_p'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'procurador_p='.$_REQUEST['procurador_p'];
			}
			
			if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}

			$this->db->where('activo', 'S');
			//$this->db->order_by('tribunal', 'ASC');
			$arr = $this->distrito_m->get_all();
			$distrito[''] = 'Seleccionar';
			foreach($arr as $key=>$val){
				$distrito[$val->id]= $val->jurisdiccion;
			}
			$this->data['distrito']= $distrito;
			
			$arr = $this->marcas_especiales_m->get_all();
			foreach($arr as $key=>$val){
				$gestores[$val->idMarca]= $val->marca;
			}
			$this->data['gestores']= $gestores;
			
			$arr = $this->tribunales_m->get_all();

			$tribunales[''] = 'Seleccionar';
			foreach($arr as $key=>$val){
				$tribunales[$val->id]= $val->tribunal;
			}
			$this->data['tribunales']= $tribunales;
				
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/control_envios/index/';

			$this->db->where($where);

			$this->db->group_by("id");
			$query = $this->db->get('configuracion cta');

			$query = $query->result();  
			
			//echo $this->db->last_query();
			//print_r($query);

			$config['total_rows'] = count($query);
	    	$config['per_page'] = '30';
	    	
	    	$this->pagination->initialize($config);
		
			$this->db->start_cache();
			$this->db->stop_cache();
			
			if($where)
				$this->db->where($where);
			$query = $this->db->get('configuracion cta', $config['per_page'], $this->data['current_pag']);

			$results = $query->result();  //print_r($query1);

			$configuraciones = array();
			foreach ($results as $key => $value) {
				$this->sistema = '';
				$sistema = $value->sistema;
				if($sistema == 'sup'){
					$configuraciones[$key]->sistema = 'SUPERIR';
				$this->sistema = $this->load->database("superir", TRUE);
				}
				if($sistema == 'prop' || $sistema == 'cat'){
					$this->sistema = $this->load->database("cat", TRUE);
					$configuraciones[$key]->sistema = 'CAT';
				}
				if($sistema == 'cae'){
					$this->sistema = $this->load->database("cae", TRUE);
					$configuraciones[$key]->sistema = 'CAE';
				}
				if($sistema == 'na')
					$configuraciones[$key]->sistema = 'N/A';
				/*if($sistema == 'cat-na')
					$configuraciones[$key]->sistema = 'CAT-NA';
				if($sistema == 'sup-na')
					$configuraciones[$key]->sistema = 'SUPERIR-NA';*/
				
				$procurador = $this->procurador_m->get_by(array('id' => $value->id_procurador));
				$designado = $this->procurador_m->get_by(array('id' => $value->id_designado));
				//print_r($query); die;
				//echo $procurador->nombre.' '.$procurador->apellido; die;
				$configuraciones[$key]->id = $value->id;
				$configuraciones[$key]->tipo_causa = $value->tipo_causa;
				$configuraciones[$key]->procurador = $procurador->nombre.' '.$procurador->apellido;
				$configuraciones[$key]->designado = $designado->nombre.' '.$designado->apellido;
				$configuraciones[$key]->mandante = ($value->mandante) ? $value->mandante : (($value->sistema=='na') ? 'N/A' : (($value->sistema=='apel') ? 'APELACIONES' : ''));
				$configuraciones[$key]->estado = ($value->estado) ? $value->estado : '';
				$configuraciones[$key]->tipoc = ($value->tipoc) ? $value->tipoc : '';
				$configuraciones[$key]->gestor = ($value->id_gestor) ? $gestores[$value->id_gestor] : '';
				$configuraciones[$key]->grupo = ($value->grupo) ? $value->grupo : '';
				
				if($value->tipo_causa == 1 || $value->tipo_juicio == 1){
					if($value->id_distrito){
						$distrito = $this->distrito_m->get_by(array('id' => $value->id_distrito));
						$configuraciones[$key]->distrito = $distrito->jurisdiccion;
					}
				}
				else{
					if($value->id_distrito){
						$res = $this->sistema->select('jurisdiccion')
										 ->from("jurisdiccion_complementaria jur")
										 ->where('id', $value->id_distrito)
										 ->get()
										 ->result();
						$configuraciones[$key]->distrito = $res[0]->jurisdiccion;
					}
				}
				if($value->tipo_causa == 1){
					if($value->id_juzgado){
						$res = $this->sistema->select('tribunal')
											 ->from("s_tribunales tri")
											 ->where('id', $value->id_juzgado)
											 ->get()
											 ->result();
						$configuraciones[$key]->tribunal = $res[0]->tribunal;
					}
				}
				else{
					$res = $this->sistema->select('tribunal')
										 ->from("tribunales_complementarios tri")
										 ->where('id', $value->id_juzgado)
										 ->get()
										 ->result();
					$configuraciones[$key]->tribunal = $res[0]->tribunal;
					
					if($value->tipoc){
						$res = $this->sistema->select('tl.tipo')
							 		->from("tipo_letra tl")
							 		->where('id', $value->tipoc)
							 		->get()
							 		->result();
						$configuraciones[$key]->tipoc = $res[0]->tipo;
					}
				}
				if($value->id_comuna){
					$res = $this->sistema->select('nombre as comuna')
										 ->from("s_comunas co")
										 ->where('id', $value->id_comuna)
										 ->get()
										 ->result();
					$configuraciones[$key]->comuna = $res[0]->comuna;
				}
				$configuraciones[$key]->juzgado = $value->juzgado;
			}
			
			$this->data['lists'] = $configuraciones; //print_r($this->data['lists']); die;
			
			$this->data['total'] = $config['total_rows'];

			################ FILTROS CONTROL ENVIOS ########################
			$a=$this->procurador_m->get_many_by('activo', 'S');
			$this->data['procurador'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['procurador'][$obj->id] = $obj->nombre.' '.$obj->apellido;}

			$this->data['distritos'] = array();
			$this->data['distritos'] = $this->distrito_m->order_by("id","ASC")->get_all(); //print_r($a); die;
			
			###################################################################
						
			if (!$this->show_tpl){ 
				$this->data['plantilla'] = 'cuentas/list_tabla_control_envios'; 
				$this->load->view('backend/templates/'.$this->data['plantilla'], $this->data);
			}
		}
			
		if ($this->show_tpl){
			$this->load->view('backend/index', $this->data);
		}

	}

	public function eliminar_configuracion(){
		$idconfig = $this->input->post('id_config');
		$this->configuracion_m->delete_by( array('id'=>$idconfig));
		echo json_encode(array('result' => 1));
		die;
	}
	
	public function asignar(){
		$results = array();
		$sistema = $this->input->post('sistema');
		$id_asigna = $this->input->post('id_asigna');
		$array_ids = $this->input->post('id_configs');
		

		$this->sistema = '';

		if($sistema == 'sup')
			$this->sistema = $this->load->database("superir", TRUE);
		if($sistema == 'prop' || $sistema == 'cat')
			$this->sistema = $this->load->database("cat", TRUE);
		elseif($sistema == 'cae')
			$this->sistema = $this->load->database("cae", TRUE);

		$arr = explode(',', $array_ids);
		//print_r($arr); die;
		foreach ($arr as $value){
			if($value){
				$fields_save = array();
				$fields_save['id_procurador'] = $id_asigna;
				$this->configuracion_m->update($value, $fields_save, TRUE, TRUE);
			}
		}

		//include_once("php_mailer.php");
		//Php_mailer::enviar_correo($results);
		echo json_encode(array('result' => 1));
		die();
	}
	
	public function cargarSelects(){
		$this->sistema = '';
		$procuradores = $jurisdicciones = $comunas = $estados = $etapas = $mandantes = array();
		$sistema = $this->input->post('sistema');
		if($sistema == 'sup')
			$this->sistema = $this->load->database("superir", TRUE);
		if($sistema == 'prop' || $sistema == 'cat')
			$this->sistema = $this->load->database("cat", TRUE);
		elseif($sistema == 'cae')
			$this->sistema = $this->load->database("cae", TRUE);
		
		if($this->sistema){
			$procuradores = $jurisdicciones = $comunas = array();
			$results = $this->sistema->select('id, nombres, apellidos')
									 ->from("0_administradores adm")
									 ->where('activo', 'S')
									 ->order_by('nombres', 'asc')
									 ->get()
									 ->result();

			//print_r($arr_mandantes); die;
			foreach ($results as $key => $value) {
				$procuradores[$key]['id'] = $value->id;
				$procuradores[$key]['name'] = $value->nombres.' '.$value->apellidos;
			}

			$results = $this->sistema->select('id, jurisdiccion')
							         ->from("jurisdiccion jur")
							         ->where('activo', 'S')
							         ->get()
							         ->result();

			//print_r($arr_mandantes); die;
			foreach ($results as $key => $value) {
				$jurisdicciones[$key]['id'] = $value->id;
				$jurisdicciones[$key]['name'] = $value->jurisdiccion;
			}

			$results = $this->sistema->select('id, nombre')
							         ->from("s_comunas co")
							         ->where('activo', 'S')
							         ->get()
							         ->result();

			//print_r($arr_mandantes); die;
			foreach ($results as $key => $value) {
				$comunas[$key]['id'] = $value->id;
				$comunas[$key]['name'] = $value->nombre;
			}

			$arr_estados = $this->sistema->select('es.id, es.estado')
								 		 ->from("s_estado_cuenta es")
								 		 ->where('activo', 'S')
								 		 ->get()
								 		 ->result();

			//print_r($arr_estados); die;

 		    foreach ($arr_estados as $key => $value) {
				if($value->estado != '' && !in_array($value->estado, $estados)){
					$estados[$value->id] = $value->estado;
				}
			}

			$arr_etapas = $this->sistema->select('et.id, et.etapa')
							 		->from("s_etapas et")
							 		->where('activo', 'S')
							 		->get()
							 		->result();

			//print_r($arr_estados); die;

 		    foreach ($arr_etapas as $key => $value) {
				if($value->etapa != '' && !in_array($value->etapa, $etapas)){
					$etapas[$value->id] = $value->etapa;
				}
			}
			
			$arr_mandantes = $this->sistema->select('ma.codigo_mandante as mandante')
								  ->from("0_mandantes ma")
								  ->where(array('activo'=>'S'))
								  ->get()
								  ->result();

			//print_r($arr_mandantes); die;
			foreach ($arr_mandantes as $key => $value) {
				if($value->mandante != '' && !in_array($value->mandante, $mandantes)){
					if($sistema == 'prop'){
						if($value->mandante != 'CAT' && $value->mandante != 'TANNER')
							$mandantes[] = $value->mandante;
					}
					elseif($sistema == 'cat'){
						if($value->mandante == 'CAT')
							$mandantes[] = $value->mandante;
					}
					else
						$mandantes[] = $value->mandante;
				}
			}
		}

		echo json_encode(array('result' => array('procurador' => $procuradores, 
												 'jurisdiccion' => $jurisdicciones, 
												 'comuna' => $comunas, 
												 'estados' => $estados, 
												 'mandantes' => $mandantes, 
												 'etapas' => $etapas)));
		die();
	}

	public function fillCiudades() {
        $idEstado = $this->input->post('idEstado');
        
        if($idEstado){
            $this->load->model('distrito_m');
            $ciudades = $this->distrito_m->getCiudades($idEstado);
            echo '<option value="0">--Seleccionar--</option>';
            foreach($ciudades as $fila){
                echo '<option value="'. $fila->id .'">'. $fila->tribunal .'</option>';
            }
        }  else {
            echo '<option value="0">--Seleccionar--</option>';
        }
    }

##########################################################################################
##########################################################################################

    function guardar_configuracion($id=''){

		$id = $this->input->post('id_config');
	    $tab = $this->tabs['datos_cuenta'];	  
		$this->session->set_userdata('tab', $tab);
		//$this->form_validation->set_rules('id_distritonew', 'Distrito', 'trim|required|xss_clean');
		//$this->form_validation->set_rules('id_tribunalnew', 'Tribunal', 'trim|required|xss_clean');
		//print_r($_POST); die("ddd");	
		if(($this->input->post('procurador') || $this->input->post('designado')) && ($this->input->post('sistema') == 'na' || $this->input->post('sistema') == 'apel' || $this->input->post('mandante') || $this->input->post('estado') || $this->input->post('id_distritonew') || $this->input->post('id_comuna') || $this->input->post('tipoproc')==2 || $this->input->post('tipojui'))){
			if (isset($_POST['tribunales']) && $_POST['tribunales'] != 'null'){
				if($id) $this->configuracion_m->delete_by(array("id" => $id));
				foreach ($_POST['tribunales'] as $key => $value){
					$fields_save = array();
					$fields_save['id_juzgado'] = $value; // Tribunal
					$fields_save['sistema'] = $this->input->post('sistema');
					$fields_save['mandante'] = $this->input->post('mandante');
					$fields_save['id_procurador'] = $this->input->post('procurador');
					$fields_save['id_designado'] = $this->input->post('designado');
					$fields_save['estado'] = $this->input->post('estado');
					$fields_save['id_distrito'] = $this->input->post('id_distritonew');
					$fields_save['id_comuna'] = $this->input->post('id_comuna');
					$fields_save['tipo_juicio'] = $this->input->post('tipojui');
					$fields_save['tipo_causa'] = $this->input->post('tipoproc');
					$fields_save['tipoc'] = $this->input->post('tipoc');
					$fields_save['id_gestor'] = $this->input->post('gestor');
					$fields_save['grupo'] = $this->input->post('grupo') ? $this->input->post('grupo') : 1;
					//$cuenta_etapa = $this->configuracion_m->save_default($fields_save, $id);
					$this->configuracion_m->insert($fields_save, false, true);
				}
			}
			else{
				$fields_save = array();
				$fields_save['sistema'] = $this->input->post('sistema');
				$fields_save['mandante'] = $this->input->post('mandante');
				$fields_save['id_procurador'] = $this->input->post('procurador');
				$fields_save['id_designado'] = $this->input->post('designado');
				$fields_save['estado'] = $this->input->post('estado');
				$fields_save['id_distrito'] = $this->input->post('id_distritonew');
				$fields_save['id_comuna'] = $this->input->post('id_comuna');
				$fields_save['tipoc'] = $this->input->post('tipoc');
				$fields_save['tipo_juicio'] = $this->input->post('tipojui');
				$fields_save['tipo_causa'] = $this->input->post('tipoproc');
				$fields_save['id_gestor'] = $this->input->post('gestor');
				$fields_save['grupo'] = $this->input->post('grupo') ? $this->input->post('grupo') : 1;
				if($id)
					$this->configuracion_m->update($id,$fields_save, false, true);
				else
					$this->configuracion_m->insert($fields_save, false, true);
			}
		}
		redirect('admin/control_envios/index/'.$id_cuenta);
	} 	
}
?>