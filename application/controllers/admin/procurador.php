<?php
include_once "./application/core/EMP_Encrypt.php";
require_once 'application/libraries/PHPExcel.php';

class Procurador extends CI_Controller {
	public $data = array();
	public $activo = 'S';
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
		$this->array_return['elimin'] = 0;
		$this->array_return['cuentas_update'] = 0;
		
	}
	public function direcciones($rut='') {
		
		$query=$this->procurador_m->list_sistema(array());
		$this->data['lists'] = $query;
        $this->data['total'] = $config['total_rows'] = count($query);

		$view='list';  //lista
		$this->data['plantilla'].= $view;	
		$config['uri_segment'] = '4';
		$this->data['rut'] = $rut;
		$this->data['current_pag'] = $this->uri->segment(4);	
		if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){ 
			$where["cta.rut"] = $_REQUEST['rut'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
			$config['suffix'].= 'rut='.$_REQUEST['rut'];
		}
		$this->data['plantilla']= $this->procurador_m->get_direcciones_list();
		
		$this->load->view ( 'backend/index', $this->data );
	}


	function gen($action,$id){$this->index($action,$id);}
	
	function index($action='',$id='') {
		$query=$this->procurador_m->get_direcciones_lista(array());
		$this->data['lists'] = $query;
        $this->data['total'] = $config['total_rows'] = count($query);

		$view='list';
		$this->data['plantilla'].= $view;	
		if($action =='buscar'){
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/procurador/buscar';
			$config['total_rows'] = $this->db->where("activo","S")->count_all_results("0_cuentas cta");
			$config['per_page'] = '35'; //$config['num_links'] = '10';
			$config['suffix'] = '';

			$like = array();
			if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){ 
				$where["cta.rut"] = $_REQUEST['rut'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rut='.$_REQUEST['rut'];
			}
			$query =$this->db->select('
								cta.rut AS rut,
								cta.dv AS dv,
								cta.cuenta_rut AS cuenta_rut,
								cta.datos AS datos,
								cta.fecha_crea AS fecha_crea
								')	 
						->where("cta.activo","S")
						->where("rut","")
						->distinct("rut")
						->get("0_cuentas cta", $config['per_page'], $this->data['current_pag']);
			
			$this->data['lists'] = $query->result();
			$this->data['total'] = $config['total_rows'];
		}
		if ($action=='actualizar'){
			$this->procurador_m->update($id, $_POST);
			$this->show_tpl = FALSE;
			$config['uri_segment'] = '6'; 
			$this->data['current_pag'] = $this->uri->segment(6);
		}
		
		
		if ($view=='list'){
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/procurador/list';
			$config['total_rows'] = $this->db->where("activo","S")->count_all_results("0_cuentas cta");
			$config['per_page'] = '35'; //$config['num_links'] = '10';
			$config['suffix'] = '';

			$like = array();
			if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){ 
				$where["cta.rut"] = $_REQUEST['rut'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rut='.$_REQUEST['rut'];
			}
			
			$query =$this->db->select('
								cta.rut AS rut,
								cta.dv AS dv,
								cta.cuenta_rut AS cuenta_rut,
								cta.datos AS datos,
								cta.fecha_crea AS fecha_crea
								')	 
					//	->where("cta.activo","S")
						->like("rut")
						//->distinct("")
						->get("0_cuentas cta", $config['per_page'], $this->data['current_pag']);
			
			$this->data['lists'] = $query->result();
			$this->data['total'] = $config['total_rows'];
		}
		if ($this->show_tpl){
			$this->data['plantilla'] = 'procurador/list'; 
			$this->load->view('backend/index', $this->data);
		}
	}
	
	######################################################
	############### importar direcciones #################
	######################################################
	public function importar_excel_dias_mora(){
		ini_set('precision', '20');
		$array_return = array ();
		$array_return['cuentas_insert'] = 0;
		$array_return['cuentas_update'] = 0;
	//	$array_return['mora_update'] = 0;
		$array_return['usuarios_insert'] = 0;
		$array_return['usuarios_update'] = 0;
	
		$this->data ['cuentas'] = FALSE;
		
		$rows_insert = array ();
		
		// ojo ss
		$uploadpath = "./uploads/importar_direcciones.xls";
		
		$this->load->library('PHPExcel');
        $inputFileType = PHPExcel_IOFactory::identify($uploadpath);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$excel = $objReader->load($uploadpath);
		$excel = $excel->getSheet(1);  //Numero de la hoja activa a importar
		$rowcount = $excel->getHighestRow();
		$highestcolumn = $excel->getHighestColumn();
		
		$id_usuario = $this->session->userdata('usuario_id');
		
		if($this->$datos=array())
		
		for($i = 2; $i <= $rowcount; $i ++){
			$rut = trim($sheet->getCellByColumnAndRow('A'. $i)->getValue());
			$dv = trim ( $sheet->getCellByColumnAndRow('B'. $i)->getValue());
			$cuenta_rut = trim ( $sheet->getCellByColumnAndRow('C'. $i)->getValue());
			$datos = trim ( $sheet->getCellByColumnAndRow('D'. $i)->getValue());
		
			
			$datos = array(
				'Rut'  => $rut,
      			'Dv'   => $dv,
    			'Cuenta_rut'    => $cuenta_rut,
    			'Datos'  => $datos,
			);
			$this->procurador_m->save_default($fields_save, $datos->id);
							$this->array_return['cuentas_update'];
		
		} // FIN FOR 
	}


}

?>