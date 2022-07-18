<?php
class Documento_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'documento';
		$this->primary_key = 'iddocumento';
		$this->field_posicion = 'posicion';
		$this->alias = 'doc';
		$this->field_categoria = '';
		
	}	
	
	 public function get_documento($id_cuenta = '') {

	    $cols = array();
		$cols[] ='doc.tipo_documento AS tipo_documento';
		$cols[] ='doc.iddocumento AS id';
		$cols[] ='doc.fecha_crea AS fecha_crea';
		$cols[] ='doc.nombre_documento AS nombre_documento';
		$cols[] ='doc.archivo_zip AS archivo_zip';
		$cols[] ='doc.id_etapa AS id_etapa';
		$cols[] ='doc.iddocumento AS id';
		
			
		$this->db->select($cols);
		$this->db->from("documento doc");
		$this->db->join("s_etapas s","s.id=doc.id_etapa","left");
		$this->db->where(array('doc.activo' => 'S'));
		
		if($id_cuenta != ''){
		$this->db->where(array('doc.idcuenta'=>$id_cuenta));  
		}
		
		$this->db->order_by('doc.fecha_crea DESC');
		$query = $this->db->get();
		return $query->result();
	
     }
	
	
	 public function eliminar_documento($id){
		$data['activo'] = 'N';
		//$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_crea'] = $this->session->userdata('usuario_id');
		$this->db->where(array('iddocumento'=>$id))->update($this->_table, $data); 
	}
	
	
	
	
}
?>