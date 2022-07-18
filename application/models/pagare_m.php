<?php
class pagare_m extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'pagare';
		$this->primary_key = 'idpagare';
		$this->alias = 'pa';
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
	
	
	
public function get_pagares_cuentas($idcuenta) {

	    $cols = array();
		$cols[] ='tp.tipo AS tipo';
		$cols[] ='p.idpagare AS id';
		$cols[] ='p.n_pagare AS n_pagare';
		$cols[] ='p.fecha_asignacion AS fecha_asignacion';
        $cols[] ='p.fecha_suscripcion AS fecha_suscripcion';
		$cols[] ='p.fecha_vencimiento AS fecha_vencimiento';
		$cols[] ='p.monto_deuda AS monto_deuda';
		$cols[] ='p.tasa_interes AS tasa_interes';
		$cols[] ='p.tasa_interes_anual AS tasa_interes_anual';
		$cols[] ='p.numero_cuotas AS numero_cuotas';
		$cols[] ='p.valor_primera_cuota AS valor_primera_cuota';
		$cols[] ='p.valor_ultima_cuota AS valor_ultima_cuota';
		$cols[] ='p.vencimiento_primera_cuota AS vencimiento_primera_cuota';
		$cols[] ='p.vencimiento_restantes_cuotas AS vencimiento_restantes_cuotas';
		$cols[] ='p.nombre_aval AS nombre_aval';
		$cols[] ='p.ultima_cuota_pagada AS ultima_cuota_pagada';
		$cols[] ='p.fecha_pago_ultima_cuota AS fecha_pago_ultima_cuota';
		$cols[] ='p.valor_ultima_cuota_pagada AS valor_ultima_cuota_pagada';
		$cols[] ='p.saldo_deuda AS saldo_deuda';
		
		$cols[] = 'DATEDIFF(NOW(),p.fecha_vencimiento) AS dias_transcurridos';
		$cols[] = "DATEDIFF( p.fecha_asignacion,  NOW() ) AS diferencia_dias";
		$cols[] = "DATEDIFF( p.fecha_asignacion,  cp.fecha_pago ) AS diferencia_dias_primer_pago";		
		$this->db->select($cols);
		$this->db->from("pagare p");
		$this->db->join("s_tipo_productos tp","tp.id=p.id_tipo_producto","left");
		$this->db->join("2_cuentas_pagos cp", "cp.id_cuenta = p.idcuenta AND cp.activo='S' AND `cp`.`id` = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=p.idcuenta AND psp.activo='S' ORDER BY psp.fecha_pago ASC LIMIT 0,1)","left");
		
		
		$this->db->where(array('p.activo' => 'S'));
		$this->db->where(array('p.idcuenta'=>$idcuenta));  
		
		$this->db->order_by('p.fecha_asignacion DESC');
		$query = $this->db->get();
		return $query->result();
	
}
	
     public function eliminar_pagare($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('idpagare'=>$id))->update($this->_table, $data); 
		$pagare = $this->get_by(array('idpagare'=>$id));
		$monto_deuda = 0;
		if (is_real($pagare->monto_deuda) || is_numeric($pagare->monto_deuda)){ $monto_deuda = $pagare->monto_deuda; }
		$this->db->query('UPDATE 0_cuentas SET monto_deuda = monto_deuda - ('.$monto_deuda.') WHERE id='.$pagare->idcuenta );
		
	}
}

?>