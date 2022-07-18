<?php
class Estado_Acreedor_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'estado_acreedor';
		$this->primary_key = 'id';
		$this->alias = 'ea';
		$this->field_categoria = '';
	
	}
}


?>