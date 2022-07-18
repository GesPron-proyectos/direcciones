<?php
class lista extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
	public function Usuarios() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'session' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'receptor_m' );
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'nodo_m' );
		//$this->output->enable_profiler(TRUE);
		/*seters*/
		$this->data['current'] = 'lista';
		$this->data['plantilla'] = 'lista/';
		$this->data['lists'] = array();
		
		/*$trib=$this->tribunales_m->order_by("tribunal","ASC")->get_many_by(array('activo'=>'S','tribunal !='=>'','padre !='=>'0'));
	    $this->data['tribunales']['']='Seleccionar..';
	 	foreach ($trib as $obj) {$this->data['tribunales'][$obj->id] = $obj->tribunal;} */
	 	
	    $trib=$this->tribunales_m->order_by("tribunal","ASC")->get_many_by(array('activo'=>'S','tribunal !='=>'','padre'=>'0'));
	    $this->data['tribunales_padres']['']='Seleccionar..';
	 	foreach ($trib as $obj) {$this->data['tribunales_padres'][$obj->id] = $obj->tribunal;}
	 	
	 	$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );
		
	}
	
	function index($id_cuenta='') {
        
		
		//$this->output->enable_profiler(TRUE);
		$config['suffix'] = '';
		
		//$data['errors'] = Session::get('errors');   
		
		
		$config['per_page'] = '50';
		$config['uri_segment'] = '4';
		$like = array();
	   // $config['base_url'] = site_url().'/admin/tribunales/index/';
	    $this->data['current_pag'] = $this->uri->segment(4);
	    
	  if (isset($_REQUEST['comuna']) && $_REQUEST['comuna']!=''){
	    			$like['c.nombre'] = $_REQUEST['comuna'];  
	   if ($config['suffix']!=''){ $config['suffix'].='&';}
	   $config['suffix'].= 'comuna='.$_REQUEST['comuna'];
	    	}
	    
	    
	    $query=$this->comunas_m->get_comuna_lista($like);
		$lists = $query->result();
        $this->data['total'] = count($lists);
		$this->data['lists'] = $lists;	
		
		
		$this->data['current'] = 'lista';
		$this->data['plantilla'] = 'lista/list'; 
		$this->load->view ( 'backend/index', $this->data );
		
		
	}
	
	public function comunas($id_cuenta='') {
		//$this->output->enable_profiler(TRUE); 
		
		$view='list';  //lista
		$this->data['plantilla'].= $view;	
		$config['uri_segment'] = '4';
		$this->data['id_cuenta'] = $id_cuenta;
		$this->data['current_pag'] = $this->uri->segment(4);	

		$this->data['lists']= $this->comunas_m->get_comuna_list();
		
		//print_r($this->data['lists']);
		
		
		$this->load->view ( 'backend/index', $this->data );
	}
	
	/* public function form222($id='') {
		$this->output->enable_profiler(TRUE); 
		$view='form';
		$this->data['plantilla'].= $view;	
		$config['uri_segment'] = '4';
		// $this->data['id_cuenta'] = $id_cuenta;
		$this->data['current_pag'] = $this->uri->segment(4);	

		    if ($action == 'form') {
		$this->form_validation->set_rules ('nombre','Nombre','required|trim');
		     if ($this->form_validation->run() == TRUE){
				$fields_save['nombre'] = $this->input->post('nombre');
				$id = $this->comunas_m->save_default($fields_save,'');
		   	    if($id == ''){
					$id = $this->db->insert_id();
				} 
				redirect('admin/lista/form/editar/'.$id);
		      }   
		    }
		
		$this->data['lists']= $this->comunas_m->get_by();
		$this->load->view ( 'backend/index', $this->data );
	} */
 public function form($action='',$id='') {
	 
 	//$this->output->enable_profiler(TRUE);
		
		//$view='comunas';
	//	$this->data['plantilla'].= $view;
		
		if ($action == 'guardar') {
				
		$this->form_validation->set_rules ('comuna','Comuna','required|trim');
	       if ($this->form_validation->run() == TRUE){
				$fields_save['nombre'] = $this->input->post('comuna');
				$fields_save['id_tribunal'] = $this->input->post('id_tribunal');
				$fields_save['id_tribunal_padre'] = $this->input->post('id_tribunal_padre');
				$this->comunas_m->save_default($fields_save,$id);
		   	     if($id == ''){
					$id = $this->db->insert_id();
				} 
				//echo 'iddd'.$id;
				redirect('admin/lista/form/editar/'.$id);
		   	    
		   } else{if (empty($id)){$id=$this->db->insert_id();} redirect('admin/lista/form/editar/'.$id);};
		}
	 if (!empty($id)){
			$this->data['lists'] = $this->comunas_m->get_by(array('id'=>$id));
	   }
		
	   	//$this->data['tribunal'] = $this->tribunales_m->get_by(array('id'=>$this->input->post('id_tribunal'))); 
	   	$this->data['id'] = $id;
		$this->data['plantilla'] = 'lista/form'; 
		$this->load->view ( 'backend/index', $this->data );
	}
	
	
	
	
}
?>