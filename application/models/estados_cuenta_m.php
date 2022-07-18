<?php
class Estados_Cuenta_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '0_cuentas';
		$this->primary_key = 'id';
		$this->alias = 'cta';
	}




	   public function list_estado(){
		
		$this->db->from ( "0_cuentas cta" );
		
		    
		    $cols = array();
			$cols [] = 'cta.id AS id';
			$cols [] = 'cta.nombres AS nombres';
			$cols [] = 'cta.mandante AS mandante';
			$cols [] = 'cta.estado AS estado';
		
			
			$this->db->where ( array ('cta.activo' => 'S' ) );

			//$this->db->distinct();
			$this->db->select($cols);
					
       		$this->db->order_by ( 'cta.estado ASC');
       		$this->db->group_by('cta.estado');
			$query = $this->db->get();
			return $query->result();	
	}
}
?>