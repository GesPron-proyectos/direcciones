<?php 
$id = '';
$id_usuario = '';
$id_mandante = '';
$id_procurador = '';
$obs_administrador = '';
$receptor = '';
$id_tipo_producto = '';
$n_pagare = '';
$monto_deuda = '';
$fecha_asignacion_day = '0';//date ( 'd' );
$fecha_asignacion_month = '0';//date ( 'm' );
$fecha_asignacion_year = '0';//date ( 'Y' );

$fecha_ultimo_pago = '';
$fecha_ingreso = '';

$tipo_demanda = '';
$exorto = '';

$id_tribunal = '';
$id_distrito = '';
$id_administrador = '';
$fecha_inicio_day = '0';//date ( 'd' );
$fecha_inicio_month = '0';//date ( 'm' );
$fecha_inicio_year = '0';//date ( 'Y' );
$rol = '';
$monto_demandado = '';
$id_etapa = '';
$id_estado_cuenta = '';
$bien_habitacional = FALSE;
$bien_vehiculo = FALSE;
$fecha_etapa_day = date ( 'd' );
$fecha_etapa_month = date ( 'm' );
$fecha_etapa_year = date ( 'Y' );
/*acuerdo*/
$valor_cuota = '';
$id_acuerdo_pago = '';
$n_cuotas = '';
$dia_vencimiento_cuota = date ( 'd' );
$fecha_primer_pago_day = date ( 'd' );
$fecha_primer_pago_month = date ( 'm' );
$fecha_primer_pago_year = date ( 'Y' );
/////////
$fecha_pago_day = date ( 'd' );
$fecha_pago_month = date ( 'm' );
$fecha_pago_year = date ( 'Y' );

$fecha_vencimiento = '';
/**
Campos gasto
 */
$fecha_day = date ( 'd' );
$fecha_month = date ( 'm' );
$fecha_year = date ( 'Y' );
$n_boleta = '';
$rut_receptor = '';
$nombre_receptor = '';
$monto = '';
$retencion = '';
$descripcion = '';
$valor_cuota_con_intereses = '';
$monto_pagado_new = '';
if($_POST){
	if (!empty($_POST ['id_usuario'])){ $id_usuario = $_POST ['id_usuario'];}
	if (!empty($_POST ['id_mandante'])){ $id_mandante = $_POST ['id_mandante'];}
	
	if (!empty($_POST ['id_procurador'])){ $id_procurador = $_POST ['id_procurador'];}
	
	if (!empty($_POST ['obs_administrador'])){ $obs_administrador = $_POST ['obs_administrador'];}
	
	if (!empty($_POST ['receptor'])){ $receptor = $_POST ['receptor'];}
	
	if (!empty($_POST ['tipo_demanda'])){ $tipo_demanda = $_POST ['tipo_demanda'];}
	if (!empty($_POST ['exorto'])){ $exorto = $_POST ['exorto'];}
	
	
	if (!empty($_POST ['id_tipo_producto'])){ $id_tipo_producto = $_POST ['id_tipo_producto'];}
	if (!empty($_POST ['n_pagare'])){ $n_pagare = $_POST ['n_pagare'];}
	if (!empty($_POST ['monto_deuda'])){ $monto_deuda = $_POST ['monto_deuda'];}
	if (!empty($_POST ['fecha_asignacion_day'])){ $fecha_asignacion_day = $_POST ['fecha_asignacion_day'];}
	if (!empty($_POST ['fecha_asignacion_month'])){ $fecha_asignacion_month = $_POST ['fecha_asignacion_month'];}
	if (!empty($_POST ['fecha_asignacion_year'])){ $fecha_asignacion_year = $_POST ['fecha_asignacion_year'];}
	if (!empty($_POST ['id_tribunal'])){ $id_tribunal = $_POST ['id_tribunal']; }
	
	if (!empty($_POST ['id_distrito'])){ $id_distrito = $_POST ['id_distrito']; }		
	
	if (!empty($_POST ['id_administrador'])){ $id_administrador = $_POST ['id_administrador'];}
	if (!empty($_POST ['fecha_inicio_day'])){ $fecha_inicio_day = $_POST ['fecha_inicio_day'];}
	if (!empty($_POST ['fecha_inicio_month'])){ $fecha_inicio_month = $_POST ['fecha_inicio_month'];}
	if (!empty($_POST ['fecha_inicio_year'])){ $fecha_inicio_year = $_POST ['fecha_inicio_year'];}
	if (!empty($_POST ['rol'])){ $rol = $_POST ['rol'];}
	if (!empty($_POST ['monto_demandado'])){ $monto_demandado = $_POST ['monto_demandado'];}
	if (!empty($_POST ['id_etapa'])){ $id_etapa = $_POST ['id_etapa'];}
	if (!empty($_POST ['fecha_etapa_day'])){ $id_usuario = $_POST ['fecha_etapa_day'];}
	if (!empty($_POST ['id_usuario'])){ $fecha_etapa_day = $_POST ['id_usuario'];}
	if (!empty($_POST ['fecha_etapa_month'])){ $fecha_etapa_month = $_POST ['fecha_etapa_month'];}
	if (!empty($_POST ['fecha_etapa_year'])){ $fecha_etapa_year = $_POST ['fecha_etapa_year'];}
	if (!empty($_POST ['bien_habitacional'])){ $bien_habitacional = $_POST ['bien_habitacional'];}
	if (!empty($_POST ['bien_vehiculo'])){ $bien_vehiculo = $_POST ['bien_vehiculo'];}
	if (!empty($_POST ['n_boleta'])){ $n_boleta = $_POST ['n_boleta'];}
	if (!empty($_POST ['rut_receptor'])){ $rut_receptor = $_POST ['rut_receptor'];}
	if (!empty($_POST ['nombre_receptor'])){ $nombre_receptor = $_POST ['nombre_receptor'];}
	if (!empty($_POST ['monto'])){ $monto = $_POST ['monto'];}
	if (!empty($_POST ['retencion'])){ $retencion = $_POST ['retencion'];}
	if (!empty($_POST ['descripcion'])){ $descripcion = $_POST ['descripcion'];}
	
	if (!empty($_POST ['id_acuerdo_pago'])){ $id_acuerdo_pago = $_POST ['id_acuerdo_pago'];}
	if (!empty($_POST ['n_cuotas'])){ $n_cuotas = $_POST ['n_cuotas'];}
	if (!empty($_POST ['valor_cuota'])){ $valor_cuota = $_POST ['valor_cuota'];}
	if (!empty($_POST ['dia_vencimiento_cuota'])){ $dia_vencimiento_cuota = $_POST ['dia_vencimiento_cuota'];}
	if (!empty($_POST ['fecha_primer_pago_day'])){ $fecha_primer_pago_day = $_POST ['fecha_primer_pago_day'];}
	if (!empty($_POST ['fecha_primer_pago_month'])){ $fecha_primer_pago_month = $_POST ['fecha_primer_pago_month'];}
	if (!empty($_POST ['fecha_primer_pago_year'])){ $fecha_primer_pago_year = $_POST ['fecha_primer_pago_year'];}
	
	if (!empty($_POST ['fecha_escritura_personeria'])){ $fecha_escritura_personeria = $_POST ['fecha_escritura_personeria'];}
	if (!empty($_POST ['notaria_personeria'])){ $notaria_personeria = $_POST ['notaria_personeria'];}
	if (!empty($_POST ['titular_personeria'])){ $titular_personeria = $_POST ['titular_personeria'];}
	
	
	if (!empty($_POST ['fecha_ultimo_pago'])){ $fecha_ultimo_pago = $_POST ['fecha_ultimo_pago'];}
	if (!empty($_POST ['fecha_ingreso'])){ $fecha_ingreso = $_POST ['fecha_ingreso'];}
}

if (count($lists)>0){
	
	$id = $lists->id;
	$id_usuario = $lists->id_usuario;
	$id_mandante = $lists->id_mandante;
	$id_procurador = $lists->id_procurador;
	$receptor = $lists->receptor;
	
	$exorto = $lists->exorto;
	$tipo_demanda = $lists->tipo_demanda;
	
	if( strtotime($lists->fecha_ultimo_pago)>0){
		$fecha_ultimo_pago = date("d-m-Y",strtotime($lists->fecha_ultimo_pago));
	}
	
	if( strtotime($lists->fecha_asignacion)>0){
		$fecha_ingreso = date("d-m-Y",strtotime($lists->fecha_asignacion));
	}
	
	$id_etapa = $lists->id_etapa;
	
	$id_tipo_producto = $lists->id_tipo_producto;
	$n_pagare = $lists->n_pagare;
	
	//echo $lists->intereses;
	//$monto_deuda = $lists->monto_deuda;
	$monto_deuda = $pagare_suma;
	
	$explode = explode ( '-', $lists->fecha_asignacion );
	$fecha_asignacion_day = $explode [2];
	$fecha_asignacion_month = $explode [1];
	$fecha_asignacion_year = $explode [0];
	
	$id_tribunal = $lists->id_tribunal;
	
	$id_distrito = $lists->id_distrito;
	$id_administrador = $lists->id_administrador;
	$explode = explode ( '-', $lists->fecha_inicio );
	$fecha_inicio_day = $explode [2];
	$fecha_inicio_month = $explode [1];
	$fecha_inicio_year = $explode [0];
	$rol = $lists->rol;
	$monto_demandado = $lists->monto_demandado;
	$id_estado_cuenta = $lists->id_estado_cuenta;
	$bien_habitacional = $lists->bien_habitacional;
	$bien_vehiculo = $lists->bien_vehiculo;
	
	$id_acuerdo_pago = $lists->id_acuerdo_pago;
	
	$n_cuotas = $lists->n_cuotas;
	
	$valor_cuota = $lists->valor_cuota;
	
	$monto_pagado_new = $lists->monto_pagado_new;
	
	$dia_vencimiento_cuota = $lists->dia_vencimiento_cuota;
	
	if ($lists->fecha_primer_pago!='0000-00-00'){
		$explode = explode ( '-', $lists->fecha_primer_pago );
		
		$fecha_primer_pago_day = $explode [2];
	
		$fecha_primer_pago_month = $explode [1];
	
		$fecha_primer_pago_year = $explode [0];
	}
	
	$fecha_escritura_personeria = $lists->fecha_escritura_personeria;
	$notaria_personeria = $lists->notaria_personeria;
	$titular_personeria = $lists->titular_personeria;
}
?>
<script type="text/javascript">
$(document).ready(function(){

	$('#fecha_ultimo_pago').datepicker();
	$('#fecha_ingreso').datepicker();

	$("select[name='id_tribunal']").change(function(){
		$.ajax({
			type: 'post',
			url: '<?php echo site_url();?>/admin/tribunales/combo/',
			data: 'id_tribunal='+$(this).val(),
			beforeSend: function() { $('#ajax_id_distrito').html('<img src="<?php echo base_url();?>images/ajax-loader.gif">');},
			success: function(data) {
				$('#ajax_id_distrito').html(data); 
			}
		});	
	});
	$().ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto({social_tools: ''});
	});
});
</script>

