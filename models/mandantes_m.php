<?php
class Mandantes_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '0_cuentas';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = 'cta';
	
	
	}
	
	public function list_mandante(){
		
	   	$this->db->from ( "0_cuentas cta" );

	    $cols = array();
		$cols [] = 'cta.id AS id';
		$cols [] = 'cta.nombres AS nombres';
		$cols [] = 'cta.mandante AS mandante';
		$cols [] = 'cta.estado AS estado';
	
		$this->db->where ( array ('cta.activo' => 'S' ) );

		//$this->db->distinct();
		$this->db->select($cols);
				
   		$this->db->order_by ( 'cta.mandante ASC');
   		$this->db->group_by('cta.mandante');
		$query = $this->db->get();
		return $query->result();	
	}




}
?>