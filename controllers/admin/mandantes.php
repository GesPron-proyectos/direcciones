<?php
class Mandantes extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
		
	public function Mandantes() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'nodo_m' );
		
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );
		
		//$this->output->enable_profiler(TRUE);
		/*seters*/
		$this->data['current'] = 'mandantes';
		$this->data['plantilla'] = 'mandantes/';
		$this->data['lists'] = array();
		$this->data['current_pag'] = '';
		
	}
	function form($action='',$id=''){
		//$this->output->enable_profiler(TRUE);
		
		$view='form';
		$this->data['plantilla'].= $view;
		
		//print_r($action);
		/*guardar*/		
		if ($action=='guardar'){
			$this->mandantes_m->setup_validate();
			
				$n_pagare_automatico= '';
			    if(isset($_POST['n_pagare_automatico'])){ 
				$n_pagare_automatico = 1;		
				}else{
				$n_pagare_automatico= 0;	
				}
			
			$fields_save['rut'] = $this->input->post('rut');
			$fields_save['razon_social'] = $this->input->post('razon_social');
			$fields_save['razon_social_completa'] = $this->input->post('razon_social_completa');
			$fields_save['codigo_mandante'] = $this->input->post('codigo_mandante');
			$fields_save['representante_nombre'] = $this->input->post('representante_nombre');
			$fields_save['representante_apepat'] = $this->input->post('representante_apepat');
			$fields_save['representante_direccion'] = $this->input->post('representante_direccion');
			$fields_save['representante_ciudad'] = $this->input->post('representante_ciudad');
			$fields_save['nombre_fantasia'] = $this->input->post('nombre_fantasia');
			$fields_save['fecha_escritura_publica'] = $this->input->post('fecha_escritura_publica');
			$fields_save['numero_repetorio'] = $this->input->post('numero_repetorio');
			$fields_save['fecha_escritura_apoderado'] = $this->input->post('fecha_escritura_apoderado');
			
			$fields_save['notaria'] = $this->input->post('notaria_apoderado');
			$fields_save['n_pagare_automatico'] = $n_pagare_automatico;

			if (!$this->mandantes_m->save_default($fields_save,$id)){}
			else{if (empty($id)){$id=$this->db->insert_id();} redirect('admin/mandantes/form/editar/'.$id);};
		}
	
		if (!empty($id))
		{
			$this->data['lists']=$this->mandantes_m->get_by(array('id'=>$id));
		}
		
		//print_r($this->data['lists']);
		
		$this->load->view ( 'backend/index', $this->data );
	}
	function gen($action,$id){$this->index($action,$id);}
	function index($action='',$id='') {
		$view='list';
		$this->data['plantilla'].= $view;
		//print_r($_POST);
		//echo $id;
//die()		;
		if ($action=='actualizar'){
			$this->mandantes_m->update($id,$_POST);
			$this->show_tpl = FALSE;
			$view='list';
			$this->load->view ( 'backend/index', $this->data );
		}
		if ($action=='up' or $action=='down'){
			$this->mandantes_m->move_up_down($_POST['posicion'],$id,$action,$_POST['field_categoria']);
			$this->show_tpl = FALSE;
		}
		if ($view=='list'){
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/cuentas/index/';
	    	$config['total_rows'] = $this->db->where("activo","S")->count_all_results("0_mandantes");
	    	$config['per_page'] = '30'; //$config['num_links'] = '10';
			/*listado*/
			$query =$this->db->select("id,activo,posicion,clase_html,public,n_pagare_automatico,razon_social,codigo_mandante,id AS field_categoria")
							 ->where("activo","S")
							 ->order_by("posicion", "desc")
			 				 ->get("0_mandantes");
			
			$this->data['lists']=$query->result();
			
			/*posiciones*/ 
			$query = $this->db->select('id AS field_categoria,MAX(posicion) AS max_posicion, MIN(posicion) AS min_posicion')->get("0_mandantes");
			if ($query->num_rows()>0){foreach ($query->result() as $key=>$val){
				$this->data['posiciones'][$val->field_categoria]['max_posicion']=$val->max_posicion;
				$this->data['posiciones'][$val->field_categoria]['min_posicion']=$val->min_posicion;
				$this->data['posiciones'][$val->field_categoria]['field_categoria']=$val->field_categoria;
			}}
			$this->data['total']=$config['total_rows'];
			if (!$this->show_tpl)
			{ 
				$this->data['plantilla'] = 'mandantes/list_tabla'; 
				$this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );
			}
		}

		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}

	}
}

class Historial {
	
	function __construct() {
	
	}
}
?>