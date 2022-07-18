<?php
class procurador_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = 'procurador';
		$this->primary_key = 'id';
		$this->alias = 'pr';
	}
	
}
?>