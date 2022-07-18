<style>
#mask{position:absolute;background:rgba(0,0,0,.3);display:none;height:100%;width:100%;left:0;top:0;z-index:999998}
.preloader {
  width: 90px;
  height: 90px;
  margin: 25% auto;
  border: 10px solid #eee;
  border-top: 10px solid #666;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 1s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
<div id="mask"><div class="preloader"></div></div>
<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Causas (<?php echo number_format($total,0,',','.');?>)</strong></h2>
  </div>
</div>
<div class="agregar-noticia">
	<div width="20%" class="agregar">
      <a id="limpiarbd" class="nueva" href="#">LIMPIAR BD</a>
    </div>
	<?php if($nuevo){ ?>
      <?php if(intval($total) == 0 || !$todos){ ?>
    <div width="20%" class="agregar">
    	<a class="nueva" href="<?php echo site_url();?>/admin/roles/revisar_cuentas/">CONSULTAR BASES</a>
    </div>
	<?php } ?>
	<?php if(intval($total) > 0){ ?>
    <div width="20%" class="agregar">
    	<a id="superir" class="nueva" href="#">CRUCE PJUD</a>
    </div>
    <div width="20%" class="agregar">
    	<a class="distrib nueva" href="#">DISTRIBUIR</a>
    </div>
	<?php } ?>
    <?php if(count($no_seleccionados) > 0){ ?>
    <div width="20%" class="agregar">
    	<a class="nueva" href="<?php echo site_url();?>/admin/roles/descargar/">NO SELECCIONADOS</a>
    </div>
    <?php } ?>
    <?php } ?><br/>
	<table id="sistemas" style="line-height:25px;margin-top:20px;">
		<thead>
			<tr>
				<th style="padding:0 10px;">Sistemas</th>
				<th style="padding:0 10px;">Total</th>
				<th style="padding:0 10px;">Total C</th>
				<th style="padding:0 10px;">Total Exhorto</th>
				<th style="padding:0 10px;">Cruce PEJUD C</th>
				<th style="padding:0 10px;">N / A - C</th>
				<th style="padding:0 10px;">Cruce PEJUD Exhorto</th>
				<th style="padding:0 10px;">N / A Exhorto</th>
				<th style="padding:0 10px;">Distribuidos</th>
			
			</tr>
		</thead>
		<tbody>
			<?php
			//$treg = $timp = $elim = $tnet = $tna = $tad = $tmatch = $selecc = $distrib = 0;
			foreach ($sistemas as $key => $value){
				//$sistema = $value->sistema;
				$total_registros =0;
				if($value->sistema =="cae-scotia"){
				   $sistema="CAE SCOTIA";
				   $total_registros = $total_rows_cae_scotia;
				}
				else if($value->sistema =="cae-itau"){
					$sistema="CAE ITAU";
					$total_registros = $total_rows_cae;
				}
				else if($value->sistema =="cat"){
					$sistema="CAT + PROPIAS";
					$total_registros = $total_rows_cat;
				}
				else if($value->sistema =="sup"){
					$sistema="SUPERIR";
					$total_registros = $total_rows_sup;
				}
				$distribuir = $total_import - $elimin + $total_na;
				$netos = $total_import - $elimin;
					
				echo "<tr id='row-{$value->sistema}'><td>{$sistema}: </td>";
				echo "<td style='text-align:center;'>{$total_registros}</td>";
				echo "<td style='text-align:center;'>{$value->total_registros}</td>";
				echo "<td style='text-align:center;'>{$value->total_registros_E}</td>";
				echo "<td style='text-align:center;'>{$value->total_match}</td>";
				echo "<td style='text-align:center;'>{$value->total_na}</td>";
				echo "<td style='text-align:center;'>{$value->total_match_E}</td>";
				echo "<td style='text-align:center;'>{$value->total_na_E}</td>";
				echo "<td style='text-align:center;'>{$value->distribuidos}</td></tr>";
				$treg_bases += intval($total_registros);
				$treg += intval($value->total_registros);
				$treg_E += intval($value->total_registros_E);
				$tmatch += intval($value->total_match);
				$tmatchE += intval($value->total_match_E);
				$tna += intval($value->total_na);
				$tnaE += intval($value->total_na_E);
				$selecc += intval($value->distribuidos);
			}
			?>
			<tr>
              <td style="font-weight:bold;">TOTALES</td>
			  <td style='text-align:center;font-weight:bold;'><?php echo $treg_bases; ?></td>
              <td style='text-align:center;font-weight:bold;'><?php echo $treg; ?></td>
			  <td style='text-align:center;font-weight:bold;'><?php echo $treg_E; ?></td>
              <td style='text-align:center;font-weight:bold;'><?php echo $tmatch; ?></td>
			  <td style='text-align:center;font-weight:bold;'><?php echo $tna; ?></td>
			  <td style='text-align:center;font-weight:bold;'><?php echo $tmatchE; ?></td>  
			  <td style='text-align:center;font-weight:bold;'><?php echo $tnaE; ?></td>
              <td style='text-align:center;font-weight:bold;'><?php echo $selecc; ?></td>
            </tr>
            <tr><td colspan="8"><div style="padding:8px;"></div></td></tr>
            <tr><td colspan="8"></td></tr>
            <tr><td colspan="8"></td></tr>
            <tr>
            	<td style="font-size:12px;">CAUSAS RESERVADAS:</td>
            	<td style='text-align:center;font-size:12px;'><?php echo $reservadas; ?></td>
            	<td style="font-size:12px;">TOTAL ANALIZADAS:</td>
            	<td style='text-align:center;font-size:12px;'><?php echo $tad; ?></td>
            	<td></td>
            	<td></td>
            	<td></td>
            	<td></td>
            </tr>
			<tr>
            	<td style="font-size:12px;">CAUSAS ERROR PEJUD:</td>
            	<td style='text-align:center;font-size:12px;'><?php echo $no_encontradas; ?></td>
            	<td></td>
            	<td></td>
            	<td></td>
            	<td></td>
            	<td></td>
            	<td></td>
            </tr>
		</tbody>
	</table>
	<div class="clear">
	<?php if(count($lists) > 0){ $val = array(); 
		foreach ($lists as $k => $v){ if ($v->fecha!='' && $v->fecha!='0000-00-00'){ $val = $v; break;}} ?>
		<div style="font-weight:bold;font-size:12px;">FECHA TRÁMITE: <?php echo date("d-m-Y", strtotime($val->fecha)); ?></div>
		<div style="font-weight:bold;font-size:12px;left:0;margin-top:-20px;">FECHA GESTIÓN: <?php echo date("d-m-Y"); ?></div>
		<div>Total correos a enviar: <?php echo ($enviados + $no_enviados); ?></div>
		<div>Email enviados: <?php echo $enviados; ?> Total Causas enviados: <?php echo $causa_enviada; ?></div>
		<div>Email No enviados: <?php echo $no_enviados; ?> Total Causas No enviados: <?php echo $causas_no_enviada; ?></div>
	<?php } ?>
	</div>
<div class="">
<?php

$reload = 0; if (isset($_REQUEST['reload'])){$reload = $_REQUEST['reload'];}
$rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
$nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];}
$ap_pat = ''; if (isset($_REQUEST['ap_pat'])){$ap_pat = $_REQUEST['ap_pat'];}
$id_abogado = ''; if (isset($_REQUEST['id_abogado'])){$id_abogado = $_REQUEST['id_abogado'];}
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
$bienes = ''; if (isset($_REQUEST['bienes'])){$bienes = $_REQUEST['bienes'];}
$rut_parcial = ''; if (isset($_REQUEST['rut_parcial'])){$rut_parcial = $_REQUEST['rut_parcial'];}
$id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta']; } 
$id_distrito = ''; if (isset($_REQUEST['id_distrito'])){$id_distrito = $_REQUEST['id_distrito'];}
$id_tribunal= ''; if (isset($_REQUEST['id_tribunal'])){$id_tribunal = $_REQUEST['id_tribunal'];}
$rol= ''; if (isset($_REQUEST['rol'])){$rol = $_REQUEST['rol'];}
$id_etapa = ''; if (isset($_REQUEST['id_etapa'])){$id_etapa = $_REQUEST['id_etapa'];}
//$id_comuna= ''; if (isset($_REQUEST['id_comuna'])){$id_comuna = $_REQUEST['id_comuna'];}
$nombre = ''; if (isset($_REQUEST['nombre'])){$nombre = $_REQUEST['nombre'];}

