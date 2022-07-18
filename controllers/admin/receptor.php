Seleccionar<?php
class receptor extends CI_Controller {
	public $data = array();
	protected $show_tpl = TRUE;
	public function Usuarios() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'session' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'cuentas_m' );
		
		$this->load->model ( 'receptor_m' );
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'nodo_m' );
		//$this->output->enable_profiler(TRUE);
		/*seters*/
		$this->data['current'] = 'receptor';
		$this->data['plantilla'] = 'receptor/';
		$this->data['lists'] = array();
		$this->load->model ( 'nodo_m' );
		$this->data['nodo'] = $this->nodo = $this->nodo_m->get_by( array('activo'=>'S') );
	
	
	    $v=$this->tribunales_m->order_by("tribunal","ASC")->get_many_by(array('activo'=>'S','padre ='=>'0'));
	    $this->data['tribunales']['']='Seleccionar..';
	 	foreach ($v as $obj) {$this->data['tribunales'][$obj->id] = $obj->tribunal;}
	
		$this->data['estado_receptor'] = array(''=>'Seleccionar','0'=>'No Vigente','1'=>'Vigente');
	}
	
	function gen($id){
		//$this->output->enable_profiler(TRUE);
		if($id != ''){
			$this->receptor_m->delete_by( array('id'=>$id));
		}
	}
	
	function cuentas($id='') {
		//$this->output->enable_profiler(TRUE); 
		$view='cuentas';
		$this->data['plantilla'].= $view;	
		$config['uri_segment'] = '4';
		$this->data['id_cuenta'] = $id;
		$this->data['current_pag'] = $this->uri->segment(4);	
		
		if ($view=='cuentas'){
			if (!empty($id)){
				$this->db->where(array('activo' => 'S'));
				$this->data['lists'] = $this->receptor_m->get_by(array('id'=>$id));
			}
			
			if (isset($_REQUEST['orden_tribunal']) && $_REQUEST['orden_tribunal']!=''){
				if ($_REQUEST['orden_tribunal'] == 'desc'){
					$order_by ='CAST(trib.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trib.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
    			$config['suffix'].= 'orden_tribunal='.$_REQUEST['orden_tribunal'];
    		}
			
			 //ORDER BY CAST(trib.tribunal AS UNSIGNED) asc

			if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){
				if ($_REQUEST['rut'] == 'desc'){
					$order_by ='u.rut desc';  
				} else {
					$order_by = 'u.rut asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
    			$config['suffix'].= 'rut='.$_REQUEST['rut'];
    		}

			
			if (!empty($id)){
				$this->data['causas'] = $this->receptor_m->causas($id, $order_by);
			}
			
			$this->load->view ( 'backend/index', $this->data );	
		}
		//echo $this->db->last_query();			
	}
	
	function index($id_cuenta='') {
		//$this->output->enable_profiler(TRUE); 
		$view='list';
		$this->data['plantilla'].= $view;	
		$config['uri_segment'] = '4';
		$this->data['id_cuenta'] = $id_cuenta;
		$this->data['current_pag'] = $this->uri->segment(4);	
		
		if ($view=='list'){
			//print_r($_REQUEST);
			//echo count($_REQUEST['nombres']);
			if (isset($_REQUEST['nombres'])  && $_REQUEST['nombres']!="" ){ 
		    	$where["r.nombre"] = $_REQUEST['nombres']; 
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'nombres='.$_REQUEST['nombres'];	
		    }
		   
			
			if (isset($_REQUEST['id_distrito'])  && $_REQUEST['id_distrito']!=""){ 
		    	$where["r.id_tribunal"] = $_REQUEST['id_distrito'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'id_tribunal='.$_REQUEST['id_distrito'];	
		    }
		       	
		//	print_r($where); 
			/*if (isset($_REQUEST['rol'])){if ($_REQUEST['rol']>0){
				$where["cta.id_tribunal_ex"] = $_REQUEST['rol'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_tribunalE='.$_REQUEST['rol'];
	    	}}*/
		//	print_r($where);
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
			if(isset($where))
			{
				$this->db->where($where);
			}
									
			$query = $this->db->get();
		
		    $this->data['lists']=  $query->result();
	    }
		//echo $this->db->last_query();			
		//$this->data['lists']= $this->receptor_m->get_receptor();
		
		
		$this->load->view ( 'backend/index', $this->data );

	}
	
	public function form($action='',$id='') {
		
		//$this->output->enable_profiler(TRUE);
		$this->load->model ( 'archivoreceptor_m' );
		$view='receptor';
		$this->data['plantilla'].= $view;
		
		if ($action == 'guardar') {
			
			$this->form_validation->set_rules ('nombre','Nombre','trim');
			//echo "<br>";
			//print_r($_POST);
			//echo "<br>";
			
			if ($this->form_validation->run() == TRUE){
				$fields_save['nombre'] = $this->input->post('nombre');
				$fields_save['rut'] = $this->input->post('rut');
				
				$fields_save['appat'] = $this->input->post('ApePat');
				$fields_save['apmat'] = $this->input->post('ApeMat');
				
				$fields_save['fono_secre'] = $this->input->post('Telefono_secretaria');				
				$fields_save['celu_secre'] = $this->input->post('celular_secretaria');				
				
				if($this->data['nodo']->nombre == 'fullpay'){
					$fields_save['comuna'] = $this->input->post('comuna');
					$fields_save['email'] = $this->input->post('email');
					$fields_save['id_estado'] = $this->input->post('id_estado');
					$fields_save['ciudad'] = $this->input->post('ciudad');
					$fields_save['direccion'] = $this->input->post('direccion');
					$fields_save['telefono'] = $this->input->post('telefono');
					$fields_save['celular'] = $this->input->post('celular');
					$fields_save['nombre_secretaria'] = $this->input->post('nombre_secretaria');
					$fields_save['id_tribunal'] = $this->input->post('id_tribunal');
				}
				$this->receptor_m->save_default($fields_save,$id);
		   	    if($id == ''){
					$id = $this->db->insert_id();
				} 
				redirect('admin/receptor/form/editar/'.$id);
		   	    
		    } 
		    else
		    {
			    if (empty($id))
			    {
				   $id=$this->db->insert_id();
				} 
				redirect('admin/receptor/form/editar/'.$id);
			};
		   	    
		}
			
        if (!empty($id)){
    	    $this->db->where(array('activo' => 'S'));
			$this->data['lists'] = $this->receptor_m->get_by(array('id'=>$id));
	    }
			

		$listadofiles=$this->archivoreceptor_m->get_listadoarchivos($id);
		//echo $this->db->last_query();
		$this->data['listadofiles'] = $listadofiles;
				
        //$this->data['id'] = $id;
		$this->data['plantilla'] = 'receptor/form'; 
		$this->load->view ( 'backend/index', $this->data );
	}
}
?>