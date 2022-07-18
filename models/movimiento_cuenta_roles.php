<?php
class Movimiento_Cuenta_Roles extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'movimiento_cuenta_roles';
		$this->primary_key = 'id';
		$this->alias = 'c';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
	}

	
	
}
?>