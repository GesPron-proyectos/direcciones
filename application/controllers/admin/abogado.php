<?php
class Abogado extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;	
	public function Abogado() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login'); }
		
		$this->load->library('form_validation');
		$this->load->model('abogados_m');
		$this->load->model('correo_m');
		$this->load->model('nodo_m');
	
		
		/*seters*/
		$this->data['current'] = 'abogado';
		$this->data['plantilla'] = 'abogado/';
		$this->data['lists'] = array();
		$this->data['current_pag'] = '';
		$this->data['nodo'] = $this->nodo_m->get_by(array('activo'=>'S'));
	}
	
	function form($action='', $id=''){
		//$this->output->enable_profiler(TRUE);
		$view='form';
		$this->data['plantilla'].= $view;
		
		/*guardar*/		
		if ($action=='guardar'){
			$fields_save = array(
				'rut' => str_replace('.', '', $_POST['rut']),
				'sistema' => $_POST['sistema'],
				'nombres' => strtoupper($_POST['nombre']),
				'ape_pat' => strtoupper($_POST['apellidop']),
				'ape_mat' => strtoupper($_POST['apellidom']),
				'usuario' => $_POST['usuario'],
				'password' => $_POST['password']
			);
			
			//$this->abogados_m->save_default($fields_save, $id);
			if($id)
				$this->abogados_m->update($id, $fields_save, false, true);
			else
				 $this->abogados_m->insert($fields_save, false, true);
			 
			redirect('admin/abogado/index/'); 
		}
	
		$this->data['lists'] = $this->abogados_m->get_by(array('id'=>$id));
			
		$this->load->view('backend/index', $this->data);
	}

	function gen($action, $id){$this->index($action, $id);}
	
	function index($action='', $id=''){
		$view='list';
		$this->data['plantilla'] .= $view;		

		if ($action=='actualizar'){
			//$this->abogados_m->update($id, $_POST);
			$this->abogados_m->delete_by(array('id'=>$id));
			$this->show_tpl = FALSE;
		}
		if ($action=='up' or $action=='down'){
			$this->abogados_m->move_up_down($_POST['posicion'], $id, $action, $_POST['field_categoria']);
			$this->show_tpl = FALSE; 
		}
		
		if ($view=='list'){
			
			/*listado*/
			$query =$this->db->select('
								p.id AS id,
								p.rut AS rut,	
								p.nombres AS nombre,
								p.ape_pat AS apellidop, 
								p.ape_mat AS apellidom, 
								p.sistema,
								p.usuario,
								p.password,
								')	 
						->where("p.activo","S")
						->get("abogado p");
			$this->data['lists'] = $query->result();
			/*posiciones*/
			//print_r($this->data['lists']); die;
			if (!$this->show_tpl){ $this->data['plantilla'] = 'abogado/list_tabla'; $this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );}
			
		}
		
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}
	}

	function guardar_correo($id){
		
		$fields_save = array();
		$fields_save['id_abogado'] = $id;
		$fields_save['correo'] = $_POST['id'];

		$this->correo_m->insert($fields_save, false, true);
	
		redirect('admin/abogado/form/editar/'.$id);
	}

	function eliminar_correo($id_user, $id){

		$this->correo_m->delete_by(array('id'=>$id));
	
		redirect('admin/abogado/form/editar/'.$id_user);
	}

}

?>