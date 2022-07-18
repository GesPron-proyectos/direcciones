<?php
class Procurador extends CI_Controller {
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
		$this->load->model ( 'log_etapas_m' );
		$this->load->model ( 'receptor_m' );
		$this->load->model ( 'diligencia_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'bienes_m' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'pagare_m' );
		$this->load->model ( 'tipo_productos_m' );
		/*seters*/
		$this->data['current'] = 'procurador';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'procurador/';
		$this->data['lists'] = array();  
		
		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
		
		$this->data['forma_pagos'] = array(''=>'Forma Pago','TF'=>'Transferencia','DP'=>'Depósito','CH'=>'Cheque','EF'=>'Efectivo');
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );		
	
	   //########## SELECT ETAPAS, SE ORDENA POR POSICION #####################// 
	 	$b=$this->etapas_m->order_by('posicion','ASC')->get_many_by(array('activo'=>'S')); 
		$this->data['etapas'][0]='Seleccionar..';
	 	foreach ($b as $obj) {$this->data['etapas'][$obj->id] = $obj->codigo.' '.$obj->etapa;}
	 	
	 	
	    $c=$this->estados_cuenta_m->order_by('estado','ASC')->get_all(); 
		$this->data['estados'][-1]='Seleccionar..';
	 	foreach ($c as $obj) {$this->data['estados'][$obj->id] = $obj->estado;}
		
		$tabs = array();
			$tabs['datos_cuenta'] = 1;
			$tabs['historial'] = 2;
			$tabs['telefonos'] = 3;
			$tabs['direcciones'] = 4;
			$tabs['bienes'] = 5;
			$tabs['gastos'] = 6;
			$tabs['documentos'] = 7;
			$tabs['etapas_juicio'] = 8;
	 		$tabs['pagares'] = 9;
		$this->tabs = $tabs;
		
