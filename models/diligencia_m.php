<?php
class diligencia_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'diligencia';
		$this->primary_key = 'id';
		$this->alias = 'd';
	}
}
?>