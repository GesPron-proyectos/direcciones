<?php
class Tipo_Vehiculo_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'tipo_vehiculo';
		$this->primary_key = 'idtipo_vehiculo';
		$this->alias = 'tipv';
		$this->field_categoria = '';
	
	}
}
?>