<?php
class Tribunales_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 's_tribunales';
		$this->primary_key = 'id';
		$this->alias = 't';
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

	
public function get_tribunales($like){
		
	$this->db->from ( "s_tribunales t" );
		
    if(count($like>0)){$this->db->like($like);}
		$cols = array ();
		$cols [] = 'tt.tribunal AS tribunal';
		$cols [] = 't.tribunal AS tribunal_hijo';
		$cols [] = 't.posicion AS posicion';
		$cols [] = 't.id AS id';
		$cols [] = 't.id AS idd';
		$cols [] = 'jur.jurisdiccion AS jurisdiccion';
		$cols [] = 'tt.padre AS padre';
		
		
		$this->db->select($cols);
		$this->db->join("s_jurisdiccion jur","jur.id=t.id_jurisdiccion","left");
		$this->db->join("s_tribunales tt","tt.id=t.padre","left");
		
		$this->db->where ( array ('t.activo' => 'S' ) );
		//`t`.`id` ASC,`t`.`padre` ASC
		$this->db->order_by ('t.id ASC');
		$this->db->order_by ('t.padre ASC');
		//$this->db->order_by ('t.posicion ASC');
		$this->db->group_by('t.id');
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		
		return $query;
	}	
 }
?>