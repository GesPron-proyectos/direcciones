<?php
class Administradores_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '0_administradores';
		$this->primary_key = 'id';
		$this->alias = 'a';
		$this->field_posicion = 'posicion';
		$this->field_categoria = 'perfil';
		
	}
	public function setup_validate(){
		$this->validate = array(
               array(
                     'field'   => 'username',
                     'label'   => 'Nombre de Usuario',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'password',
                     'label'   => 'Password',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'perfil',
                     'label'   => 'Perfil',
                     'rules'   => 'required|is_natural_no_zero'
                  ),
               array(
                     'field'   => 'correo',
                     'label'   => 'Correo Electrónico',
                     'rules'   => 'valid_email'
                  )
            );
	}
	/*Usando EMP_Model extended de CI_Model*/
	function validar_usuario($username,$password){

		 return $this->get_by(Array('username'=>$username,'password'=>$password));
	}  


    public function list_procurador(){
      
         $this->db->from ( "0_cuentas cta" );

          
          $cols = array();
         $cols [] = 'cta.id AS id';
         $cols [] = 'cta.nombres AS nombres';
         $cols [] = 'cta.mandante AS mandante';
         $cols [] = 'cta.estado AS estado';
      
         
         $this->db->where ( array ('cta.activo' => 'S' ) );

         //$this->db->distinct();
         $this->db->select($cols);
               
            $this->db->order_by ( 'cta.nombres ASC');
            $this->db->group_by('cta.nombres');
         $query = $this->db->get();
         return $query->result();   
   }




}
?>