<style>
table.listado input, table.listado select{ font-size:11px; margin:5px 0 5px 5px;}
</style>

<div class="dos" style=" width:100%;">
	
	<div class="titulo">
		<strong style="float:left; margin-right:10px;">Datos de la cuenta</strong> / <a href="<?php echo site_url()?>/admin/cuentas/datos_adicionales/<?php echo $id?>">Datos Adicionales</a><a name="top">&nbsp;</a>
		<?php if (validation_errors()!='' && (isset($_POST['enviar_cuenta']) && $_POST['enviar_cuenta']!='')): ?>
		<span class="alerta"></span><span class="error">Faltan campos por completar</span> 
		<?php endif;?>
		<span class="flechita"></span>
		<div class="clear"></div>
	</div>
	<div class="blq">
	<form action="<?php echo site_url().'/admin/'.$current.'/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">
		<div class="dos" style=" width:100%;">
			<?php 
			$ultimo_pago = '';
			if (count($pagos)>0){
				$i=1;foreach ($pagos as $key=>$val){
					if ($i==1){
						$ultimo_pago = date('d-m-Y',strtotime($val->fecha_pago));
					}
				}
			}?>
			<?php if ($id_estado_cuenta==1){ $color_bg = "#BDD95E"; $color = "#758B22"; $estado_msg = "VIGENTE"; }?>
			<?php if ($id_estado_cuenta==2){ $color_bg = "#999"; $color = "#666"; $estado_msg = "ABANDONADO"; }?>
			<?php if ($id_estado_cuenta==3){ $color_bg = "#1186B6"; $color = "#00365F"; $estado_msg = "SUSPENDIDO"; }?>
			<?php if ($id_estado_cuenta==4){ $color_bg = "#DD808C"; $color = "#9E0404"; $estado_msg = "TERMINADO"; }?>
			<?php if ($id_estado_cuenta==5){ $color_bg = "#B8A0BD"; $color = "#7A318D"; $estado_msg = "DEVUELTO"; }?>
			
			<div style="width:500px; height:25px; border:1px solid <?php echo $color;?>; padding:5px; background:<?php echo $color_bg;?>;">
				<span style="color:<?php echo $color;?>; font-size:18px; text-align:center; float:left; width:490px;">ESTADO DE LA CUENTA: <?php echo $estado_msg;?></span>
			</div>
			<div class="clear height"></div>
			
			<div class="cont-form">
				<label>Deudor*:</label>
				<div class="clear"></div>
				<?php echo form_dropdown('id_usuario', $usuarios, $id_usuario);?>
				<div class="clear"></div>
				<?php echo form_error('id_usuario','<span class="error">', '</span>');?> 
			</div> 
			<?php if (!empty($id)):?>
				<div class="cont-form"> 
					<label>Nombre</label>
					<div class="clear"></div>
					<p><?php echo $usuario->nombres.' '.$usuario->ap_pat.' '.$usuario->ap_mat;?></p>
					<a class="editar" href="<?php echo site_url();?>/admin/usuarios/form/editar/<?php echo $id_usuario?>"></a>
				</div>
			<?php endif; /*id*/?>
			<div class="cont-form">
				<label style="width: 140px; float: left">Mandante*:</label>
				<div class="clear"></div>
				<?php if ($id_mandante == '' or $id_mandante == '0'):?>
				<?php echo form_dropdown('id_mandante', $mandantes, $id_mandante);?>
				<?php else: echo $mandantes[$id_mandante];?>
				<?php endif;?>
				<div class="clear"></div>
				<?php echo form_error('id_mandante','<span class="error">', '</span>');?> 
			</div>
			<div class="cont-form">
				<label style="width: 140px; float: left">Procurador*:</label>
				<div class="clear"></div>
				<?php echo form_dropdown('id_procurador', $administradores, $id_procurador);?>
				<div class="clear"></div>
				<?php echo form_error('id_procurador','<span class="error">', '</span>');?>
			</div>
			<div class="cont-form">
				<label style="width: 140px; float: left">Receptor:</label>
				<div class="clear"></div>
				<?php echo form_dropdown('receptor', $receptores, $receptor);?>
				<?php //echo form_input('receptor', $receptor);?>
				
				<div class="clear"></div>
				<?php echo form_error('receptor','<span class="error">', '</span>');?>
			</div>
			
			<?php if ($ultimo_pago!=''):?>
				<div class="cont-form">
					<label style="width: 190px; float: left">Fecha de último pago:</label>
					<div class="clear"></div>
					<div style="color:#9E0404;font-size:20px;"><?php echo $ultimo_pago;?></div>
				</div>
			<?php endif;?>
			<span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>  
			<div class="clear"></div>
			<?php if (!empty($id)):?>
			<?php 
			if (count($direccion_list)>0){
				$direccion = $direccion_list->direccion;
			}
			
			$telefonos = '';
			if (count($telefono_list)>0){
				foreach($telefono_list as $key=>$val){
					$style = '';
					if ($val->estado==1){ $style='color:#7FBA00"';}
				    if($val->tipo == 1){
						if ($telefonos!=''){$telefonos.=' /';}$telefonos.=' Particular:';
				    }
					if( $val->tipo == 2){
						if ($telefonos!=''){$telefonos.=' /';}$telefonos.=' Comercial:';
				    }
				    if( $val->tipo == 3){
						if ($telefonos!=''){$telefonos.=' /';}$telefonos.=' Celular:';
				    }
					if( $val->tipo == 4){
						if ($telefonos!=''){$telefonos.=' /';}$telefonos.=' Otro:';
				    }
					$telefonos.=' <span style="'.$style.'">'.$val->numero.'</span>';
			  	} 
			}
			?>
				<div class="cont-form">
					<label>Dirección:</label>
					<div class="clear"></div>
					<p><?php echo $direccion;?></p>
				</div>
				<div class="cont-form">
					<label>Comuna:</label>
					<div class="clear"></div>
					<?php if( $usuario->id_comuna > 0) :?>
						<p><?php echo $comunas[$usuario->id_comuna];?></p>
					<?php endif;?>
				</div>
				<div class="cont-form">
					<label> Ciudad:</label>
					<div class="clear"></div>
					<p><?php echo $usuario->ciudad;?></p>
				</div>
				<div class="cont-form">
					<label>Teléfonos:</label>
					<div class="clear"></div>
					<p><?php echo $telefonos;?></p>
				</div>
				
				<div class="cont-form">
					<label>Mail:</label>
					<div class="clear"></div>
					<p><?php if(count( $mail_list)>0 ){ echo $mail_list->mail; }?></p>
				</div>
				
				<span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>  
				<div class="clear"></div>
				<div class="cont-form">
					<label style="width: 165px; float: left">Fecha Escritura Personería:</label>
					<div class="clear"></div>
					<input type="text" name="fecha_escritura_personeria" value="<?php echo $fecha_escritura_personeria;?>" />
				</div>
				<div class="cont-form">
					<label>Notaría Personería:</label>
					<div class="clear"></div>
					<input type="text" name="notaria_personeria" value="<?php echo $notaria_personeria;?>" />
				</div>
				<div class="cont-form">
					<label>Titular Personería:</label>
					<div class="clear"></div>
					<input type="text" name="titular_personeria" value="<?php echo $titular_personeria;?>" />
				</div>
				<div class="clear"></div>
			<?php endif; //id?>
			
			<div class="clear"></div>
			<div class="cont-form">
				<label for="id_tribunal">Distrito:</label>
				<div class="clear"></div>
				<?php if ($id_tribunal == '' or $id_tribunal == '0' or $this->session->userdata('usuario_perfil')<=2): ?>
					<?php echo form_dropdown('id_tribunal', $tribunales, $id_tribunal);?>
				<?php else:?>
					<?php echo $tribunales[$id_tribunal];?>
				<?php endif;?>
			</div>
			
			<div class="cont-form">
				<label for="monto_deuda">Juzgado:</label>
				<div class="clear"></div>
				<div id="ajax_id_distrito">
					<?php if ($id_tribunal!='' && $id_tribunal!='0'):?>
						<?php if ($id_distrito == '' or $id_distrito == '0' or $this->session->userdata('usuario_perfil')<=2): ?>
							<?php echo form_dropdown('id_distrito', $distritos, $id_distrito);?>
							<?php else: echo $distritos[$id_distrito];?>
						<?php endif;?>
					<?php endif;?>
				</div><!-- ajax_id_distrito -->
			</div>
			
			<div class="cont-form">
				<label for="rol" id="label_rol" style="width: 68px;">Rol:</label>
				<div class="clear"></div>
				<?php if ($rol == '' or $this->session->userdata('usuario_perfil')<=2):?>
					<input id="rol" name="rol" type="text" value="<?php echo $rol;?>" style="width: 150px;">
				<?php else:?>
					<?php echo $rol;?>
				<?php endif;?>
				<?php echo form_error('rol','<span class="error">', '</span>');?>
			</div>
			
			<script>
			$(document).on("change","#rol",function(e){
			    var val = $(this).val();
			    
			    if( val.contains("-")){
			    	str = val.split("-");
			    	if( str[1].length == 4){
			    		$('#label_rol').css('color','#666666');
			    		$('#btn_submit_cuenta').show();
			    	}else{
			    		alert("ROL: Formato incorrecto. Ej(15487-2014)");
			    		$('#label_rol').css('color','#F00000');
			    		$('#btn_submit_cuenta').hide();
			    	}
			    }else{
			    	if( val.length != 0){
			    		alert("ROL: Formato incorrecto. Ej(15487-2014)");
				    	$('#label_rol').css('color','#F00000');
				    	$('#btn_submit_cuenta').hide();
			    	}else{
			    		$('#label_rol').css('color','#666666');
			    		$('#btn_submit_cuenta').show();
			    	}
			    }
			    
			});
			</script>
			
			
			<div class="cont-form">
				<label for="fecha_inicio_year" style="width: 135px;">Fecha de Ingreso:</label>
				<div class="clear"></div>
				<?php echo form_input('fecha_ingreso',$fecha_ingreso,'id="fecha_ingreso" data-date-format="dd-mm-yyyy"');?>
				<div class="clear"></div>
				<?php echo form_error('fecha_ingreso','<span class="error">', '</span>');?>
			</div>
			
			<div class="cont-form">
				<label for="fecha_inicio_year" style="width: 135px;">Fecha ultimo pago:</label>
				<div class="clear"></div>

					<?php echo form_input('fecha_ultimo_pago',$fecha_ultimo_pago ,'id="fecha_ultimo_pago" data-date-format="dd-mm-yyyy"');?>

				<div class="clear"></div>
				<?php echo form_error('fecha_ultimo_pago','<span class="error">', '</span>');?>
			</div>
			
			<div class="cont-form">
				<label for="monto_demandado" style="width: 136px;">Saldo Deuda*:</label>
				<div class="clear"></div>
				<?php $global_total_saldo = ($monto_deuda-(int)$total_pagado->total)?>
				<?php echo  '$'.number_format($monto_deuda-(int)$total_pagado->total,0,',','.');?>
				<div class="clear"></div>
				<?php echo form_error('monto_demandado','<span class="error">', '</span>');?>
			</div>
			<span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>  
			<div class="clear"></div>
			<div class="cont-form">
				<label for="id_estado_cuenta" style="width: 140px; float: left">Estado de la Cuenta:</label>
				<div class="clear"></div>
				<?php echo form_dropdown('id_estado_cuenta', $estados_cuenta, $id_estado_cuenta);?>
				<div class="clear"></div>
				<?php echo form_error('id_estado_cuenta','<span class="error">', '</span>');?>
			</div>

