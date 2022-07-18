<?php
class cuentas_juzgados_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '2_cuentas_juzgados';
		$this->primary_key = 'id';
		$this->alias = 'c';
		$this->field_posicion = '';
		$this->field_categoria = 'id_cuenta';
	}
	
	public function setup_validate()	{
		$this->validate = array(
			array(
                     'field'   => 'id_cuenta',
                     'label'   => 'Cuenta',
                     'rules'   => 'is_natural_no_zero|required'
                  ),
            array(
                     'field'   => 'id_juzgado',
                     'label'   => 'Juzgado',
                     'rules'   => 'is_natural_no_zero|required'
                  ),
            array(
                     'field'   => 'id_distrito',
                     'label'   => 'Distrito',
                     'rules'   => 'is_natural_no_zero|required'
                  ),
             array(
                     'field'   => 'id_rol',
                     'label'   => 'Rol',
                     'rules'   => 'is_natural_no_zero|required'
                  )
		);

	} 
	
	public function eliminar_juzgado($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
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
	
	/////////////////***LISTAR TABLA 2_cuentas_juzgados(HISTORIAL CAUSAS TIPO C) *****/////////
	
	public function get_cuentas_juzgados($idcuenta = '') {

	    $cols = array();
		$cols[] ='tri.tribunal as tribunal';
		$cols[] ='ju.jurisdiccion as jurisdiccion';
		$cols[] ='c.numero_rol as Rol';
		$cols[] ='c.id as Id';
		$cols[] ='a.anio as anio';
		$cols[] ='tc.tipo as tipo_causa';
			
		$this->db->select($cols);
		$this->db->from("2_cuentas_juzgados c");
		$this->db->join("s_tribunales tri","tri.id = c.id_juzgado","left");
		$this->db->join("jurisdiccion ju","ju.id = c.id_distrito","left");
		$this->db->join("anio a","a.id = c.anio","left");
		$this->db->join("tipo_causa tc","tc.id = c.tipo_causa","left");

		
		$this->db->where(array('c.activo' => 'S'));
		
		if($idcuenta != ''){
		$this->db->where(array('c.id_cuenta'=>$idcuenta));  
		}
		
		$this->db->order_by('c.fecha_crea DESC');
	
		$query = $this->db->get();
		return $query->result();
	
	}
	
	public function get_cuentas_etapas2($idcuenta = '') {

	   
	   /* $cols = array();
		$cols[] ='adm.nombres AS nombres';
		$cols[] ='adm.apellidos AS apellidos';
		$cols[] ='ce.fecha_etapa AS fecha_etapa';
		$cols[] ='ce.id AS id';
		$cols[] ='ce.posicion AS posicion';
		$cols[] ='ce.observaciones AS observaciones';
		$cols[] ='ce.obs_administrador AS obs_administrador';
		$cols[] ='adm2.nombres AS procurador_nombres';
		$cols[] ='adm2.apellidos AS procurador_apellidos';
		$cols[] ='e.etapa AS etapa';
		$cols[] ='e.dias_alerta AS dias_alerta';
		$cols[] ='ce.fecha_crea AS fecha_crea'; */
		
		
		/*nuevo 04-05-2015
		 $cols[] = 'c.fecha_etapa AS cuenta_fecha_etapa';
		 $cols[] = "DATEDIFF( NOW(),c.fecha_etapa) AS dias_alerta";
		 nuevo*/
		
		
		/* $this->db->select($cols);
		$this->db->from("2_cuentas_etapas ce");
		$this->db->join("s_etapas e","e.id=ce.id_etapa","left");
		$this->db->join("0_administradores adm","adm.id=ce.id_administrador","left");
		$this->db->join("0_administradores adm2","adm2.id=ce.user_crea","left");
		//nuevo 04-05-2015
		$this->db->join("0_cuentas c","c.id=ce.id_cuenta","left");
		
		$this->db->where(array('ce.activo' => 'S'));
		$this->db->where(array('ce.id_etapa !=' => '0'));
		
		if($idcuenta != ''){
		$this->db->where(array('ce.id_cuenta'=>$idcuenta));  
		}
		
		$this->db->order_by('ce.fecha_etapa DESC');
	
		$query = $this->db->get();
		$this->db->flush_cache();  */
		
		$cols = array();
		$cols[] ='adm.nombres AS nombres';
		$cols[] ='adm.apellidos AS apellidos';
		$cols[] ='ce.fecha_etapa AS fecha_etapa';
		$cols[] ='ce.id AS id';
		$cols[] ='ce.posicion AS posicion';
		$cols[] ='ce.observaciones AS observaciones';
		$cols[] ='ce.obs_administrador AS obs_administrador';
		$cols[] ='adm2.nombres AS procurador_nombres';
		$cols[] ='adm2.apellidos AS procurador_apellidos';
		$cols[] ='e.etapa AS etapa';
		$cols[] ='e.dias_alerta AS dias_alerta';
		$cols[] ='ce.fecha_crea AS fecha_crea';
		$cols[] ='c.fecha_asignacion AS fecha_asignacion';
		//$cols[] = "DATEDIFF( NOW(),c.fecha_etapa) AS dias_alerta";
			
		$this->db->select($cols);
		$this->db->from("2_cuentas_etapas ce");
		$this->db->join("s_etapas e","e.id=ce.id_etapa","left");
		$this->db->join("0_administradores adm","adm.id=ce.id_administrador","left");
		$this->db->join("0_administradores adm2","adm2.id=ce.user_crea","left");
		$this->db->join("0_cuentas c","c.id=ce.id_cuenta","left");
		
		$this->db->where(array('ce.activo' => 'S'));
		$this->db->where(array('ce.id_etapa !=' => '0'));
		if($idcuenta != ''){
		$this->db->where(array('ce.id_cuenta'=>$idcuenta));  
		}
		
		$this->db->order_by('ce.fecha_crea DESC');
	
		$query = $this->db->get();
		return $query->result();
	
		}

	public function get_cuentas_etapas_resumen($cols,$where,$group_by='e.id',$order_by='e.etapa ASC') {

	   		
		$this->db->select($cols);
		$this->db->from("2_cuentas_etapas ce");
		$this->db->join("s_etapas e","e.id=ce.id_etapa","left");
		$this->db->join("0_administradores adm","adm.id=ce.id_administrador","left");
		$this->db->join("0_administradores adm2","adm2.id=ce.user_crea","left");
		
		$this->db->where($where);		
		$this->db->order_by($order_by);		
		$this->db->group_by($group_by);		
	
		$query = $this->db->get();
		return $query->result();
	
}
	
}
?>