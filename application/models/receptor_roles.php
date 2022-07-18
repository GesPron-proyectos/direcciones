<?php
class Receptor_Roles extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'receptor_roles';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = 'rec';
		$this->field_categoria = '';
	
	}

}
?>