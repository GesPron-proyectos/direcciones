<?php
class Ministro_de_Fe_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'ministro_de_fe';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = 'm';
		$this->field_categoria = '';
	
	}
	public function setup_validate(){
		$this->validate = array(
               array(
                     'field'   => 'rut',
                     'label'   => 'Ruts',
                     'rules'   => 'trim|required'
                  ),
            );
	}
}
?>