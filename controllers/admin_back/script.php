<?php
class Script extends CI_Controller {
	public $data = array();
	public $activo = 'S';
	protected $show_tpl = TRUE;
	public function Cuentas() { $this->__construct(); }
	function __construct() {
		parent::__construct(); 	
		$this->load->library ( 'session' );
		//if (!$this->session->userdata('usuario_id')){ redirect('admin/adm/login');}
		
		$this->load->helper ( 'date_html_helper' );
				
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'cuentas_m' );
		$this->load->model ( 'cuentas_etapas_m' );
		$this->load->model ( 'usuarios_m' );
		$this->load->model ( 'mandantes_m' );
		$this->load->model ( 'tipo_productos_m' );
		$this->load->model ( 'administradores_m' );	
		$this->load->model ( 'estados_cuenta_m' );
		$this->load->model ( 'cuentas_pagos_m');
		$this->load->model ( 'comprobantes_m');
		$this->load->model ( 'cuentas_gastos_m');
		$this->load->model ( 'cuentas_historial_m');
		$this->load->model ( 'etapas_m' );	
		$this->load->model ( 'tribunales_m' );
		$this->load->model ( 'comunas_m' );
		$this->load->model ( 'documento_m' );
		$this->load->model ( 'log_etapas_m' );
		$this->load->model ( 'receptor_m' );
		$this->load->model ( 'diligencia_m' );
		$this->load->model ( 'direccion_m' );
		$this->load->model ( 'telefono_m' );
		$this->load->model ( 'bienes_m' );
		$this->load->model ( 'nodo_m' );
		$this->load->model ( 'pagare_m' );
		$this->load->model ( 'tipo_productos_m' );
		/*seters*/
		$this->data['current'] = 'script';
		$this->data['sub_current'] = '';
		$this->data['plantilla'] = 'script/';
		$this->data['lists'] = array();
			
		
	}
	
	public function rutsinpuntos(){
		//$this->output->enable_profiler(TRUE);
		$usuarios = $this->usuarios_m->get_all();
		foreach ($usuarios as $key=>$val){
			$rut = str_replace('.','',$val->rut);
			
			$query = "UPDATE 0_usuarios SET rut = '".$rut."' WHERE id='".$val->id."'";
			//echo $query.'<br>';
			$this->db->query($query );

		}
		$pagares = $this->pagare_m->get_all();
		foreach ($pagares as $key=>$val){
			$n_pagare = str_replace('.','',$val->n_pagare);
			$query = "UPDATE pagare SET n_pagare = '".$n_pagare."' WHERE idpagare='".$val->idpagare."'";
			//echo $query.'<br>';
			$this->db->query($query );
			
		}
	}
	public function borrarpagares(){
		
		 $usuarios = $this->usuarios_m->get_all();
		 foreach($usuarios as $key=>$val){
		 	$rut = $val->rut.'-1';
		 	$pagare = $this->pagare_m->get_by(array('n_pagare'=>$rut));
		 	if (count($pagare)==1){
		 		$this->pagare_m->save( $pagare->idpagare, array( 'activo'=> 'N')  );
		 	}
		 }
	}
	public function cuentaspunteros()
	{
		 $cuentas = $this->cuentas_m->get_all();
		 foreach($cuentas as $key=>$val){
		 	/*ABONOS*/
		 	$suma_abono=0;
		 	$fecha_ultimo_pago = '';
		 	$this->db->order_by('fecha_pago DESC,fecha_crea DESC');
		 	$this->db->where('activo','S');
		 	$abonos = $this->cuentas_pagos_m->get_many_by( array('id_cuenta'=>$val->id));
		 	$i = 1;
		 	if( count($abonos)>0 ){
		 		foreach($abonos as $k=>$v){
		 			$suma_abono = $suma_abono+$v->monto_pagado;
		 			if ($i==1){
		 				$fecha_ultimo_pago = $v->fecha_pago;
		 			}
		 			$i++;
		 		}
		 	}
		 	/*GASTOS*/
		 	$suma_gastos=0;
		 	$this->db->where('activo','S');
		 	$gastos = $this->cuentas_gastos_m->get_many_by( array('id_cuenta'=>$val->id));
		 	if( count($gastos)>0 ){
		 		foreach($gastos as $k=>$v){
		 			$suma_gastos = $suma_gastos+$v->monto;
		 		}
		 	}
		 	/*PAGARE*/
		 	$suma_deuda=0;
		 	$this->db->where('activo','S');
		 	$pagares = $this->pagare_m->get_many_by( array('idcuenta'=>$val->id));
		 	
		 	if( count($pagares)>0 ){
		 		foreach($pagares as $k=>$v){
		 			$suma_deuda = $suma_deuda+$v->monto_deuda;
		 		}
		 	}
		 	
			/*ETAPA JUICIO*/
		 	$id_etapa=0;
		 	$fecha_etapa = '';
		 	$this->db->where('activo','S');
		 	$this->db->order_by('fecha_etapa DESC,fecha_crea DESC');
		 	$etapas = $this->cuentas_etapas_m->get_by( array('id_cuenta'=>$val->id,'id_etapa !='=>0));
		 	if (count($etapas)==1){
		 		$id_etapa = $etapas->id_etapa;
		 		$fecha_etapa = $etapas->fecha_etapa;
		 	}
		 	
		 	echo 'CUENTA '.$val->id.' - Abonos: '.$suma_abono.' Fecha ult. pago '.$fecha_ultimo_pago.' Gastos: '.$suma_gastos.' Deuda: '.$suma_deuda.' Etapa: '.$id_etapa.' Fecha etapa'.$fecha_etapa.'<br>';
		 	$this->cuentas_m->update_by( array('id'=>$val->id),array('monto_deuda'=>$suma_deuda,'monto_pagado_new'=>$suma_abono,'monto_gasto_new'=>$suma_gastos,'id_etapa'=>$id_etapa,'fecha_etapa'=>$fecha_etapa,'fecha_ultimo_pago'=>$fecha_ultimo_pago));
		 } 
	}
	public function puntospagare(){
		$this->output->enable_profiler(TRUE);
		$pagares = $this->pagare_m->get_many_by(array('activo'=>'S'));
		foreach ($pagares as $key=>$val){
			$fields_save = array();
			$fields_save['monto_deuda'] = str_replace(',','.',$val->monto_deuda);
			$this->pagare_m->save_default($fields_save,$val->idpagare);
		}
	}
	public function cuentascondosdirecciones(){
		$this->db->select('count(*) as cuantos,id,direccion,id_cuenta,id_comuna');
		$this->db->group_by('id_cuenta');
		$direcciones = $this->direccion_m->get_many_by(array('activo'=>'S'));
		foreach ($direcciones as $key=>$val){
			if ($val->cuantos>1){
				echo $val->id_cuenta.'<br>';
			}
		}
	}
	public function borrardireccionesduplicada(){
		$this->output->enable_profiler(TRUE);
		$this->db->select('count(*) as cuantos,id,direccion,id_cuenta,id_comuna');
		$this->db->group_by('direccion,id_cuenta,id_comuna');
		$direcciones = $this->direccion_m->get_many_by(array('activo'=>'S'));
		foreach ($direcciones as $key=>$val){
			if ($val->cuantos>1){
				echo $val->id.' : '.$val->cuantos.' =>'.$val->direccion.' IDC '.$val->id_cuenta.' COM '.$val->id_comuna.'<br>';
				$borrar_direccion = $this->direccion_m->get_many_by(array('activo'=>'S','direccion'=>$val->direccion,'id_cuenta'=>$val->id_cuenta,'id_comuna'=>$val->id_comuna));
				$i = 1;
				foreach ($borrar_direccion as $k=>$v){
					
					$fields_save = array();
					if ($i==1){
						$fields_save['estado']=2;
					} else {
						$fields_save['activo']='N';
						echo $i.' borrando '.$v->id.'<br>';
					}
					
					$this->direccion_m->save_default($fields_save,$v->id);
					$i++;
				}
			}
		}
	}
	public function distritotribunal(){
		$cuentas = $this->cuentas_m->get_many_by(array('activo'=>'S'));
		foreach ($cuentas as $key=>$val){
			
			$id_tribunal_aux = $val->id_tribunal;
			$id_distrito_aux = $val->id_distrito;
			
			if ($id_tribunal_aux>0){
				$trib = $this->tribunales_m->get_by(array('id'=>$id_tribunal_aux));
				if (count($trib)==1){
					if ($trib->padre==0){
						$id_distrito = $id_tribunal_aux;
						$id_tribunal = $id_distrito_aux;
					} else {
						$id_distrito = $id_distrito_aux;
						$id_tribunal = $id_tribunal_aux;
					}
				}
			}
			
			if ($id_tribunal>0){
				$trib = $this->tribunales_m->get_by(array('id'=>$id_tribunal));
				if (count($trib)==1){
					if ($trib->padre==0){
						echo 'fix distrito '.$id_distrito.'=>'.$id_tribunal.' | tribunal'.$id_tribunal.'=>0<br>';
						$id_distrito = $id_tribunal;
						$id_tribunal = 0;
					} elseif ($id_distrito!=$trib->padre){
						echo 'fix distrito '.$id_distrito.'=>'.$trib->padre.'<br>';
						$id_distrito = $trib->padre;
					}
				}
			}
			if ($id_distrito_aux>0 || $id_tribunal_aux>0){
				echo $val->id.'___  '.' distrito '.$id_distrito_aux.'=>'.$id_distrito.' | tribunal '.$id_tribunal_aux.'=>'.$id_tribunal.'<br>';
				$fields_save = array();
				$fields_save = array('id_distrito'=>$id_distrito,'id_tribunal'=>$id_tribunal);
				$this->cuentas_m->save_default($fields_save,$val->id);
			}
			
		}
		
	}
	public function convenios30diasdenopago() {
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

        $tipos = array('0'=>'Tipo','1'=>'Particular','2'=>'Comercial','3'=>'Celular','4'=>'Otro');
        $estados = array('0'=>'Sin confirmación','1'=>'Vigente','2'=>'No Vigente');

		if (count($lists)>0){
			$output='Listado de deudores con convenio que no han pagado hace más de 30 días';
			$output.= '<table><tr><th>Mandante</th><th>RUT Deudor</th><th>Nombre Deudor</th><th>Teléfonos</th><th>Fecha de Pago</th></tr>';
			foreach ($lists as $key=>$val){

                $this->db->order_by('fecha_crea DESC');
                $this->db->where(array('activo' => 'S'));
                $t = '';
                $telefonos = $this->telefono_m->get_many_by( array('id_cuenta'=>$val->id_cuenta) );
                if (count($telefonos)>0) {
                    foreach ($telefonos as $key => $telefono) {
                        if ($telefono->estado==1 || $telefono->estado==0){
                            $t.=$tipos[$telefono->tipo].':'.$telefono->numero.' ('.$estados[$telefono->estado].')<br>';
                        }
                    }
                }


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
	public function abonos60dias() {
		//$this->output->enable_profiler(TRUE); 
		$like = array();
		$this->load->model ( 'cuentas_m' );
		
	    $cols[] = 'm.razon_social AS razon_social';
		$cols[] = 'u.nombres AS nombres';
		$cols[] = 'u.ap_pat AS ap_pat';
		$cols[] = 'u.ap_mat AS ap_mat';
		$cols[] = 'u.rut AS rut';
		$cols[] = 'c.dia_vencimiento_cuota AS cuentas_dia_vencimiento_cuota';
		$cols[] = 'c.id AS cuentas_id';
		$cols[] = 'c.rol1 AS rol1';
		$cols[] = 'c.rol1_y AS rol1_y';
		$cols[] = "DATEDIFF( NOW() , cp.fecha_pago ) AS dias_diferencia";
		$cols[] = "cp.fecha_pago AS fecha_pago";
		$cols[] = "t.tribunal AS tribunal";
		$cols[] = "t2.tribunal AS distrito";
		$cols[] = "com.nombre AS comuna";
		$having = '';		
		/**/
		$this->db->select($cols);
		$this->db->join('0_usuarios u', 'u.id = c.id_usuario');
		$this->db->join('0_mandantes m', 'm.id = c.id_mandante');
		$this->db->join("2_cuentas_pagos cp", "cp.id_cuenta = c.id AND cp.activo='S' AND cp.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=c.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left");					 
		$this->db->join("s_tribunales t",'t.id = c.id_tribunal','left');
		$this->db->join("s_tribunales t2",'t.id = c.id_distrito','left');
		$this->db->join("s_comunas com",'com.id = u.id_comuna','left');
		if ($having!=''){
			$this->db->having($having);
		}
		$this->db->where('m.codigo_mandante', 'PROMOTORA CMR');
		$this->db->where('DATEDIFF( NOW() , cp.fecha_pago )>=60');
		if (count($like)>0){
			$this->db->like($like);
		}
		$this->db->where("(`c`.`id_estado_cuenta`='9' OR `c`.`id_estado_cuenta`='5')");
		$this->db->group_by('c.id');
		$query = $this->db->get('0_cuentas c');
		$lists = $query->result() ;
		
		if (count($lists)>0){
			$output='Listado de deudores sin convenios que no han abonado hace más de 60 días';
			$output.= '<table><tr><th>Mandante</th><th>RUT Deudor</th><th>Nombre Deudor</th><th>Fecha de Pago</th><th>Rol</th><th>Tribunal</th><th>Comuna</th></tr>';
			foreach ($lists as $key=>$val){
				$output.='<tr>';
				$output.='<td>'.$val->razon_social.'</td>';
				$output.='<td>'.$val->rut.'</td>';
				$output.='<td>'.$val->nombres.' '.$val->ap_pat.' '.$val->ap_mat.'</td>';
				$output.='<td>'.date('d-m-Y',strtotime($val->fecha_pago)).'</td>';
				$output.='<td>'.$val->rol1.'-'.$val->rol1_y.'</td>';
				$output.='<td>'.$val->tribunal.' '.$val->distrito.'</td>';
				$output.='<td>'.$val->comuna.'</td>';
								
				$output.='</tr>';
			}
			$output.='</table>';
		}
		$debug = false;
		if ($output!=''){
			$subject = 'Alertas de deudores sin convenios que han abonado hace más de 60 días';
			$to = 'hedy@mattheiycia.cl,psalamanca@mattheiycia.cl,avenegas@mattheiycia.cl';
			$cc = 'ricardo.carrasco.p@gmail.com';
			$this->_alert_email($to,$cc,$subject,$output,$debug);
		}
		//echo $output;
	}
    public function gastospendientes(){
        //$this->output->enable_profiler(TRUE);
        $this->load->model ( 'cuentas_m' );
        $this->load->model ( 'cuentas_gastos_m' );
        $gastos = $this->cuentas_gastos_m->get_many_by(array('id_estado_pago'=>-1,'activo'=>'S'));
        $output = '';
        if (count($gastos)>0){
            $output='Listado de gastos pendientes<br>';

            $output.= '<table><tr><th>RUT Deudor</th><th>Fecha</th></tr>';
            foreach ($gastos as $key=>$val){
                $cuenta = $this->cuentas_m->get_by(array('id'=>$val->id_cuenta));
                $usuario = $this->usuarios_m->get_by(array('id'=>$cuenta->id_usuario));
                $output.='<tr>';
                $output.='<td>'.$usuario->rut.'</td>';
                $output.='<td>'.date('d-m-Y',strtotime($val->fecha)).'</td>';
                $output.='</tr>';
            }
            $output.='</table>';
        }
        $debug = false;

        if ($output!=''){
            $subject = 'Gastos pendientes';
            $to = 'hedy@mattheiycia.cl,psalamanca@mattheiycia.cl';
            $cc = 'ricardo.carrasco.p@gmail.com';
            $this->_alert_email($to,$cc,$subject,$output,$debug);
        }
        //echo $output;
    }
	public function deudores200k() {
		//$this->output->enable_profiler(TRUE); 
		$this->cuentaspunteros();
		$this->load->model ( 'cuentas_m' );
		
	    $where = array();
	    $where['mand.codigo_mandante'] = 'PROMOTORA CMR';
	    $where['(`c`.`monto_deuda`-`c`.`monto_pagado_new`+`c`.`monto_gasto_new`+`c`.`intereses`) >'] = 0;
		$where['(`c`.`monto_deuda`-`c`.`monto_pagado_new`+`c`.`monto_gasto_new`+`c`.`intereses`) <='] = 250000;
		$where_str = '(`c`.`id_estado_cuenta` =1 OR `c`.`id_estado_cuenta`=5)';
		$listado = $this->cuentas_m->get_cuenta_deuda($where,$where_str);
		$lists = $listado ;
		echo '<br>Encontrados '.count($lists).'<br>';
		$output = '';
		if (count($lists)>0){
			$output='Listado de deudores <= $250.000<br>';
			$output.= '<table><tr><th>Mandante</th><th>RUT Deudor</th><th>Nombre Deudor</th><th>Procurador</th><th>Deuda (+gastos+intereses)</th></tr>';
			foreach ($lists as $key=>$val){
				$output.='<tr>';
				$output.='<td>'.$val->razon_social.'</td>';
				$output.='<td>'.$val->rut.'</td>';
				$output.='<td>'.$val->nombres.' '.$val->ap_pat.' '.$val->ap_mat.'</td>';
				$output.='<td>'.$val->apellidos.'</td>';
				
				$output.='<td>$'.number_format($val->monto_deuda,'0',',','.').'</td>';
                $output.='</tr>';
			}
			$output.='</table>';
		}
		$debug = false;

		if ($output!=''){
			$subject = 'Alertas de deudores <= $250.000';
			$to = 'hedy@mattheiycia.cl,psalamanca@mattheiycia.cl';
			$cc = 'ricardo.carrasco.p@gmail.com';
			$this->_alert_email($to,$cc,$subject,$output,$debug);
		}
		//echo $output;
	}	
	public function _alert_email($to,$cc,$subject='',$output=null,$debug=false){
		$this->load->library('email');
		$this->email->from('noreply@hmycia.cl', 'HMYCIA');
		$this->email->to($to);
		$this->email->cc($cc);
		$this->email->subject($subject);
		$this->email->message($output);
		$this->email->send();
		if ($debug){
			echo $this->email->print_debugger();
		}
	}
	public function fechapagare(){
		$pagares = $this->pagare_m->get_many_by(array('activo'=>'S','fecha_asignacion !='=>'0000-00-00','fecha_asignacion !='=>''));
		foreach ($pagares as $key=>$val){
			$c = $this->cuentas_m->get_by(array('id'=>$val->idcuenta));
			
			$fields_save = array();
			$fields_save['fecha_asignacion'] = $val->fecha_asignacion;
			
			$this->cuentas_m->save_default($fields_save,$val->idcuenta);
		}
	}
	public function separar_rol(){
		$this->output->enable_profiler(TRUE); 
		$cuentas = $this->cuentas_m->get_all();
		foreach($cuentas as $key=>$val){
			if ($val->rol!=''){
				$rol = explode('-',$val->rol);
				if (count($rol)==2){
					if (in_array($rol[1],array(2010,2011,2012,2013,2014,2015))){
						$fields_save = array();
						$fields_save['rol1'] = $rol[0];
						$fields_save['rol1_y'] = $rol[1];
						echo 'cambio '.$rol[0].' '.$rol[1].'<br>';
						$this->cuentas_m->save_default($fields_save,$val->id);
					} else {
						$fields_save = array();
						$fields_save['rol1'] = $val->rol;
						$this->cuentas_m->save_default($fields_save,$val->id);
					}
				} else {
					$fields_save = array();
					$fields_save['rol1'] = $val->rol;
					$this->cuentas_m->save_default($fields_save,$val->id);
				}
			}
		}
	}
	
	
}

?>