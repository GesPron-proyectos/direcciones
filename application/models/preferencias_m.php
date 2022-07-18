<?php
class Preferencias_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'preferencia';
		$this->primary_key = 'idpreferencia';
		$this->alias = 'p';
		$this->field_categoria = '';
	
	}

		public function get_preferencia($idpadre = ''){
		
		$this->db->from ( "preferencia p" );
		    
		    $cols = array();
			$cols [] = 'p.preferencia AS preferencia';
			$cols [] = 'p.idpreferencia AS idpreferencia';
			
			if($idpadre != ''){
			$this->db->where ( array ('p.idpreferencia' => $idpref ) );
			}
			
			$this->db->order_by ( 'p.preferencia ASC');
			$query = $this->db->get ();
			return $query->result ();	
	}

	public function get_dropdown_preferencia($idpadre=''){
		$result = $this->get_preferencia($idpadre);
		$arr = array();
		foreach($result as $key=>$val){
		
	        $arr[$val->idpreferencia] = $val->preferencia; 	
		}
		return $arr;
	
	}
}


?>