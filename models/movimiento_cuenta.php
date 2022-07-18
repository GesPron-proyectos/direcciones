<?php
class Movimiento_Cuenta extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'movimiento_cuenta';
		$this->primary_key = 'id';
		$this->alias = 'c';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
	}

	
	
}
?>