<?php
class Cuentas extends CI_Controller {
	public $data = array();
	public $activo = 'S';
	protected $show_tpl = TRUE;
	public function Cuentas() { $this->__construct(); }
	function __construct() {
		parent::__construct();
		$this->load->library ( 'session' );
		if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
 		//$this->db_cae = $this->load->database('cae', true);

		$this->load->helper ( 'date_html_helper' );
		
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'cuentas_m' ); 
		$this->load->model ( 'cuentas_na' ); 
		$this->load->model ( 'cuentas_etapas_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'cinformadas_m' );		
		$this->load->model ( 'tipo_productos_m' );
		$this->load->model ( 'administradores_m' );
		$this->load->model ( 'cuentas_juzgados_m' );	
		$this->load->model ( 'estados_cuenta_m' );
		$this->load->model ( 'cuentas_pagos_m');
		$this->load->model ( 'comprobantes_m');
		$this->load->model ( 'cuentas_gastos_m');
		$this->load->model ( 'cuentas_historial_m');
		$this->load->model ( 'etapas_m' );	
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'distrito_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'receptor_m');
		$this->load->model ( 'documento_m' );
		$this->load->model ( 'pagare_m' );
		$this->load->model ( 'diligencia_m' );
		$this->load->model ( 'gasto_regla_m' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'bienes_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'receptor_m' );
		$this->load->model ( 'documento_plantilla_m' );
		$this->load->model ( 'log_etapas_m' );
		$this->load->model ( 'mail_m' );
		$this->load->model ( 'comunas_m');
		$this->load->model ( 'cuentas_contratos_m');
		$this->load->model ( 'movimiento_cuenta');
		$this->load->model ( 'configuracion_m');
		$this->load->model ( 'abogados_m');
		
		/*seters*/
		$this->data['current'] = 'cuentas';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'cuentas/';
		$this->data['lists'] = array();
		
		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
		$this->data['forma_pagos'] = array(''=>'Forma Pago','TF'=>'Transferencia','DP'=>'DepÃ³sito','CH'=>'Cheque','EF'=>'Efectivo');
		
		
		
		$c=$this->estados_cuenta_m->order_by('estado','ASC')->get_all(); 
		$this->data['estados'][-1]='Seleccionar..';
	 	foreach ($c as $obj) {$this->data['estados'][$obj->id] = $obj->estado;}
	 	
		$this->data['nodo'] = $this->nodo = $this->nodo_m->get_by( array('activo'=>'S') );
		
		$r=$this->cuentas_m->get_many_by(array('activo'=>'S','rol !='=>''));
	    $this->data['roles']['']='Seleccionar..';
	 	foreach ($r as $obj) {$this->data['roles'][$obj->rol] = $obj->rol;}
		
	 	$rep=$this->receptor_m->order_by('nombre','ASC')->get_all(); 
		$this->data['receptores'][-1]='Seleccionar..';
	 	foreach ($rep as $obj) {$this->data['receptores'][$obj->id] = $obj->nombre;}
	 	
	 	$v=$this->comunas_m->order_by("nombre","ASC")->get_many_by(array('activo'=>'S','nombre !='=>''));
	    $this->data['comunas']['']='Seleccionar..';
	 	foreach ($v as $obj) {$this->data['comunas'][$obj->nombre] = $obj->nombre;}
		
	 	
	 	$t=$this->tribunales_m->order_by("tribunal","ASC")->get_many_by(array('activo'=>'S','padre ='=>'0'));
	    $this->data['tribunales']['']='Seleccionar..';
	 	foreach ($t as $obj) {$this->data['tribunales'][$obj->id] = $obj->tribunal;}
	 	
	 	
		$tc=$this->tribunales_m->order_by("tribunal","ASC")->get_many_by(array('activo'=>'S','padre ='=>'0'));
	    $this->data['tribunal_comuna']['']='Seleccionar..';
	 	foreach ($tc as $obj) {$this->data['tribunal_comuna'][$obj->id] = $obj->tribunal;}
	 	
		//$this->output->enable_profiler(TRUE);
		//echo $this->session->userdata('logged_in').'cuentas';
	}

	public function distribuir(){
		$configs = $this->configuracion_m->order_by('id_procurador')->get_all();

		$cuentas = $ids = array();
		foreach($configs as $key => $value){
			$where = array();
			if($value->estado)
				$where['estado'] = $value->estado;
			if($value->mandante)
				$where['mandante'] = $value->mandante;
			if($value->id_distrito){
				$this->db->join("tribunales_pjud pjud", "pjud.id = cta.id_tribunal", "left");
				$this->db->join("s_tribunales trib", "trib.id = pjud.id_s_tribunales", "left");
				$where['trib.jurisdiccion_id'] = $value->id_distrito;
			}

			if($value->sistema == 'na'){
				$query = $this->db->select('cta.*')
							  ->from("0_cuentas_n_a cta")
							  ->where(array('seleccionado'=>0))
							  ->where($where)
							  ->get();
				//print_r($query->result()); die;
			}
			else{
				$query = $this->db->select('cta.*')
							  ->from("0_cuentas cta")
							  ->where(array('seleccionado'=>0))
							  ->where($where)
							  ->get();
			}
			
			$cuentas[$value->id_procurador][] = $query->result();
			foreach ($query->result() as $k => $val) {
				$data = array();
				$data['seleccionado'] = 1;
				if($value->sistema == 'na'){
					$this->cuentas_na->update($val->id, $data, false, true);
				}
				else
					$this->cuentas_m->update($val->id, $data, false, true);
			}
		}

		foreach ($cuentas as $key => $value){
			//echo $key; print_r($value); die;
			//$total = 0; echo '('.$key.')';
			foreach ($value as $k => $v){
				//echo '['.$k.']';
				//print_r($v);
				//echo '['.count($v).']';
				foreach ($v as $s => $t){
					$idabog = $t->id_abogado;
					$datos_abog = array();
					$abogad = $this->abogados_m->get_by(array("id" => $idabog));
					$datos_abog['distribuidos'] = intval($abogad->distribuidos) + 1;
					$this->abogados_m->update($idabog, $datos_abog, false, true);
				}
			}
			//echo $key.'-->'.$total.'|';
		}
		redirect('admin/cuentas/index/');
	}
	
	function form($action='',$id='',$param=''){
		
		$view='form';
		$this->data['plantilla'].= $view;
		/*guardar*/

		$this->data['juicio_terminado'] = $this->cuentas_etapas_m->get_by(array('id_cuenta'=>$id,'id_etapa'=>'37'));
		
		$this->db->limit(15);
		$this->db->order_by('id','DESC');
		$this->db->where( array('id_cuenta'=>$id) );
		$this->data['historial_etapas_log'] = $this->log_etapas_m->get_log();
		
		/*echo '<pre>';
		print_r( $this->data['historial_etapas_log'] );
		echo '</pre>';*/
		
		$this->data['id_cuenta'] = $id;
		
		$c1[] = $this->documento_m->get_columns();
		$c2[] = $this->cuentas_m->get_columns();
		$c3[] = $this->mandantes_m->get_columns();
		$c4[] = $this->etapas_m->get_columns();
		//echo print_r($c1[]);
		$c = array_merge($c1, $c2,$c3,$c4);
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = $dato;
			}
		}
		$this->db->where(array('doc.idcuenta'=>$id));
		
		$this->db->select($cols);
		$this->db->from('documento doc');
		$this->db->join('0_cuentas c', 'c.id = doc.idcuenta');
		//$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join('s_etapas e', 'e.id = doc.id_etapa','left');
		$this->db->order_by('doc.iddocumento', 'DESC');
		
		$query = $this->db->get();

		$this->data['documentos'] = $query->result();
		
		$receptores = $this->receptor_m->dropdown('nombre');
		
		$this->data['receptores'] = array_merge(array(''=>'-- Selecionar --'),$receptores);
		
		$this->db->where(array('activo'=>'S'));
		$this->data['etapas_upload'] = $this->etapas_m->get_all();
		
		$this->db->where(array('idcuenta'=>$id));
		$this->db->select('SUM(monto_deuda) AS total_pagare_new');
		$this->db->from('pagare');
		$query = $this->db->get();
		$pagare_suma = $query->result();
		
		if( count($pagare_suma)>0 && $id != ''){
			$this->data['pagare_suma'] = $pagare_suma[0]->total_pagare_new;
		}else{
			$this->data['pagare_suma'] = 0;
		}
		
		if (!empty($id)){
			$this->data['direccion_list'] = $this->direccion_m->get_by( array('id_cuenta'=>$id,'estado'=>'1') );
			
			$this->db->where( array('id_cuenta'=>$id,'estado <='=>'1') );
			$this->data['telefono_list'] = $this->telefono_m->get_all();
			
			$this->data['bien_list'] = $this->bienes_m->get_many_by( array('id_cuenta'=>$id,'estado <='=>'1') );
			
			$this->data['mail_list'] = $this->mail_m->get_by( array('id_cuenta'=>$id,'estado'=>'1') );
		}
		
		if ($action=='guardar'){
			$this->cuentas_m->setup_validate();
			
			$fields_save = array();

			//if (isset($_POST['id_mandante'])){ $fields_save['id_mandante']=$_POST['id_mandante'];}
			if (isset($_POST['id_abogado'])){ $fields_save['id_abogado']=$_POST['id_abogado'];}
			//if (isset($_POST['id_tipo_producto'])){ $fields_save['id_tipo_producto']=$_POST['id_tipo_producto'];}
			//if (isset($_POST['n_pagare'])){ $fields_save['n_pagare']=$_POST['n_pagare'];}
			//if (isset($_POST['monto_deuda'])){ $fields_save['monto_deuda']=$_POST['monto_deuda'];}
			/*if (isset($_POST['fecha_asignacion_year']) && isset($_POST['fecha_asignacion_month']) && isset($_POST['fecha_asignacion_day'])){
				$fecha_asignacion=$_POST['fecha_asignacion_year'].'-'.$_POST['fecha_asignacion_month'].'-'.$_POST['fecha_asignacion_day'];
				$fields_save['fecha_asignacion']=$fecha_asignacion;
			}*/
			
			/*$fecha_inicio = '';
			if (isset($_POST['fecha_inicio_year']) && isset($_POST['fecha_inicio_month']) && isset($_POST['fecha_inicio_day'])){
				$fecha_inicio=$_POST['fecha_inicio_year'].'-'.$_POST['fecha_inicio_month'].'-'.$_POST['fecha_inicio_day'];
				$fields_save['fecha_inicio']=$fecha_inicio;
			}*/
			
			if ( isset($_POST['fecha_ingreso']) && strtotime($_POST['fecha_ingreso']) >0 ){ $fields_save['fecha_asignacion'] = date("Y-m-d",strtotime($_POST['fecha_ingreso']) ) ;}
			if ( isset($_POST['fecha_ultimo_pago']) && strtotime($_POST['fecha_ultimo_pago']) > 0){ $fields_save['fecha_ultimo_pago'] = date("Y-m-d",strtotime($_POST['fecha_ultimo_pago']) ) ;}
			
			if (isset($_POST['tipo_demanda'])){ $fields_save['tipo_demanda']=$_POST['tipo_demanda'];}
			if (isset($_POST['exorto'])){ $fields_save['exorto']=$_POST['exorto'];}

			if (isset($_POST['id_tribunal'])){ $fields_save['id_tribunal']=$_POST['id_tribunal'];}
			if (isset($_POST['id_distrito'])){ $fields_save['id_distrito']=$_POST['id_distrito'];}
			if (isset($_POST['rol'])){ $fields_save['rol']=$_POST['rol'];}
			
			if (isset($_POST['receptor'])){ $fields_save['receptor'] = $_POST['receptor'];}
		
			if (isset($_POST['monto_demandado'])){ $fields_save['monto_demandado']=$_POST['monto_demandado'];}
			//if (isset($_POST['id_estado_cuenta'])){ $fields_save['id_estado_cuenta']=$_POST['id_estado_cuenta'];}
			if (isset($_POST['bien_habitacional'])){ $fields_save['bien_habitacional']=$_POST['bien_habitacional'];}
			if (isset($_POST['bien_vehiculo'])){ $fields_save['bien_vehiculo']=$_POST['bien_vehiculo'];}
			
			if (isset($_POST['fecha_escritura_personeria'])){ $fields_save['fecha_escritura_personeria']=$_POST['fecha_escritura_personeria'];}
			if (isset($_POST['notaria_personeria'])){ $fields_save['notaria_personeria']=$_POST['notaria_personeria'];}
			if (isset($_POST['titular_personeria'])){ $fields_save['titular_personeria']=$_POST['titular_personeria'];}

			//###########
			//guardar
			/*
			$this->form_validation->set_rules('id_tipo_producto', 'Campo', '');
			$this->form_validation->set_rules('n_pagare', 'Campo', '');
			$this->form_validation->set_rules('monto_deuda', 'Campo', '');
			$this->form_validation->set_rules('fecha_asignacion_day', 'Campo', '');
			$this->form_validation->set_rules('fecha_asignacion_month', 'Campo', '');
			$this->form_validation->set_rules('fecha_asignacion_year', 'Campo', '');
			*/
			$this->form_validation->set_rules('fecha_escritura_personeria', '', '');
			$this->form_validation->set_rules('notaria_personeria', '', '');
			$this->form_validation->set_rules('titular_personeria', '', '');

			//###########
			if (!$this->cuentas_m->save_default($fields_save,$id)){
			}else{
				if (empty($id)){$id=$this->db->insert_id();} 
			redirect('admin/cuentas/form/editar/'.$id);
			};
		}//guardar
		
		//###########
		//load		
		$this->process_load_pagare($id);
		//###########		
		
		// --------------------------------------------------------------------
		if ($action=='guardar-historial'){

			$this->cuentas_etapas_m->setup_validate();
			if (!empty($_POST ['historial'])){$fields_save['historial'] = $_POST ['historial'];}
			$fields_save['fecha'] = date('Y-m-d H:i:s');
			$fields_save['id_cuenta'] = $id;
			if (!$this->cuentas_historial_m->save_default($fields_save,$param)){}
			else{if (empty($id)){$id=$this->db->insert_id();} redirect('admin/cuentas/form/editar/'.$id);};
		}
		if ($action=='eliminar-historial'){
			//$this->output->enable_profiler(TRUE);
			if (!empty($param)){$this->cuentas_historial_m->eliminar($param);}
			redirect('admin/cuentas/form/editar/'.$id);
		}
		// --------------------------------------------------------------------
		if ($action=='guardar-etapas'){
			$this->cuentas_etapas_m->setup_validate();
			$fecha_etapa=$_POST['fecha_etapa_year'].'-'.$_POST['fecha_etapa_month'].'-'.$_POST['fecha_etapa_day'];
			
			$id_cuenta			= $this->input->post('id_cuenta');
			$observaciones		= $this->input->post('observaciones');
			$obs_administrador	= $this->input->post('obs_administrador');
			$etapa_juicio		= $this->input->post('id_etapa');
			$etapa_otro			= $this->input->post('etapa_otro');
			
			$fields_save = array (
				'id_etapa' => $etapa_juicio, 
				'id_cuenta' => $id_cuenta, 
				'id_administrador' => $this->session->userdata('usuario_id'),
				'obs_administrador' => $obs_administrador,
				'fecha_etapa' => $fecha_etapa			
			 );

				 
			if( $etapa_otro != ''){// la id_etapa de la cuenta no se modifica, pero si se registra en el historial
				
				//$etp_datos = $this->log_etapas_m->get_by(array('id_etapa'=>$etapa_otro));
				//$this->log_etapas_m->save('', array('id_etapa'=>$etapa_otro,'observaciones'=>$observaciones,'id_cuenta'=>$id_cuenta,'fecha'=>date("Y-m-d H:i:s")));

			}else{
				$dat = $this->etapas_m->get_by( array('id'=>$etapa_juicio) );
				if( count($dat)>0 && $dat->tipo <= 2){

						if (!$this->cuentas_etapas_m->save_default( $fields_save , $param )){
							echo 'no guardo';
						}else{
							echo 'guardo';

							if( $param == ''){
								$id_etapa= $this->db->insert_id();
							}else{
								$id_etapa= $param;
							}
													
							
							/* INICIO LOG ETAPAS */
							$s_log_etapas = array();
							$s_log_etapas['operacion'] = 'crea';
							
							$cta = $this->cuentas_m->get_by( array('id'=>$id_cuenta) );
							
							if( count($cta)>0 && $cta->id_etapa != ''){
								$s_log_etapas['operacion'] = 'modifica';
								$s_log_etapas['dato_anterior'] = $cta->id_etapa;
								$id_etapa = $cta->id_etapa;
							}
							
							$s_log_etapas['id_etapa'] = $id_etapa;
							//$s_log_etapas['id_usuario'] = $this->session->userdata('usuario_id');
							$s_log_etapas['id_cuenta'] = $id_cuenta;
							$s_log_etapas['dato_nuevo'] = $etapa_juicio;
							$s_log_etapas['fecha'] = date("Y-m-d H:i:s");
							
							$this->log_etapas_m->save('', $s_log_etapas);
							/* FIN LOG ETAPAS */
							
							
							$this->cuentas_m->save_default(array('id_etapa' => $_POST ['id_etapa']),$id);
							if (empty($id)){
								$id = $this->db->insert_id();
							}
							
							redirect('admin/cuentas/form/editar/'.$id);
						}
				}

			}
		}
		if ($action=='eliminar-etapa'){
			//$this->output->enable_profiler(TRUE);
			
			/* INICIO LOG ETAPAS */
			$etp_datos = $this->log_etapas_m->get_by( array('id_etapa'=>$param) );
			
			$s_log_etapas = array();
			$s_log_etapas['operacion'] = 'elimina';
			
			if( count($etp_datos)>0 ){ 
				$s_log_etapas['dato_anterior'] = $etp_datos->dato_anterior;
				$s_log_etapas['id_etapa'] = $etp_datos->id_etapa;
				$s_log_etapas['id_cuenta'] = $etp_datos->id_cuenta;
				$s_log_etapas['dato_anterior'] = $etp_datos->dato_nuevo;
			}

			//$s_log_etapas['id_usuario'] = $this->session->userdata('usuario_id');
			$s_log_etapas['fecha'] = date("Y-m-d H:i:s");
			
			$this->log_etapas_m->save('', $s_log_etapas);
			/* FIN LOG ETAPAS */
								
			if (!empty($param)){
				$this->cuentas_etapas_m->eliminar($param);
			}
			redirect('admin/cuentas/form/editar/'.$id);
		}
		if ($action=='guardar-pagare'){
			
				$this->form_validation->set_rules('id_tipo_producto', 'Campo', '');
				$this->form_validation->set_rules('n_pagare', 'Campo', '');
				$this->form_validation->set_rules('monto_deuda', 'Campo', 'required');
				$this->form_validation->set_rules('fecha_asignacion_day', 'Campo', '');
				$this->form_validation->set_rules('fecha_asignacion_month', 'Campo', '');
				$this->form_validation->set_rules('fecha_asignacion_year', 'Campo', '');
				
				$this->form_validation->set_rules('fecha_escritura_personeria', '', '');
				$this->form_validation->set_rules('notaria_personeria', '', '');
				$this->form_validation->set_rules('titular_personeria', '', '');
						
				if ($this->form_validation->run() == TRUE){

					if ($this->input->post('monto_deuda')!=''){
						$extras = array();
						$extras['idcuenta'] = $id;
						$extras['fecha_asignacion'] = $fecha_asignacion=$_POST['fecha_asignacion_year'].'-'.$_POST['fecha_asignacion_month'].'-'.$_POST['fecha_asignacion_day'];
						
						$save = array_merge($_POST, $extras);
						
						unset($save['monto_deuda']);
						unset($save['fecha_vencimiento']);
						
						$save['monto_deuda'] = str_replace(',','.', $this->input->post("monto_deuda") );
						
						$save['fecha_vencimiento'] = date("Y-m-d",strtotime( $this->input->post('fecha_vencimiento') ));
						
						$this->pagare_m->save('',$save);
						if( $id != '' && $id != 0){
							$this->db->query('UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new + ('.str_replace(',','.', $this->input->post("monto_deuda") ).') WHERE id='.$id );
						}
						
					}
					redirect('admin/cuentas/form/editar/'.$id);
				}
		}
		// --------------------------------------------------------------------
		if ($action=='guardar-acuerdo'){
			
			$this->cuentas_m->setup_validate_acuerdo();
			$fecha_primer_pago=$_POST['fecha_primer_pago_year'].'-'.$_POST['fecha_primer_pago_month'].'-'.$_POST['fecha_primer_pago_day'];
			$fields_save = array (
				'id_acuerdo_pago' => $_POST['id_acuerdo_pago'],
				'fecha_primer_pago' => $fecha_primer_pago,
				'intereses' => $_POST['intereses'],
				'valor_cuota_con_intereses' => ( $_POST['valor_cuota_con_intereses'] * $_POST ['n_cuotas'] ),
				'n_cuotas' => $_POST ['n_cuotas'],
			
				'n_cuotas_real' => $_POST ['n_cuotas_real'],
				'valor_cuota_real' => $_POST ['valor_cuota_real'],
			
				'valor_cuota' => $_POST ['valor_cuota'], 
				'dia_vencimiento_cuota' => $_POST['dia_vencimiento_cuota']	
			 );
			if (!$this->cuentas_m->save_default($fields_save,$id)){
				
			}else{
				if (empty($id)){
					$id=$this->db->insert_id();
				} /*redirect('admin/cuentas/form/editar/'.$id);*/
			};

		}
		// --------------------------------------------------------------------
		if ($action=='guardar-pagos'){
			//$this->output->enable_profiler(FALSE);
			if (isset($_POST['fecha_pago_year']) && isset($_POST['fecha_pago_month']) && isset($_POST['fecha_pago_day'])){ 
				$fecha_pago=$_POST['fecha_pago_year'].'-'.$_POST['fecha_pago_month'].'-'.$_POST['fecha_pago_day'];
			}
			/*comprobante 25-04-2012 */
			$id_comprobante = '';
			$fields_save = array();
			$modificando = false;
			
			if ($param==''){
				
				if (!empty($fecha_pago) && $fecha_pago!='--'){$fields_save['fecha_pago'] = $fecha_pago;}
				$fields_save['verify_sign'] = uniqid();
				
			} else{
				$t = $this->cuentas_pagos_m->get_by(array('id'=>$param));
				
				if (count($t)==1){
					$id_comprobante = $t->id_comprobante;
					$monto_pagado = $t->monto_pagado;
					$modificando = true;
				}
			}	

			
			$fields_save['id_cuenta']=$id;
			if (isset($_POST['monto_pagado'])){ $fields_save['monto']=$_POST['monto_pagado'];}
			if (isset($_POST['forma_pago'])){ $fields_save['forma_pago']=$_POST['forma_pago'];}
			
			
			$this->comprobantes_m->setup_validate();
			if (!$this->comprobantes_m->save_default($fields_save,$id_comprobante)){}
			else{if (empty($id_comprobante)){$id_comprobante=$this->db->insert_id();}};
			
			/*comprobante 25-04-2012*/
			
			
			$this->cuentas_pagos_m->setup_validate();
			
			/* 25-04-2012 */ $fields_save = array();
			if (!empty($fecha_pago) && $fecha_pago!='--'){$fields_save['fecha_pago'] = $fecha_pago;}
			if (isset($_POST['n_comprobante_interno'])){ $fields_save['n_comprobante_interno'] = $_POST['n_comprobante_interno'];}
			/* 25-04-2012 */ if (isset($id_comprobante)){ $fields_save['id_comprobante'] = $id_comprobante;}
			if (!empty($_POST ['monto_pagado'])){ $fields_save['monto_pagado'] = $_POST ['monto_pagado'];}
			if (!empty($_POST ['honorarios'])){ $fields_save['honorarios'] = $_POST ['honorarios'];}
			$fields_save['monto_remitido'] = $fields_save['monto_pagado'];
			if (!empty($_POST ['honorarios'])){ $fields_save['monto_remitido'] = $fields_save['monto_remitido'] - $fields_save['honorarios'];}
			$fields_save['id_cuenta'] = $id;
			//$this->output->enable_profiler(TRUE);
			
			if (!empty($_POST ['id_acuerdo_pago'])){ $fields_save['id_acuerdo_pago'] = $_POST ['id_acuerdo_pago']; }

			
			if (!$this->cuentas_pagos_m->save_default($fields_save,$param)){
				
			}else{/*if (empty($id)){$id=$this->db->insert_id();}*/ 
				

					//$data_vouncher = $this->cuentas_pagos_m->get_by( array('id'=>$param));
					$c = $this->cuentas_m->get_by( array('id'=>$id));
					if( count($c)>0 ){
						if( $c->monto_pagado_new != $this->input->post("monto_pagado") ){
							//$monto_modificado = $c->monto_pagado_new - $this->input->post("monto_pagado");
							$this->db->query('UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new + ('.$this->input->post("monto_pagado").') WHERE id='.$id );
						}
					}else{
						//$this->db->query('UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new - ('.$this->input->post("monto_pagado").') WHERE id='.$id );
					}

				
				
				redirect('admin/cuentas/form/editar/'.$id);
			}
		}
		if ($action=='eliminar-pago'){
			if (!empty($param)){
				
				$this->cuentas_pagos_m->eliminar($param);

				$data_vouncher = $this->cuentas_pagos_m->get_by( array('id'=>$param));
				if( count($data_vouncher)>0 ){
					$descontar = $data_vouncher->monto_pagado;
					$r = $this->db->query('UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new - ('.$descontar.') WHERE id='.$id );
				}
			
				$t = $this->cuentas_pagos_m->get_by(array('id'=>$param));
				if (count($t)==1){
					$this->comprobantes_m->eliminar($t->id_comprobante); 
				}
				
			}
			redirect('admin/cuentas/form/editar/'.$id);
		}
		// --------------------------------------------------------------------
		if ($action=='guardar-gastos'){
			$this->cuentas_gastos_m->setup_validate();
			$fecha=$_POST['fecha_year'].'-'.$_POST['fecha_month'].'-'.$_POST['fecha_day'];
			
			if (!empty($fecha) && $fecha!='--'){ $fields_save ['fecha'] = $fecha;}
			if (!empty($_POST['n_boleta'])){ $fields_save['n_boleta'] = $_POST['n_boleta'];}
			if (!empty($_POST['rut_receptor'])){ $fields_save['rut_receptor'] = $_POST['rut_receptor'];}
			if (!empty($_POST['nombre_receptor'])){ $fields_save['nombre_receptor'] = $_POST[nombre_receptor];}
			if (!empty($_POST['monto'])){ $fields_save['monto'] = $_POST['monto'];}
			if (!empty($_POST['retencion'])){ $fields_save['retencion'] = $_POST['retencion'];}
			if (!empty($_POST['descripcion'])){ $fields_save['descripcion'] = $_POST['descripcion'];}

			if( $this->input->post('enviar_gastos') == 'Modificar' ){
				
					$cuenta = $this->cuentas_gastos_m->get_by( array('id'=>$this->input->post('id_cuentas_gastos')) );
					
					if( $this->input->post('monto') > $cuenta->monto ){
						
						$mnt = $this->input->post('monto') - $cuenta->monto;
						$this->db->query('UPDATE 0_cuentas SET monto_gasto_new = monto_gasto_new + ('.$mnt.') WHERE id='.$id );
						
					}else if(  $this->input->post('monto') < $cuenta->monto ){
						
						$mnt = $cuenta->monto - $this->input->post('monto');
						$this->db->query('UPDATE 0_cuentas SET monto_gasto_new = monto_gasto_new - ('.$mnt.') WHERE id='.$id );
						
					}
				
					if (!$this->cuentas_gastos_m->save_default($fields_save,$param)){
					}else{/*if (empty($id)){$id=$this->db->insert_id();}*/
						redirect('admin/cuentas/form/editar/'.$id);
					}
				
			}else{
					
					$fields_save['id_cuenta'] = $id;
					
					if( $this->input->post('monto') ){
						$this->db->query('UPDATE 0_cuentas SET monto_gasto_new = monto_gasto_new + ('.$this->input->post('monto').') WHERE id='.$id );
					}
					
					if (!$this->cuentas_gastos_m->save_default($fields_save,$param)){
					}else{/*if (empty($id)){$id=$this->db->insert_id();}*/
						redirect('admin/cuentas/form/editar/'.$id);
					}
			}
			
		}
		if ($action=='eliminar-gasto'){
			
			if (!empty($param)){
				
				$cuenta = $this->cuentas_gastos_m->get_by( array('id'=>$param) );
				$this->db->query('UPDATE 0_cuentas SET monto_gasto_new = monto_gasto_new - ('.$cuenta->monto.') WHERE id='.$id );
				$this->cuentas_gastos_m->eliminar($param);

			}
			redirect('admin/cuentas/form/editar/'.$id);
		}
		
		/**
		 * dropdown
		 */
			$this->db->where('activo','S');
			$this->db->order_by("rut","ASC");
			$a=$this->usuarios_m->get_all();
			$this->data['usuarios'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['usuarios'][$obj->id] = $obj->rut;}
			
