<?php
class Jurisdiccion extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
		
	public function Jurisdiccion() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		$this->load->model ( 'jurisdiccion_m' );
		$this->load->model ( 'nodo_m' );

		$this->load->library ('form_validation');
		$this->data['current'] = 'jurisdiccion';
		$this->data['plantilla'] = 'jurisdiccion/';
		$this->data['lists'] = array();
		
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') ); 	
	}
	
	 public function index($action='',$id='') {
		
		//$this->output->enable_profiler(TRUE);
		$config['suffix'] = '';
		
		$config['per_page'] = '50';
		$config['uri_segment'] = '4';
		$like = array();
	   // $config['base_url'] = site_url().'/admin/tribunales/index/';
	    $this->data['current_pag'] = $this->uri->segment(4);
	    
	  if (isset($_REQUEST['jurisdiccion']) && $_REQUEST['jurisdiccion']!=''){
	    			$like['juz.jurisdiccion'] = $_REQUEST['jurisdiccion'];  
	   if ($config['suffix']!=''){ $config['suffix'].='&';}
	   $config['suffix'].= 'jurisdiccion='.$_REQUEST['jurisdiccion'];
	    	}
	    
	    
	    $query=$this->jurisdiccion_m->get_jurisdicciones($like);
		$lists = $query->result();

		//print_r($lists);
       // break; 		
		$this->data['total'] = count($lists);
		$this->data['lists'] = $lists;
		
		
		
		
        //$this->load->library('pagination');
	   // $this->pagination->initialize($config);
	    
	     $this->data['current'] = 'jurisdiccion';
		$this->data['plantilla'] = 'jurisdiccion/list'; 
		$this->load->view ( 'backend/index', $this->data );


	}
	
	
    public function form($action='',$id='') {
		
		//$this->output->enable_profiler(TRUE);
		
		$view='jurisdiccion';
		$this->data['plantilla'].= $view;
		
		
			if ($action == 'guardar') {
		$this->form_validation->set_rules ('jurisdiccion','Jurisdiccion','required|trim');
		 if ($this->form_validation->run() == TRUE){
				$fields_save['jurisdiccion'] = $this->input->post('jurisdiccion');
				$this->jurisdiccion_m->save_default($fields_save,$id);
		   	    if($id == ''){
					$id = $this->db->insert_id();
				} 
				redirect('admin/jurisdiccion/form/editar/'.$id);
		   	    
		   } else{if (empty($id)){$id=$this->db->insert_id();} redirect('admin/jurisdiccion/form/editar/'.$id);};
		   	    
		}
			
    if (!empty($id)){
    	    $this->db->where(array('activo' => 'S'));
			$this->data['lists'] = $this->jurisdiccion_m->get_by(array('id'=>$id));
	   }
		
        //$this->data['id'] = $id;
		$this->data['plantilla'] = 'jurisdiccion/form'; 
		$this->load->view ( 'backend/index', $this->data );
	}
	
	public function delete($action,$id) {
		      if($action == 'actualizar'){
		if($id != ''){
			$this->jurisdiccion_m->save_default(array('activo'=> 'N'),$id,TRUE,TRUE);
		  }
	   }	
	}
	
}
?>