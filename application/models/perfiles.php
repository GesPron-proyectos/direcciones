<?php
class Perfiles extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 's_perfil';
		$this->primary_key = 'id';
	}
}
?>