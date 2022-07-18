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
#selector{margin-left:-8px;}
#pjud1, #pjud2{width:20px;}
</style>
<div id="mask"><div class="preloader"></div></div>
<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Causas (<?php echo number_format($total,0,',','.');?>)</strong></h2>
  </div>
</div>
<div class="agregar-noticia" style="min-height:200px;">
	<form id="form_pjud" method="POST" >
		<div id="selector">
			<label for="pjud1"><input type="radio" id="pjud1" name="pjud" value="1">Pjud API</label>
			<label for="pjud2"><input type="radio" id="pjud2" name="pjud" value="2">Pjud Antiguo</label>
		</div>
		<div id="pjud_new">
			<div width="20%" class="agregar">
			  <a id="limpiarbd2" class="nueva" href="<?php echo site_url();?>/admin/cuentas/limpiarbd/">LIMPIAR BD</a>
			</div>
			<div width="20%" class="agregar">
				<a id="cruce_pjud_new" class="nueva" href="#">CRUCE PEJUD</a>
			</div>
			<?php if(intval($cruces) > 0){ ?>
			<div width="20%" class="agregar">
				<a class="distrib nueva" href="#">DISTRIBUIR</a>
			</div>
			<?php } ?>
			<?php if(intval($no_seleccionados) > 0 && (intval($cruces) > 0 || intval($reservadas) > 0)){ ?>
			  <div width="20%" class="agregar">
				<a class="nueva" href="<?php echo site_url();?>/admin/cuentas/descargar/">NO SELECCIONADOS</a>
			  </div>
			<?php } ?>
		</div>
		<div id="pjud_old" style="display:none;">
			<div width="20%" class="agregar">
			  <a id="limpiarbd" class="nueva" href="<?php echo site_url();?>/admin/cuentas/limpiarbd/">LIMPIAR BD</a>
			</div>
			<?php $show = 1; foreach($abogados as $k=>$v){ if($v->contador >= 2) $show = 0; }
			  if($nuevo){
			  if($show || intval($total) == 0 || !$todos){ ?>
			  <div width="20%" class="agregar">
				<a class="nueva" href="<?php echo site_url();?>/admin/importar/cargar_excel/">IMPORTAR EXCEL</a>
			  </div>
			  <?php } ?>
			  <?php if(intval($total) > 0){ if($show){ ?>
			  <div width="20%" class="agregar">
				<a id="cruce_pjud" class="nueva" href="#">CRUCE PEJUD</a>
			  </div>
			  <?php } if(intval($cruces) > 0 || intval($reservadas) > 0){ ?>
			  <div width="20%" class="agregar">
				<a class="distrib nueva" href="#">DISTRIBUIR</a>
			  </div>
			  <?php } 
			  } ?>
			  <?php if(intval($no_seleccionados) > 0 && (intval($cruces) > 0 || intval($reservadas) > 0)){ ?>
			  <div width="20%" class="agregar">
				<a class="nueva" href="<?php echo site_url();?>/admin/cuentas/descargar/">NO SELECCIONADOS</a>
			  </div>
			  <?php } ?>
			<?php } ?>
		</div>	
		<br/>
		<table id="abogados" style="line-height:25px;margin-top:20px;">
			<thead>
				<tr>
					<th style="padding:0 10px;">Abogado</th>
					<th style="padding:0 10px;">Total</th>
					<th style="padding:0 10px;">Cruce GESPRON</th>
					<th style="padding:0 10px;">Duplicados</th>
					<th style="padding:0 10px;">Importados</th>	
					<th style="padding:0 10px;">Netos</th>
					<th style="padding:0 10px;">N / A</th>
					<th style="padding:0 10px;">A Distribuir</th>
					<th style="padding:0 10px;">Cruce PEJUD</th>
					<th style="padding:0 10px;">Distribuidos</th>
				
				</tr>
			</thead>
			<tbody>
				<?php
				$treg = $timp = $gesp = $elim = $tnet = $tna = $tad = $tmatch = $selecc = $distrib = 0;
				foreach ($abogados as $key => $value) {
					$total_import = $total_na = $elimin = 0;
					$abogado = $value->nombres.' '.$value->ape_pat.' '.$value->ape_mat;
					if($value->total_import)
						$total_import = intval($value->total_import);
					if($value->total_na)
						$total_na = intval($value->total_na);
					if($value->total_elim)
						$elimin = intval($value->total_elim);
					//$netos = $value->total_registros - $elimin - $total_na;
					$distribuir = $netos[$key] + $total_na;
					echo "<tr><td>{$abogado}: </td>";
					echo "<td style='text-align:center;'>{$value->total_registros_archivo}</td>";
					echo "<td style='text-align:center;'>{$value->cruce_gespron}</td>";
					echo "<td style='text-align:center;'>{$value->total_elim}</td>";
					echo "<td style='text-align:center;'>{$value->total_import}</td>";
					echo "<td style='text-align:center;'>{$netos[$key]}</td>";
					echo "<td style='text-align:center;'>{$value->total_na}</td>";
					echo "<td style='text-align:center;'>{$distribuir}</td>";
					echo "<td style='text-align:center;'>{$value->total_match}</td>";
					echo "<td style='text-align:center;'>{$value->distribuidos}</td></tr>";
					$treg += intval($value->total_registros_archivo);
					$timp += intval($value->total_import);
					$gesp += intval($value->cruce_gespron);
					$elim += intval($value->total_elim);
					$tnet += intval($netos[$key]);
					$tna += intval($value->total_na);
					$tad += intval($distribuir);
					$tmatch += intval($value->total_match);
					$selecc += intval($value->distribuidos);
				}
				?>
				<tr>
				  <td style="font-weight:bold;">ED ABOGADOS</td>
				  <td style='text-align:center;font-weight:bold;'><?php echo $treg; ?></td>
				  <td style='text-align:center;font-weight:bold;'><?php echo $gesp; ?></td>
				  <td style='text-align:center;font-weight:bold;'><?php echo $elim; ?></td>
				  <td style='text-align:center;font-weight:bold;'><?php echo $timp; ?></td>
				  <td style='text-align:center;font-weight:bold;'><?php echo $tnet; ?></td>
				  <td style='text-align:center;font-weight:bold;'><?php echo $tna; ?></td>
				  <td style='text-align:center;font-weight:bold;'><?php echo $tad; ?></td>
				  <td style='text-align:center;font-weight:bold;'><?php echo $tmatch; ?></td>
				  <td style='text-align:center;font-weight:bold;'><?php echo $selecc; ?></td>
				</tr>
				<tr>
					<td style="font-weight:bold;">ED ROLES</td>
					<td style='text-align:center;font-weight:bold;'><?php echo $rol_tot; ?></td>
					<td style='text-align:center;font-weight:bold;'></td>
					<td style='text-align:center;font-weight:bold;'><?php echo $rol_dupl; ?></td>
					<td style='text-align:center;font-weight:bold;'></td>
					<td style='text-align:center;font-weight:bold;'></td>
					<td style='text-align:center;font-weight:bold;'><?php echo $rol_na; ?></td>
					<td style='text-align:center;font-weight:bold;'><?php echo $rol_dist; ?></td>
					<td style='text-align:center;font-weight:bold;'><?php echo $rol_pjud; ?></td>
					<td style='text-align:center;font-weight:bold;'><?php echo $rol_sel; ?></td>
				</tr>
				<tr><td colspan="8"><div style="padding:8px;"></div></td></tr>
				<tr><td colspan="8"> </td></tr>
				<tr><td colspan="8"> </td></tr>
				<tr>
					<td style="font-size:12px;">CAUSAS RESERVADAS:</td>
					<td style='text-align:center;font-size:12px;'><?php echo $reservadas; ?></td>
					<td style="font-size:12px;"><!--TOTAL ANALIZADAS: --></td>
					<td style='text-align:center;font-size:12px;'><?php //echo $tad; ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td style="font-size:12px;">CORTE SUPREMA:</td>
					<td style='text-align:center;font-size:12px;'><?php echo $corte; ?></td>
				</tr>
				<tr>
					<td style="font-size:12px;">CORTE APELACIONES:</td>
					<td style='text-align:center;font-size:12px;'><?php echo $apelaciones; ?></td>
				</tr>
				<tr>
					<td style="font-size:12px;">LABORAL:</td>
					<td style='text-align:center;font-size:12px;'><?php echo $laboral; ?></td>
				</tr>
				<tr>
					<td style="font-size:12px;">PENAL:</td>
					<td style='text-align:center;font-size:12px;'><?php echo $penal; ?></td>
				</tr>
				<tr>
					<td style="font-size:12px;">COBRANZA:</td>
					<td style='text-align:center;font-size:12px;'><?php echo $cobranza; ?></td>
				</tr>
			</tbody>
		</table>
		<div class="clear">
		<?php if(count($lists) > 0){ $val = array(); 
			foreach ($lists as $k => $v){ if ($v->fecha!='' && $v->fecha!='0000-00-00'){ $val = $v; break;}} ?>
			<div style="font-weight:bold;font-size:12px;">FECHA TRÁMITE: <?php if ($val) echo date("d-m-Y", strtotime($val->fecha)); ?></div>
			<div style="font-weight:bold;font-size:12px;left:0;margin-top:-20px;">FECHA GESTIÓN: <?php if ($val) echo date("d-m-Y", strtotime($val->fecha_crea)); ?></div>
			<div>Total correos a enviar: <?php echo ($enviados + $no_enviados); ?></div>
			<div>Email enviados: <?php echo $enviados; ?> Total Causas enviados: <?php echo $causa_enviada; ?></div>
			<div>Total Causas No distribuidas: <?php echo $seleccionado; ?></div>
			<div>Total Causas No enviadas estado devueltos: <?php echo $devueltos; ?></div>
		<?php } ?>
		</div>
		<input type="hidden" id="site_url" value="<?php echo site_url();?>">
	</form>
