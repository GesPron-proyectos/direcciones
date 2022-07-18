<?php
class etapas_juicio extends CI_Controller {
	public $data = array();
	public $activo = 'S';
	protected $show_tpl = TRUE;
	public function Cuentas() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		
		//$this->output->enable_profiler(TRUE);
		
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		
		$this->load->helper ( 'date_html_helper' );
		
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'cuentas_m' ); $this->load->model ( 'cuentas_etapas_m' );
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
		$this->load->model ( 'diligencia_m' );
		
		/*seters*/
		$this->data['current'] = 'etapas_juicio';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'etapas_juicio/';
		$this->data['lists'] = array();
		
		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
		$this->data['forma_pagos'] = array(''=>'Forma Pago','TF'=>'Transferencia','DP'=>'Depósito','CH'=>'Cheque','EF'=>'Efectivo');
		
		$this->data['nodo'] = $this->nodo = $this->nodo_m->get_by( array('activo'=>'S') );
		
		$diligencias = array();
		$diligencia=$this->diligencia_m->order_by('nombre','ASC')->get_all(); 
		$this->data['diligencias'][-1]='Seleccionar..';
	 	foreach ($diligencia as $obj) {$this->data['diligencias'][$obj->id] = $obj->nombre;}
		
		
	}
	
	//function gen($action,$id){$this->index($action,$id);}

	public function delete($id='') {
		if($id != ''){
			$this->etapas_m->save( $id, array( 'activo'=> 'N')  );
		}
	}
	public function index($action='',$id='') {
		
		//$this->output->enable_profiler(TRUE);
			$config['suffix'] = '';
		$this->db->where( array( 'activo'=>'S' ) );
		$this->db->select('*');
		//$query = $this->db->get_etapas('s_etapas');
		$like = array();
		$config['per_page'] = '50';
		$config['uri_segment'] = '4';
		
	    $config['base_url'] = site_url().'/admin/etapas_juicio/index/';
	    $this->data['current_pag'] = $this->uri->segment(4);
	    
	 if (isset($_REQUEST['etapa']) && $_REQUEST['etapa']!=''){
	    			$like['s1.etapa'] = $_REQUEST['etapa'];  
	   if ($config['suffix']!=''){ $config['suffix'].='&';}
	   $config['suffix'].= 'etapa='.$_REQUEST['etapa'];
	    	}
	    
	  if (isset($_REQUEST['codigo']) && $_REQUEST['codigo']!=''){
	    			$like['s1.codigo'] = $_REQUEST['codigo'];  
	   if ($config['suffix']!=''){ $config['suffix'].='&';}
	   $config['suffix'].= 'codigo='.$_REQUEST['codigo'];
	    	}   
	    	
		//$this->db->where( array( 'activo'=>'S' ) );
		//$this->db->select('*');
		
		
		$query=$this->etapas_m->get_etapas($like);
		$this->data['lists'] = $query;
        $this->data['total'] = $config['total_rows'] = count($query);
		
		$this->load->library('pagination');
	    $this->pagination->initialize($config);

		$this->data['plantilla']	= 'etapas_juicio/list'; 
		$this->load->view ( 'backend/index', $this->data );


	}

	public function form($action='',$id='') {
        //$this->output->enable_profiler(TRUE);
		$datos = array();
		$datos['etapa'] = '';
		$datos['codigo'] = '';
		$datos['tipo'] = '';
		$datos['sucesor'] = '';
		$datos['dias_alerta'] = '';
		$datos['dias_alerta_proceso'] = '';
		$datos['texto_alerta'] = '';
		$datos['clasificacion'] = '';
		$datos['posicion'] = '';
		$datos['etapa_comision'] = '';
		$datos['etapa_procesal'] = '';
		$datos['sub_etapa_procesal'] = '';
		$datos['alias'] = '';
		$datos['id_diligecia'] = '';
		$b = '';
		
		if($action == 'guardar'){
			
			$this->form_validation->set_rules('etapa', 'Nombre Etapa', 'required|trim');
			/*$this->form_validation->set_rules('codigo', 'Codigó', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('tipo', 'Tipo', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('sucesor', 'Sucesor', 'required|min_length');
			$this->form_validation->set_rules('dias_alerta', 'Días Alerta', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('texto_alerta', 'Texto Alerta', 'required|min_length');
			$this->form_validation->set_rules('clasificacion', 'Clasificación', 'min_length');
			$this->form_validation->set_rules('etapa_comision', 'Etapa comision','min_length');
			$this->form_validation->set_rules('etapa_procesal', 'Etapa procesal','min_length');
			$this->form_validation->set_rules('sub_etapa_procesal', 'Sub etapa procesal','min_length');*/
			
			if ($this->form_validation->run() == TRUE){
				foreach($_POST as $key=>$val){
					$save[$key] = $val;
				}
				
			$this->etapas_m->save( $id,$save);
				
				if($id == ''){
					$id = $this->db->insert_id();
					//redirect('admin/'.$this->uri->segment(2).'/form/editar/'.$id);
				}
			}
			
		}
		
		if($action == 'editar'){
			$dat = $this->etapas_m->get_by( array( 'id'=>$id ) );
			foreach($dat as $key=>$val){
				//echo $key.'===='.$val;
				$datos[$key] = $val;
						if($key >0){
				
				
			  			 }
			
			}
		}
 		
		
		$this->data['datos'] = $datos;
		$this->data['id'] = $id;
		$this->data['plantilla'] = 'etapas_juicio/form'; 
		$this->load->view ( 'backend/index', $this->data );
	}

}

?>