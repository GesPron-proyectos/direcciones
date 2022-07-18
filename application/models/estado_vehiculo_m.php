<?php
class Estado_Vehiculo_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = 'estado_vehiculo';
		$this->primary_key = 'idestado_vehiculo';
		$this->alias = 'ev';
		$this->field_categoria = '';
	}
}
?>