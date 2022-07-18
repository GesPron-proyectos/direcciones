<?php
class Doc_Clasificacion extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'doc_clasificacion';
		$this->primary_key = 'id';
		$this->alias = 'dcl';
		$this->field_posicion = '';
		$this->field_categoria = '';
	}	

	public function save($id, $post){
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