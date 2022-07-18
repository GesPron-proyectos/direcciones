<?php
class log_etapas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = 'log_etapas';
		$this->primary_key = 'id';
		$this->alias = 'loge';
		$this->field_categoria = '';
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
	
	
	public function get_log(){
		
		$c1[] = $this->administradores_m->get_columns();
		$c2[] = $this->etapas_m->get_columns();

		$c = array_merge($c1,$c2);
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = $dato;
			}
		}
		
		foreach ($this->db->list_fields($this->_table) as $columna){
			$cols[] = $this->alias.'.'.$columna;
		}
		
		$cols[] = 'en.etapa AS etapa_nueva';
		$cols[] = 'usr.rut AS rut';
		
		$this->db->select($cols);
		$this->db->from($this->_table.' '.$this->alias);
		$this->db->join('s_etapas e', 'e.id = '.$this->alias.'.dato_anterior');
		$this->db->join('s_etapas en', 'en.id = '.$this->alias.'.dato_nuevo');
		$this->db->join('0_administradores a', 'a.id = '.$this->alias.'.id_usuario');
		$this->db->join('0_cuentas cuenta', 'cuenta.id = '.$this->alias.'.id_cuenta','left');
		$this->db->join('0_usuarios usr', 'usr.id = cuenta.id_usuario','left');
		
		
		
		$query = $this->db->get();
		return $query->result();
	}
	
}
?>