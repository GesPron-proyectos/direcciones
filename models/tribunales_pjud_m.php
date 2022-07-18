<?php
class Tribunales_pjud_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'tribunales_pjud';
		$this->primary_key = 'id';
		$this->alias = 'tj';
		$this->field_posicion = 'posicion';
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
	
	
	public function get_tribunales_pjud($where=array(),$like=array()){
		
		
		$cols = array();
		$cols[] = 't.id AS id';
		$cols[] = 't.id_tribunal AS id_tribunal';
		$cols[] = 't.tribunal AS tribunal';

      

		$this->db->select($cols);
		

        $this->db->from("tribunales_pjud t");
		
    	
		 if (count($where)>0){
         $this->db->where($where);
		} 
		if (count($like)>0){
         $this->db->like($like);
		} 
		
		
		
        $query = $this->db->get();
		return  $query->result();
	    
	}



	
}
?>