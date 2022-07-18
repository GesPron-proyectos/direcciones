<?php
class Cuentas_Pagos_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '2_cuentas_pagos';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->field_categoria = 'id_cuenta';
		
	}
	public function setup_validate(){
		$this->validate = array(
            array(
                     'field'   => 'fecha_vencimiento_day',
                     'label'   => 'Día',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_vencimiento_month',
                     'label'   => 'Mes',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_vencimiento_year',
                     'label'   => 'Año',
                     'rules'   => 'is_natural_no_zero|valid_date[fecha_vencimiento_day,fecha_vencimiento_month]'
                  ),
            array(
                     'field'   => 'fecha_pago_day',
                     'label'   => 'Día',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_pago_month',
                     'label'   => 'Mes',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_pago_year',
                     'label'   => 'Año',
                     'rules'   => 'is_natural_no_zero|valid_date[fecha_pago_day,fecha_pago_month]'
                  )
                  ,
            array(
                     'field'   => 'monto_pagado',
                     'label'   => 'Monto Pagado',
                     'rules'   => 'trim|required|is_natural_no_zero'
            	  )
            );
	} 
	public function eliminar($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
		$pago = $this->get_by(array('id'=>$id));
		$this->db->query('UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new - ('.$pago->monto_pagado.') WHERE id='.$pago->id_cuenta );
	}	
	public function get_pagos($cols,$where,$group_by='cp.id',$order_by='cp.fecha_pago ASC'){		
		$this->db->select($cols);		
		$this->db->from('2_cuentas_pagos cp');		
		$this->db->join("0_comprobantes comp", "comp.id = cp.id_comprobante","left");
		$this->db->join("0_cuentas c", "c.id = cp.id_cuenta");
		$this->db->join("0_usuarios usr", "usr.id = c.id_usuario");
		$this->db->join("s_estado_cuenta sec", "sec.id = c.id_estado_cuenta");						
		$this->db->where($where);		
		$this->db->order_by($order_by);		
		$this->db->group_by($group_by);		
		$query = $this->db->get();		
		return $query->result();	
	}
	
	public function get_cuentas_pagos($idcuenta){
				
			$this->db->from('2_cuentas_pagos cp');	
            $cols = array();
			$cols [] = 'cp.fecha_vencimiento AS fecha_vencimiento';
			$cols [] = 'cp.fecha_pago AS fecha_pago';
			$cols [] = 'cp.monto_pagado AS monto_pagado';
			$cols [] = 'cp.monto_remitido AS monto_remitido';
			$cols [] = 'cp.honorarios AS honorarios';
			$cols [] = 'cp.saldo AS saldo';
			$cols [] = 'cp.forma_pago AS forma_pago';
			$cols [] = 'cp.id_acuerdo_pago AS id_acuerdo_pago';
		    $cols [] = 'cp.n_comprobante_interno AS n_comprobante_interno';
		    
		    $this->db->select($cols);
		    
	        if($idcuenta != ''){
		    $this->db->where(array('cp.id_cuenta'=>$idcuenta));  
		    }
		    
		    //$this->db->join("0_comprobantes comp", "comp.id = cp.id_comprobante","left");		
			//$this->db->where($where);		
			$this->db->order_by('cp.fecha_pago','ASC');		
			$this->db->group_by('cp.id');		
			$query = $this->db->get();		
			return $query->result();
		}

		
public function get_monto_remitido($where=array()){
		
		
		$cols = array();
		 if(count($where)>0){
		if(count($where>0)){$this->db->where($where);}
		}
		$cols[] = 'SUM(pag2.monto_remitido) AS monto_remitido';
		$this->db->select($cols);
		$this->db->from("2_cuentas_pagos pag2");
		$this->db->group_by('pag2.id');
        $query = $this->db->get();
		return  $query->result();
	}
		
		
	}
?>