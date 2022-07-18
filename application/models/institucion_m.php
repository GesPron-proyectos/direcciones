<?php
class Institucion_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'instituciones';
		$this->primary_key = 'idinstituciones';
		$this->field_posicion = 'posicion';
		$this->alias = 'i';
		$this->field_categoria = '';
	
	}

	public function setup_validate(){
		$this->validate = array(
               array(
                     'field'   => 'razon_social',
                     'label'   => 'Raz�n Social',
                     'rules'   => 'trim|required'
                  ),
            );
	}

}
?>