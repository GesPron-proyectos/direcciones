<?php
class Hist_Cuentas_Etapas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'hist_2_cuentas_etapas';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->field_categoria = 'id_cuenta';
		
	}
	public function setup_validate(){
		$this->validate = array(
			array(
                     'field'   => 'id_cuenta',
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
	public function eliminar($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
	}
}
?>