<?php if($nodo->nombre == 'fullpay'):?>
	<div class="cont-form">
		<label style="width:135px!important; float:left">Tipo demanda *:</label>
		<div class="clear"></div>
		<div class="cont-form">
		<?php echo form_dropdown('tipo_demanda', array(''=>'-- Selecionar --','1'=>'Propia','0'=>'Cedida'),$tipo_demanda )?>
		</div>
	</div>

	<div class="cont-form">
		<label style="width:135px!important; float:left">Exorto *:</label>
		<div class="clear"></div>
		<div class="cont-form">
		<?php echo form_dropdown('exorto', array(''=>'-- Selecionar --','1'=>'Con exorto','0'=>'Sin exorto'),$exorto )?>
		</div>
	</div>
<?php endif; //end condicion pages?>

<div class="cont-form">
				<label for="bien_habitacional">Bienes</label>
				<div class="clear"></div>
				<div class="cont-form">
				<?php $bienes = array('1'=>'Vehículo','2'=>'Inmueble'); ?>
				
				<?php if( count($bien_list)>0):?>
                	
                	<?php foreach ($bien_list as $key=>$val):?>
					<?php if (array_key_exists($val->tipo, $bienes)) :?>
						<?php 
						$style='';
						if ($val->estado==1){ $style='color:#7FBA00"';}
						echo '<span style="'.$style.'">'.$bienes[$val->tipo].': '.$val->observacion.'</span><br>';
						?>
					<?php endif;?>
                    <?php endforeach;?>
				<?php endif;?>
				</div>
			</div>
	
		<div class="clear height"></div>
		<!-- 
		<BR>
		<div class="cont-form" name="pagare" id="pagare">
			<label style="width: 127px; float: left">Tipo de Producto*:</label>
			<div class="clear"></div>
			<?php echo form_dropdown('id_tipo_producto', $tipo_de_productos, $id_tipo_producto);?>
			<div class="clear"></div>
			<?php echo form_error('id_tipo_producto','<span class="error">', '</span>');?>
		</div>
		<div class="cont-form">
			<label for="n_pagare" style="width: 135px;">Número de Pagaré*:</label>
			<div class="clear"></div>
			<?php if ($n_pagare==''):?>
			<?php else: //echo $n_pagare;?>
			<?php endif;?>
			<input id="n_pagare" name="n_pagare" type="text" value="<?php echo $this->input->post('n_pagare');?>" style="width: 150px;">
			<div class="clear"></div>
			<?php echo form_error('n_pagare','<span class="error">', '</span>');?>
		</div>
		<div class="cont-form">
			<label for="monto_deuda" style="width: 100px;">Monto Pagaré*:</label>
			<div class="clear"></div>
			<?php if ($monto_deuda=='' or $monto_deuda==0 or $this->session->userdata('usuario_perfil')<=2):?>
			<input id="monto_deuda" name="monto_deuda" type="text" value="<?php echo $this->input->post('monto_deuda');?><?php //echo $monto_deuda;?>" style=" text-align:right; width: 86px;">
			<?php else: echo  '$'.number_format($monto_deuda,0,',','.');?>
			<?php endif;?>
			<div class="clear"></div>
			<?php echo form_error('monto_deuda','<span class="error">', '</span>');?>
		</div>
		<div class="cont-form">
			<label for="monto_deuda" style="width: 135px;">Fecha Pagaré*:</label>
			<div class="clear"></div>
			<?php if ($fecha_asignacion_day=='' or $fecha_asignacion_month=='' or $fecha_asignacion_year=='' or $id=='' or $this->session->userdata('usuario_perfil')<=2):?>
			<?php echo day_dropdown('fecha_asignacion_day',$fecha_asignacion_day).month_dropdown('fecha_asignacion_month',$fecha_asignacion_month).year_dropdown('fecha_asignacion_year',$fecha_asignacion_year,2010,date('Y')+10);?>
			<?php else: echo $fecha_asignacion_day."-".$fecha_asignacion_month."-".$fecha_asignacion_year;?>
			<?php endif;?>
			<div class="clear"></div>
			<?php echo form_error('fecha_asignacion_year','<span class="error">', '</span>');?>
		</div> 
		<div class="cont-form">
			<label for="monto_deuda" style="width: 135px;">&nbsp;</label>
			<div class="clear"></div>
		</div> -->
		
		<input type="submit" name="enviar_cuenta" value="Guardar Datos de la cuenta" class="boton" id="btn_submit_cuenta" style="width: 100%; float: left;">
		<div class="clear"></div>

	</div><!--blq-->
	</form>
	<div class="clear"></div>
</div>

</div>

<div class="dos" style=" width:100%;">
	<div class="titulo">
	    <strong style="float:left; margin-right:10px;">Pagares<a name="pagares">&nbsp;</a></strong>
	    <span class="flechita"></span>
	    <div class="clear"></div>
	</div>
	
	<div class="blq">
		<div class="dos" style=" width:100%;">
			
			<?php $url_form='';if (!empty($id)){$url_form.='/'.$id;}if (!empty($param)){$url_form.='/'.$param;}?>
			<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-pagare'.$url_form.'#guardar-pagare'); ?>
			<div class="cont-form" name="pagare" id="pagare">
				<label style="width: 127px; float: left">Tipo de Producto:</label>
				<div class="clear"></div>
				<?php echo form_dropdown('id_tipo_producto', $tipo_de_productos, $id_tipo_producto);?>
				<div class="clear"></div>
				<?php echo form_error('id_tipo_producto','<span class="error">', '</span>');?>
			</div>
			<div class="cont-form">
				<label for="n_pagare" style="width: 135px;">Número de Pagaré:</label>
				<div class="clear"></div>
				<?php if ($n_pagare==''):?>
				<?php else: //echo $n_pagare;?>
				<?php endif;?>
				<input id="n_pagare" name="n_pagare" type="text" value="<?php echo $this->input->post('n_pagare');?>" style="width: 150px;">
				<div class="clear"></div>
				<?php echo form_error('n_pagare','<span class="error">', '</span>');?>
			</div>
			<div class="cont-form">
				<label for="monto_deuda" style="width: 100px;">Monto Pagaré*:</label>
				<div class="clear"></div>
				<?php if ($monto_deuda=='' or $monto_deuda==0 or $this->session->userdata('usuario_perfil')<=2):?>
				<input id="monto_deuda" name="monto_deuda" type="text" value="<?php echo $this->input->post('monto_deuda');?><?php //echo $monto_deuda;?>" style=" text-align:right; width: 86px;">
				<?php else: echo  '$'.number_format($monto_deuda,0,',','.');?>
				<?php endif;?>
				<div class="clear"></div>
				<?php echo form_error('monto_deuda','<span class="error">', '</span>');?>
			</div>
			<div class="cont-form">
				<label for="monto_deuda" style="width: 135px;">Fecha Pagaré:</label>
				<div class="clear"></div>
				<?php if ($fecha_asignacion_day=='' or $fecha_asignacion_month=='' or $fecha_asignacion_year=='' or $id=='' or $this->session->userdata('usuario_perfil')<=2):?>
				<?php echo day_dropdown('fecha_asignacion_day',$fecha_asignacion_day).month_dropdown('fecha_asignacion_month',$fecha_asignacion_month).year_dropdown('fecha_asignacion_year',$fecha_asignacion_year,2010,date('Y')+10);?>
				<?php else: echo $fecha_asignacion_day."-".$fecha_asignacion_month."-".$fecha_asignacion_year;?>
				<?php endif;?>
				<div class="clear"></div>
				<?php echo form_error('fecha_asignacion_year','<span class="error">', '</span>');?>
			</div>
			
			<div class="cont-form">
				<label for="monto_deuda" style="width: 135px;">Fecha Vencimiento:</label>
				<div class="clear"></div>
				<?php echo form_input('fecha_vencimiento',$fecha_vencimiento,'id="fecha_vencimiento_pagare"');?>
				<div class="clear"></div>
				<?php echo form_error('fecha_vencimiento','<span class="error">', '</span>');?>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					$('#fecha_vencimiento_pagare').datepicker();
				});
			</script>

			<input type="submit" name="enviar_cuenta" value="Guardar Pagare" class="boton" style="width: 100%; float: left;">
			<div class="clear"></div>
			</form>
			<br>
			
			<div class="dos" style=" width:100%;">
				<div id="table_pagare">
						<table class="listado" border="1" style="border:1px solid #CDCCCC; width:100%; border-collapse:collapse;">
							<tr class="titulos-tabla">
								<td><b>Tipo de Producto</b></td>
								<td><b>Número de Pagaré</b></td>
								<td><b>Fecha Pagaré</b></td>
								<td><b>Fecha Vencimiento</b></td>
								<td><b>Monto Pagaré</b></td>
								<td><b>Fecha Pagaré</b></td>
							</tr>
							<?php //$class = 'a';?>
							<?php foreach($pagares as $key=>$val): ?> 
							<tr class="detalle-tabla" style="font-size:12px;height:27px;" id="doc_<?php echo $val->s_tipo_productos_id?>">
								<td><?php echo $val->s_tipo_productos_tipo?></td>
								<td><?php echo $val->pagare_n_pagare?></td>
								<td>
								<?php if( strtotime($val->pagare_fecha_asignacion)>0 ):?>
									<?php echo date("d-m-Y", strtotime($val->pagare_fecha_asignacion))?>
								<?php endif;?>
								</td>
								<td>
								<?php if( strtotime($val->pagare_fecha_vencimiento)>0 ):?>
									<?php echo date("d-m-Y", strtotime($val->pagare_fecha_vencimiento))?>
								<?php endif;?>
								</td>
								<td><?php echo $val->pagare_monto_deuda?></td>
								<td><a data-id="<?php echo $val->s_tipo_productos_id?>" href="<?php echo site_url().'/admin/cuentas/delete_pagare/'.$val->pagare_idpagare?>/<?php echo $id?>">Eliminar</a></td>
							</tr>
							<?php //if($class == 'a'){ $class = 'b'; }else{ $class = 'a'; }?>
							<?php endforeach;?>
						</table>
				</div>
			</div>
		
		</div>
		<div class="clear"></div>
	</div>