$fecha_asignacion_pagare = ''; if (isset($_REQUEST['fecha_asignacion_pagare'])){$fecha_asignacion_pagare = $_REQUEST['fecha_asignacion_pagare'];} 
$diferencia = ''; if (isset($_REQUEST['diferencia'])){$diferencia = $_REQUEST['diferencia'];} 
$tribunal = ''; if (isset($_REQUEST['tribunal'])){$tribunal = $_REQUEST['tribunal'];} 
$tribunalE = ''; if (isset($_REQUEST['tribunalE'])){$tribunalE = $_REQUEST['tribunalE'];} 
$operacion = ''; if (isset($_REQUEST['operacion'])){$operacion = $_REQUEST['operacion'];}  
$rolE = ''; if (isset($_REQUEST['rolE'])){$rolE = $_REQUEST['rolE'];}  
$id_distritoE = ''; if (isset($_REQUEST['id_distritoE'])){$id_distritoE = $_REQUEST['id_distritoE'];}  

$id_distritoE = ''; if (isset($_REQUEST['id_distritoE'])){$id_distritoE = $_REQUEST['id_distritoE'];}  

$id_tribunalE = ''; if (isset($_REQUEST['id_tribunalE'])){$id_tribunalE = $_REQUEST['id_tribunalE'];}

