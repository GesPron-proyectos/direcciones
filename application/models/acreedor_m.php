<?php
class Acreedor_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '0_mandantes';
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

	
		public function save($id,$post){
		$fields_save = array();
		
		$columna = $this->db->list_fields($this->_table);
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