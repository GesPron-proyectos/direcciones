<?php
class Acreedor_Estado_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'estado_acreedor';
		$this->primary_key = 'id';
		$this->alias = 'ea';
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
        $this->db->order_by('estado', 'asc');
        $estados = $this->db->get('estado_acreedor');
        
        if($estados->num_rows() > 0){
            return $estados->result();
        }
    }
    
    public function getCiudades($idEstado) {
        $this->db->where('id_estado_acreedor', $idEstado);
        $this->db->order_by('id', 'asc');
        $ciudades = $this->db->get('sub_estado_acreedor');
        
        if($ciudades->num_rows() > 0){
            return $ciudades->result();
        }
    }


    public function get_jurisdicciones(){
		
		$this->db->from ( "estado_acreedor ea" );
		$cols = array ();
		$cols [] = 'ea.estado AS estado';
		$cols [] = 'ea.id_estado_acreedor AS id_estado_acreedor';
		$this->db->select($cols);
		//$this->db->where ( array ('sea.activo' => 'S' ) );
		
		$this->db->order_by ('ea.estado ASC');
		
		$query = $this->db->get();
		return $query;
	}






 }
?>