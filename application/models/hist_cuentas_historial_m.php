<?php
class Hist_Cuentas_Historial_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'hist_2_cuentas_historial';
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
	public function eliminar($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
	}
}
?>