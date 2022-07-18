<?php
class Historial extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
		
	public function Historial() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		$this->load->helper ( 'date_html_helper' );
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'cuentas_historial_m' );
		$this->load->model ( 'nodo_m' );
		//$this->output->enable_profiler(TRUE);
		/*seters*/
		$this->data['current'] = 'historial';
		$this->data['plantilla'] = 'historial/';
		$this->data['lists'] = array();
		$this->data['current_pag'] = '';
		
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );
	}
	
	/*function form($action='',$id=''){
		$view='form';
		$this->data['plantilla'].= $view;
		
	
		if ($action=='guardar'){
			$fecha=$_POST['fecha_year'].'-'.$_POST['fecha_month'].'-'.$_POST['fecha_day'];
			$fields_save = array (
				'id_cuenta' => $_POST ['id_cuenta'], 
				'fecha' => $fecha, 
				'historial' => $_POST ['historial']
				);
			$this->cuentas_historial_m->setup_validate();
			if (!$this->cuentas_historial_m->save_default($fields_save,$id)){}
			else{if (empty($id)){$id=$this->db->insert_id();} redirect('admin/historial/form/editar/'.$id);};
		}
		
		$this->data['cuentas'] = array();
		$a=$this->cuentas_m->order_by("id","ASC")->get_all();
		
		$query =$this->db->select("cta.id AS id, usr.nombres AS nombres, usr.ap_pat AS ap_pat, usr.rut AS rut")
							 ->where("cta.activo","S")
							 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
							 ->order_by("usr.rut", "ASC")
			 				 ->get("0_cuentas cta");
		$a=$query->result();
		
		$this->data['cuentas'][0]='Seleccionar';
		foreach ($a as $obj) {$this->data['cuentas'][$obj->id] = $obj->rut.' '.$obj->nombres.' '.$obj->ap_pat;}	
		
		if (!empty($id)){$this->data['lists']=$this->cuentas_historial_m->get_by(array('id'=>$id));}

		$this->load->view ( 'backend/index', $this->data );
	} */
	function gen($action,$id){$this->index($action,$id);}
	function index($action='',$id='') {
		$view='list';
		$this->data['plantilla'].= $view;
		$config['uri_segment'] = '4';
		$where = array();
		//$sql_where = "";

		if (isset($_REQUEST['rut'])){if ($_REQUEST['rut']>0){ 
	    			$where["u.rut"] = $_REQUEST['rut'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'rut='.$_REQUEST['rut'];
	    		}}
	    		
	      if ($this->input->post('rango')!='' && $this->input->post('rango')!='0'){
    			$date = explode(' - ',$this->input->post('rango'));
    			$from = date("Y-m-d", strtotime(trim($date[0])));
				$to = date("Y-m-d", strtotime(trim($date[1])));
				$where = "(`fecha` BETWEEN '".$from."' AND '".$to."')";
	      }
	      		
	    		
		
		if ($action=='actualizar'){
			$this->historial_m->update($id,$_POST);
			$this->show_tpl = FALSE;
		}
		if ($action=='up' or $action=='down'){
			$this->historial_m->move_up_down($_POST['posicion'],$id,$action,$_POST['field_categoria']);
			$this->show_tpl = FALSE;
		}
		if ($view=='list'){
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/historial/index/';
			$config['uri_segment'] = '4';
			
			$pag = $this->db->select("ch.id as ch,ch.activo as activo,adm.nombres AS nombres,adm.apellidos AS apellidos,ch.posicion as posicion,ch.public AS publico,ch.fecha as fecha,ch.historial as historial,c.rol as rol,ch.fecha as fecha,ch.id_cuenta as id_cuenta,u.rut as rut,ch.id_etapa as idetapa,e.etapa as etapa,ch.id AS field_categoria")
			   			    ->where($where)
							->where("ch.activo","S")
                            ->order_by("ch.fecha", "desc")
							->join('0_cuentas c', 'c.id = ch.id_cuenta')
							->join('0_usuarios u', 'u.id = c.id_usuario')
							->join('0_administradores adm', 'adm.id = ch.user_crea')
							->join('s_etapas e', 'e.id = ch.id_etapa','left')
			 				->get("2_cuentas_historial ch",$config['per_page'],$this->data['current_pag']);
			 				// $this->db->select($cols); 
			 				 
			
	    	$config['total_rows'] = count($pag->result());//$this->db->where("activo","S")->count_all_results("2_cuentas_historial");
	    	$config['per_page'] = '100'; //$config['num_links'] = '10';
	    	$this->data['current_pag'] = $this->uri->segment(4);
			/*listado*/
			
	    	//$cols = array();
	    	//$cols[] = 'SELECT * FROM ch.2_cuentas_historial WHERE'.$sql_where;
	    	$query =$this->db->select("ch.id as ch,ch.activo as activo,adm.nombres AS nombres,adm.apellidos AS apellidos,ch.posicion as posicion,ch.public AS publico,ch.fecha as fecha,ch.historial as historial,c.rol as rol,ch.fecha as fecha,ch.id_cuenta as id_cuenta,u.rut as rut,ch.id_etapa as idetapa,e.etapa as etapa,ch.id AS field_categoria")
							  ->where($where)
							  ->where("ch.activo","S")
                              ->order_by("ch.fecha", "desc")
							 ->join('0_cuentas c', 'c.id = ch.id_cuenta')
							 ->join('0_usuarios u', 'u.id = c.id_usuario')
							 ->join('0_administradores adm', 'adm.id = ch.user_crea')
							 ->join('s_etapas e', 'e.id = ch.id_etapa','left')
			 				 ->get("2_cuentas_historial ch",$config['per_page'],$this->data['current_pag']);
			 				// $this->db->select($cols); 
			 				 $this->data['lists']=$query->result();
							 //echo $this->db->last_query();	
			/*posiciones*/
			$query = $this->db->select('id AS field_categoria,MAX(posicion) AS max_posicion, MIN(posicion) AS min_posicion')->get("2_cuentas_historial");
			if ($query->num_rows()>0){foreach ($query->result() as $key=>$val){
				$this->data['posiciones'][$val->field_categoria]['max_posicion']=$val->max_posicion;
				$this->data['posiciones'][$val->field_categoria]['min_posicion']=$val->min_posicion;
				$this->data['posiciones'][$val->field_categoria]['field_categoria']=$val->field_categoria;
			}}
			$this->data['total']=$config['total_rows'];
			
			$this->load->library('pagination');
	    	$this->pagination->initialize($config);
			
			if (!$this->show_tpl){ $this->data['plantilla'] = 'historial/list_tabla'; $this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );}
		}

		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}

	}
	
	function aux($id=''){


		$this->output->set_status_header('404');
		return false;


		//$this->output->enable_profiler(TRUE);
		//http://www.servicobranza.cl/app/index.php/admin/historial/aux
		$this->db->select('id');
		$this->db->where('id_estado_cuenta = 4 OR id_estado_cuenta = 5'); 

		$query = $this->db->get('0_cuentas')->result();
		$i = 0;
		$cuentaspagos = 0;
		$cuentashistorial = 0;
		$cuentasgastos = 0;
		$cuentasetapas = 0;
		foreach($query as $result)
		{
				
				//INSERT INTO dues_storage SELECT * FROM dues WHERE id=5;
				//2_cuentas_pagos
					$this->db->select('id');
					$this->db->where_in( 'id_cuenta', $result->id ); 
					$cuentas_pagos = $this->db->get('2_cuentas_pagos')->result();
					foreach($cuentas_pagos as $regs1)
					{
						$this->db->query("INSERT INTO hist_2_cuentas_pagos SELECT * FROM 2_cuentas_pagos WHERE id = ".$regs1->id.";");
						$this->db->query("DELETE FROM 2_cuentas_pagos WHERE id = ".$regs1->id.";");
						$cuentaspagos++;
					}
				
				//2_cuentas_historial
					$this->db->select('id');
					$this->db->where_in( 'id_cuenta', $result->id ); 
					$cuentas_historial = $this->db->get('2_cuentas_historial')->result();
					foreach($cuentas_historial as $regs2)
					{
						$this->db->query("INSERT INTO hist_2_cuentas_historial SELECT * FROM 2_cuentas_historial WHERE id = ".$regs2->id.";");
						$this->db->query("DELETE FROM 2_cuentas_historial WHERE id = ".$regs2->id.";");
						$cuentashistorial++;
					}
				
				//2_cuentas_gastos
					$this->db->select('id');
					$this->db->where_in( 'id_cuenta', $result->id ); 
					$cuentas_gastos = $this->db->get('2_cuentas_gastos')->result();
					foreach($cuentas_gastos as $regs3)
					{
						$this->db->query("INSERT INTO hist_2_cuentas_gastos SELECT * FROM 2_cuentas_gastos WHERE id = ".$regs3->id.";");
						$this->db->query("DELETE FROM 2_cuentas_gastos WHERE id = ".$regs3->id.";");
						$cuentasgastos++;
					}
				
				//2_cuentas_etapas
					$this->db->select('id');
					$this->db->where_in( 'id_cuenta', $result->id ); 
					$cuentas_etapas = $this->db->get('2_cuentas_etapas')->result();
					foreach($cuentas_etapas as $regs4)
					{
						$this->db->query("INSERT INTO hist_2_cuentas_etapas SELECT * FROM 2_cuentas_etapas WHERE id = ".$regs4->id.";");
						$this->db->query("DELETE FROM 2_cuentas_etapas WHERE id = ".$regs4->id.";");
						$cuentasetapas++;
					}
				
				
				//0_cuentas
				$this->db->query("INSERT INTO hist_0_cuentas SELECT * FROM 0_cuentas WHERE id = ".$result->id.";");
				$this->db->query("DELETE FROM 0_cuentas WHERE id = ".$result->id.";");
				
				$i++;
		}
		
		// INICIO INSERT DB log_crontab
			$data = array(
			   'cuentas' => $i,
			   'cuentas_etapas' => $cuentasetapas,
			   'cuentas_gastos' => $cuentasgastos,
			   'cuentas_historial' => $cuentashistorial,
			   'cuentas_pagos' => $cuentaspagos,
			   'fecha' => date('Y-m-d H:i:s')
			);
			$this->db->insert('log_crontab', $data);
		// FIN INSERT DB log_crontab
		
	/*
	
		
				$strngids = "";
		foreach($query as $result)
		{
			//print_r($result);echo "<br>";
			if( strlen($strngids) == 0 )
				$strngids = $result->id;
			else $strngids .= ",".$result->id; 
			$i++;
		}
		$strngids = "(".$strngids.")";
	//	echo $strngids."<br><br><br>";
	//	echo $i."<br><br><br>";
		
		
		if($strngids == "()")
			$strngids = "(0)";
		//INICIO CREACION TABLAS CLON
			//CREATE TABLE hist_0_cuentas LIKE 0_cuentas; INSERT hist_0_cuentas SELECT * FROM 0_cuentas;
			//CREATE TABLE hist_2_cuentas_etapas LIKE 2_cuentas_etapas; INSERT hist_2_cuentas_etapas SELECT * FROM 2_cuentas_etapas;
			//CREATE TABLE hist_2_cuentas_gastos LIKE 2_cuentas_gastos; INSERT hist_2_cuentas_gastos SELECT * FROM 2_cuentas_gastos;
			//CREATE TABLE hist_2_cuentas_historial LIKE 2_cuentas_historial; INSERT hist_2_cuentas_historial SELECT * FROM 2_cuentas_historial;
			//CREATE TABLE hist_2_cuentas_pagos LIKE 2_cuentas_pagos; INSERT hist_2_cuentas_pagos SELECT * FROM 2_cuentas_pagos;
		//FIN CREACION TABLAS CLON
		
		
		//INICIO CONTADORES		
			//SELECT COUNT(id) as cuenta FROM 2_cuentas_pagos WHERE id_cuenta = ...;
			$cuentas_pagos = $this->db->query("SELECT COUNT(id) as cuenta FROM 2_cuentas_pagos WHERE id_cuenta IN ".$strngids.";")->result();              
			//print_r($cuentas_pagos[0]->cuenta);
			echo "2_cuentas_pagos: ".$cuentas_pagos[0]->cuenta."<br>";
			
			//SELECT COUNT(id) as cuenta FROM 2_cuentas_historial WHERE id_cuenta = ...;
			$cuentas_historial = $this->db->query("SELECT COUNT(id) as cuenta FROM 2_cuentas_historial WHERE id_cuenta IN ".$strngids.";")->result();              
			//print_r($cuentas_historial[0]->cuenta);
			echo "2_cuentas_historial: ".$cuentas_historial[0]->cuenta."<br>";
			
			//SELECT COUNT(id) as cuenta FROM 2_cuentas_gastos WHERE id_cuenta = ...;
			$cuentas_gastos = $this->db->query("SELECT COUNT(id) as cuenta FROM 2_cuentas_gastos WHERE id_cuenta IN ".$strngids.";")->result();              
			//print_r($cuentas_gastos[0]->cuenta);
			echo "2_cuentas_gastos: ".$cuentas_gastos[0]->cuenta."<br>";
			
			//SELECT COUNT(id) as cuenta FROM 2_cuentas_etapas WHERE id_cuenta = ...;
			$cuentas_etapas = $this->db->query("SELECT COUNT(id) as cuenta FROM 2_cuentas_etapas WHERE id_cuenta IN ".$strngids.";")->result();              
			//print_r($cuentas_etapas[0]->cuenta);
			echo "2_cuentas_etapas: ".$cuentas_etapas[0]->cuenta."<br>";
			
			//SELECT COUNT(id) as cuenta FROM 0_cuentas WHERE id_cuenta = ...;
			$cuentas = $this->db->query("SELECT COUNT(id) as cuenta FROM 0_cuentas WHERE id IN ".$strngids.";")->result();              
			//print_r($cuentas_pagos[0]->cuenta);
			echo "0_cuentas: ".$cuentas[0]->cuenta."<br>";
		//FIN CONTADORES
		
		//INICIO ELIMINAR REGISTROS IN
			//DELETE FROM 2_cuentas_pagos WHERE id_cuenta IN ...;
			$this->db->query("DELETE FROM 2_cuentas_pagos WHERE id_cuenta IN ".$strngids.";");
			
			//DELETE FROM 2_cuentas_historial WHERE id_cuenta IN ...;
			$this->db->query("DELETE FROM 2_cuentas_historial WHERE id_cuenta IN ".$strngids.";");
			
			//DELETE FROM 2_cuentas_gastos WHERE id_cuenta IN ...;
			$this->db->query("DELETE FROM 2_cuentas_gastos WHERE id_cuenta IN ".$strngids.";");
			
			//DELETE FROM 2_cuentas_etapas WHERE id_cuenta IN ...;
			$this->db->query("DELETE FROM 2_cuentas_etapas WHERE id_cuenta IN ".$strngids.";");
			
			//DELETE FROM 0_cuentas WHERE id IN ...;
			$this->db->query("DELETE FROM 0_cuentas WHERE id IN ".$strngids.";");
		
		//FIN ELIMINAR REGISTROS IN
		
		//INICIO ELIMINAR REGISTROS NOT IN
			//DELETE FROM 2_cuentas_pagos WHERE id_cuenta NOT IN ...;
			$this->db->query("DELETE FROM hist_2_cuentas_pagos WHERE id_cuenta NOT IN ".$strngids.";");
			
			//DELETE FROM 2_cuentas_historial WHERE id_cuenta NOT IN ...;
			$this->db->query("DELETE FROM hist_2_cuentas_historial WHERE id_cuenta NOT IN ".$strngids.";");
			
			//DELETE FROM 2_cuentas_gastos WHERE id_cuenta NOT IN ...;
			$this->db->query("DELETE FROM hist_2_cuentas_gastos WHERE id_cuenta NOT IN ".$strngids.";");
			
			//DELETE FROM 2_cuentas_etapas WHERE id_cuenta NOT IN ...;
			$this->db->query("DELETE FROM hist_2_cuentas_etapas WHERE id_cuenta NOT IN ".$strngids.";");
			
			//DELETE FROM 0_cuentas WHERE id NOT IN ...;
			$this->db->query("DELETE FROM hist_0_cuentas WHERE id NOT IN ".$strngids.";");
		//FIN ELIMINAR REGISTROS IN
*/
		
		
	}
	
	
}
?>