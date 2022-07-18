<?php
class Etapas_Acreedor_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'estado_acreedor';
		$this->primary_key = 'id';
		$this->alias = 'ea';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
	}

	public function save($id,$post){
		$fields_save = array();
		
		$columnas = $this->db->list_fields($this->_table);
		foreach ($columnas as $columna){
            //echo $columna.'<br>';
			if(isset($post[$columna])){
				$fields_save[$columna] = $post[$columna];
			}
		}
	   	
		if (count($fields_save)>0){
			$this->save_default($fields_save,$id ,TRUE, TRUE);
	     }
		return false;
	}
	
	public function get_etapas($like) {
		
		$this->db->from ( "estado_acreedor s1" );
		
		if(count($like>0)){$this->db->like($like);}
		$cols = array ();
		$cols [] = 's1.estado AS estado';
		$cols [] = 's1.id AS id';
		
		
		//$this->db->where ( array ('s1.activo' => 'S' ) );
		
		/*if($idpadre != ''){
			$this->db->where ( array ('c.id' => $idpadre ) );
			} */
		//$this->db->order_by ('s1.tipo ASC');
		//$this->db->order_by ('s1.posicion ASC');
		//$this->db->order_by ('s1.codigo ASC');
		$query = $this->db->get ();
		return $query->result ();
	}


	public function get_dropdown_etapas($where = array()) {
		
		//$this->db->order_by('s1.posicion ASC');		
		$result = $this->get_etapas ($where);
		$arr = array ();
		foreach ( $result as $key => $val ) {
			$arr [$val->id] = $val->estado;
		}
		
	    return $arr;
	}
	
	
	
	
	
	
	
}
?>