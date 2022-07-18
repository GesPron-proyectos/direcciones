<?php
class Sistemas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'sistemas';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = 's';
		$this->field_categoria = '';
	
	}

	
   public function list_sistema(){
		
		$this->db->from ( "sistemas s" );
		    
		    $cols = array();
			$cols [] = 's.id AS id';
			$cols [] = 's.nombres AS nombres';
			$cols [] = 's.sistema AS sistema';
		
			
			$this->db->where ( array ('s.activo' => 'S' ) );
			
			$this->db->select($cols);
					
       		$this->db->order_by ( 's.sistema ASC');
       		$this->db->group_by('s.sistema');
			$query = $this->db->get();
			return $query->result();	
	}
	
	
	
}
?>