</div>

<?php //////////////historial////////////////////?>
<?php if (!empty($id)):?>
<div class="dos" style=" width:100%;">

	<div class="titulo">
	    <strong style="float:left; margin-right:10px;">Pagos / Abonos<a name="pagos">&nbsp;</a></strong>
	    <?php if (validation_errors()!='' && (isset($_POST['enviar_pago']) && $_POST['enviar_pago']!='')): ?>
	    <span class="alerta"></span><span class="error">Faltan campos por completar</span> <?php endif;?>
	    <span class="flechita"></span>
	    <div class="clear"></div>
	</div>

<?php if ($this->session->userdata("usuario_perfil")<=2): ?>
<div class="blq">
	<div class="dos" style=" width:100%;">
		<?php $url_form='';if (!empty($id)){$url_form.='/'.$id;}if (!empty($param)){$url_form.='/'.$param;}?>
		<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-acuerdo'.$url_form.'#pagos','id="form_guardar-acuerdo" autocomplete="off"'); ?>
		<div class="clear"></div>
		
		<input type="hidden" name="id_acuerdo_pago" value="">
		
		<!-- 
		<div class="cont-form">
		    <label for="id_acuerdo_pago" style="width: 150px;">Convenio de Pago*:</label>
		    <div class="clear"></div>
		    <select name="id_acuerdo_pago">
		        <option value="0"<?php if ($id_acuerdo_pago == '0' or empty($id_acuerdo_pago)){echo 'selected="selected"';}?>>Seleccionar</option>
		        <option value="1"<?php if ($id_acuerdo_pago == '1'){echo 'selected="selected"';}?>>Abono</option>
		        <option value="2"<?php if ($id_acuerdo_pago == '2'){echo 'selected="selected"';}?>>Convenio</option>
		    </select>
		</div>
		 -->
		<div class="cont-form">
		    <label for="n_cuotas" style="width: 150px;">Número de Abonos*:</label>
		    <div class="clear"></div>
		    <?php echo form_input(array('name'=>'n_cuotas','style'=>'width:86px; text-align:right;'), $n_cuotas,'class="n_cuotas_intereses" id="n_cuotas_intereses" autocomplete="off"' );?>
		    <input type="hidden" name="n_cuotas_real" id="n_cuotas_real">
		    
		    <div class="clear"></div>
		    <?php form_error('n_cuotas','<span class="error">', '</span>');?>
		</div>
		
		<div class="cont-form">
		    <label for="intereses" style="width: 150px;">Interes*:</label>
		    <div class="clear"></div>
		    <?php echo form_dropdown('intereses',array('2'=>'2,00','2.1'=>'2,10','2.2'=>'2,20','2.3'=>'2.30','2.4'=>'2,40','2.5'=>'2,50'),'','id="intereses"' );?>
		    <div class="clear"></div>
		    <input type="hidden" name="monto_deuda" id="global_total_saldo"  value="<?php echo $monto_deuda?>">
		    <input type="hidden" name="id_cuenta" value="<?php echo $id?>">
		    <?php form_error('valor_cuota','<span class="error">', '</span>');?>
		    <input type="hidden" name="valor_cuota_real" id="valor_cuota_real" >
		</div>
		
		<div class="cont-form">
		    <label for="valor_cuota" style="width: 150px;">Valor Abono*:</label>
		    <div class="clear"></div>
		    <?php echo form_input(array('name'=>'valor_cuota','style'=>'width:100px; text-align:right;'), $valor_cuota,'id="valor_cuota"' );?>
		    <div class="clear"></div>
		    <?php form_error('valor_cuota','<span class="error">', '</span>');?>
		</div>
		
		<input type="hidden" name="valor_cuota_con_intereses" id="valor_cuota_con_intereses" value="" >
		
		<div class="cont-form">
		<label for="dia_vencimiento_cuota" style="width: 150px;">Día de vencimiento*:</label>
		<div class="clear"></div>
		<?php echo day_dropdown('dia_vencimiento_cuota',$dia_vencimiento_cuota);?>
		<div class="clear"></div>
		<?php echo form_error('dia_vencimiento_cuota','<span class="error">', '</span>');?>
		</div>
		
		<div class="cont-form">
		<label for="fecha_primer_pago_year" style="width: 135px;">Fecha Primer Pago*:</label>
		<div class="clear"></div>
		<?php echo day_dropdown('fecha_primer_pago_day',$fecha_primer_pago_day).month_dropdown('fecha_primer_pago_month',$fecha_primer_pago_month).year_dropdown('fecha_primer_pago_year',$fecha_primer_pago_year,2010,date('Y')+10);?>
		<div class="clear"></div>
		<?php echo form_error('fecha_primer_pago_year','<span class="error">', '</span>');?>
		</div>
		<input type="submit" name="enviar_pago" value="Guardar Acuerdo Pago" class="boton" style="width: 200px; float: left; margin-top: 24px;">
		<?php echo form_close();?>
		<div class="clear height"></div>
		<span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>
		<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-pagos'.$url_form.'#pagos','autocomplete="off"'); ?>
		
		<div class="clear"></div>
		
		
		<div class="cont-form">
		    <label for="id_acuerdo_pago" style="width: 150px;">Convenio de Pago*:</label>
		    <div class="clear"></div>
		    <select name="id_acuerdo_pago">
		        <option value="0"<?php if ($id_acuerdo_pago == '0' or empty($id_acuerdo_pago)){echo 'selected="selected"';}?>>Seleccionar</option>
		        <option value="1"<?php if ($id_acuerdo_pago == '1'){echo 'selected="selected"';}?>>Abono</option>
		        <option value="2"<?php if ($id_acuerdo_pago == '2'){echo 'selected="selected"';}?>>Convenio</option>
		    </select>
		</div>
		
		
		<div class="cont-form">
		    <label for="fecha_pago_year" style="width: 135px;">Fecha Pago*:</label>
		    <div class="clear"></div>
		    <?php echo day_dropdown('fecha_pago_day',$fecha_pago_day).month_dropdown('fecha_pago_month',$fecha_pago_month).year_dropdown('fecha_pago_year',$fecha_pago_year,2010,date('Y')+10);?>
		    <div class="clear"></div>
		    <?php echo form_error('fecha_pago_year','<span class="error">', '</span>');?>
		</div>
		
		<div class="cont-form">
		    <label for="monto_pagado" style="width: 100px;">Monto Pagado*:</label>
		    <div class="clear"></div>
		    <?php echo form_input('monto_pagado','','id="monto_pagado" style="width:86px; text-align:right;"');?>
		    <div class="clear"></div>
		    <?php echo form_error('monto_pagado','<span class="error">', '</span>');?>
		</div>
		
		<div class="cont-form">
		    <label for="honorarios" style="width: 135px;">Honorarios*:</label>
		    <div class="clear"></div>
		    <?php echo form_input(array('name'=>'honorarios','style'=>'width:86px; text-align:right;'), '');?>
		    <div class="clear"></div>
		    <?php echo form_error('horarios','<span class="error">', '</span>');?>
		</div>
		<div class="cont-form">
		    <label for="monto_remitido" style="width: 135px;">Monto Remitido*:</label>
		    <div class="clear"></div>
		    <?php echo form_input(array('name'=>'monto_remitido','style'=>'width:86px; text-align:right;'), '');?>
		    <div class="clear"></div>
		    <?php echo form_error('monto_remitido','<span class="error">', '</span>');?>
		</div>
		<div class="cont-form">
		    <label for="n_comprobante_interno" style="width: 175px;">Nº Comprobante Interno*:</label>
		    <div class="clear"></div>
		    <?php echo form_input(array('name'=>'n_comprobante_interno','style'=>'width:86px; text-align:right;'), '');?>
		    <div class="clear"></div>
		    <?php echo form_error('n_comprobante_interno','<span class="error">', '</span>');?>
		</div>
	</div>
	<div class="cont-form">
	    <label for="forma_pago" style="width: 135px;">Forma de Pago*:</label>
	    <div class="clear"></div>
	    <?php echo form_dropdown('forma_pago',$forma_pagos);?>
	    <div class="clear"></div>
	    <?php echo form_error('forma_pago','<span class="error">', '</span>');?>
	</div>

	<input type="submit" name="enviar_pago" value="Guardar Pago" class="boton" style="width: 200px;; float: left; margin-top:24px;">
	
	<div class="clear height"></div>
	<div class="clear"></div>

