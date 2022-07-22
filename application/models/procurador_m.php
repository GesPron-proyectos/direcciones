<?php
class procurador_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = '0_cuentas';
		$this->primary_key = 'id';
		$this->alias = 'dir';
	}
	public function list_sistema(){
		
		$this->db->from ( "0_cuentas dir" );
		    
		    $cols = array();
		
			//$cols [] = 'dir.id AS id';
			$cols [] = 'dir.rut AS rut';
			$cols [] = 'dir.dv AS dv';
			$cols [] = 'dir.cuenta_rut AS cuenta_rut';
			$cols [] = 'dir.datos AS datos';
		
			
			
			
			
			$this->db->select($cols);
					$this->db->where ( array ('dir.activo' => 'S' ) );
       		$this->db->order_by ( 'dir.id ASC');
       		$this->db->group_by('dir.id');
			$query = $this->db->get();
			return $query->result();	
	}
	public function setup_validate(){
		$this->validate = array(
               array(
                     'field'   => 'id',
                     'label'   => 'Ids',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'rut',
                     'label'   => 'Rut',
                     'rules'   => 'trim|required'
                  ),
            );
	}
	public function save_default($id,$post){
		$fields_save = array();
		
		$columnas = $this->db->list_sistema($this->_table);
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
}
?>