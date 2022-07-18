<?php
class Distrito_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'jurisdiccion';
		$this->primary_key = 'id';
		$this->alias = 'juz';
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

	
        public function getEstados() {
        $this->db->order_by('jurisdiccion', 'asc');
        $estados = $this->db->get('jurisdiccion');
        
        if($estados->num_rows() > 0){
            return $estados->result();
        }
    }
    
    public function getCiudades($idEstado) {
        $this->db->where('jurisdiccion_id', $idEstado);
        $this->db->order_by('id', 'asc');
        $ciudades = $this->db->get('s_tribunales');
        
        if($ciudades->num_rows() > 0){
            return $ciudades->result();
        }
    }


    public function get_jurisdicciones(){
		
		$this->db->from ( "jurisdiccion juz" );
		$cols = array ();
		$cols [] = 'juz.jurisdiccion AS jurisdiccion';
		$cols [] = 'juz.id AS id';
		$this->db->select($cols);
		$this->db->where ( array ('juz.activo' => 'S' ) );
		
		$this->db->order_by ('juz.jurisdiccion ASC');
		
		$query = $this->db->get();
		return $query;
	}






 }
?>