
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
    	<a class="nueva" href="<?php echo site_url();?>/admin/importar/cargar_excel/">IMPORTAR EXCEL</a>
    </div>
    <div width="20%" class="agregar">
    	<a id="cruce_pjud" class="nueva" href="#">CRUCE PEJUD</a>
    </div>
    <div width="20%" class="agregar">
    	<a class="nueva" href="<?php echo site_url();?>/admin/cuentas/distribuir/">DISTRIBUIR</a>
    	<!--<a id="btn_distrib" class="nueva" href="#">DISTRIBUIR</a>-->
    </div><br/>
	<table id="abogados" style="line-height:25px;margin-top:20px;">
		<thead>
			<tr>
				<th style="padding:0 10px;">Abogado</th>
				<th style="padding:0 10px;">Total</th>
				<th style="padding:0 10px;">Importados</th>
				<th style="padding:0 10px;">Duplicados</th>
				<th style="padding:0 10px;">Netos</th>
				<th style="padding:0 10px;">N / A</th>
				<th style="padding:0 10px;">A Distribuir</th>
				<th style="padding:0 10px;">Cruce PEJUD</th>
				<th style="padding:0 10px;">Seleccionados</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$treg = $timp = $elim = $tnet = $tna = $tad = $tmatch = 0;
			foreach ($abogados as $key => $value) {
				$total_import = $total_na = $elimin = 0;
				$abogado = $value->nombres.' '.$value->ape_pat.' '.$value->ape_mat;
				if($value->total_import)
					$total_import = intval($value->total_import);
				if($value->total_na)
					$total_na = intval($value->total_na);
				if($value->total_elim)
					$elimin = intval($value->total_elim);
				$distribuir = $total_import - $elimin + $total_na;
				$netos = $total_import - $elimin;
				echo "<tr><td>{$abogado}: </td>";
				echo "<td style='text-align:center;'>{$value->total_registros}</td>";
				echo "<td style='text-align:center;'>{$value->total_import}</td>";
				echo "<td style='text-align:center;'>{$value->total_elim}</td>";
				echo "<td style='text-align:center;'>{$netos}</td>";
				echo "<td style='text-align:center;'>{$value->total_na}</td>";
				echo "<td style='text-align:center;'>{$distribuir}</td>";
				echo "<td style='text-align:center;'>{$value->total_match}</td>";
				echo "<td style='text-align:center;'>{$value->distribuidos}</td></tr>";
				$treg += intval($value->total_registros);
                $timp += intval($value->total_import);
                $elim += intval($value->total_elim);
                $tnet += intval($netos);
                $tna += intval($value->total_na);
                $tad += intval($distribuir);
                $tmatch += intval($value->total_match);
			}
			?>
			<tr>
              <td style="font-weight:bold;">TOTALES</td>
              <td style='text-align:center;font-weight:bold;'><?php echo $treg; ?></td>
              <td style='text-align:center;font-weight:bold;'><?php echo $timp; ?></td>
              <td style='text-align:center;font-weight:bold;'><?php echo $elim; ?></td>
              <td style='text-align:center;font-weight:bold;'><?php echo $tnet; ?></td>
              <td style='text-align:center;font-weight:bold;'><?php echo $tna; ?></td>
              <td style='text-align:center;font-weight:bold;'><?php echo $tad; ?></td>
              <td style='text-align:center;font-weight:bold;'><?php echo $tmatch; ?></td>
            </tr>
            <tr><td colspan="8"><div style="padding:8px;"></div></td></tr>
            <tr><td colspan="8"> </td></tr>
            <tr><td colspan="8"> </td></tr>
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
		</tbody>
	</table>
<div class="clear"></div>
<div class="">

<?php // print_r($roles); ?>

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
<form id="form_reload" action="<?php echo site_url();?>/admin/cuentas/" method="post">
	<input type="hidden" id="reload" value="<?php echo $reload; ?>">
</form>
</div>

<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">
<div class="content_tabla">
 <?php include APPPATH.'views/backend/templates/cuentas/list_tabla.php';?>
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

 	$("#cruce_pjud").click(function(e){
		e.preventDefault();
		$("#mask").css({"display":"block", "height": $(document).height()});
		actionData = { }
		$.ajax({
			type: "POST",
			dataType: 'json',
			data: actionData,
			url: $('#site_url').val()+'/admin/cuentas/cruce_pjud/',
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
</script>
<?php endif;?>