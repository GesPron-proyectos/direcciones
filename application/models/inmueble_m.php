<?php
class inmueble_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = '2_cuentas_inmuebles';
		$this->primary_key = 'id';
		$this->alias = 'b';
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
	
	
	
  public function eliminar_bienes($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
	}
	
	
	public function get_cuentas_inmuebles($where){


		$cols[] = 'id';
		$cols[] = 'Observacion AS Datos';
		
		$this->db->select($cols);
		
	    $this->db->from("2_cuentas_inmuebles ");
	    
        $this->db->where(array('activo' => 'S'));
	    //$this->db->where(array('c.id_cuenta'=>$id_cuenta));


        if(count($where>0)){$this->db->where($where);}
        //$this->db->group_by('b.id');
        $query = $this->db->get();
	    return  $query->result();
		
	}
	
}
?>