<?php
include_once "./application/core/EMP_Encrypt.php";
require_once 'application/libraries/PHPExcel.php';

class Procurador extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;	
	public function Procurador() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		
		$this->load->library ( 'form_validation' );
		$this->load->helper ( 'date_html_helper' );
		$this->load->helper ( 'excel_reader2' );
		$this->load->model ( 'procurador_m' );
		$this->load->model ( 'correo_m' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'nodo_m' );
		
		/*seters*/
		$this->data['current'] = 'procurador';
		$this->data['plantilla'] = 'procurador/';
		$this->data['lists'] = array();
		$this->data['current_pag'] = '';
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );

		$this->array_return['cuentas_insert'] = 0;
		$this->array_return['no_insert'] = 0;
		$this->array_return['usuarios_insert'] = 0;
		$this->array_return['usuarios_update'] = 0;
		$this->array_return['elimin'] = 0;
		$this->array_return['cuentas_update'] = 0;
		
	}

	function importar($accion =''){
		if (!$this->session->userdata('usuario_id')){redirect('login');}
		$this->load->model ( 'procurador_m' );
		$this->data['plantilla'] = 'importar/';
		$view = 'cargar';
		$this->data['plantilla'].=$view;
		$this->data['archivos'] = array();
		$this->data['error'] = array();
		$this->data['archivo'] = '';
		$this->data['rut'] = FALSE;

		if ($accion == 'guardar_archivo'){
			$nombre_archivo = date('YmdHis');		
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = '*';
			$config['max_size']	= '5120';
			/*$config['max_width']  = '2048';
			$config['max_height']  = '1080';*/
			$this->load->library('upload', $config);
			
			if (! $this->upload->do_upload ("archivo_1")) {
				$this->data['error'] = array ('error' => $this->upload->display_errors() );
			} else {
				$this->data['archivos'] = array($this->upload->data());  
				rename ( $this->data['archivos'][0]['full_path'], './uploads/importar.xls' );
				if (is_file($this->data['archivos'][0]['full_path'])){
					//unlink ( $this->data['archivos'][0]['full_path'] );
				}
			}
			$this->data['id_procurador'] = $this->input->post('pjud');
			$this->data['bandera'] = $this->input->post('pjud');
		}
	}

	
	######################################################
	############### importar direcciones #################
	######################################################
	
	public function direcciones($id='') {
		//$this->output->enable_profiler(TRUE); 
		
		$view='list';  //lista
		$this->data['plantilla'].= $view;	
		$config['uri_segment'] = '4';
		$this->data['id_cuenta'] = $id;
		$this->data['current_pag'] = $this->uri->segment(4);	

		$this->data['lists']= $this->procurador_m->get_comuna_list();
		
		//print_r($this->data['lists']);
		
		
		$this->load->view ( 'backend/index', $this->data );
		$this->data['lists'] = $query->result();
			$this->data['total'] = $config['total_rows'];
	}


	function gen($action,$id){$this->index($action,$id);}

	function index($action='',$id='') {

		$view='list';
		$this->data['plantilla'].= $view;
		$config['uri_segment'] = '4';
		$where = array();


		if (isset($_REQUEST['rut'])){if ($_REQUEST['rut']>0){ 
			$where["cta.rut"] = $_REQUEST['rut'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'rut='.$_REQUEST['rut'];
		}}
		

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
			
			$query =$this->db->select('distinct(rut),
								cta.rut AS rut,
								cta.dv AS dv,
								cta.cuenta_rut AS cuenta_rut,
								cta.datos AS datos
								')	 
						->where("cta.activo","S")
						->where($where)
						->get("0_cuentas cta");
			
			$total_rows = $query->result();
			
			//$total_rows = $query_total->result();
			$config['total_rows'] = count($total_rows);
	    	$config['per_page'] = '30';
	    	
	    	$this->pagination->initialize($config);
			
			
			$query =$this->db->select('distinct(rut),
								cta.rut AS rut,
								cta.dv AS dv,
								cta.cuenta_rut AS cuenta_rut,
								cta.datos AS datos
								')	 
						->where("cta.activo","S")
						//->distinct("datos")
						->where($where)
						->get("0_cuentas cta", $config['per_page'], $this->data['current_pag']);
			
			$this->data['lists'] = $query->result();
			$this->data['total'] = $config['total_rows'];
			
			//print_r($this->data['lists']); die;
	//		if (!$this->show_tpl){ 
		//		$this->data['plantilla'] = 'procurador/list_tabla';
		//		$this->load->view('backend/templates/'.$this->data['plantilla'], $this->data);
	//		}
		}
		
		if ($this->show_tpl){
			$this->load->view('backend/index', $this->data);
		}
	}

	public function searchfunction($action='',$id='')
    {
		if ($action=='actualizar'){
			$this->procurador_m->update($id, $_POST);
			$this->show_tpl = FALSE;
			$config['uri_segment'] = '6'; 
			$this->data['current_pag'] = $this->uri->segment(6);
		}
		$view='list';
		$this->data['plantilla'].= $view;
		$config['uri_segment'] = '4';
		$where = array();

		$this->data['current_pag'] = $this->uri->segment(4);

		if (isset($_REQUEST['rut'])){if ($_REQUEST['rut']>0){ 
			$where["cta.rut"] = $_REQUEST['rut'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'rut='.$_REQUEST['rut'];
		}}	

		if ($view=='list'){

			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/procurador/index/';
			$config['uri_segment'] = '4';
			
			$query =$this->db->select('
								cta.rut AS rut,
								cta.dv AS dv,
								cta.cuenta_rut AS cuenta_rut,
								cta.datos AS datos
								')	 
						->where("cta.activo","S")
						->where($where)
						->get("0_cuentas cta",$config['per_page'],$this->data['current_pag']);
			
						$config['total_rows'] = count($pag->result());
						$config['per_page'] = '100'; //$config['num_links'] = '10';
						$this->data['current_pag'] = $this->uri->segment(4);
			
						$query =$this->db->select('
						cta.rut AS rut,
						cta.dv AS dv,
						cta.cuenta_rut AS cuenta_rut,
						cta.datos AS datos
						')	 
				->where("cta.activo","S")
				->where($where)
				->get("0_cuentas cta",$config['per_page'],$this->data['current_pag']);
			
			$this->data['lists'] = $query->result();

			$this->data['total']=$config['total_rows'];
			
			$this->load->library('pagination');
	    	$this->pagination->initialize($config);
			
			if (!$this->show_tpl){ $this->data['plantilla'] = 'procurador/list_tabla'; $this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );}
		}

		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}
    }
}

?>