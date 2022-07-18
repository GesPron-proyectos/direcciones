<?php
class Etapas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 's_etapas';
		$this->primary_key = 'id';
		$this->alias = 'e';
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
		
		$this->db->from ( "s_etapas s1" );
		
		if(count($like>0)){$this->db->like($like);}
		$cols = array ();
		$cols [] = 's1.etapa AS etapa';
		$cols [] = 's1.id AS id';
		$cols [] = 's1.tipo AS tipo';
		$cols [] = 's1.codigo AS codigo';
		$cols [] = 's1.posicion AS posicion';
		$cols [] = 's1.id_etapa AS id_etapa';
		
		$cols [] = 's1.etapa_comision AS etapa_comision';
		$cols [] = 's1.etapa_procesal AS etapa_procesal';
		$cols [] = 's1.sub_etapa_procesal AS sub_etapa_procesal';
		
		$this->db->where ( array ('s1.activo' => 'S' ) );
		
		/*if($idpadre != ''){
			$this->db->where ( array ('c.id' => $idpadre ) );
			} */
		$this->db->order_by ('s1.tipo ASC');
		$this->db->order_by ('s1.posicion ASC');
		$this->db->order_by ('s1.codigo ASC');
		$query = $this->db->get ();
		return $query->result ();
	}


	public function get_dropdown_etapas($where = array()) {
		
		//$this->db->order_by('s1.posicion ASC');		
		$result = $this->get_etapas ($where);
		$arr = array ();
		foreach ( $result as $key => $val ) {
			$arr [$val->id] = $val->etapa;
		}
		
	    return $arr;
	}
	
	
	
	
	
	
	
}
?>