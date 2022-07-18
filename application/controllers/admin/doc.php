<?php
class Doc extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
	
	var $Void = "";
	var $SP = " ";
	var $Dot = ".";
	var $Zero = "0";
	var $Neg = "Menos";
	
	public function Doc() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'form_validation' );
		$this->load->helper('form');
		$this->load->library ( 'session' );
		//$this->output->enable_profiler(TRUE);
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
	
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'documento_m' );
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'estados_cuenta_m' );
		$this->load->model ( 'bienes_m' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'etapas_m' );
		$this->load->model ( 'cuentas_etapas_m' );
		$this->load->model ( 'pagare_m' );
		$this->load->model ( 'tipo_productos_m' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'documento_plantilla_m' );
		
		$this->load->model ( 'vehiculos_m' );
		$this->load->model ( 'personal_m' );
		$this->load->model ( 'inmueble_m' );
		
		//$this->output->enable_profiler(TRUE);
		/*seters*/
		$this->data['current'] = 'usuarios';
		$this->data['plantilla'] = 'usuarios/';
		$this->data['lists'] = array();
		//error_reporting(E_ALL);
		date_default_timezone_set("America/Santiago");
		
		/*seters*/
		$this->data['current'] = 'doc';
		$this->data['plantilla'] = 'doc/';
		$this->data['lists'] = array();
		$this->data['current_pag'] = '';
		$this->data['exito'] = '';
		//ALTER TABLE `0_cuentas` ADD `exorto` INT( 1 ) NULL AFTER `posicion` 
		//ALTER TABLE `0_cuentas` ADD `tipo_demanda` INT( 1 ) NULL AFTER `exorto` 
		
		$this->data['nodo'] = $this->nodo = $this->nodo_m->get_by( array('activo'=>'S') );
		
		$this->db->order_by('etapa ASC');
		$documentos_etapas = array(''=>'Seleccionar etapa')+$this->etapas_m->dropdown('etapa');
		//$this->output->enable_profiler(TRUE);
		//print_r($documentos_etapas);
		$this->data['documentos_etapas'] = $documentos_etapas;
	}

	
	public function index(){
		
		//$this->output->enable_profiler(TRUE);
		if($_POST){
			$this->$_POST['tipo_documento']($_POST);
		}
		
		$this->data['estados_cuenta'] = $this->estados_cuenta_m->get_many_by(array('activo'=>'S'));
		$this->data['mandantes'] = $this->mandantes_m->get_many_by(array('activo'=>'S'));
		$this->data['etapas'] = $this->etapas_m->get_many_by(array('activo'=>'S'));
		
        $this->db->limit(100);
        $doc = $this->documento_plantilla_m->get_all();
		$documentos = array();
		foreach($doc as $key=>$val){
			$documentos[$val->path]= $val->nombre_documento;
		}
		
		//print_r($documentos);
		$this->data['documentos'] = $documentos;
		
		$this->data['plantilla'] = 'doc/generar'; 
		$this->load->view ( 'backend/index', $this->data );
	}
		
	public function buscarruts() {

		
		/*$documentos_array['demanda_ejecutiva_pagare_falabella.docx'] = 'Demanda Ejecutiva PagarÃ© Falabella';
		$documentos_array['v3_demanda_ejecutiva_pagare_estudiantes_gtia_estatal.docx'] = 'Demanda Ejecutiva PagarÃ© Estudiantes GarantÃ­a Estatal';
		$documentos_array['demanda_cedida_cae_con_exhorto.docx'] = 'Demanda Cedida CAE con exhorto';
		$documentos_array['demanda_cedida_cae_sin_exhorto.docx'] = 'Demanda Cedida CAE sin exhorto';
		$documentos_array['demanda_propia_cae_con_exhorto.docx'] = 'Demanda Propia CAE con exhorto';
		$documentos_array['demanda_propia_cae_sin_exhorto.docx'] = 'Demanda Propia CAE sin exhorto';
		
		$documentos_array['nvo_formato_acompaÃ±a_documento.docx'] = 'Nuevo Formato AcompaÃ±a Documento';
		$documentos_array['nvo_formato_certificado.docx'] = 'Nuevo Formato Cetificado';*/
		
		$this->data['estados_cuenta'] = $this->estados_cuenta_m->get_many_by(array('activo'=>'S'));			
		$this->data['mandantes'] = $this->mandantes_m->get_many_by(array('activo'=>'S'));
		$this->data['etapas'] = $this->etapas_m->get_many_by(array('activo'=>'S'));
        
		$doc = $this->documento_plantilla_m->get_all();
		foreach($doc as $key=>$val){
			$documentos_array[$val->path]= $val->nombre_documento;
		}

		$this->data['documentos'] = $documentos_array;
		
		if (is_numeric($this->input->post('tipo_documento'))){
			$templates = $this->documento_plantilla_m->get_by(array('id'=>$this->input->post('tipo_documento')));
			$tipo_documento = $templates->path;
		} else {
			$tipo_documento = $this->input->post('tipo_documento');
		}
		
	    if ($this->input->post('fecha_asignacion') != ''){
			$where['cta.fecha_asignacion']	= $this->input->post('fecha_asignacion');
		}
		
		if ($this->input->post('mandante') != ''){
			$where['cta.id_mandante']	= $this->input->post('mandante');
		}
		
		if ($this->input->post('estado_cuenta') != ''){
			$where['cta.id_estado_cuenta']	= $this->input->post('estado_cuenta');
		}
				
		if ($this->input->post('id_etapa_original') != ''){
			$where['cta.id_etapa']	= $this->input->post('id_etapa_original');
		}
		
		if ($this->input->post('tipo_demanda') != ''){
			$where['cta.tipo_demanda']	= $this->input->post('tipo_demanda');
		}
		
		if ($this->input->post('exorto') != ''){
			$where['cta.exorto']	= $this->input->post('exorto');
		}
		
		if ($this->input->post('ruts') != ''){
			$ruts = explode(',',$this->input->post('ruts'));
			$this->db->where_in('usr.rut', $ruts);
		}
		
		if ($this->input->post('id_cuenta') != ''){
			$where['cta.id']	= $this->input->post('id_cuenta');
		}

		
		//$where['cta.id']		=	$id;
		$where['cta.activo']	=	'S';
		
		$select = array();
		$select[] = 'cta.id AS id';
		$select[] = 'cta.rol AS rol';
		$select[] = 'cta.activo AS activo';
		$select[] = 'cta.publico AS publico';
		$select[] = 'cta.posicion AS posicion';
		$select[] = 'cta.id_procurador';
		$select[] = 'pagare.n_pagare AS n_pagare';
		$select[] = 'pagare.fecha_vencimiento AS fecha_vencimiento';
		$select[] = 'cta.fecha_asignacion AS fecha_asignacion';
		$select[] = 'cta.monto_demandado AS monto_demandado';
		$select[] = 'cta.monto_deuda AS monto_deuda';
		$select[] = 'cta.id_estado_cuenta AS id_estado_cuenta';
		$select[] = 'cta.id_mandante AS field_categoria';
		
		$select[] = 'cta.fecha_escritura_personeria AS fecha_escritura_personeria';
		$select[] = 'cta.notaria_personeria AS notaria_personeria';
		$select[] = 'cta.titular_personeria AS titular_personeria';
		
		$select[] = 'usr.nombres AS nombres';
		$select[] = 'usr.ap_pat AS ap_pat';
		$select[] = 'usr.ap_mat AS ap_mat';
		$select[] = 'usr.rut AS rut';
		//$select[] = 'usr.direccion AS direccion';
		//$select[] = 'usr.direccion_numero AS direccion_numero';
		$select[] = 'usr.ciudad AS ciudad';
		$select[] = 'cd.direccion AS direccion';
		//$select[] = 'cd_com.nombre AS comuna';
		
		$select[] = 'com.nombre AS comuna';
		
		$select[] = 'mand.razon_social';
		$select[] = 'mand.rut AS mandante_rut';
		$select[] = 'mand.representante_nombre';
		$select[] = 'mand.representante_apepat';
		$select[] = 'mand.representante_apemat';
		$select[] = 'mand.representante_comuna';
		$select[] = 'mand.representante_ciudad';
		$select[] = 'mand.representante_direccion';
		$select[] = 'mand.representante_direccion_n';
		$select[] = 'mand.fecha_escritura_publica';
		$select[] = 'mand.numero_repetorio';
		$select[] = 'mand.notaria';
		$select[] = 'mand.fecha_escritura_apoderado';
		$select[] = 'mand.notaria_apoderado';
		
		$select[] = 'adm.rut_procurador AS procurador_rut';
		$select[] = 'adm.nombres AS procurador_nombres';
		$select[] = 'adm.apellidos AS procurador_apellidos';
		
		$select[] = 'tip.tipo AS tipo_producto';
		$select[] = 'SUM(pag.monto_pagado) AS total_pagado';
		$select[] = 'trib_com.tribunal AS jurisdiccion';
		
		$select[] = 'trib.tribunal AS distrito';
		$select[] = 'dist.tribunal AS juzgado';
		
		$query = $this->db->select( $select )
                	
				 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
				 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	
				 ->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")	
				 ->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S'","left")
				 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
				 ->join("s_comunas com", "com.id = usr.id_comuna","left")
				 ->join("s_tribunales trib", "trib.id = cta.id_tribunal","left")
				 ->join("s_tribunales dist", "dist.id = cta.id_distrito","left")
				 ->join("pagare pagare", "pagare.idcuenta = cta.id","left")	
				 ->join("2_cuentas_direccion cd", "cd.id_cuenta = cta.id","left")	
				 ->join("s_comunas cd_com", "cd_com.id = cd.id_comuna","left")
				 ->join("s_tribunales trib_com","trib_com.id=cd_com.id_tribunal_padre","left")
				 ->where($where)
				 ->order_by("id_mandante", "desc")
				 ->order_by("cta.fecha_asignacion", "desc")
				 ->group_by("cta.id")
 				 ->get("0_cuentas cta");
				
 				 $datos = $query->result();	
		
		/*
			echo '<pre>';
			print_r($datos);
			echo '</pre>';
			$this->output->enable_profiler(TRUE);
		*/
		//$this->output->enable_profiler(TRUE);
		$this->data['datos'] = $datos; 
		$this->data['plantilla'] = 'doc/generar'; 
		$this->load->view ( 'backend/index', $this->data );		
	}
		
	public function generardocnew() {
		
		//$this->output->enable_profiler(TRUE);
		/*$documentos_array['demanda_ejecutiva_pagare_falabella.docx'] = 'Demanda Ejecutiva PagarÃ© Falabella';
		$documentos_array['v3_demanda_ejecutiva_pagare_estudiantes_gtia_estatal.docx'] = 'Demanda Ejecutiva PagarÃ© Estudiantes GarantÃ­a Estatal';
		$documentos_array['demanda_cedida_cae_con_exhorto.docx'] = 'Demanda Cedida CAE con exhorto';
		$documentos_array['demanda_cedida_cae_sin_exhorto.docx'] = 'Demanda Cedida CAE sin exhorto';
		$documentos_array['demanda_propia_cae_con_exhorto.docx'] = 'Demanda Propia CAE con exhorto';
		$documentos_array['demanda_propia_cae_sin_exhorto.docx'] = 'Demanda Propia CAE sin exhorto';
		
		$documentos_array['nvo_formato_acompaÃ±a_documento.docx'] = 'Nuevo Formato AcompaÃ±a Documento';
		$documentos_array['nvo_formato_certificado.docx'] = 'Nuevo Formato Cetificado';*/
		
		$doc = $this->documento_plantilla_m->get_all();
		foreach($doc as $key=>$val){
			$documentos_array[$val->path]= $val->nombre_documento;
		}
		//print_r($doc);
		$this->data['documentos'] = $documentos_array;
		$fechademanda = $this->input->post('fecha');
		if (is_numeric($this->input->post('tipo_documento'))){
			$templates = $this->documento_plantilla_m->get_by(array('id'=>$this->input->post('tipo_documento')));
			$tipo_documento = $templates->path;
		} else {
			$tipo_documento = $this->input->post('tipo_documento');
		}
		//echo $tipo_documento;
		
		//111
		
	    if ($this->input->post('fecha_asignacion') != ''){
			$where['cta.fecha_asignacion']	= $this->input->post('fecha_asignacion');
		}
		
		if ($this->input->post('mandante') != ''){
			$where['cta.id_mandante']	= $this->input->post('mandante');
		}
		
		if ($this->input->post('estado_cuenta') != ''){
			$where['cta.id_estado_cuenta']	= $this->input->post('estado_cuenta');
		}
				
		if ($this->input->post('id_etapa_original') != ''){
			$where['cta.id_etapa']	= $this->input->post('id_etapa_original');
		}
		
		if ($this->input->post('tipo_demanda') != ''){
			$where['cta.tipo_demanda']	= $this->input->post('tipo_demanda');
		}
		
		if ($this->input->post('exorto') != ''){
			$where['cta.exorto']	= $this->input->post('exorto');
		}
		
		if ($this->input->post('Demandados') != ''){
			//$ruts = explode(',',$this->input->post('ruts'));
			$deudores=$_POST["Demandados"]; 


			for ($i=0;$i<count($deudores);$i++)    
			{ 
				$datorut	=  $deudores[$i];	
				$ruts		.=  $datorut.",";	
			} 
			$ruts = explode(',',$ruts);
			//$ruts = trim($ruts, ',');		
			$this->db->where_in('usr.rut', $ruts);
			//print_r($ruts);
		}
		//print_r($this->db);
		//print_r($_POST);
		//die();
		if ($this->input->post('id_cuenta') != ''){
			$where['cta.id']	= $this->input->post('id_cuenta');
		}

		
		//$where['cta.id']		=	$id;
		$where['cta.activo']	=	'S';
		
		$select = array();
		$select[] = 'cta.id AS id';
		$select[] = 'cta.rol AS rol';
		$select[] = 'cta.activo AS activo';
		$select[] = 'cta.publico AS publico';
		$select[] = 'cta.posicion AS posicion';
		$select[] = 'cta.id_procurador';
		$select[] = 'pagare.n_pagare AS n_pagare';
		$select[] = 'pagare.fecha_vencimiento AS fecha_vencimiento';
		$select[] = 'cta.fecha_asignacion AS fecha_asignacion';
		$select[] = 'cta.monto_demandado AS monto_demandado';
		$select[] = 'cta.monto_deuda AS monto_deuda';
		$select[] = 'cta.id_estado_cuenta AS id_estado_cuenta';
		$select[] = 'cta.id_mandante AS field_categoria';
		
		$select[] = 'cta.fecha_escritura_personeria AS fecha_escritura_personeria';
		$select[] = 'cta.notaria_personeria AS notaria_personeria';
		$select[] = 'cta.titular_personeria AS titular_personeria';
		
		$select[] = 'usr.nombres AS nombres';
		$select[] = 'usr.ap_pat AS ap_pat';
		$select[] = 'usr.ap_mat AS ap_mat';
		$select[] = 'usr.rut AS rut';
		//$select[] = 'usr.direccion AS direccion';
		//$select[] = 'usr.direccion_numero AS direccion_numero';
		$select[] = 'usr.ciudad AS ciudad';
		$select[] = 'cd.direccion AS direccion';
		//$select[] = 'cd_com.nombre AS comuna';
		
		$select[] = 'com.nombre AS comuna';
		
		$select[] = 'mand.razon_social';
		$select[] = 'mand.rut AS mandante_rut';
		$select[] = 'mand.representante_nombre';
		$select[] = 'mand.representante_apepat';
		$select[] = 'mand.representante_apemat';
		$select[] = 'mand.representante_comuna';
		$select[] = 'mand.representante_ciudad';
		$select[] = 'mand.representante_direccion';
		$select[] = 'mand.representante_direccion_n';
		$select[] = 'mand.fecha_escritura_publica';
		$select[] = 'mand.numero_repetorio';
		$select[] = 'mand.notaria';
		$select[] = 'mand.fecha_escritura_apoderado';
		$select[] = 'mand.notaria_apoderado';
		
		$select[] = 'adm.rut_procurador AS procurador_rut';
		$select[] = 'adm.nombres AS procurador_nombres';
		$select[] = 'adm.apellidos AS procurador_apellidos';
		
		$select[] = 'tip.tipo AS tipo_producto';
		$select[] = 'SUM(pag.monto_pagado) AS total_pagado';
		$select[] = 'trib_com.tribunal AS jurisdiccion';
		
		$select[] = 'trib.tribunal AS distrito';
		$select[] = 'dist.tribunal AS juzgado';
		
		$query = $this->db->select( $select )
                	
				 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
				 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	
				 ->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")	
				 ->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S'","left")
				 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
				 ->join("s_comunas com", "com.id = usr.id_comuna","left")
				 ->join("s_tribunales trib", "trib.id = cta.id_tribunal","left")
				 ->join("s_tribunales dist", "dist.id = cta.id_distrito","left")
				 ->join("pagare pagare", "pagare.idcuenta = cta.id","left")	
				 ->join("2_cuentas_direccion cd", "cd.id_cuenta = cta.id","left")	
				 ->join("s_comunas cd_com", "cd_com.id = cd.id_comuna","left")
				 ->join("s_tribunales trib_com","trib_com.id=cd_com.id_tribunal_padre","left")
				 ->where($where)
				 ->order_by("id_mandante", "desc")
				 ->order_by("cta.fecha_asignacion", "desc")
				 ->group_by("cta.id")
 				 ->get("0_cuentas cta");
				
 				 $datos = $query->result();
		
		//print_r($datos);
		//$this->output->enable_profiler(TRUE);
		if(count($datos)>0){

			$zip = new ZipArchive();
			$nombre_zip = uniqid().'__'.date("d-m-Y__H-i-s");
			$filename = './documentos_zip/'.$nombre_zip.'.zip';
			
			if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
			    exit("cannot open <$filename>\n");
			}
	
			foreach($datos as $key=>$val){
				$c1[] = $this->pagare_m->get_columns();
				$c2[] = $this->tipo_productos_m->get_columns();
				$c = array_merge($c1, $c2);
				foreach ($c as $campo) {
					foreach ($campo as $dato) {
						$cols[] = $dato;
					}
				}
					
				$this->db->order_by('pa.fecha_asignacion', 'DESC');
				if($val->id !=''){
					$this->db->where('pa.idcuenta', $val->id);
				}
				$this->db->where('pa.activo', 'S');
				$this->db->select($cols);
				$this->db->from('pagare pa');
				$this->db->join('s_tipo_productos tp', 'tp.id = pa.id_tipo_producto','left');
				$query = $this->db->get();
				
				$pagares_array = array();
				$pagares_array = $query->result();
				$monto_deuda = 0;
				$pagares = '';
				$pagares_uf = '';
				
				$total_pagare_peso = 0;

				$meses_espanol = array( '1'=>'enero','2'=>'febrero','3'=>'marzo','4'=>'abril','5'=>'mayo','6'=>'junio','7'=>'julio','8'=>'agosto','9'=>'septiembre','10'=>'octubre','11'=>'noviembre','12'=>'diciembre');
				//echo './documentos_base/'.$tipo_documento;
				require_once './doc_library/classes/CreateDocx.inc';
				$docx = new CreateDocx();
				$docx->addTemplate('./documentos_base/'.$tipo_documento);
				
				//echo "ss";
				//print_r($this);
				$garantiasve = $this->vehiculos_m->get_many_by(array('id_cuenta'=>$val->id,'activo'=>'S'));
				
				if (count($garantiasve)>0){
					$r=1;
					foreach ($garantiasve as $kb=>$val_garantiasve){
						$tipos_vehiculos = array('0'=>'Automovil','1'=>'Moto','2'=>'Furgón','3'=>'Camioneta','4'=>'Stationwagon','5'=>'Otro');
						if ($val_garantiasve->tipo==4){
					
							$docx->addTemplateVariable('GARANTIAVEHICULO_FECHA_CONT_'.$r, $val_garantiasve->fecha_cont);
							$docx->addTemplateVariable('GARANTIAVEHICULO_N_REPERTORIO_'.$r, $val_garantiasve->n_repertorio);
							$docx->addTemplateVariable('GARANTIAVEHICULO_TIPO_'.$r, $tipos_vehiculos[$val_garantiasve->tipo_vehiculo]);
							$docx->addTemplateVariable('GARANTIAVEHICULO_MARCA_'.$r, $val_garantiasve->marca);
							$docx->addTemplateVariable('GARANTIAVEHICULO_MODELO_'.$r, $val_garantiasve->modelo);
							$docx->addTemplateVariable('GARANTIAVEHICULO_N_MOTOR_'.$r, $val_garantiasve->n_motor);
							$docx->addTemplateVariable('GARANTIAVEHICULO_COLOR_'.$r, $val_garantiasve->color);
							$docx->addTemplateVariable('GARANTIAVEHICULO_INSCRIPCION_'.$r, $val_garantiasve->inscripcion);						
							$docx->addTemplateVariable('GARANTIAVEHICULO_N_CHACHIS_'.$r, $val_garantiasve->n_chachis);
							$docx->addTemplateVariable('GARANTIAVEHICULO_ANIO_'.$r, $val_garantiasve->anio);
							$docx->addTemplateVariable('GARANTIAVEHICULO_PLACAUNICA_'.$r, $val_garantiasve->placaunica);
							$docx->addTemplateVariable('GARANTIAVEHICULO_PLACAPATENTE_'.$r, $val_garantiasve->placapatente);
							$docx->addTemplateVariable('GARANTIAVEHICULO_FECHAEXIGIBLE_'.$r, $val_garantiasve->fechaexigible);
							
							$r++;
						}
					}
				}
				
				$garantiaspersonal = $this->personal_m->get_many_by(array('id_cuenta'=>$val->id,'activo'=>'S'));
				if (count($garantiaspersonal)>0){
					$r=1;
					foreach ($garantiaspersonal as $kb=>$val_garantiaspers){
						$tipos_vehiculos = array('0'=>'Automovil','1'=>'Moto','2'=>'Furgón','3'=>'Camioneta','4'=>'Stationwagon','5'=>'Otro');
						if ($val_garantiasve->tipo==0){
							
							$docx->addTemplateVariable('GARANTIAPERSONAL_TIPO_'.$r, $val_garantiaspers->tipog);
							$docx->addTemplateVariable('GARANTIAPERSONAL_NOMBRE_'.$r, $val_garantiaspers->nombreg);
							$docx->addTemplateVariable('GARANTIAPERSONAL_RUT_'.$r, $val_garantiaspers->rutg);
							$docx->addTemplateVariable('GARANTIAPERSONAL_DOMICILIO_'.$r, $val_garantiaspers->domiciliog);
							$r++;
						}
					}
				}
				
				$bienes = $this->bienes_m->get_many_by(array('id_cuenta'=>$val->id,'activo'=>'S'));
				if (count($bienes)>0){
					$r=1;
					foreach ($bienes as $kb=>$val_bienes){
						$tipos_vehiculos = array('0'=>'Automovil','1'=>'Moto','2'=>'Furgón','3'=>'Camioneta','4'=>'Stationwagon','5'=>'Otro');
						if ($val_bienes->tipo==1){
							$docx->addTemplateVariable('VEHICULO_TIPO_'.$r, $tipos_vehiculos[$val_bienes->tipo_vehiculo]);
							$docx->addTemplateVariable('VEHICULO_MARCA_'.$r, $val_bienes->marca);
							$docx->addTemplateVariable('VEHICULO_MODELO_'.$r, $val_bienes->modelo);
							$docx->addTemplateVariable('VEHICULO_N_MOTOR_'.$r, $val_bienes->n_motor);
							$docx->addTemplateVariable('VEHICULO_COLOR_'.$r, $val_bienes->color);
							$docx->addTemplateVariable('VEHICULO_INSCRIPCION_'.$r, $val_bienes->inscripcion);
							$r++;
						}
					}
				}
				//AQUI EL CODE DE LAS GARANTIAS
				
				
				//print_r($pagares_array);
				//print_r($_POST);
				$n_pagare = '';
				if (count($pagares_array)>0){
					
					$xmlSource = "http://indicadoresdeldia.cl/webservice/indicadores.xml";
					$xml = simplexml_load_file($xmlSource);
					//print_r($xml);
					$uf = str_replace(array('$','.',','),array('','','.'),$xml->indicador->uf);
					$uf_fecha = date('d/m/Y');//$fechademanda;//
					
					$total_pagare_uf = 0;
					
					$r = 1;
					$monto_deuda = 0;
					
					$acentos = array('á','Á','é','É','í','Í','ó','Ó','ú','Ú','ñ','Ñ','º');
					$acutes = array('&aacute;','&Aacute;','&eacute;','&Eacute;','&iacute;','&Iacute;','&oacute;','&Oacute;','&uacute;','&Uacute;','&ntilde;','&Ntilde;','&deg;');
				
					//echo count($pagares_array);
					foreach ($pagares_array as $k=>$v){
						
						//echo $monto_deuda.'<br>';
						//$pagares.='â€¢	PagarÃ© NÂº '.$v->pagare_n_pagare.', suscrito con fecha '.date("d/m/Y", strtotime($v->pagare_fecha_asignacion)).', ';
						//$pagares.='por la suma de '.$v->pagare_monto_deuda.' Unidades de Fomento, por concepto de capital.<br>';
						
						$vencimiento_dia = '';
						$vencimiento_mes = '';
						$vencimiento_year = '';
						
						if( strtotime($v->pagare_fecha_asignacion) > 0){
							$asignacion = date("d/m/Y", strtotime($v->pagare_fecha_asignacion));
							$asignacion_dia = date("d", strtotime($v->pagare_fecha_asignacion));
							$asignacion_mes = date("n", strtotime($v->pagare_fecha_asignacion));
							$asignacion_year = date("Y", strtotime($v->pagare_fecha_asignacion));
						}else{
							$asignacion = '_/_/_';
						}
						//echo $v->pagare_fecha_vencimiento.'----------<br>';
						if( strtotime($v->pagare_fecha_vencimiento) > 0 ){
							$vencimiento = date("d/m/Y", strtotime($v->pagare_fecha_vencimiento));
							$vencimiento_dia = date("d", strtotime($v->pagare_fecha_vencimiento));
							$vencimiento_mes = date("n", strtotime($v->pagare_fecha_vencimiento));
							$vencimiento_year = date("Y", strtotime($v->pagare_fecha_vencimiento));
						}else{
							$vencimiento = '_/_/_';
						}
						
						$suscrito = date('d/m/Y');						
						$suscrito_en_letras = strtolower($this->ValorEnLetras(date('d'),"").' de '.$meses_espanol[date("n")].' de '.$this->ValorEnLetras(date('Y'),""));
												
						$vencimiento_mes_new = '';
						$asignacion_mes_new = '';
						
						if (array_key_exists($asignacion_mes,$meses_espanol)){
							$asignacion_mes_new = $meses_espanol[$asignacion_mes];
						}
						if (array_key_exists($vencimiento_mes,$meses_espanol)){
							$vencimiento_mes_new = $meses_espanol[$vencimiento_mes];
						}
						$asignacion_en_letras = strtolower($this->ValorEnLetras($asignacion_dia,"").' de '.$asignacion_mes_new.' de '.$this->ValorEnLetras($asignacion_year,""));
						$vencimiento_en_letras = strtolower($this->ValorEnLetras($vencimiento_dia,"").' de '.$vencimiento_mes_new.' de '.$this->ValorEnLetras($vencimiento_year,""));
						//echo ' -> '.$asignacion_en_letras;
						//echo ' -> '.$vencimiento_en_letras;
					
						//$pagares.='- Pagar&eacute; de fecha '.$suscrito_en_letras.' suscrito(s) con fecha '.$asignacion_en_letras.' por el monto '.number_format($v->pagare_monto_deuda,4,',','.').' Unidades de Fomento, con vencimiento el '.$vencimiento_en_letras.'<br>';
						
						$docx->addTemplateVariable('DÍA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS_'.$r, strtolower($this->ValorEnLetras($vencimiento_dia)) );
			    		$docx->addTemplateVariable('MES_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS_'.$r, strtolower($meses_espanol[$vencimiento_mes]) );
			    		$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS_'.$r, strtolower($this->ValorEnLetras($vencimiento_year)) );
						//echo $uf."<br>"."<br>";
						//echo @number_format($v->pagare_monto_deuda,4,',','.')."<br>"."<br>";
						//echo @number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.') ."<br>"."<br>";
						$docx->addTemplateVariable('MONTO_DEUDA_DEL_PAGARE_'.$r, '$'.@number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.') );
						$docx->addTemplateVariable('MONTO_DEUDA_UF_DEL_PAGARE_'.$r, @number_format($v->pagare_monto_deuda,4,',','.') );
						
						$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_RESTANTES_CUOTAS_DEL_PAGARE_'.$r, date("d/m/Y", strtotime($v->pagare_vencimiento_restantes_cuotas)) );
						$docx->addTemplateVariable('DIA_DE_VENCIMIENTO_RESTANTES_CUOTAS_DEL_PAGARE_'.$r, date("d", strtotime($v->pagare_vencimiento_restantes_cuotas)) );
			    		$docx->addTemplateVariable('MES_DE_VENCIMIENTO_RESTANTES_CUOTAS_DEL_PAGARE_'.$r, date("m", strtotime($v->pagare_vencimiento_restantes_cuotas)) );
			    		$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_RESTANTES_CUOTAS_DEL_PAGARE_'.$r, date("Y", strtotime($v->pagare_vencimiento_restantes_cuotas)) );

						$docx->addTemplateVariable('FECHA_LETRAS_DE_ASIGNACION_PAGARE_'.$r, strtolower($asignacion_en_letras) );
						
						$docx->addTemplateVariable('FECHA_DE_ASIGNACION_PAGARE_'.$r, date("d/m/Y", strtotime($v->pagare_fecha_asignacion)) );
						$docx->addTemplateVariable('DIA_DE_ASIGNACION_PAGARE_'.$r, date("d", strtotime($v->pagare_fecha_asignacion)) );
			    		$docx->addTemplateVariable('MES_DE_ASIGNACION_PAGARE_'.$r, date("m", strtotime($v->pagare_fecha_asignacion)) );
			    		$docx->addTemplateVariable('ANO_DEASIGNACION_PAGARE_'.$r, date("Y", strtotime($v->pagare_fecha_asignacion)) );

						$docx->addTemplateVariable('FECHA_DE_AUTORIZACION_PAGARE_'.$r, date("d/m/Y", strtotime($v->pagare_fecha_autorizacion)) );
						$docx->addTemplateVariable('DIA_DE_AUTORIZACION_PAGARE_'.$r, date("d", strtotime($v->pagare_fecha_autorizacion)) );
			    		$docx->addTemplateVariable('MES_DE_AUTORIZACION_PAGARE_'.$r, date("m", strtotime($v->pagare_fecha_autorizacion)) );
			    		$docx->addTemplateVariable('ANO_DE_AUTORIZACION_PAGARE_'.$r, date("Y", strtotime($v->pagare_fecha_autorizacion)) );
						
						$docx->addTemplateVariable('FECHA_DE_SUSCRIPCION_PAGARE_'.$r, date("d/m/Y", strtotime($v->pagare_fecha_suscripcion)) );
						$docx->addTemplateVariable('DIA_DE_SUSCRIPCION_PAGARE_'.$r, date("d", strtotime($v->pagare_fecha_suscripcion)) );
			    		$docx->addTemplateVariable('MES_DE_SUSCRIPCION_PAGARE_'.$r, date("m", strtotime($v->pagare_fecha_suscripcion)) );
			    		$docx->addTemplateVariable('ANO_DE_SUSCRIPCION_PAGARE_'.$r, date("Y", strtotime($v->pagare_fecha_suscripcion)) );
												
						$docx->addTemplateVariable('ULTIMA_CUOTA_PAGADA_PAGARE_'.$r, $v->pagare_ultima_cuota_pagada);
						$docx->addTemplateVariable('SALDO_DEUDA_PAGARE_'.$r, number_format($v->pagare_saldo_deuda,0,',','.'));
						$docx->addTemplateVariable('VALOR_PRIMERA_CUOTA_DEL_PAGARE_'.$r, $v->valor_primera_cuota);
						
						$docx->addTemplateVariable('FECHA_PAGO_ULTIMA_CUOTA_PAGARE_'.$r, date("d/m/Y", strtotime($v->pagare_fecha_pago_ultima_cuota)) );
						$docx->addTemplateVariable('DIA_PAGO_ULTIMA_CUOTA_PAGARE_'.$r, date("d", strtotime($v->pagare_fecha_pago_ultima_cuota)) );
			    		$docx->addTemplateVariable('MES_PAGO_ULTIMA_CUOTA_PAGARE_'.$r, date("m", strtotime($v->pagare_fecha_pago_ultima_cuota)) );
			    		$docx->addTemplateVariable('ANO_PAGO_ULTIMA_CUOTA_PAGARE_'.$r, date("Y", strtotime($v->pagare_fecha_pago_ultima_cuota)) );
						
			    		$docx->addTemplateVariable('TASA_INTERES_DEL_PAGARE_'.$r, $v->pagare_tasa_interes);
						$docx->addTemplateVariable('NUMERO_CUOTAS_DEL_PAGARE_'.$r, $v->pagare_numero_cuotas);
						$docx->addTemplateVariable('VALOR_PRIMERA_CUOTA_DEL_PAGARE_'.$r, number_format($v->pagare_valor_primera_cuota,0,',','.'));
						$docx->addTemplateVariable('VALOR_ULTIMA_CUOTA_DEL_PAGARE_'.$r, number_format($v->pagare_valor_ultima_cuota,0,',','.'));					
						
						$docx->addTemplateVariable('N_PAGARE_'.$r, $v->pagare_n_pagare);
                        if ($r==1){$n_pagare = $v->pagare_n_pagare;}

						$glosa_saldo_deudor = '';
						$glosa_saldo_deudor_uf = '';
						$resultado_saldo_deudor = 0;
						if ($v->pagare_numero_cuotas>0){
							//echo $v->pagare_monto_deuda.'-(('.str_replace(',','.',$v->pagare_monto_deuda).'/'.$v->pagare_numero_cuotas.')*'.$v->pagare_ultima_cuota_pagada.')<br>';
							$resultado_saldo_deudor = $v->pagare_monto_deuda-(($v->pagare_monto_deuda/$v->pagare_numero_cuotas)*$v->pagare_ultima_cuota_pagada);

							$glosa_saldo_deudor= "La operación aritmética que permite arribar a la suma de $".@number_format(str_replace(array(',','.'),'',round($resultado_saldo_deudor,0)),0,',','.')."; se desprende de dividir el monto del pagaré ";
							$glosa_saldo_deudor.= "de ".'$'.@number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.')." por ".$v->pagare_numero_cuotas." ";
							$glosa_saldo_deudor.= "cuotas pactadas; y luego multiplicar el resultado por ".$v->pagare_ultima_cuota_pagada." que corresponde a la cantidad ";
							$glosa_saldo_deudor.= "de cuotas impagas.";
							$glosa_saldo_deudor = str_replace($acentos,$acutes,$glosa_saldo_deudor);
							
							$glosa_saldo_deudor_uf= "La operación aritmética que permite arribar a la suma de ".@number_format($resultado_saldo_deudor,4,',','.')." Unidades de Fomento; se desprende de dividir el monto del pagaré ";
							$glosa_saldo_deudor_uf.= "de ".@number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.')." Unidades de Fomento por ".$v->pagare_numero_cuotas." ";
							$glosa_saldo_deudor_uf.= "cuotas pactadas; y luego multiplicar el resultado por ".$v->pagare_ultima_cuota_pagada." que corresponde a la cantidad ";
							$glosa_saldo_deudor_uf.= "de cuotas impagas.";
							$glosa_saldo_deudor_uf = str_replace($acentos,$acutes,$glosa_saldo_deudor_uf);
							
							$docx->replaceTemplateVariableByHTML('MONTO_SALDO_DEUDA_DEL_PAGARE_'.$r, 'inline', $glosa_saldo_deudor.'');
							$docx->replaceTemplateVariableByHTML('MONTO_SALDO_DEUDA_DEL_PAGARE_UF_'.$r, 'inline', $glosa_saldo_deudor_uf.'');
						}
						//$docx->addTemplateVariable('MONTO_SALDO_DEUDA_DEL_PAGARE_'.$r, $glosa_saldo_deudor);
						
						//echo '--'.$glosa_saldo_deudor;						
						if ($v->pagare_id_tipo_producto==5){

							$monto_deuda+= $v->pagare_saldo_deuda;
							$total_pagare_uf+=str_replace(',','.',$v->pagare_saldo_deuda);
						} 
						elseif($v->pagare_tasa_interes>0) 
						{
							$monto_deuda+= $v->pagare_monto_deuda;
							$total_pagare_uf+=$v->pagare_tasa_interes;
						} 
						else 
						{
							//print_r($v);
							$monto_deuda+= $v->pagare_monto_deuda;
							$total_pagare_uf+=str_replace(',','.',$v->pagare_monto_deuda);
						}
						
						$pagare_numero_cuotas = $v->pagare_numero_cuotas;
						$pagare_valor_primera_cuota = number_format($v->pagare_valor_primera_cuota,0,',','.');
						$pagare_valor_ultima_cuota = number_format($v->pagare_valor_ultima_cuota,0,',','.');
						if ($v->pagare_vencimiento_primera_cuota!=''){
							$pagare_vencimiento_primera_cuota = date('d/m/Y',strtotime($v->pagare_vencimiento_primera_cuota));
						} else {
							$pagare_vencimiento_primera_cuota = '';
						}
						if ($v->pagare_vencimiento_restantes_cuotas!=''){
							$pagare_vencimiento_restantes_cuotas = date('d/m/Y',strtotime($v->pagare_vencimiento_restantes_cuotas));
						} else {
							$pagare_vencimiento_restantes_cuotas = '';
						}
						$pagare_nombre_aval = $v->pagare_nombre_aval;						
						
						$pagares.='- Pagar&eacute; de fecha '.$asignacion_en_letras.' suscrito(s) por el monto $'.@number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').', con vencimiento el '.$vencimiento_en_letras.'.<br>';
						$pagares_uf.='- Pagar&eacute; de fecha '.$asignacion_en_letras.' suscrito(s) por el monto '.str_replace('.',',',$v->pagare_monto_deuda).' Unidades de Fomento, con vencimiento el '.$vencimiento_en_letras.'.<br>';
												
						$pagare_tipo_producto = '';
						
						if ($v->pagare_id_tipo_producto==6){
							$pagare_tipo_producto.='<p style="font-family:Times; font-size:12pt">';
							$pagare_tipo_producto.='- Pagaré número <strong>'.$v->pagare_n_pagare.'</strong>, suscrito con fecha '.$asignacion_en_letras;
							$pagare_tipo_producto.=', por la suma de <strong>$'.number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').'</strong>.- suscrito por SCOTIABANK, a su vez en representación';
							$pagare_tipo_producto.=' de <strong>'.strtoupper($val->nombres).' '.strtoupper($val->ap_pat).' '.strtoupper($val->ap_mat).'</strong>, en virtud del mandato conferido por este(a) último(a)';
							$pagare_tipo_producto.=' a SCOTIABANK en el <strong>"CONTRATO CLIENTE BANCA DE PERSONA"</strong>, el cual se acompaña en el N&deg; 3 del Segundo Otrosí de esta demanda, en';
							$pagare_tipo_producto.=' donde se establece que para facilitar el cobro de lo adeudado en virtud de dicho contrato, y para efectos de completar el título ejecutivo,';
							$pagare_tipo_producto.=' el Cliente, confiere poder especial al Banco, para que éste por sí o a través de un tercero especialmente';
							$pagare_tipo_producto.=' designado al efecto, a su nombre y representación proceda a autorizar ante Notario Público,';
							$pagare_tipo_producto.=' uno o más pagarés a la vista y a la orden del Banco por la suma a que asciendan los créditos, giros y/o solicitudes de pagos y cualquier otra obligación';
							$pagare_tipo_producto.=' que se contraiga. La personería otorgada por el Banco a los firmantes del los pagares para que actúen como terceros especialmente';
							$pagare_tipo_producto.=' designado al efecto consta en la Copia de escritura pública correspondiente a "Acta N&deg; 2.375. Sesión ordinaria de Directorio de Fecha 23 de Septiembre de 2014"';
							$pagare_tipo_producto.=' la cual se acompaña en el N&deg; 6 del Segundo Otrosí. Es del caso US., que el deudor no ha pagado a ésta fecha el presente pagaré,';
							$pagare_tipo_producto.=' por lo que adeuda a mi representado el total de la obligación que exclusivamente en capital, y según lo establecido en el artículo 30 de la Ley 18.010';
							$pagare_tipo_producto.=' asciende al saldo insoluto de capital de';
							$pagare_tipo_producto.=' <strong>$'.number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').'</strong>.-';
							$pagare_tipo_producto.=' más costas e intereses los cuales deberán liquidarse en su respectivo momento.-';
							$pagare_tipo_producto.='</p>';
							
							$pagare_tipo_producto = str_replace($acentos,$acutes,$pagare_tipo_producto);
		
							$docx->replaceTemplateVariableByHTML('PAGARE_TIPO_PRODUCTO_'.$r, 'inline', $pagare_tipo_producto.'');

						}elseif ($v->pagare_id_tipo_producto==5){
						
							$pagare_tipo_producto.='<p style="font-family:Times; font-size:12pt">';
							$pagare_tipo_producto.='Pagaré número N&deg; <strong>'.$v->pagare_n_pagare.'</strong>, suscrito con fecha <strong>'.$vencimiento_en_letras.'</strong>, ';
							$pagare_tipo_producto.='por la suma de $ <strong>'.number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').'</strong>.- cantidad que se obligo a pagar con un interés de <strong>'.$v->pagare_tasa_interes.'%</strong> mensual ';
							$pagare_tipo_producto.='vencido, pagadero en <strong>'.$v->pagare_numero_cuotas.'</strong> cuotas, mensuales y sucesivas, por un valor de <strong>$'.number_format($v->pagare_valor_primera_cuota,0,',','.').'</strong>.- cada ';
							$pagare_tipo_producto.='una, excepto la última por un valor de <strong>$'.number_format($v->pagare_valor_ultima_cuota,0,',','.').'</strong>.-, siendo el primer ';
							$pagare_tipo_producto.='vencimiento el día <strong>'.date("d/m/Y", strtotime($v->pagare_vencimiento_primera_cuota)).'</strong>, y las restantes los días <strong>'.date("d", strtotime($v->pagare_vencimiento_restantes_cuotas)).'</strong> de ';
							$pagare_tipo_producto.='cada mes, o del último mes del respectivo periodo.  La parte ejecutada pagó hasta la cuota <strong>N&deg; <strong>'.$v->pagare_ultima_cuota_pagada.'</strong></strong>';
							$pagare_tipo_producto.=' con vencimiento el día <strong>'.date("d/m/Y", strtotime($v->pagare_fecha_pago_ultima_cuota)).'</strong>, en donde pagó una cuota total de <strong>$'.number_format($v->pagare_valor_ultima_cuota_pagada,0,',','.').'</strong> y dejando ';
							$pagare_tipo_producto.='un saldo pendiente como capital insoluto efectivamente adeudado a pagar de <strong>$'.number_format($v->pagare_saldo_deuda,0,',','.').'</strong>, lo cual ';
							$pagare_tipo_producto.='consta en la respectiva fila del cronograma de pago adjuntado. No obstante la parte ejecutada ';
							$pagare_tipo_producto.='dejo de servir la deuda a mi representado, sin pagar desde la cuota N&deg; <strong>'.($v->pagare_ultima_cuota_pagada+1).'</strong>,  por lo que esta parte a contar de ';
							$pagare_tipo_producto.='la fecha de presentación de esta demanda ha decidido hacer exigible el total de la obligación, que exclusivamente ';
							$pagare_tipo_producto.='en capital, y según lo establecido en el artículo 30 de la Ley 18.010, asciende al saldo de capital insoluto ';
							$pagare_tipo_producto.='antes señalado de <strong>$'.number_format($v->pagare_saldo_deuda,0,',','.').'</strong>.- más intereses correspondientes que deberán liquidarse en su respectivo momento, al igual que las costas.-';
							$pagare_tipo_producto.='</p>';
							$pagare_tipo_producto = str_replace($acentos,$acutes,$pagare_tipo_producto);
							$docx->replaceTemplateVariableByHTML('PAGARE_TIPO_PRODUCTO_'.$r, 'inline', $pagare_tipo_producto.'');
						} 
						else 
						{
							$xx = '';
							$docx->addTemplateVariable('PAGARE_TIPO_PRODUCTO_'.$r, $xx);
						}						
						//$pagares.='- Pagar&eacute; de fecha '.$suscrito.'  suscrito(s) con fecha '.$asignacion.' por el monto '.number_format($v->pagare_monto_deuda,2,',','.').' Unidades de Fomento, con vencimiento el '.$vencimiento;		
						$r++;
					}				
				}
				
				
				for ($sss=$r;$sss<=6;$sss++){
					$xx = '';
					$docx->addTemplateVariable('PAGARE_TIPO_PRODUCTO_'.$sss, $xx);
				}
				
				if( $pagares != ''){
					$pagares = '<p style="font-family:Times; font-size:12pt">'.$pagares.'</p>';
				}
				
				setlocale(LC_ALL, 'es_ES');
				$fecha_hoy_en_letras = strtolower($this->ValorEnLetras(date('d'),"").' de '.strftime("%B", strtotime(date('d-m-Y'))).' de '.$this->ValorEnLetras(date('Y'),""));
				$nombre_archivo = uniqid().'__'.date("d-m-Y__H-i-s");
				
				if ($this->data['nodo']->nombre=='swcobranza'){
					$docx->addTemplateVariable('JUZGADO', ''.str_replace('Civil','',$val->distrito));
					$docx->addTemplateVariable('DISTRITO', ''.$val->juzgado);
				}
				else {
					$docx->addTemplateVariable('DISTRITO', ''.str_replace('Civil','',$val->distrito));
					$docx->addTemplateVariable('JUZGADO', ''.$val->juzgado);
				}
				
				$docx->addTemplateVariable('ROL', ''.$val->rol);
				$docx->addTemplateVariable('JURISDICCION', ''.$val->jurisdiccion);
			    
			    $docx->addTemplateVariable('NOMBRE_MANDANTE', $val->razon_social);
			    $docx->addTemplateVariable('RUT_MANDANTE', ''.$this->formateo_rut($val->mandante_rut) );
			    		    
			    $docx->addTemplateVariable('NOMBRE', strtoupper($val->nombres));
			    $docx->addTemplateVariable('APELLIDO_PATERNO', strtoupper($val->ap_pat));
			    $docx->addTemplateVariable('APELLIDO_MATERNO',strtoupper($val->ap_mat));
			    $docx->addTemplateVariable('RUT_DEMANDADO', ''.$this->formateo_rut($val->rut) );
			    
			    //$docx->addTemplateVariable('DIRECCION', strtoupper($val->direccion));
			    // $docx->addTemplateVariable('DIRECCION_NUMERO', strtoupper($val->direccion_numero));
			    				
				
			    $direccion_list = $this->direccion_m->get_by( array('id_cuenta'=>$val->id,'activo'=>'S') );
				//print_r($direccion_list);
			    $direccion = '';
			    $comuna = $val->comuna;
			    if( count($direccion_list)>0 ){
			    	$direccion = trim($direccion_list->direccion);
			    	if ($direccion_list->id_comuna>0){
				    	$com_direccion = $this->comunas_m->get_by( array('id'=>$direccion_list->id_comuna,'activo'=>'S'));
				    	if (count($com_direccion)==1){
				    		$comuna = $com_direccion->nombre;
				    	}
			    	}
			    }
				$docx->addTemplateVariable('DIRECCION', ''.strtoupper(rtrim(trim($direccion))));
			    $docx->addTemplateVariable('COMUNA', strtoupper($comuna));
				
				
				$direccion_list = $this->direccion_m->get_many_by( array('id_cuenta'=>$val->id,'activo'=>'S') );
                $d = 1;
                if( count($direccion_list)>0 ){
                    foreach ($direccion_list as $direcc){
                        $docx->addTemplateVariable('DIRECCION_'.$d, ''.strtoupper(trim($direcc->direccion)));
                        if ($direccion_list->id_comuna>0){
                            $com_direccion = $this->comunas_m->get_by( array('id'=>$direcc->id_comuna,'activo'=>'S'));
                            if (count($com_direccion)==1){
                                $docx->addTemplateVariable('COMUNA_'.$d, ''.strtoupper($com_direccion->nombre));
                            }
                        }
                        $d++;
                    }
                }
								
				$docx->addTemplateVariable('REPRESENTANTE_DEL_MANDANTE', strtoupper($val->mandante_nombre.' '.$val->mandante_apepat.' '.$val->mandante_apemat));
			     
			    $docx->addTemplateVariable('N_PAGARE', $n_pagare);
			    $docx->addTemplateVariable('MONTO_PAGARE', number_format($monto_deuda,0,',','.').'');
				$docx->addTemplateVariable('PAGARE_TOTAL_UF2', ''.number_format($total_pagare_uf,4,',','.'));
				$docx->addTemplateVariable('PAGARE_NOMBRE_AVAL', $pagare_nombre_aval);		    
			    
			    $docx->addTemplateVariable('UF', $uf.'');//
			    $docx->addTemplateVariable('UF_FECHA', $uf_fecha.'');//
			   
			    $monto_pagare_peso = number_format(($monto_deuda*$uf),0,',','.');
			    $docx->addTemplateVariable('MONTO_PAGARE_PESO', '$'.$monto_pagare_peso);

			    $docx->addTemplateVariable('FECHA', ''.date('d-m-Y'));
			    $docx->addTemplateVariable('FECHA_ACTUAL_EN_LETRAS', ''.$fecha_hoy_en_letras);
			    
			    $docx->addTemplateVariable('FECHA_UF', ''.$this->input->post('fecha'));
			    
			    $total_pagare_peso = ($uf*$total_pagare_uf);
			    $docx->addTemplateVariable('SALDO_DEUDA', '$'.number_format(abs($val->monto_deuda),0,',','.') );
			    $docx->addTemplateVariable('TOTAL_PAGARE_PESO', '$'.number_format($total_pagare_uf,0,',','.') );
				$docx->addTemplateVariable('TOTAL_PAGARE_PESO_UF', '$'.number_format($total_pagare_peso,0,',','.') );
			    $docx->addTemplateVariable('TOTAL_PAGARE_UF', ''.number_format($total_pagare_uf,4,',','.') );
			    
				if ($pagares!=''){
			    	$docx->replaceTemplateVariableByHTML('PAGARES', 'inline', $pagares.'');
			    }else{
			    	$docx->addTemplateVariable('PAGARES', '' );
			    }
				if ($pagares_uf!=''){
			    	$docx->replaceTemplateVariableByHTML('PAGARES_UF', 'inline', $pagares_uf.'');
			    }else{
			    	$docx->addTemplateVariable('PAGARES_UF', '' );
			    }
			    
				if($val->fecha_asignacion != '' || $val->fecha_asignacion != '0'){
			    	$docx->addTemplateVariable('DÍA_DE_ASIGNACION_DEL_PAGARE', date("d", strtotime($val->fecha_asignacion)) );
			    	$docx->addTemplateVariable('MES_DE_ASIGNACION_DEL_PAGARE', date("m", strtotime($val->fecha_asignacion)) );
			    	$docx->addTemplateVariable('ANO_DE_ASIGNACION_DEL_PAGARE', date("Y", strtotime($val->fecha_asignacion)) );
			    	$docx->addTemplateVariable('FECHA_DE_ASIGNACION_DEL_PAGARE', date("d/m/Y", strtotime($val->fecha_asignacion)) );
			    	$docx->addTemplateVariable('DÍA_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_asignacion,'d') );
			    	$docx->addTemplateVariable('MES_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_asignacion,'m') );
			    	$docx->addTemplateVariable('ANO_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_asignacion,'y') );

					$fecha_asignacion_dia_venc = date("d", strtotime($v->pagare_fecha_asignacion));
					$fecha_asignacion_mes_venc = strftime("%B", strtotime($v->pagare_fecha_asignacion));
					$fecha_asignacion_year_venc = date("Y", strtotime($v->pagare_fecha_asignacion));
					$asignacion_en_letras = strtolower($this->ValorEnLetras($fecha_asignacion_dia_venc,"").' de '.$fecha_asignacion_mes_venc.' de '.$this->ValorEnLetras($fecha_asignacion_year_venc,""));
					$docx->addTemplateVariable('FECHA_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', ' '.$asignacion_en_letras );
			    	
			    }else{
			    	$docx->addTemplateVariable('DÍA_DE_ASIGNACION_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('MES_DE_ASIGNACION_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('ANO_DE_ASIGNACION_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('FECHA_DE_ASIGNACION_DEL_PAGARE', '' );
			    	$docx->addTemplateVariable('FECHA_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', ' ' );
			    }		    
				
				if($val->fecha_vencimiento != '' || $val->fecha_vencimiento != '0'){
			    	$docx->addTemplateVariable('DÍA_DE_VENCIMIENTO_DEL_PAGARE', date("d", strtotime($val->fecha_vencimiento)) );
			    	$docx->addTemplateVariable('MES_DE_VENCIMIENTO_DEL_PAGARE', date("m", strtotime($val->fecha_vencimiento)) );
			    	$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_DEL_PAGARE', date("Y", strtotime($val->fecha_vencimiento)) );
			    	$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE', date("d/m/Y", strtotime($val->fecha_vencimiento)) );

			    	$docx->addTemplateVariable('DÍA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_vencimiento,'d') );
			    	$docx->addTemplateVariable('MES_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_vencimiento,'m') );
			    	$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_vencimiento,'y') );
			    	//$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_asignacion) );
			    	
			    	/* V3 */
					$fecha_vencimiento_dia_venc = date("d", strtotime($v->pagare_fecha_vencimiento));
					$fecha_vencimiento_mes_venc = strftime("%B", strtotime($v->pagare_fecha_vencimiento));
					$fecha_vencimiento_year_venc = date("Y", strtotime($v->pagare_fecha_vencimiento));

					$venc_en_letras = strtolower($this->ValorEnLetras($fecha_vencimiento_dia_venc,"").' de '.$fecha_vencimiento_mes_venc.' de '.$this->ValorEnLetras($fecha_vencimiento_year_venc,""));

					
					$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', ' '.$venc_en_letras );
					/* V3 */
			    	
			    }else{
			    	$docx->addTemplateVariable('DÍA_DE_VENCIMIENTO_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('MES_DE_VENCIMIENTO_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE', '' );
			    	$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', ' ' );
			    }
				
				
				$docx->addTemplateVariable('NOMBRE_R', strtoupper($val->representante_nombre ));
			    $docx->addTemplateVariable('APELLIDO_PATERNO_R', strtoupper($val->representante_apepat));
			    $docx->addTemplateVariable('APELLIDO_MATERNO_R', strtoupper($val->representante_apemat));
	
			    $docx->addTemplateVariable('DIRECCION_Y_NUMERO_R', strtoupper($val->representante_direccion.' '.$val->representante_direccion_n));
			    $docx->addTemplateVariable('COMUNA_R', strtoupper($val->representante_comuna));
			    $docx->addTemplateVariable('CIUDAD_R', strtoupper($val->representante_ciudad));
			    
			    $docx->addTemplateVariable('FECHA_ESCRITURA_PUBLICA', strtoupper($val->fecha_escritura_publica));
			    $docx->addTemplateVariable('NOTARIA', strtoupper($val->notaria));
			    $docx->addTemplateVariable('NUMERO_REPERTORIO', strtoupper($val->numero_repertorio));
			    
			    $docx->addTemplateVariable('FECHA_ESCRITURA_PERSONERIA', strtoupper($val->fecha_escritura_personeria));
			    $docx->addTemplateVariable('NOTARIA_PERSONERIA', strtoupper($val->notaria_personeria));
			    $docx->addTemplateVariable('TITULAR_PERSONERIA', strtoupper($val->titular_personeria));
			    
			    $docx->addTemplateVariable('FECHA_ESCRITURA_APODERADO', strtoupper($val->fecha_escritura_apoderado));
			    $docx->addTemplateVariable('NOTARIA_APODERADO', strtoupper($val->notaria_apoderado));
			    
			    $docx->addTemplateVariable('DEUDA_TOTAL', $val->monto_deuda);
			    $docx->addTemplateVariable('DEUDA_TOTAL_EN_LETRAS',strtolower($this->ValorEnLetras($val->monto_deuda)));
			    
			    $docx->addTemplateVariable('NOMBRE_DEMANDANTE', strtoupper($val->representante_nombre));
			    $docx->addTemplateVariable('APELLIDO_PATERNO_DEMANDANTE', strtoupper($val->representante_apepat));
			    $docx->addTemplateVariable('APELLIDO_MATERNO_DEMANDANTE', strtoupper($val->representante_apemat));
			    
			    $docx->addTemplateVariable('PROCURADOR', strtoupper($val->procurador_nombres.' '.$val->procurador_apellidos));
			    $docx->addTemplateVariable('RUT_PROCURADOR', ''.$this->formateo_rut($val->procurador_rut) );
			    
			    $id = $val->id;
			    	
				if (file_exists(base_url().'documentos/'.$nombre_archivo.'.docx')) {
					
					$nombre_archivo = uniqid().'__'.date("d-m-Y__H-i-s");
					
					$fields = array();
					$fields['idcuenta'] 		= $id;
					$fields['nombre_documento']	= $nombre_archivo.'.docx';
					$fields['id_etapa']			= $_POST['id_etapa'];
					$fields['fecha_crea']		= date("Y-m-d H:i:s");
					$fields['tipo_documento']	= $documentos_array[$tipo_documento];
					$fields['archivo_zip']		= $nombre_zip.'.zip';
				
					$this->documento_m->insert( $fields ,false,true);
					
					$docx->createDocx('./documentos/'.$nombre_archivo);
						
				}else{
					
					$fields = array();
					$fields['idcuenta'] 		= $id;
					$fields['nombre_documento']	= $nombre_archivo.'.docx';
					$fields['id_etapa']			= $_POST['id_etapa'];
					$fields['fecha_crea']		= date("Y-m-d H:i:s");
					$fields['tipo_documento']	= $documentos_array[$tipo_documento];
					$fields['archivo_zip']		= $nombre_zip.'.zip';
					
					$this->documento_m->insert($fields,false,true);
					
					$docx->createDocx('./documentos/'.$nombre_archivo);
				}
					
				if( $this->data['nodo']->nombre == 'fullpay'){
				   
					if ( isset($_POST['tipo_documento'])!=''){
						$plantilla_documento = $this->documento_plantilla_m->get_by( array('id'=>$_POST['tipo_documento']));
				    }
				
					if (count($plantilla_documento) > 0 && $plantilla_documento->id_etapa !='' && $plantilla_documento->id_etapa !='0'){
						$fields_save = array();
						$fields_save['id_etapa'] = $plantilla_documento->id_etapa;
						$this->cuentas_m->save_default($fields_save,$id);
					 
						$fields_save_etapa = array();
						$fields_save_etapa['id_cuenta'] = $id;
						$fields_save_etapa['id_etapa'] = $plantilla_documento->id_etapa;
						$fields_save_etapa['fecha_etapa']= date('Y-m-d');
						$fields_save_etapa['id_administrador'] = $this->session->userdata('usuario_id');
						$this->cuentas_etapas_m->save_default($fields_save_etapa,'');
					}
				}								
				
				$this->data['exito'] = 'Se ha creado exitosamente los documentos.';
				
				$zip->addFile('./documentos/'.$nombre_archivo.'.docx',$nombre_archivo.'.docx');
				
			}
	/*echo $this->db->last_query();
				echo '<pre>';
				print_r($pagares_array);
				echo '</pre>';
				die();*/
			$zip->close();
			//echo  'test_'.$total_pagare_uf.'<BR>';
			//echo 're';
			//die();
			redirect('admin/doc/all');
		}else{
			
			$this->data['estados_cuenta'] = $this->estados_cuenta_m->get_many_by(array('activo'=>'S'));
			$this->data['mandantes'] = $this->mandantes_m->get_many_by(array('activo'=>'S'));
			$this->data['etapas'] = $this->etapas_m->get_many_by(array('activo'=>'S'));
			
			
			$this->data['plantilla'] = 'doc/generar'; 
			$this->load->view ( 'backend/index', $this->data );
		}		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	public function generardoc() {

		//$this->output->enable_profiler(TRUE);
		/*$documentos_array['demanda_ejecutiva_pagare_falabella.docx'] = 'Demanda Ejecutiva PagarÃ© Falabella';
		$documentos_array['v3_demanda_ejecutiva_pagare_estudiantes_gtia_estatal.docx'] = 'Demanda Ejecutiva PagarÃ© Estudiantes GarantÃ­a Estatal';
		$documentos_array['demanda_cedida_cae_con_exhorto.docx'] = 'Demanda Cedida CAE con exhorto';
		$documentos_array['demanda_cedida_cae_sin_exhorto.docx'] = 'Demanda Cedida CAE sin exhorto';
		$documentos_array['demanda_propia_cae_con_exhorto.docx'] = 'Demanda Propia CAE con exhorto';
		$documentos_array['demanda_propia_cae_sin_exhorto.docx'] = 'Demanda Propia CAE sin exhorto';
		
		$documentos_array['nvo_formato_acompaÃ±a_documento.docx'] = 'Nuevo Formato AcompaÃ±a Documento';
		$documentos_array['nvo_formato_certificado.docx'] = 'Nuevo Formato Cetificado';*/
		
		$doc = $this->documento_plantilla_m->get_all();
		foreach($doc as $key=>$val){
			$documentos_array[$val->path]= $val->nombre_documento;
		}
		//print_r($doc);
		$this->data['documentos'] = $documentos_array;
		
		if (is_numeric($this->input->post('tipo_documento'))){
			$templates = $this->documento_plantilla_m->get_by(array('id'=>$this->input->post('tipo_documento')));
			$tipo_documento = $templates->path;
		} else {
			$tipo_documento = $this->input->post('tipo_documento');
		}
		//echo $tipo_documento;
		
		//111
		
	    if ($this->input->post('fecha_asignacion') != ''){
			$where['cta.fecha_asignacion']	= $this->input->post('fecha_asignacion');
		}
		
		if ($this->input->post('mandante') != ''){
			$where['cta.id_mandante']	= $this->input->post('mandante');
		}
		
		if ($this->input->post('estado_cuenta') != ''){
			$where['cta.id_estado_cuenta']	= $this->input->post('estado_cuenta');
		}
				
		if ($this->input->post('id_etapa_original') != ''){
			$where['cta.id_etapa']	= $this->input->post('id_etapa_original');
		}
		
		if ($this->input->post('tipo_demanda') != ''){
			$where['cta.tipo_demanda']	= $this->input->post('tipo_demanda');
		}
		
		if ($this->input->post('exorto') != ''){
			$where['cta.exorto']	= $this->input->post('exorto');
		}
		
		if ($this->input->post('ruts') != ''){
			$ruts = explode(',',$this->input->post('ruts'));
			$this->db->where_in('usr.rut', $ruts);
		}
		
		if ($this->input->post('id_cuenta') != ''){
			$where['cta.id']	= $this->input->post('id_cuenta');
		}

		
		//$where['cta.id']		=	$id;
		$where['cta.activo']	=	'S';
		
		$select = array();
		$select[] = 'cta.id AS id';
		$select[] = 'cta.rol AS rol';
		$select[] = 'cta.activo AS activo';
		$select[] = 'cta.publico AS publico';
		$select[] = 'cta.posicion AS posicion';
		$select[] = 'cta.id_procurador';
		$select[] = 'pagare.n_pagare AS n_pagare';
		$select[] = 'pagare.fecha_vencimiento AS fecha_vencimiento';
		$select[] = 'cta.fecha_asignacion AS fecha_asignacion';
		$select[] = 'cta.monto_demandado AS monto_demandado';
		$select[] = 'cta.monto_deuda AS monto_deuda';
		$select[] = 'cta.id_estado_cuenta AS id_estado_cuenta';
		$select[] = 'cta.id_mandante AS field_categoria';
		
		$select[] = 'cta.fecha_escritura_personeria AS fecha_escritura_personeria';
		$select[] = 'cta.notaria_personeria AS notaria_personeria';
		$select[] = 'cta.titular_personeria AS titular_personeria';
		
		$select[] = 'usr.nombres AS nombres';
		$select[] = 'usr.ap_pat AS ap_pat';
		$select[] = 'usr.ap_mat AS ap_mat';
		$select[] = 'usr.rut AS rut';
		//$select[] = 'usr.direccion AS direccion';
		//$select[] = 'usr.direccion_numero AS direccion_numero';
		$select[] = 'usr.ciudad AS ciudad';
		$select[] = 'cd.direccion AS direccion';
		//$select[] = 'cd_com.nombre AS comuna';
		
		$select[] = 'com.nombre AS comuna';
		
		$select[] = 'mand.razon_social';
		$select[] = 'mand.rut AS mandante_rut';
		$select[] = 'mand.representante_nombre';
		$select[] = 'mand.representante_apepat';
		$select[] = 'mand.representante_apemat';
		$select[] = 'mand.representante_comuna';
		$select[] = 'mand.representante_ciudad';
		$select[] = 'mand.representante_direccion';
		$select[] = 'mand.representante_direccion_n';
		$select[] = 'mand.fecha_escritura_publica';
		$select[] = 'mand.numero_repetorio';
		$select[] = 'mand.notaria';
		$select[] = 'mand.fecha_escritura_apoderado';
		$select[] = 'mand.notaria_apoderado';
		
		$select[] = 'adm.rut_procurador AS procurador_rut';
		$select[] = 'adm.nombres AS procurador_nombres';
		$select[] = 'adm.apellidos AS procurador_apellidos';
		
		$select[] = 'tip.tipo AS tipo_producto';
		$select[] = 'SUM(pag.monto_pagado) AS total_pagado';
		$select[] = 'trib_com.tribunal AS jurisdiccion';
		
		$select[] = 'trib.tribunal AS distrito';
		$select[] = 'dist.tribunal AS juzgado';
		
		$query = $this->db->select( $select )
                	
				 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
				 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	
				 ->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")	
				 ->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S'","left")
				 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
				 ->join("s_comunas com", "com.id = usr.id_comuna","left")
				 ->join("s_tribunales trib", "trib.id = cta.id_tribunal","left")
				 ->join("s_tribunales dist", "dist.id = cta.id_distrito","left")
				 ->join("pagare pagare", "pagare.idcuenta = cta.id","left")	
				 ->join("2_cuentas_direccion cd", "cd.id_cuenta = cta.id","left")	
				 ->join("s_comunas cd_com", "cd_com.id = cd.id_comuna","left")
				 ->join("s_tribunales trib_com","trib_com.id=cd_com.id_tribunal_padre","left")
				 ->where($where)
				 ->order_by("id_mandante", "desc")
				 ->order_by("cta.fecha_asignacion", "desc")
				 ->group_by("cta.id")
 				 ->get("0_cuentas cta");
				
 				 $datos = $query->result();
		/*echo $this->db->last_query();
		echo '<pre>';
		print_r($datos);
		echo '</pre>';
		*/
		
		
		if(count($datos)>0){

			$zip = new ZipArchive();
			$nombre_zip = uniqid().'__'.date("d-m-Y__H-i-s");
			$filename = './documentos_zip/'.$nombre_zip.'.zip';
			
			if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
			    exit("cannot open <$filename>\n");
			}
	
			foreach($datos as $key=>$val){
				
				//
				$c1[] = $this->pagare_m->get_columns();
				$c2[] = $this->tipo_productos_m->get_columns();
				$c = array_merge($c1, $c2);
				foreach ($c as $campo) {
					foreach ($campo as $dato) {
						$cols[] = $dato;
					}
				}
				$this->db->order_by('pa.fecha_asignacion', 'DESC');
				if($val->id !=''){
					$this->db->where('pa.idcuenta', $val->id);
				}
				$this->db->where('pa.activo', 'S');
				$this->db->select($cols);
				$this->db->from('pagare pa');
				$this->db->join('s_tipo_productos tp', 'tp.id = pa.id_tipo_producto','left');
				$query = $this->db->get();
				
				$pagares_array = array();
				$pagares_array = $query->result();
				$monto_deuda = 0;
				$pagares = '';
				$pagares_uf = '';
				
				$total_pagare_peso = 0;

				$meses_espanol = array( '1'=>'enero','2'=>'febrero','3'=>'marzo','4'=>'abril','5'=>'mayo','6'=>'junio','7'=>'julio','8'=>'agosto','9'=>'septiembre','10'=>'octubre','11'=>'noviembre','12'=>'diciembre');
				
				
				require_once './doc_library/classes/CreateDocx.inc';
				$docx = new CreateDocx();
				$docx->addTemplate('./documentos_base/'.$tipo_documento);
				
				$bienes = $this->bienes_m->get_many_by(array('id_cuenta'=>$val->id,'activo'=>'S'));
				if (count($bienes)>0){
					$r=1;
					foreach ($bienes as $kb=>$val_bienes){
						$tipos_vehiculos = array('0'=>'Automovil','1'=>'Moto','2'=>'Furgón','3'=>'Camioneta','4'=>'Stationwagon','5'=>'Otro');
						if ($val_bienes->tipo==1){
							$docx->addTemplateVariable('VEHICULO_TIPO_'.$r, $tipos_vehiculos[$val_bienes->tipo_vehiculo]);
							$docx->addTemplateVariable('VEHICULO_MARCA_'.$r, $val_bienes->marca);
							$docx->addTemplateVariable('VEHICULO_MODELO_'.$r, $val_bienes->modelo);
							$docx->addTemplateVariable('VEHICULO_N_MOTOR_'.$r, $val_bienes->n_motor);
							$docx->addTemplateVariable('VEHICULO_COLOR_'.$r, $val_bienes->color);
							$docx->addTemplateVariable('VEHICULO_INSCRIPCION_'.$r, $val_bienes->inscripcion);
							$r++;
						}
					}
				}
                $n_pagare = '';
				if (count($pagares_array)>0){
					
					$xmlSource = "http://indicadoresdeldia.cl/webservice/indicadores.xml";
					$xml = simplexml_load_file($xmlSource);
					
					$uf = str_replace(array('$','.',','),array('','','.'),$xml->indicador->uf);
					$uf_fecha = date('d/m/Y');
					
					$total_pagare_uf = 0;
					
					$r = 1;
					$monto_deuda = 0;
					
					$acentos = array('á','Á','é','É','í','Í','ó','Ó','ú','Ú','ñ','Ñ','º');
					$acutes = array('&aacute;','&Aacute;','&eacute;','&Eacute;','&iacute;','&Iacute;','&oacute;','&Oacute;','&uacute;','&Uacute;','&ntilde;','&Ntilde;','&deg;');
						
					
					//echo count($pagares_array);
					foreach ($pagares_array as $k=>$v){
						
						//echo $monto_deuda.'<br>';
						//$pagares.='â€¢	PagarÃ© NÂº '.$v->pagare_n_pagare.', suscrito con fecha '.date("d/m/Y", strtotime($v->pagare_fecha_asignacion)).', ';
						//$pagares.='por la suma de '.$v->pagare_monto_deuda.' Unidades de Fomento, por concepto de capital.<br>';
						
						$vencimiento_dia = '';
						$vencimiento_mes = '';
						$vencimiento_year = '';


						if( strtotime($v->pagare_fecha_asignacion) > 0){
							$asignacion = date("d/m/Y", strtotime($v->pagare_fecha_asignacion));
							$asignacion_dia = date("d", strtotime($v->pagare_fecha_asignacion));
							$asignacion_mes = date("n", strtotime($v->pagare_fecha_asignacion));
							$asignacion_year = date("Y", strtotime($v->pagare_fecha_asignacion));
						}else{
							$asignacion = '_/_/_';
						}
						//echo $v->pagare_fecha_vencimiento.'----------<br>';
						if( strtotime($v->pagare_fecha_vencimiento) > 0 ){
							$vencimiento = date("d/m/Y", strtotime($v->pagare_fecha_vencimiento));
							$vencimiento_dia = date("d", strtotime($v->pagare_fecha_vencimiento));
							$vencimiento_mes = date("n", strtotime($v->pagare_fecha_vencimiento));
							$vencimiento_year = date("Y", strtotime($v->pagare_fecha_vencimiento));
						}else{
							$vencimiento = '_/_/_';
						}
						
						$suscrito = date('d/m/Y');
						
						$suscrito_en_letras = strtolower($this->ValorEnLetras(date('d'),"").' de '.$meses_espanol[date("n")].' de '.$this->ValorEnLetras(date('Y'),""));
						
						
						$vencimiento_mes_new = '';
						$asignacion_mes_new = '';
						
						if (array_key_exists($asignacion_mes,$meses_espanol)){
							$asignacion_mes_new = $meses_espanol[$asignacion_mes];
						}
						if (array_key_exists($vencimiento_mes,$meses_espanol)){
							$vencimiento_mes_new = $meses_espanol[$vencimiento_mes];
						}
						$asignacion_en_letras = strtolower($this->ValorEnLetras($asignacion_dia,"").' de '.$asignacion_mes_new.' de '.$this->ValorEnLetras($asignacion_year,""));
						$vencimiento_en_letras = strtolower($this->ValorEnLetras($vencimiento_dia,"").' de '.$vencimiento_mes_new.' de '.$this->ValorEnLetras($vencimiento_year,""));
						//echo ' -> '.$asignacion_en_letras;
						//echo ' -> '.$vencimiento_en_letras;
					
						
						//$pagares.='- Pagar&eacute; de fecha '.$suscrito_en_letras.' suscrito(s) con fecha '.$asignacion_en_letras.' por el monto '.number_format($v->pagare_monto_deuda,4,',','.').' Unidades de Fomento, con vencimiento el '.$vencimiento_en_letras.'<br>';
						
						
						
						$docx->addTemplateVariable('DÍA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS_'.$r, strtolower($this->ValorEnLetras($vencimiento_dia)) );
			    		$docx->addTemplateVariable('MES_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS_'.$r, strtolower($meses_espanol[$vencimiento_mes]) );
			    		$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS_'.$r, strtolower($this->ValorEnLetras($vencimiento_year)) );
						
						$docx->addTemplateVariable('MONTO_DEUDA_DEL_PAGARE_'.$r, '$'.@number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.') );
						$docx->addTemplateVariable('MONTO_DEUDA_UF_DEL_PAGARE_'.$r, @number_format($v->pagare_monto_deuda,3,',','.') );
						
						$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_RESTANTES_CUOTAS_DEL_PAGARE_'.$r, date("d/m/Y", strtotime($v->pagare_vencimiento_restantes_cuotas)) );
						$docx->addTemplateVariable('DIA_DE_VENCIMIENTO_RESTANTES_CUOTAS_DEL_PAGARE_'.$r, date("d", strtotime($v->pagare_vencimiento_restantes_cuotas)) );
			    		$docx->addTemplateVariable('MES_DE_VENCIMIENTO_RESTANTES_CUOTAS_DEL_PAGARE_'.$r, date("m", strtotime($v->pagare_vencimiento_restantes_cuotas)) );
			    		$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_RESTANTES_CUOTAS_DEL_PAGARE_'.$r, date("Y", strtotime($v->pagare_vencimiento_restantes_cuotas)) );

						$docx->addTemplateVariable('FECHA_DE_ASIGNACION_'.$r, date("d/m/Y", strtotime($v->pagare_fecha_vencimiento)) );
						$docx->addTemplateVariable('DIA_DE_ASIGNACION_'.$r, date("d", strtotime($v->pagare_fecha_vencimiento)) );
			    		$docx->addTemplateVariable('MES_DE_ASIGNACION_'.$r, date("m", strtotime($v->pagare_fecha_vencimiento)) );
			    		$docx->addTemplateVariable('ANO_DEASIGNACION_'.$r, date("Y", strtotime($v->pagare_fecha_vencimiento)) );

			    		$docx->addTemplateVariable('TASA_INTERES_DEL_PAGARE_'.$r, $v->pagare_tasa_interes);
						$docx->addTemplateVariable('NUMERO_CUOTAS_DEL_PAGARE_'.$r, $v->pagare_numero_cuotas);
						$docx->addTemplateVariable('VALOR_PRIMERA_CUOTA_DEL_PAGARE_'.$r, number_format($v->pagare_valor_primera_cuota,0,',','.'));
						$docx->addTemplateVariable('VALOR_ULTIMA_CUOTA_DEL_PAGARE_'.$r, number_format($v->pagare_valor_ultima_cuota,0,',','.'));


                        $docx->addTemplateVariable('N_PAGARE_'.$r, $v->pagare_n_pagare);
                        if ($r==1){$n_pagare = $v->pagare_n_pagare;}

						$glosa_saldo_deudor = '';
						$glosa_saldo_deudor_uf = '';
						$resultado_saldo_deudor = 0;
						if ($v->pagare_numero_cuotas>0){
							//echo $v->pagare_monto_deuda.'-(('.str_replace(',','.',$v->pagare_monto_deuda).'/'.$v->pagare_numero_cuotas.')*'.$v->pagare_ultima_cuota_pagada.')<br>';
							$resultado_saldo_deudor = $v->pagare_monto_deuda-(($v->pagare_monto_deuda/$v->pagare_numero_cuotas)*$v->pagare_ultima_cuota_pagada);

							$glosa_saldo_deudor= "La operación aritmética que permite arribar a la suma de $".@number_format(str_replace(array(',','.'),'',round($resultado_saldo_deudor,0)),0,',','.')."; se desprende de dividir el monto del pagaré ";
							$glosa_saldo_deudor.= "de ".'$'.@number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.')." por ".$v->pagare_numero_cuotas." ";
							$glosa_saldo_deudor.= "cuotas pactadas; y luego multiplicar el resultado por ".$v->pagare_ultima_cuota_pagada." que corresponde a la cantidad ";
							$glosa_saldo_deudor.= "de cuotas impagas.";
							$glosa_saldo_deudor = str_replace($acentos,$acutes,$glosa_saldo_deudor);
							
							$glosa_saldo_deudor_uf= "La operación aritmética que permite arribar a la suma de ".@number_format($resultado_saldo_deudor,4,',','.')." Unidades de Fomento; se desprende de dividir el monto del pagaré ";
							$glosa_saldo_deudor_uf.= "de ".@number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.')." Unidades de Fomento por ".$v->pagare_numero_cuotas." ";
							$glosa_saldo_deudor_uf.= "cuotas pactadas; y luego multiplicar el resultado por ".$v->pagare_ultima_cuota_pagada." que corresponde a la cantidad ";
							$glosa_saldo_deudor_uf.= "de cuotas impagas.";
							$glosa_saldo_deudor_uf = str_replace($acentos,$acutes,$glosa_saldo_deudor_uf);
							
							$docx->replaceTemplateVariableByHTML('MONTO_SALDO_DEUDA_DEL_PAGARE_'.$r, 'inline', $glosa_saldo_deudor.'');
							$docx->replaceTemplateVariableByHTML('MONTO_SALDO_DEUDA_DEL_PAGARE_UF_'.$r, 'inline', $glosa_saldo_deudor_uf.'');
						}
						//$docx->addTemplateVariable('MONTO_SALDO_DEUDA_DEL_PAGARE_'.$r, $glosa_saldo_deudor);

						//echo '--'.$glosa_saldo_deudor;
						
						if ($v->pagare_id_tipo_producto==5){

							$monto_deuda+= $v->pagare_saldo_deuda;
							$total_pagare_uf+=str_replace(',','.',$v->pagare_saldo_deuda);
						} 
						elseif($v->pagare_tasa_interes>0) 
						{
							$monto_deuda+= $v->pagare_monto_deuda;
							$total_pagare_uf+=$v->pagare_tasa_interes;
						} 
						else 
						{
							//print_r($v);
							$monto_deuda+= $v->pagare_monto_deuda;
							$total_pagare_uf+=str_replace(',','.',$v->pagare_monto_deuda);
						}
						
						$pagare_numero_cuotas = $v->pagare_numero_cuotas;
						$pagare_valor_primera_cuota = number_format($v->pagare_valor_primera_cuota,0,',','.');
						$pagare_valor_ultima_cuota = number_format($v->pagare_valor_ultima_cuota,0,',','.');
						if ($v->pagare_vencimiento_primera_cuota!=''){
							$pagare_vencimiento_primera_cuota = date('d/m/Y',strtotime($v->pagare_vencimiento_primera_cuota));
						} else {
							$pagare_vencimiento_primera_cuota = '';
						}
						if ($v->pagare_vencimiento_restantes_cuotas!=''){
							$pagare_vencimiento_restantes_cuotas = date('d/m/Y',strtotime($v->pagare_vencimiento_restantes_cuotas));
						} else {
							$pagare_vencimiento_restantes_cuotas = '';
						}
						$pagare_nombre_aval = $v->pagare_nombre_aval;
						
						
						$pagares.='- Pagar&eacute; de fecha '.$asignacion_en_letras.' suscrito(s) por el monto $'.@number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').', con vencimiento el '.$vencimiento_en_letras.'.<br>';
						$pagares_uf.='- Pagar&eacute; de fecha '.$asignacion_en_letras.' suscrito(s) por el monto '.str_replace('.',',',$v->pagare_monto_deuda).' Unidades de Fomento, con vencimiento el '.$vencimiento_en_letras.'.<br>';
						
						
						$pagare_tipo_producto = '';
						
						if ($v->pagare_id_tipo_producto==6){
							/*$pagare_tipo_producto.='<p style="font-family:Times; font-size:12pt">';
							$pagare_tipo_producto.='- Pagaré Nº <strong>'.$v->pagare_n_pagare.'</strong>, suscrito con fecha '.$asignacion_en_letras;
							$pagare_tipo_producto.=', por la suma de <strong>$'.number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').'</strong>.- suscrito por SCOTIABANK, a su vez en representación';
							$pagare_tipo_producto.=' de <strong>'.strtoupper($val->nombres).' '.strtoupper($val->ap_pat).' '.strtoupper($val->ap_mat).'</strong>, en virtud del mandato conferido por este(a) último(a)';
							$pagare_tipo_producto.=' a SCOTIABANK en el <strong>TÍTULO "DIRECTORES O APODERADOS GENERALES", Numeral 50 (cincuenta), del denominado';
							$pagare_tipo_producto.=' "CONTRATOS CLIENTE BANCA DE PERSONA"</strong> que establece <em>"50. Para facilitar el cobro de lo adeudado';
							$pagare_tipo_producto.=' en virtud del Contrato celebrado mediante el presente instrumento, sin ánimo de novar y para completar';
							$pagare_tipo_producto.=' el título ejecutivo, el Cliente, en adelante el mandante, viene, por el presente instrumento, en conferir';
							$pagare_tipo_producto.=' poder especial al Banco, para que éste por sí o a través de un tercero especialmente designado al efecto,';
							$pagare_tipo_producto.=' a su nombre y representación proceda a autorizar ante Notario Público, uno o más Pagarés a la vista y a la';
							$pagare_tipo_producto.=' orden del Banco por la suma a que asciendan los créditos, giros y/o solicitudes de pagos, transferencias';
							$pagare_tipo_producto.=' de fondos y cualquier otra obligación que se contraiga con motivo del presente instrumento. En la ejecución';
							$pagare_tipo_producto.=' del presente mandato, el Banco, por sí o a través de un Tercero que designe, queda especialmente facultado';
							$pagare_tipo_producto.=' para realizar las gestiones que a continuación se indican, sin que tal enunciación tenga carácter';
							$pagare_tipo_producto.=' taxativo: a) El Banco, podrá suscribir en nombre y representación del mandante, el o los pagarés que sean necesarios';
							$pagare_tipo_producto.=' y hacer autorizar ante Notario la firma de los suscriptores."</em> etc, y demás cláusulas establecidas en el referido contrato';
							$pagare_tipo_producto.=', las que forman parte de un todo, el cual se acompaña en el Nº 3 del Segundo Otrosí de esta demanda. La personería otorgada';
							$pagare_tipo_producto.=' por el Banco a los señores Oscar Urbano Moreno y Rodrigo Marcial Tapia Mena para que actúen como terceros especialmente';
							$pagare_tipo_producto.=' designado al efecto consta en la Copia de escritura pública correspondiente a "Acta Nº 2.375. Sesión ordinaria de';
							$pagare_tipo_producto.=' Directorio de Fecha 23 de Septiembre de 2014" la cual se acompaña en el Nº 6 del Segundo Otrosí. Es del caso US';
							$pagare_tipo_producto.='., que el deudor no ha pagado a ésta fecha el presente pagaré, por lo que adeuda a mi representado el total de la obligación';
							$pagare_tipo_producto.=', que exclusivamente en capital, y según lo establecido en el artículo 30 de la Ley 18.010, asciende al saldo insoluto';
							$pagare_tipo_producto.='  de capital de <strong>$'.number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').'</strong>.- más intereses ';
							$pagare_tipo_producto.='correspondientes que deberán liquidarse en su respectivo momento, al igual que las costas.-';
							$pagare_tipo_producto.='</p>';*/
							
							$pagare_tipo_producto.='<p style="font-family:Times; font-size:12pt">';
							$pagare_tipo_producto.='- Pagaré número <strong>'.$v->pagare_n_pagare.'</strong>, suscrito con fecha '.$asignacion_en_letras;
							$pagare_tipo_producto.=', por la suma de <strong>$'.number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').'</strong>.- suscrito por SCOTIABANK, a su vez en representación';
							$pagare_tipo_producto.=' de <strong>'.strtoupper($val->nombres).' '.strtoupper($val->ap_pat).' '.strtoupper($val->ap_mat).'</strong>, en virtud del mandato conferido por este(a) último(a)';
							$pagare_tipo_producto.=' a SCOTIABANK en el <strong>"CONTRATO CLIENTE BANCA DE PERSONA"</strong>, el cual se acompaña en el N&deg; 3 del Segundo Otrosí de esta demanda, en';
							$pagare_tipo_producto.=' donde se establece que para facilitar el cobro de lo adeudado en virtud de dicho contrato, y para efectos de completar el título ejecutivo,';
							$pagare_tipo_producto.=' el Cliente, confiere poder especial al Banco, para que éste por sí o a través de un tercero especialmente';
							$pagare_tipo_producto.=' designado al efecto, a su nombre y representación proceda a autorizar ante Notario Público,';
							$pagare_tipo_producto.=' uno o más pagarés a la vista y a la orden del Banco por la suma a que asciendan los créditos, giros y/o solicitudes de pagos y cualquier otra obligación';
							$pagare_tipo_producto.=' que se contraiga. La personería otorgada por el Banco a los firmantes del los pagares para que actúen como terceros especialmente';
							$pagare_tipo_producto.=' designado al efecto consta en la Copia de escritura pública correspondiente a "Acta N&deg; 2.375. Sesión ordinaria de Directorio de Fecha 23 de Septiembre de 2014"';
							$pagare_tipo_producto.=' la cual se acompaña en el N&deg; 6 del Segundo Otrosí. Es del caso US., que el deudor no ha pagado a ésta fecha el presente pagaré,';
							$pagare_tipo_producto.=' por lo que adeuda a mi representado el total de la obligación que exclusivamente en capital, y según lo establecido en el artículo 30 de la Ley 18.010';
							$pagare_tipo_producto.=' asciende al saldo insoluto de capital de';
							$pagare_tipo_producto.=' <strong>$'.number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').'</strong>.-';
							$pagare_tipo_producto.=' más costas e intereses los cuales deberán liquidarse en su respectivo momento.-';
							$pagare_tipo_producto.='</p>';
							
							$pagare_tipo_producto = str_replace($acentos,$acutes,$pagare_tipo_producto);
		
							$docx->replaceTemplateVariableByHTML('PAGARE_TIPO_PRODUCTO_'.$r, 'inline', $pagare_tipo_producto.'');

						}elseif ($v->pagare_id_tipo_producto==5){
						
							$pagare_tipo_producto.='<p style="font-family:Times; font-size:12pt">';
							$pagare_tipo_producto.='Pagaré número N&deg; <strong>'.$v->pagare_n_pagare.'</strong>, suscrito con fecha <strong>'.$vencimiento_en_letras.'</strong>, ';
							$pagare_tipo_producto.='por la suma de $ <strong>'.number_format(str_replace(array(',','.'),'',$v->pagare_monto_deuda),0,',','.').'</strong>.- cantidad que se obligo a pagar con un interés de <strong>'.$v->pagare_tasa_interes.'%</strong> mensual ';
							$pagare_tipo_producto.='vencido, pagadero en <strong>'.$v->pagare_numero_cuotas.'</strong> cuotas, mensuales y sucesivas, por un valor de <strong>$'.number_format($v->pagare_valor_primera_cuota,0,',','.').'</strong>.- cada ';
							$pagare_tipo_producto.='una, excepto la última por un valor de <strong>$'.number_format($v->pagare_valor_ultima_cuota,0,',','.').'</strong>.-, siendo el primer ';
							$pagare_tipo_producto.='vencimiento el día <strong>'.date("d/m/Y", strtotime($v->pagare_vencimiento_primera_cuota)).'</strong>, y las restantes los días <strong>'.date("d", strtotime($v->pagare_vencimiento_restantes_cuotas)).'</strong> de ';
							$pagare_tipo_producto.='cada mes, o del último mes del respectivo periodo.  La parte ejecutada pagó hasta la cuota <strong>N&deg; <strong>'.$v->pagare_ultima_cuota_pagada.'</strong></strong>';
							$pagare_tipo_producto.=' con vencimiento el día <strong>'.date("d/m/Y", strtotime($v->pagare_fecha_pago_ultima_cuota)).'</strong>, en donde pagó una cuota total de <strong>$'.number_format($v->pagare_valor_ultima_cuota_pagada,0,',','.').'</strong> y dejando ';
							$pagare_tipo_producto.='un saldo pendiente como capital insoluto efectivamente adeudado a pagar de <strong>$'.number_format($v->pagare_saldo_deuda,0,',','.').'</strong>, lo cual ';
							$pagare_tipo_producto.='consta en la respectiva fila del cronograma de pago adjuntado. No obstante la parte ejecutada ';
							$pagare_tipo_producto.='dejo de servir la deuda a mi representado, sin pagar desde la cuota N&deg; <strong>'.($v->pagare_ultima_cuota_pagada+1).'</strong>,  por lo que esta parte a contar de ';
							$pagare_tipo_producto.='la fecha de presentación de esta demanda ha decidido hacer exigible el total de la obligación, que exclusivamente ';
							$pagare_tipo_producto.='en capital, y según lo establecido en el artículo 30 de la Ley 18.010, asciende al saldo de capital insoluto ';
							$pagare_tipo_producto.='antes señalado de <strong>$'.number_format($v->pagare_saldo_deuda,0,',','.').'</strong>.- más intereses correspondientes que deberán liquidarse en su respectivo momento, al igual que las costas.-';
							$pagare_tipo_producto.='</p>';
							$pagare_tipo_producto = str_replace($acentos,$acutes,$pagare_tipo_producto);
							$docx->replaceTemplateVariableByHTML('PAGARE_TIPO_PRODUCTO_'.$r, 'inline', $pagare_tipo_producto.'');
						} 
						else 
						{
							$xx = '';
							$docx->addTemplateVariable('PAGARE_TIPO_PRODUCTO_'.$r, $xx);
						}
						
						//$pagares.='- Pagar&eacute; de fecha '.$suscrito.'  suscrito(s) con fecha '.$asignacion.' por el monto '.number_format($v->pagare_monto_deuda,2,',','.').' Unidades de Fomento, con vencimiento el '.$vencimiento;		
						$r++;
					}
				}
				
				
				for ($sss=$r;$sss<=6;$sss++){
					$xx = '';
					$docx->addTemplateVariable('PAGARE_TIPO_PRODUCTO_'.$sss, $xx);
				}
				
				if( $pagares != ''){
					$pagares = '<p style="font-family:Times; font-size:12pt">'.$pagares.'</p>';
				}
				//echo '==>'.$pagares;
				
				setlocale(LC_ALL, 'es_ES');
				$fecha_hoy_en_letras = strtolower($this->ValorEnLetras(date('d'),"").' de '.strftime("%B", strtotime(date('d-m-Y'))).' de '.$this->ValorEnLetras(date('Y'),""));
				
				$nombre_archivo = uniqid().'__'.date("d-m-Y__H-i-s");
				
				if ($this->data['nodo']->nombre=='swcobranza'){
					$docx->addTemplateVariable('JUZGADO', ''.str_replace('Civil','',$val->distrito));
					$docx->addTemplateVariable('DISTRITO', ''.$val->juzgado);
				}
				else {
					$docx->addTemplateVariable('DISTRITO', ''.str_replace('Civil','',$val->distrito));
					$docx->addTemplateVariable('JUZGADO', ''.$val->juzgado);
				}
				
				$docx->addTemplateVariable('ROL', ''.$val->rol);
				$docx->addTemplateVariable('JURISDICCION', ''.$val->jurisdiccion);
			    
			    $docx->addTemplateVariable('NOMBRE_MANDANTE', $val->razon_social);
			    $docx->addTemplateVariable('RUT_MANDANTE', ''.$this->formateo_rut($val->mandante_rut) );
			    		    
			    $docx->addTemplateVariable('NOMBRE', strtoupper($val->nombres));
			    $docx->addTemplateVariable('APELLIDO_PATERNO', strtoupper($val->ap_pat));
			    $docx->addTemplateVariable('APELLIDO_MATERNO',strtoupper($val->ap_mat));
			    $docx->addTemplateVariable('RUT_DEMANDADO', ''.$this->formateo_rut($val->rut) );
			    
			    //$docx->addTemplateVariable('DIRECCION', strtoupper($val->direccion));
			    // $docx->addTemplateVariable('DIRECCION_NUMERO', strtoupper($val->direccion_numero));
			    
			    $direccion_list = $this->direccion_m->get_by( array('id_cuenta'=>$val->id,'activo'=>'S') );
			    $direccion = '';
			    $comuna = $val->comuna;
			    if( count($direccion_list)>0 ){
			    	$direccion = trim($direccion_list->direccion);
			    	if ($direccion_list->id_comuna>0){
				    	$com_direccion = $this->comunas_m->get_by( array('id'=>$direccion_list->id_comuna,'activo'=>'S'));
				    	if (count($com_direccion)==1){
				    		$comuna = $com_direccion->nombre;
				    	}
			    	}
			    }
				$docx->addTemplateVariable('DIRECCION', ''.strtoupper($direccion));
			    $docx->addTemplateVariable('COMUNA', strtoupper($comuna));

                $direccion_list = $this->direccion_m->get_many_by( array('id_cuenta'=>$val->id,'activo'=>'S') );
                $d = 1;
                if( count($direccion_list)>0 ){
                    foreach ($direccion_list as $direcc){
                        $docx->addTemplateVariable('DIRECCION_'.$d, ''.strtoupper(trim($direcc->direccion)));
                        if ($direccion_list->id_comuna>0){
                            $com_direccion = $this->comunas_m->get_by( array('id'=>$direcc->id_comuna,'activo'=>'S'));
                            if (count($com_direccion)==1){
                                $docx->addTemplateVariable('COMUNA_'.$d, ''.strtoupper($com_direccion->nombre));
                            }
                        }
                        $d++;
                    }
                }

				$docx->addTemplateVariable('REPRESENTANTE_DEL_MANDANTE', strtoupper($val->mandante_nombre.' '.$val->mandante_apepat.' '.$val->mandante_apemat));
			     
			    $docx->addTemplateVariable('N_PAGARE', $n_pagare);
			    $docx->addTemplateVariable('MONTO_PAGARE', number_format($monto_deuda,0,',','.').'');
				$docx->addTemplateVariable('PAGARE_TOTAL_UF2', ''.number_format($total_pagare_uf,4,',','.'));
				$docx->addTemplateVariable('PAGARE_NOMBRE_AVAL', $pagare_nombre_aval);		    
			    
			    $docx->addTemplateVariable('UF', $uf.'');//
			    $docx->addTemplateVariable('UF_FECHA', $uf_fecha.'');//
			   
			    $monto_pagare_peso = number_format(($monto_deuda*$uf),0,',','.');
			    $docx->addTemplateVariable('MONTO_PAGARE_PESO', '$'.$monto_pagare_peso);

			    $docx->addTemplateVariable('FECHA', ''.date('d-m-Y'));
			    $docx->addTemplateVariable('FECHA_ACTUAL_EN_LETRAS', ''.$fecha_hoy_en_letras);
			    
			    $docx->addTemplateVariable('FECHA_UF', ''.$this->input->post('fecha'));
			    
			    $total_pagare_peso = ($uf*$total_pagare_uf);
			    $docx->addTemplateVariable('SALDO_DEUDA', '$'.number_format(abs($val->monto_deuda),0,',','.') );
			    $docx->addTemplateVariable('TOTAL_PAGARE_PESO', '$'.number_format($total_pagare_uf,0,',','.') );
				$docx->addTemplateVariable('TOTAL_PAGARE_PESO_UF', '$'.number_format($total_pagare_peso,0,',','.') );
			    $docx->addTemplateVariable('TOTAL_PAGARE_UF', ''.number_format($total_pagare_uf,4,',','.') );
			    
                if ($pagares!=''){
			    	$docx->replaceTemplateVariableByHTML('PAGARES', 'inline', $pagares.'');
			    }else{
			    	$docx->addTemplateVariable('PAGARES', '' );
			    }
				if ($pagares_uf!=''){
			    	$docx->replaceTemplateVariableByHTML('PAGARES_UF', 'inline', $pagares_uf.'');
			    }else{
			    	$docx->addTemplateVariable('PAGARES_UF', '' );
			    }
			    
				if($val->fecha_asignacion != '' || $val->fecha_asignacion != '0'){
			    	$docx->addTemplateVariable('DÍA_DE_ASIGNACION_DEL_PAGARE', date("d", strtotime($val->fecha_asignacion)) );
			    	$docx->addTemplateVariable('MES_DE_ASIGNACION_DEL_PAGARE', date("m", strtotime($val->fecha_asignacion)) );
			    	$docx->addTemplateVariable('ANO_DE_ASIGNACION_DEL_PAGARE', date("Y", strtotime($val->fecha_asignacion)) );
			    	$docx->addTemplateVariable('FECHA_DE_ASIGNACION_DEL_PAGARE', date("d/m/Y", strtotime($val->fecha_asignacion)) );
			    	$docx->addTemplateVariable('DÍA_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_asignacion,'d') );
			    	$docx->addTemplateVariable('MES_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_asignacion,'m') );
			    	$docx->addTemplateVariable('ANO_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_asignacion,'y') );

					$fecha_asignacion_dia_venc = date("d", strtotime($v->pagare_fecha_asignacion));
					$fecha_asignacion_mes_venc = strftime("%B", strtotime($v->pagare_fecha_asignacion));
					$fecha_asignacion_year_venc = date("Y", strtotime($v->pagare_fecha_asignacion));
					$asignacion_en_letras = strtolower($this->ValorEnLetras($fecha_asignacion_dia_venc,"").' de '.$fecha_asignacion_mes_venc.' de '.$this->ValorEnLetras($fecha_asignacion_year_venc,""));
					$docx->addTemplateVariable('FECHA_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', ' '.$asignacion_en_letras );
			    	
			    }else{
			    	$docx->addTemplateVariable('DÍA_DE_ASIGNACION_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('MES_DE_ASIGNACION_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('ANO_DE_ASIGNACION_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('FECHA_DE_ASIGNACION_DEL_PAGARE', '' );
			    	$docx->addTemplateVariable('FECHA_DE_ASIGNACION_DEL_PAGARE_EN_LETRAS', ' ' );
			    }
			    
			    if($val->fecha_vencimiento != '' || $val->fecha_vencimiento != '0'){
			    	$docx->addTemplateVariable('DÍA_DE_VENCIMIENTO_DEL_PAGARE', date("d", strtotime($val->fecha_vencimiento)) );
			    	$docx->addTemplateVariable('MES_DE_VENCIMIENTO_DEL_PAGARE', date("m", strtotime($val->fecha_vencimiento)) );
			    	$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_DEL_PAGARE', date("Y", strtotime($val->fecha_vencimiento)) );
			    	$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE', date("d/m/Y", strtotime($val->fecha_vencimiento)) );

			    	$docx->addTemplateVariable('DÍA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_vencimiento,'d') );
			    	$docx->addTemplateVariable('MES_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_vencimiento,'m') );
			    	$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_vencimiento,'y') );
			    	//$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', $this->fecha_a_letras($val->fecha_asignacion) );
			    	
			    	/* V3 */
					$fecha_vencimiento_dia_venc = date("d", strtotime($v->pagare_fecha_vencimiento));
					$fecha_vencimiento_mes_venc = strftime("%B", strtotime($v->pagare_fecha_vencimiento));
					$fecha_vencimiento_year_venc = date("Y", strtotime($v->pagare_fecha_vencimiento));

					$venc_en_letras = strtolower($this->ValorEnLetras($fecha_vencimiento_dia_venc,"").' de '.$fecha_vencimiento_mes_venc.' de '.$this->ValorEnLetras($fecha_vencimiento_year_venc,""));

					
					$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', ' '.$venc_en_letras );
					/* V3 */
			    	
			    }else{
			    	$docx->addTemplateVariable('DÍA_DE_VENCIMIENTO_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('MES_DE_VENCIMIENTO_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('ANO_DE_VENCIMIENTO_DEL_PAGARE', '____' );
			    	$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE', '' );
			    	$docx->addTemplateVariable('FECHA_DE_VENCIMIENTO_DEL_PAGARE_EN_LETRAS', ' ' );
			    }
			    
			    $docx->addTemplateVariable('NOMBRE_R', strtoupper($val->representante_nombre ));
			    $docx->addTemplateVariable('APELLIDO_PATERNO_R', strtoupper($val->representante_apepat));
			    $docx->addTemplateVariable('APELLIDO_MATERNO_R', strtoupper($val->representante_apemat));
	
			    $docx->addTemplateVariable('DIRECCION_Y_NUMERO_R', strtoupper($val->representante_direccion.' '.$val->representante_direccion_n));
			    $docx->addTemplateVariable('COMUNA_R', strtoupper($val->representante_comuna));
			    $docx->addTemplateVariable('CIUDAD_R', strtoupper($val->representante_ciudad));
			    
			    $docx->addTemplateVariable('FECHA_ESCRITURA_PUBLICA', strtoupper($val->fecha_escritura_publica));
			    $docx->addTemplateVariable('NOTARIA', strtoupper($val->notaria));
			    $docx->addTemplateVariable('NUMERO_REPERTORIO', strtoupper($val->numero_repertorio));
				
			    
			    $docx->addTemplateVariable('FECHA_ESCRITURA_PERSONERIA', strtoupper($val->fecha_escritura_personeria));
			    $docx->addTemplateVariable('NOTARIA_PERSONERIA', strtoupper($val->notaria_personeria));
			    $docx->addTemplateVariable('TITULAR_PERSONERIA', strtoupper($val->titular_personeria));
			    
			    $docx->addTemplateVariable('FECHA_ESCRITURA_APODERADO', strtoupper($val->fecha_escritura_apoderado));
			    $docx->addTemplateVariable('NOTARIA_APODERADO', strtoupper($val->notaria_apoderado));

			    
			    $docx->addTemplateVariable('DEUDA_TOTAL', $val->monto_deuda);
			    $docx->addTemplateVariable('DEUDA_TOTAL_EN_LETRAS',strtolower($this->ValorEnLetras($val->monto_deuda)));
			    
			    $docx->addTemplateVariable('NOMBRE_DEMANDANTE', strtoupper($val->representante_nombre));
			    $docx->addTemplateVariable('APELLIDO_PATERNO_DEMANDANTE', strtoupper($val->representante_apepat));
			    $docx->addTemplateVariable('APELLIDO_MATERNO_DEMANDANTE', strtoupper($val->representante_apemat));
			    
			    $docx->addTemplateVariable('PROCURADOR', strtoupper($val->procurador_nombres.' '.$val->procurador_apellidos));
			    $docx->addTemplateVariable('RUT_PROCURADOR', ''.$this->formateo_rut($val->procurador_rut) );
			    
			    $id = $val->id;
			    	
				if (file_exists(base_url().'documentos/'.$nombre_archivo.'.docx')) {
					
					$nombre_archivo = uniqid().'__'.date("d-m-Y__H-i-s");
					
					$fields = array();
					$fields['idcuenta'] 		= $id;
					$fields['nombre_documento']	= $nombre_archivo.'.docx';
					$fields['id_etapa']			= $_POST['id_etapa'];
					$fields['fecha_crea']		= date("Y-m-d H:i:s");
					$fields['tipo_documento']	= $documentos_array[$tipo_documento];
					$fields['archivo_zip']		= $nombre_zip.'.zip';
					
					$this->documento_m->insert( $fields ,false,true);
					
					$docx->createDocx('./documentos/'.$nombre_archivo);
					
						
				}else{
					
					$fields = array();
					$fields['idcuenta'] 		= $id;
					$fields['nombre_documento']	= $nombre_archivo.'.docx';
					$fields['id_etapa']			= $_POST['id_etapa'];
					$fields['fecha_crea']		= date("Y-m-d H:i:s");
					$fields['tipo_documento']	= $documentos_array[$tipo_documento];
					$fields['archivo_zip']		= $nombre_zip.'.zip';
					
					$this->documento_m->insert($fields,false,true);
					
					$docx->createDocx('./documentos/'.$nombre_archivo);
				}
					

				 if( $this->data['nodo']->nombre == 'fullpay'){
				   
				 if ( isset($_POST['tipo_documento'])!=''){
				   $plantilla_documento = $this->documento_plantilla_m->get_by( array('id'=>$_POST['tipo_documento']));
				   }
				
				 if (count($plantilla_documento) > 0 && $plantilla_documento->id_etapa !='' && $plantilla_documento->id_etapa !='0'){
					$fields_save = array();
				  	$fields_save['id_etapa'] = $plantilla_documento->id_etapa;
				    $this->cuentas_m->save_default($fields_save,$id);
				 
				    $fields_save_etapa = array();
				    $fields_save_etapa['id_cuenta'] = $id;
				    $fields_save_etapa['id_etapa'] = $plantilla_documento->id_etapa;
				    $fields_save_etapa['fecha_etapa']= date('Y-m-d');
				    $fields_save_etapa['id_administrador'] = $this->session->userdata('usuario_id');
				    $this->cuentas_etapas_m->save_default($fields_save_etapa,'');
				}
			 }
				
				$this->data['exito'] = 'Se ha creado exitosamente los documentos.';
				
				$zip->addFile('./documentos/'.$nombre_archivo.'.docx',$nombre_archivo.'.docx');
				
			}
			$zip->close();
			//echo  'test_'.$total_pagare_uf.'<BR>';
			//echo 're';
			die();
			redirect('admin/doc/all');
		}else{
			
			$this->data['estados_cuenta'] = $this->estados_cuenta_m->get_many_by(array('activo'=>'S'));
			$this->data['mandantes'] = $this->mandantes_m->get_many_by(array('activo'=>'S'));
			$this->data['etapas'] = $this->etapas_m->get_many_by(array('activo'=>'S'));
			
			
			$this->data['plantilla'] = 'doc/generar'; 
			$this->load->view ( 'backend/index', $this->data );
		}
		
		
	}
		
	public function upload($idcuenta=''){
		
		$encriptado = uniqid().'__'.date("d-m-Y__H-i-s");
		$archivo = explode(".", $_FILES['file']['name']);
		
		$config['upload_path'] = './documentos/';
		$config['allowed_types'] = '*';
		$config['max_size']	= '100000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$config['file_name']  = str_replace(" ", "_", $archivo[0]).'_'.$encriptado.'.'.$archivo[1];
		
		$this->load->library('upload', $config);
		if (! $this->upload->do_upload('file') ){
			$error = $this->upload->display_errors();
			echo $error;
		}else{
			///$data = array('upload_data' => $this->upload->data());
			if( $this->input->post('otro_upload') != '' ){
				$this->documento_m->insert(array('idcuenta'=>$idcuenta,'id_etapa'=>$this->input->post('otro_upload'),'nombre_documento'=>$config['file_name'],'fecha_crea'=>date("Y-m-d H:i:s"),'tipo_documento'=>'Archivo Subido.'),false,true);
			}else{
				$this->documento_m->insert(array('idcuenta'=>$idcuenta,'id_etapa'=>$this->input->post('id_etapa'),'nombre_documento'=>$config['file_name'],'fecha_crea'=>date("Y-m-d H:i:s"),'tipo_documento'=>'Archivo Subido.'),false,true);
			}
			
			echo '1';
		}
		
	}
	
	public function plantilla_upload(){
		$archivo = explode(".", $_FILES['file']['name']);
		$nombre_achivo = strtolower(str_replace(" ", "_", $archivo[0]));
		
		$config['upload_path'] = './documentos_base/';
		$config['allowed_types'] = '*';
		$config['file_name']  = $nombre_achivo;
		$this->load->library('upload', $config);
		
		if( $archivo[1] == 'docx'){
			
			if (! $this->upload->do_upload('file') ){
				$error = $this->upload->display_errors();
				echo $error;
			}else{
				$data = $this->upload->data();
				/*echo $data['file_name'].'<br>';
				echo $archivo[0];*/
				$save = array();
				$save['id_mandante'] = $this->input->post('id_mandante');
				$save['nombre_documento'] = $archivo[0];
				$save['path'] = $data['file_name'];
				if( $this->documento_plantilla_m->insert($save,false,true) ){
					echo 'Documento subido exitÃ³samente.';
				}
			}
		}else{
			echo 'error_extension';
		}
	}
	
	public function plantilla_delete($id_plantilla=''){
		
		$doc = $this->documento_plantilla_m->get_by( array('id'=>$id_plantilla) );
		if( count($doc)>0){
			if ( file_exists( './documentos_base/'.$doc->path ) &&  trim($doc->path) != ''){
				unlink( './documentos_base/'.$doc->path );
			}
		$this->documento_plantilla_m->delete_by( array('id'=>$id_plantilla) );
		}
		
		/*$exito = true;
		if( $exito ){*/
			echo '{"status":"exito","contenido":""}';
		/*}else{
			echo '{"status":"error","contenido":"'.$error.'"}';
		}*/
	}
	
	public function plantilla_edit($id_plantilla=""){
		//$this->output->enable_profiler(TRUE);
		$this->data['id_plantilla'] = $id_plantilla;
		
		$this->data['input_mandante'] = '';
		$this->data['input_nombre_documento'] = '';
		$this->data['posicion'] = '';
		$this->data['exorto'] = '';
		$this->data['tipo_demanda'] = '';
		$this->data['id_documento_etapa'] = '';
		$this->data['por_defecto'] = '';
		
		
		$exorto = '';
		$tipo_demanda = '';
		
		if( $_POST ){
			
			if(!empty($_FILES['file']['name'])){
				
					$archivo = explode(".", $_FILES['file']['name']);
					$nombre_achivo = strtolower(str_replace(" ", "_", $archivo[0]));
				
					$config['upload_path'] = './documentos_base/';
					$config['allowed_types'] = '*';
					$config['file_name']  = $nombre_achivo;
					$this->load->library('upload', $config);
					
					if (! $this->upload->do_upload('file') ){
						$error = $this->upload->display_errors();
						echo $error;
					}else{
						//echo $this->input->post('posicion').'sdkljjfksdjklfljksd';
						$data = $this->upload->data();
						$save = array();
						$save['id_mandante'] = $this->input->post('id_mandante');
						//$save['nombre_documento'] = $this->input->post('nombre_documento');
						 $save['path'] = $data['file_name'];
					 }
					
					//borramos el documento anterior
					$doc = $this->documento_plantilla_m->get_by( array('id'=>$id_plantilla) );
					if( count($doc)>0){
						if ( file_exists( './documentos_base/'.$doc->path ) &&  trim($doc->path) != ''){
							unlink( './documentos_base/'.$doc->path );
						}
					}// fin de borrado
			
			}else{
				$save = array();
				$save['id_etapa'] =$this->input->post('id_documento_etapa');           
				$save['id_mandante'] = $this->input->post('id_mandante');
				$save['nombre_documento'] = $this->input->post('input_nombre_documento');
			}//fin empty files
			$save['posicion'] = $this->input->post('posicion');
		    $tipo_demanda = '0';
			if (isset ( $_POST ['tipo_demanda'] )) {
				if ($_POST ['tipo_demanda'] == 'S') {
					$tipo_demanda = '1';
					$save ['tipo_demanda'] = $tipo_demanda;
				}
			} else {
				$save ['tipo_demanda'] = $tipo_demanda;
			}
			$exorto = '0';
			if (isset ( $_POST ['exorto'] )) {
				if ($_POST ['exorto'] == 'S') {
					$exorto = '1';
					$save ['exorto'] = $exorto;
				}
			} else {
				$save ['exorto'] = $exorto;
			}
			
			$por_defecto = '0';
			if (isset ( $_POST ['por_defecto'] )) {
				if ($_POST ['por_defecto'] == 'S') {
					$por_defecto = '1';
					$save ['por_defecto'] = $por_defecto;
				}
			} else {
				$save ['por_defecto'] = $por_defecto;
			}
			
			
			$this->documento_plantilla_m->update_by(array('id'=>$id_plantilla),$save);
		}
	
		$t1[] = $this->documento_plantilla_m->get_columns();
		$t2[] = $this->mandantes_m->get_columns();
		
		$d = array_merge($t1,$t2);
		foreach ($d as $campo) {
			foreach ($campo as $dato) {
				$cols[] = $dato;
			}
		}
		
		$this->db->select($cols);
		$this->db->where('dp.id',$id_plantilla);
		$this->db->from('documento_plantilla dp');
		$this->db->join('0_mandantes m', 'm.id = dp.id_mandante');
		$query = $this->db->get();
		$datos = $query->result();
		
		if( count($datos)>0 ){
			$this->data['input_mandante'] = $datos[0]->mandantes_id;
			$this->data['input_nombre_documento'] = $datos[0]->documento_plantilla_nombre_documento;
			$this->data['posicion'] = $datos[0]->documento_plantilla_posicion;	
			$this->data['documento_plantilla_exorto'] = $datos[0]->documento_plantilla_exorto;
			$this->data['documento_plantilla_tipo_demanda'] = $datos[0]->documento_plantilla_tipo_demanda;	
			$this->data['documento_plantilla_id_etapa'] = $datos[0]->documento_plantilla_id_etapa;
			$this->data['documento_plantilla_por_defecto'] = $datos[0]->documento_plantilla_por_defecto;
		}
	
		
		$m = $this->mandantes_m->get_many_by(array('activo'=>'S'));
		$mandantes = array();
		foreach($m as $key=>$val){
			$mandantes[$val->id] = $val->razon_social;
		}
		$this->data['mandantes'] = $mandantes;
		
		
		$this->data['plantilla'] = 'doc/plantilla_edit';
		$this->load->view ( 'backend/index', $this->data );
	}
	
	public function reload_doc_procurador($idcuenta=''){
		$documentos=$this->documento_m->order_by("fecha_crea","DESC")->get_many_by( array("idcuenta" => $idcuenta) );
		$this->data['documentos'] = $documentos;
		$this->load->view ( 'backend/templates/procurador/gestion/documento_tabla', $this->data );
	}
	
	public function reload_doc($idcuenta=''){
		
		
		$c1[] = $this->documento_m->get_columns();
		$c2[] = $this->cuentas_m->get_columns();
		$c3[] = $this->mandantes_m->get_columns();
		$c4[] = $this->etapas_m->get_columns();
		
		$c = array_merge($c1, $c2,$c3,$c4);
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = $dato;
			}
		}
		$this->db->where(array('doc.idcuenta'=>$idcuenta));
		$this->db->order_by('doc.iddocumento', 'DESC');
		
		$this->db->select($cols);
		$this->db->from('documento doc');
		$this->db->join('0_cuentas c', 'c.id = doc.idcuenta');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join('s_etapas e', 'e.id = doc.id_etapa','left');
		
		$query = $this->db->get();

		$this->data['documentos'] = $query->result();
		$this->load->view ( 'backend/templates/doc/table_doc', $this->data );
	}
		
	public function formateo_rut($c=''){
		$a = trim($c);
		$b = str_replace('.', '', $a);
		$rut_param = str_replace('-', '', $b);
		
		if( strlen($rut_param) == 9 ){
			$parte1 = substr($rut_param, 0,2); //12
	    	$parte2 = substr($rut_param, 2,3); //345
	    	$parte3 = substr($rut_param, 5,3); //456
	    	$parte4 = substr($rut_param, 8);   //todo despues del caracter 8 
		}else{
			$parte1 = substr($rut_param, 0,1); //12
	    	$parte2 = substr($rut_param, 1,3); //345
	    	$parte3 = substr($rut_param, 4,3); //456
	    	$parte4 = substr($rut_param, 7);   //todo despues del caracter 8 
		}
	    return $parte1.".".$parte2.".".$parte3."-".$parte4;
	}
	
	public function all($tipo=''){
   
		//$this->output->enable_profiler(TRUE);
		if( $this->session->userdata("usuario_perfil") == 3 ){
	    	$where["c.id_procurador"] = $this->session->userdata("usuario_id");
	    	$this->db->where($where);
	    }
	    	    
		if($tipo == 'plantillas'){
			$t1[] = $this->documento_plantilla_m->get_columns();
			$t2[] = $this->mandantes_m->get_columns();
			
			$d = array_merge($t1,$t2);
			foreach ($d as $campo) {
				foreach ($campo as $dato) {
					$cols[] = $dato;
				}
			}
			
			$this->db->select($cols);
			$this->db->from('documento_plantilla dp');
			$this->db->join('0_mandantes m', 'm.id = dp.id_mandante');
            //$this->db->limit(100);
			$query = $this->db->get();
			$this->data['documento_plantilla'] = $query->result();
			
			$m = $this->mandantes_m->get_many_by(array('activo'=>'S'));
			$mandantes = array();
			foreach($m as $key=>$val)
			{
				$mandantes[$val->id] = $val->razon_social;
			}
			$this->data['mandantes'] = $mandantes;		
		}		
		
		if($tipo == ''){
			$tipo = 'documentos';
		}
		
		$c1[] = $this->documento_m->get_columns();
		$c2[] = $this->cuentas_m->get_columns();
		$c3[] = $this->mandantes_m->get_columns();
		$c4[] = $this->etapas_m->get_columns();
		$c5[] = $this->usuarios_m->get_columns();
		
		$c = array_merge($c1, $c2,$c3,$c4,$c5);
		$cols=array();
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = $dato;
			}
		}
				
		$this->db->select($cols);
		$this->db->from('documento doc');
		$this->db->join('s_etapas e', 'e.id = doc.id_etapa',"left");
		$this->db->join('0_cuentas c', 'c.id = doc.idcuenta');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		
		$this->db->order_by('iddocumento', 'DESC');
        $this->db->limit(100);
		$query = $this->db->get();
		$this->data['documentos'] = $query->result();
		
		/*echo '<pre>';
		print_r( $this->data['documentos'] );
		echo '</pre>';*/
		
		
		$this->db->select($cols);
		$this->db->from('documento doc');
		$this->db->join('0_cuentas c', 'c.id = doc.idcuenta');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join('s_etapas e', 'e.id = doc.id_etapa',"left");
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->order_by('iddocumento', 'DESC');
		$this->db->group_by('doc.archivo_zip');
        $this->db->limit(100);
		$query = $this->db->get();
		$this->data['documentos_zip'] = $query->result();
		
		
		//$this->load->view ( 'backend/templates/doc/table_doc_all', $this->data );
		$this->data['tipo']			= $tipo;
		$this->data['plantilla']	= 'doc/table_doc_all';
		//print_r($this->data);
		$this->load->view ( 'backend/index', $this->data );
	}
	
	public function delete($iddocumento=''){
		$error = '';
		$exito = TRUE;
		$doc = $this->documento_m->get_by(array('iddocumento'=>$iddocumento));
		if(count($doc)>0){
			$archivo = $doc->nombre_documento;

			if( file_exists( './documentos/'.$archivo ) ) {
				if( unlink( './documentos/'.$archivo ) ){
					$this->documento_m->delete_by(array('iddocumento'=>$iddocumento));
				}else{
					$exito = false;
					$error = 'Doc. no se puedo borrar.';
				}
			}else{
				$this->documento_m->delete_by(array('iddocumento'=>$iddocumento));
			}
		}
		
		if( $exito ){
			echo '{"status":"exito","contenido":""}';
		}else{
			echo '{"status":"error","contenido":"'.$error.'"}';
		}
	}
		
	public function zip(){
		
		$zip = new ZipArchive();
		$filename = './documentos_zip/test112.zip';
		
		if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		    exit("cannot open <$filename>\n");
		}
		
		//$zip->addFromString('./documentos_base/demanda_ejecutiva_pagare_falabella.docx' . time(), "#1 Esto es una cadena de prueba aÃ±adida como  testfilephp.txt.\n");
		//$zip->addFromString("testfilephp2.txt" . time(), "#2 Esto es una cadena de prueba aÃ±adida como testfilephp2.txt.\n");
		$zip->addFile('./documentos_base/demanda_ejecutiva_pagare_falabella.docx','demanda_ejecutiva_pagare_falabella.docx');
		echo "numficheros: " . $zip->numFiles . "\n";
		echo "estado:" . $zip->status . "\n";
		$zip->close();
		
	}
	
	function fecha_a_letras($fecha,$formato=''){
		//echo $fecha.'_-<br>';
		$meses_espanol = array( '1'=>'enero','2'=>'febrero','3'=>'marzo','4'=>'abril','5'=>'mayo','6'=>'junio','7'=>'julio','8'=>'agosto','9'=>'septiembre','10'=>'octubre','11'=>'noviembre','12'=>'diciembre');			
		$fecha = date("d/m/Y", strtotime($fecha));
		$fecha_dia = date("d", strtotime($fecha));
		$fecha_mes = date("n", strtotime($fecha));
		$fecha_year = date("Y", strtotime($fecha));
		//echo 'fecha '.$fecha.' fd '.$fecha_dia.' fm '.$fecha_mes.' fy '.$fecha_year.'<br>';
		if ($formato==''){
			return strtolower($this->ValorEnLetras($fecha_dia,"").' de '.$meses_espanol[$fecha_mes].' de '.$this->ValorEnLetras($fecha_year,""));
		} elseif($formato=='d'){
			return strtolower($this->ValorEnLetras($fecha_dia,""));
		} elseif($formato=='m'){
			return strtolower($meses_espanol[$fecha_mes]);
		} elseif($formato=='y'){
			return strtolower($this->ValorEnLetras($fecha_year,""));
		}
	}
	
	function ValorEnLetras($x, $Moneda=''){
		$s="";
		$Ent="";
		$Frc="";
		$Signo="";
			
		if(floatVal($x) < 0)
		 $Signo = $this->Neg . " ";
		else
		 $Signo = "";
		
		if(intval(@number_format($x,2,'.','') )!=$x) //<- averiguar si tiene decimales
		  $s = @number_format($x,2,'.','');
		else
		  $s = @number_format($x,0,'.','');
		   
		$Pto = strpos($s, $this->Dot);
			
		if ($Pto === false)
		{
		  $Ent = $s;
		  $Frc = $this->Void;
		}
		else
		{
		  $Ent = substr($s, 0, $Pto );
		  $Frc =  substr($s, $Pto+1);
		}

		if($Ent == $this->Zero || $Ent == $this->Void)
		   $s = "Cero ";
		elseif( strlen($Ent) > 7)
		{
		   $s = $this->SubValLetra(intval( substr($Ent, 0,  strlen($Ent) - 6))) . 
				 "Millones " . $this->SubValLetra(intval(substr($Ent,-6, 6)));
		}
		else
		{
		  $s = $this->SubValLetra(intval($Ent));
		}

		if (substr($s,-9, 9) == "Millones " || substr($s,-7, 7) == "Millón ")
		   $s = $s . "de ";

		$s = $s . $Moneda;

		if($Frc != $this->Void)
		{
		   $s = $s . " Con " . $this->SubValLetra(intval($Frc)) . "Centavos";
		   //$s = $s . " " . $Frc . "/100";
		}
		
		$s = str_replace('Un Mil', 'Mil',$s);
		//echo '1.-'.$s.'<br>';
		$s = str_replace('Onceo', 'Once',$s);
		//echo '2.-'.$s.'<br>';
		return ($Signo . trim($s) );
	   
}

	function SubValLetra($numero){
		$Ptr="";
		$n=0;
		$i=0;
		$x ="";
		$Rtn ="";
		$Tem ="";

		$x = trim("$numero");
		$n = strlen($x);

		$Tem = $this->Void;
		$i = $n;
		
		while( $i > 0)
		{
		   $Tem = $this->Parte(intval(substr($x, $n - $i, 1). 
							   str_repeat($this->Zero, $i - 1 )));
		   If( $Tem != "Cero" )
			  $Rtn .= $Tem . $this->SP;
		   $i = $i - 1;
		}

		
		//--------------------- GoSub FiltroMil ------------------------------
		$Rtn=str_replace(" Mil Mil", " Un Mil", $Rtn );
		while(1)
		{
		   $Ptr = strpos($Rtn, "Mil ");       
		   If(!($Ptr===false))
		   {
			  If(! (strpos($Rtn, "Mil ",$Ptr + 1) === false ))
				$this->ReplaceStringFrom($Rtn, "Mil ", "", $Ptr);
			  Else
			   break;
		   }
		   else break;
		}

		//--------------------- GoSub FiltroCiento ------------------------------
		$Ptr = -1;
		do{
		   $Ptr = strpos($Rtn, "Cien ", $Ptr+1);
		   if(!($Ptr===false))
		   {
			  $Tem = substr($Rtn, $Ptr + 5 ,1);
			  if( $Tem == "M" || $Tem == $this->Void)
				 ;
			  else          
				 $this->ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr);
		   }
		}while(!($Ptr === false));

		//--------------------- FiltroEspeciales ------------------------------
		$Rtn=str_replace("Diez Un", "Once", $Rtn );
		$Rtn=str_replace("Diez Dos", "Doce", $Rtn );
		$Rtn=str_replace("Diez Tres", "Trece", $Rtn );
		$Rtn=str_replace("Diez Cuatro", "Catorce", $Rtn );
		$Rtn=str_replace("Diez Cinco", "Quince", $Rtn );
		$Rtn=str_replace("Diez Seis", "Dieciseis", $Rtn );
		$Rtn=str_replace("Diez Siete", "Diecisiete", $Rtn );
		$Rtn=str_replace("Diez Ocho", "Dieciocho", $Rtn );
		$Rtn=str_replace("Diez Nueve", "Diecinueve", $Rtn );
		$Rtn=str_replace("Veinte Un", "Veintiun", $Rtn );
		$Rtn=str_replace("Veinte Dos", "Veintidos", $Rtn );
		$Rtn=str_replace("Veinte Tres", "Veintitres", $Rtn );
		$Rtn=str_replace("Veinte Cuatro", "Veinticuatro", $Rtn );
		$Rtn=str_replace("Veinte Cinco", "Veinticinco", $Rtn );
		$Rtn=str_replace("Veinte Seis", "Veintiseis", $Rtn );
		$Rtn=str_replace("Veinte Siete", "Veintisiete", $Rtn );
		$Rtn=str_replace("Veinte Ocho", "Veintiocho", $Rtn );
		$Rtn=str_replace("Veinte Nueve", "Veintinueve", $Rtn );

		//--------------------- FiltroUn ------------------------------
		If(substr($Rtn,0,1) == "M") $Rtn = "Un " . $Rtn;
		//--------------------- Adicionar Y ------------------------------
		for($i=65; $i<=88; $i++)
		{
		  If($i != 77)
			 $Rtn=str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn);
		}
		$Rtn=str_replace("*", "a" , $Rtn);
		return($Rtn);
	}

	function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr){
	  $x = substr($x, 0, $Ptr)  . $NewWrd . substr($x, strlen($OldWrd) + $Ptr);
	}

	function Parte($x)	{
		$Rtn='';
		$t='';
		$i='';
		Do
		{
		  switch($x)
		  {
			 Case 0:  $t = "Cero";break;
			 Case 1:  $t = "Uno";break;
			 Case 2:  $t = "Dos";break;
			 Case 3:  $t = "Tres";break;
			 Case 4:  $t = "Cuatro";break;
			 Case 5:  $t = "Cinco";break;
			 Case 6:  $t = "Seis";break;
			 Case 7:  $t = "Siete";break;
			 Case 8:  $t = "Ocho";break;
			 Case 9:  $t = "Nueve";break;
			 Case 10: $t = "Diez";break;
			 Case 20: $t = "Veinte";break;
			 Case 30: $t = "Treinta";break;
			 Case 40: $t = "Cuarenta";break;
			 Case 50: $t = "Cincuenta";break;
			 Case 60: $t = "Sesenta";break;
			 Case 70: $t = "Setenta";break;
			 Case 80: $t = "Ochenta";break;
			 Case 90: $t = "Noventa";break;
			 Case 100: $t = "Cien";break;
			 Case 200: $t = "Doscientos";break;
			 Case 300: $t = "Trescientos";break;
			 Case 400: $t = "Cuatrocientos";break;
			 Case 500: $t = "Quinientos";break;
			 Case 600: $t = "Seiscientos";break;
			 Case 700: $t = "Setecientos";break;
			 Case 800: $t = "Ochocientos";break;
			 Case 900: $t = "Novecientos";break;
			 Case 1000: $t = "Mil";break;
			 Case 1000000: $t = "Millón";break;
		  }

		  If($t == $this->Void)
		  {
			$i = $i + 1;
			$x = $x / 1000;
			If($x== 0) $i = 0;
		  }
		  else
			 break;
			   
		}while($i != 0);
	   
		$Rtn = $t;
		Switch($i)
		{
		   Case 0: $t = $this->Void;break;
		   Case 1: $t = " Mil";break;
		   Case 2: $t = " Millones";break;
		   Case 3: $t = " Billones";break;
		}
		return($Rtn . $t);
	}
	
}

?>
