<?php
class Inscripcion_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = 'inscripcion';
		$this->primary_key = 'idinscripcion';
		$this->alias = 'ins';
		$this->field_categoria = '';
	}
}
?>