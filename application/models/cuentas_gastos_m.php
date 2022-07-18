<?php
class Cuentas_Gastos_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '2_cuentas_gastos';
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
            /*array(
                     'field'   => 'n_boleta',
                     'label'   => 'Nº Boleta',
                     'rules'   => 'trim|required|is_numeric'
            	  ),*/
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
	public function eliminar_gasto($id){
		$data['activo'] = 'N';
		$data['fecha_elim'] = date('Y-m-d H:i:s');
		$data['user_elim'] = $this->session->userdata('usuario_id');
		$this->db->where(array('id'=>$id))->update($this->_table, $data); 
		$gasto = $this->get_by(array('id'=>$id));
		$this->db->query('UPDATE 0_cuentas SET monto_gasto_new = monto_gasto_new - ('.$gasto->monto.') WHERE id='.$gasto->id_cuenta );
	}
	public function get_gastos_cuentas($idcuenta){
		
		$cols = array();
		$cols[] ='gastos.fecha AS fecha';
		$cols[] ='gastos.fecha_ingreso_banco AS fecha_ingreso_banco';
		$cols[] ='gastos.fecha_recepcion AS fecha_recepcion';
		$cols[] ='gastos.id_estado_pago AS id_estado_pago';
		$cols[] ='gastos.item_gasto AS item_gasto';
		$cols[] ='d.nombre AS diligencia';
		$cols[] = 'gastos.n_boleta AS n_boleta';
		$cols[] = 'gastos.id AS id';
		$cols[] = 'r.nombre AS nombre_receptor';
		$cols[] = 'gastos.monto AS monto';
		$cols[] = 'gastos.retencion AS retencion';
		$cols[] = 'c.rol AS rol';
		
		
		$this->db->select($cols);
		$this->db->from("2_cuentas_gastos gastos");
		$this->db->join("diligencia d","d.id=gastos.id_diligencia","left");
		$this->db->join("0_receptores r","r.id=gastos.id_receptor","left");
		$this->db->join("0_cuentas c","c.id=gastos.id_cuenta","left");
		
		
		
		$this->db->where(array('gastos.activo' => 'S'));
		$this->db->where(array('gastos.id_cuenta'=>$idcuenta));  
		
		$this->db->order_by('gastos.fecha DESC');
		$query = $this->db->get();
		return $query->result();
	
	}
	
	

	
	
	
	
	
}
?>