<?php
class Hist_Cuentas extends CI_Controller {
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
		$this->load->model ( 'hist_cuentas_m' ); $this->load->model ( 'hist_cuentas_etapas_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'tipo_productos_m' );
		$this->load->model ( 'administradores_m' );	
		$this->load->model ( 'estados_cuenta_m' );
		$this->load->model ( 'hist_cuentas_pagos_m');
		$this->load->model ( 'comprobantes_m');
		$this->load->model ( 'hist_cuentas_gastos_m');
		$this->load->model ( 'hist_cuentas_historial_m');
		$this->load->model ( 'etapas_m' );	
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'comunas_m' );
		
		/*seters*/
		$this->data['current'] = 'hist_cuentas';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'cuentas/';
		$this->data['lists'] = array();
		
		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
		$this->data['forma_pagos'] = array(''=>'Forma Pago','TF'=>'Transferencia','DP'=>'Depósito','CH'=>'Cheque','EF'=>'Efectivo');
		
		
	}
	
	function form($action='',$id='',$param=''){
		$view='hist_form';
		$this->data['plantilla'].= $view;
		//$this->output->enable_profiler(TRUE);
		/*guardar*/	

		$this->data['juicio_terminado'] = $this->hist_cuentas_etapas_m->get_by(array('id_cuenta'=>$id,'id_etapa'=>'37'));
		
		if ($action=='guardar'){
			$this->hist_cuentas_m->setup_validate();
			
			$fields_save = array();
			$fields_save['id_usuario']=$_POST['id_usuario'];
			if (isset($_POST['id_mandante'])){ $fields_save['id_mandante']=$_POST['id_mandante'];}
			if (isset($_POST['id_procurador'])){ $fields_save['id_procurador']=$_POST['id_procurador'];}
			if (isset($_POST['id_tipo_producto'])){ $fields_save['id_tipo_producto']=$_POST['id_tipo_producto'];}
			if (isset($_POST['n_pagare'])){ $fields_save['n_pagare']=$_POST['n_pagare'];}
			if (isset($_POST['monto_deuda'])){ $fields_save['monto_deuda']=$_POST['monto_deuda'];}
			if (isset($_POST['fecha_asignacion_year']) && isset($_POST['fecha_asignacion_month']) && isset($_POST['fecha_asignacion_day'])){
				$fecha_asignacion=$_POST['fecha_asignacion_year'].'-'.$_POST['fecha_asignacion_month'].'-'.$_POST['fecha_asignacion_day'];
				$fields_save['fecha_asignacion']=$fecha_asignacion;
			}
			$fecha_inicio = '';
			if (isset($_POST['fecha_inicio_year']) && isset($_POST['fecha_inicio_month']) && isset($_POST['fecha_inicio_day'])){
				$fecha_inicio=$_POST['fecha_inicio_year'].'-'.$_POST['fecha_inicio_month'].'-'.$_POST['fecha_inicio_day'];
				$fields_save['fecha_inicio']=$fecha_inicio;
			}
			if (isset($_POST['id_tribunal'])){ $fields_save['id_tribunal']=$_POST['id_tribunal'];}
			if (isset($_POST['id_distrito'])){ $fields_save['id_distrito']=$_POST['id_distrito'];}
			if (isset($_POST['rol'])){ $fields_save['rol']=$_POST['rol'];}
			if (isset($_POST['monto_demandado'])){ $fields_save['monto_demandado']=$_POST['monto_demandado'];}
			if (isset($_POST['id_estado_cuenta'])){ $fields_save['id_estado_cuenta']=$_POST['id_estado_cuenta'];}
			if (isset($_POST['bien_habitacional'])){ $fields_save['bien_habitacional']=$_POST['bien_habitacional'];}
			if (isset($_POST['bien_vehiculo'])){ $fields_save['bien_vehiculo']=$_POST['bien_vehiculo'];}

			if (!$this->hist_cuentas_m->save_default($fields_save,$id)){}
			else if (empty($id)){$id=$this->db->insert_id();} 
				
				if($fields_save['id_estado_cuenta'] != 4 && $fields_save['id_estado_cuenta'] != 5)
				{
					$this->cambiaEstado($id,$fields_save['id_estado_cuenta']);
					redirect('admin/cuentas/form/editar/'.$id);
				}else	redirect('admin/hist_cuentas/form/editar/'.$id);
			}//guardar
		// --------------------------------------------------------------------
		if ($action=='guardar-historial'){
			$this->hist_cuentas_etapas_m->setup_validate();
			if (!empty($_POST ['historial'])){$fields_save['historial'] = $_POST ['historial'];}
			$fields_save['fecha'] = date('Y-m-d H:i:s');
			$fields_save['id_cuenta'] = $id;
			if (!$this->hist_cuentas_historial_m->save_default($fields_save,$param)){}
			else{if (empty($id)){$id=$this->db->insert_id();} redirect('admin/cuentas/form/editar/'.$id);};
		}
		if ($action=='eliminar-historial'){
			//$this->output->enable_profiler(TRUE);
			if (!empty($param)){$this->hist_cuentas_historial_m->eliminar($param);}
			redirect('admin/cuentas/form/editar/'.$id);
		}
		// --------------------------------------------------------------------
		if ($action=='guardar-etapas'){
			$this->hist_cuentas_etapas_m->setup_validate();
			$fecha_etapa=$_POST['fecha_etapa_year'].'-'.$_POST['fecha_etapa_month'].'-'.$_POST['fecha_etapa_day'];
			$fields_save = array (
				'id_etapa' => $_POST ['id_etapa'], 
				'id_cuenta' => $id, 
				'id_administrador' => $_POST['id_administrador'],
				'fecha_etapa' => $fecha_etapa			
			 );
			if (!$this->hist_cuentas_etapas_m->save_default($fields_save,$param)){}
			else{if (empty($id)){$id=$this->db->insert_id();} redirect('admin/cuentas/form/editar/'.$id);};
		}
		if ($action=='eliminar-etapa'){
			//$this->output->enable_profiler(TRUE);
			if (!empty($param)){$this->hist_cuentas_etapas_m->eliminar($param);}
			redirect('admin/cuentas/form/editar/'.$id);
		}
		// --------------------------------------------------------------------
		if ($action=='guardar-acuerdo'){
			$this->hist_cuentas_m->setup_validate_acuerdo();
			$fecha_primer_pago=$_POST['fecha_primer_pago_year'].'-'.$_POST['fecha_primer_pago_month'].'-'.$_POST['fecha_primer_pago_day'];
			$fields_save = array (
				'id_acuerdo_pago' => $_POST['id_acuerdo_pago'],
				'fecha_primer_pago' => $fecha_primer_pago,
				'n_cuotas' => $_POST ['n_cuotas'], 
				'valor_cuota' => $_POST ['valor_cuota'], 
				'dia_vencimiento_cuota' => $_POST['dia_vencimiento_cuota']	
			 );
			if (!$this->hist_cuentas_m->save_default($fields_save,$id)){}
			else{if (empty($id)){$id=$this->db->insert_id();} /*redirect('admin/cuentas/form/editar/'.$id);*/};
		}
		// --------------------------------------------------------------------
		if ($action=='guardar-pagos'){
			//$this->output->enable_profiler(FALSE);
			if (isset($_POST['fecha_pago_year']) && isset($_POST['fecha_pago_month']) && isset($_POST['fecha_pago_day'])){ 
				$fecha_pago=$_POST['fecha_pago_year'].'-'.$_POST['fecha_pago_month'].'-'.$_POST['fecha_pago_day'];
			}
			/*comprobante 25-04-2012 */
			$id_comprobante = '';
			$fields_save = array();
			if ($param==''){
				
				if (!empty($fecha_pago) && $fecha_pago!='--'){$fields_save['fecha_pago'] = $fecha_pago;}
				$fields_save['verify_sign'] = uniqid();
				
			} else{
				$t = $this->hist_cuentas_pagos_m->get_by(array('id'=>$param));
				if (count($t)==1){
					$id_comprobante = $t->id_comprobante;
				}
			}	
			
			$fields_save['id_cuenta']=$id;
			if (isset($_POST['monto_pagado'])){ $fields_save['monto']=$_POST['monto_pagado'];}
			if (isset($_POST['forma_pago'])){ $fields_save['forma_pago']=$_POST['forma_pago'];}
			
			
			$this->comprobantes_m->setup_validate();
			if (!$this->comprobantes_m->save_default($fields_save,$id_comprobante)){}
			else{if (empty($id_comprobante)){$id_comprobante=$this->db->insert_id();}};
			
			/*comprobante 25-04-2012*/
			
			
			$this->hist_cuentas_pagos_m->setup_validate();
			
			/* 25-04-2012 */ $fields_save = array();
			if (!empty($fecha_pago) && $fecha_pago!='--'){$fields_save['fecha_pago'] = $fecha_pago;}
			if (isset($_POST['n_comprobante_interno'])){ $fields_save['n_comprobante_interno'] = $_POST['n_comprobante_interno'];}
			/* 25-04-2012 */ if (isset($id_comprobante)){ $fields_save['id_comprobante'] = $id_comprobante;}
			if (!empty($_POST ['monto_pagado'])){ $fields_save['monto_pagado'] = $_POST ['monto_pagado'];}
			if (!empty($_POST ['honorarios'])){ $fields_save['honorarios'] = $_POST ['honorarios'];}
			$fields_save['monto_remitido'] = $fields_save['monto_pagado'];
			if (!empty($_POST ['honorarios'])){ $fields_save['monto_remitido'] = $fields_save['monto_remitido'] - $fields_save['honorarios'];}
			$fields_save['id_cuenta'] = $id;
			//$this->output->enable_profiler(TRUE);
			if (!$this->hist_cuentas_pagos_m->save_default($fields_save,$param)){}
			else{/*if (empty($id)){$id=$this->db->insert_id();}*/ 
				redirect('admin/cuentas/form/editar/'.$id);
			}
		}
		if ($action=='eliminar-pago'){
			//$this->output->enable_profiler(TRUE);
			if (!empty($param)){
				$this->hist_cuentas_pagos_m->eliminar($param);
				$t = $this->hist_cuentas_pagos_m->get_by(array('id'=>$param));
				if (count($t)==1){
					$this->comprobantes_m->eliminar($t->id_comprobante); 
				}
			}
			redirect('admin/cuentas/form/editar/'.$id);
		}
		// --------------------------------------------------------------------
		if ($action=='guardar-gastos'){
			//$this->output->enable_profiler(TRUE);
			$this->hist_cuentas_gastos_m->setup_validate();
			$fecha=$_POST['fecha_year'].'-'.$_POST['fecha_month'].'-'.$_POST['fecha_day'];
			
			if (!empty($fecha) && $fecha!='--'){ $fields_save ['fecha'] = $fecha;}
			if (!empty($_POST['n_boleta'])){ $fields_save['n_boleta'] = $_POST['n_boleta'];}
			if (!empty($_POST['rut_receptor'])){ $fields_save['rut_receptor'] = $_POST['rut_receptor'];}
			if (!empty($_POST['nombre_receptor'])){ $fields_save['nombre_receptor'] = $_POST[nombre_receptor];}
			if (!empty($_POST['monto'])){ $fields_save['monto'] = $_POST['monto'];}
			if (!empty($_POST['retencion'])){ $fields_save['retencion'] = $_POST['retencion'];}
			if (!empty($_POST['descripcion'])){ $fields_save['descripcion'] = $_POST['descripcion'];}
			$fields_save['id_cuenta'] = $id;
			
			if (!$this->hist_cuentas_gastos_m->save_default($fields_save,$param)){}
			else{/*if (empty($id)){$id=$this->db->insert_id();}*/ redirect('admin/cuentas/form/editar/'.$id);};
		}
		if ($action=='eliminar-gasto'){
			//$this->output->enable_profiler(TRUE);
			if (!empty($param)){$this->hist_cuentas_gastos_m->eliminar($param);}
			redirect('admin/cuentas/form/editar/'.$id);
		}
		
		/**
		 * dropdown
		 */
			$this->db->where('activo','S');
			$this->db->order_by("rut","ASC");
			$a=$this->usuarios_m->get_all();
			$this->data['usuarios'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['usuarios'][$obj->id] = $obj->rut;}
			
			$a=$this->mandantes_m->get_all();
			$this->data['mandantes'][0]='Seleccionar Mandante';
			foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->razon_social;}
			
			$a=$this->tipo_productos_m->get_all();
			$this->data['tipo_de_productos'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['tipo_de_productos'][$obj->id] = $obj->tipo;}
			
			$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'perfil' => '3'));
			$a=$this->administradores_m->get_all();
			$this->data['administradores'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['administradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
			
			$a=$this->estados_cuenta_m->get_all();
			$this->data['estados_cuenta'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
			

			$a=$this->etapas_m->order_by('codigo','ASC')->get_all();
			$this->data['etapas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['etapas'][$obj->id] = $obj->codigo.' '.$obj->etapa;}
			
		    $a=$this->tribunales_m->get_many_by( array( "padre" => '0') );
			$this->data['tribunales'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['tribunales'][$obj->id] = $obj->tribunal;}
			
			$a=$this->comunas_m->order_by("nombre","ASC")->get_many_by( array("padre" => '13') );
			$this->data['comunas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['comunas'][$obj->id] = $obj->nombre;}
			
		// --------------------------------------------------------------------
		
		if (!empty($id)){$this->data['lists']=$this->hist_cuentas_m->get_by(array('id'=>$id));}
		$this->data['procuradores'] = array();
		if (!empty($this->data['lists']->id_procurador) && $this->data['lists']->id_procurador!='0'){
			$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'id' => $this->data['lists']->id_procurador));
			$a=$this->administradores_m->get_all();
			foreach ($a as $obj) {$this->data['procuradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
		}	
		$this->data['distritos'] = array();
		if (!empty($this->data['lists']->id_tribunal) && $this->data['lists']->id_tribunal!='0'){
			$a=$this->tribunales_m->order_by("id","ASC")->get_many_by( array( "padre" => $this->data['lists']->id_tribunal) );
			$this->data['distritos'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['distritos'][$obj->id] = $obj->tribunal;}	
		}
		/**
		 * listado de actualizaciones de etapas
		 */
			$query =$this->db->select('ce.id AS id, ce.id_etapa AS id_etapa, ce.fecha_etapa AS fecha_etapa, etapa.etapa AS etapa, adm.nombres AS nombres, adm.apellidos AS apellidos, ce.id AS field_categoria')
							 ->join("0_administradores adm", "adm.id = ce.id_administrador")
							 ->join("s_etapas etapa", "etapa.id = ce.id_etapa")
							 ->where("id_cuenta", $id)
							 ->where("ce.activo","S")
							 ->order_by("ce.fecha_etapa", "desc")
			 				 ->get("hist_2_cuentas_etapas ce");
			$this->data['cuenta_etapas_listado']=$query->result();
		// --------------------------------------------------------------------
		/**
		 * listado de pagos
		 */
			//$this->data['pagos']=$this->hist_cuentas_pagos_m->order_by("fecha_pago","ASC")->get_many_by( array("id_cuenta" => $id,"activo"=>"S") );
			$cols = array();
			$cols[] = 'cp.id AS id';
			$cols[] = 'cp.id_cuenta AS id_cuenta';
			$cols[] = 'cp.fecha_pago AS fecha_pago';
			$cols[] = 'cp.monto_pagado AS monto_pagado';
			$cols[] = 'cp.honorarios AS honorarios';
			$cols[] = 'cp.monto_remitido AS monto_remitido';
			$cols[] = 'cp.n_comprobante_interno AS n_comprobante_interno';
			$cols[] = 'comp.id AS id_comprobante';
			$cols[] = 'comp.forma_pago AS forma_pago';
			$this->data['pagos'] = $this->hist_cuentas_pagos_m->get_pagos($cols,array("cp.id_cuenta" => $id,"cp.activo"=>"S"));
			
		// --------------------------------------------------------------------
		/**
		 * listado de gastos
		 */
			$this->data['gastos']=$this->hist_cuentas_gastos_m->order_by("fecha","DESC")->get_many_by( array("id_cuenta" => $id,"activo"=>"S") );
		// --------------------------------------------------------------------
		/**
		 * listado de historial
		 */
			$this->data['historiales']=$this->hist_cuentas_historial_m->order_by("fecha","DESC")->get_many_by( array("id_cuenta" => $id,"activo"=>"S") );
		// --------------------------------------------------------------------
		$this->data['usuario']=array();
		if (count($this->data['lists'])>0){
			$this->data['usuario']=$this->usuarios_m->get_by(array('id' => $this->data['lists']->id_usuario));
		}
		
		
		$query =$this->db->select('SUM(monto_pagado) AS total')
							 ->where(array("id_cuenta"=>$id,"activo"=>'S'))			 
			 				 ->get("hist_2_cuentas_pagos pag");
		$a=$query->result();
		foreach ($a as $key=>$val){
			$this->data['total_pagado'] = $val;
		}

		//print_r($this->data['pagos']);
		$this->load->view ( 'backend/index', $this->data );
	}

	function gen($action,$id){$this->index($action,$id);}

	function index($action='',$id='') {
		//$this->output->enable_profiler(TRUE);
		$view='list'; 
		$config['uri_segment'] = '4';
		$this->data['current_pag'] = $this->uri->segment(4);
		
		$this->data['plantilla'].= $view;	
		$this->load->helper ( 'url' );	
		if ($action=='actualizar'){
			$this->hist_cuentas_m->update($id,$_POST);
			$this->show_tpl = FALSE;
			$config['uri_segment'] = '6'; 
			$this->data['current_pag'] = $this->uri->segment(6);
		}
		if ($action=='up' or $action=='down'){
			$this->hist_cuentas_m->move_up_down($_POST['posicion'],$id,$action,$_POST['field_categoria']);
			$this->show_tpl = FALSE; 
			$config['uri_segment'] = '6'; 
			$this->data['current_pag'] = $this->uri->segment(6);
		}
		if ($view=='list'){
			/*where*/
			//$this->output->enable_profiler(TRUE);
			$where=array();
	    	$where["cta.activo"] = "S";
	    	//$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
	    	$config['suffix'] = '';
	    	//if ($this->form_validation->run() == TRUE){
	    		if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){ 
	    			$where["usr.rut"] = $_REQUEST['rut'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'rut='.$_REQUEST['rut'];
	    		}
	    	//}
	    		if (isset($_REQUEST['nombres'])){
	    			if ($_REQUEST['nombres']!=''){ 
	    				$where["usr.nombres"] = $_REQUEST['nombres'];
	    				if ($config['suffix']!=''){ $config['suffix'].='&';}
	    				$config['suffix'].= 'nombres='.$_REQUEST['nombres'];
	    			}
	    		}
	    		if (isset($_REQUEST['ap_pat'])){if ($_REQUEST['ap_pat']!=''){ 
	    			$where["usr.ap_pat"] = $_REQUEST['ap_pat'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'ap_pat='.$_REQUEST['ap_pat'];
	    		}}
	    		if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){ 
	    			$where["cta.id_procurador"] = $_REQUEST['id_procurador'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
	    		}}
	    		if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']>0){ 
	    			$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
	    		}}
			if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/cuentas/index/';
	    	$query_total = 
	    		$this->db->where("cta.activo","S")
	    				 ->where($where)
	    				 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
						 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	
						 ->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")	
						 //->join("hist_2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S'","left")	
						 ->group_by("cta.id")
						 ->get("hist_0_cuentas cta");
						 //->count_all_results("hist_0_cuentas cta");
			$total_rows = $query_total->result();
			$config['total_rows'] = count($total_rows);
	    	$config['per_page'] = '30'; 
	    	
	    	//$config['num_links'] = '10';
	    	
	    	
	    	$this->pagination->initialize($config);
			/*listado SUM(pag.monto_remitido) AS total*/
			$query =$this->db->select('cta.id AS id, cta.activo AS activo, cta.publico AS publico, cta.posicion AS posicion, cta.id_procurador, usr.nombres AS nombres,usr.ap_pat AS ap_pat,usr.ap_mat AS ap_mat, usr.rut AS rut, mand.razon_social, tip.tipo AS tipo_producto, cta.fecha_asignacion AS fecha_asignacion, cta.monto_demandado AS monto_demandado, cta.monto_deuda AS monto_deuda, cta.id_estado_cuenta AS id_estado_cuenta, cta.id_mandante AS field_categoria')
							 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
							 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	
							 ->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")	
							 //->join("hist_2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S'","left")			 
							 ->where($where)
							 ->order_by("id_mandante", "desc")
							 ->order_by("cta.fecha_asignacion", "desc")
							 ->group_by("cta.id")
			 				 ->get("hist_0_cuentas cta",$config['per_page'],$this->data['current_pag']);
			$this->data['lists']=$query->result();
			$this->data['total']=$config['total_rows'];
			/*posiciones*/
			$query = $this->db->select('id_mandante AS field_categoria, MAX(posicion) AS max_posicion, MIN(posicion) AS min_posicion')->group_by("id_mandante")->get("hist_0_cuentas");
			foreach ($query->result() as $key=>$val){
				$this->data['posiciones'][$val->field_categoria]['max_posicion']=$val->max_posicion;
				$this->data['posiciones'][$val->field_categoria]['min_posicion']=$val->min_posicion;
				$this->data['posiciones'][$val->field_categoria]['field_categoria']=$val->field_categoria;
			}
			$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'perfil' => '3'));
			$a=$this->administradores_m->get_all();
			$this->data['procuradores'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['procuradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
			$this->data['mandantes'][0]='Seleccionar';
			$a=$this->mandantes_m->get_all();
			foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->razon_social;}
			
			
			if (!$this->show_tpl){ 
				$this->data['plantilla'] = 'cuentas/list_tabla'; 
				$this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );
			}

			
		}
			
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}
	}
	
	function reporte($tipo = 'pagos', $param = ''){
		//$this->output->enable_profiler(TRUE);
		$this->data['sub_current'] = $tipo;
		$config['uri_segment'] = '5';
		$this->data['current_pag'] = $this->uri->segment(5);
		$config['suffix'] = '';
		/*where*/
		$where=array(); $like=array();
	    $where["cta.activo"] = "S";
	    /*inicializar paginacion*/
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/admin/cuentas/reporte/'.$tipo;
	    $config['per_page'] = '30'; //$config['num_links'] = '10';
	    /************************* PAGOS *************************/
	    if ($tipo == 'pagos'){
	    	$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
		    if ($this->form_validation->run() == TRUE){
		    	if ($_REQUEST['rut']!=''){ 
		    		$where["usr.rut"] = $_REQUEST['rut'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    	}
		    }
		    if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){ 
		    	$where["cta.id_procurador"] = $_REQUEST['id_procurador'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
	    		$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
		    }}
		    if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']>0){ 
		    	$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
	    		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
		    }}
	    	$dia_i = '';
		    if (isset($_REQUEST['fecha_day'])){ 
		    	if ($_REQUEST['fecha_day']>0){$dia_i = $_REQUEST['fecha_day'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_day='.$_REQUEST['fecha_day'];
		    }
		    $mes_i = '';
		    if (isset($_REQUEST['fecha_month'])){ 
		    	if ($_REQUEST['fecha_month']>0){$mes_i = '-'.$_REQUEST['fecha_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_month='.$_REQUEST['fecha_month'];
		    }
		    $year_i = '';
		    if (isset($_REQUEST['fecha_year'])){ 
		    	if ($_REQUEST['fecha_year']>0){$year_i = $_REQUEST['fecha_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_year='.$_REQUEST['fecha_year'];
		    }
		    $like["pagos.fecha_pago"]=$year_i.$mes_i.$dia_i;
	    	$dia_f = '';
		    if (isset($_REQUEST['fecha_f_day'])){ 
		    	if ($_REQUEST['fecha_f_day']>0){$dia_f = $_REQUEST['fecha_f_day'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_day='.$_REQUEST['fecha_f_day'];
		    }
	    	$mes_f = '';
		    if (isset($_REQUEST['fecha_f_month'])){ 
		    	if ($_REQUEST['fecha_f_month']>0){$mes_f = '-'.$_REQUEST['fecha_f_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_month='.$_REQUEST['fecha_f_month'];
		    }
		    $year_f = '';
		    if (isset($_REQUEST['fecha_f_year'])){ 
		    	if ($_REQUEST['fecha_f_year']>0){$year_f = $_REQUEST['fecha_f_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_year='.$_REQUEST['fecha_f_year'];
		    }
		    $between = ''; $like=array(); $where_fecha = "";
		    if ($mes_f =='' && $year_f==''){ $like["pagos.fecha_pago"]=$year_i.$mes_i;}
		    else { 
		    	if ($dia_f == '' && $dia_i == ''){ 
		    		$where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i."01' AND '".$year_f.$mes_f."31'";
		    	} 
		    	if ($dia_f != '' && $dia_i == ''){ 
		    		$where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i."01' AND '".$year_f.$mes_f.$dia_f."'";
		    	}
		    	if ($dia_f == '' && $dia_i != ''){ 
		    		$where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i.$dia_i."' AND '".$year_f.$mes_f."31'";
		    	}
		    	if ($dia_f != '' && $dia_i != ''){ 
		    		$where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i.$dia_i."' AND '".$year_f.$mes_f.$dia_f."'";
		    	}
		    }
		    if ($where_fecha!=''){$this->db->where($where_fecha,NULL,FALSE);}
		    $config['total_rows'] = $this->db->where($where)->where(array("pagos.activo"=>"S"))->like($like)
	    								  ->join("0_usuarios usr", "usr.id = cta.id_usuario")
								 		  ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 		  ->join("0_administradores adm", "adm.id = cta.id_procurador")
	    								  ->join("hist_2_cuentas_pagos pagos", "pagos.id_cuenta = cta.id")
	    								  ->count_all_results("hist_0_cuentas cta");
		    
		    
		    $select_normal = 'cta.id AS id, cta.activo AS activo, cta.publico AS publico, cta.posicion AS posicion, adm.nombres AS nombres, adm.apellidos AS apellidos, mand.razon_social, pagos.fecha_pago AS fecha_pago, pagos.monto_pagado AS monto_pagado, cta.monto_deuda AS monto_deuda, usr.nombres AS usr_nombres, usr.ap_pat AS usr_ap_pat, usr.ap_mat AS usr_ap_mat, usr.rut AS rut, cta.id_mandante AS field_categoria, pagos.n_comprobante_interno AS n_comprobante_interno';
			$select_export = 'cta.id AS id, adm.nombres AS nombres, adm.apellidos AS apellidos, mand.razon_social, pagos.fecha_pago AS fecha_pago, pagos.monto_pagado AS monto_pagado, cta.monto_deuda AS monto_deuda, usr.nombres AS usr_nombres, usr.ap_pat AS usr_ap_pat, usr.ap_mat AS usr_ap_mat, usr.rut AS rut, pagos.n_comprobante_interno AS n_comprobante_interno';
		    
			if ($param == 'exportar'){
				$select = $select_export;
			}
			else{
				$select = $select_normal;
				$this->db->limit($config['per_page'],$this->data['current_pag']);
			}
			
	    	if ($where_fecha!=''){$this->db->where($where_fecha,NULL,FALSE);}
			
		    $query_master =$this->db->select($select)
								 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
								 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 ->join("0_administradores adm", "adm.id = cta.id_procurador")
								 ->join("hist_2_cuentas_pagos pagos", "pagos.id_cuenta = cta.id")			 
								 ->where($where)->where(array("pagos.activo"=>"S"))
								 ->like($like)
								 ->order_by("id_mandante", "desc")
								 ->order_by("cta.posicion", "desc")
				 				 ->get("hist_0_cuentas cta");
			$this->data['lists']=$query_master->result();
			
	    	$array_csv[]=array(utf8_decode('Código Cuenta'),'Nombre Procurador','Apellido Procurador','Mandante','Fecha de Pago','Monto Pagado','Monto Deuda','Nombre Deudor','Apellido Deudor','Apellido Materno Deudor','Rut','Comprobante');
			foreach ($this->data['lists'] as $obj) {
				$array_csv[] = array($obj->id,utf8_decode($obj->nombres),utf8_decode($obj->apellidos),utf8_decode($obj->razon_social),$obj->fecha_pago,$obj->monto_pagado,$obj->monto_deuda,utf8_decode($obj->usr_nombres),utf8_decode($obj->usr_ap_pat),utf8_decode($obj->usr_ap_mat),$obj->rut,$obj->n_comprobante_interno);
			}
			
			$query = $this->db->select("id_cuenta")->select_sum("monto_pagado")->select_sum("honorarios")->group_by("id_cuenta")->get("hist_2_cuentas_pagos");
			$a = $query->result();
	    	foreach ($a as $obj) {$this->data['saldo'][$obj->id_cuenta] = $obj->monto_pagado-$obj->honorarios;}
			
			/*paginacion*/
	    	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='cuentas/reportes/reporte_pagos';
	    }//pagos
	     /************************* ETAPAS *************************/
	    if ($tipo == 'etapas'){
	    	//$this->output->enable_profiler(TRUE);
	    	$config['suffix']=''; $group_by = '';
	    	
	    	$a=$this->etapas_m->order_by('codigo','ASC')->get_all(); 
			$this->data['etapas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['etapas'][$obj->id] = $obj->codigo.' '.$obj->etapa;}
	    	
	    	$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
		    if ($this->form_validation->run() == TRUE){
		    	if ($_REQUEST['rut']!=''){ 
		    		$where["usr.rut"] = $_REQUEST['rut'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    	}
		    }
		    	if ($_REQUEST['agrupar']=='S'){
		    		$group_by = "cta.id_usuario";
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'agrupar='.$_REQUEST['agrupar'];
		    	}
		    	if ($_REQUEST['id_procurador']>0){ 
		    		$where["cta.id_procurador"] = $_REQUEST['id_procurador'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
		    	}
		    	if ($_REQUEST['id_mandante']>0){ 
		    		$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    	}
	    		if ($_REQUEST['etapa']>0){ 
		    		$where["etapas.id"] = $_REQUEST['etapa'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'etapa='.$_REQUEST['etapa'];	
		    	}
		    	$mes_i = '';
		    	if (isset($_REQUEST['fecha_etapa_month'])){ 
		    		if ($_REQUEST['fecha_etapa_month']>0){
		    			$mes_i = '-'.$_REQUEST['fecha_etapa_month'].'-';
		    			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    			$config['suffix'].= 'fecha_etapa_month='.$_REQUEST['fecha_etapa_month'];
		    		}	
		    	}
		    	$year_i = '';
		    	if (isset($_REQUEST['fecha_etapa_year'])){ 
		    		if ($_REQUEST['fecha_etapa_year']>0){ 
		    			$year_i = $_REQUEST['fecha_etapa_year'];
		    			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    			$config['suffix'].= 'fecha_etapa_year='.$_REQUEST['fecha_etapa_year'];	
		    		}
		    	}
		    	$like["cetapa.fecha_etapa"]=$year_i.$mes_i;
		    	/*$mes_f = '';
		    	if (isset($_REQUEST['fecha_etapa_f_month'])){ 
		    		if ($_REQUEST['fecha_etapa_f_month']>0){$mes_f = '-'.$_REQUEST['fecha_etapa_f_month'].'-';}
		    	}
		    	$year_f = '';
		    	if (isset($_REQUEST['fecha_etapa_year'])){ 
		    		if ($_REQUEST['fecha_etapa_f_year']>0){$year_f = $_REQUEST['fecha_etapa_f_year'];}
		    	}
		    	$like["cetapa.fecha_etapa"]=$year_f.$mes_f;*/
		    
	    	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
	    	
	    	
	    	
	    	$config['total_rows'] = $this->db->where($where)
	    								  	 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
											 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
											 ->join("0_administradores adm", "adm.id = cta.id_procurador")
											 ->join("hist_2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id")
											 ->join("s_tribunales dist", "dist.id = cta.id_distrito")	
											 ->join("s_tribunales trib", "trib.id = cta.id_tribunal")	
											 ->join("s_etapas etapas", "etapas.id = cetapa.id_etapa")	
											 ->join("s_estado_cuenta estado", "estado.id = cta.id_estado_cuenta") 
								 			 ->like($like)->group_by($group_by)
	    								  	 ->count_all_results("hist_0_cuentas cta");
	    	
	    	/*$config['total_rows']  = count($query_total->result());
	    	echo 'test2';*/
	    	if ($param == 'exportar'){}
			else{
				$this->db->limit($config['per_page'],$this->data['current_pag']);
			}
			$this->db->where($where);
			$query_master =$this->db->select('cta.id AS id, cta.activo AS activo, cta.publico AS publico, cta.posicion AS posicion, usr.rut AS rut, cta.rol AS rol, adm.nombres AS nombres, adm.apellidos AS apellidos, mand.razon_social, cetapa.fecha_etapa AS fecha_etapa, etapas.etapa AS etapa, trib.tribunal AS tribunal, dist.tribunal AS distrito, usr.nombres AS usr_nombres, usr.ap_pat AS usr_ap_pat, usr.ap_mat AS usr_ap_mat, usr.direccion AS direccion, usr.direccion_numero AS direccion_numero, usr.direccion_dpto AS direccion_dpto, usr.ciudad AS ciudad, com.nombre AS comuna, estado.estado AS estado, cta.id_mandante AS field_categoria')
								 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
								 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 ->join("0_administradores adm", "adm.id = cta.id_procurador")
								 ->join("hist_2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id")
								 ->join("s_tribunales dist", "dist.id = cta.id_distrito")	
								 ->join("s_tribunales trib", "trib.id = cta.id_tribunal")	
								 ->join("s_etapas etapas", "etapas.id = cetapa.id_etapa")
								 ->join("s_estado_cuenta estado", "estado.id = cta.id_estado_cuenta") 
								 ->join("s_comunas com", "com.id = usr.id_comuna","left") 
								 ->where($where)
								 ->like($like)
								 //->order_by("id_mandante", "DESC")
								 //->group_by($group_by)
								 ->order_by("cetapa.fecha_etapa", "DESC")
				 				 ->get("hist_0_cuentas cta");
			$this->data['lists']=$query_master->result();
			
			//echo count($this->data['lists']);
			
			if ($_REQUEST['agrupar']=='S'){
				$master_key = array();
				if (count($this->data['lists'])>0){
					foreach ($this->data['lists'] as $key=>$val){
						if (!in_array($val->id, $master_key)){ 
							$master_key[] = $val->id;
							$final_lists[] = $this->data['lists'][$key];
						}
					}
				}
				$this->data['lists'] = array();
				$this->data['lists'] = $final_lists;
			}			
			
	    	$array_csv = array();
	    	$array_csv[]=array('Mandante','Rut','Deudor',utf8_decode('Dirección'),'Comuna','Ciudad','Procurador','Etapa del Juicio','Fecha Etapa','Estado Cuenta','Tribunal','Distrito','Rol');    	
	    	foreach ($this->data['lists'] as $obj) {
				$array_csv[] = array(utf8_decode($obj->razon_social),$obj->rut,utf8_decode($obj->usr_nombres.' '.$obj->usr_ap_pat.' '.$obj->usr_ap_mat),utf8_decode($obj->direccion.' '.$obj->direccion_numero.' '.$obj->direccion_dpto),utf8_decode($obj->comuna),utf8_decode($obj->ciudad),utf8_decode($obj->nombres.' '.$obj->apellidos),$obj->etapa,date("d-m-Y", strtotime($obj->fecha_etapa)),$obj->estado,$obj->tribunal,$obj->distrito,$obj->rol);
			}
			
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='cuentas/reportes/reporte_etapas';
	    }//etapas
	     /************************* GASTOS *************************/
	    if ($tipo == 'gastos'){
	    	//$this->output->enable_profiler(TRUE);
	    	$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
		    if ($this->form_validation->run() == TRUE){
		    	if ($_REQUEST['rut']!=''){ 
		    		$where["gastos.rut_receptor"] = $_REQUEST['rut'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    	}
		    }
	    	if ($_REQUEST['n_boleta']!=''){ 
		    	$where["gastos.n_boleta"] = $_REQUEST['n_boleta'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'n_boleta='.$_REQUEST['n_boleta'];
		    }
	    	if ($_REQUEST['id_mandante']>0){ 
		    	$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    }
	    	if ($_REQUEST['id_procurador']>0){ 
		    	$where["adm.id"] = $_REQUEST['id_procurador'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];	
		    }
	    	$mes_i = '';
		    if (isset($_REQUEST['fecha_month'])){ 
		    	if ($_REQUEST['fecha_month']>0){$mes_i = '-'.$_REQUEST['fecha_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_month='.$_REQUEST['fecha_month'];
		    }
		    $year_i = '';
		    if (isset($_REQUEST['fecha_year'])){ 
		    	if ($_REQUEST['fecha_year']>0){$year_i = $_REQUEST['fecha_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_year='.$_REQUEST['fecha_year'];
		    }
		    $like["gastos.fecha"]=$year_i.$mes_i;
		    
		    $mes_f = '';
		    if (isset($_REQUEST['fecha_f_month'])){ 
		    	if ($_REQUEST['fecha_f_month']>0){$mes_f = '-'.$_REQUEST['fecha_f_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_month='.$_REQUEST['fecha_f_month'];
		    }
		    $year_f = '';
		    if (isset($_REQUEST['fecha_f_year'])){ 
		    	if ($_REQUEST['fecha_f_year']>0){$year_f = $_REQUEST['fecha_f_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_year='.$_REQUEST['fecha_f_year'];
		    }
		    $between = ''; $like=array();
		    if ($mes_f =='' && $year_f==''){ $like["gastos.fecha"]=$year_i.$mes_i;}
		    else { $this->db->where("`gastos`.`fecha` BETWEEN '".$year_i.$mes_i."01' AND '".$year_f.$mes_f."31'",NULL,FALSE);}
	    	
		    $config['total_rows'] = $this->db->where($where)->like($like)
	    								     ->join("0_usuarios usr", "usr.id = cta.id_usuario")
								 			 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 			 ->join("0_administradores adm", "adm.id = cta.id_procurador")
								 			 ->join("hist_2_cuentas_gastos gastos", "gastos.id_cuenta = cta.id")	
	    								  	 ->count_all_results("hist_0_cuentas cta");
		    
	    	if ($param == 'exportar'){
			}
			else{
				$this->db->limit($config['per_page'],$this->data['current_pag']);
			}
			$query_master =$this->db->select('cta.id AS id, cta.activo AS activo, cta.publico AS publico, cta.posicion AS posicion, usr.rut AS rut, cta.rol AS rol, adm.nombres AS nombres, adm.apellidos AS apellidos, mand.razon_social, gastos.fecha AS fecha, gastos.n_boleta AS n_boleta, gastos.rut_receptor AS rut_receptor, gastos.nombre_receptor AS nombre_receptor, gastos.monto AS monto, gastos.retencion AS retencion, gastos.descripcion AS descripcion, cta.id_mandante AS field_categoria')
								 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
								 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 ->join("0_administradores adm", "adm.id = cta.id_procurador")
								 ->join("hist_2_cuentas_gastos gastos", "gastos.id_cuenta = cta.id")		 
								 ->where($where)
								 ->like($like)
								 ->order_by("id_mandante", "desc")
								 ->order_by("posicion", "desc")
								 //->group_by("gastos.n_boleta")
				 				 ->get("hist_0_cuentas cta");
			$this->data['lists']=$query_master->result();
			$array_csv = array();
	    	$array_csv[]=array('Fecha','Mandante',utf8_decode('Nº Boleta'),'Rut Receptor','Nombre Receptor','Monto',utf8_decode('Retención 10%'),utf8_decode('Descripción'));
			foreach ($this->data['lists'] as $obj) {
				$array_csv[] = array(date("d-m-Y", strtotime($obj->fecha)),utf8_decode($obj->razon_social),$obj->n_boleta,$obj->rut_receptor,$obj->nombre_receptor,$obj->monto,$obj->retencion,utf8_decode($obj->descripcion));
			}
				 				 
			
	    	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='cuentas/reportes/reporte_gastos';
	    }//etapas
		
		/************************* ESTADOS *************************/
	    if ($tipo == 'estados'){
	    	//$where = array();
	    	//echo 'REPORTE DE ESTADOS (EN DESARROLLO)';
	    	//$this->output->enable_profiler(TRUE);
	    	$config['suffix']=''; $group_by = '';
	    	
	    	$a=$this->etapas_m->order_by('codigo','ASC')->get_all(); 
			$this->data['etapas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['etapas'][$obj->id] = $obj->codigo.' '.$obj->etapa;}
	    	
	    	$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
		    if ($this->form_validation->run() == TRUE){
		    	if ($_REQUEST['rut']!=''){ 
		    		$where["usr.rut"] = $_REQUEST['rut'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    	}
		    }
		    	
		    	
		    	if ($_REQUEST['id_mandante']>0){ 
		    		$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    	}
	    		if ($_REQUEST['etapa']!='' && $_REQUEST['etapa']>0){ 
		    		$where["etapas.id"] = $_REQUEST['etapa'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'etapa='.$_REQUEST['etapa'];	
		    	}
	    		if ($_REQUEST['estado']!='' && $_REQUEST['estado']>=0){ 
	    			//echo $_REQUEST['estado'];
		    		$where["estado.id"] = $_REQUEST['estado'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'estado='.$_REQUEST['estado'];	
		    	}
		    	
		    	
		    	
		    	$mes_i = '';
		    	if (isset($_REQUEST['fecha_etapa_month'])){ 
		    		if ($_REQUEST['fecha_etapa_month']>0){
		    			$mes_i = '-'.$_REQUEST['fecha_etapa_month'].'-';
		    			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    			$config['suffix'].= 'fecha_etapa_month='.$_REQUEST['fecha_etapa_month'];
		    		}	
		    	}
		    	$year_i = '';
		    	if (isset($_REQUEST['fecha_etapa_year'])){ 
		    		if ($_REQUEST['fecha_etapa_year']>0){ 
		    			$year_i = $_REQUEST['fecha_etapa_year'];
		    			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    			$config['suffix'].= 'fecha_etapa_year='.$_REQUEST['fecha_etapa_year'];	
		    		}
		    	}
		    	$like["cetapa.fecha_etapa"]=$year_i.$mes_i;
		    	
		    /*
if(empty($like["cetapa.fecha_etapa"]))
		    	$like["cetapa.fecha_etapa"] = date("Y-m");
*/
		    
	    	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
	    	
	    	////->join_sql("LEFT JOIN 2_cuentas_etapas cetapa ON cetapa.id=cta.id AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)")
	    	
	    	
	    	$query_total = $this->db->select("cta.id")->where($where)
	    								  	 ->join("0_usuarios usr", "usr.id = cta.id_usuario AND usr.activo='S' AND cta.activo='S'")
											 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
											 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
											 ->join("hist_2_cuentas_pagos pag2", "pag2.id_cuenta = cta.id AND pag2.activo='S'","left")
											 
											 //->join_sql("LEFT JOIN 2_cuentas_etapas cetapa ON cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)")
											 			
											 //->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S' AND pag.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=cta.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left")
											 
											 ->join("s_estado_cuenta estado", "estado.id = cta.id_estado_cuenta") 
											 ->join("s_comunas com", "com.id = usr.id_comuna","left") 
											 ->join("hist_2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id AND cetapa.activo='S'","left")// AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)","left")
											 ->join("s_etapas etapas", "etapas.id = cetapa.id_etapa","left")
											 //->where("(cetapa.fecha_etapa IS NULL OR cetapa.fecha_etapa = (SELECT MAX(fecha_etapa) FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta = cta.id AND 2_ce.activo='S'))")
								 			 ->where("cta.activo","S")
											 
											 ->like($like,'match','after')
											 
											 ->group_by("cta.id")
											 ->get("hist_0_cuentas cta");
	    								  	 //->count_all_results("0_cuentas cta");
	    	
			$total_rows=$query_total->result();
			$config['total_rows'] = count($total_rows);
											 
	    	/*$config['total_rows']  = count($query_total->result());
	    	echo 'test2';*/
	    	if ($param == 'exportar'){}
			else{
				$this->db->limit($config['per_page'],$this->data['current_pag']);
			}
			$this->db->where($where);
			$query_master =$this->db->select('cta.id AS id,SUM(pag2.monto_remitido) AS total, pag.fecha_pago AS fecha_pago,cta.monto_deuda AS monto_deuda, cta.fecha_asignacion AS fecha_asignacion, cta.id AS id, cta.activo AS activo, cta.publico AS publico, cta.posicion AS posicion, usr.rut AS rut, cta.rol AS rol, adm.nombres AS nombres, adm.apellidos AS apellidos, mand.razon_social, etapas.etapa AS etapa, usr.nombres AS usr_nombres, usr.ap_pat AS usr_ap_pat, usr.ap_mat AS usr_ap_mat, usr.direccion AS direccion, usr.direccion_numero AS direccion_numero, usr.direccion_dpto AS direccion_dpto, usr.ciudad AS ciudad, com.nombre AS comuna, estado.estado AS estado, estado.id AS id_estado_cuenta, cta.id_mandante AS field_categoria')
								 ->join("0_usuarios usr", "usr.id = cta.id_usuario AND usr.activo='S' AND cta.activo='S'")
								 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
								 ->join("hist_2_cuentas_pagos pag2", "pag2.id_cuenta = cta.id AND pag2.activo='S'","left")
								 
								// ->join_sql("LEFT JOIN hist_2_cuentas_etapas cetapa ON cetapa.id = (SELECT id FROM hist_2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)")
								 //->join("hist_2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id AND cetapa.activo='S' AND cetapa.id = (SELECT id FROM hist_2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)","left")
											
								 ->join("hist_2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S' AND pag.id = (SELECT id FROM hist_2_cuentas_pagos psp WHERE psp.id_cuenta=cta.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left")
								 
								 ->join("s_estado_cuenta estado", "estado.id = cta.id_estado_cuenta") 
								 ->join("s_comunas com", "com.id = usr.id_comuna","left") 
								 
								 ->join("hist_2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id AND cetapa.activo='S'","left")// AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)","left")
								 ->join("s_etapas etapas", "etapas.id = cetapa.id_etapa","left")
								// ->where("(cetapa.fecha_etapa IS NULL OR cetapa.fecha_etapa = (SELECT MAX(fecha_etapa) FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta = cta.id AND 2_ce.activo='S'))")
								 
								 ->where("cta.activo","S")
								 ->like($like,'match','after')
								 //->order_by("id_mandante", "DESC")
								 ->group_by("cta.id")
								 //->order_by("pag.fecha_pago", "DESC")
								 ->order_by("cta.fecha_asignacion", "DESC")
				 				 ->get("hist_0_cuentas cta");
			$this->data['lists']=$query_master->result();
			
			//echo count($this->data['lists']);
			
			if ($_REQUEST['agrupar']=='S'){
				$master_key = array();
				if (count($this->data['lists'])>0){
					foreach ($this->data['lists'] as $key=>$val){
						if (!in_array($val->id, $master_key)){ 
							$master_key[] = $val->id;
							$final_lists[] = $this->data['lists'][$key];
						}
					}
				}
				$this->data['lists'] = array();
				$this->data['lists'] = $final_lists;
			}			
			
	    	$array_csv = array();
	    	$array_csv[]=array('Mandante','Estado Cuenta','Rut','Deudor',utf8_decode('Monto Pagaré'),utf8_decode('Fecha Pagaré'),'Etapa del Juicio',utf8_decode('Fecha Último Pago'),'Saldo deuda');    	
	    	foreach ($this->data['lists'] as $obj) {
	    		$fecha_pago='';if ($obj->fecha_pago!='0000-00-00' && $obj->fecha_pago!=''){ $fecha_pago = date("d-m-Y", strtotime($obj->fecha_pago));}
	    		$fecha_asignacion=''; if ($obj->fecha_asignacion!='0000-00-00' && $obj->fecha_asignacion!=''){ $fecha_asignacion = date("d-m-Y", strtotime($obj->fecha_asignacion));}
	    		$deuda = 0; $pagado = 0; if ((int)$obj->total>0){ $pagado = $obj->total;}
	    		$deuda = $obj->monto_deuda-$pagado;
				$array_csv[] = array(utf8_decode($obj->razon_social),$obj->estado,$obj->rut,utf8_decode($obj->usr_nombres.' '.$obj->usr_ap_pat.' '.$obj->usr_ap_mat),$obj->monto_deuda,$fecha_asignacion,utf8_decode($obj->etapa),$fecha_pago,$deuda);
			}
			
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='cuentas/reportes/reporte_estados';
	    }
	    //estados
		
		if ($param == 'exportar'){
			$this->show_tpl = FALSE;
			$this->load->helper('csv');
	
			//$this->output->enable_profiler(TRUE);
			header('Content-Type:text/html; charset=UTF-8');  
			array_to_csv ( $array_csv, 'reporte.csv' );
			/*
			if ($tipo == 'pagos' or $tipo=='gastos') {
				array_to_csv ( $array_csv, 'reporte.csv' );
			} else {
				query_to_csv ( $query_master, TRUE, 'reporte.csv' );
			}*/
		}
		$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'perfil' => '3'));
		$a=$this->administradores_m->get_all();
		$this->data['procuradores'][0]='Seleccionar';
		foreach ($a as $obj) {$this->data['procuradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
		$this->data['mandantes'][0]='Seleccionar';
		$a=$this->mandantes_m->get_all();
		foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->razon_social;}
		
		$this->data['suffix'] = $config['suffix'];
		
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}
	}
	
	function cargar_excel( $accion = ''){
		if (!$this->session->userdata('usuario_id')){redirect('login');}
		$this->data['plantilla'] = 'cuentas/';
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
			$config['allowed_types'] = 'xls';
			$config['max_size']	= '5120';
			$config['max_width']  = '2048';
			$config['max_height']  = '1080';
			$this->load->library('upload', $config);
			
			if (! $this->upload->do_upload ("archivo_1")) {
				$this->data['error'] = array ('error' => $this->upload->display_errors () );
			} else {
				$this->data['archivos'] = array($this->upload->data());  
				rename ( $this->data['archivos'][0]['full_path'], './uploads/importar.xls' );
				if (is_file($this->data['archivos'][0]['full_path'])){
					unlink ( $this->data['archivos'][0]['full_path'] );
				}
			}
		}
		///////////////////////////////////////
		if (is_file('./uploads/importar.xls')){ $this->data['archivo'] = './uploads/importar.xls'; }
		
		$a=$this->mandantes_m->get_all();
		$this->data['mandantes'][0]='Seleccionar Mandante';
		foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->razon_social;}
		
		if ($accion == 'importar_archivo'){
			$this->form_validation->set_rules('id_mandante', 'Mandante', 'trim|required|is_natural_no_zero');
			if ($this->form_validation->run() == TRUE){
				$array_return = array();
				$array_return = $this->importar_excel_cuentas();
				$this->data['usuarios_insert'] = $array_return['usuarios_insert'];
				$this->data['usuarios_update'] = $array_return['usuarios_update'];
				$this->data['cuentas_insert'] = $array_return['cuentas_insert'];
				$this->data['cuentas_update'] = $array_return['cuentas_update'];
				$this->data['operacion'] = $array_return['operacion'];
			}
		}
		$this->load->view ( 'backend/index', $this->data );
	}	
	function importar_excel_cuentas(){
		$this->load->helper ( 'excel_reader2' );
		$array_return = array();
		$array_return['usuarios_insert'] = 0;
		$array_return['usuarios_update'] = 0;
		$array_return['cuentas_insert'] = 0;
		$array_return['cuentas_update'] =0;
		/*$this->data['plantilla'] = 'cuentas/';
		$view = 'cargar';
		$this->data['plantilla'].=$view;*/
		$this->data['operacion'] = FALSE;
		//$this->output->enable_profiler(TRUE);
			$rows_insert = array();
			$uploadpath = "./uploads/importar.xls";
			$excel = new Spreadsheet_Excel_Reader($uploadpath);
			$rowcount = $excel->rowcount($sheet_index=0); $colcount = $excel->colcount($sheet_index=0);
			for ($i=2;$i<=$rowcount;$i++){
				$arreglo_usuario = array();
				$arreglo_cuenta = array();
				$rut = '';$ap_pat = '';$ap_mat =  '';$nombres =  '';$telefono =  '';$celular =  '';$direccion =  '';
				$direccion_numero =  '';$direccion_dpto =  '';$comuna =  '';$ciudad =  '';$n_pagare =  '';$monto_deuda =  '';
				$monto_deuda = '';$fecha_asignacion = '';$distrito =  '';$rol =  '';$fecha_demanda = '';
				$monto_demandado =  '';$monto_demandado = '';$id_tipo_producto =  '';$id_estado_cuenta =  '';$id_procurador =  '';
				$obs = '';
				$rut = $excel->val ( $i, 'B', 0 ) . '-' . $excel->val ( $i, 'C', 0 );
				$ap_pat = trim($excel->val ( $i, 'D', 0 ));
				$ap_mat =  trim($excel->val ( $i, 'E', 0 ));
				$nombres =  trim($excel->val ( $i, 'F', 0 ));
				$telefono =  trim($excel->val ( $i, 'G', 0 ));
				$celular =  trim($excel->val ( $i, 'H', 0 ));
				$direccion =  trim($excel->val ( $i, 'I', 0 ));
				$direccion_numero =  trim($excel->val ( $i, 'J', 0 ));
				$direccion_dpto =  trim($excel->val ( $i, 'K', 0 ));
				$comuna =  utf8_encode(trim($excel->val ( $i, 'L', 0 )));
				$ciudad =  trim($excel->val ( $i, 'M', 0 ));
				$n_pagare =  trim($excel->val ( $i, 'N', 0 ));
				$monto_deuda =  trim($excel->val ( $i, 'O', 0 ));
				$monto_deuda = ereg_replace("[^0-9]", "", $monto_deuda);
				$fecha_asignacion = str_replace('/','-',trim($excel->val ( $i, 'P', 0 )));
			
				if (!empty($fecha_asignacion)){$fecha_asignacion = date( 'Y-m-d' , strtotime( $fecha_asignacion));}
				$distrito =  trim($excel->val ( $i, 'Q', 0 ));
				$rol =  trim($excel->val ( $i, 'R', 0 ));
				$fecha_demanda = str_replace('/','-',trim($excel->val ( $i, 'S', 0 )));
				if (!empty($fecha_demanda)){$fecha_demanda =  date( 'Y-m-d' , strtotime( $fecha_demanda));}
				$monto_demandado =  trim($excel->val ( $i, 'T', 0 ));
				$monto_demandado = ereg_replace("[^0-9]", "", $monto_demandado);
				$id_tipo_producto =  trim($excel->val ( $i, 'U', 0 ));
				$id_estado_cuenta =  trim($excel->val ( $i, 'V', 0 ));
				$id_procurador =  trim($excel->val ( $i, 'W', 0 ));
				$obs =  trim($excel->val ( $i, 'X', 0 ));

				
				$id_comuna = '';
								
				if (!empty($comuna)){
					$a=$this->comunas_m->get_many_by( array("nombre" => $comuna) );
					if (count($a)>0){foreach ($a as $obj) {$id_comuna = $obj->id;}}
				}
				if ($id_comuna == NULL){$id_comuna = '0';}
				//if ($id_comuna==0){echo $rut.' - '.$comuna.' :'.$id_comuna.'<br>';}
				 $id_tribunal = '';
				if ($distrito!=''){
					$a=$this->tribunales_m->get_many_by( array("abr" => $distrito) );
					if (count($a)>0){foreach ($a as $obj) {$id_distrito = $obj->id; $id_tribunal = $obj->padre;}}
				}
				if (!empty($rut)){
					$id=$this->usuarios_m->search_id_record_exist(array('rut'=>$rut));
					
					if (!empty($rut)){$arreglo_usuario['rut'] = $rut;}
					if (!empty($nombres)){$arreglo_usuario['nombres'] = utf8_encode($nombres);}
					if (!empty($ap_pat)){$arreglo_usuario['ap_pat'] = utf8_encode($ap_pat);}
					if (!empty($ap_mat)){$arreglo_usuario['ap_mat'] = utf8_encode($ap_mat);}
					if (!empty($telefono)){$arreglo_usuario['telefono'] = $telefono;}
					if (!empty($celular)){$arreglo_usuario['celular'] = $celular;}
					if (!empty($direccion)){$arreglo_usuario['direccion'] = utf8_encode($direccion);}
					if (!empty($direccion_numero)){$arreglo_usuario['direccion_numero'] = $direccion_numero;}
					if (!empty($direccion_dpto)){ $arreglo_usuario['direccion_dpto'] = $direccion_dpto; }
					if (!empty($id_comuna)){$arreglo_usuario['id_comuna'] = $id_comuna;}
					if (!empty($ciudad)){$arreglo_usuario['ciudad'] = utf8_encode($ciudad);}

					if (!empty($n_pagare)){$arreglo_cuenta['n_pagare'] = $n_pagare;}
					if (!empty($monto_deuda)){$arreglo_cuenta['monto_deuda'] = $monto_deuda;}
					if (!empty($monto_demandado)){$arreglo_cuenta['monto_demandado'] = $monto_demandado;}
					if (!empty($fecha_asignacion)){$arreglo_cuenta['fecha_asignacion'] = $fecha_asignacion;}
					if (!empty($rol)){$arreglo_cuenta['rol'] = $rol;}
					if (!empty($id_tribunal)){$arreglo_cuenta['id_tribunal'] = $id_tribunal;}
					if (!empty($id_distrito)){$arreglo_cuenta['id_distrito'] = $id_distrito;}
					if (!empty($fecha_demanda)){$arreglo_cuenta['fecha_inicio'] = $fecha_demanda;}
					if (!empty($id_tipo_producto)){$arreglo_cuenta['id_tipo_producto'] = $id_tipo_producto;}
					if (!empty($id_procurador)){$arreglo_cuenta['id_procurador'] = $id_procurador;}
					if (!empty($id_estado_cuenta)){$arreglo_cuenta['id_estado_cuenta'] = $id_estado_cuenta;}
					
					$arreglo_cuenta['id_mandante'] =$_POST['id_mandante'];
					$arreglo_historial = array();
					
					
					
					if ($id == ''){
						$arreglo_usuario=array_merge($arreglo_usuario,array('fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
						//print_r($arreglo_usuario);
						//print_r($arreglo_cuenta);
						//echo '<br>INSERT '.$i.'.-'.$id.'======================>';
						$id_usuario = $this->usuarios_m->insert($arreglo_usuario,TRUE,TRUE);
						$array_return['usuarios_insert']++;
						if ($id_usuario!='' && $id_usuario != NULL){
							$arreglo_cuenta = array_merge($arreglo_cuenta, array('id_usuario' => $id_usuario, 'fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
							$this->hist_cuentas_m->insert($arreglo_cuenta,TRUE,TRUE);
							$array_return['cuentas_insert']++;
							
							if (!empty($obs)){ 
								$arreglo_historial['id_cuenta'] = $this->db->insert_id();
								$arreglo_historial['historial'] = $obs;
								$arreglo_historial['fecha'] = date('Y-m-d H:i:s');
								$arreglo_historial = array_merge($arreglo_historial, array('fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
								$this->hist_cuentas_historial_m->insert($arreglo_historial,TRUE,TRUE);
							}
						}
					}else{
						if ($id!=NULL){
							$arreglo_usuario=array_merge($arreglo_usuario,array('fecha_mod' => date('Y-m-d H:i:s'),'ip_mod' => $this->input->ip_address(), 'user_mod' => $this->session->userdata('usuario_id')));
							
							//print_r($arreglo_usuario);
							//print_r($arreglo_cuenta);
							//echo '<br>UPDATE '.$i.'.-'.$id.'======================>';
							
							$this->usuarios_m->update($id,$arreglo_usuario,TRUE,TRUE);
							$array_return['usuarios_update']++;
							
							
							$id_cuenta=$this->hist_cuentas_m->search_id_record_exist(array('id_usuario'=>$id,'id_mandante' => $_POST['id_mandante']));
							if ($id_cuenta!=''){
								$arreglo_cuenta = array_merge($arreglo_cuenta, array('fecha_mod' => date('Y-m-d H:i:s'),'ip_mod' => $this->input->ip_address(), 'user_mod' => $this->session->userdata('usuario_id')));
								$this->hist_cuentas_m->update($id_cuenta,$arreglo_cuenta,TRUE,TRUE);
								$array_return['cuentas_update']++;
							} else {
	
								$arreglo_cuenta = array_merge($arreglo_cuenta, array('id_usuario' => $id, 'fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
								$this->hist_cuentas_m->insert($arreglo_cuenta,TRUE,TRUE);
								$array_return['cuentas_insert']++;
	
							}
							if (!empty($obs)){ 
								$arreglo_historial['id_cuenta'] = $id_cuenta;
								$arreglo_historial['historial'] = $obs;
								$arreglo_historial['fecha'] = date('Y-m-d H:i:s');
								$arreglo_historial = array_merge($arreglo_historial, array('fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
								
								$this->hist_cuentas_historial_m->insert($arreglo_historial,TRUE,TRUE);
							}
							
						}
					}
				}
			}
		if ($array_return['usuarios_insert'] > 0 or $array_return['usuarios_update']>0 or $array_return['cuentas_insert'] > 0 or $array_return['cuentas_update']>0){
			$array_return['operacion'] = TRUE;
		}
		unlink("./uploads/importar.xls");
		return $array_return;
		//if (count($rows_insert)>0){$this->usuarios_m->insert_many($rows_insert,TRUE,TRUE);}
	}
	function importar_excel_etapas(){
		$this->load->helper ( 'excel_reader2' );
		//$this->output->enable_profiler(TRUE);
		$rows_insert = array();
		$uploadpath = "./uploads/estados.xls";
		$excel = new Spreadsheet_Excel_Reader($uploadpath);
		$rowcount = $excel->rowcount($sheet_index=0); $colcount = $excel->colcount($sheet_index=0);
		for ($i=1;$i<=$rowcount;$i++){
			$etapa=utf8_encode($excel->val($i,'A',0)); $codigo=$excel->val($i,'B',0);
			if ($etapa!='' && $codigo!='' && $codigo!=NULL){
				$id=$this->etapas_m->search_id_record_exist('codigo',$codigo);
				$arreglo = array('etapa' => $etapa,'codigo' => $codigo);
				if ($id == ''){
					$rows_insert[]=$arreglo;
				}else{
					$this->etapas_m->update($id,$arreglo,TRUE,TRUE);
				}
			}
		}
		if (count($rows_insert)>0){$this->etapas_m->insert_many($rows_insert,TRUE,TRUE);}
	}
	
	function importar_excel_tribunales(){
		$this->load->helper ( 'excel_reader2' );
			
		$rows_insert = array();
		$uploadpath = "./uploads/tribunales.xls";
		$excel = new Spreadsheet_Excel_Reader($uploadpath);
		$rowcount = $excel->rowcount($sheet_index=0); $colcount = $excel->colcount($sheet_index=0);
		for ($i=1;$i<=$rowcount;$i++){
			$padre=$excel->val($i,'A',0); $tribunal= $excel->val($i,'B',0); $abr=$excel->val($i,'C',0);
			if ($tribunal!=''){
				$id=$this->tribunales_m->search_id_record_exist(array('tribunal'=>$tribunal));
				$arreglo = array('padre' => $padre,'tribunal' => $tribunal,'abr' => $abr);
				//print_r($arreglo); echo '<br>';
				if ($id == ''){
					$rows_insert[]=$arreglo;
				}else{
					$this->tribunales_m->update($id,$arreglo,TRUE,TRUE);
				}
			}
		}
		if (count($rows_insert)>0){$this->tribunales_m->insert_many($rows_insert,TRUE,TRUE);}
	}
	
	function cambiaEstado($id='',$est=''){
		//$this->output->enable_profiler(TRUE);
		// http://www.servicobranza.cl/app/index.php/admin/historial/cambiaestado/3/1 < $id = 3 | $est = 1, vigente
		
		$this->db->select('COUNT(id) as cuenta');
		$this->db->where('id = '.$id); 
		$query = $this->db->get('hist_0_cuentas')->result();
		$aux = 0;
		if(($est == 4 || $est == 5) || empty($est))
			$aux = 1;
		
		// INICIO SI EXISTE REGISTRO EN EL HISTORIAL
		if( $query[0]->cuenta > 0 && $aux == 0 )
		{
			//hist_2_cuentas_pagos
					$this->db->select('id');
					$this->db->where_in( 'id_cuenta', $id ); 
					$cuentas_pagos = $this->db->get('hist_2_cuentas_pagos')->result();
					foreach($cuentas_pagos as $regs1)
					{
						$this->db->query("INSERT INTO 2_cuentas_pagos(activo,public,posicion,id_cuenta,id_comprobante,fecha_vencimiento,fecha_pago,monto_pagado,monto_remitido,honorarios,saldo,n_comprobante_interno,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod,fecha_elim,user_elim)  SELECT (activo,public,posicion,id_cuenta,id_comprobante,fecha_vencimiento,fecha_pago,monto_pagado,monto_remitido,honorarios,saldo,n_comprobante_interno,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod,fecha_elim,user_elim) FROM hist_2_cuentas_pagos WHERE id = ".$regs1->id.";");
						
						$this->db->query("DELETE FROM hist_2_cuentas_pagos WHERE id = ".$regs1->id.";");
					}
		
			//hist_2_cuentas_historial
					$this->db->select('id');
					$this->db->where_in( 'id_cuenta', $id ); 
					$cuentas_historial = $this->db->get('hist_2_cuentas_historial')->result();
					foreach($cuentas_historial as $regs2)
					{
						$this->db->query("INSERT INTO 2_cuentas_historial(activo,public,posicion,id_cuenta,fecha,historial,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod,fecha_elim,user_elim) SELECT (activo,public,posicion,id_cuenta,fecha,historial,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod,fecha_elim,user_elim) FROM hist_2_cuentas_historial WHERE id = ".$regs2->id.";");
						
						$this->db->query("DELETE FROM hist_2_cuentas_historial WHERE id = ".$regs2->id.";");
					}
					
			//hist_2_cuentas_gastos
					$this->db->select('id');
					$this->db->where_in( 'id_cuenta', $id ); 
					$cuentas_gastos = $this->db->get('hist_2_cuentas_gastos')->result();
					foreach($cuentas_gastos as $regs3)
					{
						$this->db->query("INSERT INTO 2_cuentas_gastos(activo,public,posicion,id_cuenta,fecha,n_boleta,rut_receptor,nombre_receptor,monto,retencion,descripcion,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod,fecha_elim,user_elim)  SELECT (activo,public,posicion,id_cuenta,fecha,n_boleta,rut_receptor,nombre_receptor,monto,retencion,descripcion,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod,fecha_elim,user_elim) FROM hist_2_cuentas_gastos WHERE id = ".$regs3->id.";");
						
						$this->db->query("DELETE FROM hist_2_cuentas_gastos WHERE id = ".$regs3->id.";");
					}
			
			//2_cuentas_etapas
					$this->db->select('id');
					$this->db->where_in( 'id_cuenta', $id ); 
					$cuentas_etapas = $this->db->get('hist_2_cuentas_etapas')->result();
					foreach($cuentas_etapas as $regs4)
					{
						$this->db->query("INSERT INTO 2_cuentas_etapas(activo,public,posicion,id_cuenta,id_etapa,fecha_etapa,id_administrador,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod,fecha_elim,user_elim) SELECT (activo,public,posicion,id_cuenta,id_etapa,fecha_etapa,id_administrador,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod,fecha_elim,user_elim) FROM hist_2_cuentas_etapas WHERE id = ".$regs4->id.";");
						
						$this->db->query("DELETE FROM hist_2_cuentas_etapas WHERE id = ".$regs4->id.";");
					}
			
			//0_cuentas
					$this->db->query("INSERT INTO 0_cuentas(activo,public,posicion,id_usuario,id_mandante,id_procurador,id_tipo_producto,n_pagare,monto_deuda,fecha_asignacion,id_tribunal,id_distrito,rol,fecha_inicio,monto_demandado,id_administrador,id_etapa,id_estado_cuenta,bien_habitacional,bien_vehiculo,id_acuerdo_pago,n_cuotas,valor_cuota,dia_vencimiento_cuota,fecha_primer_pago,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod) SELECT (activo,public,posicion,id_usuario,id_mandante,id_procurador,id_tipo_producto,n_pagare,monto_deuda,fecha_asignacion,id_tribunal,id_distrito,rol,fecha_inicio,monto_demandado,id_administrador,id_etapa,id_estado_cuenta,bien_habitacional,bien_vehiculo,id_acuerdo_pago,n_cuotas,valor_cuota,dia_vencimiento_cuota,fecha_primer_pago,fecha_crea,ip_crea,user_crea,fecha_mod,ip_mod,user_mod) FROM hist_0_cuentas WHERE id = ".$id.";");
					
					//actualiza estado
					if($est != 0 && !empty($est))
						$this->db->query("UPDATE 0_cuentas SET id_estado_cuenta = ".$est." WHERE id = ".$this->db->insert_id().";");
					
					$this->db->query("DELETE FROM hist_0_cuentas WHERE id = ".$id.";");
		}
		// FIN SI EXISTE REGISTRO EN EL HISTORIAL		
	}
}

?>