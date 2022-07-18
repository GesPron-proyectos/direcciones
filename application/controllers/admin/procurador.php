<?php
class Procurador extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;	
	public function Procurador() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		
		$this->load->library ( 'form_validation' );
		
		$this->load->model ( 'procurador_m' );
		$this->load->model ( 'correo_m' );
		$this->load->model ( 'nodo_m' );
	
		
		/*seters*/
		$this->data['current'] = 'procurador';
		$this->data['plantilla'] = 'procurador/';
		$this->data['lists'] = array();
		$this->data['current_pag'] = '';
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );
		
	}
	
	function form($action='',$id=''){
		//$this->output->enable_profiler(TRUE);
		$view='form';
		$this->data['plantilla'].= $view;
		
		/*guardar*/		
		if ($action=='guardar'){
			//$this->procurador_m->setup_validate();
			
			$fields_save = array(
				'nombre' => $_POST['nombre'],
				'apellido' => $_POST['apellido'],
				'rut' => $_POST['rut'],
				'correo' => $_POST['correo'],
				'activo' => 'S'
			    );
			
			$this->procurador_m->save_default($fields_save,$id);
			redirect('admin/procurador/index/'); 
		}
	
		$this->data['lists'] = $this->procurador_m->get_by(array('id'=>$id));
		############ SELECT CORREO #############
		$a=$this->procurador_m->get_all();
		$this->data['correos'][0]='Seleccionar';
		foreach ($a as $obj) {
			if(empty($id) || (!empty($id) && $obj->id != $id))
				$this->data['correos'][$obj->correo] = $obj->correo;
		}

		$query =$this->db->select('c.id AS id, c.correo')
						 ->where("id_procurador", $id)
						 ->get("correo_cc c");
		$this->data['data_correos'] = $query->result();
			
		$this->load->view ( 'backend/index', $this->data );
	}

	function gen($action,$id){$this->index($action,$id);}
	
	function index($action='',$id='') {
		
		$view='list';
		$this->data['plantilla'].= $view;	

		$config['uri_segment'] = '4';
		$this->data['current_pag'] = $this->uri->segment(4);		

		if ($action=='actualizar'){
			$this->procurador_m->update($id, $_POST);
			$this->show_tpl = FALSE;
			$config['uri_segment'] = '6'; 
			$this->data['current_pag'] = $this->uri->segment(6);
		}
		
		if ($view=='list'){
			
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/procurador/index/';
			
			$query =$this->db->select('
								p.id AS id,
								p.rut AS rut,	
								p.nombre AS nombre,
								p.apellido AS apellido, 
								p.correo AS correo
								')	 
						->where("p.activo","S")
						->get("procurador p");
			
			$total_rows = $query->result();
			
			//$total_rows = $query_total->result();
			$config['total_rows'] = count($total_rows);
	    	$config['per_page'] = '30';
	    	
	    	$this->pagination->initialize($config);
			
			
			$query =$this->db->select('
								p.id AS id,
								p.rut AS rut,	
								p.nombre AS nombre,
								p.apellido AS apellido, 
								p.correo AS correo
								')	 
						->where("p.activo","S")
						->get("procurador p", $config['per_page'], $this->data['current_pag']);
			
			$this->data['lists'] = $query->result();
			$this->data['total'] = $config['total_rows'];
			
			//print_r($this->data['lists']); die;
			if (!$this->show_tpl){ 
				$this->data['plantilla'] = 'procurador/list_tabla';
				$this->load->view('backend/templates/'.$this->data['plantilla'], $this->data);
			}
		}
		
		if ($this->show_tpl){
			$this->load->view('backend/index', $this->data);
		}
	}

	function guardar_correo($id){
		
		$fields_save = array();
		$fields_save['id_procurador'] = $id;
		$fields_save['correo'] = $_POST['id'];

		$this->correo_m->insert($fields_save, false, true);
	
		redirect('admin/procurador/form/editar/'.$id);
	}

	function eliminar_correo($id_user, $id){

		$this->correo_m->delete_by(array('id'=>$id));
	
		redirect('admin/procurador/form/editar/'.$id_user);
	}

}

?>