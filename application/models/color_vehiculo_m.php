<?php
class Color_Vehiculo_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'color_vehiculo';
		$this->primary_key = 'idcolor_vehiculo';
		$this->alias = 'colv';
		$this->field_categoria = '';
	
	}
}
?>