</div>

<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">
	<div class="content_tabla">
		<?php include APPPATH.'views/backend/templates/cuentas/list_tabla.php';?>
	</div>
</div>
<?php endif;?>  
<?php echo $this->pagination->create_links(); ?>
<script type="text/javascript">
$(document).ready(function(){
	
	var url = window.location.href;
	var hash = url.split('#')[1];

	if (hash == 'pjud=2'){
		$("#pjud1").attr({'checked': false});
		$("#pjud2").attr({'checked': true});
		$("#pjud_old").css({'display': 'block'});
		$("#pjud_new").css({'display': 'none'});
	}
	else {
		$("#pjud1").attr({'checked': true});
		$("#pjud2").attr({'checked': false});
		$("#pjud_old").css({'display': 'none'});
		$("#pjud_new").css({'display': 'block'});
	}

	$("#pjud1").click(function(e){
		$("#pjud_old").css({'display': 'none'});
		$("#pjud_new").css({'display': 'block'});
		window.location.hash = "pjud=1";
	});
	
	$("#pjud2").click(function(e){
		$("#pjud_old").css({'display': 'block'});
		$("#pjud_new").css({'display': 'none'});
		window.location.hash = "pjud=2";
	});
	
	// Al entrar, marcar el check viejo
	var $radios = $('input:radio[name=pjud]');
    $radios.filter('[value=2]').prop('checked', true);
	$("#pjud_old").css({'display': 'block'});
	$("#pjud_new").css({'display': 'none'});
	window.location.hash = "pjud=2";

	$("#limpiarbd, #limpiarbd2").click(function(e){
		e.preventDefault();
		$("#mask").css({"display":"block", "height": $(document).height()});
		actionData = { }
		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  data: actionData,
		  url: $('#site_url').val()+'/admin/cuentas/limpiarbd/',
		  success: function(response){
			$("#mask").css({"display":"none"});
			var d = response.result;
			if(d==1){
			  location.reload();
			}
		  }
		});
	});
	
	$("#cruce_pjud_new").click(function(e){
		e.preventDefault();
		//cruce_pjud(1);
		cruce_pjud_new();
	});

 	$("#cruce_pjud").click(function(e){
		e.preventDefault();
		cruce_pjud(0);
	});

	$("a.distrib").click(function(e){
		e.preventDefault();
		$("#mask").css({"display":"block", "height": $(document).height()});
		actionData = { }
		$.ajax({
		  type: "POST",
		  dataType: 'json',
		  data: actionData,
		  url: $('#site_url').val()+'/admin/cuentas/distribuir/',
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

contador = 0;
function cruce_pjud(api){
	
	if(contador == 3){
		alert("Proceso no terminado, el sitio del PJUD está inestable.\n");
		location.reload();
	}
	
	$("#mask").css({"display":"block", "height": $(document).height()});
	actionData = { 'contador': contador };
	$.ajax({
		type: "POST",
		dataType: 'json',
		data: actionData,
		url: $('#site_url').val()+'/admin/cuentas/cruce_pjud/',
		success: function(response){
			$("#mask").css({"display":"none"});
			if(response){
				var d = response.result;
				if(d==1){
					if(api)
						cruce_pjud_new();
					else{
						location.reload();
					}
				}
				else{
					$("#mask").css({"display":"block", "height": $(document).height()});
					console.log(response.error);
					setTimeout(function(){
						console.log("intentando conectar");
						contador++;
						cruce_pjud(api);
					}, 10000);
				}
			}
			else{
				location.reload();
			}
		},
		error: function(response){
			$("#mask").css({"display":"block", "height": $(document).height()});
			setTimeout(function(){
				console.log("intentando conectar");
				contador++;
				cruce_pjud(api);
			}, 10000);
		},
	});
}

function cruce_pjud_new(){
	
	if(contador == 5){
		alert("Proceso no terminado, el sitio del PJUD está inestable.\n");
		location.reload();
	}
	
	$("#mask").css({"display":"block", "height": $(document).height()});
	actionData = { 'contador': contador };
	$.ajax({
		type: "POST",
		dataType: 'json',
		data: actionData,
		url: $('#site_url').val()+'/admin/cuentas/cruce_pjud2/',
		success: function(response){
			$("#mask").css({"display":"none"});
			if(response){
				var d = response.result;
				if(d==1){
					//location.reload();
					alert("finalizado");
				}
				else{
					if(confirm(response.error)){
						$("#mask").css({"display":"block", "height": $(document).height()});
						//console.log(response.error);
						
						setTimeout(function(){
							console.log("intentando conectar");
							contador++;
							cruce_pjud_new();
						}, 10000);
					}
					else{
						//location.reload();
					}
				}
			}
			else{
				//location.reload();
			}
		},
		error: function(response){
			$("#mask").css({"display":"block", "height": $(document).height()});
			setTimeout(function(){
				console.log("intentando conectar");
				contador++;
				cruce_pjud_new();
			}, 10000);
		},
	});
}
	
</script>