<?php
class Comprobantes_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '0_comprobantes';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
		
	}
	public function setup_validate(){
		$this->validate = array(
               array(
                     'field'   => 'id_cuenta',
                     'label'   => 'Cuenta',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'monto',
                     'label'   => 'monto',
                     'rules'   => 'trim|required|is_natural_no_zero'
                  )
               
            );
	} 
	
	public function eliminar($id){
		$data['activo'] = 'N';
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
	}
	
}
?>