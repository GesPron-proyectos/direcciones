<?php
class Otros_Bienes_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = 'otros_bienes';
		$this->primary_key = 'idotros_bienes';
		$this->alias = 'o';
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
	

	public function get_otros_bienes($idcuenta = ''){
	  	$this->db->from ( "otros_bienes" );
      	$cols = array();
      	$cols [] = 'idotros_bienes AS idotros_bienes';
     	$cols [] = 'observacion AS observacion';
     	$cols [] = 'otros AS otros';
      
      	$this->db->select($cols);
        $this->db->where ( array ('activo' => 'S' ) );

		if($idcuenta != ''){
			$this->db->where(array('id_cuenta'=>$idcuenta)); 
		}
		$this->db->order_by ( 'fecha_crea DESC');
		$query = $this->db->get();
		return $query->result();	
	}

	public function get_otros_bienes_by_id($array_id){
	  	$this->db->from("otros_bienes");
      	$cols = array();
      	$cols [] = 'idotros_bienes AS idotros_bienes';
     	$cols [] = 'observacion AS observacion';
     	$cols [] = 'otros AS otros';
      
      	$this->db->select($cols);
        $this->db->where(array('activo' => 'S'));
        $this->db->where("idotros_bienes in ($array_id)");
		$this->db->order_by('fecha_crea DESC');
		$query = $this->db->get();
		return $query->result();	
	}

	public function eliminar_otro_bien($id){

		$data['activo'] = 'N';
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('idotros_bienes'=>$id))->update($this->_table, $data); 
	}

	public function get_dropdown_ac($where = array()) {
		
		//$this->db->order_by('s1.posicion ASC');		
		$result = $this->get_ac ($where);
		$arr = array ();
		foreach ( $result as $key => $val ) {
			$arr [$val->id] = $val->razon_social;
		}
	    return $arr;
	}

}

?>