<?php
class Hist_Cuentas_Pagos_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'hist_2_cuentas_pagos';
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
	}	
	
	public function get_pagos($cols,$where){
			$this->db->select($cols);		
			$this->db->from('hist_2_cuentas_pagos cp');		
			$this->db->join("0_comprobantes comp", "comp.id = cp.id_comprobante","left");		
			$this->db->where($where);		
			$this->db->order_by('cp.fecha_pago','ASC');		
			$this->db->group_by('cp.id');		
			$query = $this->db->get();		
			return $query->result();	}
}
?>