		//$this->output->enable_profiler(TRUE);		
		//$this->data['idprocurador'] = $this->session->userdata('usuario_id');		
	}
	function _unset_sessions_success(){
		$this->session->unset_userdata('success_gasto');
		$this->session->unset_userdata('success_cuenta');
		$this->session->unset_userdata('success_historial');
		$this->session->unset_userdata('success_telefono');
		$this->session->unset_userdata('success_direccion');
		$this->session->unset_userdata('success_bien');
		$this->session->unset_userdata('success_pagare');
		$this->session->unset_userdata('success_etapa');
	}
	

	function form($action='',$id='',$param=''){}

	function gen($action,$id){$this->index($action,$id);}

	function index($action='',$id='') {
		
		//$this->output->enable_profiler(TRUE);
		
		$view='list'; 
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
		if ($action=='up' or $action=='down'){
			$this->cuentas_m->move_up_down($_POST['posicion'],$id,$action,$_POST['field_categoria']);
			$this->show_tpl = FALSE; 
			$config['uri_segment'] = '6'; 
			$this->data['current_pag'] = $this->uri->segment(6);
		}
		if ($view=='list'){
			/*where*/
			//$this->output->enable_profiler(TRUE);
			$where=array();
			$like=array();
	    	$where["cta.activo"] = "S";
			//$where["cta.id_estado_cuenta"] = "1";
	    	//$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
	    	$config['suffix'] = '';
	    	$order_by='';
	    	$p_estado = '';
	      	
	 /// ***** 123 
	 
	   	/*$p_estado = $this->input->post('id_estado_cuenta');
 		 if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
   		$where_str .= "cta.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
   		$suffix ['estado[]'] = $p_estado; 
     		}*/
	    	//print_r($_GET);
			
			//print_r($_REQUEST);
			
			if (isset($_REQUEST['orden_id_tribunal']) && $_REQUEST['orden_id_tribunal']!=''){
				if ($_REQUEST['orden_id_tribunal'] == 'desc'){
					$order_by = 'CAST(tr.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(tr.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'orden_id_tribunal='.$_REQUEST['orden_id_tribunal'];
	    	}
			
			
	    	if (isset($_REQUEST['mandante']) && $_REQUEST['mandante']!=''){
				if ($_REQUEST['mandante'] == 'desc'){
					$order_by = 'mand.codigo_mandante desc';  
				} else {
					$order_by = 'mand.codigo_mandante asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'mandante='.$_REQUEST['mandante'];
	    	}
			
			if (isset($_REQUEST['Operacion'])){if ($_REQUEST['Operacion']>0){
				$where["cta.operacion"] = $_REQUEST['Operacion'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'operacion='.$_REQUEST['Operacion'];
	    	}}	    	
			
			if (isset($_REQUEST['fechaa']) && $_REQUEST['fechaa']!=''){
				if ($_REQUEST['fechaa'] == 'desc'){
					$order_by = 'cta.fecha_asignacion desc';  
				} else {
					$order_by = 'cta.fecha_asignacion asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'fechaa='.$_REQUEST['fechaa'];
	    	}
			
			if (isset($_REQUEST['diasatraso']) && $_REQUEST['diasatraso']!=''){
				if ($_REQUEST['diasatraso'] == 'desc'){
					$order_by = 'DATEDIFF( NOW(), cta.fecha_etapa ) desc';  
				} else {
					$order_by = 'DATEDIFF( NOW(), cta.fecha_etapa ) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'diasatraso='.$_REQUEST['diasatraso'];
	    	}
				    	
 		    if (isset($_REQUEST['orden_distrito']) && $_REQUEST['orden_distrito']!=''){
				if ($_REQUEST['orden_distrito'] == 'desc'){
					$order_by ='dis.tribunal desc';  
				} else {
					$order_by = 'dis.tribunal';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'orden_distrito='.$_REQUEST['orden_distrito'];
	    	}
	    	
	    		// ORDER BY `id_mandante` desc, `dis`.`tribunal`
	    		
	    	//**********
	    	/*if ($order_by==''){
	    		$order_by = "tr.posicion ASC,dist.posicion ASC,mand.razon_social,cta.fecha_asignacion ASC,cta.rol1_y ASC,cta.rol1 ASC,usr.ap_mat,usr.ap_pat ASC";
	    	}*/
	    	///***** 333
	    	if (isset($_REQUEST['rol']) && $_REQUEST['rol']!=''){
				if ($_REQUEST['rol'] == 'desc'){
					$order_by ='cta.rol desc';  
				} else {
					$order_by = 'cta.rol asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rol='.$_REQUEST['rol'];
	    	}
	    	///******
		    /*if ($order_by==''){
	    		$order_by = "tr.posicion ASC,dist.posicion ASC,mand.razon_social,cta.fecha_asignacion ASC,cta.rol1_y ASC,cta.rol1 ASC,usr.ap_mat,usr.ap_pat ASC";
				
	    	}*/
	    
	    	//// **** 4444
			if (isset($_REQUEST['orden_ap_pat']) && $_REQUEST['orden_ap_pat']!=''){
				if ($_REQUEST['orden_ap_pat'] == 'desc'){
					$order_by ='usr.ap_pat desc';  
				} else {
					$order_by = 'usr.ap_pat asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'orden_ap_pat='.$_REQUEST['orden_ap_pat'];
    		}
	    	///******
		    //if ($order_by==''){
	    	//	$order_by = "tr.posicion ASC,dist.posicion ASC,mand.razon_social,cta.fecha_asignacion ASC,cta.rol1_y ASC,cta.rol1 ASC,usr.ap_mat,usr.ap_pat ASC";
	    	//}
	    	
	        /*
			if ($order_by==''){
	    		$order_by = "tr.posicion ASC,dist.posicion ASC,mand.razon_social,cta.fecha_asignacion ASC,cta.rol1_y ASC,cta.rol1 ASC,usr.ap_mat,usr.ap_pat ASC";
	    	}	
	    	*/

			if (isset($_REQUEST['orden_id_etapa']) && $_REQUEST['orden_id_etapa']!=''){
				if ($_REQUEST['orden_id_etapa'] == 'desc'){
					$order_by ='etp.etapa desc';  
				} else {
					$order_by = 'etp.etapa asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'orden_id_etapa='.$_REQUEST['orden_id_etapa'];
	    	}
	    	///******
			//if ($order_by==''){
	    	//	$order_by = "tr.posicion ASC,dist.posicion ASC,mand.razon_social,cta.fecha_asignacion ASC,cta.rol1_y ASC,cta.rol1 ASC,usr.ap_mat,usr.ap_pat ASC";
	    	//}			    	
	    	
			if (isset($_REQUEST['etapa_actual']) && $_REQUEST['etapa_actual']!=''){ 
				$where["etp.etapa"] = $_REQUEST['etapa_actual'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'etapa_actual='.$_REQUEST['etapa_actual'];
    		}
	    	
	    	
			if (isset($_REQUEST['fecha_asignacion']) && $_REQUEST['fecha_asignacion']!=''){ 
			    $fecha_asignacion = date('Y-m-d',strtotime($_REQUEST['fecha_asignacion']));	
				$like["cta.fecha_asignacion"] = $fecha_asignacion;
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'fecha_asignacion='.$_REQUEST['fecha_asignacion'];
			}
	    		
	    		
	    		
	    	//if ($this->form_validation->run() == TRUE){
			if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){ 
				$where["usr.rut"] = $_REQUEST['rut'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rut='.$_REQUEST['rut'];
			}
			
			if (isset($_REQUEST['id_etapa']) && $_REQUEST['id_etapa']>0){ 
				$where["etap.id"] = $_REQUEST['id_etapa'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_etapa='.$_REQUEST['id_etapa'];
			}
			
			if (isset($_REQUEST['rol']) && $_REQUEST['rol']>0){ 
				$like["cta.rol"] = $_REQUEST['rol'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rol='.$_REQUEST['rol'];
			}	    		     		 
	    	
			if (isset($_REQUEST['id_distritoE'])){if ($_REQUEST['id_distritoE']>0){
				$where["cta.id_distrito_ex"] = $_REQUEST['id_distritoE'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_distritoE='.$_REQUEST['id_distritoE'];
	    	}}	    	
			
			if (isset($_REQUEST['id_tribunalE'])){if ($_REQUEST['id_tribunalE']>0){
				$where["cta.id_tribunal_ex"] = $_REQUEST['id_tribunalE'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_tribunalE='.$_REQUEST['id_tribunalE'];
	    	}}
			
			if (isset($_REQUEST['rolE']) && $_REQUEST['rolE']!=''){ 
				$where["cta.rolE"] = $_REQUEST['rolE'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rolE='.$_REQUEST['rolE'];
			}	    		 
//print_r($where);			
	//    	print_r($_REQUEST);			
			
			if (isset($_REQUEST['nombres'])){
				if ($_REQUEST['nombres']!=''){ 
					$like["usr.nombres"] = $_REQUEST['nombres'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'nombres='.$_REQUEST['nombres'];
				}
			}
			
			
		    if (isset($_REQUEST['rut_mandante'])){
				if ($_REQUEST['rut_mandante']!=''){ 
					$like["usr.rut"] = $_REQUEST['rut_mandante'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'rut_mandante='.$_REQUEST['rut_mandante'];
				}
			}		
			
			if (isset($_REQUEST['ap_pat'])){
				if ($_REQUEST['ap_pat']!=''){ 
					$like["usr.ap_pat"] = $_REQUEST['ap_pat'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'ap_pat='.$_REQUEST['ap_pat'];
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
			
			
			if (isset($_REQUEST['id_tribunal']) && $_REQUEST['id_tribunal']>0){
				$where["cta.id_tribunal"] = $_REQUEST['id_tribunal'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_tribunal='.$_REQUEST['id_tribunal'];
			} elseif (isset($_REQUEST['id_distrito']) && $_REQUEST['id_distrito']>0){
				$where["cta.id_distrito"] = $_REQUEST['id_distrito'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_distrito='.$_REQUEST['id_distrito'];
			} else {
				/*$where["cta.id_distrito"] = 1;
				$_REQUEST['id_distrito'] = 1;
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_distrito=1';*/
			}

			if (isset($_REQUEST['ap_mat'])){
	    		if ($_REQUEST['ap_mat']!=''){
	    				$where["usr.ap_mat"] = $_REQUEST['ap_mat'];
	    		}
	    	}	    	
			
    		if (isset($_REQUEST['receptor'])){
	    		if ($_REQUEST['receptor']!=''){
	    				$where["cta.receptor"] = $_REQUEST['receptor'];
	    		}
	    	}
			
			if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] != '' && $_REQUEST ['id_estado_cuenta'] >0 ) {			
				$where ["cta.id_estado_cuenta"] = $_REQUEST ['id_estado_cuenta'];
				if ($config ['suffix'] != '') {
					$config ['suffix'] .= '&';
				}
				$config ['suffix'] .= 'id_estado_cuenta=' . $_REQUEST ['id_estado_cuenta'];
			}
	    		
			if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/procurador/index/';
			
			if( $this->session->userdata("usuario_perfil") == 3 ){
	    		$where["cta.id_procurador"] = $this->session->userdata("usuario_id");
	    	}
	    	$nodo = $this->nodo_m->get_by( array('activo'=>'S') );
	    	/*if($nodo->nombre=='fullpay'){
	    		$where["cta.id_estado_cuenta"]=1;
	    	} */
	    	
	    	if($nodo->nombre=='swcobranza'){
	    		
				$where["cta.id_estado_cuenta >"]=1;	
				
	    	}
			
			if($order_by=="")
			{			
				//$order_by ="fecha_asignacion desc";
				//$order_by ="DATEDIFF( NOW() , cta.fecha_asignacion ) asc";
				$order_by ="dias_alerta_diferencia / dias_alerta*100/100*1 desc, dias_alerta_diferencia > dias_alerta*75/100*1 AND dias_alerta_diferencia <= dias_alerta*100/100*1 desc";
			}
			
			
	    	//$query_total = 
					$this->db->where("cta.activo","S");
	    			$this->db->where($where)->like($like);
                    $this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
					$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
					$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left");
					$this->db->join("s_etapas etap","etap.id=cta.id_etapa","left");
      			  	$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=cta.id_estado_cuenta","left");
					$this->db->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S'","left");
						
				
					//####### COUNT TOTAL DE CAUSAS A LISTAR ###########//
					if (count($_REQUEST)==0) {
						$this->db->where("(id_estado_cuenta='1' OR id_estado_cuenta='6' AND cta.activo = 'S')");
					}
					//############### FIN COUNT ####################### //
					
		
					$this->db->group_by("cta.id");
					$query_total = $this->db->get("0_cuentas cta");

					//print_r( $query_total);
					$total_rows = $query_total->result();
					$config['total_rows'] = count($total_rows);
					$config['per_page'] = '40'; 
					
					if( $this->session->userdata("usuario_perfil") == 3 ){
						$where["cta.id_procurador"] = $this->session->userdata("usuario_id");
					}
	    	
	    	
					$this->pagination->initialize($config);
						/*listado SUM(pag.monto_remitido) AS total*/

###############################################################################################						
############################## CONSULTA DATA COLUMNAS PROCURADOR ##############################	
###############################################################################################					
						$this->db->select('
						
							cta.id AS id,
							cta.activo AS activo,
							cta.publico AS publico,
							cta.posicion AS posicion,
							cta.id_procurador,
							cta.id_estado_cuenta AS id_estado_cuenta,,
							usr.nombres AS nombres,
							usr.ap_pat AS ap_pat,
							usr.ap_mat AS ap_mat,
							usr.rut AS rut,
							mand.codigo_mandante,
							mand.razon_social,
							tip.tipo AS tipo_producto,
							cta.fecha_asignacion AS fecha_asignacion,
							cta.monto_demandado AS monto_demandado,
							cta.monto_deuda AS monto_deuda,
							cta.id_etapa,
							cta.fecha_etapa,
							cta.receptor,
							cta.id_mandante AS field_categoria,
							cta.id_procurador,
							tr.tribunal,
							tr.id AS id_tribunal,
							dist.tribunal as distrito,
							dist.id as id_distrito,
							cta.rol as rol,
							cta.exorto as exorto,
							cta.id_etapa AS id_etapa,
							etap.etapa AS etapa_actual,
							etap.seleccionar_fecha_alarma,			
							SUM(pag.monto_pagado) AS total_pagado,
							tre.tribunal as tribunalE, 
							diste.tribunal as DistritoE, 
							cta.id_distrito_ex, 
							cta.rolE,
							0_admin.username AS username,
							(SELECT observaciones FROM 2_cuentas_etapas WHERE 2_cuentas_etapas.id_cuenta = cta.id ORDER BY id DESC LIMIT 1 ) as observaciones,
							DATEDIFF( NOW() , cta.fecha_etapa ) AS dias_alerta_diferencia,
							etap.dias_alerta AS dias_alerta,
							DATEDIFF( NOW() , cta.fecha_asignacion ) AS dias_,
							cta.acuse AS acuse,
							(SELECT id_etapa FROM 2_cuentas_etapas	WHERE id_cuenta=cta.id AND activo=\'S\' ORDER BY id  DESC LIMIT 1 ) eta

						');
						
						//(SELECT fecha_etapa FROM 2_cuentas_etapas WHERE cta.id=id_cuenta ORDER BY fecha_etapa DESC LIMIT 1) AS fecha_etapa,
						$this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
						$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
						$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")	;
						$this->db->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S'","left");
						$this->db->join("s_tribunales tr", "tr.id = cta.id_tribunal","left");
						$this->db->join("s_tribunales dist", "dist.id = tr.padre","left");
						$this->db->join("s_tribunales tre", "tre.padre = cta.id_tribunal_ex","left");
						$this->db->join("s_tribunales diste", "diste.id = cta.id_distrito_ex","left");
						$this->db->join("s_etapas etap","etap.id=cta.id_etapa","left");
						$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=cta.id_estado_cuenta","left");
						$this->db->join("0_administradores 0_admin","0_admin.id=cta.id_procurador","left");
						$this->db->where($where)->like($like);
							
						if (count($_REQUEST)==0) {
							$this->db->where("(id_estado_cuenta='1' OR id_estado_cuenta='6')");
						}
						$this->db->order_by($order_by);
						$this->db->group_by("cta.id");
						$query = $this->db->get("0_cuentas cta",$config['per_page'],$this->data['current_pag']);
						$this->data['lists']= $lists = $query->result();
						$this->data['total']=$config['total_rows'];
						//echo $this->db->last_query();
						//print_r($where);
			
			
			$this->db->where('padre', 0);
			$this->db->where('activo', 'S');
			//$this->db->where('cta.id_estado_cuenta', '1');
			$this->db->order_by('tribunal', 'ASC');
			$arr = $this->tribunales_m->get_all();
			
			$distrito[''] = 'Seleccionar';
			foreach($arr as $key=>$val){
				$distrito[$val->id]= $val->tribunal;
			}
			$this->data['distrito']= $distrito;
			
			if( isset($_REQUEST['id_distrito']) ){
				$this->db->where('padre', $_REQUEST['id_distrito']);
				$this->db->where('activo', 'S');
				$arr = $this->tribunales_m->get_all();

				$tribunales[''] = 'Seleccionar';
				foreach($arr as $key=>$val){
					$tribunales[$val->id]= $val->tribunal;
				}
			}else{
		
				$tribunales[''] = 'Seleccionar';
			}
			$this->data['tribunales']= $tribunales;
			
			////////////////############SELECT ETAPAS##########################################
			//$this->db->select('id,etapa');
			$etapas_juicio = $this->etapas_m->get_many_by(array('activo'=>'S'));
			$et=array();
			$et['']= '--------';
			foreach($etapas_juicio as $key=>$val){
				$et[$val->id]= $val->etapa;
				//$et[$val->codigo]= $val->codigo;
			}
			
			$this->data['etapas_juicio'] = $et;
			/*posiciones*/
			$query = $this->db->select('id_mandante AS field_categoria, MAX(posicion) AS max_posicion, MIN(posicion) AS min_posicion')->group_by("id_mandante")->get("0_cuentas");
			foreach ($query->result() as $key=>$val){
				$this->data['posiciones'][$val->field_categoria]['max_posicion']=$val->max_posicion;
				$this->data['posiciones'][$val->field_categoria]['min_posicion']=$val->min_posicion;
				$this->data['posiciones'][$val->field_categoria]['field_categoria']=$val->field_categoria;
			}

			//SELECT PROCURADOR, LISTADO COMPLETO ADMINISTRADORES
			$procuradores [''] = 'Seleccionar';
			$a=$this->administradores_m->get_many_by(array('activo'=>'S','nombres !='=>'','apellidos !='=>''));
			foreach ($a as $obj) {$procuradores[$obj->id] = $obj->nombres.' '.$obj->apellidos;}
			$this->data ['procuradores'] = $procuradores;

			/*CODE ANTERIOR SELECT PROCURADOR - CON PERFIL 
			$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'perfil' => '3'));
			$a=$this->administradores_m->get_all();
			$this->data['procuradores'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['procuradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}

			*/
			
			$this->data['mandantes'][0]='Seleccionar';
			$a=$this->mandantes_m->get_many_by(array('activo' => 'S'));
			foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}
			
			if (!$this->show_tpl){ 
				$this->data['plantilla'] = 'procurador/reportes'; 
				$this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );
			}

			
		}
			
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}

	}
	
	function reporte($tipo = 'pagos',$param = ''){
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
	    								  ->join("2_cuentas_pagos pagos", "pagos.id_cuenta = cta.id")
	    								  ->count_all_results("0_cuentas cta");
		    
		    
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
								 ->join("2_cuentas_pagos pagos", "pagos.id_cuenta = cta.id")			 
								 ->where($where)->where(array("pagos.activo"=>"S"))
								 ->like($like)
								 ->order_by("id_mandante", "desc")
								 ->order_by("cta.posicion", "desc")
				 				 ->get("0_cuentas cta");
			$this->data['lists']=$query_master->result();
			
	    	$array_csv[]=array(utf8_decode('Código Cuenta'),'Nombre Procurador','Apellido Procurador','Mandante','Fecha de Pago','Monto Pagado','Monto Deuda','Nombre Deudor','Apellido Deudor','Apellido Materno Deudor','Rut','Comprobante');
			foreach ($this->data['lists'] as $obj) {
				$array_csv[] = array($obj->id,utf8_decode($obj->nombres),utf8_decode($obj->apellidos),utf8_decode($obj->razon_social),$obj->fecha_pago,$obj->monto_pagado,$obj->monto_deuda,utf8_decode($obj->usr_nombres),utf8_decode($obj->usr_ap_pat),utf8_decode($obj->usr_ap_mat),$obj->rut,$obj->n_comprobante_interno);
			}
			
			$query = $this->db->select("id_cuenta")->select_sum("monto_pagado")->select_sum("honorarios")->group_by("id_cuenta")->get("2_cuentas_pagos");
			$a = $query->result();
	    	foreach ($a as $obj) {$this->data['saldo'][$obj->id_cuenta] = $obj->monto_pagado-$obj->honorarios;}
			
			/*paginacion*/
	    	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='cuentas/reportes/reporte_pagos';
	    } //pagos

	     /************************* ETAPAS *************************/
	    if ($tipo == 'etapas'){
	    	//$this->output->enable_profiler(TRUE);
	    	
	    	$config['suffix']=''; $group_by = '';
	    	
	    	$a=$this->etapas_m->order_by('codigo','ASC')->get_all(); 
			$this->data['etapas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['etapas'][$obj->id] = $obj->codigo.' '.$obj->etapa;}
	    	
	    	// $this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
		    // if ($this->form_validation->run() == TRUE){
		    // 	if ($_REQUEST['rut']!=''){ 
		    // 		$where["usr.rut"] = $_REQUEST['rut'];
		    // 		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    // 		$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    // 	}
		    // }
		    // 	if ($_REQUEST['agrupar']=='S'){
		    // 		$group_by = "cta.id_usuario";
		    // 		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    // 		$config['suffix'].= 'agrupar='.$_REQUEST['agrupar'];
		    // 	}
		    // 	if ($_REQUEST['id_procurador']>0){ 
		    // 		$where["cta.id_procurador"] = $_REQUEST['id_procurador'];
		    // 		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    // 		$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
		    // 	}
		    // 	if ($_REQUEST['id_mandante']>0){ 
		    // 		$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
		    // 		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    // 		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    // 	}
	    	// 	if ($_REQUEST['etapa']>0){ 
		    // 		$where["etapas.id"] = $_REQUEST['etapa'];
		    // 		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    // 		$config['suffix'].= 'etapa='.$_REQUEST['etapa'];	
		    // 	}
		    // 	$mes_i = '';
		    // 	if (isset($_REQUEST['fecha_etapa_month'])){ 
		    // 		if ($_REQUEST['fecha_etapa_month']>0){
		    // 			$mes_i = '-'.$_REQUEST['fecha_etapa_month'].'-';
		    // 			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    // 			$config['suffix'].= 'fecha_etapa_month='.$_REQUEST['fecha_etapa_month'];
		    // 		}	
		    // 	}
		    // 	$year_i = '';
		    // 	if (isset($_REQUEST['fecha_etapa_year'])){ 
		    // 		if ($_REQUEST['fecha_etapa_year']>0){ 
		    // 			$year_i = $_REQUEST['fecha_etapa_year'];
		    // 			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    // 			$config['suffix'].= 'fecha_etapa_year='.$_REQUEST['fecha_etapa_year'];	
		    // 		}
		    // 	}
		    // 	$like["cetapa.fecha_etapa"] = $year_i.$mes_i;
		    // 	/*$mes_f = '';
		    // 	if (isset($_REQUEST['fecha_etapa_f_month'])){ 
		    // 		if ($_REQUEST['fecha_etapa_f_month']>0){$mes_f = '-'.$_REQUEST['fecha_etapa_f_month'].'-';}
		    // 	}
		    // 	$year_f = '';
		    // 	if (isset($_REQUEST['fecha_etapa_year'])){ 
		    // 		if ($_REQUEST['fecha_etapa_f_year']>0){$year_f = $_REQUEST['fecha_etapa_f_year'];}
		    // 	}
		    // 	$like["cetapa.fecha_etapa"]=$year_f.$mes_f;*/
		    // $filtro_estado = array();
		    // if ( isset($_POST['estado']) && !empty($_POST['estado']) ) {
	    	// 	foreach ($_POST['estado'] as $key => $value) {
	    	// 		$filtro_estado[] = $value;
	    	// 	}
	    	// }
		    
	    	// if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
	    	

	    	// ---------------------------------------------------------------
	    	// Nuevas consultas para generar resporte
	    	// ---------------------------------------------------------------
			$idprocurador = '';
			$idprocurador = $this->session->userdata('usuario_id');
    		$sql_select =
    			" SELECT ". 
    			"    ce.*, mand.razon_social, usr.rut,  adm.apellidos AS amd_apellido, adm.nombres AS adm_nombres, ".
				"    com.nombre AS comuna, cta.activo AS activo, cta.id_mandante, cta.posicion AS posicion, ".
				"    cta.publico AS publico, cta.rol AS rol, estado.estado AS estado, etapas.etapa AS etapa, ".
				"    mand.razon_social, trib.tribunal AS tribunal, dist.tribunal AS distrito, usr.ap_mat AS usr_ap_mat, ".
				"    usr.ap_pat AS usr_ap_pat, usr.ciudad AS ciudad, usr.direccion AS direccion, ".
				"    usr.direccion_dpto AS direccion_dpto, adm.nombres AS nombres, adm.apellidos AS apellidos, ".
				"    usr.direccion_numero AS direccion_numero, usr.nombres AS usr_nombres, usr.rut AS rut ";

			$sql_body = "";			
			$group = "";

			// segun el modo de busqueda se cambia la consulta
			if ($this->input->get_post('modo') == "ultima") 
			{
				$sql_body = 
					" FROM ( ".
					"    SELECT id_cuenta, MAX(fecha_etapa) AS last_fecha ".
					"    FROM `2_cuentas_etapas` ".
					"    GROUP BY id_cuenta ".
					" ) ce2 ".
					" JOIN `2_cuentas_etapas` ce ON ce2.id_cuenta = ce.id_cuenta AND ce2.last_fecha = ce.fecha_etapa ".
					" LEFT JOIN `0_cuentas` cta ON cta.id = ce.id_cuenta ".
					" LEFT JOIN `0_usuarios` usr ON usr.id = cta.id_usuario ".
					" LEFT JOIN `0_mandantes` mand ON mand.id = cta.id_mandante ".
					" LEFT JOIN `0_administradores` adm ON adm.id = cta.id_procurador ".
					" LEFT JOIN `s_tribunales` trib ON trib.id = cta.id_tribunal ".
					" LEFT JOIN `s_tribunales` dist ON dist.id = cta.id_distrito ".
					" LEFT JOIN `s_etapas` etapas ON etapas.id = ce.id_etapa ".
					" LEFT JOIN `s_estado_cuenta` estado ON estado.id = cta.id_estado_cuenta ".
					" LEFT JOIN `s_comunas` com ON com.id = usr.id_comuna ".
					" WHERE cta.activo = 'S' AND cta.id_procurador = '$idprocurador'";

					// Si se busca el ultimo se debe agrupar
					$group = ' GROUP BY ce.id_cuenta ';
			} 
			else 
			{
				$sql_body = 
					" FROM `2_cuentas_etapas` ce ".
					" JOIN `0_cuentas` cta ON cta.id = ce.id_cuenta ".
					" JOIN `0_usuarios` usr ON usr.id = cta.id_usuario ".
					" JOIN `0_mandantes` mand ON mand.id = cta.id_mandante ".
					" JOIN `0_administradores` adm ON adm.id = cta.id_procurador ".
					" LEFT JOIN `s_tribunales` trib ON trib.id = cta.id_tribunal ".
					" LEFT JOIN `s_tribunales` dist ON dist.id = cta.id_distrito ".
					" LEFT JOIN `s_etapas` etapas ON etapas.id = ce.id_etapa ".
					" LEFT JOIN `s_estado_cuenta` estado ON estado.id = cta.id_estado_cuenta ".
					" LEFT JOIN `s_comunas` com ON com.id = usr.id_comuna ".
					" WHERE cta.activo = 'S' AND cta.id_procurador = '$idprocurador'";
					
			}

			// preparo los parametros de la busqueda
			$sql_where = "";
			$limit = "";
    		$bind_values = array();
    		$suffix = array();

    		$suffix['modo'] =  $this->input->get_post('modo');

    		if( $p_rut = $this->input->get_post('rut') ){
    			$sql_where .= " AND usr.rut = ? ";
    			$bind_values[] = $p_rut;
    			$suffix['rut'] =  $p_rut;
    		}
    		if( $p_procurador = $this->input->get_post('id_procurador') ){
    			$sql_where .= " AND cta.id_procurador = ? ";
    			$bind_values[] = $p_procurador;
    			$suffix['id_procurador'] =  $p_procurador;
    		}
    		if( $p_mandante = $this->input->get_post('id_mandante') ){
    			$sql_where .= " AND cta.id_mandante = ? ";
    			$bind_values[] = $p_mandante;
    			$suffix['id_mandante'] =  $p_mandante;
    		}
    		if( $p_etapa = $this->input->get_post('etapa') ){
    			$sql_where .= " AND ce.id_etapa = ? ";
    			$bind_values[] = $p_etapa;
    			$suffix['etapa'] =  $p_etapa;
    		}
    		if( $p_mes = $this->input->get_post('fecha_etapa_month') ){
    			$sql_where .= " AND MONTH(ce.fecha_etapa) = ? ";
    			$bind_values[] = $p_mes;
    			$suffix['fecha_etapa_month'] =  $p_mes;	
    		}
    		if( $p_ano = $this->input->get_post('fecha_etapa_year') ){
    			$sql_where .= " AND YEAR(ce.fecha_etapa) = ? ";
    			$bind_values[] = $p_ano;
    			$suffix['fecha_etapa_year'] =  $p_ano;	
    		}
    		$p_estado = $this->input->get_post('estado');
		    if ( is_array($p_estado) && count($p_estado) > 0 ){
	    		$sql_where .= " AND cta.id_estado_cuenta in (".implode(', ', $p_estado).") ";
	    		$suffix['estado[]'] =  $p_estado;	
	    	}

	    	$id_procurador = $this->session->userdata('usuario_id');
	    	$sql_where .= " AND cta.id_procurador = ? ";
    		$bind_values[] = $id_procurador;
    		$suffix['id_procurador'] =  $id_procurador;
    		
	    	
	    	
	    	
	    	if($param == 'exportar_etapas_juicio'){
				
	    		$this->output->enable_profiler(TRUE);

	    		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'MANDANTE' );
		$sheet->SetCellValue ( 'B1', 'ESTADO' );
		$sheet->SetCellValue ( 'C1', 'RUT' );
		$sheet->SetCellValue ( 'D1', 'DEUDOR' );
		$sheet->SetCellValue ( 'E1', 'DIRECCIÓN' );
		$sheet->SetCellValue ( 'F1', 'COMUNA');
		$sheet->SetCellValue ( 'G1', 'CIUDAD' );
		$sheet->SetCellValue ( 'H1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'I1', 'ETAPAS JUICIO' );
		$sheet->SetCellValue ( 'J1', 'FECHA ETAPA' );
		$sheet->SetCellValue ( 'K1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'L1', 'DISTRITO' );
		$sheet->SetCellValue ( 'M1', 'ROL' ); 
		
		
		$where = array ();
		$where_str = '';
		$like = array ();
		$config ['suffix'] = '';
		
	    	if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['c.id_mandante'] = $_REQUEST ['id_mandante'];
			}
			
	    	if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['c.id_procurador'] = $_REQUEST ['id_procurador'];
			}
			
	    	if (isset ( $_REQUEST ['rut'] ) && $_REQUEST ['rut'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut'];
			}
			
			
	    	if (isset ( $_REQUEST ['etapa'] ) && $_REQUEST ['etapa'] != '') {
			$where ['etap.id'] = $_REQUEST ['etapa'];
			}
			
	    	$p_estado = $this->input->get_post('estado');
 		 if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
   		$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
   		$suffix ['estado[]'] = $p_estado; 
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
		    $like["ce.fecha_etapa"]=$year_i.$mes_i;
     		
     		
     		
	    	/*if (isset ( $_REQUEST ['estado'] ) && $_REQUEST ['estado'] > 0) {
			$where ['c.id_estado_cuenta'] = $_REQUEST ['estado'];
			} */
			
		/*
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['c.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		
		
		
		
		if (isset ( $_REQUEST ['estado'] ) && $_REQUEST ['estado'] > 0) {
			$where ['c.id_estado_cuenta'] = $_REQUEST ['estado'];
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
		    $like["ce.fecha_etapa"]=$year_i.$mes_i;*/
		
	
		/*$p_estado = $this->input->get_post('estado');
		if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
			$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
			$suffix ['estado[]'] = $p_estado;	
	    }*/
	    
	
		//$this->db->where ( array ('c.activo' => 'S' ) );
		$cuentas = $this->cuentas_m->get_procurador_cuenta_etapa_juicio_exportar_fullpay ( $where, $like, $where_str );
		
		//die();
		
		
		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
		if($val->fecha_etapa == '30-11--0001' || $val->fecha_etapa == '31-12-1969' || $val->fecha_etapa == null || $val->fecha_etapa == '' ){
		     $fecha_etapa = '-'; 	
		    }else{
		  	 $fecha_etapa = date('d-m-Y H:i:s',strtotime($val->fecha_etapa));
		    }
			
			
			$sheet->SetCellValue ( 'A' . $i, $val->codigo_mandante );
			$sheet->SetCellValue ( 'B' . $i, $val->estado );
			$sheet->SetCellValue ( 'C' . $i, $val->rut );
			$sheet->SetCellValue ( 'D' . $i, $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat);
		    $sheet->SetCellValue ( 'E' . $i, $val->direccion );
		    $sheet->SetCellValue ( 'F' . $i, $val->ciudad );
		    $sheet->SetCellValue ( 'G' . $i, $val->ciudad );
		    $sheet->SetCellValue ( 'H' . $i, $val->nombres_adm.' '.$val->apellidos_adm );
			$sheet->SetCellValue ( 'I' . $i, $val->etapa);
			$sheet->SetCellValue ( 'J' . $i, $fecha_etapa);
			$sheet->SetCellValue ( 'K' . $i, $val->tribunal );
		    $sheet->SetCellValue ( 'L' . $i, $val->distrito );
		    $sheet->SetCellValue ( 'M' . $i, $val->rol );
			
			
		 $i++;
		}
		
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' );	
				
				
				/* if((int)$this->data['current_pag'] > 0) {
					$limit = ' LIMIT '.(int)$this->data['current_pag'].', '.$config['per_page'];
				} else {
					$limit = ' LIMIT '.$config['per_page'];
				}  */
	    	}


			// Numero total de registros
			$query = $this->db->query("SELECT COUNT(*) as total FROM (SELECT ce.* ".$sql_body.$sql_where.$group.") as t WHERE 1", $bind_values);
    		$config['total_rows'] = $query->first_row()->total;

			// Listar registros
			
			$query = $this->db->query($sql_select.$sql_body.$sql_where.$group.$limit, $bind_values);
			$this->data['lists'] = $query->result();

			if(count($suffix) > 0) {
				$config['suffix'] = '';
				foreach ($suffix as $key => $value) {
					// si el campo es un select multiple se descompone el array
					if (is_array($value)) {
						foreach ($value as $v) {
							$config['suffix'] .= '&'.$key.'='.$v;
						}
					} else {
						$config['suffix'] .= '&'.$key.'='.$value;
					}
				}
				$config['suffix'] = '?'.ltrim($config['suffix'], '&');
			}

	    	// ---------------------------------------------------------------
	    	// FIN nuevas consultas para generar resporte
	    	// ---------------------------------------------------------------
	

	    	$array_csv = array();
	    	$array_csv[] = array('Mandante','Rut','Deudor',utf8_decode('Dirección'),'Comuna','Ciudad','Procurador','Etapa del Juicio','Fecha Etapa','Estado Cuenta','Tribunal','Distrito','Rol');    	
	    	foreach ($this->data['lists'] as $obj) {
				$array_csv[] = array(utf8_decode($obj->razon_social),$obj->rut,utf8_decode($obj->usr_nombres.' '.$obj->usr_ap_pat.' '.$obj->usr_ap_mat),utf8_decode($obj->direccion.' '.$obj->direccion_numero.' '.$obj->direccion_dpto),utf8_decode($obj->comuna),utf8_decode($obj->ciudad),utf8_decode($obj->nombres.' '.$obj->apellidos),$obj->etapa,date("d-m-Y", strtotime($obj->fecha_etapa)),$obj->estado,$obj->tribunal,$obj->distrito,$obj->rol);
			}
			
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='procurador/reportes/reporte_etapas';

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
								 			 ->join("2_cuentas_gastos gastos", "gastos.id_cuenta = cta.id")	
	    								  	 ->count_all_results("0_cuentas cta");
		    
	    	if ($param == 'exportar'){
			}
			else{
				$this->db->limit($config['per_page'],$this->data['current_pag']);
			}
			
			if(empty($like["gastos.fecha"]))
				$like["gastos.fecha"] = date("Y-m");
			
			
			$query_master =$this->db->select('cta.id AS id, cta.activo AS activo, cta.publico AS publico, cta.posicion AS posicion, usr.rut AS rut, cta.rol AS rol, adm.nombres AS nombres, adm.apellidos AS apellidos, mand.razon_social, gastos.fecha AS fecha, gastos.n_boleta AS n_boleta, gastos.rut_receptor AS rut_receptor, gastos.nombre_receptor AS nombre_receptor, gastos.monto AS monto, gastos.retencion AS retencion, gastos.descripcion AS descripcion, cta.id_mandante AS field_categoria')
								 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
								 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 ->join("0_administradores adm", "adm.id = cta.id_procurador")
								 ->join("2_cuentas_gastos gastos", "gastos.id_cuenta = cta.id")		 
								 ->where($where)
								 ->like($like,'match','after')
								 ->order_by("id_mandante", "desc")
								 ->order_by("posicion", "desc")
								 //->group_by("gastos.n_boleta")
				 				 ->get("0_cuentas cta");
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
			$this->data['plantilla']='procurador/reportes/reporte_gastos';
	    }//gastos
		
		/************************* ESTADOS *************************/
	    if ($tipo == 'estados'){
	    	//$where = array();
	    	//echo 'REPORTE DE ESTADOS (EN DESARROLLO)';
	    	
	    //$this->output->enable_profiler(TRUE);
	    	
	    	$config['suffix']=''; $group_by = '';
	    	$order_by = '';
	    	
	     
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
		   
		    
		    	
		    	 if (isset($_REQUEST['id_mandante']) && $_REQUEST['id_mandante']>0){ 
		    		$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    	} 
		    	
		    	
	        if (isset($_REQUEST['rol']) && $_REQUEST['rol']>0){ 
		    		$where["cta.rol"] = $_REQUEST['rol'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'rol='.$_REQUEST['rol'];	
		    }	
		    	
		    	
	    		if (isset($_REQUEST['etapa']) && $_REQUEST['etapa']!='' && $_REQUEST['etapa']>0){ 
		    		$where["etapas.id"] = $_REQUEST['etapa'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'etapa='.$_REQUEST['etapa'];	
		    	}
	    		
		   if (isset($_REQUEST['estado']) && $_REQUEST['estado']!='' && $_REQUEST['estado']>=0){ 
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
		    	//$like["cetapa.fecha_etapa"]=$year_i.$mes_i;
		    	
		    
	    	/*$sql_where .= " AND cta.id_procurador = ? ";
    		$bind_values[] = $id_procurador;
    		$suffix['id_procurador'] =  $id_procurador;*/
		    	$id_procurador = $this->session->userdata('usuario_id');
	           	if ($this->session->userdata('usuario_id')){ 
	    			$where["cta.id_procurador"] = $id_procurador;
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'id_procurador='.$id_procurador;	
		    	}
    		
    		// if(empty($like["cetapa.fecha_etapa"]))
		    // 	$like["cetapa.fecha_etapa"] = date("Y-m");
		    
	    	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
	    	
	    	////->join_sql("LEFT JOIN 2_cuentas_etapas cetapa ON cetapa.id=cta.id AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)")
	    	
	    	
	    	$query_total = $this->db->select("cta.id")->where($where)
	    								  	 ->join("0_usuarios usr", "usr.id = cta.id_usuario AND usr.activo='S' AND cta.activo='S'")
											 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
											 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
											 ->join("2_cuentas_pagos pag2", "pag2.id_cuenta = cta.id AND pag2.activo='S'","left")
											 
											 //->join_sql("LEFT JOIN 2_cuentas_etapas cetapa ON cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)")
											 			
											 //->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S' AND pag.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=cta.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left")
											 
											 ->join("s_estado_cuenta estado", "estado.id = cta.id_estado_cuenta") 
											 ->join("s_comunas com", "com.id = usr.id_comuna","left") 
											 //->join("2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id AND cetapa.activo='S'","left")// AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)","left")
											 ->join("s_etapas etapas", "etapas.id = cta.id_etapa","left")
											 //->where("(cetapa.fecha_etapa IS NULL OR cetapa.fecha_etapa = (SELECT MAX(fecha_etapa) FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta = cta.id AND 2_ce.activo='S'))")
								 			 ->where("cta.activo","S")
											 
											 ->like($like,'match','after')
											 
											 ->group_by("cta.id")
											 ->get("0_cuentas cta");
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
								 ->join("2_cuentas_pagos pag2", "pag2.id_cuenta = cta.id AND pag2.activo='S'","left")
								 
								// ->join_sql("LEFT JOIN 2_cuentas_etapas cetapa ON cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)")
								 //->join("2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id AND cetapa.activo='S' AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)","left")
											
								 ->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S' AND pag.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=cta.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left")
								 
								 ->join("s_estado_cuenta estado", "estado.id = cta.id_estado_cuenta") 
								 ->join("s_comunas com", "com.id = usr.id_comuna","left") 
								 
								 //->join("2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id AND cetapa.activo='S'","left")// AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)","left")
								 ->join("s_etapas etapas", "etapas.id = cta.id_etapa","left")
								// ->where("(cetapa.fecha_etapa IS NULL OR cetapa.fecha_etapa = (SELECT MAX(fecha_etapa) FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta = cta.id AND 2_ce.activo='S'))")
								 
								 ->where("cta.activo","S")
								 ->like($like,'match','after')
								 //->order_by("id_mandante", "DESC")
								 ->group_by("cta.id")
								 //->order_by("pag.fecha_pago", "DESC")
								 ->order_by("cta.fecha_asignacion", "DESC")
				 				 ->get("0_cuentas cta");
			$this->data['lists']=$query_master->result();
			
			//echo count($this->data['lists']);
			
			/*if ($_REQUEST['agrupar']=='S'){
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
			}*/			
			
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
			$this->data['plantilla']='procurador/reportes/reporte_estados';
	   	 }//estados
		
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
		}elseif($param == 'exportar_fullpay'){
			
			
			
			
		//Mandante,"Estado Cuenta",Rut,Deudor,"Monto Pagaré","Fecha Pagaré","Etapa del Juicio","Fecha Último Pago","Saldo deuda"
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'MANDANTE' );
		$sheet->SetCellValue ( 'B1', 'ESTADO DE CUENTA' );
		$sheet->SetCellValue ( 'C1', 'RUT' );
		$sheet->SetCellValue ( 'D1', 'DEUDOR' );
		$sheet->SetCellValue ( 'E1', 'MONTO PAGARE' );
		$sheet->SetCellValue ( 'F1', 'FECHA ASIGNACIÓN');
		$sheet->SetCellValue ( 'G1', 'ETAPA JUICIO' );
		$sheet->SetCellValue ( 'H1', 'FECHA ÚLTIMO PAGO' );
		$sheet->SetCellValue ( 'I1', 'SALDO DEUDA' );
		
		
		
		$where = array ();
		$where_str = '';
		$like = array ();
		$config ['suffix'] = '';
		
		
		
		if (isset ( $_REQUEST ['etapa'] ) && $_REQUEST ['etapa'] != '') {
			$where ['etap.id'] = $_REQUEST ['etapa'];
		}
		
		if (isset ( $_REQUEST ['rut'] ) && $_REQUEST ['rut'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut'];
		}
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['c.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['c.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['c.id_mandante'] = $_REQUEST ['id_mandante'];
		}
		
		if (isset ( $_REQUEST ['estado'] ) && $_REQUEST ['estado'] > 0) {
			$where ['c.id_estado_cuenta'] = $_REQUEST ['estado'];
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
		    $like["p.fecha_asignacion"]=$year_i.$mes_i;
		
	
		/*$p_estado = $this->input->get_post('estado');
		if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
			$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
			$suffix ['estado[]'] = $p_estado;	
	    }*/
	    
		
	 
		//$this->db->where ( array ('c.activo' => 'S' ) );
		$cuentas = $this->cuentas_m->get_procurador_cuenta_exportar_fullpay ( $where, $like, $where_str );
		//}
		//print_r($cuentas);
		//die();
		
		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
			if($val->fecha_asignacion == '30-11--0001' || $val->fecha_asignacion == '31-12-1969' || $val->fecha_asignacion == null || $val->fecha_ultimo_pago == '' ){
		     $fecha_asignacion = '-'; 	
		    }else{
		  	 $fecha_asignacion  = date('d-m-Y',strtotime($val->fecha_asignacion));
		    }
			
			if($val->fecha_ultimo_pago == '30-11--0001' || $val->fecha_ultimo_pago == '31-12-1969' || $val->fecha_ultimo_pago == null || $val->fecha_ultimo_pago == ''){
		    $fecha_ultimo_pago = '-'; 
		    }else{
		    $fecha_ultimo_pago = date('d-m-Y H:i:s',strtotime($val->fecha_ultimo_pago));		
		    }
		    
		   	
		    
		    $sheet->SetCellValue ( 'A' . $i, $val->codigo_mandante );
			$sheet->SetCellValue ( 'B' . $i, $val->estado );
			$sheet->SetCellValue ( 'C' . $i, $val->rut );
			$sheet->SetCellValue ( 'D' . $i, $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat);
		    $sheet->SetCellValue ( 'E' . $i, $val->monto_deuda );		
			$sheet->SetCellValue ( 'F' . $i, $fecha_asignacion );
		    $sheet->SetCellValue ( 'G' . $i, $val->etapa);
			$sheet->SetCellValue ( 'H' . $i, $fecha_ultimo_pago );
			$sheet->SetCellValue ( 'I' . $i, $val->saldo_deuda);
			
			
			$i ++;
		}


		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' );	
				
		}
		
		$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'perfil' => '3'));
		$a=$this->administradores_m->get_all();
		$this->data['procuradores'][0]='Seleccionar';
		foreach ($a as $obj) {$this->data['procuradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
		$this->data['mandantes'][0]='Seleccionar';
		$a=$this->mandantes_m->get_many_by(array('activo' => 'S'));
		foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}
		
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
		
	
		$a=$this->mandantes_m->get_by(array('activo'=>'S'));
		$this->data['mandantes'][0]='Seleccionar Mandante';
		foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}
		
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
				if (!empty($fecha_demanda)){$fecha_demanda =  date( 'Y-m-d ' , strtotime( $fecha_demanda));}
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
							$this->cuentas_m->insert($arreglo_cuenta,TRUE,TRUE);
							$array_return['cuentas_insert']++;
							
							if (!empty($obs)){ 
								$arreglo_historial['id_cuenta'] = $this->db->insert_id();
								$arreglo_historial['historial'] = $obs;
								$arreglo_historial['fecha'] = date('Y-m-d H:i:s');
								$arreglo_historial = array_merge($arreglo_historial, array('fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
								$this->cuentas_historial_m->insert($arreglo_historial,TRUE,TRUE);
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
							
							
							$id_cuenta=$this->cuentas_m->search_id_record_exist(array('id_usuario'=>$id,'id_mandante' => $_POST['id_mandante']));
							if ($id_cuenta!=''){
								$arreglo_cuenta = array_merge($arreglo_cuenta, array('fecha_mod' => date('Y-m-d H:i:s'),'ip_mod' => $this->input->ip_address(), 'user_mod' => $this->session->userdata('usuario_id')));
								$this->cuentas_m->update($id_cuenta,$arreglo_cuenta,TRUE,TRUE);
								$array_return['cuentas_update']++;
							} else {
	
								$arreglo_cuenta = array_merge($arreglo_cuenta, array('id_usuario' => $id, 'fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
								$this->cuentas_m->insert($arreglo_cuenta,TRUE,TRUE);
								$array_return['cuentas_insert']++;
	
							}
							if (!empty($obs)){ 
								$arreglo_historial['id_cuenta'] = $id_cuenta;
								$arreglo_historial['historial'] = $obs;
								$arreglo_historial['fecha'] = date('Y-m-d H:i:s');
								$arreglo_historial = array_merge($arreglo_historial, array('fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
								
								$this->cuentas_historial_m->insert($arreglo_historial,TRUE,TRUE);
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
		
	function dropdown_ajax_etapa($id_etapa=''){

		$where = '';
		
			//$this->output->enable_profiler(TRUE);
		$d = $this->etapas_m->get_by( array('id'=>$id_etapa) );
		if( count($d)>0 ){
			
			$array = explode(';', $d->sucesor);
		
			foreach ($array as $key => $val){
				 if ($val!=''){
					 if($where!=''){
					 	$where.=" OR ";
					 }
					 $where.= "codigo='$val'";
				 }
			}
			if($where!=''){
			 	$where = '('.$where.')';
			}
			//where = (codigo='ppl004' and codigo='ppl017')
			
			if ($where!=''){
			$where.=" OR (tipo='2')";
			}
			
		}

		

		if($where != ''){
			$this->db->where( $where );
		}
		
		//###################  ETAPAS DE JUICIO (PERFIL PROCURADOR)  ###################################//
		$this->db->order_by( 'tipo','ASC' )->order_by('posicion','ASC')->order_by('codigo','ASC');
		$dat = $this->cuentas_etapas_m->get_many_by(array('activo'=>'S','codigo !='=>''));
		
		echo '<option value="">SeleccionarXX</option>';
		
		/*
		foreach($dat as $key=>$val){
		    echo '<option value="'.$val->id.'">'.strtoupper($val->codigo).' - '.$val->etapa.'</option>';
		}

		*/

		//foreach ($dat as $key=>$val) {$this->data['etapas'][$val->id] = $val->codigo.' '.$val->etapa;}

		//################# MUESTRA LA ETAPA OTRO #####################// 
		
		//echo '<option value="otro">Otro</option>';
	}
		
	//####################### FUNCION SELECT TABLA S_ETAPAS POR TIPO  ##########################//	
	function dropdown_ajax_etapa_otro(){

		$this->db->where( 'tipo', '3' );
		$this->db->where( 'etapa !=', 'Otro' );
		$this->db->order_by( 'codigo','ASC' );
		$dat = $this->etapas_m->get_all();
		
		echo '<option value="">Seleccionar</option>';
		
		foreach($dat as $key=>$val){
		    echo '<option value="'.$val->id.'">'.strtoupper($val->codigo).' - '.$val->etapa.'</option>';
		}

	}
	
	function etapa($accion=''){
		
		if($accion == 'save'){
			
			$id_cuenta		= $this->input->post('id_cuenta');
			$etapa_juicio	= $this->input->post('etapa_juicio');
			$etapa_otro		= $this->input->post('etapa_otro');
			$fecha_etapa	= $this->input->post('fecha_etapa');
			
			if( $this->input->post('observaciones') ){
				$observaciones	= $this->input->post('observaciones');
			}else{
				$observaciones	= '';
			}

			
			$fields_save = array (
				'id_etapa' => $etapa_juicio, 
				'id_cuenta' => $id_cuenta,
				'id_administrador' => $this->session->userdata('usuario_id'),
				'fecha_etapa' => $fecha_etapa,
				//'fecha_etapa' => date('Y-m-d'),
				'observaciones'=>$observaciones	
			 );
			 

			 if( $this->input->post('fecha_inicio_day') ){
			 	$fields_save['fecha_etapa'] = $this->input->post('fecha_inicio_year').'-'.$this->input->post('fecha_inicio_month').'-'.$this->input->post('fecha_inicio_day');
			 }
			
			if( $etapa_otro != '' ){// la id_etapa de la cuenta no se modifica, pero si se registra en el historial
				$this->cuentas_historial_m->save('', array('id_etapa'=>$etapa_otro,'historial'=>$observaciones,'id_cuenta'=>$id_cuenta,'fecha'=>$fecha_etapa/*date("Y-m-d H:i:s")*/));
				$dat_final = $this->etapas_m->get_by( array('id'=>$etapa_otro) );
			}else{
				
				$this->cuentas_historial_m->save('', array('id_etapa'=>$etapa_juicio,'historial'=>$observaciones,'id_cuenta'=>$id_cuenta,'fecha'=>$fecha_etapa/*date("Y-m-d H:i:s")*/));
				
				$this->cuentas_etapas_m->save( '', $fields_save );
				$id_etapa_new= $this->db->insert_id();
				
				/* INICIO LOG ETAPAS */
				$s_log_etapas = array();
				$s_log_etapas['operacion'] = 'crea';
				
				$cta = $this->cuentas_m->get_by( array('id'=>$id_cuenta) );
				
				if( count($cta)>0 ){
					$s_log_etapas['operacion'] = 'modifica';
					$s_log_etapas['dato_anterior'] = $cta->id_etapa;
				}
				
				$s_log_etapas['id_etapa'] = $id_etapa_new;
				$s_log_etapas['id_usuario'] = $this->session->userdata('usuario_id');
				$s_log_etapas['id_cuenta'] = $id_cuenta;
				$s_log_etapas['dato_nuevo'] = $etapa_juicio;
				$s_log_etapas['fecha'] = $fecha_etapa/*date("Y-m-d H:i:s")*/;
					$this->log_etapas_m->save('', $s_log_etapas);
				/* FIN LOG ETAPAS */
					
				$this->cuentas_m->save( $id_cuenta, array('id_etapa'=>$etapa_juicio,'fecha_etapa'=>$fields_save['fecha_etapa']) );

				$dat_final = $this->etapas_m->get_by( array('id'=>$etapa_juicio) );
			}
			
			
			if( count($dat_final)>0 ){
				echo $dat_final->etapa;
			}
			
		}//save
	}
	
	public function fecha_etapa($id_etapa=''){
		
		$etapa = $this->etapas_m->get_by( array('id'=>$id_etapa) );
		if( count($etapa)>0 ){
			echo $etapa->seleccionar_fecha_alarma;
		}
	}

 	public function exportar_excel(){

 		//echo $_REQUEST['id_mandante'].'sdfsdfsdf';
 	    //die();
 		//echo $_REQUEST['rol'].'jjjjjjj';
 		//echo 'llega';
 		//die();
		//$this->output->enable_profiler ( TRUE );
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'MANDANTE' );
		$sheet->SetCellValue ( 'B1', 'RUT' );
		$sheet->SetCellValue ( 'C1', 'JUZGADO' );
		$sheet->SetCellValue ( 'D1', 'JURISDICCION' );
		$sheet->SetCellValue ( 'E1', 'ROL' );
		$sheet->SetCellValue ( 'F1', 'DEUDOR' );
		$sheet->SetCellValue ( 'G1', 'JUZGADO EXHORTO' );
		$sheet->SetCellValue ( 'H1', 'JURISDICCION EXHORTO' );		
		$sheet->SetCellValue ( 'I1', 'ROL EXHORTO' );
		$sheet->SetCellValue ( 'J1', 'ETAPA ANTERIOR' );
		/*
		if ($this->data ['nodo']->nombre == 'fullpay') {
			$sheet->SetCellValue ( 'F1', 'ETAPA ANTERIOR' );
		}
		if ($this->data ['nodo']->nombre == 'swcobranza') {
			$sheet->SetCellValue ( 'F1', 'OBSERVACIONES' );
		}*/
		$sheet->SetCellValue ( 'K1', 'ETAPA ACTUAL' );
		
		if ($this->data ['nodo']->nombre == 'fullpay') {
			//$sheet->SetCellValue ( 'H1', 'RUT' );
			//$sheet->SetCellValue ( 'I1', 'TIPO' );
			//$sheet->SetCellValue ( 'J1', 'FECHA INGRESO' );
		    //$sheet->SetCellValue ( 'K1', 'FECHA ACTUAL' ); 
		    $sheet->SetCellValue ( 'L1', 'OBSERVACIONES' );
		    $sheet->SetCellValue ( 'M1', 'FECHA ETAPA ACTUAL' ); 
		   
		}
		$config = '';
		//$suffix= array();
		$order_by = '';
		$where = array ();
		$config['suffix'] = '';
		
		if (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ['stripadre.id'] = $_REQUEST ['id_distrito'];
		}
		
		//print_r($_REQUEST);
		//if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] > 0) {
		//	$where ['c.rol'] = $_REQUEST ['rol'];
		//}
		
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ['stri.id'] = $_REQUEST ['id_tribunal'];
		}
 		
 		if (isset($_REQUEST['id_estado_cuenta']) && $_REQUEST['id_estado_cuenta']!='' && $_REQUEST['id_estado_cuenta']>0 ){ 
	    			$where["cta.id_estado_cuenta"] = $_REQUEST['id_estado_cuenta'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
	    	  } 
	    	  
	    	if ($order_by==''){
	    		$order_by = "id_mandante desc,cta.fecha_asignacion desc";
	    	}
		
		if (isset($_REQUEST['rut'])){
			if ($_REQUEST['rut']!=''){ 
				$like["usr.rut"] = $_REQUEST['rut'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rut='.$_REQUEST['rut'];
			}
		}		
		
		//print_r($_REQUEST);
		if (isset($_REQUEST['id_tribunal'])){
			if ($_REQUEST['id_tribunal']>0){
				$where["cta.id_tribunal"] = $_REQUEST['id_tribunal'];
			}
	    } elseif (isset($_REQUEST['id_distrito'])){
			if ($_REQUEST['id_distrito']>0){
				$where["cta.id_distrito"] = $_REQUEST['id_distrito'];
			}
	    }

		if (isset($_REQUEST['id_distritoe'])){if ($_REQUEST['id_distritoe']>0){
			$where["cta.id_distrito_ex"] = $_REQUEST['id_distritoe'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'id_distritoe='.$_REQUEST['id_distritoe'];
		}}	    	
		
		if (isset($_REQUEST['id_tribunale'])){if ($_REQUEST['id_tribunale']>0){
			$where["cta.id_tribunal_ex"] = $_REQUEST['id_tribunale'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'id_tribunale='.$_REQUEST['id_tribunale'];
		}}
		
		if (isset($_REQUEST['role']) && $_REQUEST['role']!=''){ 
			$where["cta.rolE"] = $_REQUEST['role'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'role='.$_REQUEST['role'];
		}	    		
		
    	
		//rut] => [id_mandante] => 0 [id_estado_cuenta] => -1 [id_procurador] => 0 [rol] => [id_distrito] => [id_tribunal] => 
		//[nombres] => [ap_pat] => tapia [id_etapa] => 0 
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$where ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$where ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['cta.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['cta.id_mandante'] = $_REQUEST ['id_mandante'];
		}
		if (isset ( $_REQUEST ['rut'] ) && $_REQUEST ['rut'] > 0) {
			$where ['usr.rut'] = $_REQUEST ['rut'];
		}
 		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] > 0) {
			$where ['cta.rol'] = $_REQUEST ['rol'];
		}	
		if (isset ( $_REQUEST ['role'] ) && $_REQUEST ['role'] > 0) {
			$where ['cta.rolE'] = $_REQUEST ['role'];
		}	
 		if (isset ( $_REQUEST ['id_etapa'] ) && $_REQUEST ['id_etapa'] > 0) {
			$where ['etap.id'] = $_REQUEST ['id_etapa'];
		}
		
		$where ['cta.activo'] = 'S';
 		if( $this->session->userdata("usuario_perfil") == 3 ){
    		$where["cta.id_procurador"] = $this->session->userdata("usuario_id");
    	}
						$this->db->select('
						
							cta.id AS id,
							cta.activo AS activo,
							cta.publico AS publico,
							cta.posicion AS posicion,
							cta.id_procurador,
							cta.id_estado_cuenta AS id_estado_cuenta,
							usr.nombres AS nombres,
							usr.ap_pat AS ap_pat,
							usr.ap_mat AS ap_mat,
							usr.rut AS rut,
							mand.codigo_mandante,
							mand.razon_social,
							tip.tipo AS tipo_producto,
							cta.fecha_asignacion AS fecha_asignacion,
							cta.monto_demandado AS monto_demandado,
							cta.monto_deuda AS monto_deuda,
							cta.id_etapa,
							cta.fecha_etapa,
							cta.receptor,
							cta.id_mandante AS field_categoria,
							cta.id_procurador,
							tr.tribunal,
							tr.id AS id_tribunal,
							dist.tribunal as distrito,
							dist.id as id_distrito,
							cta.rol as rol,
							cta.exorto as exorto,
							cta.id_etapa AS id_etapa,
							etap.etapa AS etapa_actual,
							etap.seleccionar_fecha_alarma,			
							SUM(pag.monto_pagado) AS total_pagado,
							tre.tribunal as tribunalE, 
							diste.tribunal as DistritoE, 
							cta.id_distrito_ex, 
							cta.rolE,
							(SELECT observaciones FROM 2_cuentas_etapas WHERE 2_cuentas_etapas.id_cuenta = cta.id ORDER BY id DESC LIMIT 1 ) as observaciones,
							DATEDIFF( NOW() , cta.fecha_etapa ) AS dias_diferencia,
							DATEDIFF( NOW() , cta.fecha_asignacion ) AS dias_,
							cta.acuse AS acuse,
							(SELECT id_etapa FROM 2_cuentas_etapas	WHERE id_cuenta=cta.id AND activo=\'S\' ORDER BY id  DESC LIMIT 1 ) eta
							
						');
						
						//(SELECT fecha_etapa FROM 2_cuentas_etapas WHERE cta.id=id_cuenta ORDER BY fecha_etapa DESC LIMIT 1) AS fecha_etapa,
						$this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
						$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
						$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")	;
						$this->db->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S'","left");
						$this->db->join("s_tribunales tr", "tr.id = cta.id_tribunal","left");
						$this->db->join("s_tribunales dist", "dist.id = tr.padre","left");
						$this->db->join("s_tribunales tre", "tre.padre = cta.id_tribunal_ex","left");
						$this->db->join("s_tribunales diste", "diste.id = cta.id_distrito_ex","left");
						$this->db->join("s_etapas etap","etap.id=cta.id_etapa","left");
						$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=cta.id_estado_cuenta","left");
						//$this->db->where($where)->like($like);
							
						if (count($_REQUEST)==0) {
							$this->db->where("(id_estado_cuenta='1' OR id_estado_cuenta='6')");
						}
						if (count($like)>=1) {
							$this->db->like($like);
						}
						if (count($where)>=1) {
							$this->db->where($where);
						}
						$this->db->order_by($order_by);
						$this->db->group_by("cta.id");
						$cuentas_procurador = $this->db->get("0_cuentas cta")->result();
		
		
		
		//$cuentas_procurador = $this->cuentas_m->get_procurador ( $where );
		//echo $this->db->last_query();
		
		//$cuentas_procurador = $query->result();
		//print_r($cuentas_procurador);
		
		$i = 2;
		foreach ( $cuentas_procurador as $key => $val ) {
			
			//die();
			//FULLPAY
			if ($this->data ['nodo']->nombre == 'fullpay') {
				$fecha_etapa = '';
				$observacion_anterior= '';
				//echo $val->id;
				$cuentas_etapas = $this->cuentas_etapas_m->get_cuentas_etapas($val->id);
				//echo $this->db->last_query();
				//echo '<pre>'; print_r($cuentas_etapas); echo '</pre>';
				if (count($cuentas_etapas)>0){
					
					$fecha_etapa = date('d-m-Y H:i:s',strtotime($cuentas_etapas[0]->fecha_etapa));
					$vv = $cuentas_etapas[0]->etapa.' ('.date('d-m-Y H:i:s',strtotime($cuentas_etapas[0]->fecha_etapa)).')';
					//echo "www";
					$etapa_actual_ = $cuentas_etapas[0]->etapa.' ('.date('d-m-Y H:i:s',strtotime($cuentas_etapas[0]->fecha_etapa)).')';
					
				}
				if (count($cuentas_etapas)>1){
					
		     		$vv = $cuentas_etapas[1]->etapa.' ('.date('d-m-Y H:i:s',strtotime($cuentas_etapas[1]->fecha_etapa)).')';
		     		$observacion_anterior = $cuentas_etapas[1]->observaciones; 
		     		$etapa_anterior_ = $cuentas_etapas[1]->etapa.' ('.date('d-m-Y H:i:s',strtotime($cuentas_etapas[1]->fecha_etapa)).')';
		        } //else {
				//	$vv = '-';
				//}
				
				$tipo = '';
				if ($val->exorto==1 && $val->tipo_demanda==1){
					$tipo = 'Propia con Exhorto';
				} elseif ($val->exorto!=1 && $val->tipo_demanda==1){
					$tipo = 'Propia sin Exhorto';
				} elseif ($val->exorto==1 && $val->tipo_demanda!=1){
					$tipo = 'Cedida con Exhorto';
				} elseif ($val->exorto!=1 && $val->tipo_demanda!=1){
					$tipo = 'Cedida sin Exhorto';
				}
				$fecha_inicio = '';
				if ($val->fecha_inicio!='' &&  $val->fecha_inicio != '0000-00-00'){
					$fecha_inicio = date('d-m-Y H:i:s',strtotime($val->fecha_inicio));
				}
				
				//echo $val->fecha_inicio.'<br>'.$val->id;		
				
			    
				/*if ($val->fecha_etapa!=''){
					$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
				}
				echo $fecha_etapa;*/
				
			}
			 
			   
			//SWCOBRANZA
			if ($this->data ['nodo']->nombre == 'swcobranza') {
				$this->db->order_by ( 'fecha DESC' );
				$historial = $this->cuentas_historial_m->get_by ( array ('id_cuenta' => $val->id ) );
				if (isset ( $historial->observaciones ) != '') {
					$hh = $historial->observaciones;
				} else {
					$hh = '-';
				}
			}

		// echo $val->etapa.' '.$val->idce.' '.$fecha_etapa.'<br>';
			
		
			$sheet->SetCellValue ( 'A' . $i, $val->razon_social );
			$sheet->SetCellValue ( 'B' . $i, $val->rut );
			$sheet->SetCellValue ( 'C' . $i, $val->tribunal);
			$sheet->SetCellValue ( 'D' . $i, $val->distrito );
			$sheet->SetCellValue ( 'E' . $i, $val->rol );
			$sheet->SetCellValue ( 'F' . $i, $val->ap_pat . '  '.$val->nombres );
			$sheet->SetCellValue ( 'G' . $i, $val->tribunalE );
			$sheet->SetCellValue ( 'H' . $i, $val->DistritoE );
			
			$sheet->SetCellValue ( 'I' . $i, $val->rolE );
			
			
			if ($this->data ['nodo']->nombre == 'fullpay') {
				$sheet->SetCellValue ( 'J' . $i, $etapa_anterior_);
			}
			
			if ($this->data ['nodo']->nombre == 'swcobranza') {
				$sheet->SetCellValue ( 'K' . $i, $hh );
			}
			
			$sheet->SetCellValue ( 'L' . $i, $etapa_actual_ );
			
			
			
			if ($this->data ['nodo']->nombre == 'fullpay') {
				
				$estado = '';
				
				//$sheet->SetCellValue (  'H' . $i, $val->observaciones );
				$sheet->SetCellValue (  'M' . $i, $val->observaciones );
				$sheet->SetCellValue (  'N' . $i, $fecha_etapa );
			    //$sheet->SetCellValue (  'K' . $i, $fecha_etapa ); 
			    //$sheet->SetCellValue (  'L' . $i,$observacion_anterior);
			    //$sheet->SetCellValue (  'M' . $i,$val->estado); 
			    
			   /* if( $val->id_estado_cuenta == 1){
			    $estado = 'Vigente';	
			    }elseif($val->id_estado_cuenta == 2){
			    $estado = 'Advenimiento';	
			    }elseif($val->id_estado_cuenta == 4){
			    $estado = 'Terminado';	
			    }
			    $sheet->SetCellValue ( 'M'.$i,$estado);*/ 
			  }
			
			$i ++;
		}
		//echo '<pre>';print_r($excel);echo '</pre>';die();
		//die();
	 	$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=procurador_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	
	public function estado_masivo(){
		//$this->output->enable_profiler(TRUE);
		
		if (isset($_POST['chks']) && $_POST['chks']!='' && isset($_POST['id_etapa_masiva']) && $_POST['id_etapa_masiva']>0){
			$chks = explode(',',$_POST['chks']);
			foreach ($chks as $key=>$val){
				$fields_save = array();
				$fields_save['id_cuenta'] = $val;
				$fields_save['id_etapa'] = $_POST['id_etapa_masiva'];
				$fields_save['fecha_etapa'] = date('Y-m-d H:i:s');
				$this->cuentas_etapas_m->save_default($fields_save,'');
				$this->cuentas_m->save_default(array('id_etapa'=>$_POST['id_etapa_masiva'],'fecha_etapa'=>$fields_save['fecha_etapa']),$val);
			}
		}
		$this->session->set_flashdata('success_etapas','La etapa de juicio ha sido guardado exitosamente en las cuentas seleccionadas');			
		redirect('admin/procurador/index/?id_distrito='.$_REQUEST['id_distrito'].'&id_tribunal='.$_REQUEST['id_tribunal'].'&id_procurador='.$_REQUEST['id_procurador'].'&id_mandante='.$_REQUEST['id_mandante']);
		
	}
	
}

?>