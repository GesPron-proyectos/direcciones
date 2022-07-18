<?php
class Administradores extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;	
	public function Administradores() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		
		$this->load->library ( 'form_validation' );
		
		$this->load->model ( 'administradores_m' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'perfiles' );
		
		/*seters*/
		$this->data['current'] = 'administradores';
		$this->data['plantilla'] = 'administradores/';
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
			$this->administradores_m->setup_validate();
			
			
			
		       	$estado = '';
				$estado= 'N';
			    	if(isset($_POST['check'])){ 
				    $estado = 'S';		
				    }else{
				    $estado= 'N';	
				    }
			
			
			$fields_save = array(
				'perfil' => $_POST['perfil'],
				'username' => $_POST['username'],
				'password' => $_POST['password'],
				'nombres' => $_POST['nombres'],
				'apellidos' => $_POST['apellidos'],
				'rut_procurador' => $_POST['rut_procurador'],
				'cargo' => $_POST['cargo'],
				'correo' => $_POST['correo'],
                'public' => $estado
			    );
			
			if (!$this->administradores_m->save_default($fields_save,$id)){}
			else{if (empty($id)){$id=$this->db->insert_id();} redirect('admin/administradores/form/editar/'.$id);};
		}
		/* funcion combo */
			$a=$this->perfiles->get_all();
			$this->data['perfiles'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['perfiles'][$obj->id] = $obj->perfil;}
		/* end funcion combo */
		if (!empty($id)){$this->data['lists']=$this->administradores_m->get_by(array('id'=>$id));}
		$this->load->view ( 'backend/index', $this->data );
	}

	function gen($action,$id){$this->index($action,$id);}
	
	function index($action='',$id='') {
		
		$view='list';
		$this->data['plantilla'].= $view;		

		if ($action=='actualizar'){
			$this->administradores_m->update($id,$_POST);
			$this->show_tpl = FALSE;
		}
		if ($action=='up' or $action=='down'){
			$this->administradores_m->move_up_down($_POST['posicion'],$id,$action,$_POST['field_categoria']);
			$this->show_tpl = FALSE; 
		}
		
		if ($view=='list'){
			
			/*listado*/
			$query =$this->db->select('adm.id AS id, adm.username AS username, perf.perfil AS perfil, perf.id AS id_perfil, adm.public AS publico, adm.posicion AS posicion, adm.perfil AS field_categoria')
							 ->join("s_perfil perf", "adm.perfil = perf.id")
							 ->where("adm.activo","S")
							 ->order_by("perfil", "asc")
							 ->order_by("posicion", "desc")
			 				 ->get("0_administradores adm");
			$this->data['lists']=$query->result();
			/*posiciones*/
			$query = $this->db->select('perfil AS field_categoria, MAX(posicion) AS max_posicion, MIN(posicion) AS min_posicion')->group_by("perfil")->get("0_administradores");
			foreach ($query->result() as $key=>$val){
				$this->data['posiciones'][$val->field_categoria]['max_posicion']=$val->max_posicion;
				$this->data['posiciones'][$val->field_categoria]['min_posicion']=$val->min_posicion;
				$this->data['posiciones'][$val->field_categoria]['field_categoria']=$val->field_categoria;
			}
			if (!$this->show_tpl){ $this->data['plantilla'] = 'administradores/list_tabla'; $this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );}
			
		}
			
		
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}

	}
}

?>