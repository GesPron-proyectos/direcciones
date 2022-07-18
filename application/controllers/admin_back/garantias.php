<?php
class Garantias extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
		
	public function Garantias() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		$this->load->model ( 'garantias_m' );
		//$this->load->model ( 'comunas_m' );
		//$this->load->model ( 'jurisdiccion_m' );
		$this->load->model ( 'nodo_m' );
		
		$this->load->library ('form_validation');
		$this->data['current'] = 'garantias';
		$this->data['plantilla'] = 'garantias/';
		$this->data['lists'] = array();
		
	
		$this->db->order_by ('id DESC');
		$a=$this->garantias_m->get_many_by(array('estado'=>'1'));
		$garantias[0]='Seleccionar...';
		foreach ($a as $obj) {
			$garantias[$obj->id] = $obj->nombre;
		}
		//print_r($garantias);
			
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );	
		$this->data['garantias'] = $garantias;	
		
	 
	}
	/*Anlizar bien esto*/
	
	 public function index($action='',$id='') {
	 
		
		$this->output->enable_profiler(TRUE);
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

		print_r($lists);
       // break; 		
		$this->data['total'] = count($lists);
		$this->data['lists'] = $lists;
		
		$this->data['current'] = 'tribunales';
		$this->data['plantilla'] = 'tribunales/list'; 
		$this->load->view ( 'backend/index', $this->data );


	}
	
	
    public function form($action='',$id='') {
		
		$view='garantias';
		$this->data['plantilla'].= $view;
			
	    if (!empty($id)){
			$this->data['lists'] = $this->garantias_m->get_by(array('id'=>$id));
	   }
		
        //$this->data['id'] = $id;
        $this->data['id'] = $id;
		//$this->data['plantilla'] = 'tribunales/form'; 
		$this->load->view ( 'backend/index', $this->data );
	}
	
	/*
	public function delete($param='',$id) {
		if($param=='actualizar' && $id != ''){
			$this->tribunales_m->save( $id, array( 'activo'=> 'N')  );
		}
	}*/	
	public function anidado($idpadre=0,$selected=0){
		//$this->output->enable_profiler(TRUE);
		$this->db->order_by('id','ASC');
		$tr = $this->garantias_m->get_many_by(array('estado'=>'1','padre'=>$idpadre));
		$garantias = array(''=>'Seleccionar');
		//print_r($garantias);
		foreach ($tr as $key=>$val){
			$garantias[$val->id] = $val->nombre;
		}		
		echo form_dropdown('tipo',$garantias,$selected);
	}
	
}
?>