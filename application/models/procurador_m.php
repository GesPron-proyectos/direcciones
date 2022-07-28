<?php
class procurador_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = '0_cuentas';
		$this->primary_key = 'id';
		$this->alias = 'cta';
	}
	public function get_direcciones_lista($like){
		
		$this->db->from ( "0_cuentas cta" );
		    
      if(count($like>0)){$this->db->like($like);}
		    $cols = array();
			$cols [] = 'cta.rut AS rut';
			$cols [] = 'cta.dv AS dv';
			$cols [] = 'cta.cuenta_rut AS cuenta_rut';
			$cols [] = 'cta.datos AS datos';
			$this->db->where ( array ('cta.activo' => 'S' ) );
			
			$this->db->select($cols);

       		$this->db->where('cta.rut','');
			$query = $this->db->get();
			return $query;	
	}
	 public function get_comuna_list(){
		
		$this->db->from ( "0_cuentas cta" );
		    
		  $cols = array();
			$cols [] = 'cta.rut AS rut';
			$cols [] = 'cta.dv AS dv';
			$cols [] = 'cta.cuenta_rut AS cuenta_rut';
			$cols [] = 'cta.datos AS datos';
			
			$this->db->where ( array ('cta.activo' => 'S' ) );
			
			$this->db->select($cols);

       		$this->db->where('cta.rut',$rut);
			$query = $this->db->get();
			return $query->result();	
	}
	
	public function list_sistema(){
		
		$this->db->from ( "0_cuentas cta" );
		    
		    $cols = array();
		
			//$cols [] = 'dir.id AS id';
			$cols [] = 'cta.rut AS rut';
			$cols [] = 'cta.dv AS dv';
			$cols [] = 'cta.cuenta_rut AS cuenta_rut';
			$cols [] = 'cta.datos AS datos';
		
		
			$this->db->select($cols);
			$this->db->where('cta.rut');
       		$this->db->order_by ( 'cta.id ASC');
       		$this->db->group_by('cta.id');
			$query = $this->db->get();
			return $query->result();	
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
}
?>