<?php endif;?>
<?php echo form_close(); ?>

	<div id="lista_intereses"></div>

	<?php $width='70%';if ($this->session->userdata('usuario_perfil')<=2){$width='90%';}?>
	<!-- <div class="dos" style=" width:<?php echo $width;?>;">  -->
	<div>
		<?php if (count($pagos)>0 or $n_cuotas!=''):?>
		<?php //print_r($expression)?>
		<table height="61" class="listado" border="1" style="border:1px solid #CDCCCC; border-collapse:collapse;" bgcolor="#E2E0E0">
		    <tr class="titulos-tabla">
		        <td width="86">Fecha Vencimiento</td>
		        <td width="86">Fecha Pago</td>
		        <td width="96">Monto Pagado</td>
		        <td width="96">Honorarios</td>
		        <td width="106">Monto Remitido</td>
		        <td width="106">Saldo</td>
		        <td width="106">Nº Comprobante</td>
		        <td width="30">Voucher</td>
		        <td width="156">&nbsp;</td>
		    </tr>
			<?php 
				if ($n_cuotas!=''){
					$mes_vencimiento_cuota = $fecha_primer_pago_month; $mes_vencimiento_cuota = (int)$mes_vencimiento_cuota;
					$year_vencimiento_cuota = $fecha_primer_pago_year; $year_vencimiento_cuota = (int)$year_vencimiento_cuota;
								
					for ($j=1;$j<=$n_cuotas;$j++){
						if ($mes_vencimiento_cuota == 12){ $mes_vencimiento_cuota = 1; $year_vencimiento_cuota++; }
						else{ if ($j>1){$mes_vencimiento_cuota++;}}
						if ($mes_vencimiento_cuota<10){ $s_mes_vencimiento_cuota = '0'.$mes_vencimiento_cuota;}
						else{$s_mes_vencimiento_cuota=$mes_vencimiento_cuota;}
						//echo $j.'-'.$dia_vencimiento_cuota.'-'.$s_mes_vencimiento_cuota.'-'.$year_vencimiento_cuota.'<br>';
						$fecha_vencimiento[$j-1] = $dia_vencimiento_cuota.'-'.$s_mes_vencimiento_cuota.'-'.$year_vencimiento_cuota;
					}
				}
			?>
		    <?php 
		    
		    //$saldo_pagos = $monto_deuda-($valor_cuota*$n_cuotas);
		    
		   $saldo_pagos = $monto_deuda - $monto_pagado_new;

		   
		   foreach ($pagos as $key=>$val){
		   		$saldo_pagos += $val->monto_pagado;
		   }
		   
		    
		    $i=0;

		    foreach ($pagos as $key=>$val): ?>
		    <?php   //$saldo_pagos -=$val->monto_remitido; ?>
		    
		     <?php //echo $saldo_pagos.' - '.$val->monto_pagado.'<br>'?>
		    
		    <?php   $saldo_pagos -= $val->monto_pagado; ?>

		    
		    <?php if ($this->session->userdata('usuario_perfil')<=2):?>
				
		        <?php
		            $explode = explode ( '-', $val->fecha_pago );
		            $fecha_pago_day = $explode [2];
		            $fecha_pago_month = $explode [1];
		            $fecha_pago_year = $explode [0];
		        ?>
		        <tr class="detalle-tabla">
		        <?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-pagos'.$url_form.'/'.$val->id.'#pagos'); ?>
		        <td><?php /*if (isset($fecha_vencimiento)){
		        	echo $fecha_vencimiento[$i];
		        }else{
		        	echo '&nbsp;';
		        }*/
		        if( strtotime($val->fecha_vencimiento)>0 ){
		        	echo $val->fecha_vencimiento;
		        }else{
		        	echo '&nbsp;';
		        }?></td>
		        <td><?php echo date("d-m-Y", strtotime($val->fecha_pago));?>
				<?php //echo day_dropdown('fecha_pago_day',$fecha_pago_day).month_dropdown('fecha_pago_month',$fecha_pago_month).year_dropdown('fecha_pago_year',$fecha_pago_year,2000,date('Y'));?></td>
		        <td><?php echo form_input(array('name'=>'monto_pagado','style'=>'width:50px;'), $val->monto_pagado);?></td>
		        <td><?php echo form_input(array('name'=>'honorarios','style'=>'width:60px;'), $val->honorarios);?></td>
		        <td style="text-align:right;"><?php echo '$'.number_format($val->monto_remitido,0,',','.');?></td>
		        
		        <td style="text-align:right;"><?php echo '$'.number_format($saldo_pagos,0,',','.');?>
		        
		        <td><?php echo form_input(array('name'=>'n_comprobante_interno','style'=>'width:86px;'), $val->n_comprobante_interno);?><br><?php echo form_dropdown('forma_pago',$forma_pagos,$val->forma_pago);?></td>
		         <td><?php if ($val->id_comprobante>0):?><?php echo $val->id_comprobante;?>
		         <a href="<?php echo site_url().'/admin/comprobantes/voucher/'. $val->id_comprobante;?>" style="margin-top:2px; float:right;" target="_blank">
		         <img src="<?php echo base_url().'images/print_16.png';?>"></a><?php endif;?></td>
		        <td><input type="submit" name="enviar_pagos" value="Modificar" class="boton" style="width: 80px; float: right; margin:0px 5px 5px;"><a href="<?php echo site_url().'/admin/'.$current.'/form/eliminar-pago'.$url_form.'/'.$val->id.'#pagos';?>">Eliminar</a></td>
		        </td>
		        </tr>
		        <?php echo form_close();?>
		    <?php else:?>
		    <tr class="detalle-tabla">
		        <td><?php if (isset($fecha_vencimiento)){echo $fecha_vencimiento[$i];}?></td>
		        <td><?php echo date("d-m-Y", strtotime($val->fecha_pago));?></td>
		        <td style="text-align:right;"><?php echo '$'.number_format($val->monto_pagado,0,',','.');?></td>
		        <td style="text-align:right;"><?php echo '$'.number_format($val->honorarios,0,',','.');?></td>
		        <td style="text-align:right;"><?php echo '$'.number_format($val->monto_remitido,0,',','.');?></td>
		        <td style="text-align:right;"><?php echo '$'.number_format($saldo_pagos,0,',','.');?></td>
		        <td><?php echo $val->n_comprobante_interno;?></td>
		        <td><?php if ($val->id_comprobante>0):?>
				<?php echo $val->id_comprobante;?>
		        <a href="<?php echo site_url().'/admin/comprobantes/voucher/'. $val->id_comprobante;?>" style="margin-top:2px; float:right;" target="_blank">
		        <img src="<?php echo base_url().'images/print_16.png';?>"></a><?php endif;?></td>
		        <td>&nbsp;</td>
		    </tr>
		    <?php endif;?>
		    <?php $i++;endforeach;?>
		    <?php if (($n_cuotas-count($pagos))>0):for ($i=count($pagos);$i<$n_cuotas;$i++):?>
		    <tr class="detalle-tabla">
		        <td><?php if (isset($fecha_vencimiento[$i])){echo $fecha_vencimiento[$i];}?></td>
		        <td>&nbsp;</td>
		        <td style="text-align:right;">&nbsp;</td>
		        <td style="text-align:right;">&nbsp;</td>
		        <td style="text-align:right;">&nbsp;</td>
		        <td style="text-align:right;">&nbsp;</td>
		        <td style="text-align:right;">&nbsp;</td>
		        <td>&nbsp;</td>
                <td>&nbsp;</td>
		    </tr>
		    <?php endfor;endif;?>
		</table>
		<?php endif;?>
		<?php include APPPATH.'views/backend/templates/cuentas/volver.php';?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
</div>

<div class="dos" style=" width:100%;">

<div class="titulo">
	<strong style="float:left; margin-right:10px;">Observaciones / Historial<a name="historial">&nbsp;</a></strong>
	<?php if (validation_errors()!='' && (isset($_POST['enviar_historial']) && $_POST['enviar_historial']!='')): ?>
		<span class="alerta"></span><span class="error">Faltan campos por completar</span> 
	<?php endif;?>
	<span class="flechita"></span>
	<div class="clear"></div>
</div>

	<div class="blq">
		<?php 
		$url_form='';
		if (!empty($id)){$url_form.='/'.$id;}
		if (!empty($param)){$url_form.='/'.$param;}
		?>
		<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-historial'.$url_form.'#historial'); ?>
		<div class="cont-form">
		    <label for="historial" style="width: 135px;">Observaciones*:</label>

		    <?php echo form_textarea(array('name'=>'historial','style'=>'height:80px'), '');?>

		    <?php echo form_error('historial','<span class="error">', '</span>');?>
		</div>
	

	<input type="submit" name="enviar_etapa" value="Guardar Historial" class="boton" style="width: 100%; float: left;">
	<div class="clear"></div>

<?php echo form_close(); ?>
<?php endif;?>


<div class="dos" style=" width:100%;">
	<div class="dos" style=" width:100%;">
		<?php if (count($historiales)>0):?>
		<table class="listado" border="1" style="border:1px solid #CDCCCC; width:100%; border-collapse:collapse;">
		<tr class="titulos-tabla" height="23">
		    <td>Fecha</td>
		    <td>Observación</td>
		    <td>&nbsp;</td>
		</tr>
		<?php foreach ($historiales as $key=>$val):?>
			<tr class="detalle-tabla" height="23">
		    	<?php date_default_timezone_set("America/Santiago");?>
		        <td><?php echo date("d-m-Y H:i", strtotime($val->fecha));?></td>
		        <td><?php echo $val->historial;?></td>
		        <td><a href="<?php echo site_url().'/admin/'.$current.'/form/eliminar-historial'.$url_form.'/'.$val->id.'#historial';?>">Eliminar</a></td>
		    </tr>
		<?php endforeach;?></table>
		<?php endif;?>
	</div>
