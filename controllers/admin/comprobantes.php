<?php
class Comprobantes extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
		
	public function Comprobantes() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'session' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'cuentas_m' ); 
		$this->load->model ( 'comprobantes_m' );
		$this->load->helper ( 'date_html_helper' );
		//$this->output->enable_profiler(TRUE);
		/*seters*/
		$this->data['current'] = 'comprobantes';
		$this->data['plantilla'] = 'comprobantes/';
		$this->data['lists'] = array();
		
		$this->data['forma_pagos'] = array('TF'=>'Transferencia','DP'=>'Depósito','CH'=>'Cheque');
	}
	
	function voucher($id,$output=''){
		$query =$this->db->select('comp.id AS id, comp.verify_sign AS verify_sign,comp.monto AS monto,comp.forma_pago AS forma_pago, comp.fecha_pago AS fecha_pago, cta.id AS id_cuenta, cta.activo AS activo, cta.publico AS publico, cta.rol AS rol, trib.tribunal AS tribunal, dist.tribunal AS distrito, cta.id_procurador, usr.nombres AS nombres,usr.ap_pat AS ap_pat,usr.ap_mat AS ap_mat, usr.rut AS rut, mand.razon_social, tip.tipo AS tipo_producto, cta.fecha_asignacion AS fecha_asignacion, cta.monto_demandado AS monto_demandado, cta.monto_deuda AS monto_deuda, cta.id_estado_cuenta AS id_estado_cuenta, cta.id_mandante AS field_categoria')
							 ->join("0_cuentas cta", "cta.id = comp.id_cuenta")					 
							 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
							 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	
							 ->join("s_tribunales trib", "trib.id = cta.id_tribunal","left")
							 ->join("s_tribunales dist", "dist.id = cta.id_distrito","left")	
							 ->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")		 
							 ->where(array('comp.id'=>$id))
			 				 ->get("0_comprobantes comp");
		$a = $query->result();
		if (count($a)==1){
			foreach ($a as $key=>$val){
				$this->data['lists'] = $val;
			}
		}
		
		$this->data['plantilla'] = 'comprobantes/print'.$output; 
		$this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );
	}
	function get_cuenta($id){
		$query =$this->db->select('cta.id AS id, cta.activo AS activo, cta.publico AS publico, cta.rol AS rol, trib.tribunal AS tribunal, dist.tribunal AS distrito,cta.id_procurador, usr.nombres AS nombres,usr.ap_pat AS ap_pat,usr.ap_mat AS ap_mat, usr.rut AS rut, mand.razon_social, tip.tipo AS tipo_producto, cta.fecha_asignacion AS fecha_asignacion, cta.monto_demandado AS monto_demandado, cta.monto_deuda AS monto_deuda, cta.id_estado_cuenta AS id_estado_cuenta, cta.id_mandante AS field_categoria')
							 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
							 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	
							 ->join("s_tribunales trib", "trib.id = cta.id_tribunal","left")
							 ->join("s_tribunales dist", "dist.id = cta.id_distrito","left")	
							 ->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")		 
							 ->where(array('cta.id'=>$id))
							 ->order_by("id_mandante", "desc")
							 ->order_by("cta.fecha_asignacion", "desc")
							 ->group_by("cta.id")
			 				 ->get("0_cuentas cta");
		$a = $query->result();
		if (count($a)==1){
			foreach ($a as $key=>$val){
				return $val;
			}
		}
	}
	function form($action='',$id=''){
		$id_cuenta = '';
		if ($id=='' && isset($_REQUEST['rut']) && !isset($_REQUEST['id_cuenta'])){
			$aa = $this->usuarios_m->get_by(array('rut'=>$_REQUEST['rut'],'activo'=>'S'));
			if (count($aa)>0){
				$bb = $this->get_cuenta($aa->id);
				if (count($bb)==1){
					$this->data['cuenta'] = $bb;
				}
			}
		}
		if ($id=='' && isset($_REQUEST['id_cuenta'])){
			$bb = $this->get_cuenta($_REQUEST['id_cuenta']);
			$this->data['cuenta'] = $bb;
		}
		if (count($this->data['cuenta']) || $id!=''){
			$this->data['id_cuenta'] = $this->data['cuenta']->id;
			//echo '--'.$this->data['id_cuenta'];
			$view='form';
			$this->data['plantilla'].= $view;
			
			$fields_save = array();
			$fields_save['id_cuenta']=$this->data['cuenta']->id;
			$fields_save['verify_sign'] = uniqid();
			if (isset($_POST['monto'])){ $fields_save['monto']=$_POST['monto'];}
			if (isset($_POST['forma_pago'])){ $fields_save['forma_pago']=$_POST['forma_pago'];}
	
			$fecha_pago = '';
			if (isset($_POST['fecha_pago_year']) && isset($_POST['fecha_pago_month']) && isset($_POST['fecha_pago_day'])){
				$fecha_pago=$_POST['fecha_pago_year'].'-'.$_POST['fecha_pago_month'].'-'.$_POST['fecha_pago_day'];
				$fields_save['fecha_pago']=$fecha_pago;
			}
			
			/*guardar*/		
			if ($action=='guardar'){
				$this->comprobantes_m->setup_validate();
				if (!$this->comprobantes_m->save_default($fields_save,$id)){}
				else{if (empty($id)){$id=$this->db->insert_id();} redirect('admin/comprobantes/form/editar/'.$id);};
			}
			echo validation_errors();
			if (!empty($id)){$this->data['lists']=$this->comprobantes_m->get_by(array('id'=>$id));}
			if ($id!=''){
				$bb = $this->get_cuenta($this->data['lists']->id_cuenta);
				$this->data['cuenta'] = $bb;
			}
			
			$this->load->view ( 'backend/index', $this->data );
		}
	}
	function gen($action,$id){$this->index($action,$id);}
	function index($action='',$id='') {
		$view='list';
		$this->data['plantilla'].= $view;
		$this->show_tpl = TRUE;
		
		if ($action=='actualizar'){
			$this->comprobantes_m->update($id,$_POST);
			$this->show_tpl = FALSE;
		}
		if ($action=='up' or $action=='down'){
			$this->comprobantes_m->move_up_down($_POST['posicion'],$id,$action,$_POST['field_categoria']);
			$this->show_tpl = FALSE;
		}
		if ($view=='list'){
			$config['uri_segment'] = '4';
			$this->data['current_pag'] = $this->uri->segment(4);
			$where = array();
			$where['comp.activo'] = 'S';
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/comprobantes/index/';
			
			$query_total =$this->db->join("0_cuentas cta", "cta.id = comp.id_cuenta")					 
							 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
							 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	
							 ->join("s_tribunales trib", "trib.id = cta.id_tribunal","left")	
							 ->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")
							 ->where($where)		
			 				 ->get("0_comprobantes comp");
			$total_rows = $query_total->result();
			
			$config['total_rows'] = count($total_rows);
	    	$config['per_page'] = '30'; 
	    	$this->pagination->initialize($config);
	    	
			/*listado*/
			$query =$this->db->select('comp.id AS id, cta.id AS id_cuenta, comp.monto AS monto, comp.fecha_pago AS fecha_pago, cta.id AS id_cuenta, cta.activo AS activo, cta.publico AS publico, cta.rol AS rol, trib.tribunal AS tribunal,cta.id_procurador, usr.nombres AS nombres,usr.ap_pat AS ap_pat,usr.ap_mat AS ap_mat, usr.rut AS rut, mand.razon_social, tip.tipo AS tipo_producto, cta.fecha_asignacion AS fecha_asignacion, cta.monto_demandado AS monto_demandado, cta.monto_deuda AS monto_deuda, cta.id_estado_cuenta AS id_estado_cuenta, cta.id_mandante AS field_categoria')
							 ->join("0_cuentas cta", "cta.id = comp.id_cuenta")					 
							 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
							 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	
							 ->join("s_tribunales trib", "trib.id = cta.id_tribunal","left")	
							 ->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")
							 ->where($where)	
							 ->order_by('comp.fecha_pago','DESC')	
			 				 ->get("0_comprobantes comp",$config['per_page'],$this->data['current_pag']);
			$this->data['lists']=$query->result();
			/*posiciones*/
			$query = $this->db->select('id AS field_categoria,MAX(posicion) AS max_posicion, MIN(posicion) AS min_posicion')->get("0_mandantes");
			if ($query->num_rows()>0){foreach ($query->result() as $key=>$val){
				$this->data['posiciones'][$val->field_categoria]['max_posicion']=$val->max_posicion;
				$this->data['posiciones'][$val->field_categoria]['min_posicion']=$val->min_posicion;
				$this->data['posiciones'][$val->field_categoria]['field_categoria']=$val->field_categoria;
			}}
			if (!$this->show_tpl){ $this->data['plantilla'] = 'comprobantes/list_tabla'; $this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );}
		}
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}

	}
}

?>