echo form_open(site_url().'/admin/'.$current.'/',array('id' => 'form_reg'));

?>

</div><!-- campo -->

<?php
if($current == 'hist_cuentas'){
?>
<a href="<?php echo site_url();?>/admin/hist_cuentas/reporte/estados/exportar<?php echo $suffix;?>" class="ico-excel">Exportar a CSV</a>
<?php } ?>
<div class="clear height"></div>
<input type="hidden" id="site_url" value="<?php echo site_url();?>">
<form id="form_reload" action="<?php echo site_url();?>/admin/roles/" method="post">
	<input type="hidden" id="reload" value="<?php echo $reload; ?>">
</form>
</div>

<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">
<div class="content_tabla">
 <?php include APPPATH.'views/backend/templates/cuentas/roles_list_tabla.php';?>
</div>

<?php $colspan=2; //$this->load->view('backend/templates/mod/multiselect');?>

</div>

<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>
<?php if($nodo->nombre == 'fullpay'):?>
<script type="text/javascript">
$(document).ready(function(){
	$("#rut").Rut({
		on_error: function(){ alert('El R.U.T. es incorrecto. Formato: 11.111.111-1'); $("#rut").val('');  } 
	});
 
	$("#limpiarbd").click(function(e){
		e.preventDefault();
		$("#mask").css({"display":"block", "height": $(document).height()});
		actionData = { }
		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  data: actionData,
		  url: $('#site_url').val()+'/admin/roles/limpiarbd/',
		  success: function(response){
			$("#mask").css({"display":"none"});
			var d = response.result;
			if(d==1){
			  location.reload();
			}
		  }
		});
	});

 	$("#superir").click(function(e){
		e.preventDefault();
		var total = parseInt($("#sistemas tbody").find('#row-sup').find('td:nth-child(3)').text());
		//total = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(3)').text());
		//alert(total);
		cruce_pjud('sup', 0, total);
	});

  	$("a.distrib").click(function(e){
  		e.preventDefault();
      $("#mask").css({"display":"block", "height": $(document).height()});
      actionData = { }
      $.ajax({
        type: "POST",
        dataType: 'json',
        data: actionData,
        url: $('#site_url').val()+'/admin/roles/distribuir/',
        success: function(response){
            $("#mask").css({"display":"none"});
            var d = response.result;
          if(d==1){
            location.reload();
          }
        }
      });
  	});
}); 

