<?php
class Receptor_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct(){
		parent::__construct();
		$this->_table = '0_receptores';
		$this->primary_key = 'id';
		$this->alias = 'r';
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
	
	
	public function causas($idcuenta= '', $order_by = ''){

		$like = array();
		$where = array();
		
		$config = array('suffix'=>'');
		//$config['base_url'] = site_url().'/admin/alertas/etapas/';
		
		$fecha_asignacion = '';
		
		$cols[] = 'c.id_etapa AS id_etapa';
		$cols[] = 'm.codigo_mandante AS codigo_mandante';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'u.nombres AS usuarios_nombres';
		$cols[] = 'u.ap_pat AS usuarios_ap_pat';
		$cols[] = 'u.ap_mat AS usuarios_ap_mat';
		$cols[] = 'u.rut AS usuarios_rut';
		$cols[] = 'c.id AS cuentas_id';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.exorto AS exorto';		
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'p.fecha_asignacion AS fecha_asignacion_pagare';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = "DATEDIFF( NOW() , c.fecha_etapa ) AS dias_diferencia";
        $cols[] = 'e.etapa AS etapa';
		$cols[] = 'e.texto_alerta AS texto_alerta';
		$cols[] = 'e.dias_alerta AS dias_alerta';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos_adm';
		$cols[] = 'trib.tribunal AS tribunal_padre';
        $cols[] = 'tri.tribunal AS tribunal';	
		$cols[] = 'disE.tribunal as DistritoE';
        $cols[] = 'trie.tribunal as TribunalE';
		$cols[] = 'c.rolE';
		$cols[] = 'DATEDIFF( NOW() , c.fecha_asignacion ) AS dias_';
		$cols[] = 'DATEDIFF( NOW() , c.fecha_asignacion ) AS diass_';
		
		$having = '';
				
		/**/
		$this->db->distinct();
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante','left');
		//$this->db->join("2_cuentas_etapas ce", "ce.id_cuenta = c.id AND ce.activo='S' AND ce.id_etapa = c.id_etapa");	
		$this->db->join('s_etapas e', 'e.id = c.id_etapa','left');	
		$this->db->join('pagare p', 'p.idcuenta = c.id','left');
		$this->db->join('0_administradores adm','adm.id = c.id_procurador','left');
		$this->db->join("s_tribunales tri", "tri.id = c.id_tribunal","left");
		$this->db->join("s_tribunales trib", "trib.id = tri.padre","left");
		$this->db->join("s_tribunales trie", "trie.id = c.id_tribunal_ex","left");
		$this->db->join("s_tribunales disE", "disE.id = c.id_distrito_ex","left");
		
		if ($having!=''){
			$this->db->having($having);
		}
		
		if ($order_by!=''){
			$this->db->order_by($order_by);
		} else {
			$this->db->order_by('c.id');
		}
		
		$this->db->where(array('c.receptor'=>$idcuenta));
		
			
		
		$query = $this->db->get();
		return $query->result();
	}
	
	
	public function get_receptor(){
			
		$cols[] = 'r.id AS id';
		$cols[] = 'r.nombre AS nombre';
		$cols[] = 'r.appat AS ApePat';
		$cols[] = 'r.apmat AS ApeMat';
		$cols[] = 'r.rut AS rut';
		$cols[] = 'r.email AS email';
		$cols[] = 'r.direccion AS direccion';
		$cols[] = 'r.comuna AS comuna';
		$cols[] = 'r.ciudad AS ciudad';
		$cols[] = 'r.telefono AS telefono';
		$cols[] = 'r.celular AS celular';
		$cols[] = 'r.nombre_secretaria AS nombre_secretaria';
		$cols[] = 'r.fono_secre AS Telefono_secretaria';
		$cols[] = 'r.celu_secre AS celular_secretaria';		
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'jur.jurisdiccion AS jurisdiccion';
		
		$this->db->select($cols);
		$this->db->from("0_receptores r");
		$this->db->join("s_tribunales tr","tr.id=r.id_tribunal","left");
		$this->db->join("s_jurisdiccion jur","jur.id=tr.id_jurisdiccion","left");
		
		$this->db->where(array('r.activo'=>'S'));
		
			
		
		$query = $this->db->get();
		return $query->result();
	}
}
?>