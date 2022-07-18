<?php
class documento_plantilla_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'documento_plantilla';
		$this->primary_key = 'id';
		$this->alias = 'dp';
		$this->field_categoria = '';
		$this->field_posicion = '';
	}
}
?>