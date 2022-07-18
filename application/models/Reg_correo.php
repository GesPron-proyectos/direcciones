<?php
class Reg_Correo extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'reg_correo';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = 'reg';
		$this->field_categoria = '';
	
	}
	
}
?>