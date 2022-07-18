<?php
class Movimiento_Cuenta_Roles extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'roles_movimiento_cuenta';
		$this->primary_key = 'id';
		$this->alias = 'mvr';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
	}

	
	
}
?>