</div>

<div class="clear"></div>
</div>
</div>


<?php /////////////////juicio////////////////?>
<?php if (!empty($id)):?>
<div class="dos" style=" width:100%;">
	<div class="titulo">
		<strong style="float:left; margin-right:10px;">Etapa del Juicio <a name="etapas">&nbsp;</a></strong>
		<?php if (validation_errors()!='' && (isset($_POST['enviar_etapa']) && $_POST['enviar_etapa']!='')): ?>
		<span class="alerta"></span><span class="error">Faltan campos por completar</span> 
		    <?php endif;?>
		    <span class="flechita"></span>
		<div class="clear"></div>
	</div>
	
	<div class="blq">
		<?php 
		$url_form='';
		if (!empty($id)){$url_form.='/'.$id;}
		if (!empty($param)){$url_form.='/'.$param;}
		?>
		
		<?php if($id !=''):?>
		
		<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-etapas'.$url_form.'#etapas'); ?>
		<div class="clear"></div>
		<div class="cont-form">
		    <label style="width: 140px; float: left">Procurador:</label>
		    <div class="clear"></div>
		    <?php echo form_dropdown('id_administrador', $procuradores, $id_procurador);?>
		    <div class="clear"></div>
		    <?php echo form_error('id_administrador','<span class="error">', '</span>');?>
		</div>
		
		<div class="cont-form">
		    <label for="obs_administrador" style="width: 140px; float: left">Obs Administrador:</label>
		    <div class="clear"></div>
		    <?php echo form_input('obs_administrador', $obs_administrador);?>
		    <?php echo form_error('obs_administrador','<span class="error">', '</span>');?>
		</div>  
		
		<div class="cont-form">
		    <label for="id_etapa" style="width: 140px; float: left">Etapa del Juicio:</label>
		    <div class="clear"></div>
		    <?php echo form_dropdown('id_etapa', $etapas, $id_etapa,'autocomplete="off" class="select_dos" data-id="'.$id.'" ');?>
		
		    <div class="clear"></div>
		    <?php echo form_error('id_etapa' ,'<span class="error">', '</span>');?>
		</div>
		
		<!-- BEGIN PETER -->
		<div class="cont-form">
		    <label for="id_etapa" style="width: 140px; float: left">&nbsp;</label>
		    <div class="clear"></div>
		    <select style="width:97%; display:none;" name="etapa_otro" class="otro_<?php echo $id?>">
				<option value="">Seleccionar</option>
			</select>
			<input type="hidden" name="id_cuenta" value="<?php echo $id?>">
		</div>   
		<!-- END PETER -->
		

		<span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>
		<div class="cont-form">
			<label for="fecha_etapa_year" style="width: 135px;">Fecha Etapa*:</label>
			<div class="clear"></div>
			<?php echo day_dropdown('fecha_etapa_day',$fecha_etapa_day).month_dropdown('fecha_etapa_month',$fecha_etapa_month).year_dropdown('fecha_etapa_year',$fecha_etapa_year,2010,date('Y')+10);?>
			<div class="clear"></div>
			<?php echo form_error('fecha_etapa_year','<span class="error">', '</span>');?>
		</div>
	
	<div class="clear height"></div>
	<input type="submit" name="enviar_etapa" value="Guardar Etapa" class="boton" style="width: 100%; float: left;">
	<div class="clear"></div>

<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function(){
	
	$( '.select_dos' ).each(function( index ) {
		var obj = $(this);
		var id = obj.val();

	        $.ajax({
	            type: 'post',
	            url: '<?php echo site_url()?>/admin/procurador/dropdown_ajax_etapa/'+obj.val(),
	            data: '',
	            success: function (data) {
	            	obj.html( data );
	            },
	            error: function(objeto, quepaso, otroobj) {
	            },
	        });
	});
	

	$('.select_dos,.select_lista').change(function(){
		var idotro = $(this).attr('data-id');
		
		if( $(this).val() == 'otro' || $(this).val() == '72' ){
			$.ajax({
	            type: 'post',
	            url: '<?php echo site_url()?>/admin/procurador/dropdown_ajax_etapa_otro/',
	            data: '',
	            success: function (data) {
	            	$( '.otro_'+idotro ).css( 'display','block' );
	                $( '.otro_'+idotro ).html( data );
	            },
	            error: function(objeto, quepaso, otroobj) {
	            },
	        });
		}else{
			$( '.otro_'+idotro ).val( '' );
			$( '.otro_'+idotro ).css( 'display','none' );
		}
	});

	return false;
           
});


$(document).on('submit','.form_table',function(){
	var idbox = $(this).attr('data-id');

    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            alert( data );
            $( '#text_etapa_'+idbox ).html( data );
        },
        error: function(objeto, quepaso, otroobj) {
        },
    });
    return false;
});
</script>
<?php endif;?>

<div >
<div class="dos" style="width:100%;">

	<?php $width='65%';if ($this->session->userdata('usuario_perfil')<=2){$width='100%';}?>
	<?php if (count($cuenta_etapas_listado)>0):?>
	<table class="listado" border="1" style="border:1px solid #CDCCCC; width:<?php echo $width;?>; border-collapse:collapse;">
	<tr class="titulos-tabla" height="23">
	    <td width="200px">Etapa</td>
	    <td>Obs Procurador</td>
	    <td>Obs Administrador</td>
	    <td>Fecha Etapa</td>
	    <td>Procurador</td>
	    <td>&nbsp;</td>
	</tr>
	<?php $cont=1;?>
		<?php foreach ($cuenta_etapas_listado as $key=>$val):?>
			<tr class="detalle-tabla" height="23">
			<?php if ($this->session->userdata('usuario_perfil')<=2):?>
					<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-etapas'.$url_form.'/'.$val->id.'#etapas'); ?>
			        <?php
			            $explode = explode ( '-', $val->fecha_etapa );
			            $fecha_etapa_day = $explode [2];
			            $fecha_etapa_month = $explode [1];
			            $fecha_etapa_year = $explode [0];
			        ?>
			        <td><?php echo form_dropdown('id_etapa', $etapas, $val->id_etapa,'autocomplete="off" class="select_lista" data-id="'.$cont.'" style="width:97%;" ');?>
			            <select style="width:97%; display:none;" name="etapa_otro" class="otro_<?php echo $cont?>">
							<option value="">Seleccionar</option>
						</select>
					<input type="hidden" name="id_cuenta" value="<?php echo $id?>">
					<input type="hidden" name="id" value="<?php echo $val->id?>">
			        </td>
			        
			        <td><?php echo $val->observaciones?></td>
			        <td><?php echo form_input('obs_administrador', $val->obs_administrador);?></td>
			        
			        <td><?php echo day_dropdown('fecha_etapa_day',$fecha_etapa_day).month_dropdown('fecha_etapa_month',$fecha_etapa_month).year_dropdown('fecha_etapa_year',$fecha_etapa_year,2010,date('Y')+10);?></td>
			        <td><?php $aux_id_administradores = ''; if (isset($val->id_administrador)){ $aux_id_administradores = $val->id_administrador;}echo form_dropdown('id_administrador', $procuradores, $aux_id_administradores);?>
			        <input type="submit" name="enviar_etapa" value="Modificar" class="boton" style="width: 80px; float: right; margin:0px 5px 5px;">
			        </td>
			        <td><a href="<?php echo site_url().'/admin/'.$current.'/form/eliminar-etapa'.$url_form.'/'.$val->id.'#etapas';?>">Eliminar</a></td>
			        </td>
			        <?php echo form_close();?>
		    <?php else:?>
			        <td><?php echo $val->etapa;?></td>
			        <td><?php echo date("d-m-Y", strtotime($val->fecha_etapa));?></td>
			        <td><?php echo $val->nombres.' '.$val->apellidos;?></td>
			        <td>&nbsp;</td>
			<?php endif;?>
		    </tr>
		    <?php $cont++;?>
		<?php endforeach;?>
	</table>
	<?php endif;?>
	<div class="clear"></div>
	<?php include APPPATH.'views/backend/templates/cuentas/volver.php';?>
	</div>
	<div class="clear"></div>
</div>

<?php ///////////////////////// INICIO HISTORIAL ETAPAS JUICIO ////////////////////////?>


<table class="listado" border="1" style="border:1px solid #CDCCCC; width:<?php echo $width;?>; border-collapse:collapse;">
	<tr class="titulos-tabla" height="23">
    	<?php if (count($historial_etapas_log)>0):?>
        <td colspan="5">
        <div style="float:right">
        <?php 
			$rango = '';if (isset($_REQUEST['rango'])){$rango = $_REQUEST['rango'];}
			echo form_open(site_url().'/admin/cuentas/exportar_log/'.$id); 
			echo form_label('Rango de fechas', 'rango',array('style'=>'width:150px'));
			echo form_input('rango', $rango,'id="rango" style="width:180px"');
			echo form_error('rango');
			echo form_submit(array('name' => 'Exportar', 'class' => 'boton','style'=>'width:100px'), 'Exportar a CSV');
			echo form_close();
		?>
        </div>
        </td>
        <?php endif;?>
    </tr>
    <tr class="titulos-tabla" height="23">
		<td>Usuario</td>
	    <td>Acción</td>
	    <td>Etapa Anterior</td>
	    <td>Etapa Nueva</td>
	    <td>Fecha Registro</td>
	</tr>
	<?php foreach ($historial_etapas_log as $key=>$val):?>
		<tr class="detalle-tabla" height="23">
			<td><?php echo $val->administradores_nombres;?></td>
	        <td><?php echo $val->operacion;?></td>
	        <td><?php echo $val->s_etapas_etapa;?></td>
	        <td><?php echo $val->etapa_nueva;?></td>
	        <td><?php echo date("d-m-Y H:i",strtotime($val->fecha) );?></td>
	    </tr>
	<?php endforeach;?>
