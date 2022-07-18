<?php
class Hist_Cuentas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = 'hist_0_cuentas';
		$this->primary_key = 'id';
		$this->field_posicion = 'posicion';
		$this->field_categoria = 'id_mandante';
		
	}
	public function setup_validate_acuerdo(){
		$this->validate = array(
			array(
                     'field'   => 'id_acuerdo_pago',
                     'label'   => 'Convenio de Pago',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'n_cuotas',
                     'label'   => 'Nº de cuotas',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'valor_cuota',
                     'label'   => 'Valor de la cuota',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'dia_vencimiento_pago',
                     'label'   => 'Vencimiento pago',
                     'rules'   => 'is_natural_no_zero'
                  ) 
            );
	}
	public function setup_validate(){
		$this->validate = array(
			array(
                     'field'   => 'id_usuario',
                     'label'   => 'Demandado',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'id_mandante',
                     'label'   => 'Mandante',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_asignacion_day',
                     'label'   => 'Día',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_asignacion_month',
                     'label'   => 'Mes',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_asignacion_year',
                     'label'   => 'Año',
                     'rules'   => 'is_natural_no_zero|valid_date[fecha_asignacion_day,fecha_asignacion_month]'
                  ),
            array(
                     'field'   => 'fecha_inicio_day',
                     'label'   => 'Día',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_inicio_month',
                     'label'   => 'Mes',
                     'rules'   => 'is_natural_no_zero'
                  ),
            array(
                     'field'   => 'fecha_inicio_year',
                     'label'   => 'Año',
                     'rules'   => 'is_natural_no_zero|valid_date[fecha_inicio_day,fecha_inicio_month]'
                  )
            );
	} 
}
?>