var passed = false;
var ini_c = ine_e = 0;
function cruce_pjud(value, i, total){ //alert(value);
	console.log(total);
	$("#mask").css({"display":"block", "height": $(document).height()});
	var count = Math.floor(parseInt(total)/100) + 1;
	//alert(total);
	//for(j=i;j<count;j++){
	ini_c += i * 100;
	var fin  = ini_c + 100;
	actionData = { 'sistema': value }
	$.ajax({
	  type: "POST",
	  dataType: 'json',
	  data: actionData,
	  url: $('#site_url').val()+'/admin/roles/cruce_pjud/',
	  success: function(response){
		$("#mask").css({"display":"none"});
		var d = response.result;
		if(d==1){
			//  location.reload();
			console.log(passed);
			if(value=="cae-itau"){
				value="CAE ITAU";
				if(i+1 == count){
					var total_e = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(4)').text());
					setTimeout(function(){
						cruce_pjud_e("cae-itau", 0, total_e);
					}, 5000);
				}
				else{
					var ini = i+1;
					var tot = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(3)').text());
					setTimeout(function(){
						cruce_pjud("cae-itau", ini, tot);
					}, 5000);
				}
			}
			else if(value=="cae-scotia"){
				value="CAE SCOTIABANK";
				if(i+1 == count){
					if(passed == false){
						var total_e = parseInt($("#sistemas tbody").find('#row-cae-scotia').find('td:nth-child(4)').text());
						setTimeout(function(){
							passed = true;
							cruce_pjud_e("cae-scotia", 0, total_e);
						}, 5000);
					}
					else{
						var tot = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(3)').text());
						setTimeout(function(){
							passed = false;
							cruce_pjud("cae-itau", 0, tot);
						}, 5000);
					}
				}
				else{
					var ini = i+1;
					var tot = parseInt($("#sistemas tbody").find('#row-cae-scotia').find('td:nth-child(3)').text());
					setTimeout(function(){
						cruce_pjud("cae-scotia", ini, tot);
					}, 5000);
				}
			}
			else if(value=="cat"){
				value="CAT";
				if(i+1 == count){
					if(passed == false){
						var total_e = parseInt($("#sistemas tbody").find('#row-cat').find('td:nth-child(4)').text());
						setTimeout(function(){
							passed = true;
							cruce_pjud_e("cat", 0, total_e);
						}, 5000);
					}
					else{
						var tot = parseInt($("#sistemas tbody").find('#row-cae-scotia').find('td:nth-child(3)').text());
						setTimeout(function(){
							passed = false;
							cruce_pjud("cae-scotia", 0, tot);
						}, 5000);
					}
				}
				else{
					var ini = i+1;
					var tot = parseInt($("#sistemas tbody").find('#row-cat').find('td:nth-child(3)').text());
					setTimeout(function(){	
						cruce_pjud("cat", ini, tot);
					}, 5000);
				}
			}
			else if(value=="sup"){
				value="SUPERIR";
				if(i+1 == count){
					if(passed == false){
						var total_e = parseInt($("#sistemas tbody").find('#row-sup').find('td:nth-child(4)').text());
						setTimeout(function(){
							passed = true;
							cruce_pjud_e("sup", 0, total_e);
						}, 5000);
					}
					else{	
						var tot = parseInt($("#sistemas tbody").find('#row-cat').find('td:nth-child(3)').text());
						setTimeout(function(){
							passed = false;
							cruce_pjud("cat", 0, tot);
						}, 5000);
					}
				}
				else{
					var ini = i+1;
					var tot = parseInt($("#sistemas tbody").find('#row-sup').find('td:nth-child(3)').text());
					setTimeout(function(){
						cruce_pjud("sup", ini, tot);
					}, 5000);
				}	
			}
			console.log("Sistema Cruce PJUD:"+value);
		}
		else{
			console.log(response.error);
			setTimeout(function(){
				console.log("intentando conectar");
				if(value=="cae-itau"){
					value="CAE ITAU";
					var tot = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(3)').text());
					cruce_pjud("cae-itau", i, tot);
				}
				else if(value=="cae-scotia"){
					value="CAE SCOTIABANK";
					var tot = parseInt($("#sistemas tbody").find('#row-cae-scotia').find('td:nth-child(3)').text());
					cruce_pjud("cae-scotia", i, tot);
				}
				else if(value=="cat"){
					value="CAT";
					var tot = parseInt($("#sistemas tbody").find('#row-cat').find('td:nth-child(3)').text());
					cruce_pjud("cat", i, tot);
				}
				else if(value=="sup"){
					value="SUPERIR";
					var tot = parseInt($("#sistemas tbody").find('#row-sup').find('td:nth-child(3)').text());
					cruce_pjud("sup", i, tot);	
				}
			}, 10000);
		}
	  },
	  error: function(response){
		 console.log(response.error);
			setTimeout(function(){
				console.log("intentando conectar");
				if(value=="cae-itau"){
					value="CAE ITAU";
					var tot = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(3)').text());
					cruce_pjud("cae-itau", i, tot);
				}
				else if(value=="cae-scotia"){
					value="CAE SCOTIABANK";
					var tot = parseInt($("#sistemas tbody").find('#row-cae-scotia').find('td:nth-child(3)').text());
					cruce_pjud("cae-scotia", i, tot);
				}
				else if(value=="cat"){
					value="CAT";
					var tot = parseInt($("#sistemas tbody").find('#row-cat').find('td:nth-child(3)').text());
					cruce_pjud("cat", i, tot);
				}
				else if(value=="sup"){
					value="SUPERIR";
					var tot = parseInt($("#sistemas tbody").find('#row-sup').find('td:nth-child(3)').text());
					cruce_pjud("sup", i, tot);	
				}
			}, 20000);
	  }
	});
}

