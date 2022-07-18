<?php 
$id = '';
$id_usuario = '';
$id_mandante = '';
$id_procurador = '';
$id_tipo_producto = '';
$n_pagare = '';
$monto_deuda = '';
$fecha_asignacion_day = '0';//date ( 'd' );
$fecha_asignacion_month = '0';//date ( 'm' );
$fecha_asignacion_year = '0';//date ( 'Y' );
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
if($_POST){
	if (!empty($_POST ['id_usuario'])){ $id_usuario = $_POST ['id_usuario'];}
	if (!empty($_POST ['id_mandante'])){ $id_mandante = $_POST ['id_mandante'];}
	
	if (!empty($_POST ['id_procurador'])){ $id_procurador = $_POST ['id_procurador'];}
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
}
if (count($lists)>0){
	$id = $lists->id;
	$id_usuario = $lists->id_usuario;
	$id_mandante = $lists->id_mandante;
	
	$id_procurador = $lists->id_procurador;
	$id_tipo_producto = $lists->id_tipo_producto;
	$n_pagare = $lists->n_pagare;
	$monto_deuda = $lists->monto_deuda;
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
	
	$dia_vencimiento_cuota = $lists->dia_vencimiento_cuota;
	
	if ($lists->fecha_primer_pago!='0000-00-00'){
		$explode = explode ( '-', $lists->fecha_primer_pago );
		
		$fecha_primer_pago_day = $explode [2];
	
		$fecha_primer_pago_month = $explode [1];
	
		$fecha_primer_pago_year = $explode [0];
	}
}
//echo $fecha_primer_pago_day.'-'.$fecha_primer_pago_month.'-'.$fecha_primer_pago_year;
?>
<script type="text/javascript">
$(document).ready(function(){ 
	/*$("#categoria").keyup(function(){
		var ss=$(this).val();
		if (ss.length>70){$("#mrubro").html("error");}
		else{$("#mcategoria").html(70-(ss.length)+" caracteres");}
	});*/
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
<form action="<?php echo site_url().'/admin/'.$current.'/form/guardar/';?><?php if (!empty($id)){echo $id;}?>"
	method="post">
<?php //echo 'aa '.validation_errors().form_error('username').form_error('password').form_error('perfil');?>
    
<div class="titulo">
<strong style="float:left; margin-right:10px;">Datos de la cuenta</strong><a name="top">&nbsp;</a>
    <?php if (validation_errors()!='' && (isset($_POST['enviar_cuenta']) && $_POST['enviar_cuenta']!='')): ?>
<span class="alerta"></span><span class="error">Faltan campos por completar</span> 
    <?php endif;?>
    <span class="flechita"></span>
<div class="clear"></div>
</div>
<div class="blq">
<div class="dos" style=" width:100%;">
	<?php 
	$ultimo_pago = '';
	if (count($pagos)>0){
		$i=1;foreach ($pagos as $key=>$val){ 
			if ($i==1){ $ultimo_pago = date('d-m-Y',strtotime($val->fecha_pago));
			}
		}
	}
	?>
    <?php if ($id_estado_cuenta==1){ $color_bg = "#BDD95E"; $color = "#758B22"; $estado_msg = "VIGENTE"; }?>
    <?php if ($id_estado_cuenta==2){ $color_bg = "#999"; $color = "#666"; $estado_msg = "ABANDONADO"; }?>
    <?php if ($id_estado_cuenta==3){ $color_bg = "#1186B6"; $color = "#00365F"; $estado_msg = "SUSPENDIDO"; }?>
    <?php if ($id_estado_cuenta==4){ $color_bg = "#DD808C"; $color = "#9E0404"; $estado_msg = "TERMINADO"; }?>
    <?php if ($id_estado_cuenta==5){ $color_bg = "#B8A0BD"; $color = "#7A318D"; $estado_msg = "DEVUELTO"; }?>


    <div style="width:500px; height:25px; border:1px solid <?php echo $color;?>; padding:5px; background:<?php echo $color_bg;?>;">
    <span style="color:<?php echo $color;?>; font-size:18px; text-align:center; float:left; width:490px;">ESTADO DE LA CUENTA: <?php echo $estado_msg;?></span></div>
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
    <?php endif; /*nombres*/?>
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
    <div class="cont-form">
        <label>Dirección:</label>
        <div class="clear"></div>
        <p><?php echo $usuario->direccion;?></p>
    </div>
    <div class="cont-form">
        <label>Nº:</label>
        <div class="clear"></div>
        <p><?php echo $usuario->direccion_numero;?></p>
    </div>
    <div class="cont-form">
        <label>Dtp:</label>
        <div class="clear"></div>
        <p><?php echo $usuario->direccion_dpto;?></p>
    </div>
    <div class="cont-form">
        <label>Comuna:</label>
        <div class="clear"></div>
        <p><?php echo $comunas[$usuario->id_comuna];?></p>
    </div>
    <div class="cont-form">
        <label> Ciudad:</label>
        <div class="clear"></div>
        <p><?php echo $usuario->ciudad;?></p>
    </div>
    <div class="cont-form">
        <label>Teléfono:</label>
        <div class="clear"></div>
        <p><?php echo $usuario->telefono;?></p>
    </div>
    <div class="cont-form">
        <label>Celular:</label>
        <div class="clear"></div>
        <p><?php echo $usuario->celular;?></p>
    </div>
    <span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>  
    <div class="clear"></div>
    <?php endif; //id?>
    <div class="cont-form">
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
        <input id="n_pagare" name="n_pagare" type="text" value="<?php echo $n_pagare;?>" style="width: 150px;">
        <?php else: echo $n_pagare;?>
        <?php endif;?>
        <div class="clear"></div>
        <?php echo form_error('n_pagare','<span class="error">', '</span>');?>
    </div>
    <div class="cont-form">
        <label for="monto_deuda" style="width: 100px;">Monto Pagaré*:</label>
        <div class="clear"></div>
        <?php if ($monto_deuda=='' or $monto_deuda==0 or $this->session->userdata('usuario_perfil')<=2):?>
        <input id="monto_deuda" name="monto_deuda" type="text" value="<?php echo $monto_deuda;?>" style=" text-align:right; width: 86px;">
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
    <span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>  
    <div class="clear"></div>
    <div class="cont-form">
        <label for="id_tribunal">Juzgado*:</label>
        <div class="clear"></div>
        <?php if ($id_tribunal == '' or $id_tribunal == '0' or $this->session->userdata('usuario_perfil')<=2): ?>
        <?php echo form_dropdown('id_tribunal', $tribunales, $id_tribunal);?>
        <?php else: echo $tribunales[$id_tribunal];?>
        <?php endif;?>
    </div>
    <div class="cont-form">
        <label for="monto_deuda">Distrito*:</label>
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
        <label for="rol" style="width: 68px;">Rol*:</label> 
        <div class="clear"></div>
        <?php if ($rol == '' or $this->session->userdata('usuario_perfil')<=2):?>
        <input id="rol" name="rol" type="text" value="<?php echo $rol;?>" style="width: 150px;">
        <?php else: echo $rol;?>
        <?php endif;?>
        <?php echo form_error('rol','<span class="error">', '</span>');?>
    </div>
    <div class="cont-form">
        <label for="fecha_inicio_year" style="width: 135px;">Fecha de Inicio*:</label>
        <div class="clear"></div>
        <?php if ($fecha_inicio_day=='' or $fecha_inicio_month=='' or $fecha_inicio_year=='' or $id=='' or $this->session->userdata('usuario_perfil')<=2):?>
        <?php echo day_dropdown('fecha_inicio_day',$fecha_inicio_day).month_dropdown('fecha_inicio_month',$fecha_inicio_month).year_dropdown('fecha_inicio_year',$fecha_inicio_year,2010,date('Y')+10);?>
        <?php else: echo $fecha_inicio_day."-".$fecha_inicio_month."-".$fecha_inicio_year;?>
        <?php endif;?>
        <div class="clear"></div>
        <?php echo form_error('fecha_inicio_year','<span class="error">', '</span>');?>
    </div>
    <div class="cont-form">
        <label for="monto_demandado" style="width: 136px;">Saldo Deuda*:</label>
        <div class="clear"></div>
        
        <?php /*if ($monto_demandado=='' or $monto_demandado==0 or $this->session->userdata('usuario_perfil')<=2):?>
        <!--input id="monto_demandado" name="monto_demandado" type="text" value="<?php echo $monto_demandado;?>" style="width: 86px; text-align:right;"-->
        <?php else: ?>
        <?php endif;*/?>
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
    <div class="cont-form">
        <label for="bien_habitacional">Bienes</label>
        <div class="clear"></div>
        <div class="cont-form">
        <?php echo form_checkbox(array('name'=>'bien_habitacional','class'=>'check'), 1, $bien_habitacional);?> 									Habitacional
        </div>
        <div class="cont-form">
        <?php echo form_checkbox(array('name'=>'bien_vehiculo','class'=>'check'), 1, $bien_vehiculo);?> Vehículo
        </div>
    </div>
</div><!--dos-->
<div class="clear height"></div>
<input type="submit" name="enviar_cuenta" value="Guardar Datos de la cuenta" class="boton" style="width: 100%; float: left;">
<div class="clear"></div>
</div>
<!--blq-->
</form>


<?php //////////////historial////////////////////?>
<?php if (!empty($id)):?>
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
<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-acuerdo'.$url_form.'#pagos'); ?>
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
    <label for="n_cuotas" style="width: 150px;">Número de Cuotas*:</label>
    <div class="clear"></div>
    <?php echo form_input(array('name'=>'n_cuotas','style'=>'width:86px; text-align:right;'), $n_cuotas );?>
    <div class="clear"></div>
    <?php form_error('n_cuotas','<span class="error">', '</span>');?>
</div>
<div class="cont-form">
    <label for="valor_cuota" style="width: 150px;">Valor Cuota*:</label>
    <div class="clear"></div>
    <?php echo form_input(array('name'=>'valor_cuota','style'=>'width:100px; text-align:right;'), $valor_cuota );?>
    <div class="clear"></div>
    <?php form_error('valor_cuota','<span class="error">', '</span>');?>
</div>
<div class="cont-form">
<label for="dia_vencimiento_cuota" style="width: 150px;">Día de vencimiento*:</label>
<div class="clear"></div>
<?php echo day_dropdown('dia_vencimiento_cuota',$dia_vencimiento_cuota);?>
<div class="clear"></div>
<?= form_error('dia_vencimiento_cuota','<span class="error">', '</span>');?></div>
<div class="cont-form">
<label for="fecha_primer_pago_year" style="width: 135px;">Fecha Primer Pago*:</label>
<div class="clear"></div>
<?php echo day_dropdown('fecha_primer_pago_day',$fecha_primer_pago_day).month_dropdown('fecha_primer_pago_month',$fecha_primer_pago_month).year_dropdown('fecha_primer_pago_year',$fecha_primer_pago_year,2010,date('Y')+10);?>
<div class="clear"></div>
<?= form_error('fecha_primer_pago_year','<span class="error">', '</span>');?>
</div>
<input type="submit" name="enviar_pago" value="Guardar Acuerdo Pago" class="boton" style="width: 200px;; float: left;">
<?php echo form_close();?>
<div class="clear height">
</div><span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>
<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-pagos'.$url_form.'#pagos'); ?>
<div class="clear"></div>
<div class="cont-form">
    <label for="fecha_pago_year" style="width: 135px;">Fecha Pago*:</label>
    <div class="clear"></div>
    <?php echo day_dropdown('fecha_pago_day',$fecha_pago_day).month_dropdown('fecha_pago_month',$fecha_pago_month).year_dropdown('fecha_pago_year',$fecha_pago_year,2010,date('Y')+10);?>
    <div class="clear"></div>
    <?= form_error('fecha_pago_year','<span class="error">', '</span>');?>
</div>
<div class="cont-form">
    <label for="monto_pagado" style="width: 100px;">Monto Pagado*:</label>
    <div class="clear"></div>
    <?php echo form_input(array('name'=>'monto_pagado','style'=>'width:86px; text-align:right;'), '');?>
    <div class="clear"></div>
    <?= form_error('monto_pagado','<span class="error">', '</span>');?>
</div>
<div class="cont-form">
    <label for="honorarios" style="width: 135px;">Honorarios*:</label>
    <div class="clear"></div>
    <?php echo form_input(array('name'=>'honorarios','style'=>'width:86px; text-align:right;'), '');?>
    <div class="clear"></div>
    <?= form_error('horarios','<span class="error">', '</span>');?>
    </div>

<div class="cont-form">
    <label for="monto_remitido" style="width: 135px;">Monto Remitido*:</label>
    <div class="clear"></div>
    <?php echo form_input(array('name'=>'monto_remitido','style'=>'width:86px; text-align:right;'), '');?>
    <div class="clear"></div>
    <?= form_error('monto_remitido','<span class="error">', '</span>');?>
</div>
<div class="cont-form">
    <label for="n_comprobante_interno" style="width: 175px;">Nº Comprobante Interno*:</label>
    <div class="clear"></div>
    <?php echo form_input(array('name'=>'n_comprobante_interno','style'=>'width:86px; text-align:right;'), '');?>
    <div class="clear"></div>
    <?= form_error('n_comprobante_interno','<span class="error">', '</span>');?>
    </div>
</div>
<div class="cont-form">
    <label for="forma_pago" style="width: 135px;">Forma de Pago*:</label>
    <div class="clear"></div>
    <?php echo form_dropdown('forma_pago',$forma_pagos);?>
    <div class="clear"></div>
    <?= form_error('forma_pago','<span class="error">', '</span>');?>
</div>
<div class="clear height"></div>
<input type="submit" name="enviar_pago" value="Guardar Pago" class="boton" style="width: 100%; float: left;">
<div class="clear"></div>
</div>
<?php endif;?>
<?php echo form_close(); ?>
<div class="blq">
<?php $width='70%';if ($this->session->userdata('usuario_perfil')<=2){$width='90%';}?>
<div class="dos" style=" width:<?php echo $width;?>;">
<?php if (count($pagos)>0 or $id_acuerdo_pago==2):?>
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
		if ($id_acuerdo_pago == 2){
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
    <?php $saldo_pagos=$monto_deuda; $i=0;foreach ($pagos as $key=>$val): ?>
    <?php   $saldo_pagos-=$val->monto_remitido; ?>
    
    <?php if ($this->session->userdata('usuario_perfil')<=2):?>
		
        <?php
            $explode = explode ( '-', $val->fecha_pago );
            $fecha_pago_day = $explode [2];
            $fecha_pago_month = $explode [1];
            $fecha_pago_year = $explode [0];
        ?>
        <tr class="detalle-tabla">
        <?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-pagos'.$url_form.'/'.$val->id.'#pagos'); ?>
        <td><?php if (isset($fecha_vencimiento)){echo $fecha_vencimiento[$i];}else{echo '&nbsp;';}?></td>
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
        <td><?php if (isset($fecha_vencimiento)){echo $fecha_vencimiento[$i];}?></td>
        <td>&nbsp;</td>
        <td style="text-align:right;">&nbsp;</td>
        <td style="text-align:right;">&nbsp;</td>
        <td style="text-align:right;">&nbsp;</td>
        <td style="text-align:right;">&nbsp;</td>
        <td style="text-align:right;">&nbsp;</td>
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
<div class="titulo">
<strong style="float:left; margin-right:10px;">Observaciones / Historial<a name="historial">&nbsp;</a></strong>
<?php if (validation_errors()!='' && (isset($_POST['enviar_historial']) && $_POST['enviar_historial']!='')): ?>
<span class="alerta"></span><span class="error">Faltan campos por completar</span> 
    <?php endif;?>
    <span class="flechita"></span>
<div class="clear"></div>
</div>
<div class="blq">
<div class="dos" style=" width:100%;">
<?php 
$url_form='';
if (!empty($id)){$url_form.='/'.$id;}
if (!empty($param)){$url_form.='/'.$param;}
?>
<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-historial'.$url_form.'#historial'); ?>
<div class="clear"></div>
<div class="cont-form">
    <label for="historial" style="width: 135px;">Observaciones*:</label>
    <div class="clear"></div>
    <?php echo form_textarea(array('name'=>'historial','style'=>'height:80px'), '');?>
    <div class="clear"></div>
    <?= form_error('historial','<span class="error">', '</span>');?>
</div>

</div>
<div class="clear height"></div>
<input type="submit" name="enviar_etapa" value="Guardar Historial" class="boton" style="width: 100%; float: left;">
<div class="clear"></div>
</div>
<?php echo form_close(); ?>
<?php endif;?>
<div class="blq">
<div class="dos" style=" width:70%;">
<?php if (count($historiales)>0):?>
<table class="listado" border="1" style="border:1px solid #CDCCCC; width:65%; border-collapse:collapse;">
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
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>

<?php /////////////////juicio////////////////?>
<?php if (!empty($id)):?>

<div class="titulo">
<strong style="float:left; margin-right:10px;">Etapa del Juicio<a name="etapas">&nbsp;</a></strong>
<?php if (validation_errors()!='' && (isset($_POST['enviar_etapa']) && $_POST['enviar_etapa']!='')): ?>
<span class="alerta"></span><span class="error">Faltan campos por completar</span> 
    <?php endif;?>
    <span class="flechita"></span>
<div class="clear"></div>
</div>
<div class="blq">
<div class="dos" style=" width:100%;">
<?php 
$url_form='';
if (!empty($id)){$url_form.='/'.$id;}
if (!empty($param)){$url_form.='/'.$param;}
?>
<?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-etapas'.$url_form.'#etapas'); ?>
<div class="clear"></div>
<div class="cont-form">
    <label style="width: 140px; float: left">Procurador:</label>
    <div class="clear"></div>
    <?php echo form_dropdown('id_administrador', $procuradores, $id_procurador);?>
    <div class="clear"></div>
    <?= form_error('id_administrador','<span class="error">', '</span>');?>
</div>
<div class="cont-form">
    <label for="id_etapa" style="width: 140px; float: left">Etapa del Juicio:</label>
    <div class="clear"></div>
    <?php echo form_dropdown('id_etapa', $etapas, $id_etapa);?>
    <div class="clear"></div>
    <?= form_error('id_etapa' ,'<span class="error">', '</span>');?>
</div>
<span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>
<div class="cont-form">
<label for="fecha_etapa_year" style="width: 135px;">Fecha Etapa*:</label>
<div class="clear"></div>
<?php echo day_dropdown('fecha_etapa_day',$fecha_etapa_day).month_dropdown('fecha_etapa_month',$fecha_etapa_month).year_dropdown('fecha_etapa_year',$fecha_etapa_year,2010,date('Y')+10);?>
<div class="clear"></div>
<?= form_error('fecha_etapa_year','<span class="error">', '</span>');?>
</div>
</div>
<div class="clear height"></div>
<input type="submit" name="enviar_etapa" value="Guardar Etapa" class="boton" style="width: 100%; float: left;">
<div class="clear"></div>
</div>
<?php echo form_close(); ?>
<div class="blq">
<div class="dos" style=" width:70%;">
<?php $width='65%';if ($this->session->userdata('usuario_perfil')<=2){$width='100%';}?>
<?php if (count($cuenta_etapas_listado)>0):?>
<table class="listado" border="1" style="border:1px solid #CDCCCC; width:<?php echo $width;?>; border-collapse:collapse;">
<tr class="titulos-tabla" height="23">
    <td>Etapa</td>
    <td>Fecha Etapa</td>
    <td>Procurador</td>
    <td>&nbsp;</td>
</tr>
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
        <td><?php echo form_dropdown('id_etapa', $etapas, $val->id_etapa);?></td>
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
<?php endforeach;?></table>
<?php endif;?>
<div class="clear"></div>
<?php include APPPATH.'views/backend/templates/cuentas/volver.php';?>
</div>
<div class="clear"></div>
</div>

<div class="titulo"><strong style="float:left; margin-right:10px;">Gastos<a name="gastos">&nbsp;</a></strong>
<?php //if (($error['username']!='')or($error['password'])):?>
<?php if (validation_errors()!='' && (isset($_POST['enviar_gastos']) && $_POST['enviar_gastos']!='')): ?>
<span class="alerta"></span><span class="error">Faltan campos por completar</span> <?php endif;?>
<?php //endif;?>
<span class="flechita"></span>
<div class="clear"></div>
</div>
<div class="blq">
    <div class="dos" style=" width:100%;">
    <?php $url_form='';
    if (!empty($id)){$url_form.='/'.$id;}
    if (!empty($param)){$url_form.='/'.$param;}?>
    <?php echo form_open(site_url().'/admin/'.$current.'/form/guardar-gastos'.$url_form.'#gastos'); ?>
    <div class="clear"></div>
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
        <?= form_error('nombre_receptor','<span class="error">', '</span>');?>
    </div>
    <span style=" float:left; border-top:1px dotted #cccccc; width:100%; margin-bottom:10px;"></span>
    <div class="clear"></div>
    <div class="cont-form">
        <label for="monto" style="width: 135px;">Monto*:</label>
        <div class="clear"></div>
        <?php echo form_input(array('name'=>'monto','style'=>'width:86px; text-align:right;'), $monto);?>
        <div class="clear"></div>
        <?= form_error('monto','<span class="error">', '</span>');?>
    </div>
    <div class="cont-form">
        <label for="retencion" style="width: 135px;">Retención (10%)*:</label>
        <div class="clear"></div>
        <?php echo form_input(array('name'=>'retencion','style'=>'width:86px; text-align:right;'), $retencion);?>
        <div class="clear"></div>
        <?= form_error('retencion','<span class="error">', '</span>');?>
    </div>
    <div class="cont-form">
        <label for="descripcion" style="width: 200px;">Descripción o Diligencia*:</label>
        <div class="clear"></div>
        <?php echo form_input(array('name'=>'descripcion','style'=>'width:445px'), $descripcion);?>
        <div class="clear"></div>
        <?= form_error('descripcion','<span class="error">', '</span>');?>
    </div>
    </div>
    <div class="clear height"></div>
    <input type="submit" name="enviar_gastos" value="Guardar Gasto" class="boton" style="width: 100%; float: left;">
    <div class="clear"></div>
</div>
<?php echo form_close(); ?><?php $saldo_gastos=$monto_demandado; if (count($gastos)>0):?>
<div class="blq">
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
 	<td><?php echo day_dropdown('fecha_day',$fecha_gasto_day).month_dropdown('fecha_month',$fecha_gasto_month).year_dropdown('fecha_year',$fecha_gasto_year,2010,date('Y')+10);?></td>
    <td><?php echo form_input(array('name'=>'n_boleta','style'=>'width:50px;'), $val->n_boleta);?></td>
    <td><?php echo form_input(array('name'=>'rut_receptor','style'=>'width:60px;'), $val->rut_receptor);?></td>
    <td><?php echo form_input(array('name'=>'nombre_receptor','style'=>'width:130px;'), $val->nombre_receptor);?></td>
    <td style="text-align:right;"><?php echo form_input(array('name'=>'monto','style'=>'width:50px;'), $val->monto);?></td>
    <td style="text-align:right;"><?php echo form_input(array('name'=>'retencion','style'=>'width:50px;'), $val->retencion);?></td>
    <td style="text-align:right;"><?php echo form_input(array('name'=>'descripcion','style'=>'width:130px;'), $val->descripcion);?></td>
    <td style="text-align:right;"><?php echo '$'.number_format($saldo_gastos,0,',','.');?><input type="submit" name="enviar_gastos" value="Modificar" class="boton" style="width: 80px; float: right; margin:0px 5px 5px;"></td>
     <td><a href="<?php echo site_url().'/admin/'.$current.'/form/eliminar-gasto'.$url_form.'/'.$val->id.'#gastos';?>">Eliminar</a></td>
        </td>
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
<?php endif;?>
<?php include APPPATH.'views/backend/templates/cuentas/volver.php';?>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
<?php endif;?>

<div class="titulo">
<strong style="float:left; margin-right:10px;">
Documentos
<a name="gastos"> </a>
</strong>
<span class="flechita"></span>
<div class="clear"></div>
</div>

<div class="agregar-noticia" >
	<div>
	<label style="margin-left: 23px; margin-top: 6px; float: left;">Etapa de juicio del documento: </label>
		<select id="id_etapa" name="id_etapa" style="margin-left: 4px; float: left; margin-top: 17px;" autocomplete="off">
		<option value="">-----</option>
		<?php foreach($etapas_upload as $key=>$val):?>
			<option value="<?php echo $val->id?>"><?php echo $val->codigo?> <?php echo $val->etapa?></option>
		<?php endforeach;?>
		</select>
		
		<input type="file" id="file5" name="file5" />

		
	</div>
	
	
<div class="clear"></div>
</div>


<div class="blq" id="documentos_all_table">

<?php include APPPATH.'views/backend/templates/doc/table_doc.php'; ?>

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
        		reload_documentos()
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
          
      
    });

function reload_documentos(){
    $.ajax({
            type: 'post',
            url: '<?php echo base_url()?>index.php/admin/doc/reload_doc/<?php echo $id_cuenta?>',
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