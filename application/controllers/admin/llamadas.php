<?php
class llamadas extends CI_Controller {
	public $data = array();
	public $activo = 'S';
	protected $show_tpl = TRUE;
	public function llamadas() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		
		$this->load->helper ( 'date_html_helper' );
		
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'cuentas_m' ); $this->load->model ( 'cuentas_etapas_m' );
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
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'cuentas_pagos_m' );
		$this->load->model ( 'cuentas_llamadas_m' );	
		$this->load->model ( 'llamadas_m' );	
		$this->load->model ( 'nodo_m' );
		/*seters*/
		$nodo = $this->nodo_m->get_by( array('activo'=>'S') );
		$this->data['nodo'] = $nodo;	
		$this->data['current'] = 'llamadas';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'llamadas/';
		$this->data['lists'] = array();
			
		
	        $a=$this->mandantes_m->get_many_by(array('activo'=>'S'));
			$this->data['mandantes'][0]='Seleccionar Mandante';
			foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}
		
		
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


        $this->output->enable_profiler(TRUE);

       $tipo_llamadas =  array(''=> 'Seleccionar', '1'=>'Compromiso de Pago', '2'=>'Recado','3'=>'No corresponde','4'=>' Buzón','5'=>'No contesta','6'=>'No Pago','7'=>'Visita de Oficina' );
       $this->data['tipo_llamadas']     = $tipo_llamadas;
		/**/
		/*$c1[] = $this->cuentas_m->get_columns();
		$c2[] = $this->usuarios_m->get_columns();
		$c3[] = $this->mandantes_m->get_columns();
		
		$c = array_merge($c1, $c2, $c3);
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = str_replace('2_', '', $dato);
				$cols[]est = str_replace('2_', '', $dato);
			}
		}*/
	    $where = array();
		$like = array();
		$config = array('suffix'=>'');
		$fecha_asignacion = '';
		$fecha_ultimo_pago = '';
		$dia_convenio = '';
		$fecha_ultima_llamada = '';
		
		if (isset($_REQUEST['fecha_asignacion_year']) && $_REQUEST['fecha_asignacion_year']>0){ 
    		$fecha_asignacion.= $_REQUEST['fecha_asignacion_year'];	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].= 'fecha_asignacion_year='.$_REQUEST['fecha_asignacion_year'];
    	}



        if (isset($_REQUEST['fecha_asignacion_month']) && $_REQUEST['fecha_asignacion_month']>0){
    		$fecha_asignacion.= '-'.$_REQUEST['fecha_asignacion_month'].'-';	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].='fecha_asignacion_month='.$_REQUEST['fecha_asignacion_month'];
    	}
    	if ($fecha_asignacion!=''){
    		$like["pag.fecha_asignacion"] = $fecha_asignacion;
    	}


    	$y = false; $m = false;
		if (isset($_REQUEST['fecha_ultimo_pago_year']) && $_REQUEST['fecha_ultimo_pago_year']>0){ 
    		$fecha_ultimo_pago.= $_REQUEST['fecha_ultimo_pago_year'];
    		$y = true;	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].= 'fecha_ultimo_pago_year='.$_REQUEST['fecha_ultimo_pago_year'];
    	}
		if (isset($_REQUEST['fecha_ultimo_pago_month']) && $_REQUEST['fecha_ultimo_pago_month']>0){ 
			$m = true;
    		$fecha_ultimo_pago.= '-'.$_REQUEST['fecha_ultimo_pago_month'].'-';	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].='fecha_ultimo_pago_month='.$_REQUEST['fecha_asignacion_month'];
    	}
		if (isset($_REQUEST['fecha_ultimo_pago_day']) && $_REQUEST['fecha_ultimo_pago_day']>0){ 
			if (!$y && !$m){
				$fecha_ultimo_pago.='%-%-';
			} elseif($y && !$m){
				$fecha_ultimo_pago.='-%-';
			} elseif(!$y && $m){
				$fecha_ultimo_pago.='-';
			} 
			if ($_REQUEST['fecha_ultimo_pago_day']<10){
				$day = '0'.$_REQUEST['fecha_ultimo_pago_day'];
			} else {
				$day = $_REQUEST['fecha_ultimo_pago_day'];
			}
			$fecha_ultimo_pago.= $day;	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].= 'fecha_ultimo_pago_day='.$_REQUEST['fecha_ultimo_pago_day'];
    	}
    	if ($fecha_ultimo_pago!=''){
    		$like["c.fecha_ultimo_pago"] = $fecha_ultimo_pago;
    	}
    	
		$y = false; $m = false;
		if (isset($_REQUEST['fecha_ultima_llamada_year']) && $_REQUEST['fecha_ultima_llamada_year']>0){ 
    		$fecha_ultima_llamada.= $_REQUEST['fecha_ultima_llamada_year'];
    		$y = true;	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].= 'fecha_ultima_llamada_year='.$_REQUEST['fecha_ultima_llamada_year'];
    	}
		if (isset($_REQUEST['fecha_ultima_llamada_month']) && $_REQUEST['fecha_ultima_llamada_month']>0){ 
			$m = true;
    		$fecha_ultima_llamada.= '-'.$_REQUEST['fecha_ultima_llamada_month'].'-';	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].='fecha_ultima_llamada_month='.$_REQUEST['fecha_ultima_llamada_month'];
    	}
		if (isset($_REQUEST['fecha_ultima_llamada_day']) && $_REQUEST['fecha_ultima_llamada_day']>0){ 
			if (!$y && !$m){
				$fecha_ultima_llamada.='%-%-';
			} elseif($y && !$m){
				$fecha_ultima_llamada.='-%-';
			} elseif(!$y && $m){
				$fecha_ultima_llamada.='-';
			} 
			if ($_REQUEST['fecha_ultima_llamada_day']<10){
				$day = '0'.$_REQUEST['fecha_ultima_llamada_day'];
			} else {
				$day = $_REQUEST['fecha_ultima_llamada_day'];
			}
			$fecha_ultima_llamada.= $day;	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].= 'fecha_ultima_llamada_day='.$_REQUEST['fecha_ultima_llamada_day'];
    	}
    	if ($fecha_ultima_llamada!=''){
    		$like["llam.fecha"] = $fecha_ultima_llamada;
    	}

        if (isset($_REQUEST['rut'])){if ($_REQUEST['rut']!='' && $_REQUEST['rut']!='0'){
            $like["u.rut"] = $_REQUEST['rut'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'rut='.$_REQUEST['rut'];
        }}


		if (isset($_REQUEST['dia_convenio']) && $_REQUEST['dia_convenio']>0){ 
			$dia_convenio= $_REQUEST['dia_convenio'];	
    		if ($config['suffix']!=''){ $config['suffix'].='&';}
    		$config['suffix'].= 'dia_convenio='.$_REQUEST['dia_convenio'];
    	}
		if ($dia_convenio!=''){
    		$like["c.dia_vencimiento_cuota"] = $dia_convenio;
    	}
	    //echo $_REQUEST['id_mandante'].'fuera';
	      if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']!='' && $_REQUEST['id_mandante']!='0'){
	    	  //echo 'entroooooo'.$_REQUEST['id_mandante'];  		
	          	 $where["m.id"] = $_REQUEST['id_mandante'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
	    		}}

        if (isset($_REQUEST['tipo_llamada'])){if ($_REQUEST['tipo_llamada']!='' && $_REQUEST['tipo_llamada']!='0'){
            $where["llam.tipo_llamada"] = $_REQUEST['tipo_llamada'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'tipo_llamada='.$_REQUEST['tipo_llamada'];
        }}



        if (isset($_REQUEST['estado'])){if ($_REQUEST['estado']!='' && $_REQUEST['estado']!='0'){
            //echo 'entroooooo'.$_REQUEST['id_mandante'];
            $where["llam.estado"] = $_REQUEST['estado'];
            if ($config['suffix']!=''){ $config['suffix'].='&';}
            $config['suffix'].= 'estado='.$_REQUEST['estado'];
        }}



    	if($config['suffix']!= ''){
    	 $config['suffix'] = '?'.$config['suffix'];	
    	}
		
		$cols[] = 'm.codigo_mandante AS codigo_mandante';
		$cols[] = 'u.nombres AS usuarios_nombres';
		$cols[] = 'u.ap_pat AS usuarios_ap_pat';
		$cols[] = 'u.ap_mat AS usuarios_ap_mat';
		$cols[] = 'u.rut AS usuarios_rut';
		$cols[] = 'c.dia_vencimiento_cuota AS cuentas_dia_vencimiento_cuota';
		$cols[] = 'llam.id_cuenta AS id_cuenta';
        $cols[] = 'llam.id AS llamadas_cuenta_id';
        $cols[] = 'llam.tipo_llamada AS tipo_llamada';
        // $cols[] = 'llam.id AS id';
        $cols[] = 'llam.estado AS estado';
		$cols[] = 'm.id AS idmandante';
		$cols[] = "DATEDIFF( NOW() , cp.fecha_pago ) AS dias_diferencia";
		$cols[] = "cp.fecha_pago AS fecha_pago";
		$cols[] = "m.codigo_mandante AS codigo_mandante";
		$cols[] = "COUNT(llam.id) AS llamadas";
		$cols[] = "(SELECT id FROM 2_cuentas_llamadas llamhoy WHERE llamhoy.id_cuenta=c.id AND llamhoy.fecha='".date('Y-m-d')."' LIMIT 0,1) AS llamadas_hoy";
		$cols[] = "llam.fecha AS fecha_ultima_llamada";

		/**/

		/*$having = "COUNT(llam.id)=0 OR "; //AND llamhoy.fecha='".date('Y-m-d')."' 
		$having.="(SELECT id FROM 2_cuentas_llamadas llamhoy WHERE llamhoy.id_cuenta=c.id AND llamhoy.fecha='".date('Y-m-d')."' OR ((llamhoy.repetir_llamada IS NULL AND llamhoy.fecha='".date('Y-m-d')."') OR (llamhoy.repetir_llamada=1 AND llamhoy.fecha_repetir_llamada!='".date('Y-m-d')."')) LIMIT 0,1) IS NULL OR";		
		$having.="(SELECT COUNT(id) FROM 2_cuentas_llamadas llamrep WHERE llamrep.id_cuenta=c.id AND llamrep.repetir_llamada=1 AND llamrep.fecha_repetir_llamada='".date('Y-m-d')."')=1";			 
		*/
		
		/**/
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join("2_cuentas_pagos cp", "cp.id_cuenta = c.id AND cp.activo='S' AND cp.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=c.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left");
		$this->db->join('2_cuentas_llamadas llam', 'llam.id_cuenta = c.id',"left");	
		$this->db->join('pagare pag', 'pag.idcuenta = c.id',"left");						 
		//$this->db->having($having);
		$this->db->where('c.activo','S');
		$this->db->where('u.activo','S');
		$this->db->where('u.rut !=','-');
		$this->db->where('c.id_estado_cuenta','1');
		if (count($like)>0){
			$this->db->like($like);
		}
        //$this->db->limit(50);
		$this->db->group_by('llam.id');
		$query = $this->db->get('0_cuentas c');
		
		
		
		/**/
		$config['per_page'] = '20';
		$config['uri_segment'] = '4';
		$this->data['total'] = $config['total_rows'] = count($query->result());
	    $config['base_url'] = site_url().'/admin/llamadas/index/';
	    $this->data['current_pag'] = $this->uri->segment(4);

		/**/
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		//$this->db->join('2_cuentas_pagos cp', 'cp.id_cuenta = c.id',"left");
		$this->db->join("2_cuentas_pagos cp", "cp.id_cuenta = c.id AND cp.activo='S' AND cp.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=c.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left");
		$this->db->join('2_cuentas_llamadas llam', 'llam.id_cuenta = c.id',"left");		
		$this->db->join('pagare pag', 'pag.idcuenta = c.id',"left");
		//$this->db->having($having);
		$this->db->where('c.activo','S');
		$this->db->where('u.activo','S');
		$this->db->where('u.rut !=','-');
		$this->db->where('c.id_estado_cuenta','1');
		if (count($like)>0){
			$this->db->like($like);
		}
	    if (count($where)>0){
			$this->db->where($where);
		}
		$this->db->group_by('llam.id');
		$this->db->order_by('cuentas_dia_vencimiento_cuota ASC,usuarios_ap_pat ASC,usuarios_ap_mat ASC');
		$query = $this->db->get('0_cuentas c',$config['per_page'],$this->data['current_pag']);
		/**/
		$lists = $query->result();
        $this->data['lists'] = $lists;
        
       // print_r($lists).'dkjfdksjfslkdjfsldfjl';
        
        
        $this->load->library('pagination');
	    $this->pagination->initialize($config);
		$this->data['plantilla'] = 'llamadas/list'; 
		$this->load->view ( 'backend/index', $this->data );
		
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