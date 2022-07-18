<?php
class color_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'color_vehiculo';
		$this->primary_key = 'idcolor_vehiculo';
		$this->alias = 'c';
		$this->field_categoria = '';
	
	}

}
?>