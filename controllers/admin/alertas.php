<?php
class alertas extends CI_Controller {
	public $data = array();
	public $activo = 'S';
	protected $show_tpl = TRUE;
	public function Cuentas() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		
		$this->load->helper ( 'date_html_helper' );
		//$this->output->enable_profiler(TRUE);
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'cuentas_m' ); 
		$this->load->model ( 'cuentas_etapas_m' );
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
		$this->load->model ( 'nodo_m' );
		
		/*seters*/
		$this->data['current'] = 'alertas';
		$this->data['sub_current'] = 'etapas';
		$this->data['plantilla'] = 'alertas/';
		$this->data['lists'] = array();
		
		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
		$this->data['forma_pagos'] = array(''=>'Forma Pago','TF'=>'Transferencia','DP'=>'Depósito','CH'=>'Cheque','EF'=>'Efectivo');
		
		$nodo = $this->nodo_m->get_by( array('activo'=>'S') );
		$this->data['nodo'] = $nodo;	
		
	    $mandantes = array();
		$man=$this->mandantes_m->order_by("codigo_mandante","DESC")->get_many_by(array('activo'=>'S'));
		$mandantes[0]='Seleccionar';
		foreach ($man as $obj) {$mandantes[$obj->id] = $obj->codigo_mandante;}
		$this->data['mandantes'] = $mandantes;
		
		$c=$this->estados_cuenta_m->order_by('estado','ASC')->get_all(); 
		$this->data['estados'][-1]='Seleccionar..';
	 	foreach ($c as $obj) {$this->data['estados'][$obj->id] = $obj->estado;}
		
	 	$tribunales [''] = 'Seleccionar';
	 	$arr = $this->tribunales_m->get_many_by(array('padre'=>0));
		foreach ( $arr as $key => $val ) {
			$tribunales [$val->id] = $val->tribunal;
		}
		$this->data ['tribunales'] = $tribunales;
		
		$procuradores [''] = 'Seleccionar';
		$a=$this->administradores_m->get_many_by(array('activo'=>'S','nombres !='=>'','apellidos !='=>''));
		foreach ($a as $obj) {$procuradores[$obj->id] = $obj->nombres.' '.$obj->apellidos;}
		$this->data ['procuradores'] = $procuradores;
		
		//se ordena etapas por posicion 
	 	$b=$this->etapas_m->order_by('posicion','ASC')->get_many_by(array('activo'=>'S')); 
		$this->data['etapas'][0]='Seleccionar..';
	 	foreach ($b as $obj) {$this->data['etapas'][$obj->id] = $obj->codigo.' '.$obj->etapa;}
		
	 		//$this->output->enable_profiler(TRUE);
		
	}


	public function index($action='',$id='') {
        //$this->output->enable_profiler(TRUE);
         $this->etapas();

		
        /*
		$c1[] = $this->cuentas_m->get_columns();
		$c2[] = $this->cuentas_etapas_m->get_columns();
		$c3[] = $this->etapas_m->get_columns();
		$c4[] = $this->usuarios_m->get_columns();
		$c5[] = $this->mandantes_m->get_columns();
		
		$c = array_merge($c1, $c2, $c3, $c4, $c5);
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = str_replace('2_', '', $dato);
			}
		}
		$cols[] = "DATEDIFF( NOW() , ce.fecha_etapa ) AS dias_diferencia";

		$this->db->select($cols);
		$this->db->join('2_cuentas_etapas ce', 'ce.id = c.id_etapa');
		$this->db->join('s_etapas e', 'e.id = ce.id_etapa');
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		
		$this->db->where( "`e`.`activo` = 'S'
		AND CONVERT(DATEDIFF( NOW() , ce.fecha_etapa ),UNSIGNED INTEGER) >= CONVERT(e.dias_alerta,UNSIGNED INTEGER)
		GROUP BY c.id" );

		$query = $this->db->get('0_cuentas c');

		$config['per_page'] = '20';
		$config['uri_segment'] = '4';
		$this->data['total'] = $config['total_rows'] = count($query->result());
	    $config['base_url'] = site_url().'/admin/alertas/index/';
	    $this->data['current_pag'] = $this->uri->segment(4);


		$this->db->select($cols);
		$this->db->join('2_cuentas_etapas ce', 'ce.id = c.id_etapa');
		$this->db->join('s_etapas e', 'e.id = ce.id_etapa');
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		
		$this->db->where( "`e`.`activo` = 'S'
		AND CONVERT(DATEDIFF( NOW() , ce.fecha_etapa ),UNSIGNED INTEGER) >= CONVERT(e.dias_alerta,UNSIGNED INTEGER)
		GROUP BY c.id" );

		$query = $this->db->get('0_cuentas c',$config['per_page'],$this->data['current_pag']);

		$this->data['lists'] = $query->result();


	    $this->load->library('pagination');
	    $this->pagination->initialize($config);

		$this->data['plantilla']	= 'alertas/list'; 

*/


	}
	public function pagos(){
		//$this->output->enable_profiler(TRUE);
		$like = array();
		$config = array('suffix'=>'');
		$fecha_asignacion = '';
		
		$cols[] = 'm.codigo_mandante AS codigo_mandante';
		$cols[] = 'u.nombres AS usuarios_nombres';
		$cols[] = 'u.ap_pat AS usuarios_ap_pat';
		$cols[] = 'u.ap_mat AS usuarios_ap_mat';
		$cols[] = 'u.rut AS usuarios_rut';
		$cols[] = 'c.dia_vencimiento_cuota AS cuentas_dia_vencimiento_cuota';
		$cols[] = 'c.id AS cuentas_id';
		$cols[] = "DATEDIFF( NOW() , cp.fecha_pago ) AS dias_diferencia";
		$cols[] = "(DATEDIFF( NOW() , c.fecha_etapa ) - dias_alerta) AS dias_alerta_diferencia";
		$cols[] = "cp.fecha_pago AS fecha_pago";

	

		$having = '';
				
		/**/
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join("2_cuentas_pagos cp", "cp.id_cuenta = c.id AND cp.activo='S' AND cp.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=c.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left");					 
		if ($having!=''){
			$this->db->having($having);
		}
		$this->db->where("(c.id_estado_cuenta ='1' OR c.id_estado_cuenta='6' OR c.id_estado_cuenta='2' OR c.id_estado_cuenta='8' OR c.id_estado_cuenta='7')");
		$this->db->where('DATEDIFF( NOW() , cp.fecha_pago )>=27');
		if (count($like)>0){
			$this->db->like($like);
		}
		$this->db->group_by('c.id');
		$query = $this->db->get('0_cuentas c');
				
		/**/
		$config['per_page'] = '20';
		$config['uri_segment'] = '4';
		$this->data['total'] = $config['total_rows'] = count($query->result());
	    $config['base_url'] = site_url().'/admin/alertas/pagos/';
	    $this->data['current_pag'] = $this->uri->segment(4);

		/**/
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join("2_cuentas_pagos cp", "cp.id_cuenta = c.id AND cp.activo='S' AND cp.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=c.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left");

		if ($having!=''){
			$this->db->having($having);
		}
		//$this->db->where('DATEDIFF( NOW() , cp.fecha_pago )>=27');
		$this->db->where("(c.id_estado_cuenta ='1' OR c.id_estado_cuenta='6' OR c.id_estado_cuenta='2' OR c.id_estado_cuenta='8' OR c.id_estado_cuenta='7')");
		if (count($like)>0){
			$this->db->like($like);
		}
		$this->db->group_by('c.id');
		$this->db->order_by('dias_diferencia asc,usuarios_ap_pat ASC,usuarios_ap_mat ASC');
		$query = $this->db->get('0_cuentas c',$config['per_page'],$this->data['current_pag']);
		/**/
		$lists = $query->result();
        $this->data['lists'] = $lists;
		
		$this->load->library('pagination');
	    $this->pagination->initialize($config);
	    $this->data['sub_current'] = 'pagos';
		$this->data['plantilla'] = 'alertas/pagos/list'; 
		$this->load->view ( 'backend/index', $this->data );
	}
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	////////////////////////COLUMNAS ALERTAS///////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////
	public function etapas(){

	    //$this->output->enable_profiler(TRUE);
		//$this->output->enable_profiler(TRUE);
		
		$like = array();
		$where = array();
		
		$config = array('suffix'=>'');
		//$config['base_url'] = site_url().'/admin/alertas/etapas/';
		
		$fecha_asignacion = '';
		
		$cols[] = 'c.id_etapa AS id_etapa';
		$cols[] = 'm.codigo_mandante AS codigo_mandante';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'u.nombres AS usuarios_nombres';
		$cols[] = 'u.ap_pat AS usuarios_ap_pat';
		$cols[] = 'u.ap_mat AS usuarios_ap_mat';
		$cols[] = 'u.rut AS usuarios_rut';
		$cols[] = 'c.id AS cuentas_id';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.exorto AS exorto';		
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'p.fecha_asignacion AS fecha_asignacion_pagare';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'e.dias_alerta AS dias_alerta';
		$cols[] = "DATEDIFF( NOW() , c.fecha_etapa ) AS dias_alerta_diferencia";
	    //$cols[] = "(DATEDIFF( NOW() , c.fecha_etapa ) - dias_alerta) AS dias_alerta_diferencia";
        $cols[] = 'e.etapa AS etapa';
		$cols[] = 'e.texto_alerta AS texto_alerta';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'adm.apellidos AS apellidos_adm';
		$cols[] = 'trib.tribunal AS tribunal_padre';
        $cols[] = 'tri.tribunal AS tribunal';	
		$cols[] = 'disE.tribunal as DistritoE';
        $cols[] = 'trie.tribunal as TribunalE';
		$cols[] = 'c.rolE';
		$cols[] = 'DATEDIFF( NOW() , c.fecha_asignacion ) AS dias_';
		$cols[] = 'DATEDIFF( NOW() , c.fecha_asignacion ) AS diass_';
		$cols[] = 'c.operacion';
		$cols[] = 'c.acuse AS acuse';
		$cols[] = 'c.titular_personeria AS titular';

		$having = '';
				
		/**/	

		
		$this->db->select($cols);
		//$this->db->join('s_estado_cuenta sec', 'sec.id = c.id_estado_cuenta');	
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante','left');
		//$this->db->join("2_cuentas_etapas ce", "ce.id_cuenta = c.id AND ce.activo='S' AND ce.id_etapa = c.id_etapa");	
		$this->db->join('s_etapas e', 'e.id = c.id_etapa','left');	
		$this->db->join('pagare p', 'p.idcuenta = c.id','left');
		$this->db->join('0_administradores adm','adm.id = c.id_procurador','left');
		$this->db->join("s_tribunales tri", "tri.id = c.id_tribunal","left");
		$this->db->join("s_tribunales trib", "trib.id = tri.padre","left");
		$this->db->join("s_tribunales trie", "trie.id = c.id_tribunal_ex","left");
		$this->db->join("s_tribunales disE", "disE.id = c.id_distrito_ex","left");

		
		/*echo '<pre>';
		print_r($this->db->join);
			echo '</pre>';
		*/		
		
		if ($having!=''){
			$this->db->having($having);
		}
		
		if (isset($_REQUEST['Operacion']) && $_REQUEST['Operacion']>0)
		{
			if ($_REQUEST['Operacion']>0){ 				
	    			$where["c.operacion"] = $_REQUEST['Operacion'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'Operacion='.$_REQUEST['Operacion'];
			}
		}
		
		if (isset($_REQUEST['Nombre'])){ 
			if ($_REQUEST['Nombre']>0){
					$like["u.nombres"] = $_REQUEST['Nombre'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'Nombre='.$_REQUEST['Nombre'];
			}
	    }
		//print_r($_REQUEST);
		if (isset($_REQUEST['ap_mat']) && $_REQUEST['ap_mat']!=''){ 
			
				$like["u.ap_mat"] = $_REQUEST['ap_mat'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'ap_mat='.$_REQUEST['ap_mat'];
			
	    }
		//echo $_REQUEST['ap_pat'];
		if (isset($_REQUEST['ap_pat']) && $_REQUEST['ap_pat']!=''){ 
			
				$like["u.ap_pat"] = $_REQUEST['ap_pat'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'ap_pat='.$_REQUEST['ap_pat'];
			
	    }
		
	    
	 	if (isset($_REQUEST['Rut']) && $_REQUEST['Rut']>0 ){ 
	    			$like["u.rut"] = $_REQUEST['Rut'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'Rut='.$_REQUEST['Rut'];
	    }
		
		
	    if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']>0){ 
	    			$where["c.id_mandante"] = $_REQUEST['id_mandante'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
	    }}
				
		if (isset($_REQUEST['rol']) && $_REQUEST['rol']>0){ 
	    			$like["c.rol"] = $_REQUEST['rol'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'rol='.$_REQUEST['rol'];
	    }
	    
	 	if (isset($_REQUEST['id_estado_cuenta']) && $_REQUEST['id_estado_cuenta']!='' && $_REQUEST['id_estado_cuenta']>='0'){ 
	    			$where["c.id_estado_cuenta"] = $_REQUEST['id_estado_cuenta'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
	    }
	    
		if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){
	    			$where["c.id_procurador"] = $_REQUEST['id_procurador'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
	    }} 
	    
	    if (isset($_REQUEST['id_tribunal']) && $_REQUEST['id_tribunal']>0){
	    			$where["c.id_tribunal"] = $_REQUEST['id_tribunal'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
    				$config['suffix'].= 'id_tribunal='.$_REQUEST['id_tribunal'];
	    }	

		if (isset($_REQUEST['id_etapa']) && $_REQUEST['id_etapa']>0){ 
	    			$where["c.id_etapa"] = $_REQUEST['id_etapa'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_etapa='.$_REQUEST['id_etapa'];
	    }
	    
		//Inicio Order by
		
		if (isset($_REQUEST['mandante']) && $_REQUEST['mandante']!=''){
			if ($_REQUEST['mandante'] == 'desc'){
				$order_by ='codigo_mandante desc';  
			} else {
				$order_by = 'codigo_mandante asc';  
			}
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'mandante='.$_REQUEST['mandante'];
		}
		
		if (isset($_REQUEST['fechaa']) && $_REQUEST['fechaa']!=''){
			if ($_REQUEST['fechaa'] == 'desc'){
				$order_by ='fecha_asignacion desc';  
			} else {
				$order_by = 'fecha_asignacion asc';  
			}
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'fechaa='.$_REQUEST['fechaa'];
		}
		
//print_r($_REQUEST);



		if (isset($_REQUEST['diasdiferencia']) && $_REQUEST['diasdiferencia']!=''){
			if ($_REQUEST['diasdiferencia'] == 'desc'){
				$order_by ='DATEDIFF( NOW() , c.fecha_etapa ) desc';  
			} else {
				$order_by = 'DATEDIFF( NOW() , c.fecha_etapa ) asc';  					
			}
			
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'diasdiferencia='.$_REQUEST['diasdiferencia'];
		}


		if (isset($_REQUEST['procurador']) && $_REQUEST['procurador']!=''){
			if ($_REQUEST['procurador'] == 'desc'){
				$order_by ='nombres_adm desc';  
			} else {
				$order_by = 'nombres_adm asc';  					
			}
			
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'procurador='.$_REQUEST['procurador'];
		}
		
		if (isset($_REQUEST['tribunal']) && $_REQUEST['tribunal']!=''){
			if ($_REQUEST['tribunal'] == 'desc'){
				$order_by ='CAST(tri.tribunal AS UNSIGNED) desc';  
			} else {
				$order_by = 'CAST(tri.tribunal AS UNSIGNED) asc';  
			}
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'tribunal='.$_REQUEST['tribunal'];
		}
		
		//Fin ORDER BY
	
		if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
		//listar por vigente + exhorto + Rechazo Ingresa + Avenimiento + Dev Documentos
	 $this->db->where("(c.id_estado_cuenta ='1' OR c.id_estado_cuenta='6' OR c.id_estado_cuenta='2' OR c.id_estado_cuenta='8' OR c.id_estado_cuenta='7')");
		$this->db->where(array("c.activo"=>"S"));
		if (count($like)>0)
		{
			$this->db->like($like);
		}
		
		if (count($where)>0){
			$this->db->where($where);
			
		}
		
		
		if (count($where)==0 && count($like)==0){	
//echo "sssss"			;
			$this->db->where("(c.id_estado_cuenta ='1' OR c.id_estado_cuenta='6' OR c.id_estado_cuenta='2' OR c.id_estado_cuenta='8' OR c.id_estado_cuenta='7')");			
			
		}


		//print_r($like);



		if($order_by=="")
		{			
			//$order_by ="fecha_asignacion desc";exorto asc,
			$order_by ="dias_alerta_diferencia / dias_alerta*100/100*1 desc, dias_alerta_diferencia > dias_alerta*75/100*1 AND dias_alerta_diferencia <= dias_alerta*100/100*1 desc";
			
		}



	
					
						

		
		//$order_by =" DATEDIFF( NOW() , c.fecha_asignacion ) asc";
		$this->db->order_by($order_by);
		$this->db->group_by('c.id');
		$query = $this->db->get('0_cuentas c');	
				
		/**/
		$config['per_page'] = '100';
		$config['uri_segment'] = '4';
		$this->data['total'] = $config['total_rows'] = count($query->result());
	    $config['base_url'] = site_url().'/admin/alertas/etapas/';
	    $this->data['current_pag'] = $this->uri->segment(4);

		/**/
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante','left');
		//$this->db->join("2_cuentas_etapas ce", "ce.id_cuenta = c.id AND ce.activo='S' AND ce.id_etapa = c.id_etapa");	
		$this->db->join('s_etapas e', 'e.id = c.id_etapa','left');
		$this->db->join('pagare p', 'p.idcuenta = c.id','left');
		$this->db->join('0_administradores adm','adm.id = c.id_procurador','left');	
		$this->db->join("s_tribunales tri", "tri.id = c.id_tribunal","left");
		$this->db->join("s_tribunales trib", "trib.id = tri.padre","left");
		$this->db->join("s_tribunales trie", "trie.id = c.id_tribunal_ex","left");
		$this->db->join("s_tribunales disE", "disE.id = c.id_distrito_ex","left");

		
		if ($having!=''){
			$this->db->having($having);
		}		
		//######### WHERE ARRAY , DONDE EL KEY ES EL ACTIVO Y EL VALOR ES LA 'S' #############//
		$this->db->where(array("c.activo"=>"S"));

		//WHERE POR id_estado_cuenta, filtrado por estado: VIG(1) + EXH(6) + RECHAZO I(2) + ADVEN(8) + DEV DOC(7) //
	    $this->db->where("(c.id_estado_cuenta ='1' OR c.id_estado_cuenta='6' OR c.id_estado_cuenta='2' OR c.id_estado_cuenta='8' OR c.id_estado_cuenta='7')");

		if (count($where)>0){
			$this->db->where($where);
		}
		if (count($where)==0 && count($like)==0){	

			$this->db->where("(c.id_estado_cuenta ='1' OR c.id_estado_cuenta='6' OR c.id_estado_cuenta='2' OR c.id_estado_cuenta='8' OR c.id_estado_cuenta='7')");			
			
		}
		if (count($like)>0){
			$this->db->like($like);
		}
		$this->db->group_by('c.id');
		$this->db->order_by($order_by);
		
		$query = $this->db->get('0_cuentas c',$config['per_page'],$this->data['current_pag']);
		//echo $this->db->last_query();			
		/**/
		$lists = $query->result();
        $this->data['lists'] = $lists;
		
		//print_r($lists);
		
		$this->load->library('pagination');
	    $this->pagination->initialize($config);
	  
	    $this->data['sub_current'] = 'etapas';
		$this->data['plantilla'] = 'alertas/etapas/list'; 
		$this->load->view ( 'backend/index', $this->data );
	}
	
	public function etapas_cuentas(){
	  	//$this->output->enable_profiler(TRUE);
		//$this->output->enable_profiler(TRUE);
		//echo $_REQUEST['id_mandante'].'sdfsdf';
		//die();
		
		$like = array();
		$where = array();
		
		$config = array('suffix'=>'');
		//$config['base_url'] = site_url().'/admin/alertas/etapas/';
		
		
		$fecha_asignacion = '';
		
		$cols[] = 'c.id_etapa AS id_etapa';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'e.id AS etapa_id_etapa';
		$cols[] = 'm.codigo_mandante AS codigo_mandante';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'u.nombres AS usuarios_nombres';
		$cols[] = 'u.ap_pat AS usuarios_ap_pat';
		$cols[] = 'u.ap_mat AS usuarios_ap_mat';
		$cols[] = 'u.rut AS usuarios_rut';
		$cols[] = 'c.id AS cuentas_id';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'p.fecha_asignacion AS fecha_asignacion_pagare';
		//$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		//$cols[] = "DATEDIFF( NOW() , c.fecha_etapa ) AS dias_diferencia";
		$cols[] = "ce.fecha_etapa AS fecha_etapa";
		$cols[] = "DATEDIFF(c.fecha_asignacion,ce.fecha_etapa)  AS dias_diferencia";
		$cols[] = 'e.etapa AS etapa';
		$cols[] = 'e.texto_alerta AS texto_alerta';
		$cols[] = 'e.dias_alerta AS dias_alerta';
		$cols[] = "(DATEDIFF( NOW() , c.fecha_etapa ) - dias_alerta) AS dias_alerta_diferencia";
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos_adm';
		$cols[] = 'trib.tribunal AS tribunal';
		$cols[] = 'e.dias_alerta_proceso AS dias_alerta_proceso';
		
		$having = '';


		
	    if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']>0){ 
	    			$where["c.id_mandante"] = $_REQUEST['id_mandante'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
	    }}
		
		
		if (isset($_REQUEST['rol']) && $_REQUEST['rol']>0){ 
	    			$like["c.rol"] = $_REQUEST['rol'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'rol='.$_REQUEST['rol'];
	    }
	    
	 	if (isset($_REQUEST['id_estado_cuenta']) && $_REQUEST['id_estado_cuenta']!='' && $_REQUEST['id_estado_cuenta']>='0'){ 
	    			$where["c.id_estado_cuenta"] = $_REQUEST['id_estado_cuenta'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
	    }
	    
		if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){
	    			$where["c.id_procurador"] = $_REQUEST['id_procurador'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
	    }} 
	    
	    if (isset($_REQUEST['id_tribunal']) && $_REQUEST['id_tribunal']>0){
	    			$where["trib.id"] = $_REQUEST['id_tribunal'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
    				$config['suffix'].= 'id_tribunal='.$_REQUEST['id_tribunal'];
	    }	

		if (isset($_REQUEST['id_etapa']) && $_REQUEST['id_etapa']>0){ 
	    			$where["e.id"] = $_REQUEST['id_etapa'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_etapa='.$_REQUEST['id_etapa'];
	    }
	    
	    
	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
	    

		
		

	
		$this->data['current_pag'] = $this->uri->segment(4);
		//$config['per_page'] = '100';
	
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		//$this->db->join("2_cuentas_etapas ce", "ce.id_cuenta = c.id AND ce.activo='S' AND ce.id_etapa = c.id_etapa");	
		//$this->db->join('s_etapas e', 'e.id = c.id_etapa');
		$this->db->join('pagare p', 'p.idcuenta = c.id','left');
		$this->db->join('0_administradores adm','adm.id = c.id_procurador','left');	
		
		//$this->db->join("s_tribunales tri", "tri.id = c.id_tribunal","left");
		//$this->db->join("s_comunas com" ,"com.id_tribunal_padre=tri.id","left");
		
		$this->db->join("s_tribunales tri", "tri.id = c.id_tribunal","left");
		$this->db->join("s_tribunales trib", "trib.id = tri.padre","left");
		
		$this->db->join("2_cuentas_etapas ce","ce.id_cuenta = c.id","left");
		$this->db->join('s_etapas e', 'e.id = ce.id_etapa',"left");
		
		if ($having!=''){
			$this->db->having($having);
		}		
		
		
		//$this->db->where("(`c`.`id_estado_cuenta`='1' OR `c`.`id_estado_cuenta`='5')");
		//$this->db->where('DATEDIFF(c.fecha_asignacion,ce.fecha_etapa) > 0');
		//$this->db->where(array('c.fecha_asignacion !='=>'0000-00-00'));
		//$this->db->where(array('c.fecha_asignacion !='=>''));
		//$this->db->where(array('c.fecha_asignacion !='=>'NULL'));
		//$this->db->where(array('e.dias_alerta >'=>'dias_diferencia'));
		
		//$this->db->where(array('e.dias_alerta >'=>'dias_diferencia'));
		
		$this->db->where('(DATEDIFF(`ce`.`fecha_etapa`,`c`.`fecha_asignacion`) - `e`.`dias_alerta_proceso`)>`e`.`dias_alerta_proceso`');
		
		//$this->db->where(array('e.dias_alerta_proceso >'=>'0'));
		//$this->db->where(array("c.activo"=>"S"));
		//$this->db->where( "`e`.`activo` = 'S' AND CONVERT(DATEDIFF( NOW() , c.fecha_etapa),UNSIGNED INTEGER) >= CONVERT(e.dias_alerta,UNSIGNED INTEGER)" );
		if (count($where)>0){
			$this->db->where($where);
		}
		if (count($like)>0){
			$this->db->like($like);
		}
		
		$this->db->group_by('ce.id');
		
		/*dias_diferencia DESC*/
		$this->db->order_by('usuarios_ap_pat ASC,usuarios_ap_mat ASC');
		
		
		
		$query = $this->db->get('0_cuentas c',$config['per_page'],$this->data['current_pag']);
		/**/
		
		
		$lists = $query->result();
	
	/**/$this->data['total'] = $config['total_rows'] = count($query->result());
        
		$this->data['lists'] = $lists;
		
		$this->load->library('pagination');
	    $this->pagination->initialize($config);
	  
	    
	    $this->data['sub_current'] = 'etapas_cuentas';
		$this->data['plantilla'] = 'alertas/etapas_cuentas/list'; 
		$this->load->view ( 'backend/index', $this->data );






    }



    public function cuentas_alertas_proceso(){

        //echo 'dfgdgdgdf';
        //die();

        // $this->output->enable_profiler(TRUE);

        $where = array ();
        $like = array ();
        $having = array ();
        $config ['suffix'] = '';

        $this->load->library ( 'PHPExcel' );
        //$this->load->library('PHPExcel/IOFactory');
        $excel = new PHPExcel ();
        $excel->setActiveSheetIndex ( 0 );
        $sheet = $excel->getActiveSheet ();
        $sheet->SetCellValue ( 'A1', 'MANDANTE' );
        $sheet->SetCellValue ( 'B1', 'RUT DEUDOR' );
        $sheet->SetCellValue ( 'C1', 'NOMBRE DEDUDOR' );
        $sheet->SetCellValue ( 'D1', 'ROL' );
        $sheet->SetCellValue ( 'E1', 'FECHA ASIGNACIÓN' );
        $sheet->SetCellValue ( 'F1', 'FECHA PAGARE' );
        $sheet->SetCellValue ( 'G1', 'PROCURADOR' );
        $sheet->SetCellValue ( 'H1', 'TRIBUNAL' );
        $sheet->SetCellValue ( 'I1', 'DIAS ATRASOS' );
        $sheet->SetCellValue ( 'J1', 'ETAPA' );
        $sheet->SetCellValue ( 'K1', 'FECHA INGRESO ETAPA' );


        if ($having!=''){
            $this->db->having($having);
        }

        if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']>0){
            $where["c.id_mandante"] = $_REQUEST['id_mandante'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
        }}


        if (isset($_REQUEST['rol']) && $_REQUEST['rol']>0){
            $like["c.rol"] = $_REQUEST['rol'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'rol='.$_REQUEST['rol'];
        }

        if (isset($_REQUEST['id_estado_cuenta']) && $_REQUEST['id_estado_cuenta']!='' && $_REQUEST['id_estado_cuenta']>='0'){
            $where["c.id_estado_cuenta"] = $_REQUEST['id_estado_cuenta'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
        }

        if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){
            $where["c.id_procurador"] = $_REQUEST['id_procurador'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
        }}

        if (isset($_REQUEST['id_tribunal']) && $_REQUEST['id_tribunal']>0){
            $where["c.id_tribunal"] = $_REQUEST['id_tribunal'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_tribunal='.$_REQUEST['id_tribunal'];
        }

        if (isset($_REQUEST['id_etapa']) && $_REQUEST['id_etapa']>0){
            $where["c.id_etapa"] = $_REQUEST['id_etapa'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_etapa='.$_REQUEST['id_etapa'];
        }


        if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}

        $cuentas_alertas_proceso = $this->cuentas_m->get_cuentas_alertas_procesos($where,$like);

        // echo  count($cuentas_alertas_proceso);
        // die();



        $i = 2;
        foreach ( $cuentas_alertas_proceso as $key => $val ) {


         $c = abs($val->dias_diferencia);
         $f = $c - $val->dias_alerta_proceso;




            $fecha_asignacion = $val->fecha_asignacion;
            if ($fecha_asignacion!='' && $fecha_asignacion!='0000-00-00'){
                $fecha_asignacion = date('d-m-Y',strtotime($val->fecha_asignacion));
            }

            $fecha_asignacion_pagare = $val->fecha_asignacion_pagare;
            if ($fecha_asignacion_pagare!='' && $fecha_asignacion_pagare!='0000-00-00'){
                $fecha_asignacion_pagare = date('d-m-Y',strtotime($val->fecha_asignacion_pagare));
            }

            $fecha_etapa = $val->fecha_etapa;
            if ($fecha_etapa!='' && $fecha_etapa!='0000-00-00'){
                $fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
            }


            // echo $fecha_asignacion_pagare.'fdgdfgdfg';
           // die();


            $sheet->SetCellValue ( 'A' . $i, $val->codigo_mandante);
            $sheet->SetCellValue ( 'B' . $i, $val->usuarios_rut);
            $sheet->SetCellValue ( 'C' . $i, $val->usuarios_nombres.' '.$val->usuarios_ap_pat.' '.$val->usuarios_ap_mat );
            $sheet->SetCellValue ( 'D' . $i, $val->rol);
            $sheet->SetCellValue ( 'E' . $i, $fecha_asignacion );
            $sheet->SetCellValue ( 'F' . $i, $fecha_asignacion_pagare);
            $sheet->SetCellValue ( 'G' . $i, $val->nombres_adm.' '.$val->apellidos_adm );
            $sheet->SetCellValue ( 'H' . $i, $val->tribunal);
            $sheet->SetCellValue ( 'I' . $i, $f);
            $sheet->SetCellValue ( 'J' . $i, $val->etapa);
            $sheet->SetCellValue ( 'K' . $i, $fecha_etapa);

            $i++;
        }

        $writer = new PHPExcel_Writer_Excel5 ( $excel );
        header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
        header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
        header ( 'Content-Disposition: attachment; filename=alerta_procesos_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
        header ( 'Expires: 0' );
        header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header ( 'Cache-Control: private', false );
        $writer->save ( 'php://output' );



    }
	
	
	
	
	
	
	
	
	
	public function etapasfa(){
        //$this->output->enable_profiler(TRUE);
        //$this->output->enable_profiler(TRUE);

        $like = array();
        $where = array();

        $config = array('suffix'=>'');
        //$config['base_url'] = site_url().'/admin/alertas/etapas/';


        $fecha_asignacion = '';

        $cols[] = 'c.id_etapa AS id_etapa';
        $cols[] = 'm.codigo_mandante AS codigo_mandante';
        $cols[] = 'c.id_mandante AS id_mandante';
        $cols[] = 'u.nombres AS usuarios_nombres';
        $cols[] = 'u.ap_pat AS usuarios_ap_pat';
        $cols[] = 'u.ap_mat AS usuarios_ap_mat';
        $cols[] = 'u.rut AS usuarios_rut';
        $cols[] = 'c.id AS cuentas_id';
        $cols[] = 'c.rol AS rol';
        $cols[] = 'c.fecha_asignacion AS fecha_asignacion';
        $cols[] = 'p.fecha_asignacion AS fecha_asignacion_pagare';
        $cols[] = 'c.fecha_etapa AS fecha_etapa';
        $cols[] = "DATEDIFF( NOW() , c.fecha_etapa ) AS dias_diferencia";
        $cols[] = 'e.etapa AS etapa';
        $cols[] = 'e.texto_alerta AS texto_alerta';
        $cols[] = 'e.dias_alerta AS dias_alerta';
        $cols[] = "(DATEDIFF( NOW() , c.fecha_etapa ) - dias_alerta) AS dias_alerta_diferencia";
        $cols[] = 'adm.nombres AS nombres_adm';
        $cols[] = 'adm.apellidos AS apellidos_adm';
        //$cols[] = 'trib.tribunal AS tribunal_padre';
        $cols[] = 'tri.tribunal AS tribunal';

        $having = '';

        /**/

        $this->db->select($cols);
        $this->db->join('0_usuarios u', 'u.id = c.id_usuario');
        $this->db->join('0_mandantes m', 'm.id = c.id_mandante');
        //$this->db->join("2_cuentas_etapas ce", "ce.id_cuenta = c.id AND ce.activo='S' AND ce.id_etapa = c.id_etapa");
        $this->db->join('s_etapas e', 'e.id = c.id_etapa');
        $this->db->join('pagare p', 'p.idcuenta = c.id','left');
        $this->db->join('0_administradores adm','adm.id = c.id_procurador','left');
        $this->db->join("s_tribunales tri", "tri.id = c.id_tribunal","left");
        $this->db->join("s_tribunales trib", "trib.id = tri.padre","left");

        if ($having!=''){
            $this->db->having($having);
        }

        if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']>0){
            $where["c.id_mandante"] = $_REQUEST['id_mandante'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
        }}


        if (isset($_REQUEST['rol']) && $_REQUEST['rol']>0){
            $like["c.rol"] = $_REQUEST['rol'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'rol='.$_REQUEST['rol'];
        }

        if (isset($_REQUEST['id_estado_cuenta']) && $_REQUEST['id_estado_cuenta']!='' && $_REQUEST['id_estado_cuenta']>='0'){
            $where["c.id_estado_cuenta"] = $_REQUEST['id_estado_cuenta'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
        }

        if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){
            $where["c.id_procurador"] = $_REQUEST['id_procurador'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
        }}

        if (isset($_REQUEST['id_tribunal']) && $_REQUEST['id_tribunal']>0){
            $where["c.id_tribunal"] = $_REQUEST['id_tribunal'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_tribunal='.$_REQUEST['id_tribunal'];
        }

        if (isset($_REQUEST['id_etapa']) && $_REQUEST['id_etapa']>0){
            $where["c.id_etapa"] = $_REQUEST['id_etapa'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_etapa='.$_REQUEST['id_etapa'];
        }


        if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}

        $this->db->where("(c.id_estado_cuenta ='1' OR c.id_estado_cuenta='6' OR c.id_estado_cuenta='2' OR c.id_estado_cuenta='8' OR c.id_estado_cuenta='7')");
        $this->db->where(array("c.activo"=>"S"));
        $this->db->where(array('e.dias_alerta >'=>'0'));
        $this->db->where( "`e`.`activo` = 'S' AND CONVERT(DATEDIFF( NOW() , c.fecha_etapa ),UNSIGNED INTEGER) >= CONVERT(e.dias_alerta,UNSIGNED INTEGER)" );
        if (count($like)>0){
            $this->db->like($like);
        }
        if (count($where)>0){
            $this->db->where($where);
        }
        $this->db->group_by('c.id');
        $query = $this->db->get('0_cuentas c');



        /**/
        $config['per_page'] = '100';
        $config['uri_segment'] = '4';
        $this->data['total'] = $config['total_rows'] = count($query->result());
        $config['base_url'] = site_url().'/admin/alertas/etapasfa/';
        $this->data['current_pag'] = $this->uri->segment(4);

        /**/
        $this->db->select($cols);
        $this->db->join('0_usuarios u', 'u.id = c.id_usuario');
        $this->db->join('0_mandantes m', 'm.id = c.id_mandante');
        //$this->db->join("2_cuentas_etapas ce", "ce.id_cuenta = c.id AND ce.activo='S' AND ce.id_etapa = c.id_etapa");
        $this->db->join('s_etapas e', 'e.id = c.id_etapa');
        $this->db->join('pagare p', 'p.idcuenta = c.id','left');
        $this->db->join('0_administradores adm','adm.id = c.id_procurador','left');
        $this->db->join("s_tribunales tri", "tri.id = c.id_tribunal","left");
        $this->db->join("s_tribunales trib", "trib.id = tri.padre","left");

        if ($having!=''){
            $this->db->having($having);
        }
        $this->db->where("(c.id_estado_cuenta ='1' OR c.id_estado_cuenta='6' OR c.id_estado_cuenta='2' OR c.id_estado_cuenta='8' OR c.id_estado_cuenta='7')");
        $this->db->where(array('e.dias_alerta >'=>'0'));
        $this->db->where(array("c.activo"=>"S"));
        $this->db->where( "`e`.`activo` = 'S' AND CONVERT(DATEDIFF( NOW() , c.fecha_etapa),UNSIGNED INTEGER) >= CONVERT(e.dias_alerta,UNSIGNED INTEGER)" );
        if (count($where)>0){
            $this->db->where($where);
        }
        if (count($like)>0){
            $this->db->like($like);
        }
        $this->db->group_by('c.id');
        $this->db->order_by('dias_diferencia DESC,usuarios_ap_pat ASC,usuarios_ap_mat ASC');
        $query = $this->db->get('0_cuentas c',$config['per_page'],$this->data['current_pag']);
        /**/

        $lists = $query->result();
        $this->data['lists'] = $lists;

        $this->load->library('pagination');
        $this->pagination->initialize($config);


        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $this->data['sub_current'] = 'etapasfa';
        $this->data['plantilla'] = 'alertas/etapasfa/list';
        $this->load->view ( 'backend/index', $this->data );



	}
	
	public function exportar_excel (){
		
		//$like = array();
		$where = array();
		
		$config = array('suffix'=>'');
		
		$fecha_asignacion = '';
		
		$cols[] = 'c.id_etapa AS id_etapa';
		$cols[] = 'e.id AS etapa_id_etapa';
		$cols[] = 'm.codigo_mandante AS codigo_mandante';
		$cols[] = 'm.razon_social AS razon_social';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'u.nombres AS usuarios_nombres';
		$cols[] = 'u.ap_pat AS usuarios_ap_pat';
		$cols[] = 'u.ap_mat AS usuarios_ap_mat';
		$cols[] = 'u.rut AS rut';
		$cols[] = 'c.id AS cuentas_id';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'p.fecha_asignacion AS fecha_asignacion_pagare';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = "DATEDIFF( NOW() , c.fecha_etapa ) AS dias_diferencia";
		$cols[] = 'e.etapa AS etapa';
		$cols[] = 'e.texto_alerta AS texto_alerta';
		//$cols[] = 'e.dias_alerta AS dias_alerta';
		$cols[] = "(DATEDIFF( NOW() , c.fecha_etapa ) - dias_alerta) AS dias_alerta_diferencia";
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos_adm';
		$cols[] = 'tri.tribunal AS corte';
		$cols[] = 'trib.tribunal AS tribunal';
		$cols[] = 'est.estado AS estado';
		
		$having = '';
				
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		//$this->db->join("2_cuentas_etapas ce", "ce.id_cuenta = c.id");	
		$this->db->join('s_etapas e', 'e.id = c.id_etapa');	
		$this->db->join('pagare p', 'p.idcuenta = c.id','left');
		$this->db->join('0_administradores adm','adm.id = c.id_procurador','left');
		$this->db->join("s_tribunales tri", "tri.id = c.id_tribunal","left");	
		$this->db->join("s_tribunales trib", "trib.id = tri.padre","left");
		$this->db->join("s_estado_cuenta est", "est.id = c.id_estado_cuenta","left");
		
		
		
		if ($having!=''){
			$this->db->having($having);
		}
		
		//$this->db->where("(`c`.`id_estado_cuenta`='1' OR `c`.`id_estado_cuenta`='5')");
		$this->db->where(array('e.dias_alerta >'=>'0'));
		$this->db->where(array("c.activo"=>"S"));
		$this->db->where( "`e`.`activo` = 'S' AND CONVERT(DATEDIFF( NOW() , c.fecha_etapa ),UNSIGNED INTEGER) >= CONVERT(e.dias_alerta,UNSIGNED INTEGER)" );
		/*if (count($like)>0){
			$this->db->like($like);
		}*/
		if (count($where)>0){
			$this->db->where($where);
		}
		$this->db->group_by('c.id');
		$query = $this->db->get('0_cuentas c');
		$query_result = $query->result();
		
		//$this->output->enable_profiler ( TRUE );
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'MANDANTE' );
		$sheet->SetCellValue ( 'B1', 'RUT' );
		$sheet->SetCellValue ( 'C1', 'NOMBRE DEUDOR' );
		$sheet->SetCellValue ( 'D1', 'ROL' );
		$sheet->SetCellValue ( 'E1', 'FECHA ASIGNACIÓN' );
		$sheet->SetCellValue ( 'F1', 'FECHA PAGARE' );
		$sheet->SetCellValue ( 'G1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'H1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'I1', 'DÍAS ALERTA(DIFERENCIA)' );
		$sheet->SetCellValue ( 'J1', 'ETAPA' );
		$sheet->SetCellValue ( 'K1', 'FECHA INGRESO ETAPA' );
		$sheet->SetCellValue ( 'L1', 'ESTADO CUENTA' );
	
		
		//print_r($query_result);
		//die();
		
		$i = 2;
		foreach ( $query_result as $key => $val ) {
		
			
			
			$fecha_asignacion = $val->fecha_asignacion;
			if ($fecha_asignacion!='' ){
				$fecha_asignacion = date('d-m-Y',strtotime($val->fecha_asignacion));
			}
			
			$fecha_asignacion_pagare = $val->fecha_asignacion_pagare;
			if ($fecha_asignacion_pagare!=''){
				$fecha_asignacion_pagare = date('d-m-Y',strtotime($val->fecha_asignacion_pagare));
			}
			
				
			$sheet->SetCellValue ( 'A' . $i, $val->razon_social);
			$sheet->SetCellValue ( 'B' . $i, $val->rut );
			$sheet->SetCellValue ( 'C' . $i, $val->usuarios_nombres.' '.$val->usuarios_ap_pat.' '.$val->usuarios_ap_mat );
			$sheet->SetCellValue ( 'D' . $i, $val->rol );
			$sheet->SetCellValue ( 'E' . $i, $fecha_asignacion );
			$sheet->SetCellValue ( 'F' . $i, $fecha_asignacion_pagare  );
			$sheet->SetCellValue ( 'G' . $i, $val->nombres_adm.' '.$val->apellidos_adm );
			$sheet->SetCellValue ( 'H' . $i, $val->corte );
			$sheet->SetCellValue ( 'I' . $i, $val->dias_diferencia );
			$sheet->SetCellValue ( 'J' . $i, $val->etapa );
			$sheet->SetCellValue ( 'K' . $i, $val->fecha_etapa );
			$sheet->SetCellValue ( 'L' . $i, $val->estado );
			
			$i ++;
		}
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=alertas_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}

	////////////////////////////////////////////////////////////////////////////////
	//////////////////CAMPOS ALERTAS EXPORTAR EXCEL ////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////

    public function exportador_alertas(){

        // $this->output->enable_profiler(TRUE);

        $where = array ();
        $like = array ();
        $having = array ();
        $config ['suffix'] = '';

        $this->load->library ( 'PHPExcel' );
        //$this->load->library('PHPExcel/IOFactory');
        $excel = new PHPExcel ();
        $excel->setActiveSheetIndex ( 0 );
        $sheet = $excel->getActiveSheet ();
        $sheet->SetCellValue ( 'A1', 'MANDANTE' );
        $sheet->SetCellValue ( 'B1', 'RUT DEUDOR' );
        $sheet->SetCellValue ( 'C1', 'NOMBRE DEDUDOR' );
        $sheet->SetCellValue ( 'D1', 'ESTADO' );
        $sheet->SetCellValue ( 'E1', 'FECHA ASIGNACIÓN' );
        $sheet->SetCellValue ( 'F1', 'FECHA ETAPA' );
        $sheet->SetCellValue ( 'G1', 'DIAS ALERTA' );
        $sheet->SetCellValue ( 'H1', 'DIAS ETAPA' );
        $sheet->SetCellValue ( 'I1', 'ETAPA' );
        $sheet->SetCellValue ( 'J1', 'PROCURADOR' );
        $sheet->SetCellValue ( 'K1', 'ROL' );
        $sheet->SetCellValue ( 'L1', 'TRIBUNAL' );
       
    
    		

        if ($having!=''){
            $this->db->having($having);
        }

        if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']>0){
            $where["c.id_mandante"] = $_REQUEST['id_mandante'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
        }}


        if (isset($_REQUEST['rol']) && $_REQUEST['rol']>0){
            $like["c.rol"] = $_REQUEST['rol'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'rol='.$_REQUEST['rol'];
        }

        if (isset($_REQUEST['id_estado_cuenta']) && $_REQUEST['id_estado_cuenta']!='' && $_REQUEST['id_estado_cuenta']>='0'){
            $where["c.id_estado_cuenta"] = $_REQUEST['id_estado_cuenta'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
        }

        if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){
            $where["c.id_procurador"] = $_REQUEST['id_procurador'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
        }}

        if (isset($_REQUEST['id_tribunal']) && $_REQUEST['id_tribunal']>0){
            $where["c.id_tribunal"] = $_REQUEST['id_tribunal'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_tribunal='.$_REQUEST['id_tribunal'];
        }

        if (isset($_REQUEST['id_etapa']) && $_REQUEST['id_etapa']>0){
            $where["c.id_etapa"] = $_REQUEST['id_etapa'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'id_etapa='.$_REQUEST['id_etapa'];
        }


        if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}

        $cuentas_alertas_proceso = $this->cuentas_m->get_cuentas_alertas_proceso($where,$like);

         // echo  count($cuentas_alertas_proceso);
        // die();


        $i = 2;
        foreach ( $cuentas_alertas_proceso as $key => $val ) {


            $fecha_asignacion = $val->fecha_asignacion;
            if ($fecha_asignacion!='' && $fecha_asignacion!='0000-00-00'){
                $fecha_asignacion = date('d-m-Y',strtotime($val->fecha_asignacion));
            }

            $fecha_asignacion_pagare = $val->fecha_asignacion_pagare;
            if ($fecha_asignacion_pagare!='' && $fecha_asignacion_pagare!='0000-00-00'){
                $fecha_asignacion_pagare = date('d-m-Y',strtotime($val->fecha_asignacion_pagare));
            }

            $fecha_etapa = $val->fecha_etapa;
            if ($fecha_etapa!='' && $fecha_etapa!='0000-00-00'){
                $fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
            }

            $sheet->SetCellValue ( 'A' . $i, $val->codigo_mandante);
            $sheet->SetCellValue ( 'B' . $i, $val->usuario_rut);
            $sheet->SetCellValue ( 'C' . $i, $val->usuarios_nombres.' '.$val->usuarios_ap_pat.' '.$val->usuarios_ap_mat );
            $sheet->SetCellValue ( 'D' . $i, $val->estado );
			$sheet->SetCellValue ( 'E' . $i, $fecha_asignacion );
          
            $sheet->SetCellValue ( 'F' . $i, $fecha_etapa);
            $sheet->SetCellValue ( 'G' . $i, $val->dias_alerta_diferencia);
            $sheet->SetCellValue ( 'H' . $i, $val->dias_alerta);
            $sheet->SetCellValue ( 'I' . $i, $val->etapa);
            $sheet->SetCellValue ( 'J' . $i, $val->nombres_adm.' '.$val->apellidos_adm);
            $sheet->SetCellValue ( 'K' . $i, $val->rol);
            $sheet->SetCellValue ( 'L' . $i, $val->tribunal);

            $i++;
        }

       $writer = new PHPExcel_Writer_Excel5 ( $excel );
        header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
        header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
        header ( 'Content-Disposition: attachment; filename=alertas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
        header ( 'Expires: 0' );
        header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
        header ( 'Cache-Control: private', false );
        $writer->save ( 'php://output' );



    }

	
	
}

?>