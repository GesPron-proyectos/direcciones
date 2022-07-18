<?php
class Marcas_especiales_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'marcas_especiales';
		$this->primary_key = 'idMarca';
		$this->alias = 'm_e';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
	}
	
	public function get_comuna($idpadre = ''){
		
		$this->db->from ("marcas_especiales t");
		    
		    $cols = array();
			$cols [] = 't.marca AS marca';
			$cols [] = 't.idMarca AS idMarca';
			
			/*
			$this->db->where ( array ('t.activo' => 'S' ) );
			
			if($idpadre != ''){
			$this->db->where ( array ('t.id' => $idpadre ) );
			}
			*/
			$this->db->order_by ( 't.marca ASC');
			$query = $this->db->get ();
			return $query->result ();	
	}

	
     public function get_comuna_lista($like){
		
		$this->db->from ( "marcas_especiales t" );
		    
      if(count($like>0)){$this->db->like($like);}
		    $cols = array();
			$cols [] = 't.marca AS marca';
			$cols [] = 't.idMarca AS idMarca';
		//	$cols [] = 'tr.tribunal AS tribunal';
		//	$cols [] = 'tri.tribunal AS tribunal_padre';
		//	$cols [] = 'c.id_tribunal_padre AS id_tribunal_padre';
		//	$cols [] = 'c.id_tribunal AS id_tribunal';
			
		//	$this->db->where ( array ('t.activo' => 'S' ) );
			
			$this->db->select($cols);
		
			//$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
       		//$this->db->join("s_tribunales tri","tri.id=c.id_tribunal_padre","left");
			
			
			$this->db->order_by ( 't.marca ASC');
       		$this->db->group_by('t.idMarca');
			$query = $this->db->get();
			return $query;	
	}
	
	

	public function get_dropdown($idpadre=''){
		$result = $this->get_comuna($idpadre);
		$arr = array();
		foreach($result as $key=>$val){
		
	        $arr[$val->id] = $val->tipo; 	
		}
		return $arr;
	
	}
	
   public function get_comuna_list(){
		
		$this->db->from ( "marcas_especiales t" );
		    
		    $cols = array();
			$cols [] = 't.marca AS marca';
			$cols [] = 't.idMarca AS idMarca';
			//$cols [] = 'tr.tribunal AS tribunal';
			//$cols [] = 'tri.tribunal AS tribunal_padre';
			//$cols [] = 'c.id_tribunal_padre AS id_tribunal_padre';
			//$cols [] = 'c.id_tribunal AS id_tribunal';
			//$cols [] = 'trib.tribunal AS tribunal_p';
			
			//$this->db->where ( array ('t.activo' => 'S' ) );
			
			$this->db->select($cols);
		
			//$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
       		//$this->db->join("s_tribunales tri","tri.id=c.id_tribunal_padre","left");
       		//$this->db->join("s_tribunales trib","trib.id=tr.padre","left");
			
       		$this->db->order_by ( 't.marca ASC');
       		$this->db->group_by('t.idMarca');
			$query = $this->db->get();
			return $query->result();	
	}
	
	
	
}
?>