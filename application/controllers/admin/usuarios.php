<?php
class Usuarios extends CI_Controller {
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
		$this->load->model ( 'nodo_m' );
		//$this->output->enable_profiler(TRUE);
		/*seters*/
		$this->data['current'] = 'usuarios';
		$this->data['plantilla'] = 'usuarios/';
		$this->data['lists'] = array();
		
		$this->data['nodo'] = $this->nodo_m->get_by( array('activo'=>'S') );
	}
	function form($action='',$id=''){

		$view='form';
		$this->data['plantilla'].= $view;
		/*guardar*/		
		if ($action=='guardar'){
			$this->usuarios_m->setup_validate();
			if (!$this->usuarios_m->save_default($_POST,$id)){
			}else{
				if (empty($id)){
					$id=$this->db->insert_id();
				}
				
				redirect('admin/usuarios/form/editar/'.$id);
			}
		}
		/* funcion combo */
			$a=$this->comunas_m->order_by("nombre","ASC")->get_all( );//array("padre" => '13')
			$this->data['comunas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['comunas'][$obj->id] = $obj->nombre;}
		/* end funcion combo */
		if (!empty($id)){
			$this->data['lists']=$this->usuarios_m->get_by(array('id'=>$id));
			
			$dat_user = $this->cuentas_m->get_by( array('id_usuario'=>$id));
			$id_cuenta = $dat_user->id;
			$this->data['direccion_list'] = $this->direccion_m->get_by(array('id_cuenta'=>$id_cuenta));
			$this->data['telefono_list'] = $this->telefono_m->get_all( array('id_cuenta'=>$id_cuenta) );
		}
		$this->load->view ( 'backend/index', $this->data );
	}

	function gen($action,$id){$this->index($action,$id);}
	
	function index($action='',$id='') {
		// $this->output->enable_profiler(TRUE);
		
		$view='list';
		$this->data['plantilla'].= $view;	
		$config['uri_segment'] = '4';
		$this->data['current_pag'] = $this->uri->segment(4);	

		if ($action=='actualizar'){
			$this->usuarios_m->update($id,$_POST);
			$this->show_tpl = FALSE;
		}
		if ($action=='up' or $action=='down'){
			$this->usuarios_m->move_up_down($_POST['posicion'],$id,$action,$_POST['field_categoria']);
			$this->show_tpl = FALSE; 
		}
		
		if ($view=='list'){
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/usuarios/index/';
	    	$config['total_rows'] = $this->db->where("activo","S")->count_all_results("0_usuarios");
	    	$config['per_page'] = '35'; //$config['num_links'] = '10';
	    	$config['suffix'] = '';
	    	
	    	//******************
			
	    
	    	
	    	
	    	$like = array();
	    	//$order_by = '';
	    	
	    	// desc 123
		   if (isset($_REQUEST['nombres_orden']) && $_REQUEST['nombres_orden']!=''){
	    			$like['u.rut'] =  $_REQUEST['nombres_orden'];  
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'nombres_orden='.$_REQUEST['nombres_orden'];
	    		}
	    	
	    		
	    		
		  /* if ($order_by==''){
	    		$order_by = "u.fecha_crea desc";
	    	} */
	    	
	    	
		/* if (isset($_REQUEST['nombres_orden']) && $_REQUEST['nombres_orden']!=''){
	    			if ($_REQUEST['nombres_orden'] == 'desc'){
	    				$order_by ='u.nombres desc';  
	    			} else {
	    				$order_by = 'u.nombres asc';  
	    			}
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'nombres_orden='.$_REQUEST['nombres_orden'];
	    		}
	    	
		  if ($order_by==''){
	    		$order_by = "u.fecha_crea desc";
	    	}*/
	    	
	    	$this->db->flush_cache();
	    	
	    	//$this->db->query('SET SQL_BIG_SELECTS=1');
	    	$this->pagination->initialize($config);
			/*listado*/
			$query =$this->db->select('u.id,
			u.activo,
			u.public,
			u.posicion,
			u.rut,
			u.nombres,
			u.ap_pat,
			u.fecha_crea,
			u.ap_mat,
			u.direccion,
			u.direccion_numero,
			u.direccion_dpto,
			u.celular,
			u.telefono,
			c.id AS id_cuenta,
			u.ciudad,
			u.id AS field_categoria')
							 ->where( array("u.activo"=>"S") )
							 ->like($like)
							 
							 ->join('0_cuentas c', 'c.id_usuario = u.id','left')
							 //->join('2_cuentas_direccion d', 'c.id = d.id_cuenta AND `d`.`estado`="1"','left')
							 //->join('2_cuentas_telefono tp', 'c.id = tp.id_cuenta AND `tp`.`estado`="1" AND `tp`.`tipo`="1"','left')
							 //->join('2_cuentas_telefono tc', 'c.id = tc.id_cuenta AND `tc`.`estado`="1" AND `tc`.`tipo`="3"','left')
							 ->group_by('u.id')
			 				 ->get("0_usuarios u", $config['per_page'], $this->data['current_pag']);
			$this->data['lists']=$query->result();
			/*echo '<pre>';
			print_r( $this->data['lists'] );
			echo '</pre>';*/
			/*posiciones*/
			
			
			
			$query = $this->db->select('id AS field_categoria, MAX(posicion) AS max_posicion, MIN(posicion) AS min_posicion')->get("0_usuarios");
			foreach ($query->result() as $key=>$val){
				$this->data['posiciones'][$val->field_categoria]['max_posicion']=$val->max_posicion;
				$this->data['posiciones'][$val->field_categoria]['min_posicion']=$val->min_posicion;
				$this->data['posiciones'][$val->field_categoria]['field_categoria']=$val->field_categoria;
			}
			$this->data['total']=$config['total_rows'];
			if (!$this->show_tpl){ $this->data['plantilla'] = 'usuarios/list_tabla'; $this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data );}
			
		}
			
		
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}

	}
}

?>