<?php
class Comunas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 's_comunas';
		$this->primary_key = 'id';
		$this->alias = 'com';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
	}
	
	public function get_comuna($idpadre = ''){
		
		$this->db->from ( "s_comunas c" );
		    
		    $cols = array();
			$cols [] = 'c.nombre AS nombre';
			$cols [] = 'c.id AS id';
			
			$this->db->where ( array ('c.activo' => 'S' ) );
			
			if($idpadre != ''){
			$this->db->where ( array ('c.id' => $idpadre ) );
			}
			
			$this->db->order_by ( 'c.nombre ASC');
			$query = $this->db->get ();
			return $query->result ();	
	}

	
     public function get_comuna_lista($like){
		
		$this->db->from ( "s_comunas c" );
		    
      if(count($like>0)){$this->db->like($like);}
		    $cols = array();
			$cols [] = 'c.nombre AS nombre';
			$cols [] = 'c.id AS id';
			$cols [] = 'tr.tribunal AS tribunal';
			$cols [] = 'tri.tribunal AS tribunal_padre';
			$cols [] = 'c.id_tribunal_padre AS id_tribunal_padre';
			$cols [] = 'c.id_tribunal AS id_tribunal';
			
			$this->db->where ( array ('c.activo' => 'S' ) );
			
			$this->db->select($cols);
		
			$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
       		$this->db->join("s_tribunales tri","tri.id=c.id_tribunal_padre","left");
			
			
			$this->db->order_by ( 'c.nombre ASC');
       		$this->db->group_by('c.id');
			$query = $this->db->get();
			return $query;	
	}
	
	

	public function get_dropdown($idpadre=''){
		$result = $this->get_comuna($idpadre);
		$arr = array();
		foreach($result as $key=>$val){
		
	        $arr[$val->id] = $val->nombre; 	
		}
		return $arr;
	
	}
	
   public function get_comuna_list(){
		
		$this->db->from ( "s_comunas c" );
		    
		    $cols = array();
			$cols [] = 'c.nombre AS nombre';
			$cols [] = 'c.id AS id';
			$cols [] = 'tr.tribunal AS tribunal';
			$cols [] = 'tri.tribunal AS tribunal_padre';
			$cols [] = 'c.id_tribunal_padre AS id_tribunal_padre';
			$cols [] = 'c.id_tribunal AS id_tribunal';
			//$cols [] = 'trib.tribunal AS tribunal_p';
			
			$this->db->where ( array ('c.activo' => 'S' ) );
			
			$this->db->select($cols);
		
			$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
       		$this->db->join("s_tribunales tri","tri.id=c.id_tribunal_padre","left");
       		//$this->db->join("s_tribunales trib","trib.id=tr.padre","left");
			
       		$this->db->order_by ( 'c.nombre ASC');
       		$this->db->group_by('c.id');
			$query = $this->db->get();
			return $query->result();	
	}
	
	
	
}
?>