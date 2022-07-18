<?php
class comunas_tribunales_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '2_comunas_tribunales';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = '';
		$this->field_categoria = '';
		
	}
}
?>