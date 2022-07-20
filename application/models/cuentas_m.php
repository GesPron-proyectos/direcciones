<?php
class Cuentas_M extends EMP_Model{
	public function EMP_Model() { $this->__construct(); }

	public function __construct() {
		parent::__construct();
		$this->_table = '0_cuentas';
		$this->primary_key = 'id';
		$this->alias = 'c';
		$this->field_posicion = 'posicion';
		$this->field_categoria = '';
		
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
                     'rules'   => 'required'
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
            /*array(
                     'field'   => 'fecha_inicio_day',
                     'label'   => 'DÃ­a',
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
                  ),*/
                  ///
            array(
                     'field'   => 'fecha_escritura_personeria',
                     'label'   => 'Fecha escritura personeria',
                     'rules'   => ''
                  ),
            array(
                     'field'   => 'notaria_personeria',
                     'label'   => 'Notaria personería',
                     'rules'   => ''
                  ),
            array(
                     'field'   => 'titular_personeria',
                     'label'   => 'Titular personeria',
                     'rules'   => ''
                  )
            );
	}
	
	
	public function save($id,$post){
		$fields_save = array();
		
		$columnas = $this->db->list_fields($this->_table);
		foreach ($columnas as $columna){
			if(isset($post[$columna])){
				$fields_save[$columna] = $post[$columna];
			}
		}
	   	
		if (count($fields_save)>0){
			$this->save_default($fields_save,$id ,TRUE, TRUE);
	     }
		return false;
	}
	############################################################
	################### QUERY VISTA RESUMEN CUENTAS#############
	############################################################
	public function get_cuentas($idcuenta= ''){
		
		$cols = array();
		$cols[] = 'c.rut AS rut';
		$cols[] = 'c.dv AS dv';
		$cols[] = 'c.cuenta AS cuenta';
		$cols[] = 'c.datos AS datos';
		
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("0_receptores res","res.id=c.receptor","left");
        $this->db->join("s_tribunales tri","tri.id=c.id_tribunal","left");
        $this->db->join("jurisdiccion ju","ju.id=c.id_distrito","left");
        $this->db->join("s_tribunales trie","trie.id=c.id_tribunal_ex","left");
        $this->db->join("jurisdiccion jue","jue.id=c.id_distrito_ex","left");
		$this->db->join("s_etapas seta","seta.id=c.id_etapa","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
		$this->db->join("2_cuentas_contratos 2cc", "2cc.id_cuenta = c.id","left");
        $this->db->join("pagare p","p.idcuenta=c.id","left");
        $this->db->join("anio a","a.id=c.anio","left");
        $this->db->join("tipo_causa tp","tp.id=c.letraC","left");
        $this->db->join("tipo_causa tpp","tpp.id=c.letraE","left");
        $this->db->join("anio an","an.id=c.anioE","left");
        $this->db->join("marcas_especiales me","me.idMarca=c.id_marcas_especiales","left");
        // $this->db->where(array('c.activo' => 'S'));
		$this->db->where(array('c.activo' => 'S'));
		$this->db->where(array('c.id'=>$idcuenta));  
		
		$this->db->group_by('c.id');
		
		$query = $this->db->get();
		$result = $query->result();
		
		return $result[0];
		
	}
	
	public function get_reporte_jurisdiccion(){
		
		$cols = array();
		$cols[] = 'COUNT(c.id) AS cuantos';
		$cols[] = 'c.id AS id';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.padre AS padre';
		$cols[] = 'jur.jurisdiccion AS jurisdiccion';
	
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("s_tribunales tr","tr.id=c.id_tribunal");
        $this->db->join("s_jurisdiccion jur","jur.id=tr.id_jurisdiccion");
        $this->db->where(array('c.activo' => 'S'));
	    //$this->db->group_by('tr.id');
         $this->db->group_by('jur.id');
		
		$query = $this->db->get();
		return $query;
	}
	
	public function get_procurador($where = array()){
		
		$cols = array();
	
        if(count($where>0)){$this->db->where($where);}
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'adm.fecha_crea AS fecha_crea';
		$cols[] = 'res.nombre AS nombre_receptor';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'tr.id AS id_tribunal';
		
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dist.id AS id_distrito';
		$cols[] = 's_cuenta.estado AS estado';
		
		$cols[] = 'c.n_cuotas_real AS n_cuotas_real';
		$cols[] = 'c.intereses AS intereses';
		$cols[] = 'c.valor_cuota AS valor_cuota';
		$cols[] = 'c.valor_cuota_real AS valor_cuota_real';
		$cols[] = 'c.valor_cuota_con_intereses AS valor_cuota_con_intereses';
		$cols[] = 'c.dia_vencimiento_cuota AS dia_vencimiento_cuota';
		
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.id AS id';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'c.n_cuotas AS n_cuotas';
		$cols[] = 'c.fecha_primer_pago AS fecha_primer_pago';
		$cols[] = 'c.monto_deuda AS monto_deuda';
		$cols[] = 'c.monto_pagado_new AS monto_pagado_new';
		$cols[] = 'c.fecha_primer_pago AS fecha_primer_pago';
		$cols[] = 'c.monto_gasto_new AS monto_gasto_new';
		
		$cols[] = 'man.razon_social AS razon_social';
		
		$cols[] = 'c.tipo_demanda AS tipo_demanda';
		$cols[] = 'c.exorto AS exorto';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.fecha_inicio AS fecha_inicio';
		$cols[] = 'c.id_procurador AS id_procurador';
		
		$cols[] = 'ce2.id_etapa AS id_etapa';
		$cols[] = 'ce2.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce2.observaciones AS observaciones';
		$cols[] = 'ce2.id AS idce';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'trie.tribunal AS tribunalE';
		$cols[] = 'c.rolE AS rolE';
		$cols[] = '(SELECT observaciones FROM 2_cuentas_etapas WHERE 2_cuentas_etapas.id_cuenta = c.id ORDER BY id DESC LIMIT 1 ) as observaciones';
		$cols[] = 'diste.tribunal AS DistritoE';
		
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario");
        $this->db->join("0_mandantes man","man.id=c.id_mandante");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("0_receptores res","res.id=c.receptor","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
        $this->db->join("2_cuentas_pagos pag2", "pag2.id_cuenta = c.id","left");
        
        $this->db->join("2_cuentas_etapas ce2" ,"ce2.id_cuenta = c.id");
        $this->db->join("s_etapas etap","etap.id=ce2.id_etapa");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        
		$this->db->join("s_tribunales trie", "trie.id = c.id_tribunal_ex","left");
		//$this->db->join("s_tribunales tre", "tre.id = cta.id_tribunal_ex","left")
		$this->db->join("s_tribunales diste", "diste.id = tr.padre","left");
        
        /*$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta AND c.id_estado_cuenta>0");
        $this->db->join("2_cuentas_etapas ce","ce.id_etapa =c.id_etapa AND ce.id_etapa<>0 AND ce.activo='S'","left");
         $this->db->join("s_etapas seta","seta.id=ce.id_etapa","left");*/
        
        // $this->db->where(array('c.activo' => 'S'));
		//$this->db->where(array('c.activo' => 'S'));
		//$this->db->limit(3000);  
		
		$this->db->group_by('c.id');
		
		$query = $this->db->get();
		
		return $query->result();
	}	
	public function get_procurador_excel($where = array()){
		
		$cols = array();
	
        if(count($where>0)){$this->db->where($where);}
		
		$this->db->select("cta.id AS id,
							cta.activo AS activo,
							cta.publico AS publico,
							cta.posicion AS posicion,
							cta.id_procurador,
							cta.id_estado_cuenta AS id_estado_cuenta,,
							usr.nombres AS nombres,
							usr.ap_pat AS ap_pat,
							usr.ap_mat AS ap_mat,
							usr.rut AS rut,
							mand.codigo_mandante,
							mand.razon_social,
							tip.tipo AS tipo_producto,
							cta.fecha_asignacion AS fecha_asignacion,
							cta.monto_demandado AS monto_demandado,
							cta.monto_deuda AS monto_deuda,
							cta.id_etapa,
							cta.fecha_etapa,
							cta.receptor,
							cta.id_mandante AS field_categoria,
							cta.id_procurador,
							tr.tribunal,
							tr.id AS id_tribunal,
							dist.tribunal as distrito,
							dist.id as id_distrito,
							cta.rol as rol,
							cta.exorto as exorto,
							cta.id_etapa AS id_etapa,
							etap.etapa AS etapa_actual,
							etap.seleccionar_fecha_alarma,			
							SUM(pag.monto_pagado) AS total_pagado,
							tre.tribunal as tribunalE, 
							cta.id_distrito_ex, 
							cta.rolE,
							(SELECT observaciones FROM 2_cuentas_etapas WHERE 2_cuentas_etapas.id_cuenta = cta.id ORDER BY id DESC LIMIT 1 ) as observaciones,
							DATEDIFF( NOW() , cta.fecha_etapa ) AS dias_diferencia");
		//$this->db->from("0_cuentas c");
		//$this->db->join("0_usuarios usr","usr.id=c.id_usuario");
		$this->db->from("0_cuentas cta");
        $this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
		$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
		$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left")	;
		$this->db->join("2_cuentas_pagos pag", "pag.id_cuenta = cta.id AND pag.activo='S'","left");
						
		$this->db->join("s_tribunales tr", "tr.id = cta.id_tribunal","left");
		$this->db->join("s_tribunales dist", "dist.id = tr.padre","left");
						
		$this->db->join("s_tribunales tre", "tre.id = cta.id_tribunal_ex","left");
		$this->db->join("s_tribunales diste", "diste.id = tr.padre","left");
		$this->db->join("s_etapas etap","etap.id=cta.id_etapa");;
		$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=cta.id_estado_cuenta","left");
			
		//$this->db->where($where)->like($like);

		//$this->db->order_by($order_by)  ;
		//$this->db->group_by("cta.id");
				
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}	
	
	
	
	public function get_procurador_api($where = array()){
		
		$cols = array();
	
        if(count($where>0)){$this->db->where($where);}
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'adm.fecha_crea AS fecha_crea';
		$cols[] = 'res.nombre AS nombre_receptor';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'tr.id AS id_tribunal';
		
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dist.id AS id_distrito';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'seta.etapa AS etapa';
		
		$cols[] = 'c.n_cuotas_real AS n_cuotas_real';
		$cols[] = 'c.intereses AS intereses';
		$cols[] = 'c.valor_cuota AS valor_cuota';
		$cols[] = 'c.valor_cuota_real AS valor_cuota_real';
		$cols[] = 'c.valor_cuota_con_intereses AS valor_cuota_con_intereses';
		$cols[] = 'c.dia_vencimiento_cuota AS dia_vencimiento_cuota';
		
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.id AS id';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'c.n_cuotas AS n_cuotas';
		$cols[] = 'c.fecha_primer_pago AS fecha_primer_pago';
		$cols[] = 'c.monto_deuda AS monto_deuda';
		$cols[] = 'c.monto_pagado_new AS monto_pagado_new';
		$cols[] = 'c.fecha_primer_pago AS fecha_primer_pago';
		$cols[] = 'c.monto_gasto_new AS monto_gasto_new';
		
		$cols[] = 'man.razon_social AS razon_social';
		
		$cols[] = 'c.tipo_demanda AS tipo_demanda';
		$cols[] = 'c.exorto AS exorto';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.fecha_inicio AS fecha_inicio';
		$cols[] = 'c.id_procurador AS id_procurador';
		
		$cols[] = 'ce.id_etapa AS id_etapa';
		$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce.observaciones AS observaciones';
		$cols[] = 'ce.id AS idce';
		$cols[] = 'seta.etapa AS etapa';
		$cols[] = 'seta.sucesor AS sucesor';
		$cols[] = 's_cuenta.estado AS estado_cuenta';
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario");
        $this->db->join("0_mandantes man","man.id=c.id_mandante");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("0_receptores res","res.id=c.receptor","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
        $this->db->join("2_cuentas_pagos pag2", "pag2.id_cuenta = c.id","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta AND c.id_estado_cuenta>0");
        $this->db->join("2_cuentas_etapas ce","ce.id_etapa =c.id_etapa AND c.id=ce.id_cuenta AND ce.id_etapa<>0 AND ce.activo='S'","left");
         $this->db->join("s_etapas seta","seta.id=ce.id_etapa","left");
        
        // $this->db->where(array('c.activo' => 'S'));
		//$this->db->where(array('c.activo' => 'S'));
		//$this->db->limit(3000);  
		
		$this->db->group_by('c.id');
		$this->db->order_by('c.rol asc, ce.fecha_etapa desc');
		
		$query = $this->db->get();
		return $query->result();
	}	
	
	public function get_reporte_fecha_asignacion($where=array()){
		
		$cols = array();
	    
		if(count($where)>0){
		if(count($where>0)){$this->db->where($where);}
		}
		$cols[] = 'COUNT(c.id) AS cuantos';
		$cols[] = 'c.id AS id';
		//$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'DATEDIFF(NOW(),fecha_asignacion) AS dias';
		
	    $this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->where(array('c.activo' => 'S'));
	    // $this->db->group_by('c.id');
		
		$query = $this->db->get();
		return $query;
	}
	
	public function get_cuenta_excel($sql_where='',$group_by='c.id,cet.id'){
		
		//echo 'entro a la funcion';
		
		$cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_tribunal AS id_tribunal';
		$cols[] = 'c.id_distrito AS id_distrito';
		$cols[] = 'c.receptor AS receptor';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.id_usuario AS id_usuario';
		$cols[] = 'c.fecha_inicio AS fecha_inicio';
		$cols[] = 'c.id_etapa AS id_etapa';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'c.id_administrador AS id_administrador';
		
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'man.razon_social AS razon_social';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'adm.fecha_crea AS fecha_crea';
		$cols[] = 'res.nombre AS nombre_receptor';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'seta.etapa AS etapa';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'c.fecha_etapa AS fecha_etapa'; 
		$cols[] = 'seta.dias_alerta AS dias_alerta';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as duracion';
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("0_receptores res","res.id=c.receptor","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        $this->db->join("s_comunas com","com.id=dir.id_comuna","left");
        $this->db->join("2_cuentas_etapas cet","cet.id_cuenta = c.id", "left");
        $this->db->join("s_etapas seta","seta.id=cet.id_etapa");
        
        //$this->db->where(array('c.activo' => 'S'));
        //$this->db->limit('30');
        if ($sql_where!=''){
        	$this->db->where($sql_where);
        }
        
        $this->db->group_by($group_by);
        $this->db->order_by("cet.fecha_etapa DESC");
        $query = $this->db->get();
		return  $query->result();
	    
	}
	
	public function get_cuenta_estado_excel($where){
		
		//echo 'entro a la funcion';
        
		if(count($where>0)){$this->db->where($where);}
		
        $cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_usuario AS id_usuario';
		$cols[] = 'c.id_etapa AS id_etapa';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'c.monto_deuda AS monto_deuda';
		$cols[] = 'c.monto_pagado_new AS monto_pagado_new';
		$cols[] = 'c.fecha_ultimo_pago AS fecha_ultimo_pago';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'man.razon_social AS razon_social';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'seta.etapa AS etapa';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'p.fecha_vencimiento AS fecha_asignacion_pagare';
		$cols[] = 'pag.fecha_pago AS fecha_pago'; 
		$cols[] = 'seta.dias_alerta AS dias_alerta';
		$cols[] = 'DATEDIFF(NOW(),ce.fecha_etapa) as duracion_etapa';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		//$cols[] = 'SUM(pag2.monto_remitido) AS monto_remitido';
		$this->db->select($cols);
		
		
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario AND usr.activo='S'");
        $this->db->join("0_mandantes man","man.id=c.id_mandante");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id AND ce.id_etapa=c.id_etapa AND ce.id = (SELECT id FROM 2_cuentas_etapas WHERE id_cuenta=c.id AND id_etapa=c.id_etapa ORDER BY fecha_etapa DESC LIMIT 0,1)","left");
        $this->db->join("s_etapas seta","seta.id=ce.id_etapa","left");
        $this->db->join("pagare p","p.idcuenta=c.id","left");
        $this->db->join("2_cuentas_pagos pag", "pag.id_cuenta = c.id AND pag.activo='S' AND pag.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=c.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left"); 
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->where(array('c.activo' => 'S'));
        $this->db->order_by("c.fecha_asignacion", "DESC");
        $this->db->group_by('c.id');
        $query = $this->db->get();
		return  $query->result();
	}
	
	
	public function gastos_cuentas($idcuenta){
		
	    $cols = array();
		$cols[] ='c.monto_deuda AS monto_deuda';
		
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("2_cuentas_gastos gastos","gastos.id_cuenta=c.id","left");
	    
        $this->db->where(array('c.id'=>$idcuenta));
		$query = $this->db->get();
		return $query->result();
	}
	
	
	public function get_etapas_juicio(){
		
		//echo 'entro a la funcion';
		
		$cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_etapa AS id_etapa';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.id_tribunal AS id_tribunal';
		$cols[] = 'c.id_usuario AS id_usuario';
		$cols[] = 'c.id_etapa AS id_etapa';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'seta.etapa AS etapa';
		$cols[] = 'seta.fecha_crea AS fecha_crea';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'tri.tribunal AS tribunal';
		 
	    $this->db->select($cols);
		
		
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario AND usr.activo='S'");
        $this->db->join("s_etapas seta","seta.id=c.id_etapa","left");
        $this->db->join("s_tribunales tri","tri.id=c.id_tribunal","left");
        
        $this->db->where(array('c.activo' => 'S'));
       // $this->db->order_by("c.fecha_asignacion", "DESC");
        $this->db->group_by('c.id');
        $query = $this->db->get();
		return  $query->result();
	}
	
	
	public function get_cuenta_deuda($where,$where_str=''){
		
	if(count($where>0)){$this->db->where($where);}
		
		    $cols = array();
			$cols [] = 'usu.rut AS rut';
			$cols [] = 'usu.nombres AS nombres';
			$cols [] = 'usu.ap_pat AS ap_pat';
			$cols [] = 'usu.ap_mat AS ap_mat';
			$cols [] = 'adm.apellidos AS apellidos';
			$cols [] = '(c.monto_deuda-c.monto_pagado_new+c.monto_gasto_new+c.intereses) AS monto_deuda';
			$cols [] = 'c.monto_gasto_new AS monto_gasto_new';
			$cols [] = 'c.intereses AS intereses';
			$cols [] = 'mand.razon_social AS razon_social';
			
			$this->db->select($cols);
			$this->db->from("0_cuentas c");
		    $this->db->join("0_usuarios usu","usu.id=c.id_usuario","left");
		    $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
		    $this->db->join("0_mandantes mand","mand.id=c.id_mandante","left");
		    $this->db->where(array('c.activo'=>'S'));
		    if ($where_str!=''){
		    	$this->db->where($where_str);
		    }
			
			$this->db->order_by ( 'usu.nombres DESC');
			$query = $this->db->get ();
			return $query->result ();	
	}
	public function get_cuentas_listado($cols = array(),$where=array(), $group_by='', $order_by = ''){
				
		$cols[] = 'c.id AS id';
	
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("0_receptores res","res.id=c.receptor","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		$this->db->join("s_etapas seta","seta.id=c.id_etapa","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");

        
        // $this->db->where(array('c.activo' => 'S'));
		$this->db->where(array('c.activo' => 'S'));
        $this->db->where(array('man.id !=' => 6));
		if (count($where)>0){
			$this->db->where($where);
		}  
		if ($group_by!=''){
			$this->db->group_by($group_by);
		} else {
			$this->db->group_by('c.id');
		}
		if ($order_by!=''){
			$this->db->group_by($order_by);
		}
		
		$query = $this->db->get();
		$result = $query->result();
		
		return $result;
		
	}
	
	
	public function get_cuenta_exportar_fullpay($where=array(),$like=array(),$where_str=''){
		
		$cols = array();
		
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'est_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS nombre';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce.fecha_crea AS fecha_crea_cuentas_etapas';
		$cols[] = 'ce.id_etapa AS id_etapa';
		$cols[] = 'ce.id AS id_2_cuenta_etapa';
		$cols[] = 's_cuenta.estado AS estado';
		
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        //$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        //$this->db->join("s_tribunales tri","tri.id=tr.padre","left");
		$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		
       // " JOIN `2_cuentas_etapas` ce ON ce2.id_cuenta = ce.id_cuenta /* AND ce2.last_fecha = ce.fecha_etapa ".*/
        $this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
        
		$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        //$this->db->join("s_comunas com","com.id=usr.id_comuna","left");
        $this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("s_etapas etap","etap.id=ce.id_etapa","left");
        
        if (count($where)>0){
         $this->db->where($where);
		} 
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}
        //$this->db->limit('30');
        //$this->db->group_by('dir.id');
        $this->db->order_by("usr.nombres", "DESC");
		$this->db->group_by('ce.id');
		
        $query = $this->db->get();
		return  $query->result();
	    
	}
	
	
	public function get_cuenta_exportar_fullpay_group($where=array(),$like=array(),$where_str=''){
		
	
		$cols = array();
		//$cols[] = 'c.id AS id';
		$cols[] = 'pa.n_pagare AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.medio_contacto_otro AS medio_contacto_otro';
		$cols[] = 'c.medio_contacto AS medio_contacto';
		$cols[] = 'c.medio_informado_otro AS medio_informado_otro';
		$cols[] = 'c.medio_informado AS medio_informado';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		//$cols[] = 'DATEDIFF(NOW(),ce.fecha_etapa) as ultimo_dia';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'est_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS nombre';
		//$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		//$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce.fecha_crea AS fecha_crea_cuentas_etapas';
		$cols[] = 'ce.id_cuenta AS id_cuenta';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		//$cols[] = 'ce.id AS id';
		$cols[] = 'trib.tribunal as tribunal_padre_comuna';
		$cols[] = 'trie.tribunal as tribunalE';
		$cols[] = 'diste.tribunal as DistritoE';
		
		$cols[] = 'c.rolE as rolE';
		
		/*
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		$this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        $this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("s_tribunales trie", "trie.id = c.id_tribunal_ex","left");
		$this->db->join("s_tribunales diste", "diste.id = c.id_distrito_ex","left");
		$this->db->join("pagare pa", "pa.idcuenta = c.id","left");
		$this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("s_etapas etap","etap.id=c.id_etapa","left");
        */
	  //////############ CONSULTA PARA EXPORTAR EXCEL CUENTAS#######///////
        /*
		cta.id AS id,
		usr.id_comuna AS id_comuna,
		cta.posicion AS posicion,
		cta.publico AS publico,
		cta.activo AS activo,
		cta.id_procurador,
		mand.razon_social,
		mand.clase_html AS clase_html,
		tip.tipo AS tipo_producto,
		cta.monto_demandado AS monto_demandado,
		cta.id_estado_cuenta AS id_estado_cuenta,
		cta.id_castigo AS id_castigo,
		2cc.numero_contrato AS numero_contrato,
		cta.id_mandante AS field_categoria,
		cta.monto_deuda AS monto_deuda,  
		cta.monto_pagado_new AS total_pagado, cta.n_pagare AS pagare,
		cta.monto_deuda_new AS monto_deuda_new, DATEDIFF( NOW(),cta.fecha_asignacion ) AS diferencia
		etap.dias_alerta AS dias_alerta,
		etap.fecha_crea AS fecha_crea_etapa, 
		etap.id AS id_etapa,
		pa.n_pagare as npage,
		cta.id_tribunal AS id_tribunal,
		trib_com.tribunal as tribunal_padre_comuna,
	    tribu.tribunal AS tribunal_padre, 
		

		   
		
        */

        ###################################################
        ########### QUERY EXPORTAR EXCEL ##################
        ###################################################

		$this->db->select(
			'
			tri.alias AS trib_ju_C,
			trie.alias AS trib_ju_E,
			tri.alias3 AS tribunal,
			ju.jurisdiccion AS jurisdiccion,
			trie.alias3 AS tribunalE,
			jue.jurisdiccion AS jurisdiccionE,
			comu.nombre AS nombre_comuna,
			cta.rol AS rol,
		    mand.codigo_mandante AS codigo_mandante,	
			usr.nombres AS nombres,
			usr.ap_pat AS ap_pat,
			usr.ap_mat AS ap_mat, 
			usr.rut AS rut, 
			cta.fecha_asignacion AS fecha_asignacion,
			cta.exorto,			
			cta.rolE,
			a.anio AS anio,
			tp.tipo AS letraC,
			tpp.tipo AS letraE,
			an.anio AS anioE,
			operacion,
			etap.etapa AS etapa, 
			MAX(2ce.fecha_crea) AS fecha_crea_x, 
			coditau.cod AS coditau,
			coditau.glosa AS glosa, 	
			secta.estado AS estado,
			me.marca AS marca,	 
			2ce.observaciones,
			cta.fecha_etapa AS fecha_etapa, 
			admin.username AS procurador,
			MAX(2ce.observaciones) AS observaciones,
			MAX(comu.nombre) AS nombre_comuna
			');
			//,cta.id_tribunal AS id_tribunal, pa.n_pagare as npage, operacion, etap.etapa AS etapa, etap.id AS id_etapa, etap.fecha_crea AS fecha_crea_etapa, etap.dias_alerta AS dias_alerta');
			$this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
			$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
			$this->db->join("s_tribunales tri", "tri.id = cta.id_tribunal","left");
			$this->db->join("jurisdiccion ju", "ju.id = cta.id_distrito","left");
			$this->db->join("s_tribunales trie", "trie.id = cta.id_tribunal_ex","left");
			$this->db->join("jurisdiccion jue", "jue.id = cta.id_distrito_ex","left");


			$this->db->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left");
			$this->db->join("s_comunas comu", "comu.id = 2cd.id_comuna","left");
			//$this->db->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left");
			//$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left");
			//$this->db->join("pagare pa", "pa.idcuenta = cta.id","left");
			//$this->db->join("2_cuentas_bienes 2cb", "2cb.id_cuenta = cta.id","left");
			//$this->db->join("2_cuentas_contratos 2cc", "2cc.id_cuenta = cta.id","left");
			$this->db->join("s_etapas etap","etap.id=cta.id_etapa","left");
			$this->db->join("cod_itau coditau","coditau.id=etap.id_cod_itau","left");
			$this->db->join("s_estado_cuenta secta","secta.id=cta.id_estado_cuenta","left");
			$this->db->join("0_administradores admin","admin.id=cta.id_procurador","left");
			$this->db->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id","left");
			$this->db->join("tipo_causa tp","tp.id=cta.letraC","left");
			$this->db->join("anio a","a.id=cta.anio","left");
			$this->db->join("tipo_causa tpp","tpp.id=cta.letraE","left");
			$this->db->join("anio an","an.id=cta.anioE","left");
			$this->db->join("marcas_especiales me","me.idMarca=cta.id_marcas_especiales","left");


			
			//$this->db->where($where);
		
        if (count($where)>0){
         $this->db->where($where);
		 
		} 
		$this->db->where('usr.rut !=','');
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}
        //$this->db->limit('30');
        //$this->db->group_by('dir.id');
       // $this->db->order_by("2ce.fecha_crea", "DESC");
		$this->db->where(array('cta.activo' => 'S'));
		
		/*
		$this->db->where("(
		(2ce.id_cuenta,2ce.fecha_etapa) IN 
		( SELECT id_cuenta, MAX(fecha_etapa)
  		FROM 2_cuentas_etapas 2ce2
  		WHERE
  		activo = 'S'
		GROUP BY id_cuenta
						
						))");
		
		*/
		
        $this->db->group_by('cta.id');
       // $this->db->order_by('fecha_crea_x DESC');
        
		$query = $this->db->get('0_cuentas cta');
        //$query = $this->db->get();
		return  $query->result();
	    
	}
	
	

	#######################################################################################
	###########################EXPORT EXCEL INFORME CIERRE ################################
	#######################################################################################

	public function get_cuenta_exportar_informe_cierre($where=array(),$like=array(),$where_str=''){
		
	
		$cols = array();
		//$cols[] = 'c.id AS id';
		$cols[] = 'pa.n_pagare AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.medio_contacto_otro AS medio_contacto_otro';
		$cols[] = 'c.medio_contacto AS medio_contacto';
		$cols[] = 'c.medio_informado_otro AS medio_informado_otro';
		$cols[] = 'c.medio_informado AS medio_informado';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		//$cols[] = 'DATEDIFF(NOW(),ce.fecha_etapa) as ultimo_dia';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'est_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS nombre';
		//$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		//$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce.fecha_crea AS fecha_crea_cuentas_etapas';
		$cols[] = 'ce.id_cuenta AS id_cuenta';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		//$cols[] = 'ce.id AS id';
		$cols[] = 'trib.tribunal as tribunal_padre_comuna';
		$cols[] = 'trie.tribunal as tribunalE';
		$cols[] = 'diste.tribunal as DistritoE';
		
		$cols[] = 'c.rolE as rolE';
		
		/*
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		$this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        $this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("s_tribunales trie", "trie.id = c.id_tribunal_ex","left");
		$this->db->join("s_tribunales diste", "diste.id = c.id_distrito_ex","left");
		$this->db->join("pagare pa", "pa.idcuenta = c.id","left");
		$this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("s_etapas etap","etap.id=c.id_etapa","left");
        */
	  //////############ CONSULTA PARA EXPORTAR EXCEL CUENTAS#######///////
        /*
		cta.id AS id,
		usr.id_comuna AS id_comuna,
		cta.posicion AS posicion,
		cta.publico AS publico,
		cta.activo AS activo,
		cta.id_procurador,
		mand.razon_social,
		mand.clase_html AS clase_html,
		tip.tipo AS tipo_producto,
		cta.monto_demandado AS monto_demandado,
		cta.id_estado_cuenta AS id_estado_cuenta,
		cta.id_castigo AS id_castigo,
		2cc.numero_contrato AS numero_contrato,
		cta.id_mandante AS field_categoria,
		cta.monto_deuda AS monto_deuda,  
		cta.monto_pagado_new AS total_pagado, cta.n_pagare AS pagare,
		cta.monto_deuda_new AS monto_deuda_new, DATEDIFF( NOW(),cta.fecha_asignacion ) AS diferencia
		etap.dias_alerta AS dias_alerta,
		etap.fecha_crea AS fecha_crea_etapa, 
		etap.id AS id_etapa,
		pa.n_pagare as npage,
		cta.id_tribunal AS id_tribunal,
		trib_com.tribunal as tribunal_padre_comuna,
	    tribu.tribunal AS tribunal_padre, 
		

		   
		
        */

        ###################################################
        ########### QUERY EXPORTAR EXCEL ##################
        ###################################################

		$this->db->select(
			'
			tri.alias AS trib_ju_C,
			trie.alias AS trib_ju_E,
			tri.alias3 AS tribunal,
			ju.jurisdiccion AS jurisdiccion,
			trie.alias3 AS tribunalE,
			jue.jurisdiccion AS jurisdiccionE,
			comu.nombre AS nombre_comuna,
			cta.rol AS rol,
		    mand.codigo_mandante AS codigo_mandante,	
			usr.nombres AS nombres,
			usr.ap_pat AS ap_pat,
			usr.ap_mat AS ap_mat, 
			usr.rut AS rut, 
			cta.fecha_asignacion AS fecha_asignacion,
			cta.exorto,			
			cta.rolE,
			a.anio AS anio,
			tp.tipo AS letraC,
			tpp.tipo AS letraE,
			an.anio AS anioE,
			operacion,
			etap.etapa AS etapa, 
			MAX(2ce.fecha_crea) AS fecha_crea, 
			coditau.cod AS coditau,
			coditau.glosa AS glosa, 	
			secta.estado AS estado,
			me.marca AS marca,	 
			cta.fecha_etapa AS fecha_etapa, 
			admin.username AS procurador,
			2ce.observaciones AS observaciones,
			NOW() AS fecha_actual,
			MAX(comu.nombre) AS nombre_comuna
			');
			//,cta.id_tribunal AS id_tribunal, pa.n_pagare as npage, operacion, etap.etapa AS etapa, etap.id AS id_etapa, etap.fecha_crea AS fecha_crea_etapa, etap.dias_alerta AS dias_alerta');
			$this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
			$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
			$this->db->join("s_tribunales tri", "tri.id = cta.id_tribunal","left");
			$this->db->join("jurisdiccion ju", "ju.id = cta.id_distrito","left");
			$this->db->join("s_tribunales trie", "trie.id = cta.id_tribunal_ex","left");
			$this->db->join("jurisdiccion jue", "jue.id = cta.id_distrito_ex","left");


			$this->db->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left");
			$this->db->join("s_comunas comu", "comu.id = 2cd.id_comuna","left");
			//$this->db->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left");
			//$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left");
			//$this->db->join("pagare pa", "pa.idcuenta = cta.id","left");
			//$this->db->join("2_cuentas_bienes 2cb", "2cb.id_cuenta = cta.id","left");
			//$this->db->join("2_cuentas_contratos 2cc", "2cc.id_cuenta = cta.id","left");
			$this->db->join("s_etapas etap","etap.id=cta.id_etapa","left");
			$this->db->join("cod_itau coditau","coditau.id=etap.id_cod_itau","left");
			$this->db->join("s_estado_cuenta secta","secta.id=cta.id_estado_cuenta","left");
			$this->db->join("0_administradores admin","admin.id=cta.id_procurador","left");
			$this->db->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id","left");
			$this->db->join("tipo_causa tp","tp.id=cta.letraC","left");
			$this->db->join("anio a","a.id=cta.anio","left");
			$this->db->join("tipo_causa tpp","tpp.id=cta.letraE","left");
			$this->db->join("anio an","an.id=cta.anioE","left");
			$this->db->join("marcas_especiales me","me.idMarca=cta.id_marcas_especiales","left");


			
			//$this->db->where($where);
		
        if (count($where)>0){
         $this->db->where($where);
		 
		} 
		$this->db->where('usr.rut !=','');
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}
        
		$this->db->where(array('cta.activo' => 'S'));
		$this->db->where("(DATE(2ce.fecha_crea) = CURDATE())");
		$this->db->where("(etap.id != '121' AND etap.id != '137' AND etap.id != '144' AND etap.id != '148' AND etap.id != '154' AND etap.id != '111' AND etap.id != '106' AND etap.id != '84' AND etap.id != '86' AND etap.id != '147' 
			AND etap.id != '112' AND etap.id != '113' AND etap.id != '114' AND etap.id != '120' AND etap.id != '153' AND etap.id != '1')");
		//$this->db->where("(2ce.id_etapa IN (SELECT id_etapa FROM 2_cuentas_etapas 2ce WHERE id_etapa = 118))");
		$this->db->where("(cta.id_estado_cuenta='1' OR id_estado_cuenta='6' OR id_estado_cuenta='10' OR id_estado_cuenta='2' OR id_estado_cuenta='3')");
		//$this->db->where("(etap.id = '156')");
		$this->db->where("(cta.id_mandante='2' OR cta.id_mandante='12')");
		$this->db->where("(
		(2ce.id_cuenta,2ce.fecha_etapa) IN 
		( SELECT id_cuenta, MAX(fecha_etapa)
  		FROM 2_cuentas_etapas 2ce2
  		WHERE
  		activo = 'S'
		GROUP BY id_cuenta
						
						))");
		
		
		
		//$this->db->order_by('coditau.cod','asc');
        $this->db->group_by('cta.id');
      
        
		$query = $this->db->get('0_cuentas cta');
        //$query = $this->db->get();
		return  $query->result();
	    
	}
	



	#######################################################################################
	############################# FIN EXPORT EXCEL INFORME CIERRE ##########################
	#######################################################################################

	
	
	
	
	#######################################################################################
	###########################EXPORT EXCEL INFORME INGRESOS ################################
	#######################################################################################

	public function get_cuenta_exportar_informe_ingresos($where=array(),$like=array(),$where_str=''){
		
	
		$cols = array();
		//$cols[] = 'c.id AS id';
		$cols[] = 'pa.n_pagare AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.medio_contacto_otro AS medio_contacto_otro';
		$cols[] = 'c.medio_contacto AS medio_contacto';
		$cols[] = 'c.medio_informado_otro AS medio_informado_otro';
		$cols[] = 'c.medio_informado AS medio_informado';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		//$cols[] = 'DATEDIFF(NOW(),ce.fecha_etapa) as ultimo_dia';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'est_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS nombre';
		//$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		//$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce.fecha_crea AS fecha_crea_cuentas_etapas';
		$cols[] = 'ce.id_cuenta AS id_cuenta';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		//$cols[] = 'ce.id AS id';
		$cols[] = 'trib.tribunal as tribunal_padre_comuna';
		$cols[] = 'trie.tribunal as tribunalE';
		$cols[] = 'diste.tribunal as DistritoE';
		
		$cols[] = 'c.rolE as rolE';
		
		/*
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		$this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        $this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("s_tribunales trie", "trie.id = c.id_tribunal_ex","left");
		$this->db->join("s_tribunales diste", "diste.id = c.id_distrito_ex","left");
		$this->db->join("pagare pa", "pa.idcuenta = c.id","left");
		$this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("s_etapas etap","etap.id=c.id_etapa","left");
        */
	  //////############ CONSULTA PARA EXPORTAR EXCEL CUENTAS#######///////
        /*
		cta.id AS id,
		usr.id_comuna AS id_comuna,
		cta.posicion AS posicion,
		cta.publico AS publico,
		cta.activo AS activo,
		cta.id_procurador,
		mand.razon_social,
		mand.clase_html AS clase_html,
		tip.tipo AS tipo_producto,
		cta.monto_demandado AS monto_demandado,
		cta.id_estado_cuenta AS id_estado_cuenta,
		cta.id_castigo AS id_castigo,
		2cc.numero_contrato AS numero_contrato,
		cta.id_mandante AS field_categoria,
		cta.monto_deuda AS monto_deuda,  
		cta.monto_pagado_new AS total_pagado, cta.n_pagare AS pagare,
		cta.monto_deuda_new AS monto_deuda_new, DATEDIFF( NOW(),cta.fecha_asignacion ) AS diferencia
		etap.dias_alerta AS dias_alerta,
		etap.fecha_crea AS fecha_crea_etapa, 
		etap.id AS id_etapa,
		pa.n_pagare as npage,
		cta.id_tribunal AS id_tribunal,
		trib_com.tribunal as tribunal_padre_comuna,
	    tribu.tribunal AS tribunal_padre, 
		

		   
		
        */

        ###################################################
        ########### QUERY EXPORTAR EXCEL INFORME INGRESOS ##################
        ###################################################

		$this->db->select(
			'
			tri.alias AS trib_ju_C,
			trie.alias AS trib_ju_E,
			tri.alias3 AS tribunal,
			ju.jurisdiccion AS jurisdiccion,
			trie.alias3 AS tribunalE,
			jue.jurisdiccion AS jurisdiccionE,
			comu.nombre AS nombre_comuna,
			cta.rol AS rol,
		    mand.codigo_mandante AS codigo_mandante,	
			usr.nombres AS nombres,
			usr.ap_pat AS ap_pat,
			usr.ap_mat AS ap_mat, 
			usr.rut AS rut, 
			cta.fecha_asignacion AS fecha_asignacion,
			cta.exorto,			
			cta.rolE,
			a.anio AS anio,
			tp.tipo AS letraC,
			tpp.tipo AS letraE,
			an.anio AS anioE,
			operacion,
			etap.etapa AS etapa, 
			MAX(2ce.fecha_crea) AS fecha_crea, 
			coditau.cod AS coditau,
			coditau.glosa AS glosa, 	
			secta.estado AS estado,
			me.marca AS marca,	 
			cta.fecha_etapa AS fecha_etapa, 
			admin.username AS procurador,
			2ce.observaciones AS observaciones,
			NOW() AS fecha_actual,
			MAX(comu.nombre) AS nombre_comuna
			');
			//,cta.id_tribunal AS id_tribunal, pa.n_pagare as npage, operacion, etap.etapa AS etapa, etap.id AS id_etapa, etap.fecha_crea AS fecha_crea_etapa, etap.dias_alerta AS dias_alerta');
			$this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
			$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
			$this->db->join("s_tribunales tri", "tri.id = cta.id_tribunal","left");
			$this->db->join("jurisdiccion ju", "ju.id = cta.id_distrito","left");
			$this->db->join("s_tribunales trie", "trie.id = cta.id_tribunal_ex","left");
			$this->db->join("jurisdiccion jue", "jue.id = cta.id_distrito_ex","left");


			$this->db->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left");
			$this->db->join("s_comunas comu", "comu.id = 2cd.id_comuna","left");
			//$this->db->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left");
			//$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left");
			//$this->db->join("pagare pa", "pa.idcuenta = cta.id","left");
			//$this->db->join("2_cuentas_bienes 2cb", "2cb.id_cuenta = cta.id","left");
			//$this->db->join("2_cuentas_contratos 2cc", "2cc.id_cuenta = cta.id","left");
			$this->db->join("s_etapas etap","etap.id=cta.id_etapa","left");
			$this->db->join("cod_itau coditau","coditau.id=etap.id_cod_itau","left");
			$this->db->join("s_estado_cuenta secta","secta.id=cta.id_estado_cuenta","left");
			$this->db->join("0_administradores admin","admin.id=cta.id_procurador","left");
			$this->db->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id","left");
			$this->db->join("tipo_causa tp","tp.id=cta.letraC","left");
			$this->db->join("anio a","a.id=cta.anio","left");
			$this->db->join("tipo_causa tpp","tpp.id=cta.letraE","left");
			$this->db->join("anio an","an.id=cta.anioE","left");
			$this->db->join("marcas_especiales me","me.idMarca=cta.id_marcas_especiales","left");


			
			//$this->db->where($where);
		
        if (count($where)>0){
         $this->db->where($where);
		 
		} 
		$this->db->where('usr.rut !=','');
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}
        $this->db->where(array('cta.activo' => 'S'));
		$this->db->where("(DATE(2ce.fecha_crea) = CURDATE() - INTERVAL 1 DAY)");
		//$this->db->where("(DATE(2ce.fecha_crea) = '2020-04-17')");
		$this->db->where("(etap.id = '84' OR etap.id = '147' )");
		$this->db->where("(cta.id_estado_cuenta='1' OR id_estado_cuenta='9')");
		$this->db->where("(cta.id_mandante='2' OR cta.id_mandante='12')");
		
		/*
		$this->db->where("(
		(2ce.id_cuenta,2ce.fecha_etapa) IN 
		( SELECT id_cuenta, MAX(fecha_etapa)
  		FROM 2_cuentas_etapas 2ce2
  		WHERE
  		activo = 'S'
		GROUP BY id_cuenta
						
						))");

*/

		
		
		//$this->db->order_by('coditau.cod','asc');
        $this->db->group_by('cta.id');
      
        
		$query = $this->db->get('0_cuentas cta');
        //$query = $this->db->get();
		return  $query->result();
	    
	}
	



	#######################################################################################
	############################# FIN EXPORT EXCEL INFORME INGRESOS #######################
	#######################################################################################

	
	
	
	


	#######################################################################################
	###########################EXPORT EXCEL INFORME ROLES ################################
	#######################################################################################

	public function get_cuenta_exportar_informe_roles($where=array(),$like=array(),$where_str=''){
		
	
		$cols = array();
		//$cols[] = 'c.id AS id';
		$cols[] = 'pa.n_pagare AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.medio_contacto_otro AS medio_contacto_otro';
		$cols[] = 'c.medio_contacto AS medio_contacto';
		$cols[] = 'c.medio_informado_otro AS medio_informado_otro';
		$cols[] = 'c.medio_informado AS medio_informado';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		//$cols[] = 'DATEDIFF(NOW(),ce.fecha_etapa) as ultimo_dia';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'est_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS nombre';
		//$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		//$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce.fecha_crea AS fecha_crea_cuentas_etapas';
		$cols[] = 'ce.id_cuenta AS id_cuenta';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		//$cols[] = 'ce.id AS id';
		$cols[] = 'trib.tribunal as tribunal_padre_comuna';
		$cols[] = 'trie.tribunal as tribunalE';
		$cols[] = 'diste.tribunal as DistritoE';
		
		$cols[] = 'c.rolE as rolE';
		
		/*
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		$this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        $this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("s_tribunales trie", "trie.id = c.id_tribunal_ex","left");
		$this->db->join("s_tribunales diste", "diste.id = c.id_distrito_ex","left");
		$this->db->join("pagare pa", "pa.idcuenta = c.id","left");
		$this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("s_etapas etap","etap.id=c.id_etapa","left");
        */
	  //////############ CONSULTA PARA EXPORTAR EXCEL CUENTAS#######///////
        /*
		cta.id AS id,
		usr.id_comuna AS id_comuna,
		cta.posicion AS posicion,
		cta.publico AS publico,
		cta.activo AS activo,
		cta.id_procurador,
		mand.razon_social,
		mand.clase_html AS clase_html,
		tip.tipo AS tipo_producto,
		cta.monto_demandado AS monto_demandado,
		cta.id_estado_cuenta AS id_estado_cuenta,
		cta.id_castigo AS id_castigo,
		2cc.numero_contrato AS numero_contrato,
		cta.id_mandante AS field_categoria,
		cta.monto_deuda AS monto_deuda,  
		cta.monto_pagado_new AS total_pagado, cta.n_pagare AS pagare,
		cta.monto_deuda_new AS monto_deuda_new, DATEDIFF( NOW(),cta.fecha_asignacion ) AS diferencia
		etap.dias_alerta AS dias_alerta,
		etap.fecha_crea AS fecha_crea_etapa, 
		etap.id AS id_etapa,
		pa.n_pagare as npage,
		cta.id_tribunal AS id_tribunal,
		trib_com.tribunal as tribunal_padre_comuna,
	    tribu.tribunal AS tribunal_padre, 
		

		   
		
        */

        ###################################################
        ########### QUERY EXPORTAR EXCEL INFORME ROLES ##################
        ###################################################

		$this->db->select(
			'
			tri.alias AS trib_ju_C,
			trie.alias AS trib_ju_E,
			tri.alias3 AS tribunal,
			ju.jurisdiccion AS jurisdiccion,
			trie.alias3 AS tribunalE,
			jue.jurisdiccion AS jurisdiccionE,
			comu.nombre AS nombre_comuna,
			cta.rol AS rol,
		    mand.codigo_mandante AS codigo_mandante,	
			usr.nombres AS nombres,
			usr.ap_pat AS ap_pat,
			usr.ap_mat AS ap_mat, 
			usr.rut AS rut, 
			cta.fecha_asignacion AS fecha_asignacion,
			cta.exorto,			
			cta.rolE,
			a.anio AS anio,
			tp.tipo AS letraC,
			tpp.tipo AS letraE,
			an.anio AS anioE,
			operacion,
			etap.etapa AS etapa, 
			MAX(2ce.fecha_crea) AS fecha_crea, 
			coditau.cod AS coditau,
			coditau.glosa AS glosa, 	
			secta.estado AS estado,
			me.marca AS marca,	 
			cta.fecha_etapa AS fecha_etapa, 
			admin.username AS procurador,
			2ce.observaciones AS observaciones,
			NOW() AS fecha_actual,
			MAX(comu.nombre) AS nombre_comuna
			');
			//,cta.id_tribunal AS id_tribunal, pa.n_pagare as npage, operacion, etap.etapa AS etapa, etap.id AS id_etapa, etap.fecha_crea AS fecha_crea_etapa, etap.dias_alerta AS dias_alerta');
			$this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
			$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
			$this->db->join("s_tribunales tri", "tri.id = cta.id_tribunal","left");
			$this->db->join("jurisdiccion ju", "ju.id = cta.id_distrito","left");
			$this->db->join("s_tribunales trie", "trie.id = cta.id_tribunal_ex","left");
			$this->db->join("jurisdiccion jue", "jue.id = cta.id_distrito_ex","left");


			$this->db->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left");
			$this->db->join("s_comunas comu", "comu.id = 2cd.id_comuna","left");
			//$this->db->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left");
			//$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left");
			//$this->db->join("pagare pa", "pa.idcuenta = cta.id","left");
			//$this->db->join("2_cuentas_bienes 2cb", "2cb.id_cuenta = cta.id","left");
			//$this->db->join("2_cuentas_contratos 2cc", "2cc.id_cuenta = cta.id","left");
			$this->db->join("s_etapas etap","etap.id=cta.id_etapa","left");
			$this->db->join("cod_itau coditau","coditau.id=etap.id_cod_itau","left");
			$this->db->join("s_estado_cuenta secta","secta.id=cta.id_estado_cuenta","left");
			$this->db->join("0_administradores admin","admin.id=cta.id_procurador","left");
			$this->db->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id","left");
			$this->db->join("tipo_causa tp","tp.id=cta.letraC","left");
			$this->db->join("anio a","a.id=cta.anio","left");
			$this->db->join("tipo_causa tpp","tpp.id=cta.letraE","left");
			$this->db->join("anio an","an.id=cta.anioE","left");
			$this->db->join("marcas_especiales me","me.idMarca=cta.id_marcas_especiales","left");


			
			//$this->db->where($where);
		
        if (count($where)>0){
         $this->db->where($where);
		 
		} 
		$this->db->where('usr.rut !=','');
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}
       
	    $this->db->where(array('cta.activo' => 'S'));
		$this->db->where("(DATE(2ce.fecha_crea) = CURDATE() - INTERVAL 1 DAY)");
		//$this->db->where("(DATE(2ce.fecha_crea) = '2020-04-24')");
		$this->db->where("(etap.id = '84' OR etap.id = '147' )");
		$this->db->where("(cta.id_estado_cuenta='1' OR id_estado_cuenta='9')");
		$this->db->where("(cta.id_mandante='2' OR cta.id_mandante='12')");
		
		/*
		$this->db->where("(
		(2ce.id_cuenta,2ce.fecha_etapa) IN 
		( SELECT id_cuenta, MAX(fecha_etapa)
  		FROM 2_cuentas_etapas 2ce2
  		WHERE
  		activo = 'S'
		GROUP BY id_cuenta
						
						))");

*/
		
		
		//$this->db->order_by('coditau.cod','asc');
        $this->db->group_by('cta.id');
      
        
		$query = $this->db->get('0_cuentas cta');
        //$query = $this->db->get();
		return  $query->result();
	    
	}
	



	#######################################################################################
	############################# FIN EXPORT EXCEL INFORME ROLES #######################
	#######################################################################################
	
	
	
	
	#######################################################################################
	###########################EXPORT EXCEL INFORME MORA ################################
	#######################################################################################

	public function get_cuenta_exportar_informe_mora($where=array(),$like=array(),$where_str=''){
		
	
		$cols = array();
		//$cols[] = 'c.id AS id';
		$cols[] = 'pa.n_pagare AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.medio_contacto_otro AS medio_contacto_otro';
		$cols[] = 'c.medio_contacto AS medio_contacto';
		$cols[] = 'c.medio_informado_otro AS medio_informado_otro';
		$cols[] = 'c.medio_informado AS medio_informado';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		//$cols[] = 'DATEDIFF(NOW(),ce.fecha_etapa) as ultimo_dia';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'est_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS nombre';
		//$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		//$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce.fecha_crea AS fecha_crea_cuentas_etapas';
		$cols[] = 'ce.id_cuenta AS id_cuenta';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		//$cols[] = 'ce.id AS id';
		$cols[] = 'trib.tribunal as tribunal_padre_comuna';
		$cols[] = 'trie.tribunal as tribunalE';
		$cols[] = 'diste.tribunal as DistritoE';
		
		$cols[] = 'c.rolE as rolE';
		
		/*
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		$this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        $this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("s_tribunales trie", "trie.id = c.id_tribunal_ex","left");
		$this->db->join("s_tribunales diste", "diste.id = c.id_distrito_ex","left");
		$this->db->join("pagare pa", "pa.idcuenta = c.id","left");
		$this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("s_etapas etap","etap.id=c.id_etapa","left");
        */
	  //////############ CONSULTA PARA EXPORTAR EXCEL CUENTAS#######///////
        /*
		cta.id AS id,
		usr.id_comuna AS id_comuna,
		cta.posicion AS posicion,
		cta.publico AS publico,
		cta.activo AS activo,
		cta.id_procurador,
		mand.razon_social,
		mand.clase_html AS clase_html,
		tip.tipo AS tipo_producto,
		cta.monto_demandado AS monto_demandado,
		cta.id_estado_cuenta AS id_estado_cuenta,
		cta.id_castigo AS id_castigo,
		2cc.numero_contrato AS numero_contrato,
		cta.id_mandante AS field_categoria,
		cta.monto_deuda AS monto_deuda,  
		cta.monto_pagado_new AS total_pagado, cta.n_pagare AS pagare,
		cta.monto_deuda_new AS monto_deuda_new, DATEDIFF( NOW(),cta.fecha_asignacion ) AS diferencia
		etap.dias_alerta AS dias_alerta,
		etap.fecha_crea AS fecha_crea_etapa, 
		etap.id AS id_etapa,
		pa.n_pagare as npage,
		cta.id_tribunal AS id_tribunal,
		trib_com.tribunal as tribunal_padre_comuna,
	    tribu.tribunal AS tribunal_padre, 
		

		   
		
        */

        ###################################################
        ########### QUERY EXPORTAR EXCEL INFORME MORA ##################
        ###################################################

		$this->db->select(
			'
			tri.alias AS trib_ju_C,
			trie.alias AS trib_ju_E,
			tri.alias3 AS tribunal,
			ju.jurisdiccion AS jurisdiccion,
			trie.alias3 AS tribunalE,
			jue.jurisdiccion AS jurisdiccionE,
			comu.nombre AS nombre_comuna,
			cta.rol AS rol,
		    mand.codigo_mandante AS codigo_mandante,	
			usr.nombres AS nombres,
			usr.ap_pat AS ap_pat,
			usr.ap_mat AS ap_mat, 
			usr.rut AS rut, 
			cta.fecha_asignacion AS fecha_asignacion,
			cta.exorto,			
			cta.rolE,
			a.anio AS anio,
			tp.tipo AS letraC,
			tpp.tipo AS letraE,
			an.anio AS anioE,
			operacion,
			etap.etapa AS etapa, 
			MAX(2ce.fecha_crea) AS fecha_crea, 
			coditau.cod AS coditau,
			coditau.glosa AS glosa, 	
			secta.estado AS estado,
			me.marca AS marca,	 
			cta.fecha_etapa AS fecha_etapa, 
			admin.username AS procurador,
			2ce.observaciones AS observaciones,
			NOW() AS fecha_actual,
			MAX(comu.nombre) AS nombre_comuna
			');
			//,cta.id_tribunal AS id_tribunal, pa.n_pagare as npage, operacion, etap.etapa AS etapa, etap.id AS id_etapa, etap.fecha_crea AS fecha_crea_etapa, etap.dias_alerta AS dias_alerta');
			$this->db->join("0_usuarios usr", "usr.id = cta.id_usuario");
			$this->db->join("0_mandantes mand", "mand.id = cta.id_mandante","left")	;
			$this->db->join("s_tribunales tri", "tri.id = cta.id_tribunal","left");
			$this->db->join("jurisdiccion ju", "ju.id = cta.id_distrito","left");
			$this->db->join("s_tribunales trie", "trie.id = cta.id_tribunal_ex","left");
			$this->db->join("jurisdiccion jue", "jue.id = cta.id_distrito_ex","left");


			$this->db->join("2_cuentas_direccion 2cd", "2cd.id_cuenta = cta.id","left");
			$this->db->join("s_comunas comu", "comu.id = 2cd.id_comuna","left");
			//$this->db->join("s_tribunales trib_com","trib_com.id=comu.id_tribunal_padre","left");
			//$this->db->join("s_tipo_productos tip", "tip.id = cta.id_tipo_producto","left");
			//$this->db->join("pagare pa", "pa.idcuenta = cta.id","left");
			//$this->db->join("2_cuentas_bienes 2cb", "2cb.id_cuenta = cta.id","left");
			//$this->db->join("2_cuentas_contratos 2cc", "2cc.id_cuenta = cta.id","left");
			$this->db->join("s_etapas etap","etap.id=cta.id_etapa","left");
			$this->db->join("cod_itau coditau","coditau.id=etap.id_cod_itau","left");
			$this->db->join("s_estado_cuenta secta","secta.id=cta.id_estado_cuenta","left");
			$this->db->join("0_administradores admin","admin.id=cta.id_procurador","left");
			$this->db->join("2_cuentas_etapas 2ce","2ce.id_cuenta=cta.id","left");
			$this->db->join("tipo_causa tp","tp.id=cta.letraC","left");
			$this->db->join("anio a","a.id=cta.anio","left");
			$this->db->join("tipo_causa tpp","tpp.id=cta.letraE","left");
			$this->db->join("anio an","an.id=cta.anioE","left");
			$this->db->join("marcas_especiales me","me.idMarca=cta.id_marcas_especiales","left");


			
			//$this->db->where($where);
		
        if (count($where)>0){
         $this->db->where($where);
		 
		} 
		$this->db->where('usr.rut !=','');
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}

        $this->db->where(array('cta.activo' => 'S'));
		//$this->db->where("(DATE(2ce.fecha_crea) = CURDATE())");
		//$this->db->where("(etap.id = '84' OR etap.id = '147' )");
		$this->db->where("(cta.id_estado_cuenta='1' OR id_estado_cuenta='6' OR id_estado_cuenta='3' OR id_estado_cuenta='10' OR id_estado_cuenta='7' OR id_estado_cuenta='2')");
		$this->db->where("(cta.id_mandante='2' OR cta.id_mandante='12')");
	
/*
		$this->db->where("(
		(2ce.id_cuenta,2ce.fecha_etapa) IN 
		( SELECT id_cuenta, MAX(fecha_etapa)
  		FROM 2_cuentas_etapas 2ce2
  		WHERE
  		activo = 'S'
		GROUP BY id_cuenta
						
						))");
*/
		
        $this->db->group_by('cta.id');
      	
        
		$query = $this->db->get('0_cuentas cta');
        //$query = $this->db->get();
		return  $query->result();
	    
	}
	



	#######################################################################################
	############################# FIN EXPORT EXCEL INFORME MORA #######################
	#######################################################################################

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function get_cuenta_etapas_exportar_fullpay_group($where=array(),$like=array(),$where_str=''){
		
		$cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'c.medio_contacto AS medio_contacto';
		$cols[] = 'c.medio_contacto_otro AS medio_contacto_otro';
		$cols[] = 'c.medio_informado AS medio_informado';
		$cols[] = 'c.medio_informado_otro AS medio_informado_otro';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'est_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS juzgado';
		
		//*****
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'c.fecha_crea AS fecha_crea_cuentas_etapas';
		//******
		
		//$cols[] = 'ce.id_etapa AS id_etapa';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'trib.tribunal as juzgado';
		
		$this->db->select($cols);
		
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("0_mandantes man","man.id=c.id_mandante","left");
		$this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
    	$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		$this->db->join("2_cuentas_etapas ce2" ,"ce2.id_cuenta = c.id","left");
        $this->db->join("s_etapas etap","etap.id=c.id_etapa","left");
        
		$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
		$this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
       
        
        if (count($where)>0){
         $this->db->where($where);
		} 
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}
		
		
        $this->db->order_by("c.fecha_etapa", "DESC");
		$this->db->group_by('c.id');
		
        $query = $this->db->get();
		return  $query->result();
	    
	}
	
	
	public function get_cuenta_etapas_exportar_fullpay($where=array(),$like=array(),$where_str=''){
		
		$cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'est_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS juzgado';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce2.fecha_crea AS fecha_crea_cuentas_etapas';
		$cols[] = 'ce2.id_etapa AS id_etapa';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'trib.tribunal as juzgado';
		
		$this->db->select($cols);
		
		$this->db->from("0_cuentas c");
		$this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("0_mandantes man","man.id=c.id_mandante","left");
		$this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
    	$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		$this->db->join("2_cuentas_etapas ce2" ,"ce2.id_cuenta = c.id","left");
        $this->db->join("s_etapas etap","etap.id=ce2.id_etapa","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
		$this->db->join("s_comunas com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
       
		/********
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
		****////////
		
        
        if (count($where)>0){
         $this->db->where($where);
		} 
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}
		
		
        $this->db->order_by("c.fecha_etapa", "DESC");
		//$this->db->group_by('c.fecha_etapa');
	    $query = $this->db->get();
		return  $query->result();
	    
	}
	
		public function get_cuenta_ex($where=array(),$like=array()){
		
		$cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		
		$cols[] = 'c.rol AS rol';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'trib.tribunal AS tribunal_padre';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales tri","tri.id=tr.padre","left");
		$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        $this->db->join("s_comunas com","com.id=usr.id_comuna","left");
        $this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
        
        if (count($where)>0){
         $this->db->where($where);
		} 
		if (count($like)>0){
         $this->db->like($like);
		} 
		
        //$this->db->limit('30');
        $this->db->group_by('dir.id');
        $this->db->group_by('c.id');
        
        $query = $this->db->get();
		return  $query->result();
	    
	}
	
	
	
	
	public function get_cuentas_alertas($where=array(),$like=array()){
		
		$cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		//$cols[] = 'c.id_distrito AS id_distrito';
		//$cols[] = 'c.receptor AS receptor';
		$cols[] = 'c.rol AS rol';
		//$cols[] = 'c.id_usuario AS id_usuario';
		//$cols[] = 'c.fecha_inicio AS fecha_inicio';
		//$cols[] = 'c.id_etapa AS id_etapa';
		//$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		//$cols[] = 'c.id_administrador AS id_administrador';
		
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		//$cols[] = 'man.razon_social AS razon_social';
		//$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		//$cols[] = 'adm.fecha_crea AS fecha_crea';
		//$cols[] = 'res.nombre AS nombre_receptor';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		
		//$cols[] = 's_cuenta.estado AS estado';
		//$cols[] = 'seta.etapa AS etapa';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		//$cols[] = 'com.nombre AS nombre_comuna_direccion';
		//$cols[] = 'cet.fecha_etapa AS fecha_etapa'; 
		//$cols[] = 'seta.dias_alerta AS dias_alerta';
		//$cols[] = 'DATEDIFF(NOW(),cet.fecha_etapa) as duracion';
		$cols[] = 'p.monto_deuda AS monto_deuda';
		$cols[] = 'p.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'p.fecha_crea AS fecha_crea';
		
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        //$this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        //$this->db->join("0_receptores res","res.id=c.receptor","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		//$this->db->join("s_tribunales dist","dist.id=c.id_distrito","left");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        $this->db->join("s_comunas com","com.id=usr.id_comuna","left");
        $this->db->join("pagare p","p.idcuenta=c.id","left");
        //$this->db->join("s_comunas com","com.id=dir.id_comuna","left");
        //$this->db->join("2_cuentas_etapas cet","cet.id_cuenta=c.id");
        //$this->db->join("s_etapas seta","seta.id=cet.id_etapa");
        
        if (count($where)>0){
         $this->db->where($where);
		} 
		if (count($like)>0){
         $this->db->like($like);
		} 
		
        //$this->db->limit('30');
        //$this->db->group_by('dir.id');
        $this->db->group_by('c.id');
        
        $query = $this->db->get();
		return  $query->result();
	    
	}
	
	
	public function get_cuenta_estado_excel_fullpay($where){
		
		//echo 'entro a la funcion';
        
		if(count($where>0)){$this->db->where($where);}
		
        $cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_usuario AS id_usuario';
		$cols[] = 'c.id_etapa AS id_etapa';
		$cols[] = 'c.tipo_demanda AS tipo_demanda';
		$cols[] = 'c.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'c.monto_deuda AS monto_deuda';
		$cols[] = 'c.fecha_etapa AS fecha_etapa';
		$cols[] = 'c.monto_pagado_new AS monto_pagado_new';
		$cols[] = 'c.monto_deuda_new AS monto_deuda_new';
		$cols[] = 'c.fecha_ultimo_pago AS fecha_ultimo_pago';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'c.fecha_estado_cuenta AS fecha_estado_cuenta';
		$cols[] = 'c.exorto AS exorto';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'man.razon_social AS razon_social';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'seta.etapa AS etapa';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 's_cuenta.estado AS estado';
		//$cols[] = 'p.fecha_vencimiento AS fecha_asignacion_pagare';
		//$cols[] = 'p.n_pagare AS n_pagare';
		
		//*******
		//$cols[] = 'pag.fecha_pago AS fecha_pago'; 
		
		$cols[] = 'seta.dias_alerta AS dias_alerta';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as duracion_etapa';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		//$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'seta.dias_alerta AS dias_alerta';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'juris.jurisdiccion AS jurisdiccion';
		$cols[] = 'res.nombre AS nombre_receptor';
		//$cols[] = 'cb.marca AS marca';
		//$cols[] = 'SUM(pag2.monto_remitido) AS monto_remitido';
		$this->db->select($cols);
		
		
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario AND usr.activo='S'");
        $this->db->join("0_mandantes man","man.id=c.id_mandante");
        $this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
       //$this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id AND ce.id_etapa=c.id_etapa AND ce.id = (SELECT id FROM 2_cuentas_etapas WHERE id_cuenta=c.id AND id_etapa=c.id_etapa ORDER BY fecha_etapa DESC LIMIT 0,1)","left");
        
        //**////
        //$this->db->join("s_etapas seta","seta.id=c.id_etapa","left");
        
       // $this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
        $this->db->join("s_etapas seta","seta.id=c.id_etapa","left");
        
        //$this->db->join("pagare p","p.idcuenta=c.id","left");
        
        //*********//
        //$this->db->join("2_cuentas_pagos pag", "pag.id_cuenta = c.id AND pag.activo='S' AND pag.id = (SELECT id FROM 2_cuentas_pagos psp WHERE psp.id_cuenta=c.id AND psp.activo='S' ORDER BY psp.fecha_pago DESC LIMIT 0,1)","left"); 
        
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
		$this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        $this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
        $this->db->join("s_jurisdiccion juris","juris.id=tr.id_jurisdiccion","left");
        $this->db->join("0_receptores res","res.id=c.receptor","left");
        //$this->db->join("2_cuentas_bienes cb","cb.id_cuenta=c.id","left");
        
        $this->db->where(array('c.activo' => 'S'));
        $this->db->order_by("c.fecha_asignacion", "DESC");
        $this->db->group_by('c.id');
        $query = $this->db->get();
		return  $query->result();
	}
	
	/*public function get_cuenta_bienes($where){
		
	if(count($where>0)){$this->db->where($where);}	
		
		$cols = array();
		$cols[] = 'cb.marca AS marca';
		
		$this->db->from("0_cuentas c");
		$this->db->join("2_cuentas_bienes cb","cb.id_cuenta=c.id","left");
		
		$this->db->where(array('c.activo' => 'S'));
        $this->db->order_by("c.fecha_asignacion", "DESC");
        $this->db->group_by('c.id');
        $query = $this->db->get();
		return  $query->result();
	}
	
	
	public function get_cuenta_pagares(){
		
		$cols = array();
		$cols[] = 'p.n_pagare AS n_pagare';
		$cols[] = 'p.fecha_vencimiento AS fecha_asignacion_pagare';
		
		$this->db->from("0_cuentas c");
		$this->db->join("pagare p","p.idcuenta=c.id","left");
		
		$this->db->where(array('c.activo' => 'S'));
        $this->db->order_by("c.fecha_asignacion", "DESC");
        $this->db->group_by('c.id');
        $query = $this->db->get();
		return  $query->result();
		} */
	
	public function get_procurador_cuenta_exportar_fullpay($where=array(),$like=array(),$where_str=''){
		
		$cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'est_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS nombre';
		$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce.fecha_crea AS fecha_crea_cuentas_etapas';
		$cols[] = 'ce.id_etapa AS id_etapa';
		$cols[] = 'ce.id AS id_2_cuenta_etapa';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'p.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'p.saldo_deuda AS saldo_deuda';
		$cols[] = 'p.monto_deuda AS monto_deuda';
		$cols[] = 'c.fecha_ultimo_pago AS fecha_ultimo_pago';
		
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        //$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        //$this->db->join("s_tribunales tri","tri.id=tr.padre","left");
		$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		
       // " JOIN `2_cuentas_etapas` ce ON ce2.id_cuenta = ce.id_cuenta /* AND ce2.last_fecha = ce.fecha_etapa ".*/
        $this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
        
		$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        //$this->db->join("s_comunas com","com.id=usr.id_comuna","left");
        $this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("s_etapas etap","etap.id=ce.id_etapa","left");
		$this->db->join("pagare p","p.idcuenta=c.id","left");
        
        if (count($where)>0){
         $this->db->where($where);
		} 
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}
        //$this->db->limit('30');
        //$this->db->group_by('dir.id');
        $this->db->order_by("usr.nombres", "DESC");
		$this->db->group_by('c.id');
		
        $query = $this->db->get();
		return  $query->result();
	    
	}
	
	
	public function get_procurador_cuenta_etapa_juicio_exportar_fullpay($where=array(),$like=array(),$where_str=''){
		
		$cols = array();
		$cols[] = 'c.id AS id';
		$cols[] = 'c.id_procurador AS id_procurador';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
		$cols[] = 'c.rol AS rol';
		$cols[] = 'c.id_castigo AS id_castigo';
		$cols[] = 'c.rol2 AS rol2';
		$cols[] = 'c.fecha_crea AS fecha_crea';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_asignacion) as duracion';
		$cols[] = 'DATEDIFF(NOW(),c.fecha_etapa) as ultimo_dia';
		$cols[] = 'usr.rut AS rut';
		$cols[] = 'usr.nombres AS nombres';
		$cols[] = 'usr.ap_pat AS ap_pat';
		$cols[] = 'usr.ap_mat AS ap_mat';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'dist.tribunal AS distrito';
		$cols[] = 'dir.direccion AS direccion';
		$cols[] = 'com.nombre AS nombre_comuna';
		$cols[] = 'man.codigo_mandante AS codigo_mandante';
		$cols[] = 'adm.nombres AS nombres_adm';
		$cols[] = 'adm.apellidos AS apellidos_adm';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'etap.etapa AS etapa';
		$cols[] = 'etap.id AS id_etapa';
		$cols[] = 'etap.fecha_crea AS fecha_crea_etapa';
		$cols[] = 'etap.dias_alerta AS dias_alerta';
		$cols[] = 'com.nombre AS nombre';
		$cols[] = 'ce.fecha_etapa AS fecha_etapa';
		$cols[] = 'ce.fecha_crea AS fecha_crea_cuentas_etapas';
		$cols[] = 'ce.id_etapa AS id_etapa';
		$cols[] = 'ce.id_cuenta AS id_cuenta';
		$cols[] = 'ce.id AS id_2_cuenta_etapa';
		$cols[] = 's_cuenta.estado AS estado';
		$cols[] = 'p.fecha_asignacion AS fecha_asignacion';
		$cols[] = 'p.saldo_deuda AS saldo_deuda';
		$cols[] = 'p.monto_deuda AS monto_deuda';
		$cols[] = 'c.fecha_ultimo_pago AS fecha_ultimo_pago';
		
		
		$this->db->select($cols);
		$this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        //$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        //$this->db->join("s_tribunales tri","tri.id=tr.padre","left");
		$this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
		
       // " JOIN `2_cuentas_etapas` ce ON ce2.id_cuenta = ce.id_cuenta /* AND ce2.last_fecha = ce.fecha_etapa ".*/
        $this->db->join("2_cuentas_etapas ce","ce.id_cuenta=c.id","left");
        
		$this->db->join("s_estado_cuenta s_cuenta","s_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("2_cuentas_direccion dir","dir.id_cuenta=c.id","left");
        //$this->db->join("s_comunas com","com.id=usr.id_comuna","left");
        $this->db->join("`s_comunas` com","com.id = dir.id_comuna","left");
		$this->db->join("s_tribunales trib","trib.id=com.id_tribunal_padre","left");
		$this->db->join("0_mandantes man","man.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("s_estado_cuenta est_cuenta","est_cuenta.id=c.id_estado_cuenta","left");
        $this->db->join("s_etapas etap","etap.id=ce.id_etapa","left");
		$this->db->join("pagare p","p.idcuenta=c.id","left");
        
        if (count($where)>0){
         $this->db->where($where);
		} 
		if (count($like)>0){
         $this->db->like($like);
		} 
		if ($where_str!=''){
			$this->db->where($where_str);
		}
        //$this->db->limit('30')get_cuentas_gastos;
        //$this->db->group_by('dir.id');
        $this->db->order_by("usr.nombres", "DESC");
		$this->db->group_by('ce.id_cuenta');
		
        $query = $this->db->get();
		return  $query->result();
	    
	}
	
	public function get_cuentas_gastos($where=array(),$like=array()){
		
		
		$cols = array();
		$cols[] = 'c.id AS id';
        $cols[] = 'c.rol AS rol';
		$cols[] = 'c.id_mandante AS id_mandante';
		$cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
        $cols[] = '2cc.numero_contrato AS numero_contrato';
		$cols[] = 'usr.rut AS rut_deudor';
		$cols[] = 'usr.ciudad AS ciudad';
		$cols[] = 'tr.id AS id_tribunal';
		$cols[] = 'tr.tribunal AS tribunal';
		$cols[] = 'r.nombre AS nombre_receptor';
		$cols[] = 'r.rut AS rut_receptor';
		$cols[] = 'adm.nombres AS nombres';
		$cols[] = 'adm.apellidos AS apellidos';
		$cols[] = 'man.razon_social AS razon_social';
		$cols[] = 'usr.nombres AS nombres_deudores';
		$cols[] = 'usr.ap_pat AS apellido_paterno';
		$cols[] = 'usr.ap_pat AS apellido_materno';
		$cols[] = 'pagos.forma_pago AS forma_pago';
		$cols[] = 'gastos.fecha_ingreso_banco AS fecha_ingreso_banco';
		$cols[] = 'gastos.fecha_recepcion AS fecha_recepcion';
		$cols[] = 'gastos.monto AS monto';
		$cols[] = 'gastos.retencion AS retencion'; 
		//$cols[] =' gastos.descripcion AS descripcion';
        $cols[] ='d.nombre AS nombre_diligencia';
		$cols[] ='gastos.n_boleta AS n_boleta';
        $cols[] = 'pagos.id_acuerdo_pago AS id_acuerdo_pago';

		$this->db->select($cols);
		

        $this->db->from("0_cuentas c");
		$this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("0_mandantes man","man.id=c.id_mandante","left");
		$this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
		$this->db->join("2_cuentas_gastos gastos", "gastos.id_cuenta = c.id");
        $this->db->join("diligencia d", "d.id = gastos.id_diligencia");
		$this->db->join("2_cuentas_pagos pagos", "pagos.id_cuenta = c.id","left");
		$this->db->join("0_receptores r","r.id=gastos.id_receptor","left");
		$this->db->join("s_tribunales tr","tr.id = r.id_tribunal","left");
        $this->db->join("2_cuentas_contratos 2cc", "2cc.id_cuenta = c.id","left");
    	
		 if (count($where)>0){
         $this->db->where($where);
		} 
		if (count($like)>0){
         $this->db->like($like);
		} 
		
		//$this->db->group_by('c.id');
		
        $query = $this->db->get();
		return  $query->result();
	    
	}


    public function get_cuentas_pagos($where=array())/*like=array()*/ {

        $cols = array();
        $cols[] = 'c.id AS id';
        $cols[] = 'c.id_estado_cuenta AS id_estado_cuenta';
        $cols[] = 'c.activo AS activo';
        $cols[] = 'e.estado AS estado';
        $cols[] = 'pagos.honorarios AS honorarios';
        $cols[] = 'c.public AS publico';
        $cols[] = 'c.posicion AS posicion';
        $cols[] = 'adm.nombres AS nombres';
        $cols[] = 'adm.apellidos AS apellidos';
        $cols[] = 'm.razon_social AS razon_social';
        $cols[] = 'pagos.fecha_pago AS fecha_pago';
        $cols[] = 'pagos.monto_pagado AS monto_pagado';
        $cols[] = 'c.monto_deuda AS monto_deuda';
        $cols[] = 'usr.ap_pat AS ap_pat';
        $cols[] = 'usr.ap_mat AS ap_mat';
        $cols[] = 'usr.nombres AS nombres_deudor';
        $cols[] = 'usr.rut AS rut';
        $cols[] = 'c.id_mandante AS field_categoria';
        $cols[] = 'pagos.n_comprobante_interno AS n_comprobante_interno';

        $cols[] = 'd.nombre AS nombre_diligencia';
        $cols[] = 'c.rol AS rol';
        $cols[] = 'c.numero_contrato AS numero_contrato';
        $cols[] = 'tri.tribunal AS tribunal';

        $this->db->select($cols);

        $this->db->from("0_cuentas c");
        $this->db->join("0_usuarios usr","usr.id=c.id_usuario","left");
        $this->db->join("s_estado_cuenta e" ,"e.id = c.id_estado_cuenta");
        $this->db->join("0_mandantes m","m.id=c.id_mandante","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("2_cuentas_pagos pagos", "pagos.id_cuenta = c.id");
        $this->db->join("2_cuentas_gastos 2cg","2cg.id_cuenta=c.id","left");
        $this->db->join("diligencia d","d.id=2cg.id_diligencia","left");
        $this->db->join("s_tribunales tri","tri.id=c.id_tribunal","left");


        if (count($where)>0){
            $this->db->where($where);
        }

       /* if (count($like)>0){
            $this->db->like($like);
        }*/

        $this->db->where(array('c.activo' => 'S'));
        $this->db->where(array('pagos.activo' => 'S'));

        $this->db->group_by('c.id');

        $query = $this->db->get();
        return  $query->result();

        }

        #########################################################################################
        ############ VALOR VARIABLE EXPORTAR EXCEL ALERTAS ######################################
        #########################################################################################

    public function get_cuentas_alertas_proceso($where=array(),$like=array()){

        $cols[] = 'c.id_etapa AS id_etapa';
        $cols[] = 'e.id AS etapa_id_etapa';
        $cols[] = 'm.codigo_mandante AS codigo_mandante';
        $cols[] = 'c.id_mandante AS id_mandante';
        $cols[] = 'u.nombres AS usuarios_nombres';
        $cols[] = 'u.ap_pat AS usuarios_ap_pat';
        $cols[] = 'u.ap_mat AS usuarios_ap_mat';
        $cols[] = 'u.rut AS usuario_rut';
        $cols[] = 'c.id AS cuentas_id';
        $cols[] = 'c.rol AS rol';
        $cols[] = 'c.fecha_asignacion AS fecha_asignacion';
        $cols[] = 'p.fecha_asignacion AS fecha_asignacion_pagare';
        $cols[] = 'c.fecha_etapa AS fecha_etapa';
        $cols[] = 'DATEDIFF( NOW() , c.fecha_etapa ) AS dias_diferencia';
        $cols[] = 'e.etapa AS etapa';
        $cols[] = 'secta.estado AS estado';

        $cols[] = 'e.texto_alerta AS texto_alerta';
        $cols[] = 'e.dias_alerta AS dias_alerta';
        $cols[] = "(DATEDIFF( NOW() , c.fecha_etapa ) - dias_alerta) AS dias_alerta_diferencia";
        $cols[] = 'adm.nombres AS nombres_adm';
        $cols[] = 'adm.apellidos AS apellidos_adm';
        //$cols[] = 'trib.tribunal AS tribunal_padre';
        $cols[] = 'tr.tribunal AS tribunal';

        $having = '';

        /**/
         $this->db->select($cols);

        $this->db->from("0_cuentas c");
        $this->db->join("0_usuarios u","u.id = c.id_usuario");
        $this->db->join("0_mandantes m","m.id = c.id_mandante");
        //$this->db->join("2_cuentas_etapas ce", "ce.id_cuenta = c.id AND ce.activo='S' AND ce.id_etapa = c.id_etapa");
        $this->db->join("s_etapas e", "e.id = c.id_etapa");
        $this->db->join("pagare p", "p.idcuenta = c.id","left");
        $this->db->join("0_administradores adm","adm.id=c.id_procurador","left");
        $this->db->join("s_tribunales tr","tr.id=c.id_tribunal","left");
        $this->db->join("s_tribunales dist","dist.id=tr.padre","left");
        $this->db->join("s_estado_cuenta secta","secta.id=c.id_estado_cuenta","left");

        if ($having!=''){
            $this->db->having($having);
        }

        if (count($where)>0){
            $this->db->where($where);
        }

        if (count($like)>0){
            $this->db->like($like);
        }
         //$this->db->where( "`e`.`activo` = 'S' AND CONVERT(DATEDIFF( NOW() , c.fecha_etapa ),UNSIGNED INTEGER) >= CONVERT(e.dias_alerta,UNSIGNED INTEGER)" );
        //$this->db->where( "CONVERT(DATEDIFF( NOW(),c.fecha_etapa ),UNSIGNED INTEGER) >= CONVERT(e.dias_alerta,UNSIGNED INTEGER)" );
         //$this->db->where("c.id_estado_cuenta ='1' OR c.id_estado_cuenta='6' OR c.id_estado_cuenta='2' OR c.id_estado_cuenta='8' OR c.id_estado_cuenta='7' OR m.activo = 'S'");

        //$this->db->where(array('e.dias_alerta >'=>'0'));
        //$this->db->where(array('e.activo' => 'S'));
        $this->db->where(array('c.activo' => 'S'));
        $this->db->group_by('c.id');
        //$this->db->order_by('c.dias_diferencia DESC', 'u.usuarios_ap_pat ASC','u.usuarios_ap_mat ASC');


         $query = $this->db->get();
        return  $query->result();


    }


    public function get_cuentas_alertas_procesos($where,$like){



        $cols[] = 'c.id_etapa AS id_etapa';
        $cols[] = 'c.fecha_asignacion AS fecha_asignacion';
        $cols[] = 'e.id AS etapa_id_etapa';
        $cols[] = 'm.codigo_mandante AS codigo_mandante';
        $cols[] = 'c.id_mandante AS id_mandante';
        $cols[] = 'u.nombres AS usuarios_nombres';
        $cols[] = 'u.ap_pat AS usuarios_ap_pat';
        $cols[] = 'u.ap_mat AS usuarios_ap_mat';
        $cols[] = 'u.rut AS usuarios_rut';
        $cols[] = 'c.id AS cuentas_id';
        $cols[] = 'c.rol AS rol';
        $cols[] = 'c.fecha_asignacion AS fecha_asignacion';
        $cols[] = 'p.fecha_asignacion AS fecha_asignacion_pagare';
        //$cols[] = 'ce.fecha_etapa AS fecha_etapa';
        //$cols[] = "DATEDIFF( NOW() , c.fecha_etapa ) AS dias_diferencia";
        $cols[] = "ce.fecha_etapa AS fecha_etapa";
        $cols[] = "DATEDIFF(c.fecha_asignacion,ce.fecha_etapa)  AS dias_diferencia";
        $cols[] = 'e.etapa AS etapa';
        $cols[] = 'e.texto_alerta AS texto_alerta';
        $cols[] = 'e.dias_alerta AS dias_alerta';
        $cols[] = 'adm.nombres AS nombres_adm';
        $cols[] = 'adm.apellidos AS apellidos_adm';
        $cols[] = 'trib.tribunal AS tribunal';
        $cols[] = 'e.dias_alerta_proceso AS dias_alerta_proceso';


        $this->db->select($cols);
        $this->db->from("0_cuentas c");
        $this->db->join('0_usuarios u','u.id = c.id_usuario');
        $this->db->join('0_mandantes m','m.id = c.id_mandante');
        $this->db->join('pagare p','p.idcuenta = c.id','left');
        $this->db->join('0_administradores adm','adm.id = c.id_procurador','left');
        $this->db->join("s_tribunales tri", "tri.id = c.id_tribunal","left");
        $this->db->join("s_tribunales trib","trib.id = tri.padre","left");
        $this->db->join("2_cuentas_etapas ce","ce.id_cuenta = c.id","left");
        $this->db->join('s_etapas e', 'e.id = ce.id_etapa',"left");

        $this->db->where('(DATEDIFF(`ce`.`fecha_etapa`,`c`.`fecha_asignacion`) - `e`.`dias_alerta_proceso`)>`e`.`dias_alerta_proceso`');


        if (count($where)>0){
            $this->db->where($where);
        }
        if (count($like)>0){
            $this->db->like($like);
        }

        $this->db->group_by('ce.id');

        $query = $this->db->get();
        return  $query->result();


    }


	
}
?>