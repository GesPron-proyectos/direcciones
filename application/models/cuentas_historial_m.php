<?php
class Cuentas_Historial_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '2_cuentas_historial';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->field_categoria = 'id_cuenta';
		
	}
	public function setup_validate(){
		$this->validate = array(
			array(
                     'field'   => 'id_cuenta',
                     'label'   => 'Cuenta',
                     'rules'   => 'is_natural_no_zero|required'
                  ),
            array(
                     'field'   => 'historial',
                     'label'   => 'Observación',
                     'rules'   => 'required'
                  )
            );
	} 
	public function eliminar_historial($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
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
	
	
	
public function get_cuentas_etapas_historial($idcuenta = '') {

	    $cols = array();
		$cols[] ='adm2.nombres AS nombres';
		$cols[] ='adm2.apellidos AS apellidos';
		$cols[] ='ch.fecha AS fecha';
		$cols[] ='ch.observaciones AS observaciones';
		$cols[] ='ch.id AS id';
		
			
		$this->db->select($cols);
		$this->db->from("2_cuentas_historial ch");
		$this->db->join("0_administradores adm2","adm2.id=ch.user_crea","left");
		$this->db->where(array('ch.activo' => 'S'));
		
		if($idcuenta != ''){
		$this->db->where(array('ch.id_cuenta'=>$idcuenta));  
		}
		
		$this->db->order_by('ch.fecha_crea DESC');
		$query = $this->db->get();
		return $query->result();
	
}
	
	
	
	
	
	
	
}
?>