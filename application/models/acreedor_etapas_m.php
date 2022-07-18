<?php
class Acreedor_Etapas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '2_acreedor_etapas';
		$this->primary_key = 'id';
		$this->alias = '2ae';
		$this->field_posicion = '';
		$this->field_categoria = 'id_acreedor';
		
	}
	public function setup_validate(){
		$this->validate = array(
			array(
                     'field'   => 'id_acreedor',
                     'label'   => 'Cuenta',
                     'rules'   => 'is_natural_no_zero|required'
                  ),
            array(
                     'field'   => 'id_etapa',
                     'label'   => 'Etapa',
                     'rules'   => 'is_natural_no_zero|required'
                  ),
            array(
                     'field'   => 'fecha_etapa_day',
                     'label'   => 'Día',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_etapa_month',
                     'label'   => 'Mes',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_etapa_year',
                     'label'   => 'Año',
                     'rules'   => 'is_natural_no_zero|valid_date[fecha_etapa_day,fecha_etapa_month]'
                  )
            );
	} 
	
	######################## */ revisar userdata('usuario_id') */ ######################
	public function eliminar_etapa_acreedor($id){
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
	
	//////////////////********	
	public function get_cuentas_etapas($idacreedor = '') {
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
		$cols[] ='e.estado AS etapa';
		$cols[] ='ce.fecha_crea AS fecha_crea';
			
		$this->db->select($cols);
		$this->db->from("2_acreedor_etapas ce");
		$this->db->join("estado_acreedor e","e.id=ce.id_etapa","left");
		$this->db->join("0_administradores adm","adm.id=ce.id_administrador","left");
		$this->db->join("0_administradores adm2","adm2.id=ce.user_crea","left");
		
		$this->db->where(array('ce.activo' => 'S'));
		$this->db->where(array('ce.id_etapa !=' => '0'));
		
		if($idacreedor != ''){
		$this->db->where(array('ce.id_acreedor'=>$idacreedor));  
		}
		
		$this->db->order_by('ce.fecha_etapa asc');
	
		$query = $this->db->get();
		$this->db->flush_cache();
		
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
		$cols[] ='e.estado AS etapa';
		$cols[] ='ce.fecha_crea AS fecha_crea';
			
		$this->db->select($cols);
		$this->db->from("2_acreedor_etapas ce");
		$this->db->join("estado_acreedor e","e.id=ce.id_etapa","left");
		$this->db->join("0_administradores adm","adm.id=ce.id_administrador","left");
		$this->db->join("0_administradores adm2","adm2.id=ce.user_crea","left");
		
		$this->db->where(array('ce.activo' => 'S'));
		$this->db->where(array('ce.id_etapa !=' => '0'));
		if($idacreedor != ''){
		$this->db->where(array('ce.id_acreedor'=>$idacreedor));  
		}
		
		$this->db->order_by('ce.fecha_etapa DESC,ce.fecha_crea DESC');
	
		$query = $this->db->get();
		return $query->result();
	}
	
	#######################################################################
    ###################### LISTADO ETAPAS JUICIO ###########################
    #######################################################################

	public function get_cuentas_etapas2($idacreedor = '') {
		
	 	######################################################
		############# LISTA ETAPAS ############################
		#####################################################

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
		$cols[] ='e.estado AS etapa';
		$cols[] ='sea.sub_estado_acreedor AS etapa2';
		//$cols[] ='e.dias_alerta AS dias_alerta';
		$cols[] ='ce.fecha_crea AS fecha_crea';
		//$cols[] ='c.fecha_asignacion AS fecha_asignacion';
		//$cols[] ='stado.estado AS estado';
		//$cols[] = "DATEDIFF( NOW(),c.fecha_etapa) AS dias_alerta";
			
		$this->db->select($cols);
		$this->db->from("2_acreedor_etapas ce");
		$this->db->join("estado_acreedor e","e.id=ce.id_etapa","left");
		$this->db->join("sub_estado_acreedor sea","sea.id=ce.id_etapa2","left");
		$this->db->join("0_administradores adm","adm.id=ce.id_administrador","left");
		$this->db->join("0_administradores adm2","adm2.id=ce.user_crea","left");
		$this->db->join("acreedor c","c.idacreedor=ce.id_acreedor","left");
		//$this->db->join("s_estado_cuenta stado","stado.id=e.id_estado","left");
		
		$this->db->where(array('ce.activo' => 'S'));
		$this->db->where(array('ce.id_etapa !=' => '0'));
		if($idacreedor != ''){
		$this->db->where(array('ce.id_acreedor'=>$idacreedor));  
		}
		
		$this->db->order_by('ce.fecha_etapa DESC, ce.id DESC');
	
		$query = $this->db->get();
		return $query->result();
	}

	public function get_cuentas_etapas_resumen($cols,$where,$group_by='e.id',$order_by='e.estado ASC') {
	
		$this->db->select($cols);
		$this->db->from("2_acreedor_etapas ce");
		$this->db->join("estado_acreedor e","e.id=ce.id_etapa","left");
		$this->db->join("0_administradores adm","adm.id=ce.id_administrador","left");
		$this->db->join("0_administradores adm2","adm2.id=ce.user_crea","left");
		
		$this->db->where($where);		
		$this->db->order_by($order_by);		
		$this->db->group_by($group_by);		
	
		$query = $this->db->get();
		return $query->result();
	}	

	public function get_data_cuentas_etapas($id){
		$cols = array();
		$cols[] ='ce.id AS id';
		$cols[] ='ce.id_acreedor AS id_acreedor';
		$cols[] ='ce.id_etapa AS id_etapa';
		$cols[] ='ce.id_etapa2 AS id_etapa2';
		$cols[] ='ce.fecha_etapa AS fecha_etapa';
		$cols[] ='ce.observaciones AS observaciones';

		$this->db->select($cols);
		$this->db->from("2_acreedor_etapas ce");
		$this->db->where(array('ce.id' => $id));
		$query = $this->db->get();
		return $query->result();
	}

	public function get_ultima_etapa($idacreedor){
		$cols = array();
		$cols[] ='ce.id AS id';
		$cols[] ='e.id AS id_etapa';
		$cols[] ='e.estado AS etapa';
		$cols[] ='se.id AS id_etapa2';
		$cols[] ='ce.fecha_etapa AS fecha_etapa';
		$cols[] ='ce.observaciones AS observaciones';
		$cols[] ='se.sub_estado_acreedor AS sub_estado_acreedor';

		$this->db->select($cols);
		$this->db->from("2_acreedor_etapas ce");
		$this->db->join("estado_acreedor e","e.id=ce.id_etapa");
		$this->db->join("sub_estado_acreedor se","se.id_estado_acreedor=e.id", "left");
		$this->db->where(array('ce.id_acreedor'=>$idacreedor, 'ce.activo'=>'S'));
		$this->db->order_by('ce.id DESC');
		$query = $this->db->get();
		return $query->result();
	}
}
?>