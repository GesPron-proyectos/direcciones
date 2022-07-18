<?php
class cobros extends CI_Controller {
	public $data = array();
	public $activo = 'S';
	protected $show_tpl = TRUE;
	public function cobros() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		
		
		
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
		
		/*seters*/
		$this->data['current'] = 'cobros';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'cobros/';
		$this->data['lists'] = array();
		
		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
		$this->data['forma_pagos'] = array(''=>'Forma Pago','TF'=>'Transferencia','DP'=>'DepÃ³sito','CH'=>'Cheque','EF'=>'Efectivo');
		
		$c=$this->estados_cuenta_m->order_by('estado','ASC')->get_all(); 
		$this->data['estados'][-1]='Seleccionar..';
	 	foreach ($c as $obj) {$this->data['estados'][$obj->id] = $obj->estado;}
	 	
		$this->data['estados_cobros'] = array('1'=>'Informada a cobro','2'=>'Enviada a cobro','3'=>'Cobrada');
		
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
	 	
		
		
		
		
		
		
		
		
		
	}
	public function alertas_convenio(){
		//$this->output->enable_profiler(TRUE);
		
					
		$cols[] = 'm.codigo_mandante AS codigo_mandante';
		$cols[] = 'u.nombres AS usuarios_nombres';
		$cols[] = 'u.ap_pat AS usuarios_ap_pat';
		$cols[] = 'u.ap_mat AS usuarios_ap_mat';
		$cols[] = 'u.rut AS usuarios_rut';
		$cols[] = 'c.dia_vencimiento_cuota AS cuentas_dia_vencimiento_cuota';
		$cols[] = 'c.id AS cuentas_id';
		$cols[] = "cp.fecha_pago AS fecha_pago";
		$cols[] = "m.codigo_mandante AS codigo_mandante";
		$cols[] = "COUNT(llam.id) AS llamadas";
		$cols[] = "llam.fecha AS fecha_ultima_llamada";
		$cols[] = "mail.mail AS mail";
		
		
		
		
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');

		$this->db->join("2_cuentas_pagos cp", "cp.id_cuenta = c.id AND cp.activo='S' AND cp.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=c.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left");
		$this->db->join('2_cuentas_llamadas llam', 'llam.id_cuenta = c.id',"left");		
		$this->db->join('2_cuentas_mail mail', 'mail.id_cuenta = c.id',"left");	
		$this->db->where(array('c.dia_vencimiento_cuota !='=>0));
		$this->db->where('c.id_estado_cuenta','1');
		
		$this->db->group_by('c.id');
		$this->db->order_by('cuentas_dia_vencimiento_cuota ASC,usuarios_ap_pat ASC,usuarios_ap_mat ASC');
		$query = $this->db->get('0_cuentas c');
		
		$lists = $query->result();
        foreach ($lists as $key=>$val){
        	
        	$m = date('m'); $y = date('Y');
        	if ($m==1){
        		$m_anterior = 12;
        		$y_anterior = date('Y')-1;
        	} else {
        		$m_anterior = $m-1;
        		$y_anterior = date('Y');
        	}
        	
        	$mes_anterior = mktime( 0, 0, 0, $m_anterior, 1, $y ); 
      		$dias_del_mes_anterior = date("t",$mes_anterior);
        	
        	if ($val->cuentas_dia_vencimiento_cuota==1){
        		$dia = $dias_del_mes_anterior-1;
        	} elseif ($val->cuentas_dia_vencimiento_cuota==2){
        		$dia = $dias_del_mes_anterior;
        	} else {
        		$dia =$val->cuentas_dia_vencimiento_cuota-2;
        	}
        	echo $dia.'_'.$val->usuarios_ap_pat.' '.$val->mail.' '.$val->cuentas_id.'<br>';
        	if ($dia==date('j')){
        		echo '--------HOY AVISA '.$val->cuentas_dia_vencimiento_cuota.'<br>';
        	}
        	
        }
	}
	public function index($action='',$id='') {


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
			$like = array();
	    	$where["cta.activo"] = "S";
			
	    	//$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
	    	$config['suffix'] = '';
	    	//if ($this->form_validation->run() == TRUE){
	    		
	    	$order_by = '';    	
			//Inicio Nuevo Orden
			
 		    if (isset($_REQUEST['fecha_asignacion_pagare']) && $_REQUEST['fecha_asignacion_pagare']!=''){
				if ($_REQUEST['fecha_asignacion_pagare'] == 'desc'){
					$order_by ='cta.fecha_asignacion desc';  
				} else {
					$order_by = 'cta.fecha_asignacion asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'fecha_asignacion_pagare='.$_REQUEST['fecha_asignacion_pagare'];
    		}
			
			if (isset($_REQUEST['diferencia']) && $_REQUEST['diferencia']!=''){
				if ($_REQUEST['diferencia'] == 'desc'){
					$order_by ='cta.fecha_asignacion desc';  
				} else {
					$order_by = 'cta.fecha_asignacion asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'diferencia='.$_REQUEST['diferencia'];
	    	}
			
			if (isset($_REQUEST['comuna']) && $_REQUEST['comuna']!=''){
				if ($_REQUEST['comuna'] == 'desc'){
					$order_by ='nombre_comuna desc';  
				} else {
					$order_by = 'nombre_comuna asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'comuna='.$_REQUEST['comuna'];
	    	}
			
			//print_r($_REQUEST);
			if (isset($_REQUEST['tribunal']) && $_REQUEST['tribunal']!=''){
				if ($_REQUEST['tribunal'] == 'desc'){
					$order_by ='CAST(tri.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(tri.tribunal AS UNSIGNED) asc';  					
				}
				
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunal='.$_REQUEST['tribunal'];
	    	}
			
			if (isset($_REQUEST['tribunalE']) && $_REQUEST['tribunalE']!=''){
				if ($_REQUEST['tribunalE'] == 'desc'){
					$order_by ='CAST(trie.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trie.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunalE='.$_REQUEST['tribunalE'];
	    	}
			
			//Fin Nuevo Orden
		    if (isset($_REQUEST['rut_orden']) && $_REQUEST['rut_orden']!=''){
				if ($_REQUEST['rut_orden'] == 'desc'){
					$order_by ='usr.rut desc';  
				} else {
					$order_by = 'usr.rut asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rut_orden='.$_REQUEST['rut_orden'];
			}
	    	
	    	if ($order_by==''){
	    		//$order_by = "usr.rut desc,cta.fecha_asignacion desc";
				$order_by = "cta.fecha_asignacion asc";
	    	}
	    	
			if (isset($_REQUEST['nombres_orden']) && $_REQUEST['nombres_orden']!=''){
				if ($_REQUEST['nombres_orden'] == 'desc'){
					$order_by ='usr.nombres desc';  
				} else {
					$order_by = 'usr.nombres asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'nombres_orden='.$_REQUEST['nombres_orden'];
    		}
	    		    	
	    	if (isset($_REQUEST['orden_rol']) && $_REQUEST['orden_rol']!=''){
				if ($_REQUEST['orden_rol'] == 'desc'){
					$order_by ='cta.rol desc';  
				} else {
					$order_by = 'cta.rol asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
    			$config['suffix'].= 'orden_rol='.$_REQUEST['orden_rol'];
    		}
	    
	    	
		      
	    	if ($order_by==''){
	    		$order_by = "id_mandante desc,cta.fecha_asignacion desc";
	    	}
	    	
	    	
			if (isset($_REQUEST['id_tribunal']) && $_REQUEST['id_tribunal']>0){
	    		$where["cta.id_tribunal"] = $_REQUEST['id_tribunal'];
	    	if ($config['suffix']!=''){ $config['suffix'].='&';}
    			$config['suffix'].= 'id_tribunal='.$_REQUEST['id_tribunal'];
		    } elseif (isset($_REQUEST['id_distrito']) && $_REQUEST['id_distrito']>0){
	    		$where["cta.id_distrito"] = $_REQUEST['id_distrito'];
	    	if ($config['suffix']!=''){ $config['suffix'].='&';}
    			$config['suffix'].= 'id_distrito='.$_REQUEST['id_distrito'];
		    } 
    	
	    	
		    if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){
	    		if ($this->nodo->nombre == 'fullpay'){
    				$where["usr.rut"] = str_replace('.','',$_REQUEST['rut']);
				} else {
					$where["usr.rut"] = $_REQUEST['rut'];
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rut='.$_REQUEST['rut'];
    		}
	    		    		
			if (isset($_REQUEST['nombres'])){
				if ($_REQUEST['nombres']!=''){ 
					$like["usr.nombres"] = $_REQUEST['nombres'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'nombres='.$_REQUEST['nombres'];
				}
			}
			
			if (isset($_REQUEST['id_estado_cuenta'])){
				if ($_REQUEST['id_estado_cuenta']>0){
					$where["cta.id_estado_cuenta"] = $_REQUEST['id_estado_cuenta'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
				}
			}
						
			if (isset($_REQUEST['nombre'])){
				if ($_REQUEST['nombre']!=''){
					$like["comu.nombre"] = $_REQUEST['nombre'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'nombre='.$_REQUEST['nombre'];
				}
			}
			
			if (isset($_REQUEST['ap_pat'])){if ($_REQUEST['ap_pat']!=''){
				$like["usr.ap_pat"] = $_REQUEST['ap_pat'];
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
			
		    if (isset($_REQUEST['bienes'])){
				if ($_REQUEST['bienes']!=''){ 
					$observacion = trim($_REQUEST['bienes']);	    				
					$like["2cb.observacion"] = $observacion; 
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'bienes='.$_REQUEST['bienes'];
				 }
			}
			
		    if (isset($_REQUEST['rut_parcial'])){
				if ($_REQUEST['rut_parcial']!=''){ 
				$rut_parcial = trim($_REQUEST['rut_parcial']);	    				
					$like["usr.rut"] = $rut_parcial; 
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'rut_parcial='.$_REQUEST['rut_parcial'];
				 }
			}
			if (isset($_REQUEST['operacion'])){if ($_REQUEST['operacion']>0){
				$where["cta.operacion"] = $_REQUEST['operacion'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'operacion='.$_REQUEST['operacion'];
	    	}}	    	
			
			if ($_REQUEST['rolE']!=''){
				//print_R($_REQUEST);
				if (count($_REQUEST['rolE'])>0){
					$like["cta.rolE"] = $_REQUEST['rolE'];
					//$where["cta.rolE"] = $_REQUEST['rolE'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'rolE='.$_REQUEST['rolE'];
				}
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
			
			
		    if (isset($_REQUEST['rol'])){
				if ($_REQUEST['rol']!=''){ 
					$like["cta.rol"] = $_REQUEST['rol'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'rol='.$_REQUEST['rol'];
				}
			}
			
			if (isset($_REQUEST['rol']) && $_REQUEST['rol']!=''){
				if ($_REQUEST['rol'] == 'desc'){
					$order_by ='CAST(tri.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(tri.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
    			$config['suffix'].= 'orden_rol='.$_REQUEST['rol'];
    		}
				
			//	if ($config['suffix']!=''){ $config['suffix'].='&';}
			//	$config['suffix'].= 'rol='.$_REQUEST['rol'];
			
			
			if (isset($_REQUEST['id_tribunal_comuna'])){
				if ($_REQUEST['id_tribunal_comuna']!=''){ 
					$where["trib_com.id"] = $_REQUEST['id_tribunal_comuna'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'id_tribunal_comuna='.$_REQUEST['id_tribunal_comuna'];
				}
			}
	    	// print_r($where);		 
	    	$this->db->where('padre', 0);
						
			$this->db->where('activo', 'S');
			//$this->db->order_by('tribunal', 'ASC');
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
			}else
			{		
				$tribunales[''] = 'Seleccionar';
			}
			$this->data['tribunales']= $tribunales;
	    		 
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/cobros/index/';
			
						 
			$this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
			$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
			$this->db->join("s_tribunales tri", "tri.id = cta.id_tribunal","left");
			$this->db->join("s_tribunales tribu", "tribu.id = cta.id_distrito","left");
			$this->db->join("s_tribunales trie", "trie.id = cta.id_tribunal_ex","left");
			$this->db->join("s_tribunales disE", "disE.id = cta.id_distrito_ex","left");
			$this->db->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left");
			$this->db->join("s_comunas comu", "comu.id = 2cd.id_comuna","left");
			$this->db->join("cuentas_informadas inf", "inf.idcuenta = cta.id");
			$this->db->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left");
			$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left");
			$this->db->join("pagare pa", "pa.idcuenta = cta.id","left");
			$this->db->join("2_cuentas_bienes 2cb", "2cb.id_cuenta = cta.id","left");
			$this->db->join("2_cuentas_contratos 2cc", "2cc.id_cuenta = cta.id","left");
			$this->db->where($where);
			
			if (count($_REQUEST)==0) {
				//$this->db->or_where("cta.id_estado_cuenta",'1');
				//$this->db->or_where("cta.id_estado_cuenta",'6');
				$this->db->where("(id_estado_cuenta='1' OR id_estado_cuenta='6')");
			}
			$this->db->like($like);
			$this->db->order_by($order_by);
			//$this->db->group_by("cta.id");
			$query_total = $this->db->get('0_cuentas cta');					 
						 
			$total_rows = $query_total->result();
			$config['total_rows'] = count($total_rows);
	    	$config['per_page'] = '30';
	    	
	    	$this->pagination->initialize($config);
			/*listado SUM(pag.monto_remitido) AS total*/
			//$this->db->start_cache();
			//$query =
			$this->db->select(
			'cta.id AS id,cta.rol AS rol,usr.id_comuna AS id_comuna,tri.tribunal AS tribunal,trib_com.tribunal as tribunal_padre_comuna,
		    ,mand.codigo_mandante AS codigo_mandante,tribu.tribunal AS tribunal_padre, 
			cta.activo AS activo, cta.publico AS publico, cta.posicion AS posicion,comu.nombre AS nombre_comuna,cta.id_procurador, usr.nombres AS nombres,usr.ap_pat AS ap_pat,usr.ap_mat AS ap_mat, 
			usr.rut AS rut, mand.razon_social,mand.clase_html AS clase_html,tip.tipo AS tipo_producto, cta.fecha_asignacion AS fecha_asignacion, cta.monto_demandado AS monto_demandado,
			cta.id_estado_cuenta AS id_estado_cuenta,cta.id_castigo AS id_castigo,2cc.numero_contrato AS numero_contrato,cta.id_mandante AS field_categoria,
			cta.monto_deuda AS monto_deuda,  
			cta.monto_pagado_new AS total_pagado, cta.n_pagare AS pagare,
			cta.monto_deuda_new AS monto_deuda_new, DATEDIFF( NOW(),cta.fecha_asignacion ) AS diferencia
			, cta.exorto, trie.tribunal as TribunalE, disE.tribunal as DistritoE,cta.rolE
			,cta.id_tribunal AS id_tribunal, pa.n_pagare as npage, operacion, inf.estado as estadocobro, inf.id as idcob');
			$this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
			$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
			$this->db->join("s_tribunales tri", "tri.id = cta.id_tribunal","left");
			$this->db->join("s_tribunales tribu", "tribu.id = cta.id_distrito","left");
			$this->db->join("s_tribunales trie", "trie.id = cta.id_tribunal_ex","left");
			$this->db->join("s_tribunales disE", "disE.id = cta.id_distrito_ex","left");
			$this->db->join("cuentas_informadas inf", "inf.idcuenta = cta.id");
			$this->db->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left");
			$this->db->join("s_comunas comu", "comu.id = 2cd.id_comuna","left");
			$this->db->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left");
			$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left");
			$this->db->join("pagare pa", "pa.idcuenta = cta.id","left");
			$this->db->join("2_cuentas_bienes 2cb", "2cb.id_cuenta = cta.id","left");
			$this->db->join("2_cuentas_contratos 2cc", "2cc.id_cuenta = cta.id","left");
			$this->db->where($where);
			//print_r($where);
			if (count($_REQUEST)==0) {
				//$this->db->or_where("cta.id_estado_cuenta",'1');
				//$this->db->or_where("cta.id_estado_cuenta",'6');
				$this->db->where("(id_estado_cuenta='1' OR id_estado_cuenta='6')");
			}
			$this->db->like($like);
			$this->db->order_by($order_by);
			$this->db->group_by("cta.id");
			$query = $this->db->get('0_cuentas cta',$config['per_page'],$this->data['current_pag']);
			//echo $this->db->last_query();			 				  
			//print_r($query->V1());
			
			$this->db->start_cache();
			$this->db->stop_cache();
			
			$this->data['lists'] = $query->result();
			
			//print_r($this->data['lists']);
			/*echo '<pre>';
			print_r( $this->data['lists'] );
			echo '</pre>';
			*/
			
			$this->data['total']=$config['total_rows'];
			/*posiciones*/
			$query = $this->db->select('id_mandante AS field_categoria, MAX(posicion) AS max_posicion, MIN(posicion) AS min_posicion')->group_by("id_mandante")->get("0_cuentas");
			foreach ($query->result() as $key=>$val){

				$this->data['posiciones'][$val->field_categoria]['max_posicion']=$val->max_posicion;
				$this->data['posiciones'][$val->field_categoria]['min_posicion']=$val->min_posicion;
				$this->data['posiciones'][$val->field_categoria]['field_categoria']=$val->field_categoria;
			}
			$this->db->where(array('activo' => $this->activo, 'public' => $this->activo));
			$a=$this->administradores_m->get_all();
			$this->data['procuradores'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['procuradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
			$this->data['mandantes'][0]='Seleccionar';
			$a=$this->mandantes_m->get_many_by(array('activo'=>'S'));
			foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}
						
			if (!$this->show_tpl){ 
				$this->data['plantilla'] = 'cobros/list'; 
				$this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );
			}			
		}
			
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}
	}

	public function save($id){
		//print_r($_REQUEST);
		//$this->output->enable_profiler(TRUE);
		$estado = $_REQUEST['estadoscobros'];
		$id_cuenta = $_REQUEST['id_cuenta'];
		$id = $_REQUEST['id'];
		$save=array();
				$save['idcuenta'] = $id_cuenta;
				$save['estado'] = $estado;				
				$save['id'] = $id;	
				//$save['usuario'] = $this->session->userdata('usuario_id');
				$this->cinformadas_m->save_default($save,$id_cuenta);
		redirect('admin/cobros');
	}
	public function historial($id){




       $fields_save = array();
		
		if ($this->input->post('obs')!=''){
			$fields_save['observacion'] = $this->input->post('obs');
            /*  $fields_save['id_cuenta'] = $this->input->post('id_cuenta');*/
          }

          /*$fields_save['fecha'] = date('Y-m-d');
          $fields_save['tipo_llamada'] =  $this->input->post('tipo_llamada');
          $this->cuentas_llamadas_m->save_default($fields_save,'');*/
		
		if ($fields_save['observacion']!=''){
			$fields = array();
			$fields['fecha'] = date('Y-m-d H:i:s');
            $fields_save['id_cuenta'] = $this->input->post('id_cuenta');
			$fields['observaciones'] = $this->input->post('obs');
			$this->cuentas_historial_m->save_default($fields,$id);
		}
		
	}
	
	public function finalizar($id_cuenta){
		
    	
		$fields_save = array();
		if ($this->input->post('repetir_llamada')==1){
			$fields_save['repetir_llamada'] = $this->input->post('repetir_llamada');
			$fields_save['fecha_repetir_llamada'] = date('Y-m-d',strtotime($this->input->post('fecha_repetir_llamada')));
		}
		if ($this->input->post('obs')!=''){
			$fields_save['observacion'] = $this->input->post('obs');
		}
		$fields_save['id_cuenta'] = $id_cuenta;
		$fields_save['fecha'] = date('Y-m-d');
		$fields_save['estado'] = 1;
		$this->cuentas_llamadas_m->save_default($fields_save,'');
		
		if ($fields_save['observacion']!=''){
			$fields = array();
			$fields['id_cuenta'] = $id_cuenta;
			$fields['fecha'] = date('Y-m-d H:i:s');
			
			$fields['observaciones'] = $fields_save['observacion'];
			$this->cuentas_historial_m->save_default($fields,'');
		}
		
	}
	public function realizadas(){

        $this->output->enable_profiler(TRUE);
		$where = array();
		$like = array();
		$config = array('suffix'=>'');
		$fecha_llamada = '';

        $tipo_llamadas =  array('Seleccionar'=> '', '1'=>'Compromiso de Pago', '2'=>'Recado','3'=>'No corresponde','4'=>' Buzón','5'=>'No contesta','6'=>'No Pago','7'=>'Visita de Oficina' );
        $this->data['tipo_llamadas']     = $tipo_llamadas;
		
		if (isset($_REQUEST['fecha_llamada_year']) && $_REQUEST['fecha_llamada_year']>0){ 
    		$fecha_llamada.= $_REQUEST['fecha_llamada_year'];	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].= 'fecha_llamada_year='.$_REQUEST['fecha_llamada_year'];
    	}
		if (isset($_REQUEST['fecha_llamada_month']) && $_REQUEST['fecha_llamada_month']>0){ 
    		$fecha_llamada = $_REQUEST['fecha_llamada_month'];	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].='fecha_llamada_month='.$_REQUEST['fecha_llamada_month'];
    	}
    	if ($fecha_llamada!=''){
    		$like["llam.fecha"] = $fecha_llamada;
    	}
		if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']!='' && $_REQUEST['id_mandante']!='0'){		
          	 $where["m.id"] = $_REQUEST['id_mandante'];
    			if ($config['suffix']!=''){ $config['suffix'].='&';}
    			$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
    		}}

        if (isset($_REQUEST['rut'])){if ($_REQUEST['rut']!='' && $_REQUEST['rut']!='0' ){
            $like["u.rut"] = $_REQUEST['rut'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'rut='.$_REQUEST['rut'];
        }}

         if (isset($_REQUEST['tipo_llamada'])){if ($_REQUEST['tipo_llamada']!='' && $_REQUEST['tipo_llamada']!='0' && $_REQUEST['tipo_llamada']!='Seleccionar'){
            $where["llam.tipo_llamada"] = $_REQUEST['tipo_llamada'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'tipo_llamada='.$_REQUEST['tipo_llamada'];
         }}

    	
    	if($config['suffix']!= ''){
    	 	$config['suffix'] = '?'.$config['suffix'];	
    	}
    	$cols = array();
		$cols[] = 'm.codigo_mandante AS codigo_mandante';
		$cols[] = 'u.nombres AS usuarios_nombres';
		$cols[] = 'u.ap_pat AS usuarios_ap_pat';
		$cols[] = 'u.ap_mat AS usuarios_ap_mat';
		$cols[] = 'u.rut AS usuarios_rut';
		$cols[] = 'llam.fecha AS fecha';
		$cols[] = 'llam.observacion AS observacion';
		$cols[] = 'llam.repetir_llamada AS repetir_llamada';
		$cols[] = 'llam.fecha_repetir_llamada AS fecha_repetir_llamada';
		$cols[] = 'c.id AS cuentas_id';
        $cols[] = 'llam.tipo_llamada AS tipo_llamada';
		/**/
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join('2_cuentas_llamadas llam', 'llam.id_cuenta = c.id');							 
		if (count($like)>0){
			$this->db->like($like);
		}
	    
		$this->db->group_by('c.id');
		$query = $this->db->get('0_cuentas c');
		
		
		
		/**/
		$config['per_page'] = '50';
		$config['uri_segment'] = '4';
		$this->data['total'] = $config['total_rows'] = count($query->result());
	    $config['base_url'] = site_url().'/admin/llamadas/realizadas/';
	    $this->data['current_pag'] = $this->uri->segment(4);

		/**/
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join('2_cuentas_llamadas llam', 'llam.id_cuenta = c.id');		
		$this->db->where('c.activo','S');
		//$this->db->where('c.id_estado_cuenta','1');
		if (count($like)>0){
			$this->db->like($like);
		}
	    if (count($where)>0){
			$this->db->where($where);
		}
		$this->db->group_by('llam.id');
		$this->db->order_by('llam.fecha desc');
		$query = $this->db->get('0_cuentas c',$config['per_page'],$this->data['current_pag']);
		/**/
		$lists = $query->result();
        $this->data['lists'] = $lists;

        //print_r($lists);
        //die();

        
       // print_r($lists).'dkjfdksjfslkdjfsldfjl';
        
        $this->data['sub_current'] = 'realizadas';
        $this->load->library('pagination');
	    $this->pagination->initialize($config);
		$this->data['plantilla'] = 'llamadas/realizadas'; 
		$this->load->view ( 'backend/index', $this->data );
	}
	function actualizar_estado($id){
		$tipos = array('0'=>'Tipo','1'=>'Particular','2'=>'Comercial','3'=>'Celular','4'=>'Otro');
		$save = false;
		$fields_save['estado'] = $this->input->post('estado');
		$save = $this->telefono_m->save_default($fields_save,$id);
		$telefono = $this->telefono_m->get_by(array('id'=>$id));

		if ($telefono->estado==0){echo '<a href="#" class="img-check telefono-act-estado" rel="'.$telefono->id.'" data-estado="1"><img src="'.base_url().'img/ico-uncheked.png"></a>';}
		if ($telefono->estado==1){echo '<a href="#" class="img-check telefono-act-estado" rel="'.$telefono->id.'" data-estado="2"><img src="'.base_url().'img/ico-cheked-no.png"></a>';}
		if ($telefono->estado==0){echo '<a href="#" class="img-check telefono-act-estado" rel="'.$telefono->id.'" data-estado="2"><img src="'.base_url().'img/eliminar.jpg"></a>';}
		$style='';if ($telefono->estado==1){$style = ' style="color:#7FBA00"';}
		echo '<div class="phone-box">';
		if ($telefono->estado<2){
			echo '<span'.$style.'>'.$telefono->numero.'</span> ('.$tipos[$telefono->tipo].')'; 
		} else {
			echo '';
		}
		echo '</div><div class="clear"></div>';
		
	}










    public function form_llamada($id){

        $f = array();

        if ($this->input->post('tipo_llamada')!=''){
            $f['tipo_llamada'] = $this->input->post('tipo_llamada');
        }

        if ($f['tipo_llamada']!=''){

            $fields['id_cuenta'] = $this->input->post('id_cuenta');
            $fields['fecha'] = date('Y-m-d H:i:s');
            $fields['tipo_llamada'] = $f['tipo_llamada'];
            $this->cuentas_llamadas_m->save_default($fields,$id);
         }

        redirect('admin/llamadas/index/');

    }


     public function exportador_cuentas_llamadas_fullpay(){


       // $this->output->enable_profiler(TRUE);

         $where = array ();
         $like = array ();
         $config ['suffix'] = '';

         $this->load->library ( 'PHPExcel' );
         //$this->load->library('PHPExcel/IOFactory');
         $excel = new PHPExcel ();
         $excel->setActiveSheetIndex ( 0 );
         $sheet = $excel->getActiveSheet ();
         $sheet->SetCellValue ( 'A1', 'ID' );
         $sheet->SetCellValue ( 'B1', 'RUT  ' );
         $sheet->SetCellValue ( 'C1', 'NOMBRED DEUDOR' );
         $sheet->SetCellValue ( 'D1', 'DÍA COVENIO' );
        // $sheet->SetCellValue ( 'E1', 'ULTIMO PAGO ULTIMA LLAMADA' );
        //  $sheet->SetCellValue ( 'F1', 'LLAMADAS' );
        // $sheet->SetCellValue ( 'G1', 'TELÉFONOS' );
         $sheet->SetCellValue ( 'E1', 'ESTADO CAUSA' );




         $config = array('suffix'=>'');


         if (isset($_REQUEST['rut'])){if ($_REQUEST['rut']!='' && $_REQUEST['rut']!='0'){
             $like["u.rut"] = $_REQUEST['rut'];
             if ($config['suffix']!=''){ $config['suffix'].='&';}
             $config['suffix'].= 'rut='.$_REQUEST['rut'];
         }}

            if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']!='' && $_REQUEST['id_mandante']!='0'){
             //echo 'entroooooo'.$_REQUEST['id_mandante'];
             $where["man.id"] = $_REQUEST['id_mandante'];
             if ($config['suffix']!=''){ $config['suffix'].='&';}
             $config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
         }}

         if (isset($_REQUEST['estado'])){if ($_REQUEST['estado']!='' && $_REQUEST['estado']!='0'){
             //echo 'entroooooo'.$_REQUEST['id_mandante'];
             $where["llam.estado"] = $_REQUEST['estado'];
             if ($config['suffix']!=''){ $config['suffix'].='&';}
             $config['suffix'].= 'estado='.$_REQUEST['estado'];
         }}


        $cuentas_llamadas = $this->cuentas_llamadas_m->get_cuentas_llamadas ( $where, $like );

        $i = 2;
         foreach ( $cuentas_llamadas as $key => $val ) {


                 if($val->estado == 1){
                   $tl = 'Compromiso de Pago';
                 }elseif($val->estado == 2){
                     $tl = 'Recado';
                 }elseif($val->estado == 3){
                     $tl = 'No corresponde';
                 }elseif($val->estado == 4){
                     $tl = 'Buzón';
                 }elseif($val->estado == 5){
                     $tl = 'No contesta';
                 }elseif($val->estado == 6){
                     $tl = 'No Pago';
                 }elseif($val->estado == 7) {
                     $tl = 'Visita Oficina';
                  }elseif($val->estado == 0){
                     $tl = '-';
                 }


             $sheet->SetCellValue ( 'A' . $i, $val->id );
             $sheet->SetCellValue ( 'B' . $i, $val->rut );
             $sheet->SetCellValue ( 'C' . $i, $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat);
             $sheet->SetCellValue ( 'D' . $i, $val->dia_vencimiento_cuota);
             $sheet->SetCellValue ( 'E' . $i, $tl );

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




}

?>