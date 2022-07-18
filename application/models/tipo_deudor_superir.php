<?php
class Tipo_Deudor_Superir extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'tipo_deudor_superir';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->alias = 't_u';
		$this->field_categoria = '';
	
	}
	public function setup_validate(){
		$this->validate = array(
               array(
                     'field'   => 'tipo',
                     'label'   => 'tipo',
                     'rules'   => 'trim|required'
                  )
           
            );
	}

	public function get_tipo_deudor($tipo){
		$this->db->from ("tipo_deudor_superir t");
	    $cols = array();
		$cols[] = 't.tipo';
		$cols[] = 't.id AS id';
		
		$this->db->where(array('tipo'=>$tipo));
		$query = $this->db->get();
		return $query->result();
	}
}
?>