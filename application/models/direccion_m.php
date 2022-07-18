<?php
class Direccion_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = '2_cuentas_direccion';
		$this->primary_key = 'id';
		$this->alias = 'd';
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
	
	
	
public function get_direccion_cuenta($idcuenta = ''){
		
		$this->db->from ( "2_cuentas_direccion dir" );
		    
		    $cols = array();
			$cols [] = 'dir.direccion AS direccion';
			$cols [] = 'dir.observacion AS observacion';
			$cols [] = 'dir.estado AS estado';
			$cols [] = 'dir.id AS id';
			$cols [] = 'dir.tipo AS tipo';
			$cols [] = 'comu.nombre AS nombre_comuna';
			
			$this->db->select($cols);
		    $this->db->join("s_comunas comu","comu.id=dir.id_comuna","left");
			$this->db->where ( array ('dir.activo' => 'S' ) );
			
			if($idcuenta != ''){
			$this->db->where(array('dir.id_cuenta'=>$idcuenta)); 
			}
			/* if($idpadre != ''){
			$this->db->where ( array ('c.id' => $idpadre ) );
			} */
			
			$this->db->order_by ( 'dir.fecha_crea DESC');
			$query = $this->db->get ();
			return $query->result ();	
	}
	
	
public function eliminar_direccion($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
	}
	
	
	
	
}
?>