<?php
class Consulta_Pjud_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'consulta_pjud';
		$this->primary_key = 'id';
	}

}
?>