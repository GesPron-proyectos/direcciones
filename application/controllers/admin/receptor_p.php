<?php
class Receptor_P extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
	
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		
		$this->load->model('receptor_pjud');
		
		/*seters*/
		$this->data['current'] = 'receptor_pjud';
		$this->data['sub_current'] = 'receptor_pjud';
		$this->data['plantilla'] = 'receptor_pjud/';
		$this->data['lists'] = array();
	}

	public function index(){}

	public function listar($id='') {
        //$this->output->enable_profiler(TRUE);
		
		$this->db->select('rec.*');
		
		$this->db->where(array('id_movimiento' => $id));

		$query = $this->db->get('receptor rec');

		$this->data['lists'] = $query->result();


	    //$this->load->library('pagination');
	    //$this->pagination->initialize($config);

		$this->data['plantilla'] = 'receptor_pjud/list';

		//$this->load->view('backend/templates/'.$this->data['plantilla'], $this->data);

		if ($this->show_tpl){
			$this->load->view('backend/index', $this->data);
		}
	}
}

?>