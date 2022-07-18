<?php
class Usuarios_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '0_usuarios';
		$this->primary_key = 'id';
		$this->alias = 'u';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
		
	}
	public function setup_validate(){
		$this->validate = array(
			array(
                     'field'   => 'rut',
                     'label'   => 'Rut',
                     'rules'   => 'trim|required|is_rut'
                  ),
               array(
                     'field'   => 'nombres',
                     'label'   => 'Nombres',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'ap_pat',
                     'label'   => 'Ap. Paterno',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'ap_mat',
                     'label'   => 'Ap. Materno',
                     'rules'   => 'trim|required'
                  )
               
            );
	}
	/*Usando EMP_Model extended de CI_Model*/
	function validar_usuario($username,$password){

		 return $this->get_by(Array('username'=>$username,'password'=>$password));
	}  
}
?>