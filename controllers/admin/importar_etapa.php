<?php
class Importar_etapa extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}

public function importar_etapas(){

	  //$this->output->enable_profiler(TRUE);
		$this->load->model ( 'etapas_m' );
		
        $this->load->helper ( 'excel_reader2' );
		
		$array_return = array ();
		$array_return ['etapas_insert'] = 0;
		$array_return ['etapas_update'] = 0;
		
		
		$this->data ['operacion'] = FALSE;
		
		
		//echo 'INICIO IMPORTACIÓN<br>';
		//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		$rows_insert = array ();
		$uploadpath = "./uploads/importador_etapas.xls"; // ver
		$excel = new Spreadsheet_Excel_Reader ( $uploadpath );
		$rowcount = $excel->rowcount ( $sheet_index = 0 );
		$colcount = $excel->colcount ( $sheet_index = 0 );
		
		for($i = 2; $i <= $rowcount; $i++) {//$rowcount
			
			//if ($i%10==0){echo date('d-m-Y H:i:s').' - Leyendo fila '.$i.'...<br>';}
			
			$codigo = ''; //A
			$actuacion = ''; //	B
		    $plazo_alarma = ''; //C
			$seleccionar_fecha_actuacion = ''; //D
			$mensaje_alarma = ''; //E
			$siguiente_actuacion = ''; //F
			$siempre_seleccionable = ''; //G
		
	        $codigo  = trim ( $excel->val ( $i, 'A', 0 ) );
			$actuacion = trim ( $excel->val ( $i, 'B', 0 ) );
			$plazo_alarma = trim ( $excel->val ( $i, 'C', 0 ) );
			$seleccionar_fecha_actuacion = trim ( $excel->val ( $i, 'D', 0 ) );

			if($seleccionar_fecha_actuacion != ''){ 
			$seleccionar_fecha_actuacion = date ( "Y-m-d", strtotime ( $seleccionar_fecha_actuacion ) );
			}
			$mensaje_alarma = trim ( $excel->val ( $i, 'E', 0 ) );
			$siguiente_actuacion = trim ( $excel->val ( $i, 'F', 0 ) );
			$siempre_seleccionable = trim ( $excel->val( $i, 'G', 0 ) );
			
			
			$codigo =  utf8_encode ( $codigo );
			$actuacion =  utf8_encode ( $actuacion );
            $plazo_alarma  = utf8_encode ( $plazo_alarma  );
			//$seleccionar_fecha_actuacion = utf8_encode ( $seleccionar_fecha_actuacion  );
			$mensaje_alarma= utf8_encode ($mensaje_alarma );
			$siguiente_actuacion =  utf8_encode ($siguiente_actuacion );
			$siempre_seleccionable = utf8_encode ($siempre_seleccionable );
			//$keyword= limpiar_acento ($keyword );
			//$precio = str_replace(array('$',',','.'),'',$precio);
            $idetapa = ''; 
			$idetapa = $this->etapas_m->search_id_record_exist(array('codigo'=>$codigo),'idtapa');
	
			
			// arr_categoria
			$arr_etapa = array ();
			if (! empty ( $codigo )) {
				$arr_etapa ['codigo'] = $codigo;
			}
			
			if (! empty ( $actuacion )) {
				$arr_etapa ['etapa'] = $actuacion;
			}
			if (! empty ( $plazo_alarma )) {
				$arr_etapa ['dias_alerta'] = $plazo_alarma;
			}
			if (! empty ( $seleccionar_fecha_actuacion )) {
				$arr_etapa ['seleccionar_fecha_alarma'] = $seleccionar_fecha_actuacion;
			}
			if (! empty ( $mensaje_alarma )) {
				$arr_etapa ['texto_alerta'] = $mensaje_alarma;
			}
			
		    if (! empty ( $siguiente_actuacion )) {
				$arr_etapa ['sucesor'] = $siguiente_actuacion;
			}
			
		     if (! empty ( $siempre_seleccionable )) {
				$arr_etapa ['tipo'] = $siempre_seleccionable;
			}
			
			
			
			
			// insert etapa

			if ($idetapa == '' || $idetapa == NULL) {
				//insert
				$arr_etapa = array_merge ( $arr_etapa, array ('fecha_crea' => date ( 'Y-m-d H:i:s' ), 'ip_crea' => $this->input->ip_address () ) );
				$idetapa = $this->etapas_m->insert ( $arr_etapa, TRUE, TRUE ); //retorna idpartida ingresada
				$array_return ['etapas_insert'] ++; //contabiliza cuantos ingresos
			} else {
				//update
				$arr_etapa = array_merge ( $arr_etapa, array ('fecha_mod' => date ( 'Y-m-d H:i:s' ), 'ip_mod' => $this->input->ip_address () ) );
				$this->etapas_m->update ( $idetapa, $arr_etapa, TRUE, TRUE );
				$array_return ['etapas_update'] ++; //contabiliza cuantas actualizaciones
			}

		}//for
		if ($array_return ['etapas_insert'] > 0 or $array_return ['etapas_update'] > 0) {
			$array_return ['operacion'] = TRUE;
		}
		if ($array_return ['etapas_insert'] > 0 or $array_return ['etapas_update'] > 0) {
			$array_return ['operacion'] = TRUE;
		}
		
		//redirect('admin/cuentas/reporte/etapas/');
		//$this->producto_categoria();
	//	echo '<pre>';
		//print_r( $array_return );
	//	echo '</pre>';
     }
  }