</table>
<?php ///////////////////////// FIN HISTORIAL ETAPAS JUICIO ////////////////////////?>
		
	</div>
	
</div>

<div class="dos" style=" width:100%;">
<div class="titulo"><strong style="float:left; margin-right:10px;">Gastos<a name="gastos">&nbsp;</a></strong>
	<?php //if (($error['username']!='')or($error['password'])):?>
		<?php if (validation_errors()!='' && (isset($_POST['enviar_gastos']) && $_POST['enviar_gastos']!='')): ?>
			<span class="alerta"></span><span class="error">Faltan campos por completar</span>
		<?php endif;?>
	<?php //endif;?>
	<span class="flechita"></span>
	<div class="clear"></div>
</div>

<div class="blq">
    <div class="dos" style=" width:100%;">
	    <?php $url_form='';
	    if (!empty($id)){$url_form.='/'.$id;}
	    if (!empty($param)){$url_form.='/'.$param;}?>
	    <?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-gastos'.$url_form.'#gastos',' id="form_guardar-gastos"'); ?>
	    <div class="clear"></div>
	    
	<div class="cont-form">
        <label for="nombre_receptor" style="width: 135px;">Item Gasto*:</label>
        <div class="clear"></div>
        <?php echo form_dropdown('item_gasto_p', array( ''=>'Seleccionar','Receptor'=>'Receptor','otro'=>'Otro' ), '',' class="selector_tres" autocomplete="off" data-id="'.$id.'" ');?>
        <div class="clear"></div>
        <?php //echo form_error('nombre_receptor','<span class="error">', '</span>');?>
    </div>

  	<div class="cont-form selector_cuatro_box" style="display:none;" >
        <label for="nombre_receptor" style="width: 135px;">Diligencia*:</label>
        <div class="clear"></div>
        <?php echo form_dropdown('diligencia_p', array( ''=>'Seleccionar' ), '',' class="selector_cuatro" autocomplete="off" data-id="'.$id.'" id="diligencia_p" ');?>
        <div class="clear"></div>
        <?php //echo form_error('nombre_receptor','<span class="error">', '</span>');?>
    </div>
    
	    <div class="cont-form">
	        <label for="fecha_year" style="width: 135px;">Fecha *:</label>
	        <div class="clear"></div>
	        <?php echo day_dropdown('fecha_day',$fecha_day).month_dropdown('fecha_month',$fecha_month).year_dropdown('fecha_year',$fecha_year,2010,date('Y')+10);?>
	        <div class="clear"></div>
	        <?php echo form_error('fecha_year','<span class="error">', '</span>');?>
	    </div>
	    <div class="cont-form">
	        <label for="n_boleta" style="width: 135px;">Nº boleta*:</label>
	        <div class="clear"></div>
	        <?php echo form_input(array('name'=>'n_boleta','style'=>'width:135px'), $n_boleta);?>
	        <div class="clear"></div>
	        <?php echo form_error('n_boleta','<span class="error">', '</span>');?>
	    </div>
	    <div class="cont-form">
	        <label for="rut_receptor" style="width: 135px;">Rut Receptor*:</label>
	        <div class="clear"></div>
	        <?php echo form_input(array('name'=>'rut_receptor','style'=>'width:135px'), $rut_receptor);?>
	        <div class="clear"></div>
	        <?php echo form_error('rut_receptor','<span class="error">', '</span>');?>
	    </div>
	    
	    <div class="cont-form">
	        <label for="nombre_receptor" style="width: 135px;">Nombre Receptor*:</label>
	        <div class="clear"></div>
	        <?php echo form_input(array('name'=>'nombre_receptor','style'=>'width:170px'), $nombre_receptor);?>
	        <div class="clear"></div>
	        <?php echo form_error('nombre_receptor','<span class="error">', '</span>');?>
	    </div>
	    
	    <span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>
	    <div class="clear"></div>
	    <div class="cont-form">
	        <label for="monto" style="width: 135px;">Monto*:</label>
	        <div class="clear"></div>
	        <?php echo form_input(array('name'=>'monto'), $monto, 'style="width:86px; text-align:right;" id="monto_diligencia_p" ');?>
	        <div class="clear"></div>
	        <?php echo form_error('monto','<span class="error">', '</span>');?>
	        <input type="hidden" value="<?php echo ($monto_deuda-(int)$total_pagado->total)?>" name="monto_deuda_diligencia_p" id="monto_deuda_diligencia_p">
	    </div>
	    <div class="cont-form">
	        <label for="retencion" style="width: 135px;">Retención (10%)*:</label>
	        <div class="clear"></div>
	        <?php echo form_input( 'retencion', $retencion , 'style="width:86px; text-align:right;" id="retencion"');?>
	        <div class="clear"></div>
	        <?php echo form_error('retencion','<span class="error">', '</span>');?>
	    </div>
	    <div class="cont-form">
	        <label for="descripcion" style="width: 200px;">Descripción o Diligencia*:</label>
	        <div class="clear"></div>
	        <?php echo form_input(array('name'=>'descripcion','style'=>'width:445px'), $descripcion);?>
	        <div class="clear"></div>
	        <?php echo form_error('descripcion','<span class="error">', '</span>');?>
	    </div>
    </div>
    <div class="clear height"></div>
    <input type="submit" name="enviar_gastos" value="Guardar Gasto" class="boton" style="width: 100%; float: left;">
    <div class="clear"></div>

<?php echo form_close(); ?><?php $saldo_gastos=$monto_demandado; ?>
<?php if (count($gastos)>0):?>

<?php $width='70%';if ($this->session->userdata('usuario_perfil')<=2){$width='100%';}?>
<div class="dos" style="width:<?php echo $width;?>;">
	<table class="listado"  border="1" style="border:1px solid #CDCCCC; width:80%; border-collapse:collapse;">
		<tr class="titulos-tabla">
		    <td>Fecha</td>
		    <td>Nº Boleta</td>
		    <td>Rut Receptor</td>
		    <td>Nombre Receptor</td>
		    <td>Monto</td>
		    <td>Retención</td>
		    <td>Descripción</td>
		    <td>Saldo (<?php echo '$'.number_format($saldo_gastos,0,',','.');?>)</td>
		    <td>&nbsp;</td>
		</tr>
			<?php foreach ($gastos as $key=>$val):?>
			<?php   $saldo_gastos+=$val->monto;  ?>
			<tr class="detalle-tabla">
					<?php if ($this->session->userdata('usuario_perfil')<=2):?>
						<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-gastos'.$url_form.'/'.$val->id.'#gastos'); ?>
								    <?php
										$explode = explode ( '-', $val->fecha );
										$fecha_gasto_day = $explode [2];
										$fecha_gasto_month = $explode [1];
										$fecha_gasto_year = $explode [0];
									?>
					 	<td><input type="hidden" name="id_cuentas_gastos" value="<?php echo $val->id?>">
					 	<?php echo day_dropdown('fecha_day',$fecha_gasto_day).month_dropdown('fecha_month',$fecha_gasto_month).year_dropdown('fecha_year',$fecha_gasto_year,2010,date('Y')+10);?></td>
					    <td><?php echo form_input(array('name'=>'n_boleta','style'=>'width:50px;'), $val->n_boleta);?></td>
					    <td><?php echo form_input(array('name'=>'rut_receptor','style'=>'width:60px;'), $val->rut_receptor);?></td>
					    <td><?php echo form_input(array('name'=>'nombre_receptor','style'=>'width:130px;'), $val->nombre_receptor);?></td>
					    <td style="text-align:right;"><?php echo form_input(array('name'=>'monto','style'=>'width:50px;'), $val->monto);?></td>
					    <td style="text-align:right;"><?php echo form_input(array('name'=>'retencion','style'=>'width:50px;'), $val->retencion);?></td>
					    <td style="text-align:right;"><?php echo form_input(array('name'=>'descripcion','style'=>'width:130px;'), $val->descripcion);?></td>
					    <td style="text-align:right;"><?php echo '$'.number_format($saldo_gastos,0,',','.');?><input type="submit" name="enviar_gastos" value="Modificar" class="boton" style="width: 80px; float: right; margin:0px 5px 5px;"></td>
					    <td><a href="<?php echo site_url().'/admin/'.$current.'/form/eliminar-gasto'.$url_form.'/'.$val->id.'#gastos';?>">Eliminar</a></td>
					    <?php echo form_close();?>
					<?php else:?>
					    <td><?php echo date("d-m-Y", strtotime($val->fecha));?></td>
					    <td><?php echo $val->n_boleta;?></td>
					    <td><?php echo $val->rut_receptor;?></td>
					    <td><?php echo $val->nombre_receptor;?></td>
					    <td style="text-align:right;"><?php echo '$'.number_format($val->monto,0,',','.');?></td>
					    <td style="text-align:right;"><?php echo '$'.number_format($val->retencion,0,',','.');?></td>
					    <td style="text-align:right;"><?php echo $val->descripcion;?></td>
					    <td style="text-align:right;"><?php echo '$'.number_format($saldo_gastos,0,',','.');?></td>
					    <td>&nbsp;</td>
					<?php endif;?>
			</tr>
			<?php endforeach;?>
	</table>
</div>
<?php endif;?>
<?php include APPPATH.'views/backend/templates/cuentas/volver.php';?>
<?php endif;?>
</div>
</div>

