<?php
class Configuracion_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'configuracion';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = 'conf';
		$this->field_categoria = '';
	
	}
	
}
?>