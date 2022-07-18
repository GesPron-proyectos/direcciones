<?php
class Cuentas_NA extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '0_cuentas_n_a';
		$this->primary_key = 'id';
		$this->alias = 'c';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
		
	}
	 
}
?>