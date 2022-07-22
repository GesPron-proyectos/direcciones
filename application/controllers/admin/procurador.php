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

	function form2( $accion = ''){
		//$this->output->enable_profiler ( TRUE );
	  if (!$this->session->userdata('usuario_id')){redirect('login');}
	  $this->load->model ( 'procurador_m' );
	  $this->data['plantilla'] = 'importar/';
	  $view = 'cargar';
	  $this->data['plantilla'].=$view;
	  $this->data['archivos'] = array();
	  $this->data['error'] = array();
	  $this->data['archivo'] = '';
	  $this->data['operacion'] = FALSE;
	  /*cargar archivo*/
	  for($i = 1; $i <= $rowcount; $i++) {
		//$operacion = trim($sheet->getCell('A'. $i)->getValue());
		$rut = trim ($sheet->getCell('A'. $i)->getValue());
		$dv = trim($sheet->getCell('B'. $i)->getValue());
		$cuenta_rut = trim($sheet->getCell('C'. $i)->getValue());
		$datos = trim($sheet->getCell('D'. $i)->getValue());
		
		//consultar direcciones por rut
		if($rut){
			//Insertar dirrrr //t
			$procurador = $this->procurador_m->get_by(array("rut"=> $rut));
			if($procurador){
				$procurador = $this->procurador_m->get_by(array("id"));
				if($procurador)
					$this->array_return['cuentas_update']++;
				else{
					$fields_save = array();
					$fields_save['rut'] = $rut;
					$fields_save['dv'] = $dv;
					$fields_save['cuenta_rut'] = $cuenta_rut;
					$fields_save['datos'] = $datos;
					$this->procurador_m->insert($fields_save, TRUE, TRUE);
					$this->array_return['cuentas_insert']++;
				}
			}
		}
		elseif($rut){
			$this->db->select('cta.*');
			$this->db->from('0_cuentas cta');
			  //$this->db->join("0_usuarios usr","cta.id_usuario=usr.id");
			//$this->db->where("rut like '{$rut}%' and cta.activo = 'S'");
			$this->db->group_by('cta.id');
			$query = $this->db->get();
			$cuentas = $query->result();
			if($cuentas){
				foreach($cuentas as $k => $v){
					$bloqueado = $this->procurador_m->get_by(array("id" => $v->id, 'rut' => $rut));
					if($bloqueado)
						$this->array_return['cuentas_update']++;
					else{
						if($rut){
							$fields_save = array();
							$fields_save['rut'] = $rut;
							$fields_save['dv'] = $dv;
							$fields_save['cuenta_rut'] = $cuenta_rut;
							$fields_save['datos'] = $datos;
							$this->procurador_m->insert($fields_save, TRUE, TRUE);
							$this->array_return['cuentas_insert']++;
						}
						if($dv){
							$fields_save = array();
							$fields_save['rut'] = $rut;
							$fields_save['dv'] = $dv;
							$fields_save['cuenta_rut'] = $cuenta_rut;
							$fields_save['datos'] = $datos;
							$this->procurador_m->insert($fields_save, TRUE, TRUE);
							$this->array_return['cuentas_insert']++;
						}
					}
				}
			}
		}
	}
	  
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
	  ///////////////////////////////////////
	  if (is_file('./uploads/importar.xls')){ $this->data['archivo'] = './uploads/importar.xls'; }
	  
	  $procurador = array(0=>'Seleccionar');
	  $a = $this->procurador_m->get_many_by(array('activo'=>'S'));
	  foreach ($a as $obj) {
		  $procurador[$obj->id] = $obj->rut.' '.$obj->dv;
	  }

	  $this->data['procurador'] = $procurador;

	  if ($accion == 'importar_archivo'){
		  //$this->form_validation->set_rules('id_procurador', 'Procurador', 'trim|required|is_natural_no_zero');
		  if ($this->form_validation->run() == TRUE){
			  $abogado = $this->input->post('id_procurador');
			  if( $this->nodo->nombre == 'fullpay'){
				  $this->importar_excel_nodo_fullpay($procurador);
				  //die();
			  }
			  if($this->input->post('bandera')){
				  redirect('admin/cuentas');
			  }
			  $this->data['usuarios_insert'] = $this->array_return['usuarios_insert'];
			  $this->data['usuarios_update'] = $this->array_return['usuarios_update'];
			  $this->data['cuentas_insert'] = $this->array_return['cuentas_insert'];
			  //$this->data['cuentas_update'] = $this->array_return['cuentas_update'];
			  $this->data['operacion'] = TRUE;
		  } else {
			  //echo 'falla mandante';
		  }
	  }
	  $this->load->view ( 'backend/index', $this->data );
  }
	######################################################
	############### importar direcciones #################
	######################################################
	

	function form1($action='',$id=''){
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
								cta.rut AS rut,
								cta.dv AS dv,
								cta.cuenta_rut AS cuenta_rut,
								cta.datos AS datos
								')	 
						->where("cta.activo","S")
						->get("0_cuentas cta");
			
			$total_rows = $query->result();
			
			//$total_rows = $query_total->result();
			$config['total_rows'] = count($total_rows);
	    	$config['per_page'] = '30';
	    	
	    	$this->pagination->initialize($config);
			
			
			$query =$this->db->select('
								cta.rut AS rut,
								cta.dv AS dv,
								cta.cuenta_rut AS cuenta_rut,
								cta.datos AS datos
								')	 
						->where("cta.activo","S")
						->distinct("datos")
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


}

?>