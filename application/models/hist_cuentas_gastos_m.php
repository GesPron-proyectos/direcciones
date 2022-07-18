<?php
class Hist_Cuentas_Gastos_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'hist_2_cuentas_gastos';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->field_categoria = 'id_cuenta';
		
	}
	public function setup_validate(){
		$this->validate = array(
            array(
                     'field'   => 'fecha_day',
                     'label'   => 'Día',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_month',
                     'label'   => 'Mes',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_year',
                     'label'   => 'Año',
                     'rules'   => 'is_natural_no_zero|valid_date[fecha_day,fecha_month]'
                  ),
            array(
                     'field'   => 'n_boleta',
                     'label'   => 'Nº Boleta',
                     'rules'   => 'trim|required|is_numeric'
            	  ),
            array(
                     'field'   => 'rut_receptor',
                     'label'   => 'Rut Receptor',
                     'rules'   => 'trim|required|is_rut'
            	  ),
            array(
                     'field'   => 'nombre_receptor',
                     'label'   => 'Nombre Receptor',
                     'rules'   => 'trim|required'
            	  ),
            array(
                     'field'   => 'monto',
                     'label'   => 'Monto',
                     'rules'   => 'trim|required|is_natural_no_zero'
            	  ),
            array(
                     'field'   => 'retencion',
                     'label'   => 'Retención',
                     'rules'   => 'trim|required|is_natural_no_zero'
            	  ),
            array(
                     'field'   => 'descripcion',
                     'label'   => 'Descripción',
                     'rules'   => 'trim|required'
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