<div class="dos" style=" width:100%;">
		<div>
		<form action="<?php echo site_url()?>/admin/doc/generardoc" method="post" >
			<div class="titulo">
				<strong style="float:left; margin-right:10px;">
					Generar Documentos
					<a name="generar_docuemntos_masivos"> </a>
				</strong>
				<span class="flechita"></span>
				<div class="clear"></div>
			</div> 
			
			<div class="blq">
			
			Documento:
			<?php echo form_dropdown('tipo_documento', $documentos_generar,'' )?>
			
	
			Fecha *:
			<input type="text" name="fecha" id="fechaaaa" data-date-format="dd-mm-yyyy"  value="<?php echo $this->input->post('fecha')?>">
			<input type="hidden" name="id_cuenta" value="<?php echo $id?>" >
		
			<div class="cont-form">
				<label style="width:135px!important;">Dejar en etapa De Juicio*:</label>
				<select name="id_etapa">
				<option value="">---</option>
				<?php foreach($etapas_generar as $key=>$val):?>
					<option value="<?php echo $val->id?>"><?php echo $val->codigo?> <?php echo $val->etapa?></option>
				<?php endforeach;?>
				</select>
				<div class="clear"></div>
				<span id="error_rut" class="error"></span>
			</div>
			
			<input type="submit" value="Generar">
			</div>
			</form>
			
			<script type="text/javascript">
				$(document).ready(function(){
					$('#fechaaaa').datepicker();
				});
			</script>
		</div>


	<div class="titulo">
		<strong style="float:left; margin-right:10px;">
			Documentos
			<a name="gastos"> </a>
		</strong>
		<span class="flechita"></span>
		<div class="clear"></div>
	</div>
	<div class="blq">
		<div class="agregar-noticia" >
			<div>
				<label style="margin-left: 23px; margin-top: 6px; float: left; width: 201px;">Etapa de juicio del documento: </label>
				
				<?php echo form_dropdown('id_etapa', $etapas, $id_etapa,'autocomplete="off" class="select_dos" data-id="upload" id="id_etapa" style="margin-left: 4px; float: left; margin-top: 17px;" ');?>
				
				<select class="otro_upload" id="otro_upload" name="otro_upload" style="display:none; float: left; margin-top: 17px;">
					<option value="">Seleccionar</option>
				</select>

				<input type="file" id="file5" name="file5" style="float:left;"/>
			</div>
			<div class="clear"></div>
		</div>
		
		<div id="documentos_all_table">
		<?php include APPPATH.'views/backend/templates/doc/table_doc.php'; ?>
		</div>
	</div>
</div>

<style>
.progress-striped .bar {
    background-color: #149BDF;
    background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
    background-size: 40px 40px;
}
.progress .bar {
    -moz-box-sizing: border-box;
    background-color: #0E90D2;
    background-repeat: repeat-x;
    box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.15) inset;
    color: #FFFFFF;
    float: left;
    font-size: 12px;
    height: 100%;
    text-align: center;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    transition: width 0.6s ease 0s;
    width: 0;
}
.alert-danger, .alert-error {
    background-color: #F2DEDE;
    border-color: #EED3D7;
    color: #B94A48;
	padding-left:15px;
}
.alert .close {
    line-height: 20px;
    position: relative;
    right: -21px;
    top: -2px;
	margin-right: 28px;
    margin-top: 9px;
	float:right;
}
button.close {
    background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 0 none;
    cursor: pointer;
    padding: 0;
}
.detalle-tabla:hover{
	background-color: #FFF;
}
.close {
    color: #000000;
    float: right;
    font-size: 20px;
    font-weight: bold;
    line-height: 20px;
    opacity: 0.2;
    text-shadow: 0 1px 0 #FFFFFF;
}
.pekecontainer{
	clear:both;
	padding-top: 10px;
}

.btn-upload{
	background-color: #E8E8E8;
    border: 1px solid #CDCCCC;
    float: left;
    height: 36px;
    margin-right: 10px;
    margin-top: 5px;
    padding: 0px 37px 4px 42px !important;
	background: url("<?php echo base_url()?>img/ico-more.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
}
</style>

<script type="text/javascript" src="<?php echo base_url()?>js/pekeUpload.js"></script>
<script type="text/javascript">
$(document).ready(function(){
      $("#file5").pekeUpload({theme:'bootstrap',btnText:"Subir Nuevo Documento.", allowedExtensions:"docx|doc|pdf|xlsx|xls",invalidExtError:"Tipo de archivo inválido.",showFilename:false,url:"<?php echo base_url()?>index.php/admin/doc/upload/<?php echo $id_cuenta?>", onFileSuccess:function(file,data){
        	setTimeout(function(){
        		$(".pekecontainer").fadeOut(function() {
            		$(this).html('').fadeIn();
        		});
        		reload_documentos();
        	}, 3000); 
      },onFileError:function(file,error){
        	setTimeout(function(){
        		$(".pekecontainer").fadeOut(function() {
            		$(this).html('').fadeIn();
        		});
        	}, 3000); 
      }});

      $(".btn-upload").css('marginTop','10px');
      $(".btn-upload").css('marginLeft','13px');
      $(".btn-upload").hide();

      $('#id_etapa').click(function(){
    	  etapa = $(this).val();
    	  if(etapa == ''){
    		  $(".btn-upload").hide();
    	  }else{
    		  $(".btn-upload").show();
    	  }    	  
      });
      
      $(document).on("change",".selector_tres",function(e){
	  	    $.ajax({
	            type: 'post',
	            url: '<?php echo base_url()?>index.php/admin/cuentas/get_diligencia/'+$(this).val(),
	            data: '',
	            success: function (data) {
	            	$(".selector_cuatro_box").show();
	                $(".selector_cuatro").html(data);
	            },
	            error: function(objeto, quepaso, otroobj) {
	            },
	   		});
      });
});

function reload_documentos(){
    $.ajax({
            type: 'post',
            url: '<?php echo base_url()?>index.php/admin/doc/reload_doc_procurador/<?php echo $id_cuenta?>',
            data: '',
            success: function (data) {
                $('#documentos_all_table').html(data);
            },
            error: function(objeto, quepaso, otroobj) {
            },
    });
}

$(document).ready(function(){
	$('.delete_doc').click(function(){ 
		$(this).html('Eliminando...');
		id_td = $(this).attr('data-id');
			$.ajax({
		        type: 'POST',
		        url: $(this).attr('href'),
		        data: '',
		        success: function (data) {
		        	var json_obj = $.parseJSON(data);
		    		if(json_obj.status == 'exito'){
		    			$("#doc_"+id_td).fadeOut();
		    		}else{
		    			$(this).html(json_obj.contenido);
		    		}
		        },
		        error: function(objeto, quepaso, otroobj) {
		        },
		    }); 
		return false;
	});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('#rango').daterangepicker();
	$.ajax({
		type: 'post',
		url: '<?php echo site_url()?>/admin/cuentas/tabla_intereses/<?php echo $id?>',
		data: $('#form_guardar-acuerdo').serialize(),
		success: function (data) {
			$('#lista_intereses').html(data);
		},
		error: function(objeto, quepaso, otroobj) {
		},
	});

});

$(document).on("change","select[name=fecha_primer_pago_day],select[name=fecha_primer_pago_month],select[name=fecha_primer_pago_year]",function(e){
	$.ajax({
		type: 'post',
		url: '<?php echo site_url()?>/admin/cuentas/tabla_intereses/<?php echo $id?>',
		data: $('#form_guardar-acuerdo').serialize(),
		success: function (data) {
			$('#lista_intereses').html(data);
		},
		error: function(objeto, quepaso, otroobj) {
		},
	});
}); 

$(document).on("change",".n_cuotas_intereses",function(e){
    var cuotas = $(this).val();
    $('.total_n_cuotas').each( function() {
        var box = $(this);
           $.ajax({
               type: 'post',
               url: '<?php echo site_url()?>/admin/cuentas/cal_cuotas/',
               data: 'cuotas='+cuotas+'&total='+box.attr('data-total'),
               success: function (data) {
                   box.html(data);
               },
               error: function(objeto, quepaso, otroobj) {
               },
           }); 
    });
    return false;
});

$(document).on("change","#diligencia_p",function(e){
	var id = $(this).val();
	var box = $(this);

	   $.ajax({
	       type: 'post',
	       url: '<?php echo site_url()?>/admin/cuentas/cal_diligencia/'+id,
	       data: $('#form_guardar-gastos').serialize(),
	       success: function (data) {
	    	   var json_obj = $.parseJSON(data);
	    	   
	       	   if(json_obj.status == 'exito'){
		       		$('#monto_diligencia_p').val(json_obj.monto_gasto);
		       		$('#retencion').val(json_obj.retencion);
	       	   }
	       },
	       error: function(objeto, quepaso, otroobj) {
	       },
	   });

    return false;
});

$(document).on("change","#intereses,.n_cuotas_intereses",function(e){
	$.ajax({
	       type: 'post',
	       url: '<?php echo site_url()?>/admin/cuentas/cal_interes/x_cuotas',
	       data: $('#form_guardar-acuerdo').serialize(),
	       success: function (data) {
				
				$('#valor_cuota').val(data);
				$('#valor_cuota_con_intereses').val(data);
				$('#valor_cuota_real').val(data);
	       },
	       error: function(objeto, quepaso, otroobj) {
	       },
	   });
});

$(document).on("keyup","#monto_pagado",function(e){
		var monto_pagado = parseInt($(this).val());
		var honorarios = 0; var monto_remitido = 0;
		if ($(this).val()!=''){honorarios = Math.round(monto_pagado*0.15,0);}
		if ($(this).val()!=''){monto_remitido = Math.round(monto_pagado*0.85,0);}
		$('input[name="honorarios"]').val(honorarios);
		$('input[name="monto_remitido"]').val(monto_remitido);
	});
$(document).on("change","#valor_cuota",function(e){
	var valor = $(this).val();
	
	$('#valor_cuota_con_intereses').val(valor);
	$('#valor_cuota_real').val(valor);
	
	$.ajax({
	       type: 'post',
	       url: '<?php echo site_url()?>/admin/cuentas/cal_interes/x_valor_cuota',
	       data: $('#form_guardar-acuerdo').serialize(),
	       success: function (data) {

				var json_obj = $.parseJSON(data);
				var cuotas = json_obj.n_cuotas;

				$('#n_cuotas_real').val( json_obj.n_cuotas_real );
				$('#n_cuotas_intereses').val(cuotas);
			    
				    $('.total_n_cuotas').each( function() {
				        var box = $(this);
				           $.ajax({
				               type: 'post',
				               url: '<?php echo site_url()?>/admin/cuentas/cal_cuotas/',
				               data: 'cuotas='+cuotas+'&total='+box.attr('data-total'),
				               success: function (data) {
				                   box.html(data);
				               },
				               error: function(objeto, quepaso, otroobj) {
				               },
				           }); 
				    });

							
	       },
	       error: function(objeto, quepaso, otroobj) {
	       },
	   });


    
});

</script>