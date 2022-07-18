<?php
class Abogados_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'abogado';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = 'abo';
		$this->field_categoria = '';
	
	}

	
   public function list_sistema(){
		
		$this->db->from ( "abogado ab" );
		    
		    $cols = array();
			$cols [] = 'ab.id AS id';
			$cols [] = 'ab.nombres AS nombres';
			$cols [] = 'ab.sistema AS sistema';
		
			
			$this->db->where ( array ('ab.activo' => 'S' ) );
			
			$this->db->select($cols);
					
       		$this->db->order_by ( 'ab.sistema ASC');
       		$this->db->group_by('ab.sistema');
			$query = $this->db->get();
			return $query->result();	
	}
	
	
	
}
?>