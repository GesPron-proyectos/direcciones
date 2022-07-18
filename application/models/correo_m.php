<?php
class Correo_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = 'correo_cc';
		$this->primary_key = 'id';
		$this->alias = 'b';
		$this->field_categoria = '';
	}
	
}
?>