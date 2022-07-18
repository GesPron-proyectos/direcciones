<?php
class S_Etapas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 's_etapas';
		$this->primary_key = 'id';
		$this->alias = 'et';
		$this->field_posicion = '';
		$this->field_categoria = '';
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

	



    public function get_etapas(){
		
		$this->db->from ( "s_etapas et" );
		$cols = array ();
		$cols [] = 'et.id AS id';
		$cols [] = 'et.etapa AS etapa';
		$this->db->select($cols);
		$this->db->where ( array ('et.activo' => 'S' ) );
		
		$this->db->order_by ('et.etapa ASC');
		
		$query = $this->db->get();
		return $query;
	}






 }
?>