<?php

class Cuentas_Contratos_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '2_cuentas_contratos';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = '2cc';
		$this->field_categoria = '';
		
	}
	public function setup_validate(){
		$this->validate = array(
               array(
                     'field'   => 'numero_contrato',
                     'label'   => 'numero contrato',
                     'rules'   => 'trim'
                  ),
            );
	}
		

	public function save($id,$post){
		$fields_save = array();
		
		$columnas = $this->db->list_fields($this->_table);
		foreach ($columnas as $columna){
			if(isset($post[$columna])){
				$fields_save[$columna] = $post[$columna];
			}
		}
	   	
		if (count($fields_save)>0){
			$this->save_default($fields_save,$id ,TRUE, TRUE);
	     }
		return false;
	}
}

?>