			$a=$this->mandantes_m->get_all();
			$this->data['mandantes'][0]='Seleccionar Mandante';
			foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}
			
			$a=$this->tipo_productos_m->get_all();
			$this->data['tipo_de_productos'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['tipo_de_productos'][$obj->id] = $obj->tipo;}
			
			$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'perfil' => '3'));
			$a=$this->administradores_m->get_all();
			$this->data['administradores'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['administradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
			
			$a=$this->estados_cuenta_m->get_all();
			$this->data['estados_cuenta'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}

			$a=$this->etapas_m->order_by('codigo','ASC')->order_by('tipo','ASC')->get_many_by(array('activo'=>'S','codigo !='=>''));
			$this->data['etapas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['etapas'][$obj->id] = $obj->codigo.' '.$obj->etapa;}
			
		    $a=$this->tribunales_m->get_many_by( array( "padre" => '0') );
			$this->data['tribunales'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['tribunales'][$obj->id] = $obj->tribunal;}
			
			$a=$this->comunas_m->order_by("nombre","ASC")->get_many_by( array("padre" => '13') );
			$this->data['comunas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['comunas'][$obj->id] = $obj->nombre;}
			
		// --------------------------------------------------------------------
		
		if (!empty($id)){
			$this->data['lists']=$this->cuentas_m->get_by(array('id'=>$id));
			//print_r($this->data['lists']);
		}
		
		
		$this->data['procuradores'] = array();
		if (!empty($this->data['lists']->id_procurador) && $this->data['lists']->id_procurador!='0'){
			$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'id' => $this->data['lists']->id_procurador));
			$a=$this->administradores_m->get_all();
			foreach ($a as $obj) {$this->data['procuradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
		}	


		$this->data['abogados'] = array();
		if (!empty($this->data['lists']->id_abogado) && $this->data['lists']->id_abogado!='0'){
			$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'id' => $this->data['lists']->id_abogado));
			$a=$this->abogados_m->where("activo", 'S')->get_all();
			foreach ($a as $obj) {$this->data['abogados'][$obj->id] = $obj->nombres.' '.$obj->ape_pat;}
		}	


		$this->data['distritos'] = array();
		if (!empty($this->data['lists']->id_tribunal) && $this->data['lists']->id_tribunal!='0'){
			$a=$this->tribunales_m->order_by("id","ASC")->get_many_by( array( "padre" => $this->data['lists']->id_tribunal) );
			$this->data['distritos'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['distritos'][$obj->id] = $obj->tribunal;}	
		}
		/**
		 * listado de actualizaciones de etapas
		 */
			$query =$this->db->select('
			 ce.id AS id,
			 ce.id_etapa AS id_etapa, ce.obs_administrador, ce.observaciones, c.fecha_etapa AS fecha_etapa, adm.nombres AS nombres, adm.apellidos AS apellidos, ce.id AS field_categoria')
							 ->join("0_administradores adm", "adm.id = ce.id_administrador")
							 ->join("s_etapas etapa", "etapa.id = ce.id_etapa")
							 ->where("id_cuenta", $id)
							 ->where("ce.activo","S")
							 ->order_by("c.fecha_etapa", "desc")
							 ->order_by("ce.fecha_crea", "desc")
							 ->get("2_cuentas_etapas ce");
			$this->data['cuenta_etapas_listado']=$query->result();
		// --------------------------------------------------------------------
		/**
		 * listado de pagos
		 */
			//$this->data['pagos']=$this->cuentas_pagos_m->order_by("fecha_pago","ASC")->get_many_by( array("id_cuenta" => $id,"activo"=>"S") );
			$cols = array();
			$cols[] = 'cp.id AS id';
			$cols[] = 'cp.id_cuenta AS id_cuenta';
			$cols[] = 'cp.fecha_pago AS fecha_pago';
			$cols[] = 'cp.fecha_vencimiento AS fecha_vencimiento';
			$cols[] = 'cp.monto_pagado AS monto_pagado';
			$cols[] = 'cp.honorarios AS honorarios';
			$cols[] = 'cp.monto_remitido AS monto_remitido';
			$cols[] = 'cp.n_comprobante_interno AS n_comprobante_interno';
			$cols[] = 'comp.id AS id_comprobante';
			$cols[] = 'comp.forma_pago AS forma_pago';
			$this->data['pagos'] = $this->cuentas_pagos_m->get_pagos($cols,array("cp.id_cuenta" => $id,"cp.activo"=>"S"));
			
		// --------------------------------------------------------------------
		/**
		 * listado de gastos
		 */
			$this->data['gastos']=$this->cuentas_gastos_m->order_by("fecha","DESC")->get_many_by( array("id_cuenta" => $id,"activo"=>"S") );
		// --------------------------------------------------------------------
		/**
		 * listado de historial
		 */
			$this->data['historiales']=$this->cuentas_historial_m->order_by("fecha","DESC")->get_many_by( array("id_cuenta" => $id,"activo"=>"S") );
		// --------------------------------------------------------------------
		$this->data['usuario']=array();
		if (count($this->data['lists'])>0){
			$this->data['usuario']=$this->usuarios_m->get_by(array('id' => $this->data['lists']->id_usuario));
		}
		
		
		$query =$this->db->select('SUM(monto_pagado) AS total')
							 ->where(array("id_cuenta"=>$id,"activo"=>'S'))			 
			 				 ->get("2_cuentas_pagos pag");
		$a=$query->result();
		foreach ($a as $key=>$val){
			$this->data['total_pagado'] = $val;
		}
		
		
		/* BEGIN PETER */
		$doc = $this->documento_plantilla_m->get_all();
		$documentos = array();
		$documentos[] = 'Seleccionar...';
		foreach($doc as $key=>$val){
			$documentos[$val->path]= $val->nombre_documento;
		}
		$this->data['documentos_generar'] = $documentos;
		
		$this->db->order_by('etapa ASC');
		$this->data['etapas_generar'] = $this->etapas_m->get_many_by(array('activo'=>'S'));
		/* END PETER */

		
		$this->load->view ( 'backend/index', $this->data );
	}
	
	
	public function datos_adicionales_edit($accion='',$id=''){
		
		$view='datos_adicionales_edit';
		$this->data['plantilla'].= $view;
		
		if( $_POST && $id != ''){
			if( $this->input->post('direccion') ){
				$save=array();
				$save['direccion'] = $this->input->post('direccion');
				$save['observacion'] = $this->input->post('observacion');
				$save['estado'] = $this->input->post('estado');
				$this->direccion_m->save($id,$save);
				$arr = $this->direccion_m->get_by( array('id'=>$id));
				$id_cuenta = $arr->id_cuenta;
			}
			if( $this->input->post('telefono') ){
				$save=array();
			  	$save['numero'] = $this->input->post('telefono');
			  	$save['observacion'] = $this->input->post('observacion');
			  	$save['estado'] = $this->input->post('estado');
				$this->telefono_m->save($id,$save);
				$arr = $this->telefono_m->get_by( array('id'=>$id));
				$id_cuenta = $arr->id_cuenta;
			}
			if( $this->input->post('celular') ){
				$save=array();
			  	$save['numero'] = $this->input->post('celular');
			  	$save['observacion'] = $this->input->post('observacion');
			  	$save['estado'] = $this->input->post('estado');
				$this->telefono_m->save($id,$save);
				$arr = $this->telefono_m->get_by( array('id'=>$id));
				$id_cuenta = $arr->id_cuenta;
			}
			if( $this->input->post('bien') ){
				$save=array();
			  	$save['tipo'] = $this->input->post('bien');
			  	$save['estado'] = $this->input->post('estado');
			  	$save['observacion'] = $this->input->post('observacion');
				$this->bienes_m->save($id,$save);
				$arr = $this->bienes_m->get_by( array('id'=>$id));
				$id_cuenta = $arr->id_cuenta;
			}
			
			if( $this->input->post('mail') ){
				$save=array();
			  	$save['mail'] = $this->input->post('mail');
			  	$save['estado'] = $this->input->post('estado');
			  	$save['observacion'] = $this->input->post('observacion');
				$this->mail_m->save($id,$save);
				$arr = $this->mail_m->get_by( array('id'=>$id));
				$id_cuenta = $arr->id_cuenta;
			}
			
			redirect('admin/cuentas/datos_adicionales/'.$id_cuenta);
		}
		
		if( $accion == 'direccion'){
			$arr = $this->direccion_m->get_by( array('id'=>$id) );
			if( count($arr)>0 ){
				$this->data['dato'] =  $arr->direccion;
				$this->data['estado'] =  $arr->estado;
				$this->data['id_cuenta'] =  $arr->id_cuenta;
				$this->data['observacion'] =  $arr->observacion;
			}
		}
		
		if( $accion == 'telefono' || $accion == 'celular'){
			$arr = $this->telefono_m->get_by( array('id'=>$id) );
			if( count($arr)>0 ){
				$this->data['dato'] =  $arr->numero;
				$this->data['estado'] =  $arr->estado;
				$this->data['id_cuenta'] =  $arr->id_cuenta;
				$this->data['observacion'] =  $arr->observacion;
			}
		}
		
		if( $accion == 'bien' ){
			$arr = $this->bienes_m->get_by( array('id'=>$id) );
			if( count($arr)>0 ){
				$this->data['dato'] =  $arr->tipo;
				$this->data['estado'] =  $arr->estado;
				$this->data['id_cuenta'] =  $arr->id_cuenta;
				$this->data['observacion'] =  $arr->observacion;
			}
		}
		
		if( $accion == 'mail' ){
			$arr = $this->mail_m->get_by( array('id'=>$id) );
			if( count($arr)>0 ){
				$this->data['dato'] =  $arr->mail;
				$this->data['estado'] =  $arr->estado;
				$this->data['id_cuenta'] =  $arr->id_cuenta;
				$this->data['observacion'] =  $arr->observacion;
			}
		}
		
		$this->data['id'] = $id;
		$this->data['titulo'] = $accion;
		$this->load->view ( 'backend/index', $this->data );
		
	}
	
	public function datos_adicionales_delete($accion='',$id=''){
		
		if( $accion == 'direccion'){
			$arr = $this->direccion_m->get_by( array('id'=>$id));
			$id_cuenta = $arr->id_cuenta;
			$this->direccion_m->delete_by( array('id'=>$id) );
		}
		
		if( $accion == 'telefono' || $accion == 'celular'){
			$arr = $this->telefono_m->get_by( array('id'=>$id));
			$id_cuenta = $arr->id_cuenta;
			$this->telefono_m->delete_by( array('id'=>$id) );
		}
		if( $accion == 'bien' ){
			$arr = $this->bienes_m->get_by( array('id'=>$id));
			$id_cuenta = $arr->id_cuenta;
			$this->bienes_m->delete_by( array('id'=>$id) );
		}
		
		if( $accion == 'mail' ){
			$arr = $this->mail_m->get_by( array('id'=>$id));
			$id_cuenta = $arr->id_cuenta;
			$this->mail_m->delete_by( array('id'=>$id) );
		}
		
		redirect('admin/cuentas/datos_adicionales/'.$id_cuenta);
		
	}
	
	public function datos_adicionales($id_cuenta=''){
		
		$view='datos_adicionales';
		$this->data['plantilla'].= $view;
        
		if( $_POST && $id_cuenta != ''){
			$redirec = FALSE;
			if( $this->input->post('direccion') ){
				$save=array();
				$save['direccion'] = $this->input->post('direccion');
				$save['observacion'] = $this->input->post('observacion');
				$save['estado'] = '0';
				$save['id_cuenta'] = $id_cuenta;
				$this->direccion_m->save('',$save);
				$redirec = TRUE;
			}
			if( $this->input->post('telefono') ){
				$save=array();
			  	$save['numero'] = $this->input->post('telefono');
			  	$save['observacion'] = $this->input->post('observacion');
			  	$save['estado'] = '0';
			  	$save['tipo'] = $this->input->post('tipo');
			  	$save['id_cuenta'] = $id_cuenta;
				$this->telefono_m->save('',$save);
				$redirec = TRUE;
			}
			if( $this->input->post('bien') ){
				$save=array();
			  	$save['estado'] = '0';
			  	$save['tipo'] = $this->input->post('bien');
			  	$save['observacion'] = $this->input->post('observacion');
			  	$save['id_cuenta'] = $id_cuenta;
				$this->bienes_m->save('',$save);
				$redirec = TRUE;
			}
			
			if( $this->input->post('mail') ){
				
				$this->form_validation->set_rules('mail', 'Mail', 'trim|required|valid_email');
				if( $this->form_validation->run() ){
					$save=array();
				  	$save['estado'] = '0';
				  	$save['mail'] = $this->input->post('mail');
				  	$save['observacion'] = $this->input->post('observacion');
				  	$save['id_cuenta'] = $id_cuenta;
					$this->mail_m->save('',$save);
					$redirec = TRUE;
				}
			}
			if( $redirec ){
				redirect('admin/cuentas/datos_adicionales/'.$id_cuenta);
			}

		}
		 
		
		
		$this->db->order_by('estado');
		$this->db->where('id_cuenta',$id_cuenta);
		$direccion_list = $this->direccion_m->get_all();

		$this->db->order_by('tipo,estado DESC');
		$this->db->where('id_cuenta',$id_cuenta);
		$telefono_list = $this->telefono_m->get_all();
		
		$this->db->order_by('estado DESC');
		$this->db->where('id_cuenta',$id_cuenta);
		$mail_list = $this->mail_m->get_all();		
		
		$this->db->order_by('estado DESC');
		$this->db->where('id_cuenta',$id_cuenta);
		$bienes_list = $this->bienes_m->get_all();
		
		
		$this->data['id'] = $id_cuenta;
		$this->data['direccion_list'] = $direccion_list;
		$this->data['telefono_list'] = $telefono_list;
		$this->data['bienes_list'] = $bienes_list;
		$this->data['mail_list'] = $mail_list;
		$this->load->view ( 'backend/index', $this->data );
	}

	function gen($action,$id){$this->index($action,$id);}

	function index($action='',$id=''){
		$view='list'; 
		$config['uri_segment'] = '4';
		$this->data['current_pag'] = $this->uri->segment(4);
		
		if(isset($_POST['reload']))
			$this->data['reload'] = $_POST['reload'];
		$this->data['plantilla'].= $view;	
		$this->load->helper('url');	
		if ($action=='actualizar'){
			$this->cuentas_m->update($id,$_POST);
			$this->show_tpl = FALSE;
			$config['uri_segment'] = '6'; 
			$this->data['current_pag'] = $this->uri->segment(6);
		}
		if ($action=='up' or $action=='down'){
			$this->cuentas_m->move_up_down($_POST['posicion'], $id, $action,$_POST['field_categoria']);
			$this->show_tpl = FALSE; 
			$config['uri_segment'] = '6'; 
			$this->data['current_pag'] = $this->uri->segment(6);
		}
		if ($view=='list'){
			/*where*/
			//$this->cruce_pjud();
			//$this->output->enable_profiler(TRUE);
			$where=array();
			$like = array();
	    	$where["cta.activo"] = "S";
			
	    	//$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
	    	$config['suffix'] = '';
	    	//if ($this->form_validation->run() == TRUE){
	    		
	    	$order_by = '';
			
			if (isset($_REQUEST['comuna']) && $_REQUEST['comuna']!=''){
				if ($_REQUEST['comuna'] == 'desc'){
					$order_by ='nombre_comuna desc';  
				} else {
					$order_by = 'nombre_comuna asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'comuna='.$_REQUEST['comuna'];
	    	}

			//Fin Nuevo Orden
		    if (isset($_REQUEST['rut_orden']) && $_REQUEST['rut_orden']!=''){
				if ($_REQUEST['rut_orden'] == 'desc'){
					$order_by ='usr.rut desc';  
				} else {
					$order_by = 'usr.rut asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rut_orden='.$_REQUEST['rut_orden'];
			}
	    	
			if (isset($_REQUEST['nombres_orden']) && $_REQUEST['nombres_orden']!=''){
				if ($_REQUEST['nombres_orden'] == 'desc'){
					$order_by ='usr.nombres desc';  
				} else {
					$order_by = 'usr.nombres asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'nombres_orden='.$_REQUEST['nombres_orden'];
    		}
	    		    	
	    	if (isset($_REQUEST['orden_rol']) && $_REQUEST['orden_rol']!=''){
				if ($_REQUEST['orden_rol'] == 'desc'){
					$order_by ='cta.rol desc';  
				} else {
					$order_by = 'cta.rol asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
    			$config['suffix'].= 'orden_rol='.$_REQUEST['orden_rol'];
    		}

		    if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){
	    		if ($this->nodo->nombre == 'fullpay'){
    				$where["usr.rut"] = str_replace('.','',$_REQUEST['rut']);
				} else {
					$where["usr.rut"] = $_REQUEST['rut'];
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'rut='.$_REQUEST['rut'];
    		}
	    		    

			if (isset($_REQUEST['nombres'])){
				if ($_REQUEST['nombres']!=''){ 
					$like["usr.nombres"] = $_REQUEST['nombres'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'nombres='.$_REQUEST['nombres'];
				}
			}
						
			if (isset($_REQUEST['nombre'])){
				if ($_REQUEST['nombre']!=''){
					$like["comu.nombre"] = $_REQUEST['nombre'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'nombre='.$_REQUEST['nombre'];
				}
			}
			
			if (isset($_REQUEST['ap_pat'])){if ($_REQUEST['ap_pat']!=''){
				$like["usr.ap_pat"] = $_REQUEST['ap_pat'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'ap_pat='.$_REQUEST['ap_pat'];
			}}
			if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){
				$where["cta.id_procurador"] = $_REQUEST['id_procurador'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
			}}

			if (isset($_REQUEST['id_abogado'])){if ($_REQUEST['id_abogado']>0){ 
				$where["cta.id_abogado"] = $_REQUEST['id_abogado'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_abogado='.$_REQUEST['id_abogado'];
			}}

			
			if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
			
		    if (isset($_REQUEST['bienes'])){
				if ($_REQUEST['bienes']!=''){ 
					$observacion = trim($_REQUEST['bienes']);	    				
					$like["2cb.observacion"] = $observacion; 
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'bienes='.$_REQUEST['bienes'];
				 }
			}
			
		    if (isset($_REQUEST['rut_parcial'])){
				if ($_REQUEST['rut_parcial']!=''){ 
				$rut_parcial = trim($_REQUEST['rut_parcial']);	    				
					$like["usr.rut"] = $rut_parcial; 
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'rut_parcial='.$_REQUEST['rut_parcial'];
				 }
			}
			if (isset($_REQUEST['operacion'])){if ($_REQUEST['operacion']>0){
				$where["cta.operacion"] = $_REQUEST['operacion'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'operacion='.$_REQUEST['operacion'];
	    	}}	    	
			
		    	

			if (isset($_REQUEST['id_distritoE'])){if ($_REQUEST['id_distritoE']>0){
				$where["cta.id_distrito_ex"] = $_REQUEST['id_distritoE'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_distritoE='.$_REQUEST['id_distritoE'];
	    	}}	    	
			
			if (isset($_REQUEST['id_tribunalE'])){if ($_REQUEST['id_tribunalE']>0){
				$where["cta.id_tribunal_ex"] = $_REQUEST['id_tribunalE'];
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'id_tribunalE='.$_REQUEST['id_tribunalE'];
	    	}}
			
			
		    if (isset($_REQUEST['rol'])){
				if ($_REQUEST['rol']!=''){ 
					$like["CONCAT(tp.tipo,'-',cta.rol,'-',a.anio)"] = $_REQUEST['rol'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'rol='.$_REQUEST['rol'];
				}
			}
			
			if (isset($_REQUEST['rol']) && $_REQUEST['rol']!=''){
				if ($_REQUEST['rol'] == 'desc'){
					$order_by ='CAST(tri.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(tri.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
    			$config['suffix'].= 'orden_rol='.$_REQUEST['rol'];
    		}
			
			if (isset($_REQUEST['id_tribunal_comuna'])){
				if ($_REQUEST['id_tribunal_comuna']!=''){ 
					$where["trib_com.id"] = $_REQUEST['id_tribunal_comuna'];
					if ($config['suffix']!=''){ $config['suffix'].='&';}
					$config['suffix'].= 'id_tribunal_comuna='.$_REQUEST['id_tribunal_comuna'];
				}
			}
	    	// print_r($where);		 
	    	//	$this->db->where('padre', 0);
						
			$this->db->where('activo', 'S');
			//$this->db->order_by('tribunal', 'ASC');
			$arr = $this->distrito_m->get_all();
			$distrito[''] = 'Seleccionar';
			foreach($arr as $key=>$val){
				$distrito[$val->id]= $val->jurisdiccion;
			}
			$this->data['distrito']= $distrito;
			
			//	$this->db->where('activo', 'S');
				$arr = $this->tribunales_m->get_all();

				$tribunales[''] = 'Seleccionar';
				foreach($arr as $key=>$val){
					$tribunales[$val->id]= $val->tribunal;
				}
			$this->data['tribunales']= $tribunales;
				
			/*paginacion*/
			$this->load->library('pagination');
			$config['base_url'] = site_url().'/admin/cuentas/index/';

			$this->db->where($where);

			$this->db->like($like);
			//$this->db->order_by($order_by);
			$this->db->group_by("cta.id");
			$query1 = $this->db->get('0_cuentas cta');

			$query1 = $query1->result();  //print_r($query1);

			$this->db->where($where);

			$this->db->like($like);
			//$this->db->order_by($order_by);
			$this->db->group_by("cta.id");

			$query2 = $this->db->get('0_cuentas_n_a cta');
 
			$query2 = $query2->result();  //print_r($query2); die;

			$total_rows = array_merge($query1, $query2); //print_r($total_rows); die;
						 
			//$total_rows = $query_total->result();
			$config['total_rows'] = count($total_rows);
	    	$config['per_page'] = '30';
	    	
	    	$this->pagination->initialize($config);


			###########################################################################
			#########################QUERY LIST TABLA CUENTAS #########################
			###########################################################################

			$this->db->select('
				cta.id AS id,
				cta.rol AS rol,
				cta.tribunal AS tribunal,
				cta.fecha_ingreso AS fecha_ingreso,
				cta.caratulado AS caratulado,
				cta.rut,
				cta.fecha_etapa,
				cta.etapa as etapa_cuenta,
				cta.nombres as procurador,
				cta.estado,
				cta.mandante,
				abo.nombres AS nombres,
				mc.etapa, 
				mc.tramite,
				mc.descripcion,
				mc.fecha,
				mc.url,
				');
			$this->db->join("abogado abo", "abo.id = cta.id_abogado");
			$this->db->join("movimiento_cuenta mc", "cta.id = mc.id_cuenta", 'left');
			
			$this->db->where($where);

			$this->db->like($like);
			//$this->db->order_by($order_by);
			$this->db->group_by("cta.id");
			$query =  $this->db->get('0_cuentas cta', $config['per_page'], $this->data['current_pag']);
			
			$list1 = $query->result();

			// N_A

			$this->db->select('
				cta.id AS id,
				cta.rol AS rol,
				cta.tribunal AS tribunal,
				cta.fecha_ingreso AS fecha_ingreso,
				cta.caratulado AS caratulado,
				cta.rut,
				cta.fecha_etapa,
				cta.etapa as etapa_cuenta,
				cta.nombres as procurador,
				cta.estado,
				cta.mandante,
				abo.nombres AS nombres,
				mc.etapa, 
				mc.tramite,
				mc.descripcion,
				mc.fecha,
				mc.url,
				');
			$this->db->join("abogado abo", "abo.id = cta.id_abogado");
			$this->db->join("movimiento_cuenta mc", "cta.id = mc.id_cuenta", 'left');
			
			$this->db->where($where);

			$this->db->like($like);
			//$this->db->order_by($order_by);
			$this->db->group_by("cta.id");
			$query =  $this->db->get('0_cuentas_n_a cta', $config['per_page'], $this->data['current_pag']);
			
			$list2 = $query->result(); //print_r($list2); die;

			$a=$this->abogados_m->get_all();
			$this->data['abogados'] = $a;

			//Contar las reservadas
			$this->db->select('count(*) as cant');
			$this->db->where(array('causa_reservada'=>1));
			$query =  $this->db->get('0_cuentas cta');
			$cant1 = $query->result();

			$this->db->select('count(*) as cant');
			$this->db->where(array('causa_reservada'=>1));
			$query =  $this->db->get('0_cuentas_n_a cta');
			$cant2 = $query->result();
			$reservadas = intval($cant1[0]->cant) + intval($cant2[0]->cant);
			$this->data['reservadas'] = $reservadas;
		
			$this->db->start_cache();
			$this->db->stop_cache();
			
			$this->data['lists'] = array_merge($list1, $list2); //print_r($this->data['lists']); die;
			
			$this->data['total'] = $config['total_rows'];
			
			$this->db->where(array('activo' => $this->activo, 'public' => $this->activo));
			$a=$this->administradores_m->get_all();
			$this->data['procuradores'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['procuradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
			$this->data['mandantes'][0]='Seleccionar';
			$a=$this->mandantes_m->get_many_by(array('activo'=>'S'));
			foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}

			if (!$this->show_tpl){ 
				$this->data['plantilla'] = 'cuentas/list_tabla'; 
				$this->load->view ( 'backend/templates/'.$this->data['plantilla'], $this->data);
			}			
		}
			
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}

	}
	
	public function cuenta_total_deuda($id_cuenta = ''){
		$this->db->where( array( 'idcuenta'=>$id_cuenta ) );
		$this->db->select('SUM(monto_deuda) AS monto_deuda');
		$this->db->from('pagare');
		$query = $this->db->get();
		$dat = $query->result();
		if( count($dat)>0 ){
			return $dat[0]->monto_deuda;
		}else{
			return '0';
		}
		
	}
	
	public function cuenta_total_pagado($id_cuenta = ''){
		$this->db->where( array( 'id_cuenta'=>$id_cuenta,'activo'=>'S' ) );
		$this->db->select('SUM(monto_pagado) AS monto_pagado');
		$this->db->from('2_cuentas_pagos');
		$query = $this->db->get();
		$dat = $query->result();
		if( count($dat)>0 ){
			return $dat[0]->monto_pagado;
		}else{
			return '0';
		}
	}
	
	function reporte($tipo = 'pagos',$param = ''){

        //$this->output->enable_profiler(TRUE);
		
	    $this->data['sub_current'] = $tipo;
		$config['uri_segment'] = '5';
		$this->data['current_pag'] = $this->uri->segment(5);
		$config['suffix'] = '';
		/*where*/
		$where=array(); $like=array();
	    $where["cta.activo"] = "S";
	    /*inicializar paginacion*/
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/admin/cuentas/reporte/'.$tipo;
	    $config['per_page'] = '30'; //$config['num_links'] = '10';
	    /************************* PAGOS *************************/
	    if ($tipo == 'pagos'){
	    	$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
		    if ($this->form_validation->run() == TRUE){
		    	if ($_REQUEST['rut']!=''){ 
		    		$where["usr.rut"] = $_REQUEST['rut'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    	}
		    }
		    if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){ 
		    	$where["cta.id_procurador"] = $_REQUEST['id_procurador'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
	    		$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
		    }}
		    if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']>0){ 
		    	$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
	    		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
		    }}
		    
	   	  if (isset($_REQUEST['id_estado_cuenta'])){if ($_REQUEST['id_estado_cuenta']>0){ 
		    	$where["cta.id_estado_cuenta"] = $_REQUEST['id_estado_cuenta'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
	    		$config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
		    }}
		    
		     
	    	$dia_i = '';
		    if (isset($_REQUEST['fecha_day'])){ 
		    	if ($_REQUEST['fecha_day']>0){$dia_i = $_REQUEST['fecha_day'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_day='.$_REQUEST['fecha_day'];
		    }
		    $mes_i = '';
		    if (isset($_REQUEST['fecha_month'])){ 
		    	if ($_REQUEST['fecha_month']>0){$mes_i = '-'.$_REQUEST['fecha_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_month='.$_REQUEST['fecha_month'];
		    }
		    $year_i = '';
		    if (isset($_REQUEST['fecha_year'])){ 
		    	if ($_REQUEST['fecha_year']>0){$year_i = $_REQUEST['fecha_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_year='.$_REQUEST['fecha_year'];
		    }
		    $like["pagos.fecha_pago"]=$year_i.$mes_i.$dia_i;
	    	$dia_f = '';
		    if (isset($_REQUEST['fecha_f_day'])){ 
		    	if ($_REQUEST['fecha_f_day']>0){$dia_f = $_REQUEST['fecha_f_day'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_day='.$_REQUEST['fecha_f_day'];
		    }
	    	$mes_f = '';
		    if (isset($_REQUEST['fecha_f_month'])){ 
		    	if ($_REQUEST['fecha_f_month']>0){$mes_f = '-'.$_REQUEST['fecha_f_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_month='.$_REQUEST['fecha_f_month'];
		    }
		    $year_f = '';
		    if (isset($_REQUEST['fecha_f_year'])){ 
		    	if ($_REQUEST['fecha_f_year']>0){$year_f = $_REQUEST['fecha_f_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_year='.$_REQUEST['fecha_f_year'];
		    }
		    $between = ''; $like=array(); $where_fecha = "";
		    if ($mes_f =='' && $year_f==''){ $like["pagos.fecha_pago"]=$year_i.$mes_i;}
		    else { 
		    	if ($dia_f == '' && $dia_i == ''){ 
		    		$where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i."01' AND '".$year_f.$mes_f."31'";
		    	} 
		    	if ($dia_f != '' && $dia_i == ''){ 
		    		$where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i."01' AND '".$year_f.$mes_f.$dia_f."'";
		    	}
		    	if ($dia_f == '' && $dia_i != ''){ 
		    		$where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i.$dia_i."' AND '".$year_f.$mes_f."31'";
		    	}
		    	if ($dia_f != '' && $dia_i != ''){ 
		    		$where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i.$dia_i."' AND '".$year_f.$mes_f.$dia_f."'";
		    	}
		    }
		    
		$this->data['estados_cuenta'] = array();
		$a=$this->estados_cuenta_m->get_all();
		$this->data['estados_cuenta'][-1]='Seleccionar';
		foreach ($a as $obj) {$this->data['estados_cuenta'][$obj->id] = $obj->estado;}
		    
		    
		    if ($where_fecha!=''){$this->db->where($where_fecha,NULL,FALSE);}
            $a = $this->db->select('COUNT(DISTINCT cta.id) as count')->join("0_usuarios usr", "usr.id = cta.id_usuario","left")
                ->join("s_estado_cuenta sec" , "sec.id = cta.id_estado_cuenta")
                ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")
                ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
                ->join("2_cuentas_pagos pagos", "pagos.id_cuenta = cta.id")
                ->where($where)->where(array("pagos.activo"=>"S"))
                ->like($like)->get('0_cuentas cta');
            foreach ($a->result() as $obj) {
                $config['total_rows'] = $obj->count;
            }

                //->order_by("id_mandante", "desc")
                //->count_all_results("0_cuentas cta");
		    
		    
		    $select_normal = 'cta.id AS id,mand.clase_html AS clase_html,mand.codigo_mandante AS codigo_mandante,cta.id_estado_cuenta AS id_estado_cuenta,cta.activo AS activo,sec.estado AS estado,pagos.honorarios AS honorarios, cta.publico AS publico, cta.posicion AS posicion, adm.nombres AS nombres, adm.apellidos AS apellidos, mand.razon_social, pagos.fecha_pago AS fecha_pago, pagos.monto_pagado AS monto_pagado, cta.monto_deuda AS monto_deuda, usr.nombres AS usr_nombres, usr.ap_pat AS usr_ap_pat, usr.ap_mat AS usr_ap_mat, usr.rut AS rut, cta.id_mandante AS field_categoria, pagos.n_comprobante_interno AS n_comprobante_interno';

			$select_export = 
			'cta.id AS id,
			 adm.nombres AS nombres,
			 adm.apellidos AS apellidos,
			 mand.razon_social AS razon_social,
			 mand.clase_html AS clase_html,
			 mand.codigo_mandante AS codigo_mandante,
			 sec.estado AS estado,
			 pagos.fecha_pago AS fecha_pago,
			 pagos.monto_pagado AS monto_pagado,
			 cta.monto_deuda AS monto_deuda,
			 usr.nombres AS usr_nombres,
			 usr.ap_pat AS usr_ap_pat,
			 usr.ap_mat AS usr_ap_mat,
			 usr.rut AS rut,
			 pagos.n_comprobante_interno AS n_comprobante_interno';
		    
			if ($param == 'exportar'){
				$select = $select_export;
			}
			else{
				$select = $select_normal;
				$this->db->limit($config['per_page'],$this->data['current_pag']);
			}
			
	    	if ($where_fecha!=''){$this->db->where($where_fecha,NULL,FALSE);}
			
		    $query_master =$this->db->select($select)
								 ->join("0_usuarios usr", "usr.id = cta.id_usuario","left")
								 ->join("s_estado_cuenta sec" , "sec.id = cta.id_estado_cuenta")
								 ->join("0_mandantes mand", "mand.id = cta.id_mandante","left")
								 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
								 ->join("2_cuentas_pagos pagos", "pagos.id_cuenta = cta.id")			 
								 ->where($where)->where(array("pagos.activo"=>"S"))
								 ->like($like)
								 //->order_by("id_mandante", "desc")
								 ->group_by("cta.id")
				 				 ->get("0_cuentas cta");
			$this->data['lists']=$query_master->result();
			

            $array_csv[]=array('Código Cuenta','Nombre Procurador','Apellido Procurador','Mandante','Fecha de Pago','Monto Pagado','Monto Deuda','Nombre Deudor','Apellido Deudor','Apellido Materno Deudor','Rut','Comprobante','Estado Cuenta');
			foreach ($this->data['lists'] as $obj) {
				$array_csv[] = array($obj->id,$obj->nombres,$obj->apellidos,$obj->razon_social,$obj->fecha_pago,$obj->monto_pagado,$obj->monto_deuda,$obj->usr_nombres,$obj->usr_ap_pat,$obj->usr_ap_mat,$obj->rut,$obj->n_comprobante_interno,$obj->estado);
			}
			
			$query = $this->db->select("id_cuenta")->select_sum("monto_pagado")->select_sum("honorarios")->group_by("id_cuenta")->get("2_cuentas_pagos");
			$a = $query->result();
	    	foreach ($a as $obj) {$this->data['saldo'][$obj->id_cuenta] = $obj->monto_pagado-$obj->honorarios;}
			
			/*paginacion*/
	    	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='cuentas/reportes/reporte_pagos';
	    } //pagos

	     /************************* ETAPAS *************************/
	    if ($tipo == 'etapas'){
	    	
	      //  $this->output->enable_profiler(TRUE);
	    	
	    	$config['suffix']=''; $group_by = '';
	    	
	    	$a=$this->etapas_m->order_by('codigo','ASC')->get_all(); 
			$this->data['etapas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['etapas'][$obj->id] = $obj->codigo.' '.$obj->etapa;}
	    	   	

	    	// ---------------------------------------------------------------
	    	// Nuevas consultas para generar reportes
	    	// ---------------------------------------------------------------

			
			
			$sql_select =
    			" SELECT ". 
    			"    ce.*,
    			mand.razon_social,
    			mand.clase_html AS clase_html,
    			usr.rut,
    			adm.apellidos AS amd_apellido,
    			adm.nombres AS adm_nombres,
    			".
				"    comu.nombre AS comuna,
				cta.id AS idcuenta,
				cta.activo AS activo,
				cta.id_mandante,
				cta.posicion AS posicion, ".
				"    cta.publico AS publico,
				cta.monto_deuda AS monto_deuda,
				cta.rol AS rol,
				estado.estado AS estado,
				etapas.etapa AS etapa, ".
				"    mand.razon_social,
				mand.codigo_mandante AS codigo_mandante,
				trib.tribunal AS tribunal,
				dist.tribunal AS distrito,
				usr.ap_mat AS usr_ap_mat, ".
				"    usr.ap_pat AS usr_ap_pat,
				usr.ciudad AS ciudad,
				usr.direccion AS direccion, ".
				"    usr.direccion_dpto AS direccion_dpto,
				adm.nombres AS nombres, adm.apellidos AS apellidos, ".
    			"	 etapas.dias_alerta AS dias_alerta,
    			DATEDIFF(NOW(),
    			c.fecha_etapa) as duracion, ".
				"    usr.direccion_numero AS direccion_numero,
				usr.nombres AS usr_nombres,
				usr.rut AS rut,
				trib_com.tribunal as tribunal_padre_comuna";

			$sql_body = "";			
			$group = "";

			// segun el modo de busqueda se cambia la consulta
			if ($this->input->get_post('modo') == "ultima") 
			{
				
				

			/*	 ->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left")
								->join("s_comunas comu", "comu.id = 2cd.id_comuna","left")
								->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left")*/
				
				
				$sql_body = 
					" FROM ( ".
					"    SELECT id_cuenta, MAX(fecha_etapa) AS last_fecha ".
					"    FROM `2_cuentas_etapas` ".
					"    GROUP BY id_cuenta ".
					" ) ce2 ".
					" JOIN `2_cuentas_etapas` ce ON ce2.id_cuenta = ce.id_cuenta AND ce2.last_fecha = c.fecha_etapa ".
					" LEFT JOIN `0_cuentas` cta ON cta.id = ce.id_cuenta ".
					" LEFT JOIN `0_usuarios` usr ON usr.id = cta.id_usuario ".
					" LEFT JOIN `0_mandantes` mand ON mand.id = cta.id_mandante ".
					" LEFT JOIN `0_administradores` adm ON adm.id = cta.id_procurador ".
					" LEFT JOIN `s_tribunales` trib ON trib.id = cta.id_tribunal ".
					" LEFT JOIN `s_tribunales` dist ON dist.id = cta.id_distrito ".
					
					" JOIN `s_etapas` etapas ON etapas.id = ce.id_etapa ".
					
					" LEFT JOIN `s_estado_cuenta` estado ON estado.id = cta.id_estado_cuenta ".
					" LEFT JOIN `2_cuentas_direccion` 2cd ON 2cd.id_cuenta = cta.id".
					" LEFT JOIN `s_comunas` comu ON comu.id = 2cd.id_comuna".
					" LEFT JOIN `s_tribunales` trib_com ON trib_com.id=comu.id_tribunal_padre".
					
					" WHERE cta.activo = 'S' ";
			
					
					// Si se busca el ultimo se debe agrupar
					$group = ' GROUP BY ce.id_cuenta ';

			} 
			else 
			{
				$sql_body = 
					
					" FROM `2_cuentas_etapas` ce ".
					" JOIN `0_cuentas` cta ON cta.id = ce.id_cuenta ".
					" JOIN `0_usuarios` usr ON usr.id = cta.id_usuario ".
					" JOIN `0_mandantes` mand ON mand.id = cta.id_mandante ".
					" LEFT JOIN `0_administradores` adm ON adm.id = cta.id_procurador ".
					" LEFT JOIN `s_tribunales` trib ON trib.id = cta.id_tribunal ".
					" LEFT JOIN `s_tribunales` dist ON dist.id = cta.id_distrito ".
					" LEFT JOIN `s_etapas` etapas ON etapas.id = ce.id_etapa ".
					" LEFT JOIN `s_estado_cuenta` estado ON estado.id = cta.id_estado_cuenta ".
					//" LEFT JOIN `s_comunas` com ON com.id = usr.id_comuna ".
				    " LEFT JOIN `2_cuentas_direccion` 2cd ON 2cd.id_cuenta = cta.id".
					" LEFT JOIN `s_comunas` comu ON comu.id = 2cd.id_comuna".
					" LEFT JOIN `s_tribunales` trib_com ON trib_com.id=comu.id_tribunal_padre".
					" WHERE cta.activo = 'S' ";
			}

			// preparo los parametros de la busqueda
			$sql_where = "";
			$limit = "";
    		$bind_values = array();
    		$suffix = array();
			//$like = array();
    		
    		$suffix['modo'] =  $this->input->get_post('modo');
			
    		
    		
    		if( $p_rut = $this->input->get_post('rut') ){
    			$sql_where .= " AND usr.rut = ? ";
    			$bind_values[] = $p_rut;
    			$suffix['rut'] =  $p_rut;
    		}
    		
	    	if( $nombre_comuna = $this->input->get_post('nombre') ){
    			$sql_where .= " AND comu.nombre = ? ";
    			$bind_values[] = $nombre_comuna;
    			$suffix['nombre'] =  $nombre_comuna;
    		}
    		
    		
	    	if( $p_rol = $this->input->get_post('rol') ){
    			$sql_where .= " AND cta.rol = ? ";
    			$bind_values[] = $p_rol;
    			$suffix['rol'] =  $p_rol;
    		 }
    		
    		
    		if( $p_procurador = $this->input->get_post('id_procurador') ){
    			$sql_where .= " AND cta.id_procurador = ? ";
    			$bind_values[] = $p_procurador;
    			$suffix['id_procurador'] =  $p_procurador;
    		}
    		if( $p_mandante = $this->input->get_post('id_mandante') ){
    			$sql_where .= " AND cta.id_mandante = ? ";
    			$bind_values[] = $p_mandante;
    			$suffix['id_mandante'] =  $p_mandante;
    		}
    		if( $p_etapa = $this->input->get_post('etapa') ){
    			$sql_where .= " AND ce.id_etapa = ? ";
    			$bind_values[] = $p_etapa;
    			$suffix['etapa'] =  $p_etapa;
    		}
    		
	     
    		
    		
	    	if ($this->input->post('rango')!='' && $this->input->post('rango')!='0'){
    			$date = explode(' - ',$this->input->post('rango'));
    			$from = date("Y-m-d", strtotime(trim($date[0])));
				$to = date("Y-m-d", strtotime(trim($date[1])));
				$sql_where.="AND (`c`.`fecha_etapa` BETWEEN '".$from." 00:00:00' AND '".$to." 23:59:59')";
				$suffix['rango'] =  $this->input->post('rango');	
    		} else {
    		
		    	if( $p_day = $this->input->get_post('fecha_etapa_day') ){
	    			$sql_where .= " AND DAY(c.fecha_etapa) = '".$p_day."' ";
	    			//$bind_values[] = $p_day;
	    			$suffix['fecha_etapa_day'] =  $p_day;	
	    		}
	    		if( $p_mes = $this->input->get_post('fecha_etapa_month') ){
	    			$sql_where .= " AND MONTH(c.fecha_etapa) = '".$p_mes."' ";
	    			//$bind_values[] = $p_mes;
	    			$suffix['fecha_etapa_month'] =  $p_mes;	
	    		}
	    		if( $p_ano = $this->input->get_post('fecha_etapa_year') ){
	    			$sql_where .= " AND YEAR(c.fecha_etapa) = '".$p_ano."' ";
	    			//$bind_values[] = $p_ano;
	    			$suffix['fecha_etapa_year'] =  $p_ano;	
	    		}
    		}

    		
    		$p_estado = $this->input->get_post('estado');
		    if ( is_array($p_estado) && count($p_estado) > 0 ){
	    		$sql_where .= " AND cta.id_estado_cuenta in (".implode(', ', $p_estado).") ";
	    		$suffix['estado[]'] =  $p_estado;	
	    	}

	    	
	    	
	    	if($param !== 'exportar'){
				if((int)$this->data['current_pag'] > 0) {
					$limit = ' LIMIT '.(int)$this->data['current_pag'].', '.$config['per_page'];
				} else {
					$limit = ' LIMIT '.$config['per_page'];
				} 
	    	}
	    	/*echo $sql_where;
	    	die ();*/
			//$this->output->enable_profiler(TRUE);

			// Numero total de registros
			$query = $this->db->query("SELECT COUNT(*) as total FROM (SELECT ce.* ".$sql_body.$sql_where.$group.") as t WHERE 1", $bind_values);
    		
			
			$config['total_rows'] = $query->first_row()->total;
			
			// Listar registros
			
			$query = $this->db->query($sql_select.$sql_body.$sql_where.$group.$limit, $bind_values);
			$this->data['lists'] = $query->result();

			
			//print_r($this->data['lists']);
			//die();
			
			
			if(count($suffix) > 0) {
				$config['suffix'] = '';
				foreach ($suffix as $key => $value) {
					// si el campo es un select multiple se descompone el array
					if (is_array($value)) {
						foreach ($value as $v) {
							$config['suffix'] .= '&'.$key.'='.$v;
						}
					} else {
						$config['suffix'] .= '&'.$key.'='.$value;
					}
				}
				$config['suffix'] = '?'.ltrim($config['suffix'], '&');
			}

	    	$array_csv = array();
	    	$array_csv[] = array('Mandante','Rut','Deudor',utf8_decode('DirecciÃ³n'),'Comuna','Ciudad','Procurador','Etapa del Juicio','Fecha Etapa','Estado Cuenta','Tribunal','Distrito','Rol');    	
	    	foreach ($this->data['lists'] as $obj) {
				$array_csv[] = array(utf8_decode($obj->razon_social),$obj->rut,utf8_decode($obj->usr_nombres.' '.$obj->usr_ap_pat.' '.$obj->usr_ap_mat),utf8_decode($obj->direccion.' '.$obj->direccion_numero.' '.$obj->direccion_dpto),utf8_decode($obj->comuna),utf8_decode($obj->ciudad),utf8_decode($obj->nombres.' '.$obj->apellidos),$obj->etapa,date("d-m-Y", strtotime($obj->fecha_etapa)),$obj->estado,$obj->tribunal,$obj->distrito,$obj->rol);
			}
			
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='cuentas/reportes/reporte_etapas';

	    }//etapas
	    
	     /************************* GASTOS *************************/
	    if ($tipo == 'gastos'){
	    	//$this->output->enable_profiler(TRUE);
	    	$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
		    if ($this->form_validation->run() == TRUE){
		    	if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){ 
		    		$where["gastos.rut_receptor"] = $_REQUEST['rut'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    	}
		    }
	    	if (isset($_REQUEST['rut_deudor']) && $_REQUEST['rut_deudor']!=''){ 
		    	$where["usr.rut"] = $_REQUEST['rut_deudor'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'rut_deudor='.$_REQUEST['rut_deudor'];
		    }
		    
	    	if (isset($_REQUEST['rut_parcial']) && $_REQUEST['rut_parcial']!=''){ 
		    	$like["usr.rut"] = $_REQUEST['rut_parcial'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'rut_parcial='.$_REQUEST['rut_parcial'];
		    }
		    
		    
		    if (isset($_REQUEST['n_boleta']) && $_REQUEST['n_boleta']!=''){ 
		    	$where["gastos.n_boleta"] = $_REQUEST['n_boleta'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'n_boleta='.$_REQUEST['n_boleta'];
		    }
	    	if (isset($_REQUEST['id_mandante']) && $_REQUEST['id_mandante']>0){ 
		    	$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    }
		    
		    
	    	if (isset($_REQUEST['rol']) && $_REQUEST['rol']>0){ 
		    	$like["cta.rol"] = $_REQUEST['rol'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'rol='.$_REQUEST['rol'];	
		    }
		    
	    	if (isset($_REQUEST['id_procurador']) && $_REQUEST['id_procurador']>0){ 
		    	$where["adm.id"] = $_REQUEST['id_procurador'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];	
		    }
			
			if (isset ( $_REQUEST ['id_receptor'] ) && $_REQUEST ['id_receptor'] > 0) {
				$where ["gastos.id_receptor"] = $_REQUEST ['id_receptor'];
				if ($config ['suffix'] != '') {
					$config ['suffix'] .= '&';
				}
				$config ['suffix'] .= 'id_receptor=' . $_REQUEST ['id_receptor'];
			}
		    
		    if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
				$where ["tr.id"] = $_REQUEST ['id_tribunal'];
				if ($config ['suffix'] != '') {
					$config ['suffix'] .= '&';
				}
				$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
			}
		    
			 
			
			
		    $mes_i = '';
		    if (isset($_REQUEST['fecha_month'])){ 
		    	if ($_REQUEST['fecha_month']>0){$mes_i = '-'.$_REQUEST['fecha_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_month='.$_REQUEST['fecha_month'];
		    }
		    $year_i = '';
		    if (isset($_REQUEST['fecha_year'])){ 
		    	if ($_REQUEST['fecha_year']>0){$year_i = $_REQUEST['fecha_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_year='.$_REQUEST['fecha_year'];
		    }
		    $like["gastos.fecha"]=$year_i.$mes_i;
		    
		    $mes_f = '';
		    if (isset($_REQUEST['fecha_f_month'])){ 
		    	if ($_REQUEST['fecha_f_month']>0){$mes_f = '-'.$_REQUEST['fecha_f_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_month='.$_REQUEST['fecha_f_month'];
		    }
		    $year_f = '';
		    if (isset($_REQUEST['fecha_f_year'])){ 
		    	if ($_REQUEST['fecha_f_year']>0){$year_f = $_REQUEST['fecha_f_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_year='.$_REQUEST['fecha_f_year'];
		    }
		    $between = ''; //$like=array();
		    if ($mes_f =='' && $year_f==''){ $like["gastos.fecha"]=$year_i.$mes_i;}
		    else { $this->db->where("`gastos`.`fecha` BETWEEN '".$year_i.$mes_i."01' AND '".$year_f.$mes_f."31'",NULL,FALSE);} 
	    	
		    $config['total_rows'] = $this->db->where($where)->like($like)
	    								     ->join("0_usuarios usr", "usr.id = cta.id_usuario")
								 			 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 			 ->join("0_administradores adm", "adm.id = cta.id_procurador")
								 			 ->join("2_cuentas_gastos gastos", "gastos.id_cuenta = cta.id")	
	    								  	 ->join("0_receptores r", "r.id = gastos.id_receptor","left")
	    								  	 ->join("s_tribunales tr","tr.id = r.id_tribunal","left")
								 			 ->count_all_results("0_cuentas cta");
		    
	    	if ($param == 'exportar'){
			}
			else{
				$this->db->limit($config['per_page'],$this->data['current_pag']);
			}
			
			/*if(empty($like["gastos.fecha"]))
				$like["gastos.fecha"] = date("Y-m");*/
			
			
			$query_master =$this->db->select('cta.id AS id,mand.clase_html AS clase_html,mand.codigo_mandante AS codigo_mandante,cta.activo AS activo,pagos.forma_pago AS forma_pago,r.nombre AS nombre_recep,r.rut AS rut_r, cta.publico AS publico,cta.posicion AS posicion, usr.rut AS rut,usr.nombres AS nombre_deudor,usr.ap_pat AS apellido_paterno,usr.ap_mat AS apellido_materno,tr.tribunal AS tribunal,tr.id AS id_tribunal, cta.rol AS rol, adm.nombres AS nombres, adm.apellidos AS apellidos, mand.razon_social, gastos.fecha AS fecha, gastos.n_boleta AS n_boleta,gastos.fecha_ingreso_banco AS fecha_ingreso_banco,gastos.fecha_recepcion AS fecha_recepcion, gastos.rut_receptor AS rut_receptor, gastos.nombre_receptor AS nombre_receptor, gastos.monto AS monto, gastos.retencion AS retencion, gastos.descripcion AS descripcion, cta.id_mandante AS field_categoria')
								 ->join("0_usuarios usr", "usr.id = cta.id_usuario")
								 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
								 ->join("2_cuentas_gastos gastos", "gastos.id_cuenta = cta.id")	
								 ->join("2_cuentas_pagos pagos", "pagos.id_cuenta = cta.id","left")
								 ->join("0_receptores r", "r.id = gastos.id_receptor","left")
								 ->join("s_tribunales tr","tr.id = r.id_tribunal","left")
								  ->where($where)
								 ->like($like,'match','after')
								 ->order_by("id_mandante", "desc")
								 ->order_by("posicion", "desc")
								 //->group_by("gastos.n_boleta")
				 				 ->get("0_cuentas cta");
			$this->data['lists']=$query_master->result();
			$array_csv = array();
	    	$array_csv[]=array('ID','Mandante','Nombre Receptor','Rut Receptor','NÂº Boleta','Fecha recepcion','Fecha Ingreso Banco','Monto',utf8_decode('Retención 10%'),utf8_decode('Descripción'),'Rut Deudor','Nombre Deudor','Forma Pago');
			foreach ($this->data['lists'] as $obj) {
				$array_csv[] = array($obj->id,utf8_decode($obj->razon_social),utf8_decode($obj->nombre_recep),$obj->rut_r,$obj->n_boleta,date("Y-m-d", strtotime($obj->fecha_recepcion)),date("Y-m-d",strtotime($obj->fecha_ingreso_banco)),$obj->monto,$obj->retencion,utf8_decode($obj->descripcion),$obj->rut,$obj->nombre_deudor.' '.$obj->apellido_paterno.' '.$obj->apellido_materno,$obj->forma_pago);
			}
				 				 
			if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='cuentas/reportes/reporte_gastos';
	    }//gastos
		
		/************************* ESTADOS *************************/
	    if ($tipo == 'estados'){
	    	//$where = array();
	    	//echo 'REPORTE DE ESTADOS (EN DESARROLLO)';
	    	
	    	//$this->output->enable_profiler(TRUE);
	    	
	    	$config['suffix']=''; $group_by = '';
	    	
	    	$a=$this->etapas_m->order_by('codigo','ASC')->get_all(); 
			$this->data['etapas'][0]='Seleccionar';
			foreach ($a as $obj) {$this->data['etapas'][$obj->id] = $obj->codigo.' '.$obj->etapa;}
	    	
	    	$this->form_validation->set_rules('rut', 'Rut', 'trim|is_rut|xss_clean');
			if ($this->form_validation->run() == TRUE){
		    	
			if ($_REQUEST['rut']!=''){ 
		    		$where["usr.rut"] = $_REQUEST['rut'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    	}
		    }
		    	
		    	
		    	if (isset($_REQUEST['id_mandante']) && $_REQUEST['etapa']!='' && $_REQUEST['id_mandante']>0){ 
		    		$where["cta.id_mandante"] = $_REQUEST['id_mandante'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    	}
	    		if (isset($_REQUEST['etapa']) && $_REQUEST['etapa']!='' && $_REQUEST['etapa']>0){ 
		    		$where["etapas.id"] = $_REQUEST['etapa'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'etapa='.$_REQUEST['etapa'];	
		    	}
	    		if (isset($_REQUEST['estado']) && $_REQUEST['estado']!='' && $_REQUEST['estado']>=0){ 
	    			//echo $_REQUEST['estado'];
		    		$where["estado.id"] = $_REQUEST['estado'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'estado='.$_REQUEST['estado'];	
		    	}
		    	
		    	//****
	           	if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){
	    			$where["cta.id_procurador"] = $_REQUEST['id_procurador'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
	    		}}
		    	//***
		    	$mes_i = '';
		    	if (isset($_REQUEST['fecha_etapa_month'])){ 
		    		if ($_REQUEST['fecha_etapa_month']>0){
		    			$mes_i = '-'.$_REQUEST['fecha_etapa_month'].'-';
		    			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    			$config['suffix'].= 'fecha_etapa_month='.$_REQUEST['fecha_etapa_month'];
		    		}	
		    	}
		    	$year_i = '';
		    	if (isset($_REQUEST['fecha_etapa_year'])){ 
		    		if ($_REQUEST['fecha_etapa_year']>0){ 
		    			$year_i = $_REQUEST['fecha_etapa_year'];
		    			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    			$config['suffix'].= 'fecha_etapa_year='.$_REQUEST['fecha_etapa_year'];	
		    		}
		    	}
		    	//$like["cetapa.fecha_etapa"]=$year_i.$mes_i;
		    	
		    // if(empty($like["cetapa.fecha_etapa"]))
		    // 	$like["cetapa.fecha_etapa"] = date("Y-m");
		    
	    	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
	    	
	    	////->join_sql("LEFT JOIN 2_cuentas_etapas cetapa ON cetapa.id=cta.id AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)")
	    	
	    	
	    	$query_total = $this->db->select("cta.id,adm.nombres AS nombres, adm.apellidos AS apellidos,trib_com.tribunal as tribunal_padre_comuna")->where($where)
	    								  	 ->join("0_usuarios usr", "usr.id = cta.id_usuario AND usr.activo='S' AND cta.activo='S'")
											 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
											 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
											 ->join("2_cuentas_pagos pag2", "pag2.id_cuenta = cta.id AND pag2.activo='S'","left")
											 
											 //->join_sql("LEFT JOIN 2_cuentas_etapas cetapa ON cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)")
											 			
											 //->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S' AND pag.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=cta.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left")
												
						
						 					->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left")
											->join("s_comunas comu", "comu.id = 2cd.id_comuna","left")
											->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left")
													
											 
											//->join("s_comunas com", "com.id = usr.id_comuna","left") 
											 
											->join("s_estado_cuenta estado", "estado.id = cta.id_estado_cuenta") 
											 //->join("2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id AND cetapa.activo='S'","left")// AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)","left")
											 ->join("s_etapas etapas", "etapas.id = cta.id_etapa","left")
											 //->where("(cetapa.fecha_etapa IS NULL OR cetapa.fecha_etapa = (SELECT MAX(fecha_etapa) FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta = cta.id AND 2_ce.activo='S'))")
								 			 ->where("cta.activo","S")
											 
											 ->like($like,'match','after')
											 
											 ->group_by("cta.id")
											 ->get("0_cuentas cta");
	    								  	 //->count_all_results("0_cuentas cta");
	    	
	    	
			$total_rows=$query_total->result();
			
			$config['total_rows'] = count($total_rows);
											 
	    	/*$config['total_rows']  = count($query_total->result());
	    	echo 'test2';*/
	    	if ($param == 'exportar'){}
			else{
				$this->db->limit($config['per_page'],$this->data['current_pag']);
			}
			$this->db->where($where);
			$query_master =$this->db->select('cta.monto_deuda as monto_deuda,trib_com.tribunal as tribunal_padre_comuna,cta.monto_pagado_new as monto_pagado_new, cta.fecha_ultimo_pago as fecha_ultimo_pago,pagare.fecha_asignacion as fecha_pagare,cta.id AS id,SUM(pag2.monto_remitido) AS total,estado.estado AS estado,etapas.etapa AS etapa , pag.fecha_pago AS fecha_pago,cta.monto_deuda AS monto_deuda,cta.id_mandante AS id_mandante,mand.codigo_mandante AS codigo_mandante,cta.fecha_asignacion AS fecha_asignacion, cta.id AS id, cta.activo AS activo, cta.publico AS publico, cta.posicion AS posicion, usr.rut AS rut,mand.clase_html AS clase_html, cta.rol AS rol, adm.nombres AS nombres, adm.apellidos AS apellidos, mand.razon_social, etapas.etapa AS etapa, usr.nombres AS usr_nombres, usr.ap_pat AS usr_ap_pat, usr.ap_mat AS usr_ap_mat, usr.direccion AS direccion, usr.direccion_numero AS direccion_numero, usr.direccion_dpto AS direccion_dpto, usr.ciudad AS ciudad, comu.nombre AS comuna, estado.estado AS estado, estado.id AS id_estado_cuenta, cta.id_mandante AS field_categoria')
								 ->join("0_usuarios usr", "usr.id = cta.id_usuario AND usr.activo='S' AND cta.activo='S'")
								 ->join("0_mandantes mand", "mand.id = cta.id_mandante")
								 ->join("0_administradores adm", "adm.id = cta.id_procurador","left")
								 ->join("2_cuentas_pagos pag2", "pag2.id_cuenta = cta.id AND pag2.activo='S'","left")
								 
								// ->join_sql("LEFT JOIN 2_cuentas_etapas cetapa ON cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)")
								 //->join("2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id AND cetapa.activo='S' AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)","left")
											
								 ->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S' AND pag.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=cta.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left")
								 ->join("s_estado_cuenta estado", "estado.id = cta.id_estado_cuenta","left") 
								 //->join("s_comunas com", "com.id = usr.id_comuna","left") 
								 
								 //******
								 ->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left")
								->join("s_comunas comu", "comu.id = 2cd.id_comuna","left")
								->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left")
								 //****
								 
								 
								 //->join("2_cuentas_etapas cetapa", "cetapa.id_cuenta = cta.id AND cetapa.activo='S'","left")// AND cetapa.id = (SELECT id FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta=cta.id ORDER BY 2_ce.fecha_etapa DESC LIMIT 0,1)","left")
								 ->join("s_etapas etapas", "etapas.id = cta.id_etapa","left")
								 ->join("pagare pagare", "pagare.idcuenta = cta.id","left")
								// ->where("(cetapa.fecha_etapa IS NULL OR cetapa.fecha_etapa = (SELECT MAX(fecha_etapa) FROM 2_cuentas_etapas 2_ce WHERE 2_ce.id_cuenta = cta.id AND 2_ce.activo='S'))")
								 
								 ->where("cta.activo","S")
								 ->like($like,'match','after')
								 //->order_by("id_mandante", "DESC")
								 ->group_by("cta.id")
								 //->order_by("pag.fecha_pago", "DESC")
								 ->order_by("cta.fecha_asignacion", "DESC")
				 				 ->get("0_cuentas cta");
			$this->data['lists']=$query_master->result();
			
			//echo count($this->data['lists']);
			
			if (isset($_REQUEST['agrupar']) && $_REQUEST['agrupar']=='S'){
				$master_key = array();
				if (count($this->data['lists'])>0){
					foreach ($this->data['lists'] as $key=>$val){
						if (!in_array($val->id, $master_key)){ 
							$master_key[] = $val->id;
							$final_lists[] = $this->data['lists'][$key];
						}
					}
				}
				$this->data['lists'] = array();
				$this->data['lists'] = $final_lists;
			}			
			
	    	$array_csv = array();
	    	$array_csv[]=array('Mandante','Estado Cuenta','Rut','Deudor',utf8_decode('Monto Pagaré'),utf8_decode('Fecha Pagaré'),'Etapa del Juicio',utf8_decode('Fecha Último Pago'),'Saldo deuda');    	
	    	foreach ($this->data['lists'] as $obj) {
	    		$fecha_pago='';if ($obj->fecha_pago!='0000-00-00' && $obj->fecha_pago!=''){ $fecha_pago = date("d-m-Y", strtotime($obj->fecha_pago));}
	    		$fecha_asignacion=''; if ($obj->fecha_asignacion!='0000-00-00' && $obj->fecha_asignacion!=''){ $fecha_asignacion = date("d-m-Y", strtotime($obj->fecha_asignacion));}
	    		$deuda = 0; $pagado = 0; if ((int)$obj->total>0){ $pagado = $obj->total;}
	    		$deuda = $obj->monto_deuda-$pagado;
				$array_csv[] = array(utf8_decode($obj->razon_social),$obj->estado,$obj->rut,utf8_decode($obj->usr_nombres.' '.$obj->usr_ap_pat.' '.$obj->usr_ap_mat),$obj->monto_deuda,$fecha_asignacion,utf8_decode($obj->etapa),$fecha_pago,$deuda);
			}
			
			$this->data['total']=$config['total_rows'];
			$this->pagination->initialize($config);
			$this->data['suffix'] = $config['suffix'];
			$this->data['plantilla']='cuentas/reportes/reporte_estados';
	    }//estados
		
		if ($param == 'exportar'){
			$this->show_tpl = FALSE;
			$this->load->helper('csv');
	
			//$this->output->enable_profiler(TRUE);
			header('Content-Type:text/html; charset=UTF-8');  
			array_to_csv ( $array_csv, 'reporte.csv' );
			/*
			if ($tipo == 'pagos' or $tipo=='gastos') {
				array_to_csv ( $array_csv, 'reporte.csv' );
			} else {
				query_to_csv ( $query_master, TRUE, 'reporte.csv' );
			}*/
		}
		$this->db->where(array('activo' => $this->activo, 'public' => $this->activo, 'perfil' => '3'));
		$a=$this->administradores_m->get_many_by(array('activo'=>'S'));
		$this->data['procuradores'][0]='Seleccionar';
		foreach ($a as $obj) {$this->data['procuradores'][$obj->id] = $obj->nombres.' '.$obj->apellidos;}
		$this->data['mandantes'][0]='Seleccionar';
		$a=$this->mandantes_m->get_many_by(array('activo'=>'S'));
		foreach ($a as $obj) {$this->data['mandantes'][$obj->id] = $obj->codigo_mandante;}
		
		$this->data['suffix'] = $config['suffix'];
		
		if ($this->show_tpl){
			$this->load->view ( 'backend/index', $this->data );
		}
	}
	//// 333
	
	function reporte_jurisdiccion(){
		$this->data['sub_current'] = 'reporte_jurisdiccion';
		$view = '/reportes/reporte_jurisdiccion';
		$this->data['plantilla'].=$view;
		$cuen = $this->cuentas_m->get_reporte_jurisdiccion();
		$cuentas = $cuen->result();
		$this->data['total'] = count($cuentas);
		$this->data['lists'] = $cuentas;
	   $this->load->view ( 'backend/index', $this->data );
	}
	// eee
	function reporte_fecha_asignacion(){
		$this->data['sub_current'] = 'reporte_fecha_asignacion';
		$view = '/reportes/reporte_fecha_asignacion';
		$this->data['plantilla'].=$view;
		$where = array();
		$where['DATEDIFF(NOW(),fecha_asignacion) <='] = 90;
		$rfa = $this->cuentas_m->get_reporte_fecha_asignacion($where);
		$reportes_fecha_asignacion = $rfa->result();
		
		/////
		//mayores a 90 y menos de 120 
		$where = array();
		$where['DATEDIFF(NOW(),fecha_asignacion) >']= 90;
		$where['DATEDIFF(NOW(),fecha_asignacion) <=']= 120;
		$rfa3 = $this->cuentas_m->get_reporte_fecha_asignacion($where);
		$reportes_fecha_asignacion_3 = $rfa3->result();
		
		$where = array();
		$where['DATEDIFF(NOW(),fecha_asignacion) >'] = 120;
		$rfa4 = $this->cuentas_m->get_reporte_fecha_asignacion($where);
		$reportes_fecha_asignacion_4 = $rfa4->result();
		
		
		//$this->data['total'] = count($reportes_fecha_asignacion);
	    $this->data['lists'] = $reportes_fecha_asignacion;
	    $this->data['lists3'] = $reportes_fecha_asignacion_3;
	    $this->data['lists4'] = $reportes_fecha_asignacion_4;
	    $this->load->view ( 'backend/index', $this->data );
	}

	function comparaFechas($fecha1, $fecha2){
        // Formato de fecha Y-m-d
        $a = new DateTime($fecha1);
        $b = new DateTime($fecha2);
        $res = ($a > $b) ? "mayor" : (($a < $b) ? "menor" : "igual");
        return $res;
    }

	function cruce_pjud(){
        //lIMPIAR TABLA DE MOVIMIENTOS
        $this->movimiento_cuenta->delete_all();
		$this->db->select('
			cta.id AS id,
			cta.id_abogado,
			cta.rol AS rol,
			cta.id_tribunal
			');
		$this->db->where('cruce_pjud', 0);
		$this->db->group_by("cta.id");
		$query = $this->db->get('0_cuentas cta');
		$data1 = $query->result();

		$this->pjud($data1, 1);

		$this->db->select('
			cta.id AS id,
			cta.id_abogado,
			cta.rol AS rol,
			cta.id_tribunal
			');
		$this->db->where('cruce_pjud', 0);
		$this->db->group_by("cta.id");
		$query = $this->db->get('0_cuentas_n_a cta');
		$data2 = $query->result(); //print_r($data2); die;

		$this->pjud($data2, 2);
		
		//$this->index();
		//$abogados = $this->abogados_m->get_all();
		echo json_encode(array('result' => 1));
		die();
	}

	function pjud($data, $opt){
		$url = "https://civil.pjud.cl/CIVILPORWEB/ConsultaDetalleAtPublicoAccion.do?TIP_Consulta=1&TIP_Cuaderno=1&";
		define('USER_AGENT', 'Mozilla / 5.0 (Windows NT 5.1) AppleWebKit / 537.36 (KHTML, como Gecko) Chrome / 35.0.2309.372 Safari / 537.36');
        define('COOKIE_FILE', 'cookie.txt');
		foreach ($data as $key => $value){
			$etapa = $tramite = $descripcion = $fecha = '';
			$rol = $value->rol;
			$rol = explode('-', $rol);

			$aux = $url;
			$url = $url.'ROL_Causa='.$rol[1].'&TIP_Causa='.$rol[0].'&ERA_Causa='.$rol[2].'&COD_Tribunal='.$value->id_tribunal.'&TIP_Informe=1&';
			//echo $url; die;
			$curl = curl_init();
	        curl_setopt($curl, CURLOPT_HEADER, FALSE);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($curl, CURLOPT_COOKIESESSION, TRUE);
	        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_POST, true);

	        $results = curl_exec($curl);
	        $aaa = $url;
	        $url = $aux;
	        if($results === false){
	            echo 'Curl error: ' . curl_error($curl);
	        }
	        curl_close($curl);

	        if (strpos($results, 'Cannot find bean under name') !== false) {
	            if($opt==1){
			        //ACTUALIZO CRUCE PJUD EN CUENTA
			        $datos_cuenta = array();
			        $datos_cuenta['causa_reservada'] = 1;
			        $this->cuentas_m->update($value->id, $datos_cuenta, false, true);
			    }
			    else{ //die('eee');
			    	$datos_cuenta = array();
			        $datos_cuenta['causa_reservada'] = 1;
			        $this->cuentas_na->update($value->id, $datos_cuenta, false, true);
			    }
	        }
	        else{
		        libxml_use_internal_errors(true);
		        libxml_clear_errors();
		        $this->dom = new DOMDocument();
		        $this->dom->loadHTML($results);
		        $this->dom->preserveWhiteSpace = false;
		        $div = $this->dom->getElementById('Historia');
		        if($div){
			        $tables = $div->getElementsByTagName('table');
			        $rows = $tables->item(1)->getElementsByTagName('tr');
			        $fecha_ant = '';
			        foreach ($rows as $row){
			            $cols = $row->getElementsByTagName('td');
			            $fecha = trim($cols->item(6)->nodeValue);
			            if(strlen($fecha) > 10){
			                $fecha = explode(' ', $fecha);
			                $f = explode('/', $fecha[0]);
			            }
			            else
			                $f = explode('/', $fecha);
			            $fecha = $f[2].'-'.$f[1].'-'.$f[0];

			            if($fecha_ant){
			                $compara = $this->comparaFechas($fecha, $fecha_ant);
			                if($compara == 'mayor'){
			                    $fecha_ant = $fecha;
			                    $etapa = trim($cols->item(3)->nodeValue);
			                    $tramite = trim($cols->item(4)->nodeValue);
			                    $descripcion = trim($cols->item(5)->nodeValue);
			                    //URL DOCUMENTO
			                    $image = $row->getElementsByTagName('img')->item(0);
					            if($image){
					                $url_doc = trim($image->getAttribute('onclick'), ")'");
					                if($url_doc){
					                    //$url_doc = explode("ShowPDFCabecera('", $url_doc);
					                    $url_doc = substr($url_doc, 19);
					                    $url_doc = 'https://civil.pjud.cl/'.$url_doc;
					                }
					            }
			                }
			                elseif($compara == 'igual'){
			                    if($etapa == '' || $tramite == '' || $descripcion == ''){
			                        $etapa = trim($cols->item(3)->nodeValue);
			                        $tramite = trim($cols->item(4)->nodeValue);
			                        $descripcion = trim($cols->item(5)->nodeValue);
			                        //URL DOCUMENTO
				                    $image = $row->getElementsByTagName('img')->item(0);
						            if($image){
						                $url_doc = trim($image->getAttribute('onclick'), ")'");
						                if($url_doc){
						                    //$url_doc = explode("ShowPDFCabecera('", $url_doc);
						                    $url_doc = substr($url_doc, 19);
						                    $url_doc = 'https://civil.pjud.cl/'.$url_doc;
						                }
						            }
			                    }
			                }
			                else
			                    $fecha = $fecha_ant;
			            }
			            else{
			                $fecha_ant = $fecha;
			                $etapa = trim($cols->item(3)->nodeValue);
			                $tramite = trim($cols->item(4)->nodeValue);
			                $descripcion = trim($cols->item(5)->nodeValue);
			                //URL DOCUMENTO
		                    $image = $row->getElementsByTagName('img')->item(0);
				            if($image){
				                $url_doc = trim($image->getAttribute('onclick'), ")'");
				                if($url_doc){
				                    //$url_doc = explode("ShowPDFCabecera('", $url_doc);
				                    $url_doc = substr($url_doc, 19);
				                    $url_doc = 'https://civil.pjud.cl/'.$url_doc;
				                }
				            }
			            }
			        }
			        if($opt==1){
				        $data_save = array();
				        $data_save['id_cuenta'] = $value->id;
				        $data_save['url'] = $url_doc;
				        $data_save['etapa'] = $etapa;
				        $data_save['tramite'] = $tramite;
				        $data_save['descripcion'] = $descripcion;
				        $data_save['fecha'] = Date('Y-m-d', strtotime($fecha));
				        //print_r($data_save); die;
				        $this->movimiento_cuenta->insert($data_save, false, true);
				        //ACTUALIZO CRUCE PJUD EN CUENTA
				        $datos_cuenta = array();
				        $datos_cuenta['cruce_pjud'] = 1;
				        $this->cuentas_m->update($value->id, $datos_cuenta, false, true);
				        //ACTUALIZO CRUCE PJUD EN ABOGADOS
				        $idabog = $value->id_abogado;
						$datos_abog = array();
						$abogad = $this->abogados_m->get_by(array("id" => $idabog));
						$datos_abog['total_match'] = intval($abogad->total_match) + 1;
						$this->abogados_m->update($idabog, $datos_abog, false, true);
					}
					else{
						$data_save = array();
				        $data_save['id_cuenta'] = $value->id;
				        $data_save['url'] = $url_doc;
				        $data_save['etapa'] = $etapa;
				        $data_save['tramite'] = $tramite;
				        $data_save['descripcion'] = $descripcion;
				        $data_save['fecha'] = Date('Y-m-d', strtotime($fecha));
				        //print_r($data_save); die;
				        $this->movimiento_cuenta->insert($data_save, false, true);
				        //ACTUALIZO CRUCE PJUD EN CUENTA
				        $datos_cuenta = array();
				        $datos_cuenta['cruce_pjud'] = 1;
				        $this->cuentas_na->update($value->id, $datos_cuenta, false, true);
				        //ACTUALIZO CRUCE PJUD EN ABOGADOS
				        $idabog = $value->id_abogado;
						$datos_abog = array();
						$abogad = $this->abogados_m->get_by(array("id" => $idabog));
						$datos_abog['total_match'] = intval($abogad->total_match) + 1;
						$this->abogados_m->update($idabog, $datos_abog, false, true);
					}
			    }
			}
		} //die;
	}
		
	function importar_excel_cuentas(){
		$this->load->helper ( 'excel_reader2' );
		$array_return = array();
		$array_return['usuarios_insert'] = 0;
		$array_return['usuarios_update'] = 0;
		$array_return['cuentas_insert'] = 0;
		$array_return['cuentas_update'] =0;
		/*$this->data['plantilla'] = 'cuentas/';
		$view = 'cargar';
		$this->data['plantilla'].=$view;*/
		$this->data['operacion'] = FALSE;
		//$this->output->enable_profiler(TRUE);
			$rows_insert = array();
			$uploadpath = "./uploads/importar.xls";
			
			if (is_file( $uploadpath )){
				chmod($uploadpath, 0777);
			}
			
			$excel = new Spreadsheet_Excel_Reader($uploadpath);
			$rowcount = $excel->rowcount($sheet_index=0); $colcount = $excel->colcount($sheet_index=0);
			for ($i=2;$i<=$rowcount;$i++){
				$arreglo_usuario = array();
				$arreglo_cuenta = array();
				
				$rut = '';$ap_pat = '';$ap_mat =  '';$nombres =  '';$telefono =  '';$celular =  '';$direccion =  '';
				$direccion_numero =  '';$direccion_dpto =  '';$comuna =  '';$ciudad =  '';$n_pagare =  '';$monto_deuda =  '';
				$monto_deuda = '';$fecha_asignacion = '';$distrito =  '';$rol =  '';$fecha_demanda = '';
				$monto_demandado =  '';$monto_demandado = '';$id_tipo_producto =  '';$id_estado_cuenta =  '';$id_procurador =  '';
				$obs = ''; 
				$n_pagare1 = ''; $n_pagare2 = ''; $propia_cedida = ''; $exhorto='';
				
				/*if( trim($excel->val ( $i, 'C', 0 )) == ''){					
					$rut = trim( $excel->val ( $i, 'B', 0 ) );
				}else{
					$rut = trim( $excel->val ( $i, 'B', 0 ) . '-' . $excel->val ( $i, 'C', 0 ) );//swcobranza
				}*/
				
				if( $this->nodo->nombre == 'fullpay'){
					$rut = trim( str_replace('.','',$excel->val ( $i, 'B', 0 )) );
				}
				
				if( $this->nodo->nombre == 'swcobranza'){
					$rut = trim( $excel->val ( $i, 'B', 0 ) . '-' . $excel->val ( $i, 'C', 0 ) );//swcobranza
				}
				
				
				$ap_pat = trim($excel->val ( $i, 'D', 0 ));
				$ap_mat =  trim($excel->val ( $i, 'E', 0 ));
				$nombres =  trim($excel->val ( $i, 'F', 0 ));
				
				$telefono =  trim($excel->val ( $i, 'G', 0 ));
				$celular =  trim($excel->val ( $i, 'H', 0 ));
				$direccion =  trim($excel->val ( $i, 'I', 0 ));
				$direccion_numero =  trim($excel->val ( $i, 'J', 0 ));
				$direccion_dpto =  trim($excel->val ( $i, 'K', 0 ));
				
				$comuna =  utf8_encode(trim($excel->val ( $i, 'L', 0 )));
				$ciudad =  trim($excel->val ( $i, 'M', 0 ));
				$n_pagare1 =  trim(str_replace('.','',$excel->val ( $i, 'N', 0 )));
				
				
				//$monto_deuda = ereg_replace("[^0-9]", "", $monto_deuda);
				
				$fecha_asignacion = str_replace('/','-',trim($excel->val ( $i, 'P', 0 )));
				
				if (!empty($fecha_asignacion)){$fecha_asignacion = date( 'Y-m-d' , strtotime( $fecha_asignacion));}
				$distrito =  trim($excel->val ( $i, 'Q', 0 ));
				$rol =  trim($excel->val ( $i, 'R', 0 ));
				$fecha_demanda = str_replace('/','-',trim($excel->val ( $i, 'S', 0 )));
				if (!empty($fecha_demanda)){$fecha_demanda =  date( 'Y-m-d' , strtotime( $fecha_demanda));}
				$monto_demandado =  trim($excel->val ( $i, 'T', 0 ));
				$monto_demandado = @ereg_replace("[^0-9]", "", $monto_demandado);
				$id_tipo_producto =  trim($excel->val ( $i, 'U', 0 ));
				$id_estado_cuenta =  trim($excel->val ( $i, 'V', 0 ));
				$id_procurador =  trim($excel->val ( $i, 'W', 0 ));
				
				if( $this->nodo->nombre == 'fullpay'){
					
					$monto_pagare1 =  str_replace(',','.',trim($excel->val ( $i, 'O', 0 )) );
					
					$n_pagare2 =  trim(str_replace('.','',$excel->val ( $i, 'X', 0 )));
					$monto_pagare2 =  str_replace(',', '.', trim($excel->val ( $i, 'Y', 0 )) );
					$fecha_pagare1 =  trim($excel->val ( $i, 'Z', 0 ));
					$fecha_vencimiento_pagare1 =  trim($excel->val ( $i, 'AA', 0 ));
					$fecha_pagare2 =  trim($excel->val ( $i, 'AB', 0 ));
					$fecha_vencimiento_pagare2 =  trim($excel->val ( $i, 'AC', 0 ));
					
					$propia_cedida =  trim($excel->val ( $i, 'AD', 0 ));
					$exhorto =  trim($excel->val ( $i, 'AE', 0 ));
					
					/*PAGARE 3*/
					$n_pagare3 =  trim(str_replace('.','',$excel->val ( $i, 'AF', 0 )));
					$monto_pagare3 =  str_replace(',', '.', trim($excel->val ( $i, 'AG', 0 )) );
					$fecha_pagare3 =  trim($excel->val ( $i, 'AH', 0 ));
					$fecha_vencimiento_pagare3 =  trim($excel->val ( $i, 'AI', 0 ));
					/* ### */
					
					$monto_deuda = ( $monto_pagare1 + $monto_pagare2 + $monto_pagare3 );
				}
				
				if( $this->nodo->nombre == 'swcobranza'){
					
					$monto_pagare1 =  str_replace(',','.',trim($excel->val ( $i, 'O', 0 )) );
					
					$obs =  trim($excel->val ( $i, 'X', 0 ));
					$fecha_pagare1 = $fecha_asignacion;
					$fecha_vencimiento_pagare1 = '';
					
					$monto_deuda = $monto_pagare1;
				}
				
				$id_comuna = '';
								
				if (!empty($comuna)){
					$a=$this->comunas_m->get_many_by( array("nombre" => $comuna) );
					if (count($a)>0){foreach ($a as $obj) {$id_comuna = $obj->id;}}
				}
				if ($id_comuna == NULL){$id_comuna = '0';}
				//if ($id_comuna==0){echo $rut.' - '.$comuna.' :'.$id_comuna.'<br>';}
				 $id_tribunal = '';
				if ($distrito!=''){
					$a=$this->tribunales_m->get_many_by( array("abr" => $distrito) );
					if (count($a)>0){foreach ($a as $obj) {$id_distrito = $obj->id; $id_tribunal = $obj->padre;}}
				}
				
				//$rrut = str_replace('-', '', $rut);
				if(! empty( $rut ) ){
					
					$id=$this->usuarios_m->search_id_record_exist(array('rut'=>$rut));
					
					if (!empty($rut)){$arreglo_usuario['rut'] = $rut;}
					if (!empty($nombres)){$arreglo_usuario['nombres'] = utf8_encode($nombres);}
					if (!empty($ap_pat)){$arreglo_usuario['ap_pat'] = utf8_encode($ap_pat);}
					if (!empty($ap_mat)){$arreglo_usuario['ap_mat'] = utf8_encode($ap_mat);}
					//if (!empty($telefono)){$arreglo_usuario['telefono'] = $telefono;}
					//if (!empty($celular)){$arreglo_usuario['celular'] = $celular;}
					//if (!empty($direccion)){$arreglo_usuario['direccion'] = utf8_encode($direccion);}
					//if (!empty($direccion_numero)){$arreglo_usuario['direccion_numero'] = $direccion_numero;}
					//if (!empty($direccion_dpto)){ $arreglo_usuario['direccion_dpto'] = $direccion_dpto; }
					if (!empty($id_comuna)){$arreglo_usuario['id_comuna'] = $id_comuna;}
					if (!empty($ciudad)){$arreglo_usuario['ciudad'] = utf8_encode($ciudad);}

					if (!empty($n_pagare1)){$arreglo_cuenta['n_pagare'] = $n_pagare1;}
					if (!empty($monto_deuda)){$arreglo_cuenta['monto_deuda'] = $monto_deuda;}
					if (!empty($monto_demandado)){$arreglo_cuenta['monto_demandado'] = $monto_demandado;}
					if (!empty($fecha_asignacion)){$arreglo_cuenta['fecha_asignacion'] = $fecha_asignacion;}
					if (!empty($rol)){$arreglo_cuenta['rol'] = $rol;}
					if (!empty($id_tribunal)){$arreglo_cuenta['id_tribunal'] = $id_tribunal;}
					if (!empty($id_distrito)){$arreglo_cuenta['id_distrito'] = $id_distrito;}
					if (!empty($fecha_demanda)){$arreglo_cuenta['fecha_inicio'] = $fecha_demanda;}
					if (!empty($id_tipo_producto)){$arreglo_cuenta['id_tipo_producto'] = $id_tipo_producto;}
					if (!empty($id_procurador)){$arreglo_cuenta['id_procurador'] = $id_procurador;}
					if (!empty($id_estado_cuenta)){$arreglo_cuenta['id_estado_cuenta'] = $id_estado_cuenta;}

					
					if (!empty($propia_cedida)){
						if( $propia_cedida == 'P'){
							$arreglo_cuenta['tipo_demanda'] = '1';
						}else if( $propia_cedida == 'C'){
							$arreglo_cuenta['tipo_demanda'] = '0';
						}
					}
					
					if (!empty($exhorto)){
						if( $exhorto == 'C'){
							$arreglo_cuenta['exorto'] = '1';
						}else if( $exhorto == 'S'){
							$arreglo_cuenta['exorto'] = '0';
						}
					}
					
					
					$arreglo_cuenta['id_mandante'] =$_POST['id_mandante'];
					$arreglo_historial = array();
					
					$arreglo_pagare = array(); //peter
					$arreglo_pagare['id_cuenta']		= '';//peter
					$arreglo_pagare['monto_pagare_new'] = $monto_deuda;//peter
					$data_cuenta_antes_de_excel = array();//peter
					
					if ($id == ''){
						$modificando = false;
						$arreglo_usuario=array_merge($arreglo_usuario,array('fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
						//print_r($arreglo_usuario);
						//print_r($arreglo_cuenta);
						//echo '<br>INSERT '.$i.'.-'.$id.'======================>';
						$id_usuario = $this->usuarios_m->insert($arreglo_usuario,TRUE,TRUE);
						$array_return['usuarios_insert']++;
						if ($id_usuario!='' && $id_usuario != NULL){
							$arreglo_cuenta = array_merge($arreglo_cuenta, array('id_usuario' => $id_usuario, 'fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
							$this->cuentas_m->insert($arreglo_cuenta,TRUE,TRUE);
							$idcuenta_p = $this->db->insert_id();//peter
							
							
								    /* DIRECCION */
							    	if( trim($direccion) != ''){
							    		$direccion_concatenada = utf8_encode($direccion.' '.$direccion_numero.' '.$direccion_dpto);
							    		$existe_direccion = $this->direccion_m->get_by(array('direccion'=>$direccion_concatenada,'id_cuenta'=>$idcuenta_p));
							    		
							    		
							    		
							    		if (count($existe_direccion)==0){
									    	$save=array();
									    	$save['direccion'] = $direccion_concatenada;
									    	$save['estado'] = 1;
									    	$save['id_cuenta'] = $idcuenta_p;
									    	$this->direccion_m->save('',$save);
							    		}
							    	}
							    	
							    	/* TELEFONO */
							    	if( trim($telefono) != ''){
							    		$existe_telefono = $this->telefono_m->get_by(array('numero'=>$telefono,'tipo'=>1,'id_cuenta'=>$idcuenta_p));
							    		if (count($existe_telefono)==0){
									    	$save=array();
									    	$save['numero'] = $telefono;
									    	$save['estado'] = 1;
									    	$save['tipo'] = 1;
									    	$save['id_cuenta'] = $idcuenta_p;
									    	$this->telefono_m->save('',$save);
							    		}
							    	}
							    	
							    	
							    	/* CELULAR */
							    	if( trim($celular) != ''){
							    		$existe_celular = $this->telefono_m->get_by(array('numero'=>$celular,'tipo'=>3,'id_cuenta'=>$idcuenta_p));
							    		if (count($existe_celular)==0){
								    		$save=array();
									    	$save['numero'] = $celular;
									    	$save['estado'] = 1;
									    	$save['tipo'] = 3;
									    	$save['id_cuenta'] = $idcuenta_p;
									    	$this->telefono_m->save('',$save);
							    		}
							    	}
		    	
							
							
							if( $this->nodo->nombre == 'swcobranza'){
								if($obs!=''){
									$arreglo_historial['id_cuenta'] = $this->db->insert_id();
									$arreglo_pagare['id_cuenta'] = $arreglo_historial['id_cuenta'];//peter
									$arreglo_historial['historial'] = $obs;
									$arreglo_historial['fecha'] = date('Y-m-d H:i:s');
									//$arreglo_historial = array_merge($arreglo_historial, array('fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
									$this->cuentas_historial_m->insert($arreglo_historial,TRUE,TRUE);
								}
							}//fin codicion page
							
						}
					}else{
						if ($id!=NULL){
							$arreglo_usuario=array_merge($arreglo_usuario,array('fecha_mod' => date('Y-m-d H:i:s'),'ip_mod' => $this->input->ip_address(), 'user_mod' => $this->session->userdata('usuario_id')));
							
							//print_r($arreglo_usuario);
							//print_r($arreglo_cuenta);
							//echo '<br>UPDATE '.$i.'.-'.$id.'======================>';
							
							$this->usuarios_m->update($id,$arreglo_usuario,TRUE,TRUE);
							$array_return['usuarios_update']++;
							
							
							
							$id_cuenta=$this->cuentas_m->search_id_record_exist(array('id_usuario'=>$id,'id_mandante' => $_POST['id_mandante']));
							//print_r($arreglo_pagare);
							if ($id_cuenta!=''){
								$modificando = true;//peter
								$data_cuenta_antes_de_excel = $this->cuentas_m->get_by( array('id'=>$id_cuenta));//peter
								$arreglo_pagare['id_cuenta'] = $id_cuenta;//peter
								$arreglo_cuenta = array_merge($arreglo_cuenta, array('fecha_mod' => date('Y-m-d H:i:s'),'ip_mod' => $this->input->ip_address(), 'user_mod' => $this->session->userdata('usuario_id')));
								$this->cuentas_m->update($id_cuenta,$arreglo_cuenta,TRUE,TRUE);
								$idcuenta_p = $id_cuenta;//peter
								$array_return['cuentas_update']++;
							} else {
	
								$arreglo_cuenta = array_merge($arreglo_cuenta, array('id_usuario' => $id, 'fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
								$this->cuentas_m->insert($arreglo_cuenta,TRUE,TRUE);
								$idcuenta_p = $this->db->insert_id();//peter
								$array_return['cuentas_insert']++;
	
							} 
							
							
									/* DIRECCION */
							    	if( trim($direccion) != ''){
							    		$direccion_concatenada = utf8_encode($direccion.' '.$direccion_numero.' '.$direccion_dpto);
							    		$existe_direccion = $this->direccion_m->get_by(array('direccion'=>$direccion_concatenada,'id_cuenta'=>$idcuenta_p));
							    		//echo $idcuenta_p.' '.$direccion_concatenada.' '.count($existe_direccion).'<br>';
							    		if (count($existe_direccion)==0){
									    	$save=array();
									    	$save['direccion'] = $direccion_concatenada;
									    	$save['estado'] = 1;
									    	$save['id_cuenta'] = $idcuenta_p;
									    	$this->direccion_m->save('',$save);
							    		}
							    	}
							    	
							    	/* TELEFONO */
							    	if( trim($telefono) != ''){
							    		$existe_telefono = $this->telefono_m->get_by(array('numero'=>$telefono,'tipo'=>1,'id_cuenta'=>$idcuenta_p));
							    		if (count($existe_telefono)==0){
									    	$save=array();
									    	$save['numero'] = $telefono;
									    	$save['estado'] = 1;
									    	$save['tipo'] = 1;
									    	$save['id_cuenta'] = $idcuenta_p;
									    	$this->telefono_m->save('',$save);
							    		}
							    	}
							    	
							    	
							    	/* CELULAR */
							    	if( trim($celular) != ''){
							    		$existe_celular = $this->telefono_m->get_by(array('numero'=>$celular,'tipo'=>3,'id_cuenta'=>$idcuenta_p));
							    		if (count($existe_celular)==0){
								    		$save=array();
									    	$save['numero'] = $celular;
									    	$save['estado'] = 1;
									    	$save['tipo'] = 3;
									    	$save['id_cuenta'] = $idcuenta_p;
									    	$this->telefono_m->save('',$save);
							    		}
							    	}
							
							if( $this->nodo->nombre == 'swcobranza'){
								if ($obs!=''){ 
									$arreglo_historial['id_cuenta'] = $id_cuenta;
									$arreglo_historial['historial'] = $obs;
									$arreglo_historial['fecha'] = date('Y-m-d H:i:s');
									$arreglo_historial = array_merge($arreglo_historial, array('fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('usuario_id')));
									
									//$this->cuentas_historial_m->insert($arreglo_historial,TRUE,TRUE);
								}
							}//fin codicion page
							
							
						}
					}
							
					
						/* PETER */
						/* ACTUALIZAMOS PAGARE 1 */
							if( $n_pagare1 != ''){
								$pagare1 = $this->pagare_m->get_by( array('idcuenta' => $idcuenta_p, 'n_pagare'=>$n_pagare1) );
								//echo 'CUENTA '.$idcuenta_p.' NÂºPAGARE'.$n_pagare1.' = '.count($pagare1).'<br>';
								if( count($pagare1)>0 ){
									$fields = array();
									//$fields['monto_deuda'] = $arreglo_pagare['monto_pagare_new'];
									$fields['monto_deuda'] = $monto_pagare1;
									$fields['fecha_asignacion'] = $fecha_pagare1;
									
									if( strtotime($fecha_vencimiento_pagare1)>0 ){
										$fields['fecha_vencimiento'] = date("Y-m-d", strtotime($fecha_vencimiento_pagare1));
									}else{
										$fields['fecha_vencimiento'] = '';
									}
									
									$this->pagare_m->update_by(array('idpagare'=>$pagare1->idpagare,'n_pagare'=>$n_pagare1),$fields);
									
									if($idcuenta_p == ''){
										$idcuenta_p = $pagare1->idcuenta;
									}
								}else{
									$fields = array();
									$fields['idcuenta'] = $idcuenta_p;
									$fields['n_pagare'] = $arreglo_cuenta['n_pagare'];						
									//$fields['monto_deuda'] = $arreglo_pagare['monto_pagare_new'];
									$fields['monto_deuda'] = $monto_pagare1;
									if (isset($arreglo_cuenta['id_tipo_producto'])){
										$fields['id_tipo_producto'] = $arreglo_cuenta['id_tipo_producto'];
									}
									$fields['fecha_asignacion'] = $fecha_pagare1;
									
									if( strtotime($fecha_vencimiento_pagare1)>0 ){
										$fields['fecha_vencimiento'] = date("Y-m-d", strtotime($fecha_vencimiento_pagare1));
									}else{
										$fields['fecha_vencimiento'] = '';
									}

									$this->pagare_m->save_default($fields,'');
								}
							}
						/* ACTUALIZAMOS PAGARE 2 */
							if( $n_pagare2 != ''){
								$pagare2 = $this->pagare_m->get_by( array('idcuenta' => $idcuenta_p, 'n_pagare'=>$n_pagare2) );
								//echo 'CUENTA '.$idcuenta_p.' NÂºPAGARE'.$n_pagare2.' = '.count($pagare2).'<br>';
								if( count($pagare2)>0 ){
									$fields = array();
									$fields['monto_deuda'] = $monto_pagare2;
									$fields['fecha_asignacion'] = $fecha_pagare2;
									
									if( strtotime($fecha_vencimiento_pagare2)>0 ){
										$fields['fecha_vencimiento'] = date("Y-m-d", strtotime($fecha_vencimiento_pagare2));
									}else{
										$fields['fecha_vencimiento'] = '';
									}
									$this->pagare_m->update_by(array('idpagare'=>$pagare2->idpagare,'n_pagare'=>$n_pagare2),$fields);
									
									if($idcuenta_p == ''){
										$idcuenta_p = $pagare2->idcuenta;
									}
								}else{
									$fields = array();
									$fields['idcuenta'] = $idcuenta_p;
									$fields['n_pagare'] = $n_pagare2;						
									$fields['monto_deuda'] = $monto_pagare2;
									if (isset($arreglo_cuenta['id_tipo_producto'])){
										$fields['id_tipo_producto'] = $arreglo_cuenta['id_tipo_producto'];
									}
									$fields['fecha_asignacion'] = $fecha_pagare2;
									
									if( strtotime($fecha_vencimiento_pagare2)>0 ){
										$fields['fecha_vencimiento'] = date("Y-m-d", strtotime($fecha_vencimiento_pagare2));
									}else{
										$fields['fecha_vencimiento'] = '';
									}
									$this->pagare_m->save_default($fields,'');
								}
							}
							/* ACTUALIZAMOS PAGARE 3 */
							if( isset( $n_pagare3) && $n_pagare3 != ''){
								$pagare3 = $this->pagare_m->get_by( array('idcuenta' => $idcuenta_p, 'n_pagare'=>$n_pagare3) );
								//echo 'CUENTA '.$idcuenta_p.' NÂºPAGARE'.$n_pagare3.' = '.count($pagare3).'<br>';
								if( count($pagare2)>0 ){
									$fields = array();
									$fields['monto_deuda'] = $monto_pagare3;
									$fields['fecha_asignacion'] = $fecha_pagare3;
									
									if( strtotime($fecha_vencimiento_pagare3)>0 ){
										$fields['fecha_vencimiento'] = date("Y-m-d", strtotime($fecha_vencimiento_pagare3));
									}else{
										$fields['fecha_vencimiento'] = '';
									}
									$this->pagare_m->update_by(array('idpagare'=>$pagare3->idpagare,'n_pagare'=>$n_pagare3),$fields);
									
									if($idcuenta_p == ''){
										$idcuenta_p = $pagare3->idcuenta;
									}
								}else{
									$fields = array();
									$fields['idcuenta'] = $idcuenta_p;
									$fields['n_pagare'] = $n_pagare3;						
									$fields['monto_deuda'] = $monto_pagare3;
									if (isset($arreglo_cuenta['id_tipo_producto'])){
										$fields['id_tipo_producto'] = $arreglo_cuenta['id_tipo_producto'];
									}
									$fields['fecha_asignacion'] = $fecha_pagare3;
								
									if( strtotime($fecha_vencimiento_pagare3)>0 ){
										$fields['fecha_vencimiento'] = date("Y-m-d", strtotime($fecha_vencimiento_pagare3));
									}else{
										$fields['fecha_vencimiento'] = '';
									}
									$this->pagare_m->save_default($fields,'');
								}
							}
						/*ACTUALIZAMOS MONTO TOTAL EN TABLA CUENTA CAMPO ( monto_pagare_new )*/
							//$data_cuenta_antes_de_excel = $this->cuentas_m->get_by( array('id'=>$param));
							if($idcuenta_p !=''){
								if( $modificando && count($data_cuenta_antes_de_excel) > 0 ){
									
									if( $data_cuenta_antes_de_excel->monto_pagado_new != $monto_deuda ){
										
										$monto_modificado = $data_cuenta_antes_de_excel->monto_pagado_new - $monto_deuda;
										//echo $monto_deuda.' - '.$monto_modificado;
										$this->db->query('UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new - ('.$monto_modificado.') WHERE id='.$idcuenta_p );
									}
								}else{
									if($monto_deuda != ''){
										$this->db->query('UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new + ('.$monto_deuda.') WHERE id='.$idcuenta_p );
									}
								}
							}
						/* PETER */
					
				}
			}
		if ($array_return['usuarios_insert'] > 0 or $array_return['usuarios_update']>0 or $array_return['cuentas_insert'] > 0 or $array_return['cuentas_update']>0){
			$array_return['operacion'] = TRUE;
		}
		//unlink("./uploads/importar.xls");
		return $array_return;
		//if (count($rows_insert)>0){$this->usuarios_m->insert_many($rows_insert,TRUE,TRUE);}
	}
			
	public function importar_excel_nodo_swcobranza(){
		
		
		//$this->output->enable_profiler(TRUE);
		echo 'entro'; 
		
		$this->load->helper ( 'excel_reader2' );
		$array_return = array();
		$array_return['usuarios_insert'] = 0;
		$array_return['usuarios_update'] =0;
				
		$this->data['operacion'] = FALSE;
		//$this->output->enable_profiler(TRUE);
		$rows_insert = array();
		$uploadpath = "./uploads/importar_excel_nodo_swcobranza.xls";
		
		$excel = new Spreadsheet_Excel_Reader($uploadpath);
	    $rowcount = $excel->rowcount($sheet_index=0); $colcount = $excel->colcount($sheet_index=0);
	    
	    
	    for ($i=2;$i<=$rowcount;$i++){
			
	    	$arreglo_cuenta = array();
	    
	    
		$mandante = ''; $rut =''; $dv =''; $apellido_paterno =''; $apellido_materno = ''; $nombre = ''; $telefono = ''; $telefono_comercial =''; $celular ='';
		$comuna =''; $numero_pagare =''; $monto_pagare = ''; $fecha_vencimiento_pagare = ''; $distrito = ''; $tribunal ='';
	    $rol = ''; $fecha_demanda = ''; $producto =''; $estado =''; $procurador ='';  $monto_deuda_fecha_mora = ''; $fecha_mora ='';
	
	    
	    
	    
	            $mandante = trim($excel->val ( $i, 'A', 0 ));
				$rut =  trim($excel->val ( $i, 'B', 0 ));
				$dv =  trim($excel->val ( $i, 'C', 0 ));
				$apellido_paterno =  trim($excel->val ( $i, 'D', 0 ));
				$apellido_materno =  trim($excel->val ( $i, 'E', 0 ));
				$nombre =  trim($excel->val ( $i, 'F', 0 ));
				$telefono =  trim($excel->val ( $i, 'G', 0 ));
				$telefono_comercial =  trim($excel->val ( $i, 'H', 0 ));
				$celular =  utf8_encode(trim($excel->val ( $i, 'I', 0 )));
				$direccion_particular = trim($excel->val ( $i, 'J', 0 ));
				$direccion_comercial = trim($excel->val ( $i, 'K', 0 ));
				$comuna =  utf8_encode(trim($excel->val ( $i, 'L', 0 )));
				$numero_pagare =  trim(str_replace('.','',$excel->val ( $i, 'M', 0 )));
				$monto_pagare =  trim($excel->val ( $i, 'N', 0 ));
	            $fecha_vencimiento_pagare = trim($excel->val ( $i, 'O', 0 ));
				$fecha_vencimiento_pagare = date("Y-m-d", strtotime($fecha_vencimiento_pagare));
				$distrito = trim($excel->val ( $i, 'P', 0 ));
				$tribunal = trim($excel->val ( $i, 'Q', 0 ));
				$rol= trim($excel->val ( $i, 'R', 0 ));
				$fecha_demanda = trim($excel->val ( $i, 'S', 0 ));
				$producto= trim($excel->val ( $i, 'T', 0 ));
				$estado= trim($excel->val ( $i, 'U', 0 ));
				
				$procurador= trim($excel->val ( $i, 'V', 0 ));
				$monto_deuda_fecha_mora= trim($excel->val ( $i, 'W', 0 ));
				
				$fecha_mora = trim($excel->val ( $i, 'X', 0 ));
				$fecha_mora = date("Y-m-d", strtotime($fecha_mora));
				
				
				
			// usuarios 	
	            if(! empty($rut) ){
					
					$id=$this->usuarios_m->search_id_record_exist(array('rut'=>$rut));
					
					if (!empty($rut)){$arreglo_usuario['rut'] = $rut;}
	               // if (!empty($dv)){$arreglo_usuario['dv'] = $dv;}
					if (!empty($nombre)){$arreglo_usuario['nombres'] = utf8_encode($nombre);}
					if (!empty($apellido_paterno)){$arreglo_usuario['ap_pat'] = utf8_encode($apellido_paterno);}
					if (!empty($apellido_materno)){$arreglo_usuario['ap_mat'] = utf8_encode($apellido_materno);}
				
				    $idusuario=$this->usuarios_m->search_id_record_exist(array('rut'=>$rut));
							//print_r($arreglo_pagare);
							if ($idusuario!=''){
							    $arreglo_usuario = array_merge($arreglo_usuario, array('fecha_mod' => date('Y-m-d H:i:s'),'ip_mod' => $this->input->ip_address(), 'user_mod' => $this->session->userdata('id')));
								$this->usuarios_m->update($idusuario,$arreglo_usuario,TRUE,TRUE);
								$array_return['usuarios_update']++;
							} else {
	                            $arreglo_usuario = array_merge($arreglo_usuario, array('id' => $id, 'fecha_crea' => date('Y-m-d H:i:s'),'ip_crea' => $this->input->ip_address(), 'user_crea' => $this->session->userdata('id')));
								$this->usuarios_m->insert($arreglo_usuario,TRUE,TRUE);
								$array_return['usuarios_insert']++;
	                        }
				       }

	      $idusuario=$this->usuarios_m->search_id_record_exist(array('rut'=>$rut));
		  $idusuario=$this->usuarios_m->search_id_record_exist(array('rut'=>$rut));	       
				       
				       
	    
	    
	             }
	     }
		
	function importar_excel_etapas(){
		$this->load->helper ( 'excel_reader2' );
		//$this->output->enable_profiler(TRUE);
		$rows_insert = array();
		$uploadpath = "./uploads/estados.xls";
		$excel = new Spreadsheet_Excel_Reader($uploadpath);
		$rowcount = $excel->rowcount($sheet_index=0); $colcount = $excel->colcount($sheet_index=0);
		for ($i=1;$i<=$rowcount;$i++){
			$etapa=utf8_encode($excel->val($i,'A',0)); $codigo=$excel->val($i,'B',0);
			if ($etapa!='' && $codigo!='' && $codigo!=NULL){
				$id=$this->etapas_m->search_id_record_exist('codigo',$codigo);
				$arreglo = array('etapa' => $etapa,'codigo' => $codigo);
				if ($id == ''){
					$rows_insert[]=$arreglo;
				}else{
					$this->etapas_m->update($id,$arreglo,TRUE,TRUE);
				}
			}
		}
		if (count($rows_insert)>0){$this->etapas_m->insert_many($rows_insert,TRUE,TRUE);}
	}
	
	function importar_excel_tribunales(){
		$this->load->helper ( 'excel_reader2' );
			
		$rows_insert = array();
		$uploadpath = "./uploads/tribunales.xls";
		$excel = new Spreadsheet_Excel_Reader($uploadpath);
		$rowcount = $excel->rowcount($sheet_index=0); $colcount = $excel->colcount($sheet_index=0);
		for ($i=1;$i<=$rowcount;$i++){
			$padre=$excel->val($i,'A',0); $tribunal= $excel->val($i,'B',0); $abr=$excel->val($i,'C',0);
			if ($tribunal!=''){
				$id=$this->tribunales_m->search_id_record_exist(array('tribunal'=>$tribunal));
				$arreglo = array('padre' => $padre,'tribunal' => $tribunal,'abr' => $abr);
				//print_r($arreglo); echo '<br>';
				if ($id == ''){
					$rows_insert[]=$arreglo;
				}else{
					$this->tribunales_m->update($id,$arreglo,TRUE,TRUE);
				}
			}
		}
		if (count($rows_insert)>0){$this->tribunales_m->insert_many($rows_insert,TRUE,TRUE);}
	}
		
	public function process_load_pagare($idcuenta=''){
	
		$c1[] = $this->pagare_m->get_columns();
		$c2[] = $this->tipo_productos_m->get_columns();
		
		$c = array_merge($c1, $c2);
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = $dato;
			}
		}
		
		$this->db->order_by('pa.fecha_asignacion', 'DESC');
		
		if($idcuenta !=''){
			$this->db->where('pa.idcuenta', $idcuenta);
		}
		
		$this->db->select($cols);
		$this->db->from('pagare pa');
		$this->db->join('s_tipo_productos tp', 'tp.id = pa.id_tipo_producto','left');
		$query = $this->db->get();
		
		$this->data['pagares'] = $query->result();

		/*echo '<pre>';
		print_r($this->data['pagares']);
		echo '</pre>';*/
	}
	
	public function delete_pagare($idpagare='',$idcuenta=''){
		if($idpagare != '' && $idcuenta != '' ){
			$this->pagare_m->delete_by( array('idpagare'=>$idpagare));
			redirect('admin/cuentas/form/editar/'.$idcuenta.'#pagare');
		}
	}
	
	public function prueba(){
		/*$numero = '1';
		$idcuenta = '3';
		$query = $this->db->query('UPDATE 0_cuentas SET monto_pagado_new = monto_pagado_new + ('.$numero.') WHERE id='.$idcuenta );
		print_r($query);*/
		
		$this->db->order_by('fecha_asignacion', 'ASC');
		$pagare = $this->pagare_m->get_by( array('idcuenta' => 3) );
		
		//echo '<pre>';
		//print_r($pagare);
		//echo '</pre>';
	}

	public function cal_interes($tipo_formula='')
	{
		$valor_cuota		= $this->input->post('valor_cuota');
		$cuotas 			= $this->input->post('n_cuotas');
		$intereses			= $this->input->post('intereses');
		$id_cuenta 			= $this->input->post('id_cuenta');
		$total_deuda 		= $this->input->post('monto_deuda');
		$diferencia_dias 	= 0;
		
		$porcentaje = ($intereses/100);
		

		$pagos = $this->cuentas_pagos_m->get_cuentas_pagos($id_cuenta);
		
		$pagares = $this->pagare_m->get_pagares_cuentas( $id_cuenta );
		
		
		$diferencia_dias_primer_pago = 0;
		
		if( count($pagares) ){
			$diferencia_dias = $pagares[0]->diferencia_dias;
			
		}
		if( count($pagos) ){
			$diferencia_dias = $pagos[0]->diferencia_dias;
			$diferencia_dias_primer_pago = $pagos[0]->diferencia_dias_primer_pago;
			
		}
		
		$cuenta = $this->cuentas_m->get_by( array('id'=>$id_cuenta) );
		if( count($cuenta) ){
			$monto_pagado = $cuenta->monto_pagado_new;
		}else{
			$monto_pagado = 0;
		}
		
		$total_deuda = $total_deuda - $monto_pagado;
		
		$interes = $porcentaje*$total_deuda*($diferencia_dias/30);
		
		if( $tipo_formula == 'x_cuotas' && $this->input->post('n_cuotas') != 0){
			$x = $this->input->post('n_cuotas');
			echo (($total_deuda + abs($interes))/$x);
			
		} else if( $tipo_formula == 'x_valor_cuota' && $this->input->post('valor_cuota') != 0){
			$x = $this->input->post('valor_cuota');
			echo '{"n_cuotas":"'.ceil( (($total_deuda + abs($interes))/$x) ).'","n_cuotas_real":"'.(($total_deuda + abs($interes))/$x).'"}';
		}
	}
	public function tabla_intereses($id_cuenta='')
	{
		$this->data['diferencia_dias']	= 0;
		$this->data['total_deuda']		= 0;
		$this->data['intereses']		= array('2.5','2.4','2.3','2.2','2.1','2');
		
		$this->db->select( "DATEDIFF( NOW(), fecha_asignacion   ) AS diferencia_dias, fecha_asignacion AS fecha_inicio" );
		$this->db->order_by('fecha_asignacion','ASC');
		$pagare = $this->pagare_m->get_by( array('idcuenta'=>$id_cuenta, 'activo'=>'S') );
		
		$this->db->select( "DATEDIFF( NOW(), fecha_pago ) AS diferencia_dias, fecha_pago AS fecha_inicio" );
		$this->db->order_by('fecha_pago DESC,fecha_crea DESC');
		$pagos = $this->cuentas_pagos_m->get_by(array('activo'=>'S','id_cuenta'=>$id_cuenta,'id_acuerdo_pago'=>0));
		
		
		$this->data['cuenta'] = $this->cuentas_m->get_by( array('id'=>$id_cuenta) );

		$this->data['diferencia_dias']		= '0';
		$this->data['fecha_comparacion']	= '0';
		$this->data['fecha_title']			= 'Fecha Inicio'; //POR DEFECTO
		$this->data['f_vencimiento']		= ''; //POR DEFECTO
		
		/*FECHA ÃšLTIMO PAGARE*/
		if( count($pagare) && strtotime($pagare->fecha_inicio) > 0 ){
			$this->data['diferencia_dias']		= $pagare->diferencia_dias;
			$this->data['fecha_comparacion']	= $pagare->fecha_inicio;
			$this->data['fecha_title']			= 'Fecha Inicio';
			$this->data['fecha_vencimiento']	= $pagare->fecha_inicio;
		}
		/*ÃšLTIMO PAGO QUE NO ES CONVENIO INGRESADO*/
		if( count($pagos)>0 && strtotime($pagos->fecha_inicio) > 0){
			$this->data['diferencia_dias']		= $pagos->diferencia_dias;
			$this->data['fecha_comparacion']	= $pagos->fecha_inicio;
			$this->data['fecha_title']			= 'Fecha Ãšltimo Pago';
			$this->data['fecha_vencimiento']		= '';
		}
		
		
		$this->db->select('SUM(monto_deuda) AS total_deuda');
		$dat_m = $this->pagare_m->get_many_by( array('idcuenta'=>$id_cuenta,'activo'=>'S') );
		
		if( count($dat_m) ){
			$this->data['total_deuda'] =( $dat_m[0]->total_deuda - $this->data['cuenta']->monto_pagado_new );
		}

		$this->load->view ( 'backend/templates/cuentas/tabla_intereses', $this->data );
	}
	
	public function cal_cuotas()
	{
		if( $this->input->post('cuotas') != '' && $this->input->post('total') != ''){
			$total = $this->input->post('total')/$this->input->post('cuotas');
			echo number_format($total,0,',','.'); 
		}
	}
	
	public function eliminaracuse($id_cuenta="")
	{
		$fields_save = array (
			'acuse' => '1'
		);
		
		if (!$this->cuentas_m->save_default($fields_save,$id_cuenta))
		{
			echo 'Cuenta actualizada';
		} else {
			echo 'Error al actualizar cuenta';
		}		
	}
	
	public function enviaracuse($id_cuenta="")
	{		
			$tipooperacion = $_POST['tipooperacion'];
			$mensaje= $_POST['operacion'];
			print_r($_POST);
			
			
			$this->db->select("c.operacion, c.rol, m.razon_social");
			$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
			if (count($like)>0){
				$this->db->like($like);
			}				
			$this->db->where('c.id', $id_cuenta);
			$this->db->where('c.activo', 'S');				
			$this->db->group_by('c.id');
			$query = $this->db->get('0_cuentas c');
			$cuen = $query->result() ;
			
			
			switch($tipooperacion)
			{
				case 1:
					$Aquien ="Cobro";
				break;
				case 2:
					$Aquien ="Sacar de rojo";
				break;
				case 3:
					$Aquien ="Otro";
				break;
				
			}
			
			
			if($tipooperacion==1)
			{			
		
				//$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
				//$cuen = $this->cuentas_m->get_all();
				//print_r($cuen);
				$rol = "";
				foreach($cuen as $key=>$val){
					$operacion = $val->operacion;
					$rol = $val->rol;
					$razon_social = $val->razon_social;
				}	
				$save=array();
				$save['idcuenta'] = $id_cuenta;
				$save['comentario'] = $mensaje;				
				$save['estado'] = '1';		
				//$save['fecha'] = date('Y-m-d H:i:s');
				$save['usuario'] = $this->session->userdata('usuario_id');
				$this->cinformadas_m->save('',$save);		
				// C-xxxxx del MANDANTE "XXXXXX" del SISTEMA "XXXXX" ha sido informada a "XXXXX"
				//$message = 'La cuenta con numero operacion ' . $operacion. ' a sido informada como a cobro' . "\r\n";		
				$message = 'La cuenta con Rol C-'. $rol  . " del mandante " .$razon_social . " del sistema " . SISTEMA . " ha sido informada a " . $Aquien . "\r\n";	
				$message .= "<br>El comentario: " .  $mensaje;
				
			}else
			{
				/*$this->db->where('id', $id_cuenta);
				$this->db->where('activo', 'S');
				$cuen = $this->cuentas_m->get_all();*/
				
				$rol = "";
				foreach($cuen as $key=>$val){
					$rol = $val->rol;
					$razon_social = $val->razon_social;
				}			
				
				/*$message = 'La cuenta con rol '. $rol  . "\r\n";	
				$message .= "<br>El comentario: " .  $mensaje  . "\r\n";*/
				
				$message = 'La cuenta con Rol C-'. $rol  . " del mandante " .$razon_social . " del sistema " . SISTEMA . " ha sido informada a " . $Aquien . "\r\n";	
				$message .= "<br>El comentario: " .  $mensaje;
			}

			$subject = 'Informe de avance de cuenta rol ' . $rol;
            $to = 'carlos.rojas.ti@hotmail.com';
            $cc = '';
			
			$para = 'contacto@gespron.cl;';
			$cabeceras = 'From: contacto@gespron.cl'. "\r\n" .
			'MIME-Version: 1.0' . "\r\n".
			'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
						
			if(mail($para, $subject, $message, $cabeceras)) {
				echo 'Informe enviado correctamente';
			} else {
				echo 'Error al enviar informe';
			}
			//die();
			//$this->session->set_userdata('success_etapas','La etapa de juicio ha sido guardado exitosamente');			
			redirect('admin/procurador');
			
	}
	
	public function list_ajax($id_cuenta=""){
		$c1[] = $this->cuentas_m->get_columns();
		$c2[] = $this->tribunales_m->get_columns();
		$c3[] = $this->usuarios_m->get_columns();
		$c4[] = $this->estados_cuenta_m->get_columns();
		$c5[] = $this->comunas_m->get_columns();
		
		$c = array_merge($c1,$c2,$c3,$c4,$c5);
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = $dato;
			}
		}
		
		//$this->db->order_by('pa.fecha_asignacion', 'DESC');
		if($id_cuenta !=''){
			$this->db->where('c.id', $id_cuenta);
		}
		$cols[] = 'tt.tribunal AS tribunal';
		$this->db->select($cols);
		$this->db->from('0_cuentas c');
		$this->db->join('s_tribunales t', 't.id = c.id_distrito','left');
		$this->db->join('s_tribunales tt', 'tt.id = c.id_tribunal','left');
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario','left');
		$this->db->join('s_estado_cuenta ec', 'ec.id = c.id_estado_cuenta','left');
		$this->db->join('s_comunas com', 'com.id = u.id_comuna','left');
		
		$query = $this->db->get();
		$this->data['cuenta'] = $query->result();
		/*##########################*/
		
		$c1=array(); $c2=array();
		$c1[] = $this->cuentas_etapas_m->get_columns();
		$c2[] = $this->etapas_m->get_columns();
		
		$cols=array();
		$c = array_merge($c1,$c2);
		foreach ($c as $campo) {
			foreach ($campo as $dato) {
				$cols[] = str_replace('2_', '', $dato);
			}
		}
		
		if($id_cuenta !=''){
			$this->db->where('ce.id_cuenta', $id_cuenta);
		}
		
		$this->db->select($cols);
		$this->db->from('2_cuentas_etapas ce');		
		$this->db->join('s_etapas e', 'e.id = ce.id_etapa','left');
		$this->db->order_by('ce.fecha_etapa','DESC');
		$this->db->order_by('ce.fecha_crea','DESC');
		$query = $this->db->get();
		$this->data['etapas'] = $query->result();
		/*##########################*/
		
		
		if($id_cuenta !=''){
			$this->db->where('idcuenta', $id_cuenta);
		}
		$this->data['pagares'] = $pagares = $this->pagare_m->get_all();
		
		$monto_deuda = 0;
		foreach($pagares as $key=>$val){
	    	$monto_deuda =  ($monto_deuda + $val->monto_deuda);
	  	}
	  	$this->data['monto_deuda'] = $monto_deuda;
  
		
	  	$this->data['direccion_list'] = $this->direccion_m->get_by( array('id_cuenta'=>$id_cuenta,'estado'=>'1') );
		$this->db->where( array('id_cuenta'=>$id_cuenta,'estado <='=>'1') );
		$this->data['telefono_list'] = $this->telefono_m->get_all();
		$this->data['bien_list'] = $this->bienes_m->get_many_by( array('id_cuenta'=>$id_cuenta,'estado <='=>'1') );
	  	
		$this->load->view ( 'backend/templates/cuentas/list_ajax', $this->data );
	}
	
	
	public function get_diligencia($item_gasto='')
	{
		if($item_gasto!=''){
			$this->db->where( 'item_gasto',$item_gasto);
		}
		
		$diligencias = $this->diligencia_m->get_all();
		
		echo '<option selected="selected" value="">Seleccionar</option>';
		foreach($diligencias as $key=>$val){
		    echo '<option value="'.$val->id.'">'.$val->nombre.'</option>';
		}  
	}
	
	public function cal_diligencia($id_diligencia='',$idcuenta= ''){
		
		//$this->output->enable_profiler ( TRUE );
		$monto_gasto = 0;
		if($this->data['nodo']->nombre == 'fullpay'){
		$where = array ();
		}
		
		if($this->data['nodo']->nombre == 'swcobranza'){
		$cuentas_diligencias = $this->cuentas_m->get_by ( array ('id' => $idcuenta ) );
		if (count ( $cuentas_diligencias ) > 0) {
			$idmandante = $cuentas_diligencias->id_mandante;
			$monto_deuda = $cuentas_diligencias->monto_deuda;
		}
		
		
		$where = array ();
		if ($idmandante != '') {
			$where ['id_mandante'] = $idmandante;
		} else {
			$where ['id_mandante'] = '0';
		}
		
		$where ['rango1 <='] = $monto_deuda;
		$where ['rango2 >='] = $monto_deuda;
		}
		$where ['id_diligencia'] = $id_diligencia;
		$datos2 = $this->gasto_regla_m->get_by ( $where );
		

		if (count ( $datos2 ) > 0) {
			$monto_gasto = $datos2->monto_gasto;
		} else {
			if($this->data['nodo']->nombre == 'swcobranza'){
			$where = array ();
			$where ['rango1 <='] = $monto_deuda;
			$where ['rango2 >='] = $monto_deuda;
			}
			if($this->data['nodo']->nombre == 'fullpay'){
				$where = array();
			}
			$where ['id_diligencia'] = $id_diligencia;
			$datos2 = $this->gasto_regla_m->get_by ( $where );
			if (count ( $datos2 ) > 0) {
				$monto_gasto = $datos2->monto_gasto;
			}
		  }
		
		echo '{"status":"exito","monto_gasto":"' . $monto_gasto . '","retencion":"' . ($monto_gasto * 10 / 100) . '"}';
	
	}

	public function cal_interes111($tipo_formula='')
	{
		$valor_cuota		= $this->input->post('valor_cuota');
		$cuotas 			= $this->input->post('n_cuotas');
		$intereses			= $this->input->post('intereses');
		$id_cuenta 			= $this->input->post('id_cuenta');
		$total_deuda 		= $this->input->post('monto_deuda');
		$diferencia_dias 	= 0;
		
		$porcentaje = ($intereses/100);
		
		$this->db->select( "DATEDIFF( fecha_pago , NOW() ) AS diferencia_dias" );
		$this->db->order_by('fecha_pago DESC,fecha_crea DESC');
		$pagos = $this->cuentas_pagos_m->get_by(array('activo'=>'S','id_cuenta'=>$id_cuenta));
		
		$this->db->select( "DATEDIFF( fecha_asignacion , NOW() ) AS diferencia_dias" );
		$this->db->order_by('fecha_asignacion','ASC');
		$pagares = $this->pagare_m->get_by( array('idcuenta'=>$id_cuenta) );
		
		if( count($pagares) ){
			$diferencia_dias = $pagares->diferencia_dias;
		}
		if( count($pagos) ){
			//$diferencia_dias = $pagos->diferencia_dias;
		}
		
		$dat_c = $this->cuentas_m->get_by( array('id'=>$id_cuenta) );
		if( count($dat_c) ){
			$monto_pagado_new = $dat_c->monto_pagado_new;
		}else{
			$monto_pagado_new = 0;
		}
		
		$total_deuda = $total_deuda - $monto_pagado_new;
		
		$interes = $porcentaje*$total_deuda*($diferencia_dias/30);
		

		if( $tipo_formula == 'x_cuotas' && $this->input->post('n_cuotas') != 0){
			
			$x = $this->input->post('n_cuotas');
			echo  (($total_deuda + abs($interes))/$x);
			
		}else if( $tipo_formula == 'x_valor_cuota' && $this->input->post('valor_cuota') != 0){
			
			$x = $this->input->post('valor_cuota');
			//echo  ceil( (($total_deuda + abs($interes))/$x) );
			echo '{"n_cuotas":"'.ceil( (($total_deuda + abs($interes))/$x) ).'","n_cuotas_real":"'.(($total_deuda + abs($interes))/$x).'"}';
			
		}

		/*
		echo '<br>deuda '.$total_deuda.'<br>';
		echo 'coutas '.$cuotas.'<br>';
		echo 'interes '.abs($interes).'<br>';*/
		//echo number_format( (($total_deuda+str_replace(',', '.', $interes))/$cuotas) , 0, ',','.');
	}
		
	public function poblar_cambio_tablas()
	{
		$usuarios = $this->usuarios_m->get_all();
		
		foreach($usuarios as $key=>$val){
			
			$cta = $this->cuentas_m->get_by( array('id_usuario'=>$val->id) );
			if( count($cta)>0 ){
				$direccion =  $val->direccion.' '.$val->direccion_numero.' '.$val->direccion_dpto;
		    	$telefono =  $val->telefono;
		    	$celular =  $val->celular;
		    	$id_cuenta =  $cta->id;
		    	
		    	$bien_habitacional =  $cta->bien_habitacional;
		    	$bien_vehiculo =  $cta->bien_vehiculo;
		    	
		    	/* BIENES */
		    	if( $bien_habitacional == 2){
		    		$save=array();
			    	$save['tipo'] = 2;
			    	$save['estado'] = 1;
			    	$save['id_cuenta'] = $id_cuenta;
			    	$this->bienes_m->save('',$save);
		    	}
		    	
				if( $bien_vehiculo == 1){
		    		$save=array();
			    	$save['tipo'] = 1;
			    	$save['estado'] = 1;
			    	$save['id_cuenta'] = $id_cuenta;
			    	$this->bienes_m->save('',$save);
		    	}
		    	
		    	// DIRECCION
		    	if( trim($direccion) != ''){
			    	$save=array();
			    	$save['direccion'] = $direccion;
			    	$save['estado'] = 1;
			    	$save['id_cuenta'] = $id_cuenta;
			    	$this->direccion_m->save('',$save);
		    	}
		    	
		    	// TELEFONO 
		    	if( trim($telefono) != ''){
			    	$save=array();
			    	$save['numero'] = $telefono;
			    	$save['estado'] = 1;
			    	$save['tipo'] = 1;
			    	$save['id_cuenta'] = $id_cuenta;
			    	$this->telefono_m->save('',$save);
		    	}
		    	
		    	
		    	// CELULAR 
		    	if( trim($celular) != ''){
		    		$save=array();
			    	$save['numero'] = $celular;
			    	$save['estado'] = 1;
			    	$save['tipo'] = 3;
			    	$save['id_cuenta'] = $id_cuenta;
			    	$this->telefono_m->save('',$save);
		    	}
			}
		}
	}
		
	public function poblar()
	{
		 $cuentas = $this->cuentas_m->get_all();
		 foreach($cuentas as $key=>$val){
		 	$suma_abono=0;
		 	$this->db->where('activo','S');
		 	$abonos = $this->cuentas_pagos_m->get_many_by( array('id_cuenta'=>$val->id));
		 	
		 	if( count($abonos)>0 ){
		 		foreach($abonos as $k=>$v){
		 			$suma_abono = $suma_abono+$v->monto_pagado;
		 		}
		 	}
		 	
		 	$suma_abono=0;
		 	$this->db->where('activo','S');
		 	$gastos = $this->cuentas_gastos_m->get_many_by( array('id_cuenta'=>$val->id));
		 	
		 	if( count($gastos)>0 ){
		 		foreach($gastos as $k=>$v){
		 			$suma_gastos = $suma_gastos+$v->monto;
		 		}
		 	}
		 	
		 	echo 'idcuenta '.$val->id.' - '.$suma_abono.' '.$suma_gastos.'<br>';
		 	$this->cuentas_m->update_by( array('id'=>$val->id),array('monto_pagado_new'=>$suma_abono,'monto_gasto_new'=>$suma_gastos));
		 	/*echo '<pre>';
			 print_r($abonos);
			 echo '</pre>';*/
		 } 
	}
	
	public function exportar_log($id_cuenta=''){
		$array_csv = array();
	    $array_csv[]=array('Usuario',utf8_decode('AcciÃ³n'),'RUT','Etapa anterior','Etapa nueva',utf8_decode('Fecha operaciÃ³n'));    	


		$this->db->order_by('id','DESC');
	    if ($id_cuenta != ''){
			$this->db->where( array('id_cuenta'=>$id_cuenta) );
	    }
	    
	    if ($this->input->post('rango')!=''){
    		$date = explode(' - ',$this->input->post('rango'));
    		$from = date("Y-m-d", strtotime(trim($date[0])));
			$to = date("Y-m-d", strtotime(trim($date[1])));
			$this->db->where("(`fecha` BETWEEN '".$from." 00:00:00' AND '".$to." 23:59:59')");
	    }
	    
	    $logs = $this->log_etapas_m->get_log();
		foreach ($logs as $key=>$val){
			$array_csv[] = array(utf8_decode($val->administradores_nombres),utf8_decode($val->operacion),$val->rut,utf8_decode($val->s_etapas_etapa),utf8_decode($val->etapa_nueva),date("d-m-Y H:i",strtotime($val->fecha)));
		}		
    
		$this->load->helper('csv');
		header('Content-Type:text/html; charset=UTF-8');  
		array_to_csv ( $array_csv, 'reporte_log.csv' );
	
	}
	
	public function cuenta_exportar(){
		
		//echo $this->input->post('id_mandante').'dfsdfsdfsd';
    	//die();
		
		//$this->output->enable_profiler ( TRUE );
		$this->load->library ( 'PHPExcel' );
	    //$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'MANDANTE' );
		$sheet->SetCellValue ( 'B1', 'RUT' );
		$sheet->SetCellValue ( 'C1', 'DEUDOR' );
		$sheet->SetCellValue ( 'D1', 'DIRECCION' );
		$sheet->SetCellValue ( 'E1', 'COMUNA' );
		$sheet->SetCellValue ( 'F1', 'CIUDAD' );
		$sheet->SetCellValue ( 'H1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'I1', 'ETAPA DE JUICIO' );
		$sheet->SetCellValue ( 'J1', 'FECHA ETAPA' );
		if ($this->data['nodo']->nombre=='fullpay'){
			$sheet->SetCellValue ( 'K1', 'DÃ�AS TRANSCURRIDOS' );
			$sheet->SetCellValue ( 'L1', 'DÃ�AS ALERTA' );
			$sheet->SetCellValue ( 'M1', 'ESTADO CUENTA' );
			$sheet->SetCellValue ( 'N1', 'TRIBUNAL' );
			$sheet->SetCellValue ( 'O1', 'DISTRITO' );
		    $sheet->SetCellValue ( 'P1', 'ROL' );
		} else {
			$sheet->SetCellValue ( 'K1', 'ESTADO CUENTA' );
			$sheet->SetCellValue ( 'L1', 'TRIBUNAL' );
			$sheet->SetCellValue ( 'M1', 'DISTRITO' );
		    $sheet->SetCellValue ( 'N1', 'ROL' );
		}
		
	    $sql_where = "c.activo = 'S'";
		if( $this->input->get_post('rut') ){
    		$sql_where .= " AND usr.rut = '".$this->input->get_post('rut')."' ";
    	}
    	
    	 if( $this->input->get_post('nombre') ){
    		$sql_where .= " AND comu.nombre = '".$this->input->get_post('nombre')."' ";
    	}
    	
    	if( $this->input->get_post('id_procurador') ){
    		$sql_where .= " AND c.id_procurador = '".$this->input->get_post('id_procurador')."' ";
    	}
    	if( $this->input->get_post('id_mandante') ){
    		$sql_where .= " AND c.id_mandante = '".$this->input->get_post('id_mandante')."' ";
    	}
    	if( $this->input->get_post('etapa') ){
    		$sql_where .= " AND cet.id_etapa = '".$this->input->get_post('etapa')."' ";
    	}
    		
	 	if( $this->input->get_post('rol') ){
    		$sql_where .= " AND c.rol = '".$this->input->get_post('rol')."' ";
    	}
    	
	    if ($this->input->get_post('rango')!=''){
    		$date = explode(' - ',$this->input->get_post('rango'));
    		$from = date("Y-m-d", strtotime(trim($date[0])));
			$to = date("Y-m-d", strtotime(trim($date[1])));
			$sql_where.=" AND (`c`.`fecha_etapa` BETWEEN '".$from." 00:00:00' AND '".$to." 23:59:59')";
			
    	} else {
    		
		    if( $p_day = $this->input->get_post('fecha_etapa_day') ){
	    		$sql_where .= " AND DAY(c.fecha_etapa) = '".$p_day."'";
	    		//$bind_values[] = $p_day;
	    		$suffix['fecha_etapa_day'] =  $p_day;	
	    	}
	    	if( $p_mes = $this->input->get_post('fecha_etapa_month') ){
	    		$sql_where .= " AND MONTH(c.fecha_etapa) = '".$p_mes."'";
	    		//$bind_values[] = $p_mes;
	    		$suffix['fecha_etapa_month'] =  $p_mes;	
	    	}
	    	if( $p_ano = $this->input->get_post('fecha_etapa_year') ){
	    		$sql_where .= " AND YEAR(c.fecha_etapa) = '".$p_ano."'";
	    		//$bind_values[] = $p_ano;
	    		$suffix['fecha_etapa_year'] =  $p_ano;	
	    	}
    	}
    	$p_estado = $this->input->get_post('estado');
		if ( is_array($p_estado) && count($p_estado) > 0 ){
	    	$sql_where .= " AND c.id_estado_cuenta in (".implode(', ', $p_estado).") ";	
	    }
	    $group_by = 'c.id,cet.id';
	    if ($this->input->get_post('modo') == "ultima") {
	    	$group_by = 'c.id';
	    	$sql_header = "SELECT `c`.`id` AS id, `c`.`id_tribunal` AS id_tribunal, `c`.`id_distrito` AS id_distrito, `c`.`receptor` AS receptor, `c`.`rol` AS rol, `c`.`id_usuario` AS id_usuario, `c`.`fecha_inicio` AS fecha_inicio, `c`.`id_etapa` AS id_etapa, `c`.`fecha_asignacion` AS fecha_asignacion, `c`.`id_administrador` AS id_administrador, `usr`.`rut` AS rut, `usr`.`nombres` AS nombres, `usr`.`ap_pat` AS ap_pat, `usr`.`ap_mat` AS ap_mat, `usr`.`ciudad` AS ciudad, `man`.`razon_social` AS razon_social, `man`.`codigo_mandante` AS codigo_mandante, `adm`.`nombres` AS nombres_adm, `adm`.`apellidos` AS apellidos, `adm`.`fecha_crea` AS fecha_crea, `res`.`nombre` AS nombre_receptor, `tr`.`tribunal` AS tribunal, `dist`.`tribunal` AS distrito, `estado`.`estado` AS estado, `seta`.`etapa` AS etapa, `dir`.`direccion` AS direccion, `comu`.`nombre` AS nombre_comuna, `c`.`fecha_etapa` AS fecha_etapa, `seta`.`dias_alerta` AS dias_alerta, DATEDIFF(NOW(),cet.fecha_etapa) as duracion";
	    	
	    	$sql_body = 
					" FROM ( ".
					"    SELECT id_cuenta, MAX(fecha_etapa) AS last_fecha ".
					"    FROM `2_cuentas_etapas` ".
					"    GROUP BY id_cuenta ".
					" ) ce2 ".
					" JOIN `2_cuentas_etapas` cet ON ce2.id_cuenta = cet.id_cuenta AND ce2.last_fecha = c.fecha_etapa ".
					" LEFT JOIN `0_cuentas` c ON c.id = cet.id_cuenta ".
					" LEFT JOIN `0_usuarios` usr ON usr.id = c.id_usuario ".
					" LEFT JOIN `0_mandantes` man ON man.id = c.id_mandante ".
					" LEFT JOIN `0_administradores` adm ON adm.id = c.id_procurador ".
	    			" LEFT JOIN `0_receptores` res ON `res`.`id`=`c`.`receptor`".
					" LEFT JOIN `s_tribunales` tr ON tr.id = c.id_tribunal ".
					" LEFT JOIN `s_tribunales` dist ON dist.id = tr.padre ".
	    			" LEFT JOIN `2_cuentas_direccion` dir ON `dir`.`id_cuenta`=`c`.`id`".
					" JOIN `s_etapas` seta ON seta.id = cet.id_etapa ".
					" LEFT JOIN `s_estado_cuenta` estado ON estado.id = c.id_estado_cuenta ".
					//" LEFT JOIN `s_comunas` com ON com.id = usr.id_comuna ".
					" LEFT JOIN `2_cuentas_direccion` 2cd ON 2cd.id_cuenta = c.id".
					" LEFT JOIN `s_comunas` comu ON comu.id = 2cd.id_comuna".		
	    	
					" WHERE c.activo = 'S' ";
	    	if ($sql_where!=''){
	    		$sql_where = "AND ".$sql_where;
	    	}
			$query = $this->db->query($sql_header.$sql_body.$sql_where." GROUP BY c.id");
			$cuentas_etapas = $query->result();
					// Si se busca el ultimo se debe agrupar
					//$group = ' GROUP BY ce.id_cuenta ';
	    } else {
	    	$cuentas_etapas = $this->cuentas_m->get_cuenta_excel($sql_where,$group_by);
	    }
	    
		
         
		
		$i = 2;
		foreach ( $cuentas_etapas as $key => $val ) {
			//$fecha_etapa = '';
			 if ($val->fecha_etapa!='' && $val->fecha_etapa!='0000-00-00 00:00:00'){
				$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
			} 
			$sheet->SetCellValue ( 'A' . $i, $val->codigo_mandante );
			$sheet->SetCellValue ( 'B' . $i, $val->rut );
			$sheet->SetCellValue ( 'C' . $i, $val->ap_pat.' '.$val->nombres );
			$sheet->SetCellValue ( 'D' . $i, $val->direccion );
			$sheet->SetCellValue ( 'E' . $i, $val->nombre_comuna );
			$sheet->SetCellValue ( 'F' . $i, $val->ciudad );
			$sheet->SetCellValue ( 'H' . $i, $val->nombres_adm );
			$sheet->SetCellValue ( 'I' . $i, $val->etapa );
			$sheet->SetCellValue ( 'J' . $i, $val->fecha_etapa );
			
			if ($this->data['nodo']->nombre=='fullpay'){
				$dias_transcurridos = $val->duracion;
				$dias_alerta = '';
				if ($val->dias_alerta>0){ $dias_alerta = $val->dias_alerta;}
				
				$sheet->SetCellValue ( 'K' . $i, $dias_transcurridos );
				$sheet->SetCellValue ( 'L' . $i, $dias_alerta );
				
				$sheet->SetCellValue ( 'M' . $i, $val->estado );
				$sheet->SetCellValue ( 'N' . $i, $val->tribunal );
				$sheet->SetCellValue ( 'O' . $i, $val->distrito );
				$sheet->SetCellValue ( 'P' . $i, $val->rol );
			} else {
				$sheet->SetCellValue ( 'K' . $i, $val->estado );
				$sheet->SetCellValue ( 'L' . $i, $val->tribunal );
				$sheet->SetCellValue ( 'M' . $i, $val->distrito );
				$sheet->SetCellValue ( 'N' . $i, $val->rol );
			}
			
			$i ++;
		}
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	
	public function cuenta_exportar_fullpay(){
		
		//echo $this->input->post('id_mandante').'dfsdfsdfsd';
    	//die();
		
		//$this->output->enable_profiler ( TRUE );
		$this->load->library ( 'PHPExcel' );
	    //$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'MANDANTE' );
		$sheet->SetCellValue ( 'B1', 'RUT' );
		$sheet->SetCellValue ( 'C1', 'DEUDOR' );
		$sheet->SetCellValue ( 'D1', 'DIRECCION' );
		$sheet->SetCellValue ( 'E1', 'COMUNA' );
		$sheet->SetCellValue ( 'F1', 'CIUDAD' );
		$sheet->SetCellValue ( 'H1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'I1', 'ETAPA DE JUICIO' );
		$sheet->SetCellValue ( 'J1', 'FECHA ETAPA' );
		$sheet->SetCellValue ( 'K1', 'DÃ�AS TRANSCURRIDOS' );
		$sheet->SetCellValue ( 'L1', 'DÃ�AS ALERTA' );
		$sheet->SetCellValue ( 'M1', 'ESTADO CUENTA' );
		$sheet->SetCellValue ( 'N1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'O1', 'DISTRITO' );
		$sheet->SetCellValue ( 'P1', 'ROL' );
		
		
	    $sql_where = "c.activo = 'S'";
		if( $this->input->get_post('rut') ){
    		$sql_where .= " AND usr.rut = '".$this->input->get_post('rut')."' ";
    	}
    	
    	 if( $this->input->get_post('nombre') ){
    		$sql_where .= " AND comu.nombre = '".$this->input->get_post('nombre')."' ";
    	}
    	
    	if( $this->input->get_post('id_procurador') ){
    		$sql_where .= " AND c.id_procurador = '".$this->input->get_post('id_procurador')."' ";
    	}
    	if( $this->input->get_post('id_mandante') ){
    		$sql_where .= " AND c.id_mandante = '".$this->input->get_post('id_mandante')."' ";
    	}
    	if( $this->input->get_post('etapa') ){
    		$sql_where .= " AND cet.id_etapa = '".$this->input->get_post('etapa')."' ";
    	}
    		
	 	if( $this->input->get_post('rol') ){
    		$sql_where .= " AND c.rol = '".$this->input->get_post('rol')."' ";
    	}
    	
	    if ($this->input->get_post('rango')!=''){
    		$date = explode(' - ',$this->input->get_post('rango'));
    		$from = date("Y-m-d", strtotime(trim($date[0])));
			$to = date("Y-m-d", strtotime(trim($date[1])));
			$sql_where.=" AND (`c`.`fecha_etapa` BETWEEN '".$from." 00:00:00' AND '".$to." 23:59:59')";
			
    	} else {
    		
		    if( $p_day = $this->input->get_post('fecha_etapa_day') ){
	    		$sql_where .= " AND DAY(c.fecha_etapa) = '".$p_day."'";
	    		//$bind_values[] = $p_day;
	    		$suffix['fecha_etapa_day'] =  $p_day;	
	    	}

	    	if( $p_mes = $this->input->get_post('fecha_etapa_month') ){
	    		$sql_where .= " AND MONTH(c.fecha_etapa) = '".$p_mes."'";
	    		//$bind_values[] = $p_mes;
	    		$suffix['fecha_etapa_month'] =  $p_mes;	
	    	}
	    	if( $p_ano = $this->input->get_post('fecha_etapa_year') ){
	    		$sql_where .= " AND YEAR(c.fecha_etapa) = '".$p_ano."'";
	    		//$bind_values[] = $p_ano;
	    		$suffix['fecha_etapa_year'] =  $p_ano;	
	    	}
    	}
    	$p_estado = $this->input->get_post('estado');
		if ( is_array($p_estado) && count($p_estado) > 0 ){
	    	$sql_where .= " AND c.id_estado_cuenta in (".implode(', ', $p_estado).") ";	
	    }
	    $group_by = 'c.id,cet.id';
	    if ($this->input->get_post('modo') == "ultima") {
	    	$group_by = 'c.id';
	    	$sql_header = "SELECT `c`.`id` AS id, `c`.`id_tribunal` AS id_tribunal, `c`.`id_distrito` AS id_distrito, `c`.`receptor` AS receptor, `c`.`rol` AS rol, `c`.`id_usuario` AS id_usuario, `c`.`fecha_inicio` AS fecha_inicio, `c`.`id_etapa` AS id_etapa, `c`.`fecha_asignacion` AS fecha_asignacion, `c`.`id_administrador` AS id_administrador, `usr`.`rut` AS rut, `usr`.`nombres` AS nombres, `usr`.`ap_pat` AS ap_pat, `usr`.`ap_mat` AS ap_mat, `usr`.`ciudad` AS ciudad, `man`.`razon_social` AS razon_social, `man`.`codigo_mandante` AS codigo_mandante, `adm`.`nombres` AS nombres_adm, `adm`.`apellidos` AS apellidos, `adm`.`fecha_crea` AS fecha_crea, `res`.`nombre` AS nombre_receptor, `tr`.`tribunal` AS tribunal, `dist`.`tribunal` AS distrito, `estado`.`estado` AS estado, `seta`.`etapa` AS etapa, `dir`.`direccion` AS direccion, `comu`.`nombre` AS nombre_comuna, `c`.`fecha_etapa` AS fecha_etapa, `seta`.`dias_alerta` AS dias_alerta, DATEDIFF(NOW(),c.fecha_etapa) as duracion";

	    	
	    	$sql_body = 
					" FROM ( ".
					"    SELECT id_cuenta, MAX(fecha_etapa) AS last_fecha ".
					"    FROM `2_cuentas_etapas` ".
					"    GROUP BY id_cuenta ".
					" ) ce2 ".
					" JOIN `2_cuentas_etapas` cet ON ce2.id_cuenta = cet.id_cuenta AND ce2.last_fecha = c.fecha_etapa ".
					" LEFT JOIN `0_cuentas` c ON c.id = cet.id_cuenta ".
					" LEFT JOIN `0_usuarios` usr ON usr.id = c.id_usuario ".
					" LEFT JOIN `0_mandantes` man ON man.id = c.id_mandante ".
					" LEFT JOIN `0_administradores` adm ON adm.id = c.id_procurador ".
	    			" LEFT JOIN `0_receptores` res ON `res`.`id`=`c`.`receptor`".
					" LEFT JOIN `s_tribunales` tr ON tr.id = c.id_tribunal ".
					" LEFT JOIN `s_tribunales` dist ON dist.id = tr.padre ".
	    			" LEFT JOIN `2_cuentas_direccion` dir ON `dir`.`id_cuenta`=`c`.`id`".
					" JOIN `s_etapas` seta ON seta.id = cet.id_etapa ".
					" LEFT JOIN `s_estado_cuenta` estado ON estado.id = c.id_estado_cuenta ".
					" LEFT JOIN `2_cuentas_direccion` 2cd ON 2cd.id_cuenta = c.id".
					" LEFT JOIN `s_comunas` comu ON comu.id = 2cd.id_comuna".		
	    	
					" WHERE c.activo = 'S' ";

				
	    	if ($sql_where!=''){
	    		$sql_where = "AND ".$sql_where;
	    	}
			$query = $this->db->query($sql_header.$sql_body.$sql_where." GROUP BY c.id");
			$cuentas_etapas = $query->result();
					// Si se busca el ultimo se debe agrupar
					//$group = ' GROUP BY ce.id_cuenta ';
	    } else {
	    	$cuentas_etapas = $this->cuentas_m->get_cuenta_excel($sql_where,$group_by);
	    }
	    
		
         
		
		$i = 2;
		foreach ( $cuentas_etapas as $key => $val ) {
			
			//$fecha_etapa = '';
			if ($val->fecha_etapa!='' && $val->fecha_etapa!='0000-00-00 00:00:00'){
				$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
			}
			$sheet->SetCellValue ( 'A' . $i, $val->id );
			$sheet->SetCellValue ( 'B' . $i, $val->codigo_mandante );
			$sheet->SetCellValue ( 'C' . $i, $val->rut );
			$sheet->SetCellValue ( 'D' . $i, $val->ap_pat.' '.$val->nombres );
			$sheet->SetCellValue ( 'E' . $i, $val->direccion );
			$sheet->SetCellValue ( 'F' . $i, $val->nombre_comuna );
			$sheet->SetCellValue ( 'G' . $i, $val->ciudad );
			$sheet->SetCellValue ( 'H' . $i, $val->nombres_adm );
			$sheet->SetCellValue ( 'I' . $i, $val->etapa );
			$sheet->SetCellValue ( 'J' . $i, $fecha_etapa );
			
			$dias_transcurridos = $val->duracion;
			$dias_alerta = '';
				
			if ($val->dias_alerta>0){ $dias_alerta = $val->dias_alerta;}
				
			$sheet->SetCellValue ( 'K' . $i, $dias_transcurridos );
			$sheet->SetCellValue ( 'L' . $i, $dias_alerta );
				
			$sheet->SetCellValue ( 'M' . $i, $val->estado );
			$sheet->SetCellValue ( 'N' . $i, $val->tribunal );
			$sheet->SetCellValue ( 'O' . $i, $val->distrito );
			$sheet->SetCellValue ( 'P' . $i, $val->rol );
			
			$i ++;
		}
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
		
	public function cuenta_estado(){
		
		$config['suffix'] = '';
		$where = array();
		//$this->output->enable_profiler ( TRUE );
		$this->load->library ( 'PHPExcel' );
	    //$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'MANDANTE' );
		$sheet->SetCellValue ( 'B1', 'ESTADO CUENTA' );
		$sheet->SetCellValue ( 'C1', 'RUT' );
		$sheet->SetCellValue ( 'D1', 'DEUDOR' );
		$sheet->SetCellValue ( 'E1', 'ETAPA DE JUICIO' );
		if ($this->data['nodo']->nombre=='fullpay'){
			$sheet->SetCellValue ( 'F1', 'FECHA INGRESO ETAPA' );
			$sheet->SetCellValue ( 'G1', 'DÍAS TRANSCURRIDOS' );
			$sheet->SetCellValue ( 'H1', 'PROCURADOR' );
			$sheet->SetCellValue ( 'I1', 'FECHA INGRESO' );
			$sheet->SetCellValue ( 'J1', 'DÍAS TRANSCURRIDOS' );
		} else {
			$sheet->SetCellValue ( 'F1', 'MONTO PAGARÉ' );
			$sheet->SetCellValue ( 'G1', 'FECHA PAGARÉ' );
			$sheet->SetCellValue ( 'H1', 'FECHA ULTIMO PAGO' );
			$sheet->SetCellValue ( 'I1', 'TOTAL DEUDA' );
			$sheet->SetCellValue ( 'J1', 'MONTO PAGADO' );
		}
		
				if (isset($_REQUEST['rut']) && $_REQUEST['rut']!='' && $_REQUEST['rut']>0){
	    			$where["usr.rut"] = str_replace('.','',$_REQUEST['rut']);
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'rut='.$_REQUEST['rut'];
	    		}
	
	         	if (isset($_REQUEST['id_mandante']) && $_REQUEST['etapa']!='' && $_REQUEST['id_mandante']>0){ 
		    		$where["c.id_mandante"] = $_REQUEST['id_mandante'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    	}
	    		if (isset($_REQUEST['etapa']) && $_REQUEST['etapa']!='' && $_REQUEST['etapa']>0){ 
		    		$where["c.id_etapa"] = $_REQUEST['etapa'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'etapa='.$_REQUEST['etapa'];	
		    	}
	    		if (isset($_REQUEST['estado']) && $_REQUEST['estado']!='' && $_REQUEST['estado']>=0){ 
	    			$where["c.id_estado_cuenta"] = $_REQUEST['estado'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'estado='.$_REQUEST['estado'];	
		    	}
		    	
		    	if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){
	    			$where["c.id_procurador"] = $_REQUEST['id_procurador'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
	    		}}
		

		
		
		
		$cuentas_estados = $this->cuentas_m->get_cuenta_estado_excel($where);
         
		
		
		
		
		$i = 2;
		foreach ( $cuentas_estados as $key => $val ) {
			 		
			$fecha_asignacion_pagare = $val->fecha_asignacion_pagare;
			if ($fecha_asignacion_pagare!='' && $fecha_asignacion_pagare!='0000-00-00'){
				$fecha_asignacion_pagare = date('d-m-Y',strtotime($val->fecha_asignacion_pagare));
			}
			$fecha_pago = $val->fecha_ultimo_pago;
			if ($fecha_pago!='' && $fecha_pago!='0000-00-00'){
				$fecha_pago = date('d-m-Y',strtotime($val->fecha_ultimo_pago));
			}
			
			$sheet->SetCellValue ( 'A' . $i, $val->codigo_mandante);
			$sheet->SetCellValue ( 'B' . $i, $val->estado);
			$sheet->SetCellValue ( 'C' . $i, $val->rut);
			$sheet->SetCellValue ( 'D' . $i, $val->ap_pat.' '.$val->nombres);
			$sheet->SetCellValue ( 'E' . $i, $val->etapa);
			if ($this->data['nodo']->nombre=='fullpay'){
				$fecha_etapa = $val->fecha_etapa;
				if ($fecha_etapa!='' && $fecha_etapa!='0000-00-00'){
					$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
				}
				$fecha_asignacion = $val->fecha_asignacion;
				if ($fecha_asignacion!='' && $fecha_asignacion!='0000-00-00'){
					$fecha_asignacion = date('d-m-Y',strtotime($val->fecha_asignacion));
				}
				
				$sheet->SetCellValue ( 'F' . $i, $fecha_etapa);
				$sheet->SetCellValue ( 'G' . $i, $val->duracion_etapa);
				$sheet->SetCellValue ( 'H' . $i, $val->nombres_adm.' '.$val->apellidos);
				
				$sheet->SetCellValue ( 'I' . $i, $fecha_asignacion);
				$sheet->SetCellValue ( 'J' . $i, $val->duracion);
			} else {
			    $sheet->SetCellValue ( 'F' . $i, $val->monto_deuda);
			    $sheet->SetCellValue ( 'G' . $i, $fecha_asignacion_pagare);
				$sheet->SetCellValue ( 'H' . $i, $fecha_pago );
				$sheet->SetCellValue ( 'I' . $i, ($val->monto_deuda-$val->monto_pagado_new) );
				$sheet->SetCellValue ( 'J' . $i, $val->monto_pagado_new );
			}
			$i++;
		}
		
	   $writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=estado_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	
	public function cuenta_estado_fullpay(){
		
		
		
		$config['suffix'] = '';
		$where = array();
		

		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
	    //$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'ID' );
		$sheet->SetCellValue ( 'B1', 'MANDANTE' );
		$sheet->SetCellValue ( 'C1', 'RUT' );
		$sheet->SetCellValue ( 'D1', 'DEUDOR' );
		$sheet->SetCellValue ( 'E1', 'DIRECCIÓN' );
		$sheet->SetCellValue ( 'F1', 'COMUNA' );
		$sheet->SetCellValue ( 'G1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'H1', 'ETAPA DE JUICIO' );
		$sheet->SetCellValue ( 'I1', 'FECHA ETAPA' );
		$sheet->SetCellValue ( 'J1', 'DÍAS TRANSCURRIDOS ÚLTIMA ETAPA' );
		$sheet->SetCellValue ( 'K1', 'DÍAS ALERTA' );
		$sheet->SetCellValue ( 'L1', 'ESTADO CUENTA' );
		$sheet->SetCellValue ( 'M1', 'FECHA INGRESO' );
		$sheet->SetCellValue ( 'N1', 'DÍAS TRANCURRIDOS' );
		$sheet->SetCellValue ( 'O1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'P1', 'CORTE' );
		$sheet->SetCellValue ( 'Q1', 'ROL' );
		$sheet->SetCellValue ( 'R1', 'ROL 2' );
		$sheet->SetCellValue ( 'S1', 'CASTIGO' );
		$sheet->SetCellValue ( 'T1', 'JURISDICCION' );
		$sheet->SetCellValue ( 'U1', 'TIPO DEMANDA' );
		$sheet->SetCellValue ( 'V1', 'EXHORTO' );
		$sheet->SetCellValue ( 'W1', 'PAGARE' );
		$sheet->SetCellValue ( 'X1', 'FECHA ESTADO CUENTA' );
		$sheet->SetCellValue ( 'Y1', 'BIENES' );
	 	$sheet->SetCellValue ( 'Z1', 'DEUDA TOTAL' );
		//$sheet->SetCellValue ( 'AA1','SALDO DEUDA' );
		$sheet->setCellValueByColumnAndRow(26, 1,'TOTAL PAGARE'); //$i++;
		
		
		
				if (isset($_REQUEST['rut']) && $_REQUEST['rut']!='' && $_REQUEST['rut']>0){
	    			$where["usr.rut"] = str_replace('.','',$_REQUEST['rut']);
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'rut='.$_REQUEST['rut'];
	    		}
	
	         	if (isset($_REQUEST['id_mandante']) && $_REQUEST['etapa']!='' && $_REQUEST['id_mandante']>0){ 
		    		$where["c.id_mandante"] = $_REQUEST['id_mandante'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    	}
	    		if (isset($_REQUEST['etapa']) && $_REQUEST['etapa']!='' && $_REQUEST['etapa']>0){ 
		    		$where["c.id_etapa"] = $_REQUEST['etapa'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'etapa='.$_REQUEST['etapa'];	
		    	}
	    		if (isset($_REQUEST['estado']) && $_REQUEST['estado']!='' && $_REQUEST['estado']>=0){ 
	    			$where["c.id_estado_cuenta"] = $_REQUEST['estado'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'estado='.$_REQUEST['estado'];	
		    	}
		    	
		    	if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){
	    			$where["c.id_procurador"] = $_REQUEST['id_procurador'];
	    			if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
	    		}}
		

		
		$cuentas_estados = $this->cuentas_m->get_cuenta_estado_excel_fullpay($where);
       
		$saldo_deuda = '0';
		$i = 2;
		foreach ( $cuentas_estados as $key => $val ) {

			
			$fecha_pago = $val->fecha_ultimo_pago;
			if ($fecha_pago!='' && $fecha_pago!='0000-00-00'){
				$fecha_pago = date('d-m-Y',strtotime($val->fecha_ultimo_pago));
			}
			
			$fecha_etapa = $val->fecha_etapa;
			if ($fecha_etapa!='' && $fecha_etapa!='0000-00-00'){
				$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
			}
			
			$fecha_crea = $val->fecha_crea;
			if ($fecha_crea!='' && $fecha_crea!='0000-00-00'){
				$fecha_crea = date('d-m-Y',strtotime($val->fecha_crea));
			}
			
			$fecha_estado_cuenta = $val->fecha_estado_cuenta;
			if ($fecha_estado_cuenta!='' && $fecha_estado_cuenta!='0000-00-00'){
				$fecha_estado_cuenta = date('d-m-Y',strtotime($val->fecha_estado_cuenta));
			}
			
			
			
			$exorto = '';
			if($val->exorto == '1'){
			$exorto = 'Con Exhorto';
			}else{
			$exorto = 'Sin Exhorto';	
			}
			
			
			$castigo = '';
			if($val->id_castigo == '2'){
			$castigo = 'Castigo';
			}else{
			$castigo = 'No Castigo';	
			}
			
			$tipo_demanda = '';
			if($val->tipo_demanda == '1'){
			$tipo_demanda = 'Propia';
			}else{
			$tipo_demanda = 'Cedida';	
			}

			/*$where = array();

			$cuentas_bienes = $this->cuentas_m->get_cuenta_bienes($where);*/

			$cuentas_pagares = $this->pagare_m->get_many_by(array('activo' =>'S','idcuenta' =>$val->id));


            $where['c.id'] = $val->id;
            $cuentas_bienes=$this->bienes_m->get_cuentas_bienes($where);

            //echo $val->id.'<br>';

			/*print_r($cuentas_bienes);
			die(); */ 
			
			$pagare = "";
			foreach ($cuentas_pagares as $k => $v){
			
			if($pagare == ''){
			$pagare.= ',';
			}
			$pagare.= $v->n_pagare;
			}
			

			$monto_deuda = $val->monto_deuda; // 59.5884
            $mont_deuda = str_replace('.','',$monto_deuda);

            $deuda = '';
            $deuda = str_replace('.','',$val->monto_pagado_new);

            $deu = '';
            if($mont_deuda>0 && $mont_deuda>$deuda){
               $deu = $mont_deuda-$deuda;
            }



            $deu_g = '';
            $monto_gasto_new = str_replace('.','',$val->monto_gasto_new);

            if($deu > 0){
               $d_g = $monto_gasto_new + $deu;
             }


            $monto_pagado_new = $val->monto_pagado_new;
			
			
			$sd = ($monto_deuda - $monto_pagado_new);
			if($sd >0){
			$saldo_deuda = $saldo_deuda + $sd;
			}

            if($saldo_deuda>0) {
                $sal_deuda = str_replace('.','',$saldo_deuda);
            }else{
                $sal_deuda = '-';
            }

            $sheet->SetCellValue ( 'A' . $i, $val->id);
			$sheet->SetCellValue ( 'B' . $i, $val->codigo_mandante);
			$sheet->SetCellValue ( 'C' . $i, $val->rut);
			$sheet->SetCellValue ( 'D' . $i, $val->ap_pat.' '.$val->nombres);
			$sheet->SetCellValue ( 'E' . $i, $val->direccion);
			$sheet->SetCellValue ( 'F' . $i, $val->nombre_comuna);
			$sheet->SetCellValue ( 'G' . $i, $val->nombres_adm.' '.$val->apellidos);
			$sheet->SetCellValue ( 'H' . $i, $val->etapa);
			$sheet->SetCellValue ( 'I' . $i, $fecha_etapa);
			
			//$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
			$sheet->SetCellValue ( 'J' . $i, $val->ultimo_dia);
			
			$sheet->SetCellValue ( 'K' . $i, $val->dias_alerta);
			$sheet->SetCellValue ( 'L' . $i, $val->estado);
			$sheet->SetCellValue ( 'M' . $i, $val->fecha_crea);
			
			// $cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
			$sheet->SetCellValue ( 'N' . $i, $val->duracion);
			$sheet->SetCellValue ( 'O' . $i, $val->distrito);
			$sheet->SetCellValue ( 'P' . $i, $val->tribunal);
			$sheet->SetCellValue ( 'Q' . $i, $val->rol);
			$sheet->SetCellValue ( 'R' . $i, $val->rol2);
			$sheet->SetCellValue ( 'S' . $i, $castigo);
			$sheet->SetCellValue ( 'T' . $i, $val->jurisdiccion);
			$sheet->SetCellValue ( 'U' . $i, $tipo_demanda);
			$sheet->SetCellValue ( 'V' . $i, $exorto);
			$sheet->SetCellValue ( 'W' . $i, $pagare );
			$sheet->SetCellValue ( 'X' . $i, $fecha_estado_cuenta);
			
			

            $marca = '';

            foreach ($cuentas_bienes as $k => $v){
			if($marca != ''){
			$marca.= ',';
            }$marca .= $v->marca;
            }

            $mpn = $val->monto_pagado_new;

            if($mpn>0) {
                $mont_pag_new = number_format($mpn, 0,'.','');
            }else{
                $mont_pag_new = '-';
            }

            $sheet->SetCellValue ( 'Y' . $i, $marca);
			$sheet->SetCellValue ( 'Z' . $i,$d_g);
			//$sheet->SetCellValue ( 'AA' . $i, $saldo_deuda);
			$sheet->setCellValueByColumnAndRow(26, $i, $mont_deuda); //$i++;
			
			 /*$fecha_asignacion = $val->fecha_asignacion;
				if ($fecha_asignacion!='' && $fecha_asignacion!='0000-00-00'){
					$fecha_asignacion = date('d-m-Y',strtotime($val->fecha_asignacion));
				}*/
				
			$i++;
		}

          //print_r($cuentas_estados);
          //die();

		 
	    $writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=estado_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' );
	
	}	
	
	
	
	public function exportador_etapas_juicio(){
		
		//$this->output->enable_profiler ( TRUE );
		$this->load->library ( 'PHPExcel' );
	    //$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'RUT' );
		$sheet->SetCellValue ( 'B1', 'DV' );
		$sheet->SetCellValue ( 'C1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'D1', 'ROL' );
		$sheet->SetCellValue ( 'E1', 'AÑO ROL' );
		$sheet->SetCellValue ( 'F1', 'FECHA INGRESO CORTE' );
		$sheet->SetCellValue ( 'G1', 'FECHA ASIGNACIÓN' );
		$sheet->SetCellValue ( 'H1', 'ETAPA DE JUICIO' );
		
		
		
		$etapas_juicio = $this->cuentas_m->get_etapas_juicio();
         
		
	
		
		
		
		$i = 2;
		foreach ( $etapas_juicio as $key => $val ) {
			

			
	$this->db->order_by('fecha_crea','DESC');		
	$this->db->order_by('fecha_etapa','DESC');
	$fecha = $this->cuentas_etapas_m->get_by( array('id_cuenta'=>$val->id));
    
	$fecha_etapas  = date('d-m-Y',strtotime($fecha->fecha_etapa));	
	 
	    if($fecha_etapas != '0' && $fecha_etapas != ''){
			    $fecha_etapa = $fecha_etapas; 
			 }else{
			  $fecha_etapa = '-';	
			 }  
	
	$fecha_asignacionn  = date('d-m-Y',strtotime($val->fecha_asignacion));

			if($fecha_asignacionn != '0' && $fecha_asignacionn != ''){
			    $fecha_asignacion = $fecha_asignacionn; 
			 }else{
			  $fecha_asignacion = '-';	
			 } 
			 
			 
			
			$rut ='';
			$dv = '';
            $aÃ±o_rol=''; 
			$rol ='';
			
			$dvv = substr($val->rut,'-1','1');
			$rutt = substr($val->rut,'0','8');
			$roll = substr($val->rol,'0','4');
			$aÃ±o_roll = substr($val->rol,'-4','4');
			//echo $aÃ±o_rol.'shfsdfhdjfhsdk';
			
		     if($roll != '0' && $roll != ''){
			    $rol = $roll; 
			 }else{
			    $rol = '-';	
			 }
			
			
		    if($aÃ±o_roll != '0' && $aÃ±o_roll != ''){
			    $aÃ±o_rol = $aÃ±o_roll; 
			 }else{
			  $aÃ±o_rol = '-';	
			 }

			 if($dvv != '0' && $dvv != ''){
			    $dv = $dvv; 
			 }else{
			  $dv = '-';	
			 } 
			 
			  if($rutt != '0' && $rutt != ''){
			    $rut = $rutt; 
			 }else{
			  $rut = '-';	
			 } 
			 
			
			
			
			$sheet->SetCellValue ( 'A' . $i,$rut );
			$sheet->SetCellValue ( 'B' . $i, $dv );
			$sheet->SetCellValue ( 'C' . $i, $val->tribunal);
			$sheet->SetCellValue ( 'D' . $i, $rol );
			$sheet->SetCellValue ( 'E' . $i, $aÃ±o_rol );
			$sheet->SetCellValue ( 'F' . $i, $fecha_etapa );
			$sheet->SetCellValue ( 'G' . $i, $fecha_asignacion  );
			$sheet->SetCellValue ( 'H' . $i, $val->etapa );
			
			$i ++;
		}
		
		 $writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	
	
	
	public function exportador_cuentas() {
		
		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'RUT' );
		$sheet->SetCellValue ( 'B1', 'NOMBRE' );
		$sheet->SetCellValue ( 'C1', 'APELLIDO' );
		$sheet->SetCellValue ( 'D1', 'DIRECCIÓN' );
		$sheet->SetCellValue ( 'E1', 'COMUNA' );
		$sheet->SetCellValue ( 'F1', 'TRIBUNAL' );
		
		$where = array ();
		$like = array ();
		$config ['suffix'] = '';
		
		/*if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
	    	$b = $this->comunas_m->get_by( array('nombre'=> $_REQUEST ['nombre']));
	     } */
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		if (isset ( $_REQUEST ['rut_parcial'] ) && $_REQUEST ['rut_parcial'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut_parcial'];
		}
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['c.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['c.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['c.id_mandante'] = $_REQUEST ['id_mandante'];
		}
		
		if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] > 0) {
			$where ['c.id_estado_cuenta'] = $_REQUEST ['id_estado_cuenta'];
		}
		
		if (isset ( $_REQUEST ['id_etapa'] ) && $_REQUEST ['id_etapa'] != '' && $_REQUEST ['id_etapa'] > 0) {
			$where ["c.id_etapa"] = $_REQUEST ['id_etapa'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_etapa=' . $_REQUEST ['id_etapa'];
		}
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ["c.id_tribunal"] = $_REQUEST ['id_tribunal'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
		} elseif (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ["c.id_distrito"] = $_REQUEST ['id_distrito'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_distrito=' . $_REQUEST ['id_distrito'];
		}
		
		$this->db->where ( array ('dir.activo' => 'S' ) );
		$this->db->where ( array ('c.activo' => 'S' ) );
		$cuentas = $this->cuentas_m->get_cuenta_ex ( $where, $like );
		
		//print_r($cuentas);
		//die();
		

		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
			$sheet->SetCellValue ( 'A' . $i, $val->rut );
			$sheet->SetCellValue ( 'B' . $i, $val->nombres );
			$sheet->SetCellValue ( 'C' . $i, $val->ap_pat );
			$sheet->SetCellValue ( 'D' . $i, $val->direccion );
			$sheet->SetCellValue ( 'E' . $i, $val->nombre_comuna );
			$sheet->SetCellValue ( 'F' . $i, $val->tribunal_padre );
			
			$i ++;
		}
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	
	
	//############################ EXPORTAR EXCEL  ##########################################//
	
	public function exportador_cuentas_fullpay() {
		
		 //echo $_POST['id_tribunal_comuna'].'sdfdfsdfsdfdf';
		 //die();
		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();

		$sheet->SetCellValue ( 'A1', 'C_ROL_AÑO_TRIB_JU' );
		$sheet->SetCellValue ( 'B1', 'E_ROL_AÑO_TRIB_JU' );
		$sheet->SetCellValue ( 'C1', 'MANDANTE' );
		$sheet->SetCellValue ( 'D1', 'RUT' );
		$sheet->SetCellValue ( 'E1', 'N° OPERACION' );
		$sheet->SetCellValue ( 'F1', 'COD GESTION' );
		$sheet->SetCellValue ( 'G1', 'FECHA ETAPA' );
		$sheet->SetCellValue ( 'H1', 'GLOSA' );
		$sheet->SetCellValue ( 'I1', 'ESTADO' );
		$sheet->SetCellValue ( 'J1', 'FECHA ASIGNACION' );		
		$sheet->SetCellValue ( 'K1', 'COMUNA' );
		$sheet->SetCellValue ( 'L1', 'DEUDOR' );
		$sheet->SetCellValue ( 'M1', 'ETAPA' );
		$sheet->SetCellValue ( 'N1', 'JURISDICCION' );

		$sheet->SetCellValue ( 'O1', 'LETRA C' );
		$sheet->SetCellValue ( 'P1', 'ROL' );
		$sheet->SetCellValue ( 'Q1', 'AÑO' );
		$sheet->SetCellValue ( 'R1', 'TRIBUNAL' );
		//$sheet->SetCellValue ( 'O1', 'DÍAS ULTIMA ETAPA' );
		//$sheet->SetCellValue ( 'P1', 'TRIBUNAL EXHORTO' );
		
		$sheet->SetCellValue ( 'S1', 'JURISDICCION EXHORTO' );
		$sheet->SetCellValue ( 'T1', 'LETRA E' );
		$sheet->SetCellValue ( 'U1', 'ROL EXHORTO' );
		$sheet->SetCellValue ( 'V1', 'AÑO' );
		$sheet->SetCellValue ( 'W1', 'TRIBUNAL EXHORTO' );

		$sheet->SetCellValue ( 'X1', 'PROCURADOR' );

		$sheet->SetCellValue ( 'Y1', 'FECHA GESTION' );
		$sheet->SetCellValue ( 'Z1', 'MARCAS ESPECIALES' );
		//$sheet->SetCellValue ( 'AA1', 'OBSERVACION ETAPA' );
		$sheet->SetCellValue ( 'AA1', 'FECHA PRIMER VCTO IMPAGO' );
		$sheet->SetCellValue ( 'AB1', 'TIPO DEUDOR' );
		$sheet->SetCellValue ( 'AC1', 'DIAS MORA' );
		$sheet->SetCellValue ( 'AD1', 'TRAMO MORA' );
		$sheet->SetCellValue ( 'AE1', 'OBSERVACIONES' );
		
		
	
	
	


	
		
		/*$sheet->SetCellValue ( 'J1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'M1', 'DÍAS DE ALERTA' );
		$sheet->SetCellValue ( 'N1', 'CORTE' );
		$sheet->SetCellValue ( 'O1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'R1', 'CASTIGO' );*/
		
		
		$where = array ();
		$where_str = '';
		$like = array ();
		$config ['suffix'] = '';
		
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			
		}
		
		
		if (isset ( $_REQUEST ['rut_parcial'] ) && $_REQUEST ['rut_parcial'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut_parcial'];
		}



		
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['cta.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['cta.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['cta.id_mandante'] = $_REQUEST ['id_mandante'];
		}

        if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] > 0) {
            $where ['cta.id_estado_cuenta'] = $_REQUEST ['id_estado_cuenta'];
        }
	
		if ($order_by==''){
	    	//$order_by = "id_mandante desc,cta.fecha_asignacion desc";
			$order_by = "cta.fecha_asignacion desc";
	    }
	
		//Inicio Nuevo Orden
			//print_r($_REQUEST);
 		    if (isset($_REQUEST['fecha_asignacion_pagare']) && $_REQUEST['fecha_asignacion_pagare']!=''){
				if ($_REQUEST['fecha_asignacion_pagare'] == 'desc'){
					$order_by ='cta.fecha_asignacion desc';  
				} else {
					$order_by = 'cta.fecha_asignacion asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'fecha_asignacion_pagare='.$_REQUEST['fecha_asignacion_pagare'];
    		}
			
			if (isset($_REQUEST['diferencia']) && $_REQUEST['diferencia']!=''){
				if ($_REQUEST['diferencia'] == 'desc'){
					$order_by ='DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) desc';  
				} else {
					$order_by = 'DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'diferencia='.$_REQUEST['diferencia'];
	    	}
//print_r($_REQUEST);

			if (isset($_REQUEST['tribunal']) && $_REQUEST['tribunal']!=''){
				if ($_REQUEST['tribunal'] == 'desc'){
					$order_by ='CAST(trib.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trib.tribunal AS UNSIGNED) asc';  					
				}
				
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunal='.$_REQUEST['tribunal'];
	    	}
			
			if (isset($_REQUEST['tribunalE']) && $_REQUEST['tribunalE']!=''){
				if ($_REQUEST['tribunalE'] == 'desc'){
					$order_by ='CAST(trie.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trie.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunalE='.$_REQUEST['tribunalE'];
	    	}
			
			
	    	
//Fin Nuevo Orden



		/*$p_estado = $this->input->get_post('id_estado_cuenta');
		if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
			$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
			$suffix ['estado[]'] = $p_estado;	
	    }*/

        if (isset ( $_REQUEST ['etapa'] ) && $_REQUEST ['etapa'] != '' && $_REQUEST ['etapa'] > 0) {
			$where ["etap.id"] = $_REQUEST ['etapa'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'etapa=' . $_REQUEST ['etapa'];
		}
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ["cta.id_tribunal"] = $_REQUEST ['id_tribunal'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
		} elseif (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ["cta.id_distrito"] = $_REQUEST ['id_distrito'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_distrito=' . $_REQUEST ['id_distrito'];
		}
		
		
		 if (isset($_REQUEST['id_tribunal_comuna'])){
	    	if ($_REQUEST['id_tribunal_comuna']!=''){ 
	    		$where["trib.id"] = $_REQUEST['id_tribunal_comuna'];
	    		if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_tribunal_comuna='.$_REQUEST['id_tribunal_comuna'];
	    	}
	    } 
	  		
		//$this->db->where ( array ('dir.activo' => 'S' ) );
		$this->db->where ( array ('cta.activo' => 'S' ) );
		$this->db->order_by($order_by);
		// if ($this->input->get_post('modo') == "ultima"){
		$cuentas = $this->cuentas_m->get_cuenta_exportar_fullpay_group( $where, $like, $where_str );
		//}
		
		//else{ 
		//print_r($where);		
		//$cuentas = $this->cuentas_m->get_cuenta_exportar_fullpay ( $where, $like, $where_str );
		//}
		//echo $this->db->last_query();

		//echo '<pre>';print_r($cuentas);echo '</pre>';die();
		//die();
		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
			
			$castigo = '';
			if($val->id_castigo == '2'){
			$castigo = 'Castigo';
			}else{
			$castigo = 'No Castigo';	
			}
			
			//VALOR CAMPOS EXCEL
			$fecha_crea_etapa  = date('d-m-Y',strtotime($val->fecha_crea_x));
		    $fecha_asig = date('d-m-Y',strtotime($val->fecha_asignacion));
		    $ultimo_dia = date('d-m-Y',strtotime($val->ultimo_dia));
			$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));

			$sheet->SetCellValue ( 'A' . $i, $val->letraC. $val->rol. $val->anio. $val->trib_ju_C );
			$sheet->SetCellValue ( 'B' . $i, $val->letraE. $val->rolE. $val->anioE. $val->trib_ju_E );
			$sheet->SetCellValue ( 'C' . $i, $val->codigo_mandante );
			$sheet->SetCellValue ( 'D' . $i, $val->rut );
		    $sheet->SetCellValue ( 'E' . $i, $val->operacion );
		    $sheet->SetCellValue ( 'F' . $i, $val->coditau);
		    $sheet->SetCellValue ( 'G' . $i, $fecha_etapa);
			$sheet->SetCellValue ( 'H' . $i, $val->glosa);
			$sheet->SetCellValue ( 'I' . $i, $val->estado);
			$sheet->SetCellValue ( 'J' . $i, $fecha_asig );		
			$sheet->SetCellValue ( 'K' . $i, $val->nombre_comuna);	
			$sheet->SetCellValue ( 'L' . $i, $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat);
			$sheet->SetCellValue ( 'M' . $i, $val->etapa);
			$sheet->SetCellValue ( 'N' . $i, $val->jurisdiccion);
			//DistritoE
			$sheet->SetCellValue ( 'O' . $i, $val->letraC);
			$sheet->SetCellValue ( 'P' . $i, $val->rol);
			$sheet->SetCellValue ( 'Q' . $i, $val->anio);
			$sheet->SetCellValue ( 'R' . $i, $val->tribunal );
		
			//$sheet->SetCellValue ( 'O' . $i, $val->ultimo_dia );
			//$sheet->SetCellValue ( 'P' . $i, $val->tribunalE );
			$sheet->SetCellValue ( 'S' . $i, $val->jurisdiccionE );

			$sheet->SetCellValue ( 'T' . $i, $val->letraE);
			$sheet->SetCellValue ( 'U' . $i, $val->rolE );
			$sheet->SetCellValue ( 'V' . $i, $val->anioE );
			$sheet->SetCellValue ( 'W' . $i, $val->tribunalE );
			$sheet->SetCellValue ( 'X' . $i, $val->procurador );
			$sheet->SetCellValue ( 'Y' . $i, $fecha_crea_etapa);
			$sheet->SetCellValue ( 'Z' . $i, $val->marca );
			//$sheet->SetCellValue ( 'AA' . $i, $val->observaciones);
			$sheet->SetCellValue ( 'AA' . $i, $val->fecha_primer_vcto);
			$sheet->SetCellValue ( 'AB' . $i, $val->tipo_deudor);
			$sheet->SetCellValue ( 'AC' . $i, $val->dia_mora);
			$sheet->SetCellValue ( 'AD' . $i, $val->tramo_mora);
			$sheet->SetCellValue ( 'AE' . $i, $val->observaciones);
			
			
			
	
		
			/*
			$sheet->SetCellValue ( 'H' . $i, $val->estado );
			
			$sheet->SetCellValue ( 'J' . $i, $fecha_crea_etapa);
			
			$sheet->SetCellValue ( 'L' . $i, $val->nombres_adm.' '.$val->apellidos);
		
			$sheet->SetCellValue ( 'M' . $i, $val->duracion);
			$sheet->SetCellValue ( 'N' . $i, $val->dias_alerta );
			
			$sheet->SetCellValue ( 'P' . $i, $val->rol2);
			$sheet->SetCellValue ( 'Q' . $i, $castigo);
			*/
			$i ++;
		}
		//echo '<pre>';print_r($excel);echo '</pre>';die();
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	
	
	#####################################################################################
	########################## EXPORT EXCEL INFORME GESTIONES ###########################
	#####################################################################################

	
	public function exportador_cuentas_informe_gestiones() {
		
		 //echo $_POST['id_tribunal_comuna'].'sdfdfsdfsdfdf';
		 //die();
		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();

		$sheet->SetCellValue ( 'A1', 'ACREEDOR' );
		$sheet->SetCellValue ( 'B1', 'RUT DEUDOR' );
		$sheet->SetCellValue ( 'C1', 'OPERACIÓN' );
		$sheet->SetCellValue ( 'D1', 'CARTERA' );
		$sheet->SetCellValue ( 'E1', 'PRODUCTO' );
		$sheet->SetCellValue ( 'F1', 'FECHA TRAMITE' );
		$sheet->SetCellValue ( 'G1', 'CODIGO TRAMITE BANCO' );
		$sheet->SetCellValue ( 'H1', 'OBSERVACIONES' );
		$sheet->SetCellValue ( 'I1', 'OBSERVACIONES PROCURADOR' );
		$sheet->SetCellValue ( 'J1', 'FECHA GESTION' );	
		$sheet->SetCellValue ( 'K1', 'MANDANTE' );
		$sheet->SetCellValue ( 'L1', 'ESTADO' );
		$sheet->SetCellValue ( 'M1', 'MARCA' );		


		
		$where = array ();
		$where_str = '';
		$like = array ();
		$config ['suffix'] = '';
		
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			
		}
		
		
		if (isset ( $_REQUEST ['rut_parcial'] ) && $_REQUEST ['rut_parcial'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut_parcial'];
		}



		
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['cta.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['cta.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['cta.id_mandante'] = $_REQUEST ['id_mandante'];
		}

        if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] > 0) {
            $where ['cta.id_estado_cuenta'] = $_REQUEST ['id_estado_cuenta'];
        }
	
		if ($order_by==''){
	    	//$order_by = "id_mandante desc,cta.fecha_asignacion desc";
			$order_by = "cta.fecha_asignacion desc";
	    }
	
		//Inicio Nuevo Orden
			//print_r($_REQUEST);
 		    if (isset($_REQUEST['fecha_asignacion_pagare']) && $_REQUEST['fecha_asignacion_pagare']!=''){
				if ($_REQUEST['fecha_asignacion_pagare'] == 'desc'){
					$order_by ='cta.fecha_asignacion desc';  
				} else {
					$order_by = 'cta.fecha_asignacion asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'fecha_asignacion_pagare='.$_REQUEST['fecha_asignacion_pagare'];
    		}
			
			if (isset($_REQUEST['diferencia']) && $_REQUEST['diferencia']!=''){
				if ($_REQUEST['diferencia'] == 'desc'){
					$order_by ='DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) desc';  
				} else {
					$order_by = 'DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'diferencia='.$_REQUEST['diferencia'];
	    	}
//print_r($_REQUEST);

			if (isset($_REQUEST['tribunal']) && $_REQUEST['tribunal']!=''){
				if ($_REQUEST['tribunal'] == 'desc'){
					$order_by ='CAST(trib.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trib.tribunal AS UNSIGNED) asc';  					
				}
				
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunal='.$_REQUEST['tribunal'];
	    	}
			
			if (isset($_REQUEST['tribunalE']) && $_REQUEST['tribunalE']!=''){
				if ($_REQUEST['tribunalE'] == 'desc'){
					$order_by ='CAST(trie.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trie.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunalE='.$_REQUEST['tribunalE'];
	    	}
			
			
	    	
//Fin Nuevo Orden



		/*$p_estado = $this->input->get_post('id_estado_cuenta');
		if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
			$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
			$suffix ['estado[]'] = $p_estado;	
	    }*/

        if (isset ( $_REQUEST ['etapa'] ) && $_REQUEST ['etapa'] != '' && $_REQUEST ['etapa'] > 0) {
			$where ["etap.id"] = $_REQUEST ['etapa'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'etapa=' . $_REQUEST ['etapa'];
		}
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ["cta.id_tribunal"] = $_REQUEST ['id_tribunal'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
		} elseif (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ["cta.id_distrito"] = $_REQUEST ['id_distrito'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_distrito=' . $_REQUEST ['id_distrito'];
		}
		
		
		 if (isset($_REQUEST['id_tribunal_comuna'])){
	    	if ($_REQUEST['id_tribunal_comuna']!=''){ 
	    		$where["trib.id"] = $_REQUEST['id_tribunal_comuna'];
	    		if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_tribunal_comuna='.$_REQUEST['id_tribunal_comuna'];
	    	}
	    } 
	  		
		//$this->db->where ( array ('dir.activo' => 'S' ) );
		$this->db->where ( array ('cta.activo' => 'S' ) );
		$this->db->order_by($order_by);
		// if ($this->input->get_post('modo') == "ultima"){
		$cuentas = $this->cuentas_m->get_cuenta_exportar_informe_cierre( $where, $like, $where_str );
		//}
		
		//else{ 
		//print_r($where);		
		//$cuentas = $this->cuentas_m->get_cuenta_exportar_fullpay ( $where, $like, $where_str );
		//}
		//echo $this->db->last_query();

		//echo '<pre>';print_r($cuentas);echo '</pre>';die();
		//die();
		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
			
			$castigo = '';
			if($val->id_castigo == '2'){
			$castigo = 'Castigo';
			}else{
			$castigo = 'No Castigo';	
			}
			
			//VALOR CAMPOS EXCEL
			$fecha_crea_etapa  = date('d-m-Y',strtotime($val->fecha_crea));
		    $fecha_asig = date('d-m-Y',strtotime($val->fecha_asignacion));
		    $ultimo_dia = date('d-m-Y',strtotime($val->ultimo_dia));
			$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
			$producto = 'CAE';
			$acreedor = 'CorpBanca';
			$mandante = 'codigo_mandante';
			$extra = '- JCS';
			$rut = substr($val->rut, 0, strrpos($val->rut, '-')); 
			//$rut = substr($val->rut, 0,-2);
			//Banca Personas = CorpBanca
			//BICP = ITAU

			if($val->codigo_mandante == 'ITAU'){
		    $cartera = 'BICP';
			}elseif ($val->codigo_mandante == 'CorpBanca') {
			$cartera = 'Banca Personas';
			}

			if(empty($val->observaciones)){
				$adicional = '';
			}else{
				$adicional = '- JCS';
			}


			$a = 'Cumple lo ordenado previo a proveer demanda';
			$b = 'Demanda con reparo';
			$c = 'Demanda rechazada';
			$d = 'Se suspende juicio';
			$e = 'En proceso de Retiro de Demanda CAE';
			$f = 'Banco informa pago del deudor';


			if($val->etapa == $a){

			$etapa = $val->observaciones;

			}elseif ($val->etapa == $b) {
				
			$etapa = $val->observaciones;

			}elseif ($val->etapa == $c) {
				
			$etapa = $val->observaciones;

			}elseif ($val->etapa == $d) {
				
			$etapa = $val->observaciones;
			
			}elseif ($val->etapa == $e) {
				
			$etapa = $val->observaciones;
			
			}elseif ($val->etapa == $f) {
				
			$etapa = 'En proceso de retiro de Demanda CAE';
			

			}else{
				$etapa = $val->etapa;
			}





			$sheet->SetCellValue ( 'A' . $i,$acreedor);
			$sheet->SetCellValue ( 'B' . $i, $rut );
		    $sheet->SetCellValue ( 'C' . $i, $val->operacion );
		    $sheet->SetCellValue ( 'D' . $i, $cartera);
		    $sheet->SetCellValue ( 'E' . $i, $producto );
		    $sheet->SetCellValue ( 'F' . $i, $fecha_etapa);
		    $sheet->SetCellValue ( 'G' . $i, $val->coditau);
		    $sheet->SetCellValue ( 'H' . $i, $etapa.' '.$extra);
		    $sheet->SetCellValue ( 'I' . $i, $val->observaciones.' '.$adicional);
		    $sheet->SetCellValue ( 'J' . $i, $fecha_crea_etapa);
			$sheet->SetCellValue ( 'K' . $i, $val->codigo_mandante);
			$sheet->SetCellValue ( 'L' . $i, $val->estado);
			$sheet->SetCellValue ( 'M' . $i, $val->marca);
			
			
			
	
			$i ++;
		}
		//echo '<pre>';print_r($excel);echo '</pre>';die();
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=Informe Gestiones' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	


	#####################################################################################
	####################### FIN EXPORT EXCEL INFORME GESTIONES ##########################
	#####################################################################################
	
	
	
	
	
	#####################################################################################
	########################## EXPORT EXCEL INFORME INGRESOS ###########################
	#####################################################################################

	
	public function exportador_cuentas_informe_ingresos() {
		
		 //echo $_POST['id_tribunal_comuna'].'sdfdfsdfsdfdf';
		 //die();
		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();

		$sheet->SetCellValue ( 'A1', 'ACREEDOR' );
		$sheet->SetCellValue ( 'B1', 'RUT DEUDOR' );
		$sheet->SetCellValue ( 'C1', 'OPERACIÓN' );
		$sheet->SetCellValue ( 'D1', 'CARTERA' );
		$sheet->SetCellValue ( 'E1', 'PRODUCTO' );
		$sheet->SetCellValue ( 'F1', 'FECHA TRAMITE' );
		$sheet->SetCellValue ( 'G1', 'CODIGO TRAMITE BANCO' );
		$sheet->SetCellValue ( 'H1', 'OBSERVACIONES' );
		$sheet->SetCellValue ( 'I1', 'OBSERVACIONES PROCURADOR' );
		$sheet->SetCellValue ( 'J1', 'FECHA GESTION' );	
		$sheet->SetCellValue ( 'K1', 'MANDANTE' );
		$sheet->SetCellValue ( 'L1', 'ESTADO' );					


		
		$where = array ();
		$where_str = '';
		$like = array ();
		$config ['suffix'] = '';
		
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			
		}
		
		
		if (isset ( $_REQUEST ['rut_parcial'] ) && $_REQUEST ['rut_parcial'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut_parcial'];
		}



		
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['cta.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['cta.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['cta.id_mandante'] = $_REQUEST ['id_mandante'];
		}

        if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] > 0) {
            $where ['cta.id_estado_cuenta'] = $_REQUEST ['id_estado_cuenta'];
        }
	
		if ($order_by==''){
	    	//$order_by = "id_mandante desc,cta.fecha_asignacion desc";
			$order_by = "cta.fecha_asignacion desc";
	    }
	
		//Inicio Nuevo Orden
			//print_r($_REQUEST);
 		    if (isset($_REQUEST['fecha_asignacion_pagare']) && $_REQUEST['fecha_asignacion_pagare']!=''){
				if ($_REQUEST['fecha_asignacion_pagare'] == 'desc'){
					$order_by ='cta.fecha_asignacion desc';  
				} else {
					$order_by = 'cta.fecha_asignacion asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'fecha_asignacion_pagare='.$_REQUEST['fecha_asignacion_pagare'];
    		}
			
			if (isset($_REQUEST['diferencia']) && $_REQUEST['diferencia']!=''){
				if ($_REQUEST['diferencia'] == 'desc'){
					$order_by ='DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) desc';  
				} else {
					$order_by = 'DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'diferencia='.$_REQUEST['diferencia'];
	    	}
//print_r($_REQUEST);

			if (isset($_REQUEST['tribunal']) && $_REQUEST['tribunal']!=''){
				if ($_REQUEST['tribunal'] == 'desc'){
					$order_by ='CAST(trib.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trib.tribunal AS UNSIGNED) asc';  					
				}
				
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunal='.$_REQUEST['tribunal'];
	    	}
			
			if (isset($_REQUEST['tribunalE']) && $_REQUEST['tribunalE']!=''){
				if ($_REQUEST['tribunalE'] == 'desc'){
					$order_by ='CAST(trie.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trie.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunalE='.$_REQUEST['tribunalE'];
	    	}
			
			
	    	
//Fin Nuevo Orden



		/*$p_estado = $this->input->get_post('id_estado_cuenta');
		if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
			$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
			$suffix ['estado[]'] = $p_estado;	
	    }*/

        if (isset ( $_REQUEST ['etapa'] ) && $_REQUEST ['etapa'] != '' && $_REQUEST ['etapa'] > 0) {
			$where ["etap.id"] = $_REQUEST ['etapa'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'etapa=' . $_REQUEST ['etapa'];
		}
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ["cta.id_tribunal"] = $_REQUEST ['id_tribunal'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
		} elseif (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ["cta.id_distrito"] = $_REQUEST ['id_distrito'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_distrito=' . $_REQUEST ['id_distrito'];
		}
		
		
		 if (isset($_REQUEST['id_tribunal_comuna'])){
	    	if ($_REQUEST['id_tribunal_comuna']!=''){ 
	    		$where["trib.id"] = $_REQUEST['id_tribunal_comuna'];
	    		if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_tribunal_comuna='.$_REQUEST['id_tribunal_comuna'];
	    	}
	    } 
	  		
		//$this->db->where ( array ('dir.activo' => 'S' ) );
		$this->db->where ( array ('cta.activo' => 'S' ) );
		$this->db->order_by($order_by);
		// if ($this->input->get_post('modo') == "ultima"){
		$cuentas = $this->cuentas_m->get_cuenta_exportar_informe_ingresos( $where, $like, $where_str );
		//}
		
		//else{ 
		//print_r($where);		
		//$cuentas = $this->cuentas_m->get_cuenta_exportar_fullpay ( $where, $like, $where_str );
		//}
		//echo $this->db->last_query();

		//echo '<pre>';print_r($cuentas);echo '</pre>';die();
		//die();
		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
			
			$castigo = '';
			if($val->id_castigo == '2'){
			$castigo = 'Castigo';
			}else{
			$castigo = 'No Castigo';	
			}
			
			//VALOR CAMPOS EXCEL
			$fecha_crea_etapa  = date('d-m-Y',strtotime($val->fecha_crea));
		    $fecha_asig = date('d-m-Y',strtotime($val->fecha_asignacion));
		    $ultimo_dia = date('d-m-Y',strtotime($val->ultimo_dia));
			$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
			$producto = 'CAE';
			$acreedor = 'CorpBanca';
			$mandante = 'codigo_mandante';
			$extra = '- JCS';
			$rut = substr($val->rut, 0, strrpos($val->rut, '-')); 
			//$rut = substr($val->rut, 0,-2);
			//Banca Personas = CorpBanca
			//BICP = ITAU

			if($val->codigo_mandante == 'ITAU'){
		    $cartera = 'BICP';
			}elseif ($val->codigo_mandante == 'CorpBanca') {
			$cartera = 'Banca Personas';
			}

			if(empty($val->observaciones)){
				$adicional = '';
			}else{
				$adicional = '- JCS';
			}


			$sheet->SetCellValue ( 'A' . $i,$acreedor);
			$sheet->SetCellValue ( 'B' . $i, $rut );
		    $sheet->SetCellValue ( 'C' . $i, $val->operacion );
		    $sheet->SetCellValue ( 'D' . $i, $cartera);
		    $sheet->SetCellValue ( 'E' . $i, $producto );
		    $sheet->SetCellValue ( 'F' . $i, $fecha_etapa);
		    $sheet->SetCellValue ( 'G' . $i, $val->coditau);
		    $sheet->SetCellValue ( 'H' . $i, $val->etapa.' '.$extra);
		    $sheet->SetCellValue ( 'I' . $i, $val->observaciones.' '.$adicional);
		    $sheet->SetCellValue ( 'J' . $i, $fecha_crea_etapa);
			$sheet->SetCellValue ( 'K' . $i, $val->codigo_mandante);
			$sheet->SetCellValue ( 'L' . $i, $val->estado);
			
			
			
	
			$i ++;
		}
		//echo '<pre>';print_r($excel);echo '</pre>';die();
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=Informe Ingresos' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	


	#####################################################################################
	####################### FIN EXPORT EXCEL INFORME INGRESOS ##########################
	#####################################################################################
	
	
	
	

	
	#####################################################################################
	########################## EXPORT EXCEL INFORME ROLES ###########################
	#####################################################################################

	
	public function exportador_cuentas_informe_roles() {
		
		 //echo $_POST['id_tribunal_comuna'].'sdfdfsdfsdfdf';
		 //die();
		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();

		$sheet->SetCellValue ( 'A1', 'ACREEDOR' );
		$sheet->SetCellValue ( 'B1', 'RUT DEUDOR' );
		$sheet->SetCellValue ( 'C1', 'OPERACIÓN' );
		$sheet->SetCellValue ( 'D1', 'CARTERA' );
		$sheet->SetCellValue ( 'E1', 'PRODUCTO' );

		$sheet->SetCellValue ( 'F1', 'ROL' );
		$sheet->SetCellValue ( 'G1', 'AÑO' );
		$sheet->SetCellValue ( 'H1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'I1', 'CIUDAD' );

		$sheet->SetCellValue ( 'J1', 'FECHA GESTION' );	
		$sheet->SetCellValue ( 'K1', 'MANDANTE' );
		$sheet->SetCellValue ( 'L1', 'ESTADO' );
		$sheet->SetCellValue ( 'M1', 'ETAPA' );						


		
		$where = array ();
		$where_str = '';
		$like = array ();
		$config ['suffix'] = '';
		
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			
		}
		
		
		if (isset ( $_REQUEST ['rut_parcial'] ) && $_REQUEST ['rut_parcial'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut_parcial'];
		}



		
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['cta.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['cta.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['cta.id_mandante'] = $_REQUEST ['id_mandante'];
		}

        if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] > 0) {
            $where ['cta.id_estado_cuenta'] = $_REQUEST ['id_estado_cuenta'];
        }
	
		if ($order_by==''){
	    	//$order_by = "id_mandante desc,cta.fecha_asignacion desc";
			$order_by = "cta.fecha_asignacion desc";
	    }
	
		//Inicio Nuevo Orden
			//print_r($_REQUEST);
 		    if (isset($_REQUEST['fecha_asignacion_pagare']) && $_REQUEST['fecha_asignacion_pagare']!=''){
				if ($_REQUEST['fecha_asignacion_pagare'] == 'desc'){
					$order_by ='cta.fecha_asignacion desc';  
				} else {
					$order_by = 'cta.fecha_asignacion asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'fecha_asignacion_pagare='.$_REQUEST['fecha_asignacion_pagare'];
    		}
			
			if (isset($_REQUEST['diferencia']) && $_REQUEST['diferencia']!=''){
				if ($_REQUEST['diferencia'] == 'desc'){
					$order_by ='DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) desc';  
				} else {
					$order_by = 'DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'diferencia='.$_REQUEST['diferencia'];
	    	}
//print_r($_REQUEST);

			if (isset($_REQUEST['tribunal']) && $_REQUEST['tribunal']!=''){
				if ($_REQUEST['tribunal'] == 'desc'){
					$order_by ='CAST(trib.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trib.tribunal AS UNSIGNED) asc';  					
				}
				
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunal='.$_REQUEST['tribunal'];
	    	}
			
			if (isset($_REQUEST['tribunalE']) && $_REQUEST['tribunalE']!=''){
				if ($_REQUEST['tribunalE'] == 'desc'){
					$order_by ='CAST(trie.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trie.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunalE='.$_REQUEST['tribunalE'];
	    	}
			
			
	    	
//Fin Nuevo Orden



		/*$p_estado = $this->input->get_post('id_estado_cuenta');
		if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
			$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
			$suffix ['estado[]'] = $p_estado;	
	    }*/

        if (isset ( $_REQUEST ['etapa'] ) && $_REQUEST ['etapa'] != '' && $_REQUEST ['etapa'] > 0) {
			$where ["etap.id"] = $_REQUEST ['etapa'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'etapa=' . $_REQUEST ['etapa'];
		}
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ["cta.id_tribunal"] = $_REQUEST ['id_tribunal'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
		} elseif (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ["cta.id_distrito"] = $_REQUEST ['id_distrito'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_distrito=' . $_REQUEST ['id_distrito'];
		}
		
		
		 if (isset($_REQUEST['id_tribunal_comuna'])){
	    	if ($_REQUEST['id_tribunal_comuna']!=''){ 
	    		$where["trib.id"] = $_REQUEST['id_tribunal_comuna'];
	    		if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_tribunal_comuna='.$_REQUEST['id_tribunal_comuna'];
	    	}
	    } 
	  		
		//$this->db->where ( array ('dir.activo' => 'S' ) );
		$this->db->where ( array ('cta.activo' => 'S' ) );
		$this->db->order_by($order_by);
		// if ($this->input->get_post('modo') == "ultima"){
		$cuentas = $this->cuentas_m->get_cuenta_exportar_informe_roles( $where, $like, $where_str );
		//}
		
		//else{ 
		//print_r($where);		
		//$cuentas = $this->cuentas_m->get_cuenta_exportar_fullpay ( $where, $like, $where_str );
		//}
		//echo $this->db->last_query();

		//echo '<pre>';print_r($cuentas);echo '</pre>';die();
		//die();
		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
			
			$castigo = '';
			if($val->id_castigo == '2'){
			$castigo = 'Castigo';
			}else{
			$castigo = 'No Castigo';	
			}
			
			//VALOR CAMPOS EXCEL
			$fecha_crea_etapa  = date('d-m-Y',strtotime($val->fecha_crea));
		    $fecha_asig = date('d-m-Y',strtotime($val->fecha_asignacion));
		    $ultimo_dia = date('d-m-Y',strtotime($val->ultimo_dia));
			$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
			$producto = 'CAE';
			$acreedor = 'CorpBanca';
			$mandante = 'codigo_mandante';
			$extra = '- JCS';
			$rut = substr($val->rut, 0, strrpos($val->rut, '-')); 
			//$rut = substr($val->rut, 0,-2);
			//Banca Personas = CorpBanca
			//BICP = ITAU

			if($val->codigo_mandante == 'ITAU'){
		    $cartera = 'BICP';
			}elseif ($val->codigo_mandante == 'CorpBanca') {
			$cartera = 'Banca Personas';
			}

			if(empty($val->observaciones)){
				$adicional = '';
			}else{
				$adicional = '- JCS';
			}


			$sheet->SetCellValue ( 'A' . $i,$acreedor);
			$sheet->SetCellValue ( 'B' . $i, $rut );
		    $sheet->SetCellValue ( 'C' . $i, $val->operacion );
		    $sheet->SetCellValue ( 'D' . $i, $cartera);
		    $sheet->SetCellValue ( 'E' . $i, $producto );

		    $sheet->SetCellValue ( 'F' . $i, $val->rol);
			$sheet->SetCellValue ( 'G' . $i, $val->anio);
			$sheet->SetCellValue ( 'H' . $i, $val->tribunal );
		    $sheet->SetCellValue ( 'I' . $i, $val->jurisdiccion);

		    $sheet->SetCellValue ( 'J' . $i, $fecha_crea_etapa);
			$sheet->SetCellValue ( 'K' . $i, $val->codigo_mandante);
			$sheet->SetCellValue ( 'L' . $i, $val->estado);
			$sheet->SetCellValue ( 'M' . $i, $val->etapa);
			
			
			
	
			$i ++;
		}
		//echo '<pre>';print_r($excel);echo '</pre>';die();
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=Informe Roles' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	


	#####################################################################################
	####################### FIN EXPORT EXCEL INFORME ROLES ##########################
	#####################################################################################
	
	
	
	
	

	#####################################################################################
	########################## EXPORT EXCEL INFORME MORA ###########################
	#####################################################################################

	
	public function exportador_cuentas_informe_mora() {
		
		 //echo $_POST['id_tribunal_comuna'].'sdfdfsdfsdfdf';
		 //die();
		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();

		$sheet->SetCellValue ( 'A1', 'RUT_N_OPERACION' );
		$sheet->SetCellValue ( 'B1', 'MANDANTE' );
		$sheet->SetCellValue ( 'C1', 'RUT' );
		$sheet->SetCellValue ( 'D1', 'OPERACIÓN' );
		$sheet->SetCellValue ( 'E1', 'LICITACION' );
		$sheet->SetCellValue ( 'F1', 'DIAS MORA' );
		$sheet->SetCellValue ( 'G1', 'TRAMO MORA' );
		$sheet->SetCellValue ( 'H1', 'ESTADO' );
		$sheet->SetCellValue ( 'I1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'J1', 'OBSERVACIONES' );
		$sheet->SetCellValue ( 'K1', 'MARCAS ESPECIALES' );
						


		
		$where = array ();
		$where_str = '';
		$like = array ();
		$config ['suffix'] = '';
		
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			
		}
		
		
		if (isset ( $_REQUEST ['rut_parcial'] ) && $_REQUEST ['rut_parcial'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut_parcial'];
		}



		
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['cta.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['cta.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['cta.id_mandante'] = $_REQUEST ['id_mandante'];
		}

        if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] > 0) {
            $where ['cta.id_estado_cuenta'] = $_REQUEST ['id_estado_cuenta'];
        }
	
		if ($order_by==''){
	    	//$order_by = "id_mandante desc,cta.fecha_asignacion desc";
			$order_by = "cta.fecha_asignacion desc";
	    }
	
		//Inicio Nuevo Orden
			//print_r($_REQUEST);
 		    if (isset($_REQUEST['fecha_asignacion_pagare']) && $_REQUEST['fecha_asignacion_pagare']!=''){
				if ($_REQUEST['fecha_asignacion_pagare'] == 'desc'){
					$order_by ='cta.fecha_asignacion desc';  
				} else {
					$order_by = 'cta.fecha_asignacion asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'fecha_asignacion_pagare='.$_REQUEST['fecha_asignacion_pagare'];
    		}
			
			if (isset($_REQUEST['diferencia']) && $_REQUEST['diferencia']!=''){
				if ($_REQUEST['diferencia'] == 'desc'){
					$order_by ='DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) desc';  
				} else {
					$order_by = 'DATEDIFF ( NOW(), `cta`.`fecha_asignacion`) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'diferencia='.$_REQUEST['diferencia'];
	    	}
//print_r($_REQUEST);

			if (isset($_REQUEST['tribunal']) && $_REQUEST['tribunal']!=''){
				if ($_REQUEST['tribunal'] == 'desc'){
					$order_by ='CAST(trib.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trib.tribunal AS UNSIGNED) asc';  					
				}
				
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunal='.$_REQUEST['tribunal'];
	    	}
			
			if (isset($_REQUEST['tribunalE']) && $_REQUEST['tribunalE']!=''){
				if ($_REQUEST['tribunalE'] == 'desc'){
					$order_by ='CAST(trie.tribunal AS UNSIGNED) desc';  
				} else {
					$order_by = 'CAST(trie.tribunal AS UNSIGNED) asc';  
				}
				if ($config['suffix']!=''){ $config['suffix'].='&';}
				$config['suffix'].= 'tribunalE='.$_REQUEST['tribunalE'];
	    	}
			
			
	    	
//Fin Nuevo Orden



		/*$p_estado = $this->input->get_post('id_estado_cuenta');
		if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
			$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
			$suffix ['estado[]'] = $p_estado;	
	    }*/

        if (isset ( $_REQUEST ['etapa'] ) && $_REQUEST ['etapa'] != '' && $_REQUEST ['etapa'] > 0) {
			$where ["etap.id"] = $_REQUEST ['etapa'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'etapa=' . $_REQUEST ['etapa'];
		}
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ["cta.id_tribunal"] = $_REQUEST ['id_tribunal'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
		} elseif (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ["cta.id_distrito"] = $_REQUEST ['id_distrito'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_distrito=' . $_REQUEST ['id_distrito'];
		}
		
		
		 if (isset($_REQUEST['id_tribunal_comuna'])){
	    	if ($_REQUEST['id_tribunal_comuna']!=''){ 
	    		$where["trib.id"] = $_REQUEST['id_tribunal_comuna'];
	    		if ($config['suffix']!=''){ $config['suffix'].='&';}
	    			$config['suffix'].= 'id_tribunal_comuna='.$_REQUEST['id_tribunal_comuna'];
	    	}
	    } 
	  		
		//$this->db->where ( array ('dir.activo' => 'S' ) );
		$this->db->where ( array ('cta.activo' => 'S' ) );
		$this->db->order_by($order_by);
		// if ($this->input->get_post('modo') == "ultima"){
		$cuentas = $this->cuentas_m->get_cuenta_exportar_informe_mora( $where, $like, $where_str );
		//}
		
		//else{ 
		//print_r($where);		
		//$cuentas = $this->cuentas_m->get_cuenta_exportar_fullpay ( $where, $like, $where_str );
		//}
		//echo $this->db->last_query();

		//echo '<pre>';print_r($cuentas);echo '</pre>';die();
		//die();
		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
			
			$castigo = '';
			if($val->id_castigo == '2'){
			$castigo = 'Castigo';
			}else{
			$castigo = 'No Castigo';	
			}
			
			//VALOR CAMPOS EXCEL
			$fecha_crea_etapa  = date('d-m-Y',strtotime($val->fecha_crea));
		    $fecha_asig = date('d-m-Y',strtotime($val->fecha_asignacion));
		    $ultimo_dia = date('d-m-Y',strtotime($val->ultimo_dia));
			$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));
			$producto = 'CAE';
			$acreedor = 'CorpBanca';
			$mandante = 'codigo_mandante';
			$extra = '- JCS';
			$rut = substr($val->rut, 0, strrpos($val->rut, '-')); 
			$licitacion = substr($val->operacion, 0, 4); 
			//$rut_operacion = $rut. number($val->operacion);

/*

			if(empty($val->observaciones)){
				$adicional = '';
			}else{
				$adicional = '- JCS';
			}
*/

			//$sheet->SetCellValue ( 'A' . $i,$val->rut_operacion);
			$sheet->SetCellValue ( 'B' . $i,$val->codigo_mandante);
			$sheet->SetCellValue ( 'C' . $i,$rut );
		    $sheet->SetCellValue ( 'D' . $i,$val->operacion );
		    $sheet->SetCellValue ( 'E' . $i,$licitacion );
		   // $sheet->SetCellValue ( 'F' . $i, $val->dias_mora );
		   // $sheet->SetCellValue ( 'G' . $i, $val->tramo_mora );
			$sheet->SetCellValue ( 'H' . $i,$val->estado);
			$sheet->SetCellValue ( 'I' . $i,$val->procurador);
			//$sheet->SetCellValue ( 'J' . $i,$val->observaciones);
			$sheet->SetCellValue ( 'K' . $i,$val->marca);
			
			
			
	
			$i ++;
		}
		//echo '<pre>';print_r($excel);echo '</pre>';die();
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=Informe Mora' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	


	#####################################################################################
	####################### FIN EXPORT EXCEL INFORME MORA ##########################
	#####################################################################################
	
	
	
	
	
	
	
	
	
	
	
	public function exp_cuentas_swcobranza() {
		
		 //echo $_POST['id_tribunal_comuna'].'sdfdfsdfsdfdf';
		 //die();
		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'ID' );
		$sheet->SetCellValue ( 'B1', 'MANDANTE' );
		$sheet->SetCellValue ( 'C1', 'ESTADO DE CUENTA' );
		$sheet->SetCellValue ( 'D1', 'RUT' );
		$sheet->SetCellValue ( 'E1', 'COMUNA' );
		$sheet->SetCellValue ( 'F1', 'DEUDOR' );
		$sheet->SetCellValue ( 'G1', 'ETAPA DE JUICIO' );
		$sheet->SetCellValue ( 'H1', 'FECHA INGRESO ETAPA' );
		$sheet->SetCellValue ( 'I1', 'DÍAS ÚLTIMA ETAPA' );
		$sheet->SetCellValue ( 'J1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'K1', 'FECHA INGRESO' );
		$sheet->SetCellValue ( 'L1', 'DÍAS INGRESO' );
		$sheet->SetCellValue ( 'M1', 'DÍAS DE ALERTA' );
		$sheet->SetCellValue ( 'N1', 'CORTE' );
		$sheet->SetCellValue ( 'O1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'P1', 'ROL' );
		$sheet->SetCellValue ( 'Q1', 'ROL DE EXHORTO' );
		$sheet->SetCellValue ( 'R1', 'CASTIGO' );
		$sheet->SetCellValue ( 'S1', 'MEDIO CONTACTO' );
		$sheet->SetCellValue ( 'T1', 'MEDIO CONTACTO OTRO' );
		$sheet->SetCellValue ( 'U1', 'MEDIO INFORMA' );
		$sheet->SetCellValue ( 'V1', 'MEDIO INFORMA OTRO' );
		//$sheet->SetCellValue ( 'S1', 'FECHA ASIGNACIÃ“N' );
		
		
		$where = array ();
		$where_str = '';
		$like = array ();
		$config ['suffix'] = '';
		
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			
		}
		
		if (isset ( $_REQUEST ['rut_parcial'] ) && $_REQUEST ['rut_parcial'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut_parcial'];
		}
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['c.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['c.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['c.id_mandante'] = $_REQUEST ['id_mandante'];
		}
		
	
		/* $p_estado = $this->input->get_post('estado');
		if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
			$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
			$suffix ['estado[]'] = $p_estado;	
	    } */
	    
		
		if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] > 0) {
			$where ['c.id_estado_cuenta'] = $_REQUEST ['id_estado_cuenta'];
		}
		
		
		if (isset ( $_REQUEST ['etapa'] ) && $_REQUEST ['etapa'] != '' && $_REQUEST ['etapa'] > 0) {
			$where ["etap.id"] = $_REQUEST ['etapa'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'etapa=' . $_REQUEST ['etapa'];
		}
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ["c.id_tribunal"] = $_REQUEST ['id_tribunal'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
		} elseif (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ["c.id_distrito"] = $_REQUEST ['id_distrito'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_distrito=' . $_REQUEST ['id_distrito'];
		}
		
		
		 if (isset($_REQUEST['id_tribunal_comuna'])){
	    			if ($_REQUEST['id_tribunal_comuna']!=''){ 
	    				$where["trib.id"] = $_REQUEST['id_tribunal_comuna'];
	    				if ($config['suffix']!=''){ $config['suffix'].='&';}
	    				$config['suffix'].= 'id_tribunal_comuna='.$_REQUEST['id_tribunal_comuna'];
	    			 }
	    	} 
	   
		
		$this->db->where ( array ('dir.activo' => 'S' ) );
		$this->db->where ( array ('c.activo' => 'S' ) );
		
		
		$cuentas = $this->cuentas_m->get_cuenta_exportar_fullpay_group( $where, $like, $where_str );
		
		//print_r($cuentas);
		//die();
		
		
		
		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			//print_r($val);
			//echo $val->id.'='.$val->medio_contacto.'='.$val->medio_informado;
			
			$medio_contacto_otro = '';
			$medio_contacto = '';
			$medio_informado_otro = '';
			$medio_informado = '';
			
			if($val->medio_contacto_otro != '' || $val->medio_contacto_otro != 0){
				$medio_contacto_otro = $val->medio_contacto_otro;	
			}
			
			if($val->medio_contacto == 1){
					$medio_contacto = 'Se acerca a oficina';
			}elseif($val->medio_contacto == 2){
					$medio_contacto = 'Envía coreo Electronico';
			}elseif($val->medio_contacto == 3){
					$medio_contacto = 'Se acerca a CMR';
			}elseif($val->medio_contacto == 4){
					$medio_contacto = 'Llama a oficina';
			}
			
			
			
			if($val->medio_informado_otro != '' || $val->medio_informado_otro != 0){
				$medio_informado_otro = $val->medio_informado_otro;	
			}
			
			
			if($val->medio_informado == 1){
				$medio_informado = 'Teléfono';
			}elseif($val->medio_informado == 2){
				$medio_informado  = 'Notificación Judicial';
			}elseif($val->medio_informado == 3){
				$medio_informado = 'Embargo';
			}elseif($val->medio_informado == 4){
				$medio_informado = 'Carta con demanda';
			}elseif($val->medio_informado == 5){
				$medio_informado = 'Carta Notificado';
			}elseif($val->medio_informado == 6){
				$medio_informado = 'Iniciativa propia';
			}elseif($val->medio_informado == 7){
				$medio_informado = 'Retiro';
			}elseif($val->medio_informado == 8){
				$medio_informado = 'Correo electronico';
			}elseif($val->medio_informado == 9){
				$medio_informado = 'Carta asignación Cartera';
			}
			
			
			$castigo = '';
			if($val->id_castigo == '2'){
			$castigo = 'Castigo';
			}else{
			$castigo = 'No Castigo';	
			}
			
			//$fecha_asignacion = date('d-m-Y',strtotime($val->fecha_asignacion));
			
			$fecha_etapa  = date('d-m-Y',strtotime($val->fecha_etapa));
		    $fecha_crea = date('d-m-Y',strtotime($val->fecha_crea));
		    $ultimo_dia = date('d-m-Y',strtotime($val->ultimo_dia));          
			
		    $sheet->SetCellValue ( 'A' . $i, $val->id );
			$sheet->SetCellValue ( 'B' . $i, $val->codigo_mandante );
			$sheet->SetCellValue ( 'C' . $i, $val->estado );
			$sheet->SetCellValue ( 'D' . $i, $val->rut );
			$sheet->SetCellValue ( 'E' . $i, $val->nombre_comuna );
			$sheet->SetCellValue ( 'F' . $i, $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat);
			$sheet->SetCellValue ( 'G' . $i, $val->etapa);
			$sheet->SetCellValue ( 'H' . $i, $fecha_etapa);
			$sheet->SetCellValue ( 'I' . $i, $val->ultimo_dia );
			$sheet->SetCellValue ( 'J' . $i, $val->nombres_adm.' '.$val->apellidos);
			$sheet->SetCellValue ( 'K' . $i, $fecha_crea );
			$sheet->SetCellValue ( 'L' . $i, $val->duracion);
			$sheet->SetCellValue ( 'M' . $i, $val->dias_alerta );
			$sheet->SetCellValue ( 'N' . $i, $val->tribunal );
			$sheet->SetCellValue ( 'O' . $i, $val->distrito );
			$sheet->SetCellValue ( 'P' . $i, $val->rol);
			$sheet->SetCellValue ( 'Q' . $i, $val->rol2);
			$sheet->SetCellValue ( 'R' . $i, $castigo);
			$sheet->SetCellValue ( 'S' . $i, $medio_contacto );
			$sheet->SetCellValue ( 'T' . $i, $medio_contacto_otro );
			$sheet->SetCellValue ( 'U' . $i, $medio_informado );
			$sheet->SetCellValue ( 'V' . $i, $medio_informado_otro );
			//$sheet->SetCellValue ( 'S' . $i, $fecha_asignacion);
			
			$i ++;
		}
		
		 $writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	
	
	
	
	
	
	
	public function exportar_excel(){

		//$this->output->enable_profiler ( TRUE );
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'NOMBRE' );
		$sheet->SetCellValue ( 'B1', 'RUT' );
		$sheet->SetCellValue ( 'C1', 'ROL' );
		$sheet->SetCellValue ( 'D1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'E1', 'DISTRITO' );
		$sheet->SetCellValue ( 'F1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'G1', 'MONTO PAGARE' );
		$sheet->SetCellValue ( 'H1', 'FECHA ASIGNACIÓN' );
		$sheet->SetCellValue ( 'I1', 'FECHA PAGARE' );
		
		
		
		$where = array();
		$like = array();
	    $config['suffix'] = '';
		
	   
	    
	    
 		
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['c.id_procurador'] = $_REQUEST ['id_procurador'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    $config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['c.id_mandante'] = $_REQUEST ['id_mandante'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    $config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
		}
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['c.rol'] = $_REQUEST ['rol'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    $config['suffix'].= 'rol='.$_REQUEST['rol'];
		}
		
		if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] > 0) {
			$where ['c.id_estado_cuenta'] = $_REQUEST ['id_estado_cuenta'];
			if ($config['suffix']!=''){ $config['suffix'].='&';}
		    $config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
		}
		
	if ($config['suffix']!=''){$config['suffix']='?'.$config['suffix'];}
		
		$where ['c.activo'] = 'S';
 		$alertas = $this->cuentas_m->get_cuentas_alertas($where,$like);

		//print_r($alertas);
		//die();
		
		
		$i = 2;
		foreach ( $alertas as $key => $val ) {
		
			$fecha_asignacion = $val->fecha_asignacion;
			if ($fecha_asignacion!='' && $fecha_asignacion!='0000-00-00'){
				$fecha_asignacion = date('d-m-Y',strtotime($val->fecha_asignacion));
			}
			
			$fecha_pagare = $val->fecha_crea;
			if ($fecha_pagare!='' && $fecha_pagare!='0000-00-00'){
				$fecha_pagare = date('d-m-Y',strtotime($val->fecha_crea));
			}	

		 	//$monto_deuda = number_format($val->monto_deuda,0,',','.'); 
			
			
			$sheet->SetCellValue ( 'A' . $i, $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat);
			$sheet->SetCellValue ( 'B' . $i, $val->rut );
			$sheet->SetCellValue ( 'C' . $i, $val->rol );
			$sheet->SetCellValue ( 'D' . $i, $val->nombres_adm.' '.$val->apellidos);
			$sheet->SetCellValue ( 'E' . $i, $val->distrito );
			$sheet->SetCellValue ( 'F' . $i, $val->tribunal );
			$sheet->SetCellValue ( 'G' . $i, $val->monto_deuda );
			$sheet->SetCellValue ( 'H' . $i, $fecha_asignacion );
			$sheet->SetCellValue ( 'I' . $i, $fecha_pagare );
			
			
			
			$i ++;
		}
		
		 $writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=alertas_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	
	
	
	public function exportador_cuentas_etapas_fullpay() {
		
		
		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'ID' );
		$sheet->SetCellValue ( 'B1', 'MANDANTE' );
		$sheet->SetCellValue ( 'C1', 'RUT' );
		$sheet->SetCellValue ( 'D1', 'DEUDOR' );
		$sheet->SetCellValue ( 'E1', 'COMUNA' );
		$sheet->SetCellValue ( 'F1', 'CIUDAD' );
		$sheet->SetCellValue ( 'G1', 'PROCURADOR' );
		$sheet->SetCellValue ( 'H1', 'ETAPA DE JUICIO' );
		$sheet->SetCellValue ( 'I1', 'FECHA ETAPA' );
		$sheet->SetCellValue ( 'J1', 'DÍAS ÚLTIMA ETAPA' );
		$sheet->SetCellValue ( 'K1', 'ESTADO CUENTA' );
		$sheet->SetCellValue ( 'L1', 'FECHA INGRESO ETAPA' );
		$sheet->SetCellValue ( 'M1', 'DÍAS INGRESO' );
		$sheet->SetCellValue ( 'N1', 'CORTE' );
		$sheet->SetCellValue ( 'O1', 'TRIBUNAL' );
		$sheet->SetCellValue ( 'P1', 'ROL' );
		$sheet->SetCellValue ( 'Q1', 'ROL 2' );
		$sheet->SetCellValue ( 'R1', 'CASTIGO' );
		$sheet->SetCellValue ( 'S1', 'JUZGADO' );
		
		$where = array ();
		$where_str = '';
		$like = array ();
		$config ['suffix'] = '';
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			
		}
		
		if (isset ( $_REQUEST ['rut'] ) && $_REQUEST ['rut'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut'];
		}
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['c.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['c.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['c.id_mandante'] = $_REQUEST ['id_mandante'];
		}
		
	
		$p_estado = $this->input->get_post('estado');
		if (is_array ( $p_estado ) && count ( $p_estado ) > 0) {
			$where_str .= "c.id_estado_cuenta in (" . implode ( ', ', $p_estado ) . ") ";
			$suffix ['estado[]'] = $p_estado;	
	    }
	    
		if (isset ( $_REQUEST ['etapa'] ) && $_REQUEST ['etapa'] != '' && $_REQUEST ['etapa'] > 0) {
			$where ["ce2.id_etapa"] = $_REQUEST ['etapa'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'etapa=' . $_REQUEST ['etapa'];
		}
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ["c.id_tribunal"] = $_REQUEST ['id_tribunal'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
		} elseif (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ["c.id_distrito"] = $_REQUEST ['id_distrito'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_distrito=' . $_REQUEST ['id_distrito'];
		}
		
		
		/* if( $p_day = $this->input->get_post('fecha_etapa_day') ){
	    			$where .= " AND DAY(ce.fecha_etapa) = '".$p_day."' ";
	    			$suffix['fecha_etapa_day'] =  $p_day;	
	    		}
	    		
				if( $p_day = $this->input->get_post('fecha_etapa_month') ){
	    			$where .= " AND DAY(ce.fecha_etapa) = '".$p_day."' ";
	    			$suffix['fecha_etapa_month'] =  $p_day;	
	    		}
		
				if( $p_day = $this->input->get_post('fecha_etapa_year') ){
	    			$where .= " AND DAY(ce.fecha_etapa) = '".$p_day."' ";
	    			$suffix['fecha_etapa_year'] =  $p_day;	
	    		} */
		
		$this->db->where ( array ('dir.activo' => 'S' ) );
		$this->db->where ( array ('c.activo' => 'S' ) );
				
		
		/*if ($this->input->get_post('modo') == "ultima"){
		$cuentas = $this->cuentas_m->get_cuenta_etapas_exportar_fullpay_group( $where, $like, $where_str );
		} */ //else{ 
		
	if ($this->input->get_post('modo') == "todas"){			
	 $cuentas = $this->cuentas_m->get_cuenta_etapas_exportar_fullpay($where, $like, $where_str );
	}else{
	 $cuentas = $this->cuentas_m->get_cuenta_etapas_exportar_fullpay_group($where, $like, $where_str );
	}

	//	}
		//print_r($cuentas);
		//die();
		
		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
			
			$castigo = '';
			if($val->id_castigo == '2'){
			$castigo = 'Castigo';
			}else{
			$castigo = 'No Castigo';	
			}
			
			
			$fecha_crea_cuentas_etapas = date('d-m-Y',strtotime($val->fecha_crea_cuentas_etapas));
		    $fecha_crea = date('d-m-Y',strtotime($val->fecha_crea));
		    $ultimo_dia = date('d-m-Y',strtotime($val->ultimo_dia)); 
			$fecha_etapa = date('d-m-Y',strtotime($val->fecha_etapa));		
		    
			
		 	
			
			
		    $sheet->SetCellValue ( 'A' . $i, $val->id );
			$sheet->SetCellValue ( 'B' . $i, $val->codigo_mandante );
			$sheet->SetCellValue ( 'C' . $i, $val->rut );
			$sheet->SetCellValue ( 'D' . $i, $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat);
			$sheet->SetCellValue ( 'E' . $i, $val->nombre_comuna );
			$sheet->SetCellValue ( 'F' . $i, '' );
			$sheet->SetCellValue ( 'G' . $i, $val->nombres_adm.' '.$val->apellidos);
			$sheet->SetCellValue ( 'H' . $i, $val->etapa);
			$sheet->SetCellValue ( 'I' . $i, $fecha_etapa);
			$sheet->SetCellValue ( 'J' . $i, $val->ultimo_dia );
			$sheet->SetCellValue ( 'K' . $i, $val->estado );
			$sheet->SetCellValue ( 'L' . $i, $fecha_crea_cuentas_etapas );
			$sheet->SetCellValue ( 'M' . $i, $val->duracion);
			$sheet->SetCellValue ( 'N' . $i, $val->distrito );
			$sheet->SetCellValue ( 'O' . $i, $val->tribunal );
			$sheet->SetCellValue ( 'P' . $i, $val->rol);
			$sheet->SetCellValue ( 'Q' . $i, $val->rol2);
			$sheet->SetCellValue ( 'R' . $i, $castigo);
			$sheet->SetCellValue ( 'S' . $i, $val->juzgado);
			
			$i ++;
		}
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	} 
	
	
		public function importar_fallabella_fullpay(){
        
            $this->output->enable_profiler(TRUE);
		
	    
		$this->load->helper ( 'excel_reader2' );
		$array_return = array ();
		$array_return ['usuarios_insert'] = 0;
		$array_return ['usuarios_update'] = 0;
		
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ('comunas_m' );
		$this->load->model ('cuentas_contratos_m' );
        $this->load->model ('cuentas_contratos_m' );
		
		
		$this->data ['operacion'] = FALSE;
		
		$importacion=uniqid();
		
		//echo 'INICIO IMPORTACIÃƒâ€œN<br>';
		//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		$rows_insert = array ();
		$uploadpath = "./uploads/falabella.xls";
		$excel = new Spreadsheet_Excel_Reader ( $uploadpath );
		$rowcount = $excel->rowcount ( $sheet_index = 0 ); 
		$colcount = $excel->colcount ( $sheet_index = 0 );
		
		//echo '<pre>';
		//print_r($excel);
		//echo '</pre>';
		//

		for($i = 2; $i <= $rowcount; $i++) {//$rowcount
			
			//if ($i%10==0){echo date('d-m-Y H:i:s').' - Leyendo fila '.$i.'...<br>';}
			
			
			$numero_contrato = ''; //A
			$rut = ''; //B
			$apellido_paterno = ''; //C
		    $apellido_materno = ''; //D
			$idusuario ='';
			$idcuenta ='';
			$nombres = '';
			$telefono ='';
			$telefono2 ='';
			$celular ='';
			$direccion ='';
			$direccion_comercial ='';
			$monto_deuda ='';
			$saldo_deuda = '';
            $fecha_asignacion = '';
            $mail = '';


			$numero_contrato = trim ( $excel->val ( $i, 'A', 0 ) );
			$numero_contrato =  utf8_encode ($numero_contrato);
			
			$rut = trim ( $excel->val ( $i, 'B', 0 ) );
			$apellido_paterno = trim ( $excel->val ( $i, 'C', 0 ) );
			$apellido_paterno =  utf8_encode ($apellido_paterno);
			
			$apellido_materno = trim ( $excel->val ( $i, 'D', 0 ) );
			$apellido_materno =  utf8_encode ($apellido_materno);
			
			$nombres = trim ( $excel->val ($i,'E',0));
			$nombres =  utf8_encode ($nombres);
			
			$direccion = trim ( $excel->val ( $i, 'F', 0 ) );
			$direccion =  utf8_encode ($direccion);
			
			$comuna = trim ( $excel->val ( $i, 'G', 0 ) );
			$comuna = utf8_encode ($comuna);
			$telefono = trim ( $excel->val ( $i, 'H', 0 ) );
			$direccion_comercial = trim ( $excel->val ( $i, 'I', 0 ) );
			$direccion_comercial = utf8_encode ($direccion_comercial);

            $comuna_comercial = trim ( $excel->val ( $i, 'J', 0 ) );
            $comuna_comercial = utf8_encode ($comuna_comercial);
			
			$telefono2 = trim ( $excel->val ( $i, 'K', 0 ) );
			$celular = trim ( $excel->val ( $i, 'L', 0 ) );
			$saldo_deuda = trim ( str_replace ('.','', $excel->val( $i, 'M', 0 )));
			$monto_deuda = trim ( str_replace ('.','', $excel->val ( $i, 'N', 0 )));


            $fecha_suscripcion = trim ( $excel->val ( $i, 'O', 0 ) );
            if ($fecha_suscripcion != '') {
                $fecha_suscripcion = date ( "Y-m-d", strtotime ( $fecha_suscripcion ) );
            }


            $fecha_vencimiento = trim ( $excel->val ( $i, 'P', 0 ) );
            if ($fecha_vencimiento != '') {
                $fecha_vencimiento = date ( "Y-m-d", strtotime ( $fecha_vencimiento ) );
            }

            $fecha_asignacion = trim ( $excel->val ( $i, 'Q', 0 ) );
            $fecha_asignacion = date ( "Y-m-d", strtotime ( $fecha_asignacion ) );

            $mail = trim ( $excel->val ( $i, 'R', 0 ) );
            $mail = utf8_encode ($mail);

			//echo 'Rut '.$rut.' Apellido='.$apellido_paterno.' direccion'.$direccion.' nombres'.$nombres.'<br>';
			
			$idusuario = $this->usuarios_m->search_id_record_exist(array('rut'=>$rut),'id');			   
	        
			$idmandante = $this->mandantes_m->search_id_record_exist(array('codigo_mandante' => 'Falabella'),'id');

			
			if ($idmandante != '') {
			
			$arr_usuarios = array ();
			
					if ($rut != '' ) {
					$arr_usuarios ['rut'] = $rut;
				
			    	if ($apellido_paterno != '' ) {
					$arr_usuarios ['ap_pat'] = $apellido_paterno;
					}
			
					if ($apellido_materno != '') {
					$arr_usuarios ['ap_mat'] = $apellido_materno;
					}

					if ($nombres != '') {
					$arr_usuarios ['nombres'] = $nombres;
					}
			
				}
			
		
		   if ($idusuario == ''|| $idusuario == NULL ) {
				//insert
				$arr_usuarios = array_merge ( $arr_usuarios, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				
			    $idusuario = $this->usuarios_m->insert ( $arr_usuarios, TRUE, TRUE ); //retorna idpartida ingresada
				$array_return ['usuarios_insert'] ++; //contabiliza cuantos ingresos
			} else {
				//update
				$arr_usuarios = array_merge ( $arr_usuarios, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->usuarios_m->update ( $idusuario, $arr_usuarios, TRUE, TRUE );
				$array_return ['usuarios_update'] ++; //contabiliza cuantas actualizaciones
			}
       
			//$idcuenta = $this->cuentas_m->search_id_record_exist(array('id_usuario'=>$idusuario),'id');
		
			$iddireccion = $this->direccion_m->search_id_record_exist(array('id_cuenta'=>$idcuenta),'id');
		
			$idcomuna = $this->comunas_m->search_id_record_exist(array('nombre'=>$comuna),'id');
			
			
		if($fecha_asignacion != ''){
			$datos_cuentas ['fecha_asignacion'] = $fecha_asignacion;
			}else{
			$datos_cuentas ['fecha_asignacion'] = '-';
		 }
			
			$idcuenta = $this->revisar_cuentas ($idmandante,$idusuario,$datos_cuentas);
			
							$datos_direccion = array();
	                        $idcomuna = ''; 
	                        $comuna = $this->comunas_m->get_by(array('nombre' => $comuna)); 
					        if (count($comuna) == '1' ){
		                     $idcomuna=$comuna->id;		
	                        }



                            $comuna_comercial = $this->comunas_m->get_by(array('nombre' => $comuna_comercial));
                            if (count($comuna_comercial) == '1' ){
                            $idcomuna_comercial=$comuna_comercial->id;
                            }



                             $datos_direccion['tipo'] = '1';
                             $datos_direccion['id_cuenta'] = $idcuenta;
                             $datos_direccion['id_comuna'] = $idcomuna;
		           			 $datos_direccion['direccion'] = $direccion;
							 $this->revisar_direccion($idcuenta,$datos_direccion);
							 $datos_direccion['tipo'] = '2';
	                         $datos_direccion['id_cuenta'] = $idcuenta;
                             $datos_direccion['id_comuna'] = $idcomuna_comercial;
	                         $datos_direccion['direccion'] = $direccion_comercial;
							 $this->revisar_direccion($idcuenta,$datos_direccion);
						 
			
							$datos_telefono = array();
						  
					        $datos_telefono['id_cuenta'] = $idcuenta; 
						    $datos_telefono['numero'] = $telefono;
						    $datos_telefono['tipo'] = 1;
						    $this->revisar_telefonos($idcuenta,$datos_telefono);
							$datos_telefono['id_cuenta'] = $idcuenta; 
						    $datos_telefono['numero'] = $telefono2;
						    $datos_telefono['tipo'] = 2;
						    $this->revisar_telefonos($idcuenta,$datos_telefono);
						    $datos_telefono['id_cuenta'] = $idcuenta;  
						    $datos_telefono['numero'] = $celular;
						    $datos_telefono['tipo'] = 3;
						    $this->revisar_telefonos($idcuenta,$datos_telefono);

                            $datos_mail['id_cuenta'] = $idcuenta;
                            $datos_mail['mail'] = $mail;
                            $this->revisar_mail($idcuenta,$datos_mail);

			
				
						if ($monto_deuda!=''){
						
						$datos_pagare = array ();
						
						$datos_pagare ['monto_deuda'] = $monto_deuda;
						
						if($fecha_vencimiento != ''){
						$datos_pagare ['fecha_vencimiento'] = date('Y-m-d',strtotime($fecha_vencimiento));
						}else{
						$datos_pagare ['fecha_vencimiento'] = '-';
						}
						
						if($fecha_suscripcion != ''){
						$datos_pagare ['fecha_suscripcion'] = date('Y-m-d',strtotime($fecha_suscripcion));
						}else{
						$datos_pagare ['fecha_suscripcion'] = '-';
						}
						
						$datos_pagare ['saldo_deuda'] = $saldo_deuda;

                     $this->revisar_pagare ( $idcuenta, $datos_pagare );
					}
					
					if($numero_contrato != ''){
					 
					$datos_contratos = array ();	
					
					$datos_contratos ['numero_contrato'] = $numero_contrato;



                        $datos_contratos ['id_cuenta'] = $idcuenta;
						$this->revisar_contrato ( $idcuenta, $datos_contratos );
						
					}
					
				 }			    
		   }//for
       
       if ($array_return ['usuarios_insert'] > 0 or $array_return ['usuarios_update'] > 0) {
			$array_return ['operacion'] = TRUE;
		
		}
			 redirect('admin/cuentas/');
	 }
	
	
	public function revisar_telefonos($idcuenta,$datos = array()){
	$idtelefono= '';
            
	$telefono = $this->telefono_m->get_by ( array ('id_cuenta' => $idcuenta, 'numero'=>$datos['numero'], 'tipo'=>$datos['tipo'] ) );

	//print_r($telefono);
	//die(); 
		if (count($telefono) == '1' ){
	     $idtelefono=$telefono->id;		
	      }	
		
	      if($datos['numero'] != ''){
	 if ($idtelefono != '') {
				$datos_telefonos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->telefono_m->update ( $idtelefono, $datos, TRUE, TRUE );
			} else {
				$datos_telefonos = array_merge ($datos, array ('id' => $idtelefono, 'fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->telefono_m->insert($datos+array('estado'=>0),TRUE,TRUE );
			}	
		return $idtelefono;      
	  }
	}


    public function revisar_mail($idcuenta,$datos = array()){


        $idmail= '';


        $mail = $this->mail_m->get_by ( array ('id_cuenta' => $idcuenta, 'mail'=>$datos['mail']) );


        if (count($mail) == '1' ){
            $idmail=$mail->id;
        }


        if($datos['mail'] != ''){
            if ($idmail != '') {
                $datos_mail = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
                $this->mail_m->update ( $idmail, $datos, TRUE, TRUE );
            } else {
                $datos_mail = array_merge ($datos, array ('id' => $idmail, 'fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
                $this->mail_m->insert($datos+array('estado'=>0),TRUE,TRUE );
            }
            return $idmail;
        }
    }


	
	
	public function revisar_contrato($idcuenta,$datos=array()){

	$idcontrato= '';
		
	$contrato = $this->cuentas_contratos_m->get_by (array('id_cuenta' => $idcuenta,'numero_contrato'=>$datos['numero_contrato']));
    
	if (count($contrato) == '1' ){
	     $idcontrato = $contrato->id;		
	      }
		
	     if ($idcontrato != '') {
				$datos_contratos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->cuentas_contratos_m->update ( $idcontrato, $datos, TRUE, TRUE );
			} else {
				$datos_contratos = array_merge ($datos, array ( 'fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->cuentas_contratos_m->insert($datos,TRUE,TRUE );
			}	
		return $idcontrato;   
	      
	 }
	 

	
	public function revisar_direccion($idcuenta,$datos=array()){
	$iddireccion= '';
		
	$direccion = $this->direccion_m->get_by (array('id_cuenta' => $idcuenta,'direccion'=>$datos['direccion']));
    
	if (count($direccion) == '1' ){
	     $iddireccion=$direccion->id;		
	      }
		
	     if ($iddireccion != '') {
				$datos_direcciones = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->direccion_m->update ( $iddireccion, $datos, TRUE, TRUE );
			} else {
				$datos_direcciones = array_merge ($datos, array ( 'fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->direccion_m->insert($datos+array('estado'=>0),TRUE,TRUE );
			}	
		return $iddireccion;   
	      
	 }
	 
	 
	public function revisar_pagare($idcuenta,$datos){
		
		$idpagare = '';
		
	 	if (isset($datos['monto_deuda']) && $datos['monto_deuda']>0){
			$datos['monto_deuda'] = trim ( str_replace ( ',', '.', $datos['monto_deuda'] ) );
		
		
			$pagare = $this->pagare_m->get_by ( array ('idcuenta' => $idcuenta, 'monto_deuda' => $datos ['monto_deuda'] ) );
			
			if (count ( $pagare ) == '1') {
				$idpagare = $pagare->idpagare;
			}
			
			
			if ($idpagare != '') {
				if ($datos['monto_deuda']>0){
					$datos = array_merge ( $datos, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
					$this->pagare_m->update ( $idpagare, $datos+array('idcuenta'=>$idcuenta,'activo'=>'S'), TRUE, TRUE );
				}
			} else {
				$datos = array_merge ( $datos, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$this->pagare_m->insert ( $datos+array('idcuenta'=>$idcuenta,'activo'=>'S'), TRUE, TRUE );
			}
	 	}
		return $idpagare;
	
	}
	
	public function revisar_cuentas($datos_cuentas){
		$idcuenta = '';
		
	

        //print_r($datos_cuentas);
       // die();
		
	//	$cuenta = $this->cuentas_m->get_by ( array ('id_usuario' => $idusuario,'id_mandante' => $idmandante));
		
		 
		if (count ( $cuenta ) == '1') {
			$idcuenta = $cuenta->id;
		}
		
		if ($idcuenta != '') {
			$datos_cuentas = array_merge ( $datos_cuentas, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
			$this->cuentas_m->update ( $idcuenta, $datos_cuentas, TRUE, TRUE );
		 } else {
			$datos_cuentas = array_merge ( $datos_cuentas, array ('id' => $idcuenta, 'fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
			$idcuenta = $this->cuentas_m->insert ( $datos_cuentas,TRUE, TRUE );
		 }
		return $idcuenta;
	 	}
	 	
	 	/*
	 	
	 	public function importar_cuentas_cambio_estado() {
		
	 	// $this->output->enable_profiler ( TRUE );
	   // echo 'entro';
		
		$this->load->helper ( 'excel_reader2' );
		$array_return = array ();
		$array_return ['cuentas_insert'] = 0;
		$array_return ['cuentas_update'] = 0;
		
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'estados_cuenta_m' );
		       
	
		$this->data ['operacion'] = FALSE;
		
		$rows_insert = array ();

		// ojo ss
		$uploadpath = "./uploads/cambio_estado_terminado.xls";

		
		$excel = new Spreadsheet_Excel_Reader ( $uploadpath );
		$rowcount =  $excel->rowcount ( $sheet_index = 0);   
		$colcount = $excel->colcount ( $sheet_index = 0 );	

		for($i = 2; $i <= $rowcount; $i++) {
			
			//echo $i.'==';	
			$nombre  = '';
			$rut = '';
			$estado = '';
			$arreglo_cambio_estado = array();
			
			$nombre = utf8_encode ( trim ( $excel->val ( $i, 'A', 0 ) ) );
			$rut = trim ( str_replace ( '.', '', $excel->val ( $i, 'B', 0 ) ) );
			$estado = trim ( $excel->val ( $i, 'C', 0 ) );

		  $usuario = $this->usuarios_m->get_by ( array ('rut' => $rut ) );
	
		  
	
		   
		  $cuentas = $this->cuentas_m->get_by(array('id_usuario'=>$usuario->id));
		  
		  $cuenta_estado = $this->estados_cuenta_m->get_by(array('estado'=>'Terminado')); 
		  
		  if($estado != ''){
		  $arreglo_cambio_estado['id_estado_cuenta'] = $cuenta_estado->id;	
		  }

		  
		  if (count($cuentas) == '1' ){
                $idcuenta=$cuentas->id;
            } 
           	
          
           	
           	

           	
           //print_r($arreglo_cambio_estado);
           	
          if ($idcuenta != '') {
				$arreglo_cambio_estado = array_merge ( $arreglo_cambio_estado, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->cuentas_m->update ( $idcuenta, $arreglo_cambio_estado, TRUE, TRUE );
			    $this->array_return['cuentas_update']++;
			 } else {
				$arreglo_cambio_estado = array_merge ( $arreglo_cambio_estado, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$idcuenta=$this->cuentas_m->insert ( $arreglo_cambio_estado, TRUE, TRUE );
			    $this->array_return['cuentas_insert']++;
			 } 
	              
		 }
	 	
	 	}
	 	
	 	
*/

	 	public function exportador_reporte_pagos_excel() {
		
		//$this->output->enable_profiler ( TRUE );
		
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'RUT' );
		$sheet->SetCellValue ( 'B1', 'NOMBRE' );
		$sheet->SetCellValue ( 'C1', 'APELLIDO' );
		$sheet->SetCellValue ( 'D1', 'DIRECCIÓN' );
		$sheet->SetCellValue ( 'E1', 'COMUNA' );
		$sheet->SetCellValue ( 'F1', 'TRIBUNAL' );
		
		$where = array ();
		$like = array ();
		$config ['suffix'] = '';
		
		/*if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
	    	$b = $this->comunas_m->get_by( array('nombre'=> $_REQUEST ['nombre']));
	     } */
		
		if (isset ( $_REQUEST ['nombre'] ) && $_REQUEST ['nombre'] != '') {
			
			$like ['com.nombre'] = $_REQUEST ['nombre'];
		}
		
		if (isset ( $_REQUEST ['rut_parcial'] ) && $_REQUEST ['rut_parcial'] != '') {
			$like ['usr.rut'] = $_REQUEST ['rut_parcial'];
		}
		
		if (isset ( $_REQUEST ['rol'] ) && $_REQUEST ['rol'] != '') {
			$like ['c.rol'] = $_REQUEST ['rol'];
		}
		
		if (isset ( $_REQUEST ['nombres'] ) && $_REQUEST ['nombres'] != '') {
			$like ['usr.nombres'] = $_REQUEST ['nombres'];
		}
		if (isset ( $_REQUEST ['ap_pat'] ) && $_REQUEST ['ap_pat'] != '') {
			$like ['usr.ap_pat'] = $_REQUEST ['ap_pat'];
		}
		if (isset ( $_REQUEST ['id_procurador'] ) && $_REQUEST ['id_procurador'] > 0) {
			$where ['c.id_procurador'] = $_REQUEST ['id_procurador'];
		}
		if (isset ( $_REQUEST ['id_mandante'] ) && $_REQUEST ['id_mandante'] > 0) {
			$where ['c.id_mandante'] = $_REQUEST ['id_mandante'];
		}
		
		if (isset ( $_REQUEST ['id_estado_cuenta'] ) && $_REQUEST ['id_estado_cuenta'] > 0) {
			$where ['c.id_estado_cuenta'] = $_REQUEST ['id_estado_cuenta'];
		}
		
		if (isset ( $_REQUEST ['id_etapa'] ) && $_REQUEST ['id_etapa'] != '' && $_REQUEST ['id_etapa'] > 0) {
			$where ["c.id_etapa"] = $_REQUEST ['id_etapa'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_etapa=' . $_REQUEST ['id_etapa'];
		}
		
		if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
			$where ["c.id_tribunal"] = $_REQUEST ['id_tribunal'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
		} elseif (isset ( $_REQUEST ['id_distrito'] ) && $_REQUEST ['id_distrito'] > 0) {
			$where ["c.id_distrito"] = $_REQUEST ['id_distrito'];
			if ($config ['suffix'] != '') {
				$config ['suffix'] .= '&';
			}
			$config ['suffix'] .= 'id_distrito=' . $_REQUEST ['id_distrito'];
		}
		
		$this->db->where ( array ('dir.activo' => 'S' ) );
		$this->db->where ( array ('c.activo' => 'S' ) );
		$cuentas = $this->cuentas_m->get_cuenta_ex ( $where, $like );
		
		//print_r($cuentas);
		//die();
		

		$i = 2;
		$r = 0;
		foreach ( $cuentas as $key => $val ) {
			
			$sheet->SetCellValue ( 'A' . $i, $val->rut );
			$sheet->SetCellValue ( 'B' . $i, $val->nombres );
			$sheet->SetCellValue ( 'C' . $i, $val->ap_pat );
			$sheet->SetCellValue ( 'D' . $i, $val->direccion );
			$sheet->SetCellValue ( 'E' . $i, $val->nombre_comuna );
			$sheet->SetCellValue ( 'F' . $i, $val->tribunal_padre );
			
			$i ++;
		}
		
		$writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=cuentas_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' ); 
	}	
	 	
	 	
	 public function exportador_gastos(){

         //$this->output->enable_profiler(TRUE);
         //echo $_POST['fecha_month'],$_POST['fecha_year'],$_POST['fecha_f_month'],$_POST['fecha_f_year'];
         //die();


	 	$where = array ();
		$like = array ();
		$config ['suffix'] = '';
	 	
		$this->load->library ( 'PHPExcel' );
		//$this->load->library('PHPExcel/IOFactory');
		$excel = new PHPExcel ();
		$excel->setActiveSheetIndex ( 0 );
		$sheet = $excel->getActiveSheet ();

        $sheet->SetCellValue ( 'A1', 'ID CUENTA' );
        $sheet->SetCellValue ( 'B1', 'MANDANTE' );
		$sheet->SetCellValue ( 'C1', 'RECEPTOR' );
        $sheet->SetCellValue ( 'D1', 'NºBOLETA' );
		$sheet->SetCellValue ( 'E1', 'FECHA RECEPCIÓN' );
		$sheet->SetCellValue ( 'F1', 'FECHA INGRESO BANCO' );
         $sheet->SetCellValue ( 'G1', 'TRIBUNAL' );
        $sheet->SetCellValue ( 'H1', 'MONTO' );
		$sheet->SetCellValue ( 'I1', 'RETENCIÓN 10%' );
		$sheet->SetCellValue ( 'J1', 'DILIGENCIA' );
		$sheet->SetCellValue ( 'K1', 'RUT DEUDOR' );
		$sheet->SetCellValue ( 'L1', 'NOMBRE DEUDOR' );
		$sheet->SetCellValue ( 'M1', 'FORMA PAGO' );
        $sheet->SetCellValue('N1', 'NÚMERO CONTRATO');
        $sheet->SetCellValue('M1', 'FORMA DE ABONO');

		    if (isset($_REQUEST['rut']) && $_REQUEST['rut']!=''){ 
		    		$where["gastos.rut_receptor"] = $_REQUEST['rut'];
		    		if ($config['suffix']!=''){ $config['suffix'].='&';}
		    		$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    	}
		    
	    	if (isset($_REQUEST['rut_deudor']) && $_REQUEST['rut_deudor']!=''){ 
		    	$where["usr.rut"] = $_REQUEST['rut_deudor'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'rut_deudor='.$_REQUEST['rut_deudor'];
		    }
		    
	    	if (isset($_REQUEST['rut_parcial']) && $_REQUEST['rut_parcial']!=''){ 
		    	$like["usr.rut"] = $_REQUEST['rut_parcial'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'rut_parcial='.$_REQUEST['rut_parcial'];
		    }
		    
		    
		    if (isset($_REQUEST['n_boleta']) && $_REQUEST['n_boleta']!=''){ 
		    	$where["gastos.n_boleta"] = $_REQUEST['n_boleta'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'n_boleta='.$_REQUEST['n_boleta'];
		    }
	    	if (isset($_REQUEST['id_mandante']) && $_REQUEST['id_mandante']>0){ 
		    	$where["c.id_mandante"] = $_REQUEST['id_mandante'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];	
		    }
		    
		    
	    	if (isset($_REQUEST['rol']) && $_REQUEST['rol']>0){ 
		    	$like["c.rol"] = $_REQUEST['rol'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'rol='.$_REQUEST['rol'];	
		    }
		    
	    	if (isset($_REQUEST['id_procurador']) && $_REQUEST['id_procurador']>0){ 
		    	$where["adm.id"] = $_REQUEST['id_procurador'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];	
		    }
			
			if (isset ( $_REQUEST ['id_receptor'] ) && $_REQUEST ['id_receptor'] > 0) {
				$where ["gastos.id_receptor"] = $_REQUEST ['id_receptor'];
				if ($config ['suffix'] != '') {
					$config ['suffix'] .= '&';
				}
				$config ['suffix'] .= 'id_receptor=' . $_REQUEST ['id_receptor'];
			}
		    
		    if (isset ( $_REQUEST ['id_tribunal'] ) && $_REQUEST ['id_tribunal'] > 0) {
				$where ["tr.id"] = $_REQUEST ['id_tribunal'];
				if ($config ['suffix'] != '') {
					$config ['suffix'] .= '&';
				}
				$config ['suffix'] .= 'id_tribunal=' . $_REQUEST ['id_tribunal'];
			}


         $mes_i = '';
         if (isset($_REQUEST['fecha_month'])){
             if ($_REQUEST['fecha_month']>0){$mes_i = '-'.$_REQUEST['fecha_month'].'-';}
             if ($config['suffix']!=''){ $config['suffix'].='&';}
             $config['suffix'].= 'fecha_month='.$_REQUEST['fecha_month'];
         }
         $year_i = '';




         if (isset($_REQUEST['fecha_year'])){
             if ($_REQUEST['fecha_year']>0){$year_i = $_REQUEST['fecha_year'];}
             if ($config['suffix']!=''){ $config['suffix'].='&';}
             $config['suffix'].= 'fecha_year='.$_REQUEST['fecha_year'];
         }
         $like["gastos.fecha"]=$year_i.$mes_i;

         $mes_f = '';
         if (isset($_REQUEST['fecha_f_month'])){
             if ($_REQUEST['fecha_f_month']>0){$mes_f = '-'.$_REQUEST['fecha_f_month'].'-';}
             if ($config['suffix']!=''){ $config['suffix'].='&';}
             $config['suffix'].= 'fecha_f_month='.$_REQUEST['fecha_f_month'];
         }
         $year_f = '';
         if (isset($_REQUEST['fecha_f_year'])){
             if ($_REQUEST['fecha_f_year']>0){$year_f = $_REQUEST['fecha_f_year'];}
             if ($config['suffix']!=''){ $config['suffix'].='&';}
             $config['suffix'].= 'fecha_f_year='.$_REQUEST['fecha_f_year'];
         }
         $between = ''; //$like=array();
         if ($mes_f =='' && $year_f==''){ $like["gastos.fecha"]=$year_i.$mes_i;}
         else { $this->db->where("`gastos`.`fecha` BETWEEN '".$year_i.$mes_i."01' AND '".$year_f.$mes_f."31'",NULL,FALSE);}


         $cuentas_gastos = $this->cuentas_m->get_cuentas_gastos($where,$like);
		    $i = 2;
		    foreach ( $cuentas_gastos as $key => $val ) {
                $acuerdo_pago = '';

                if($val->id_acuerdo_pago == 1 ) {
                    $acuerdo_pago = 'Abono';
                }
                elseif($val->id_acuerdo_pago == 2) {
                    $acuerdo_pago = 'Acuerdo';
                }else{
                    $acuerdo_pago = '-';
                }

		   	$fecha_recepcion = $val->fecha_recepcion;
			if ($fecha_recepcion!='' && $fecha_recepcion!='0000-00-00'){
				$fecha_recepcion = date('d-m-Y',strtotime($val->fecha_recepcion));
			} 	 	
		    	
		   $fecha_ingreso_banco = $val->fecha_ingreso_banco;
			if ($fecha_ingreso_banco!='' && $fecha_ingreso_banco!='0000-00-00'){
				$fecha_ingreso_banco = date('d-m-Y',strtotime($val->fecha_ingreso_banco));
			}



            $sheet->SetCellValue ( 'A' . $i, $val->id);
			$sheet->SetCellValue ( 'B' . $i, $val->razon_social);
			$sheet->SetCellValue ( 'C' . $i, $val->nombre_receptor);
			$sheet->SetCellValue ( 'D' . $i, $val->n_boleta);
			$sheet->SetCellValue ( 'E' . $i, $fecha_recepcion );
			$sheet->SetCellValue ( 'F' . $i, $fecha_ingreso_banco );
			$sheet->SetCellValue ( 'G' . $i, $val->tribunal);
            $sheet->SetCellValue ( 'H' . $i, $val->monto);
			$sheet->SetCellValue ( 'I' . $i, $val->retencion);
			$sheet->SetCellValue ( 'J' . $i, $val->nombre_diligencia);
			$sheet->SetCellValue ( 'K' . $i, $val->rut_deudor);
			$sheet->SetCellValue ( 'L' . $i, $val->nombres_deudores.' '.$val->apellido_paterno.' '.$val->apellido_materno);
			$sheet->SetCellValue ( 'M' . $i, $val->forma_pago);
            $sheet->SetCellValue ( 'N' . $i, $val->numero_contrato);
            $sheet->SetCellValue ( 'O' . $i, $acuerdo_pago);
			
			
			$i++;
		    }
		    
		 $writer = new PHPExcel_Writer_Excel5 ( $excel );
		header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
		header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
		header ( 'Content-Disposition: attachment; filename=gastos_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header ( 'Cache-Control: private', false );
		$writer->save ( 'php://output' );
		    
	 	}
	 	
	 	
	 	public function exportador_pagos(){

        // $this->output->enable_profiler(TRUE);


        $where = array ();
		$like = array ();
		$config ['suffix'] = '';

         $this->load->library ( 'PHPExcel' );
          //$this->load->library('PHPExcel/IOFactory');
          $excel = new PHPExcel ();
        $excel->setActiveSheetIndex ( 0 );
        $sheet = $excel->getActiveSheet ();
		$sheet->SetCellValue ( 'A1', 'CÓDIGO CUENTA' );
		$sheet->SetCellValue ( 'B1', 'NOMBRE PROCURADOR' );
		$sheet->SetCellValue ( 'C1', 'APELLIDO PROCURADOR' );
		$sheet->SetCellValue ( 'D1', 'MANDANTE' );
		$sheet->SetCellValue ( 'E1', 'FECHA PAGO' );
		$sheet->SetCellValue ( 'F1', 'MONTO PAGADO' );
		$sheet->SetCellValue ( 'G1', 'MONTO DEUDA' );
		$sheet->SetCellValue ( 'H1', 'NOMBRE DEUDOR' );
		$sheet->SetCellValue ( 'I1', 'APELLIDO DEUDOR' );
		$sheet->SetCellValue ( 'J1', 'APELLIDO MATERNO DEUDOR' );
		$sheet->SetCellValue ( 'K1', 'RUT DEUDOR' );
		$sheet->SetCellValue ( 'L1', 'COMPROBANTE' );
		$sheet->SetCellValue ( 'M1', 'ESTADO' );
        $sheet->SetCellValue ( 'N1', 'TRIBUNAL' );
        $sheet->SetCellValue ( 'O1', 'ROL' );
        $sheet->SetCellValue ( 'P1', 'NUMERO COMPROBANTE' );
        $sheet->SetCellValue ( 'Q1', 'DILIGENCIA' );
	    	
		    
		   /* if ($_REQUEST['rut']!=''){
		    	$where["usr.rut"] = $_REQUEST['rut'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
	    		$config['suffix'].= 'rut='.$_REQUEST['rut'];
		    }*/
		   
		    if (isset($_REQUEST['id_procurador'])){if ($_REQUEST['id_procurador']>0){ 
		    	$where["c.id_procurador"] = $_REQUEST['id_procurador'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
	    		$config['suffix'].= 'id_procurador='.$_REQUEST['id_procurador'];
		    }}
		    if (isset($_REQUEST['id_mandante'])){if ($_REQUEST['id_mandante']>0){ 
		    	$where["c.id_mandante"] = $_REQUEST['id_mandante'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
	    		$config['suffix'].= 'id_mandante='.$_REQUEST['id_mandante'];
		    }}
		    
	   	  if (isset($_REQUEST['id_estado_cuenta'])){if ($_REQUEST['id_estado_cuenta']>0){ 
		    	$where["c.id_estado_cuenta"] = $_REQUEST['id_estado_cuenta'];
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
	    		$config['suffix'].= 'id_estado_cuenta='.$_REQUEST['id_estado_cuenta'];
		    }}
		    
		     
	    	$dia_i = '';
		    if (isset($_REQUEST['fecha_day'])){ 
		    	if ($_REQUEST['fecha_day']>0){$dia_i = $_REQUEST['fecha_day'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_day='.$_REQUEST['fecha_day'];
		    }
		    $mes_i = '';
		    if (isset($_REQUEST['fecha_month'])){ 
		    	if ($_REQUEST['fecha_month']>0){$mes_i = '-'.$_REQUEST['fecha_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_month='.$_REQUEST['fecha_month'];
		    }
		    $year_i = '';
		    if (isset($_REQUEST['fecha_year'])){ 
		    	if ($_REQUEST['fecha_year']>0){$year_i = $_REQUEST['fecha_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_year='.$_REQUEST['fecha_year'];
		    }

            //$like["pagos.fecha_pago"]=$year_i.$mes_i.$dia_i;

            $dia_f = '';
		    if (isset($_REQUEST['fecha_f_day'])){ 
		    	if ($_REQUEST['fecha_f_day']>0){$dia_f = $_REQUEST['fecha_f_day'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_day='.$_REQUEST['fecha_f_day'];
		    }
	    	$mes_f = '';
		    if (isset($_REQUEST['fecha_f_month'])){ 
		    	if ($_REQUEST['fecha_f_month']>0){$mes_f = '-'.$_REQUEST['fecha_f_month'].'-';}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_month='.$_REQUEST['fecha_f_month'];
		    }
		    $year_f = '';
		    if (isset($_REQUEST['fecha_f_year'])){ 
		    	if ($_REQUEST['fecha_f_year']>0){$year_f = $_REQUEST['fecha_f_year'];}
		    	if ($config['suffix']!=''){ $config['suffix'].='&';}
		    	$config['suffix'].= 'fecha_f_year='.$_REQUEST['fecha_f_year'];
		    }

            $between = ''; $where_fecha = "";
            if ($mes_f =='' && $year_f==''){ $like["pagos.fecha_pago"]=$year_i.$mes_i;}
                else {
                if ($dia_f == '' && $dia_i == ''){
                    $where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i."01' AND '".$year_f.$mes_f."31'";
                }
                if ($dia_f != '' && $dia_i == ''){
                    $where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i."01' AND '".$year_f.$mes_f.$dia_f."'";
                }
                if ($dia_f == '' && $dia_i != ''){
                    $where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i.$dia_i."' AND '".$year_f.$mes_f."31'";
                }
                if ($dia_f != '' && $dia_i != ''){
                    $where_fecha = "`pagos`.`fecha_pago` BETWEEN '".$year_i.$mes_i.$dia_i."' AND '".$year_f.$mes_f.$dia_f."'";
                }
            }

            if ($where_fecha!=''){$this->db->where($where_fecha,NULL,FALSE);}

            $cuentas_pagos = $this->cuentas_m->get_cuentas_pagos($where,$like);

            //print_r($cuentas_pagos);
            //die();

            $i = 2;
		    foreach ($cuentas_pagos as $key => $val) {

                //print_r($val);

                $fecha_pago = $val->fecha_pago;
                if ($fecha_pago!='0000-00-00'){
                    $fecha_pago= date('d-m-Y',strtotime($val->fecha_pago));
                }

                $vv = '';
                if($val->monto_deuda>0){
                $vv =  str_replace(',','.',$val->monto_deuda);

                }


                $sheet->SetCellValue ( 'A' . $i, $val->id);
                $sheet->SetCellValue ( 'B' . $i, $val->nombres);
                $sheet->SetCellValue ( 'C' . $i, $val->apellidos);
                $sheet->SetCellValue ( 'D' . $i, $val->razon_social);
                $sheet->SetCellValue ( 'E' . $i, $fecha_pago);
                $sheet->SetCellValue ( 'F' . $i, $val->monto_pagado );
                $sheet->SetCellValue ( 'G' . $i, $vv);
                $sheet->SetCellValue ( 'H' . $i, $val->nombres_deudor);
                $sheet->SetCellValue ( 'I' . $i, $val->ap_pat);
                $sheet->SetCellValue ( 'J' . $i, $val->ap_mat);
                $sheet->SetCellValue ( 'K' . $i, $val->rut);
                $sheet->SetCellValue ( 'L' . $i, $val->n_comprobante_interno);
                $sheet->SetCellValue ( 'M' . $i, $val->estado);
                $sheet->SetCellValue ( 'N' . $i, $val->tribunal);
                $sheet->SetCellValue ( 'O' . $i, $val->rol);
                $sheet->SetCellValue ( 'P' . $i, $val->numero_contrato);
                $sheet->SetCellValue ( 'Q' . $i, $val->nombre_diligencia);

                $i++;

                }

           $writer = new PHPExcel_Writer_Excel5 ( $excel );
            header ( 'Content-type: application/vnd.ms-excel; charset=utf-8' );
            header ( 'Content-type:   application/x-msexcel; charset=utf-8' );
            header ( 'Content-Disposition: attachment; filename=pagos_exportar_' . date ( 'd/m/Y' ) . '_' . date ( 'His' ) . '.xls' );
            header ( 'Expires: 0' );
            header ( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
            header ( 'Cache-Control: private', false );
            $writer->save ( 'php://output' );

        }

    public function convenios30dias() {
        //$this->output->enable_profiler(TRUE);
        $like = array();
        $this->load->model ( 'cuentas_m' );

        $cols[] = 'm.razon_social AS razon_social';
        $cols[] = 'u.nombres AS nombres';
        $cols[] = 'u.ap_pat AS ap_pat';
        $cols[] = 'u.ap_mat AS ap_mat';
        $cols[] = 'u.rut AS rut';
        $cols[] = 'c.id AS id_cuenta';
        $cols[] = 'c.dia_vencimiento_cuota AS cuentas_dia_vencimiento_cuota';
        $cols[] = 'c.id AS cuentas_id';
        $cols[] = "DATEDIFF( NOW() , cp.fecha_pago ) AS dias_diferencia";
        $cols[] = "cp.fecha_pago AS fecha_pago";
        $having = '';
        /**/
        $this->db->select($cols);
        $this->db->join('0_usuarios u', 'u.id = c.id_usuario');
        $this->db->join('0_mandantes m', 'm.id = c.id_mandante');
        $this->db->join("2_cuentas_pagos cp", "cp.id_cuenta = c.id AND cp.activo='S' AND cp.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=c.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left");
        if ($having!=''){
            $this->db->having($having);
        }
        $this->db->where('m.codigo_mandante', 'PROMOTORA CMR');
        $this->db->where('DATEDIFF( NOW() , cp.fecha_pago )>=30');
        if (count($like)>0){
            $this->db->like($like);
        }
        //$this->db->where("(`c`.`id_estado_cuenta`<>4)");
        $this->db->where("(`c`.`id_estado_cuenta`='5' OR `c`.`id_estado_cuenta`='9')");
        $this->db->group_by('c.id');
        $query = $this->db->get('0_cuentas c');
        $lists = $query->result() ;


        if (count($lists)>0){
            $output='Listado de deudores con convenio que no han pagado hace más de 30 días';
            $output.= '<table><tr><th>Mandante</th><th>RUT Deudor</th><th>Nombre Deudor</th><th>Teléfonos</th><th>Fecha de Pago</th></tr>';
            foreach ($lists as $key=>$val){

                $output.='<tr>';
                $output.='<td>'.$val->razon_social.'</td>';
                $output.='<td>'.$val->rut.'</td>';
                $output.='<td>'.$val->nombres.' '.$val->ap_pat.' '.$val->ap_mat.'</td>';
                $output.='<td>'.$t.'</td>';

                $output.='<td>'.date('d-m-Y',strtotime($val->fecha_pago)).'</td>';
                $output.='</tr>';
            }
            $output.='</table>';
        }
        $debug = false;
        if ($output!=''){
            $subject = 'Alertas de convenios sin pago por más de 30 días';
            $to = 'hedy@mattheiycia.cl,psalamanca@mattheiycia.cl,rflores@mattheiycia';
            $cc = 'ricardo.carrasco.p@gmail.com';
            $this->_alert_email($to,$cc,$subject,$output,debug);
        }
        //echo $output;
    }
	 	
	 	
	 	
	 	
}
?>