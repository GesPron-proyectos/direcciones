<?php
class Inmueble_Bien_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'inmueble';
		$this->primary_key = 'idinmueble';
		$this->alias = 'im';
		$this->field_categoria = '';
	
	}

		public function save($id,$post){
		$fields_save = array();
		
		$columna = $this->db->list_fields($this->_table);
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
	
	public function get_inmueble($idcuenta = ''){
		$this->db->from ( "inmueble inmue" );
		$cols = array();
		$cols [] = 'inmue.idinmueble AS idinmueble';
		$cols [] = 'inmue.activo AS activo';
		$cols [] = 'inmue.observacion AS observacion';
		$cols [] = 'inmue.direccion AS direccion';
		$cols [] = 'inmue.avaluo AS avaluo';
		$cols [] = 'co.nombre AS nombre';
		$cols [] = 'ins.nombre_inscripcion AS nombre_inscripcion';
      
      	$this->db->select($cols);

      	$this->db->join("s_comunas co","co.id = inmue.comuna_idcomuna","inner");
      	$this->db->join("inscripcion ins","ins.idinscripcion= inmue.inscripcion_idinscripcion","inner");
       
        $this->db->where ( array ('inmue.activo' => 'S' ) );

		if($idcuenta != ''){
			$this->db->where(array('inmue.id_cuenta'=>$idcuenta)); 
		}
		$this->db->order_by ( 'inmue.fecha_crea DESC');
		$query = $this->db->get();
		return $query->result();	
	}

	public function get_inmuebles_by_id($array_id){
		$this->db->from("inmueble inmue");
		$cols = array();
		$cols [] = 'inmue.idinmueble AS idinmueble';
		$cols [] = 'inmue.activo AS activo';
		$cols [] = 'inmue.observacion AS observacion';
		$cols [] = 'inmue.direccion AS direccion';
		$cols [] = 'inmue.avaluo AS avaluo';
		$cols [] = 'co.nombre AS nombre';
		$cols [] = 'ins.nombre_inscripcion AS nombre_inscripcion';
      
      	$this->db->select($cols);

      	$this->db->join("s_comunas co","co.id = inmue.comuna_idcomuna","inner");
      	$this->db->join("inscripcion ins","ins.idinscripcion= inmue.inscripcion_idinscripcion","inner");
       
        $this->db->where(array('inmue.activo' => 'S'));
		$this->db->where("inmue.idinmueble in ($array_id)");
		$this->db->order_by('inmue.fecha_crea DESC');
		$query = $this->db->get();
		return $query->result();	
	}

	public function eliminar_inmueble($id){

		$data['activo'] = 'N';
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('idinmueble'=>$id))->update($this->_table, $data); 
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