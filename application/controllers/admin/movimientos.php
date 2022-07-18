<?php
class Movimientos extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
	
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		
		$this->load->model('movimiento_cuenta');
		
		/*seters*/
		$this->data['current'] = 'movimientos';
		$this->data['sub_current'] = 'movimientos';
		$this->data['plantilla'] = 'movimientos/';
		$this->data['lists'] = array();
	}

	public function index(){}

	public function listar($id=0, $id_na=0) {
        //$this->output->enable_profiler(TRUE);
		
		$this->db->select('mov.*');
		if($id)
			$this->db->where(array('id_cuenta' => $id));
		elseif($id_na)
			$this->db->where(array('id_cuenta_na' => $id_na));

		$query = $this->db->get('movimiento_cuenta mov');

		$this->data['lists'] = $query->result();
		
		//echo $this->db->last_query();die;
		
	    //$this->load->library('pagination');
	    //$this->pagination->initialize($config);

		$this->data['plantilla'] = 'movimientos/list';

		//$this->load->view('backend/templates/'.$this->data['plantilla'], $this->data);

		if ($this->show_tpl){
			$this->load->view('backend/index', $this->data);
		}
	}
}

?>