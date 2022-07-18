<?php
class Modelo_Vehiculo_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'modelo_vehiculo';
		$this->primary_key = 'idmodelo_vehiculo';
		$this->alias = 'mdv';
		$this->field_categoria = '';
	
	}

	public function get_modelo_vehiculo($id_marca){

		$this->db->from( "modelo_vehiculo" );
		$cols   = array();
		$cols[] = 'idmodelo_vehiculo AS idmodelo_vehiculo';
		$cols[] = 'modelo AS modelo';

		$this->db->select($cols);
		$this->db->where('marca_vehiculo_idmarca_vehiculo',$id_marca);
        $query = $this->db->get();
	    return  $query->result();
		
	}
}
?>