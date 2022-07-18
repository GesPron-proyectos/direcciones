<?php
class Cuentas_Llamadas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '2_cuentas_llamadas';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->field_categoria = 'id_cuenta';
		
	}
	public function setup_validate(){
		$this->validate = array(
			
            );
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


    public function get_cuentas_llamadas($where=array(),$like=array()){

        $cols = array();
        $cols[] = 'c.id AS id';
        $cols[] = 'c.id_mandante AS id_mandante';
        $cols[] = 'c.rol AS rol';
        $cols[] = 'c.dia_vencimiento_cuota AS dia_vencimiento_cuota';
        $cols[] = 'usr.rut AS rut';
        $cols[] = 'usr.nombres AS nombres';
        $cols[] = 'usr.ap_pat AS ap_pat';
        $cols[] = 'usr.ap_mat AS ap_mat';
        $cols[] = 'llas.tipo_llamada AS tipo_llamada';
        $cols[] = 'llas.estado AS estado';
        $this->db->select($cols);
        $this->db->from("2_cuentas_llamadas llas");
        $this->db->join("0_cuentas c","c.id=llas.id_cuenta","left");
        $this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");


        if (count($where)>0){
            $this->db->where($where);
        }
        if (count($like)>0){
            $this->db->like($like);
        }

        //$this->db->limit('30');
        //$this->db->group_by('dir.id');
        //$this->db->group_by('c.id');

        $query = $this->db->get();
        return  $query->result();

    }
		
	
}
?>