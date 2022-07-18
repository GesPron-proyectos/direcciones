<?php
class Hist_Estado_Cuentas extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'hist_estado_cuentas';
		$this->primary_key = 'id';
		$this->alias = 'hec';
	}	

	public function save($id, $post){
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

	public function get_historial($idcuenta = '') {
		$cols = array();
		$cols[] ='adm.nombres AS nombres';
		$cols[] ='adm.apellidos AS apellidos';
		$cols[] ='ec.estado AS estado';
		$cols[] ='hec.fecha_crea AS fecha_crea';
			
		$this->db->select($cols);
		$this->db->from("hist_estado_cuentas hec");
		$this->db->join("0_administradores adm","adm.id=hec.id_usuario");
		$this->db->join("s_estado_cuenta ec","ec.id=hec.id_estado_cuenta");

		if($idcuenta != ''){
			$this->db->where(array('hec.id_cuenta'=>$idcuenta));  
		}
		
		$this->db->order_by('hec.id DESC');
	
		$query = $this->db->get();
		return $query->result();
	}
}
?>