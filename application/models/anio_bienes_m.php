<?php
class Anio_Bienes_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = 'anio_bienes';
		$this->primary_key = 'idanio_bienes';
		$this->alias = 'abn';
		$this->field_categoria = '';
	}
}
?>