function cruce_pjud_e(value, i, total){ //alert(value);
	console.log(total);
	$("#mask").css({"display":"block", "height": $(document).height()});
	var count = Math.floor(parseInt(total)/100) + 1;
	//alert(total);
	//for(j=i;j<count;j++){
	ine_e += i * 100;
	var fin  = ine_e + 100;
	actionData = {'sistema': value, 'ini': ine_e, 'fin': fin }
	$.ajax({
	  type: "POST",
	  dataType: 'json',
	  data: actionData,
	  url: $('#site_url').val()+'/admin/roles/cruce_pjud_e/',
	  success: function(response){
		$("#mask").css({"display":"none"});
		var d = response.result;
		if(d==1){
			//  location.reload();
			if(value=="cae-itau"){
				value="CAE ITAU";
				if(i+1 == count){
					alert("Proceso terminado");
				}
				else{
					var ini = i+1;
					var tot = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(4)').text());
					setTimeout(function(){
						cruce_pjud_e("cae-itau", ini, tot);
					}, 5000);
				}
			}
			else if(value=="cae-scotia"){
				value="CAE SCOTIABANK";
				if(i+1 == count){
					var tot = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(3)').text());
					setTimeout(function(){
						cruce_pjud("cae-itau", 0, tot);
					}, 5000);
				}
				else{
					var ini = i+1;
					var tot = parseInt($("#sistemas tbody").find('#row-cae-scotia').find('td:nth-child(4)').text());
					setTimeout(function(){
						cruce_pjud_e("cae-scotia", ini, tot);
					}, 5000);
				}
			}
			else if(value=="cat"){
				value="CAT";
				if(i+1 == count){
					var tot = parseInt($("#sistemas tbody").find('#row-cae-scotia').find('td:nth-child(3)').text());
					setTimeout(function(){
						cruce_pjud("cae-scotia", 0, tot);
					}, 5000);
				}
				else{
					var ini = i+1;
					var tot = parseInt($("#sistemas tbody").find('#row-cat').find('td:nth-child(4)').text());
					setTimeout(function(){
						cruce_pjud_e("cat", ini, tot);
					}, 5000);
				}
			}
			else if(value=="sup"){
				value="SUPERIR";
				if(i+1 == count){
					var tot = parseInt($("#sistemas tbody").find('#row-cat').find('td:nth-child(3)').text());
					setTimeout(function(){
						cruce_pjud("cat", 0, tot);
					}, 5000);
				}
				else{
					var ini = i+1;
					var tot = parseInt($("#sistemas tbody").find('#row-sup').find('td:nth-child(4)').text());
					setTimeout(function(){
						cruce_pjud_e("sup", ini, tot);
					}, 5000);
				}	
			}
			console.log("Sistema Cruce PJUD:"+value);
		}
		else{
			console.log(response.error);
			setTimeout(function(){
				console.log("intentando conectar");
				if(value=="cae-itau"){
					value="CAE ITAU";
					var tot = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(4)').text());
					cruce_pjud_e("cae-itau", i, tot);
				}
				else if(value=="cae-scotia"){
					value="CAE SCOTIABANK";
					var tot = parseInt($("#sistemas tbody").find('#row-cae-scotia').find('td:nth-child(4)').text());
					cruce_pjud_e("cae-scotia", i, tot);
				}
				else if(value=="cat"){
					value="CAT";
					var tot = parseInt($("#sistemas tbody").find('#row-cat').find('td:nth-child(4)').text());
					cruce_pjud_e("cat", i, tot);
				}
				else if(value=="sup"){
					value="SUPERIR";
					var tot = parseInt($("#sistemas tbody").find('#row-sup').find('td:nth-child(4)').text());
					cruce_pjud_e("sup", i, tot);	
				}
			}, 10000);
		}
	  },
	  error: function(response){
		 console.log(response.error);
			setTimeout(function(){
				console.log("intentando conectar");
				if(value=="cae-itau"){
					value="CAE ITAU";
					var tot = parseInt($("#sistemas tbody").find('#row-cae-itau').find('td:nth-child(4)').text());
					cruce_pjud_e("cae-itau", i, tot);
				}
				else if(value=="cae-scotia"){
					value="CAE SCOTIABANK";
					var tot = parseInt($("#sistemas tbody").find('#row-cae-scotia').find('td:nth-child(4)').text());
					cruce_pjud_e("cae-scotia", i, tot);
				}
				else if(value=="cat"){
					value="CAT";
					var tot = parseInt($("#sistemas tbody").find('#row-cat').find('td:nth-child(4)').text());
					cruce_pjud_e("cat", i, tot);
				}
				else if(value=="sup"){
					value="SUPERIR";
					var tot = parseInt($("#sistemas tbody").find('#row-sup').find('td:nth-child(4)').text());
					cruce_pjud_e("sup", i, tot);	
				}
			}, 20000);
	  }
	});
}

</script>
<?php endif;?>