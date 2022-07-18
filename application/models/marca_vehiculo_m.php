<?php
class Marca_Vehiculo_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'marca_vehiculo';
		$this->primary_key = 'idmarca_vehiculo';
		$this->alias = 'tipv';
		$this->field_categoria = '';
	
	}

	public function get_marca_vehiculo($idcuenta = ''){

		$this->db->from( "marca_vehiculo" );
		$cols   = array();
		$cols[] = 'idmarca_vehiculo AS idmarca_vehiculo';
		$cols[] = 'marca AS marca';

		$this->db->select($cols);
        $query = $this->db->get();
	    return  $query->result();
		
	}
}
?>