<?php
class Cartas_Enviadas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'cartas_enviadas';
		$this->primary_key = 'idcartas_enviadas';
		$this->alias = 'ac';
		$this->field_categoria = '';
	
	}

		public function save($id,$post){
		$fields_save = array();
		
		$columna = $this->db->list_fields($this->_table);
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

	public function get_cartas_by_id($array_id){
		$this->db->from ("cartas_enviadas ac");
		$cols = array();
		$cols [] = 'ac.fecha_envio AS fecha_envio';
		$cols [] = 'ac.idcartas_enviadas AS idcartas_enviadas';
		$cols [] = 'ac.id_cuenta AS id_cuenta';
		$cols [] = 'ac.estado AS estado';
		$cols [] = 'ac.codigo AS codigo';
		$cols [] = 'ac.finalizada AS finalizada';
		$cols [] = 'ac.instituciones_idinstituciones AS instituciones_idinstituciones';
		$cols [] = 'ins.razon_social AS razon_social';
		$cols [] = 'ins.idinstituciones AS idinstituciones';
      	$this->db->select($cols);
        $this->db->join("instituciones ins", "ins.idinstituciones=ac.instituciones_idinstituciones", "inner");
        $this->db->where(array('ac.activo' => 'S'));
        $this->db->where("ac.idcartas_enviadas in ($array_id)");
		$this->db->order_by('ac.codigo DESC');
		$query = $this->db->get();
		return $query->result();	
	}

	public function get_cartas_enviadas($idcuenta = '', $array = array()){

		$this->db->from ( "cartas_enviadas ac" );
		$cols = array();
		$cols [] = 'ac.fecha_envio AS fecha_envio';
		$cols [] = 'ac.fecha_ultimo_envio AS fecha_ultimo_envio';
		$cols [] = 'ac.idcartas_enviadas AS idcartas_enviadas';
		$cols [] = 'ac.id_cuenta AS id_cuenta';
		$cols [] = 'ac.estado AS estado';
		$cols [] = 'ac.codigo AS codigo';
		$cols [] = 'ac.finalizada AS finalizada';
		$cols [] = 'ac.instituciones_idinstituciones AS instituciones_idinstituciones';
		$cols [] = 'ins.razon_social AS razon_social';
		$cols [] = 'ins.idinstituciones AS idinstituciones';

      	$this->db->select($cols);
        $this->db->join("instituciones ins","ins.idinstituciones=ac.instituciones_idinstituciones","inner");

        if(empty($array))
        	$this->db->where (array('ac.activo' => 'S', 'ac.fecha_envio' => '0000-00-00'));
        else
        	$this->db->where (array('ac.activo' => 'S'));
        //$this->db->group_by ( array ('ins.razon_social') );

		if($idcuenta != ''){
			$this->db->where(array('ac.id_cuenta'=>$idcuenta)); 
		}

		if($array['id_inst']){
			$this->db->where(array('ac.instituciones_idinstituciones'=>$array['id_inst']));
		}

		if($array['fecha']){
			$where_str = "ac.fecha_envio >= '".date('Y-m-d', strtotime($array['fecha']))." 00:00:00' AND ac.fecha_crea <= '".date('Y-m-d', strtotime($array['fecha']))." 23:59:59'";
			$this->db->where($where_str);
		}

		if($array['estado'] != ''){
			$this->db->where(array('ac.estado'=>$array['estado']));
		}

		$this->db->order_by('ac.codigo DESC');
		$query = $this->db->get();
		return $query->result ();	
	}

	public function get_reg_cartas($idcuenta = '', $id_inst = ''){
		$this->db->from ( "cartas_enviadas cr" );
		$cols = array();
		$cols [] = 'cr.fecha_envio AS fecha_envio';
		$cols [] = 'cr.estado AS estado';
		$cols [] = 'cr.idcartas_enviadas AS idcartas_enviadas';
		$cols [] = 'cr.instituciones_idinstituciones AS instituciones_idinstituciones';
		$cols [] = 'inst.razon_social AS razon_social';
		$cols [] = 'inst.idinstituciones AS idinstituciones';

		$this->db->select($cols);
		$this->db->join("instituciones inst","inst.idinstituciones=cr.instituciones_idinstituciones","inner");

		$this->db->where(array('cr.activo' => 'S'));

		if($idcuenta != ''){
			$this->db->where(array('cr.id_cuenta'=>$idcuenta)); 
		}

		if($id_inst != ''){
			$this->db->where(array('cr.instituciones_idinstituciones'=>$id_inst)); 
		}

		$this->db->order_by ( 'cr.fecha_crea DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function comparar_select($id_cuenta, $id_institutucion_c = ''){

    	$query = $this->db->select('instituciones_idinstituciones', 'id_cuenta');
    			 $this->db->where('id_cuenta', $id_cuenta);
  				 $this->db->where('instituciones_idinstituciones', $id_institutucion_c);
      			 $this->get();

    	$row = $query->row_array();  //returns a single row

    	if($row['instituciones_idinstituciones'] == $id_institutucion_c){

    		echo '<script>alert("!No se puede resgistrar!")</script>';

    	} else {
    		$this->db->insert( 'cartas_enviadas', $row );
    		echo'<script>alert("!Gracias por resgistrarte!")</script>';
    	}
  	}

	public function eliminar_carta($id){

		$data['activo'] = 'N';
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('idcartas_enviadas'=>$id))->update($this->_table, $data); 
	}
}

?>