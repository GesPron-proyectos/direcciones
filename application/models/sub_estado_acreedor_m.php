<?php
class Sub_estado_acreedor_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'sub_estado_acreedor';
		$this->primary_key = 'id';
		$this->alias = 'sea';
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
		
	$this->db->from ( "sub_estado_acreedor t" );
		
    if(count($like>0)){$this->db->like($like);}
		$cols = array ();
		//$cols [] = 'tt.tribunal AS tribunal';
		$cols [] = 't.sub_estado_acreedor AS sub_estado_acreedor';
		//$cols [] = 't.posicion AS posicion';
		$cols [] = 't.id AS id';
		$cols [] = 't.id AS idd';
		$cols [] = 'jur.estado AS estado';
		//$cols [] = 'tt.padre AS padre';
		
		
		$this->db->select($cols);
		$this->db->join("estado_acreedor jur","jur.id=t.id_estado_acreedor","left");
	//	$this->db->join("sub_estado_acreedor tt","tt.id=t.padre","left");
		
		$this->db->where ( array ('t.activo' => 'S' ) );
		//`t`.`id` ASC,`t`.`padre` ASC
		$this->db->order_by ('t.id ASC');
		//$this->db->order_by ('t.padre ASC');
		//$this->db->order_by ('t.posicion ASC');
		$this->db->group_by('t.id');
		$query = $this->db->get();
		
		//echo $this->db->last_query();
		
		return $query;
	}	
 }
?>