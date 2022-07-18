<?php
class Tipo_Productos_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 's_tipo_productos';
		$this->primary_key = 'id';
		$this->alias = 'tp';
	}
}
?>