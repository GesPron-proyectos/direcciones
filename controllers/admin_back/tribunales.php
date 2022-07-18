<?php
class Tribunales extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
		
	public function Tribunales() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'jurisdiccion_m' );
		$this->load->model ( 'nodo_m' );
		
		$this->load->library ('form_validation');
		$this->data['current'] = 'tribunales';
		$this->data['plantilla'] = 'tribunales/';
		$this->data['lists'] = array();
		
		
	        $a=$this->comunas_m->get_all();
			$comunas[0]='Seleccionar';
			foreach ($a as $obj) {
				$comunas[$obj->id] = $obj->nombre;
			}	
			
			$this->db->order_by ('tribunal DESC');
	        $a=$this->tribunales_m->get_many_by(array( 'padre'=>'0'));
	   		$tribunales[0]='Seleccionar...';
			foreach ($a as $obj) {
				$tribunales[$obj->id] = $obj->tribunal;
			}
			
			
	        $this->db->order_by ('jurisdiccion DESC');
	        $a=$this->jurisdiccion_m->get_many_by(array('activo'=>'S'));
	   		$jurisdicciones[0]='Seleccionar...';
			foreach ($a as $obj) {
				$jurisdicciones[$obj->id] = $obj->jurisdiccion;
			}
			
			
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );	
		$this->data['jurisdicciones'] = $jurisdicciones;		
	    $this->data['tribunales'] = $tribunales;	
		$this->data['comunas'] = $comunas;
	 
	}
	
	 public function index($action='',$id='') {
		
		//$this->output->enable_profiler(TRUE);
		$config['suffix'] = '';
		
		$config['per_page'] = '50';
		$config['uri_segment'] = '4';
		$like = array();
	   // $config['base_url'] = site_url().'/admin/tribunales/index/';
	    $this->data['current_pag'] = $this->uri->segment(4);
	    
	  if (isset($_REQUEST['tribunal']) && $_REQUEST['tribunal']!=''){
	    			$like['t.tribunal'] = $_REQUEST['tribunal'];  
	   if ($config['suffix']!=''){ $config['suffix'].='&';}
	   $config['suffix'].= 'tribunal='.$_REQUEST['tribunal'];
	    	}
	    
	    
	    $query=$this->tribunales_m->get_tribunales($like);
		$lists = $query->result();

		//print_r($lists);
       // break; 		
		$this->data['total'] = count($lists);
		$this->data['lists'] = $lists;
		
		
		
		
        //$this->load->library('pagination');
	   // $this->pagination->initialize($config);
	    
	     $this->data['current'] = 'tribunales';
		$this->data['plantilla'] = 'tribunales/list'; 
		$this->load->view ( 'backend/index', $this->data );


	}
	
	
    public function form($action='',$id='') {
		
		
		
		
		$view='tribunales';
		$this->data['plantilla'].= $view;
		
		
		if ($action == 'guardar') {
			$this->form_validation->set_rules ('tribunal','Tribunal','required|trim');
			$this->form_validation->set_rules ('id_jurisdiccion','Juzdireccion','trim');
			$this->form_validation->set_rules ('posicion','Posicion','trim');
		    if ($this->form_validation->run() == TRUE){
				$fields_save['tribunal'] = $this->input->post('tribunal');
				$fields_save['padre'] = $this->input->post('padre');
				$fields_save['id_jurisdiccion'] = $this->input->post('id_jurisdiccion');
				$fields_save['posicion'] = $this->input->post('posicion');
		   	    $this->tribunales_m->save_default($fields_save,$id);
		   	    
		   	    if($id == ''){
					$id = $this->db->insert_id();
				} 
				redirect('admin/tribunales/form/editar/'.$id);		   	    
		    } 
			else
			{
				if (empty($id)){$id=$this->db->insert_id();} redirect('admin/tribunales/form/editar/'.$id);
			};		   	    
		}
			
	    if (!empty($id)){
			$this->data['lists'] = $this->tribunales_m->get_by(array('id'=>$id));
	   }
		
        //$this->data['id'] = $id;
        $this->data['id'] = $id;
		$this->data['plantilla'] = 'tribunales/form'; 
		$this->load->view ( 'backend/index', $this->data );
	}
	
	public function delete($param='',$id) {
		if($param=='actualizar' && $id != ''){
			$this->tribunales_m->save( $id, array( 'activo'=> 'N')  );
		}
	}	
	public function anidado($idpadre=0,$selected=0){
		//$this->output->enable_profiler(TRUE);
		$this->db->order_by('posicion','ASC');
		$tr = $this->tribunales_m->get_many_by(array('activo'=>'S','padre'=>$idpadre));
		$tribunales = array(''=>'Seleccionar');
		foreach ($tr as $key=>$val){
			$tribunales[$val->id] = $val->tribunal;
		}
		echo form_dropdown('id_tribunal',$tribunales,$selected);
	}
	public function anidadoE($idpadre=0,$selected=0){
		//$this->output->enable_profiler(TRUE);
		$this->db->order_by('posicion','ASC');
		$tr = $this->tribunales_m->get_many_by(array('activo'=>'S','padre'=>$idpadre));
		$tribunales = array(''=>'Seleccionar');
		foreach ($tr as $key=>$val) 
		{
			$tribunales[$val->id] = $val->tribunal;
		}
		echo form_dropdown('id_tribunalExow',$tribunales,$selected);
	}
}
?>