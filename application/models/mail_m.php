<?php
class Mail_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = '2_cuentas_mail';
		$this->primary_key = 'id';
		$this->alias = 'm';
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
	
	
public function get_mails($idcuenta) {
		
		
		
		$cols = array ();
		$cols [] = 'm.mail AS mail';
		$cols [] = 'm.estado AS estado';
		$cols [] = 'c.id AS id_cuenta';
		$cols [] = 'm.id AS id';
		$this->db->select($cols);
		//$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
		$this->db->from("2_cuentas_mail m");
		$this->db->join("0_cuentas c","c.id=m.id_cuenta","left");
		
		
         if($idcuenta != ''){
		$this->db->where(array('c.id'=>$idcuenta));  
		}
		
		$this->db->where ( array ('m.activo' => 'S' ) );
		
		/*if($idpadre != ''){
			$this->db->where ( array ('c.id' => $idpadre ) );
			} */
		
		$this->db->order_by ( 'm.mail ASC' );
		$query = $this->db->get ();
		return $query->result ();
	}
	
	public function eliminar($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
		
	}	
	
	
}
?>