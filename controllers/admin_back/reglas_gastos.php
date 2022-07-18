<?php
class reglas_gastos extends CI_Controller {
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
		$this->load->model ( 'gasto_regla_m' );
		$this->load->model ( 'diligencia_m' );
		$this->load->model ( 'nodo_m' );
		/*seters*/
		$this->data['current'] = 'reglas_gastos';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'reglas_gastos/';
		$this->data['lists'] = array();
		
		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
		$this->data['forma_pagos'] = array(''=>'Forma Pago','TF'=>'Transferencia','DP'=>'Depósito','CH'=>'Cheque','EF'=>'Efectivo');
		
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );
		
	}

	public function index($action='',$id='') {
		
		//$this->output->enable_profiler(TRUE);
		
		/**/
		$c1[] = $this->gasto_regla_m->get_columns();
		$c2[] = $this->diligencia_m->get_columns();
		$c3[] = $this->mandantes_m->get_columns();
		/*$c4[] = $this->usuarios_m->get_columns();
		$c5[] = $this->mandantes_m->get_columns();*/
		
		$c = array_merge($c1, $c2, $c3);
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = str_replace('2_', '', $dato);
			}
		}
		
		/**/
		$this->db->select($cols);
		$this->db->join('diligencia d', 'd.id = rg.id_diligencia','left');
		$this->db->join('0_mandantes m', 'm.id = rg.id_mandante','left');
		$query = $this->db->get('gasto_regla rg');
		/**/
		
		$config['per_page'] = '20';
		$config['uri_segment'] = '4';
		$this->data['total'] = $config['total_rows'] = count($query->result());
	    $config['base_url'] = site_url().'/admin/'.$this->uri->segment(2).'/index/';
	    $this->data['current_pag'] = $this->uri->segment(4);

		/**/
		$this->db->select($cols);
		$this->db->join('diligencia d', 'd.id = rg.id_diligencia','left');
		$this->db->join('0_mandantes m', 'm.id = rg.id_mandante','left');
		$query = $this->db->get('gasto_regla rg',$config['per_page'],$this->data['current_pag']);
		
		/**/
		$this->data['lists'] = $query->result();
		
		/*echo '<pre>';
		print_r($this->data['lists']);
		echo '</pre>';*/
		
	    $this->load->library('pagination');
	    $this->pagination->initialize($config);

		$this->data['plantilla']	= $this->uri->segment(2).'/list'; 
		$this->load->view ( 'backend/index', $this->data );


	}

	public function form($action='',$id='') {
		
		$datos = array();
		$datos['rango1'] = '';
		$datos['rango2'] = '';
		$datos['monto_gasto'] = '';
		$datos['id_mandante'] = '';
		$datos['id_diligencia'] = '';
		
		
		$p = $this->mandantes_m->get_all();
		$dat[''] = 'Seleccionar';
		foreach($p as $key=>$val){
			if( trim($val->razon_social) != ''){
				$dat[$val->id] = $val->razon_social;
			}
		}
		$this->data['mandantes'] = $dat;
		
		$dat = array();
		$this->db->order_by('nombre ASC');
		$this->db->group_by('nombre');
		$p = $this->diligencia_m->get_all();
		$dat[''] = 'Seleccionar';
		foreach($p as $key=>$val){
			if( trim($val->nombre) != ''){
				$dat[$val->id] = $val->nombre;
			}
		}
		$this->data['diligencias'] = $dat;
		
		if($action == 'guardar'){
			
			$this->form_validation->set_rules('rango1', 'Nombre Etapa', '');
			/*$this->form_validation->set_rules('codigo', 'Codigó', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('tipo', 'Tipo', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('sucesor', 'Sucesor', 'required|min_length');
			$this->form_validation->set_rules('dias_alerta', 'Días Alerta', 'required|is_natural_no_zero');
			$this->form_validation->set_rules('texto_alerta', 'Texto Alerta', 'required|min_length');
			$this->form_validation->set_rules('clasificacion', 'Clasificación', 'required|min_length');*/
			
			if ($this->form_validation->run() == TRUE){
				foreach($_POST as $key=>$val){
					$save[$key] = $val;
				}
				$this->gasto_regla_m->save( $id,$save  );
				
				if($id == ''){
					$id = $this->db->insert_id();
					redirect('admin/'.$this->uri->segment(2).'/form/editar/'.$id);
				}
			}
			
		}
		
		if($action == 'editar'){
			$dat = $this->gasto_regla_m->get_by( array( 'id'=>$id ) );
			foreach($dat as $key=>$val){
				$datos[$key] = $val;
			}
		}
		
		
		$this->data['datos'] = $datos;
		$this->data['id'] = $id;
		$this->data['plantilla'] = 'reglas_gastos/form'; 
		$this->load->view ( 'backend/index', $this->data );
	}
	
	
	public function delete($id='') {
		if($id != ''){
			//$this->gasto_regla_m->save( $id, array( 'activo'=> 'N')  );
			$this->gasto_regla_m->delete_by( array( 'id'=> $id)  );
		}
	}

}

?>