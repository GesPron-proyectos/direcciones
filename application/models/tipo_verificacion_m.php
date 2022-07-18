<?php
class Tipo_Verificacion_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'tipo_verificacion';
		$this->primary_key = 'idtipo_verificacion';
		$this->alias = 'tp';
		$this->field_categoria = '';
	
	}
}

?>