<script type="text/javascript">
	$().ready(function() {
		$(".xtool").click(function(){
			var idd=$(this).attr("id");
			var campo=$(this).attr("title");
			var value=$(this).attr("rel");

			if (confirm("¿Está seguro que desea eliminar este registro?") == true) {
				$("tr#row-"+idd).addClass("c");
				$.ajax({
					type: 'post',
					url: '<?php echo site_url();?>/admin/<?php echo $current;?>/delete/'+idd,
					data: campo+'='+value,
					beforeSend: function() { 
						$('#tools_k'+idd).html('<img src="<?php echo base_url();?>images/ajax-loader.gif">');
					},
					success: function(data) {
						
						$('#tools_'+idd).fadeOut('slow', function(){
							$('#tools_'+idd).remove();
						});
			
						
					},
					complete: function(data){
						setTimeout(function(){
								clase = 'a';
								$('.tr').each( function() {
									$(this).removeClass('a');
									$(this).removeClass('b');
		
									if(clase == 'a'){
										$(this).addClass('a');
										clase = 'b';
									}else{
										$(this).addClass('b');
										clase = 'a';
									}
								});
						},1000);
					}
				});	
			}
		});
		$(document).on('submit','.formendcall', function(e){
	     if (confirm("¿Está seguro de marcar esta llamada con finalizada?") == true) {		
			 var form = $(this);
			 var tr = form.parent('td').parent('tr');
			 $.ajax({
				type: form.attr('method'),
				url:  form.attr('action'),
				data: form.serialize(),
				success: function(data) {
					tr.remove();
				},
			 });
		 }
		 return false
	   });
	   $(document).on('click','.telefono-act-estado', function(e){
		   if (confirm('¿Está seguro de realizar esta acción?')){
			   var id_telefono = $(this).attr('rel');
			   var estado = $(this).data('estado');
			   $.ajax({
					type: 'post',
					url: '<?php echo site_url();?>/admin/<?php echo $current;?>/actualizar_estado/'+id_telefono,
					data: 'estado='+estado,
					beforeSend: function() { 
						$('#box_'+id_telefono).html('<img src="<?php echo base_url();?>images/ajax-loader.gif">');
					},
					success: function(data) {
						
						$('#box_'+id_telefono).html(data);	
					},
			   });
		   }
		   return false;
	   });
	   
	   $(".datepicker").datepicker({ format: 'dd-mm-yyyy',});
	});
</script>


<style>
table.listado tr,table.listado input, table.listado select {
    font-size: 11px;
    margin: 5px 0 5px 5px;
	line-height: 12px !important;
}
</style>


<?php $nombres_orden = ''; if (isset($_REQUEST['nombres_orden'])){$nombres_orden = $_REQUEST['nombres_orden'];} ?>
<?php $rut_orden = ''; if (isset($_REQUEST['rut_orden'])){$rut_orden = $_REQUEST['rut_orden'];} ?>
<?php $orden_rol = ''; if (isset($_REQUEST['orden_rol'])){$orden_rol = $_REQUEST['orden_rol'];} ?>
<?php $nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];} ?>
<?php $ap_pat = ''; if (isset($_REQUEST['ap_pat'])){$ap_pat = $_REQUEST['ap_pat'];} ?>
<?php $id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];} ?>
<?php $id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];} ?>
<?php $rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];} ?>
<?php $id_castigo = ''; if (isset($_REQUEST['id_castigo'])){$id_castigo = $_REQUEST['id_castigo'];} ?>
<?php $fecha_asignacion_pagare = ''; if (isset($_REQUEST['fecha_asignacion_pagare'])){$fecha_asignacion_pagare = $_REQUEST['fecha_asignacion_pagare'];} ?>
<?php $diferencia = ''; if (isset($_REQUEST['diferencia'])){$diferencia = $_REQUEST['diferencia'];} ?>
<?php $tribunal = ''; if (isset($_REQUEST['tribunal'])){$tribunal = $_REQUEST['tribunal'];} ?>
<?php $tribunalE = ''; if (isset($_REQUEST['tribunalE'])){$tribunalE = $_REQUEST['tribunalE'];} ?>
<?php $comuna = ''; if (isset($_REQUEST['comuna'])){$comuna = $_REQUEST['comuna'];} ?>
<?php $operacion = ''; if (isset($_REQUEST['operacion'])){$operacion = $_REQUEST['operacion'];}  ?>

<?php //print_r($lists);//$id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta']; } ?>
<?php if (count($lists)>0): ?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  <td width="8%" class="nombre">Nº Operación</td> 
  <!--<td width="8%" class="nombre">Jurisdicción</td>-->
  <!--<td width="8%" class="nombre">Tribunal</td>-->
  <!--<td width="12%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&tribunal=';?><?php if($tribunal!='' && $tribunal=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Tribunal</a></td>     -->
  <td width="12%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&orden_rol=';?><?php if($orden_rol!='' && $orden_rol=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Rol / Tribunal</a></td>   
  
  <td width="8%" class="nombre">Mandante</td>
  <td width="9%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&rut_orden=';?><?php if($rut_orden!='' && $rut_orden=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Deudor RUT</a></td>   <!-- ?rut_orden=[desc] -->
  <td width="9%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&nombres_orden=';?><?php if($nombres_orden!='' && $nombres_orden=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Nombre Deudor</a></td>   <!-- ?rut_orden=[desc] -->
  
  <td width="10%" class="nombre">Procurador</td>
  <td width="12%" class="nombre">Estado Cuenta</td>


  <!--<td width="12%" class="nombre">Trib. Exhorto</td>-->
  <td width="9%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&tribunalE=';?><?php if($tribunalE!='' && $tribunalE=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Rol / Tri. Exhorto</a></td>   <!-- ?rut_orden=[desc] -->
  <!--<td width="12%" class="nombre">Juz Exhortado</td>
  <td width="10%" class="nombre">Rol Exhorto</td>  -->
  
  <!--<td width="12%" class="nombre">Juzgado</td>-->
  <?php if ($nodo->nombre=='swcobranza'):?>
  <td width="12%" class="nombre">Fecha Pagaré </td>
  <td width="12%" class="nombre">Monto Pagaré</td>
  <td width="12%" class="nombre">Saldo Deuda</td>
  <?php endif;?>
  <?php if ($nodo->nombre=='fullpay'):?>
  <td width="12%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&comuna=';?><?php if($comuna!='' && $comuna=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Comuna</a></td>
  <!--<td width="12%" class="nombre">Fecha asignación</td>-->
  
  <td width="9%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&fecha_asignacion_pagare=';?><?php if($fecha_asignacion_pagare!='' && $fecha_asignacion_pagare=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Fecha asignación</a></td>   <!-- ?rut_orden=[desc] -->
  
  <!--<td width="12%" class="nombre">Días transcurridos</td>-->
  <td width="9%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&diferencia=';?><?php if($diferencia!='' && $diferencia=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Días transcurridos</a></td>   <!-- ?rut_orden=[desc] -->  
  <?php endif;?>
  <td width="10%" class="nombre">Gestión</td>


</tr>

<?php  //print_r($lists); ?>
<form action="<?php echo site_url()?>/admin/cobros/save" method="post" class="form_table" data-id="<?php echo $val->id;?>" autocomplete="off">
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">

<td style="float: left; width: 50px ! important;">
<?php echo $val->operacion?> 
	<?php if($val->id_castigo==2){ echo '<span class="castigado"></span>';  } ?>
</td>
<td align="center"><?php echo $val->tribunal?><br><?php echo $val->tribunal_padre;?><br><?php echo $val->rol;?></td>
<!--<td><?php echo $val->tribunal;?></td>-->
<!--<td width="12%" class="nombre"><?php echo $val->rol;?></td> Rol Exortado-->
    
  <td > <!--input name="chk-<?php echo $check_id;?>" type="checkbox" value="" class="check"-->
      <a href="<?php echo site_url().'/admin/gestion/index/'.$val->id;?>">
          <?php if($nodo->nombre == 'fullpay') { ?>
          <?php    if($val->clase_html == 1  ) {  ?>
              <?php echo $val->codigo_mandante ?> <span class="mandante2013" style="display:inline-block;vertical-align-text-bottom;"></span>
               <?php  } else {  echo $val->codigo_mandante; } ?>
    <?php } ?>

    <?php if($nodo->nombre == 'swcobranza') { ?>
    <?PHP echo $val->codigo_mandante; ?>
    <?php }?></a></td>

  <td><?php echo $val->rut;?></td>
  <td><?php echo $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat;?></td>
  <td><?php if (array_key_exists($val->id_procurador,$procuradores)){ echo $procuradores[$val->id_procurador];}?></td>
  <td><strong><?php 
  if ($val->id_estado_cuenta==1){echo '<span class="green">VIGENTE</span>';}
  if ($val->id_estado_cuenta==7){echo '<span class="green">DEV. DOCUMENTOS</span>';}
  if ($val->id_estado_cuenta==2 && $nodo->nombre=='fullpay'){echo '<span class="gris">ABONANDO</span>';}
  if ($val->id_estado_cuenta==2 && $nodo->nombre=='swcobranza'){echo '<span class="gris">ABANDONADO</span>';}
  if ($val->id_estado_cuenta==3){echo '<span class="blue">SUSPENDIDO</span>';}
  if ($val->id_estado_cuenta==4){echo '<span class="red">TERMINADO</span>';}
  if ($val->id_estado_cuenta==5 && $nodo->nombre=='swcobranza'){echo '<span class="purple">CONVENIO</span>';}
  if ($val->id_estado_cuenta==5 && $nodo->nombre=='fullpay'){echo '<span class="purple">DEVUELTO</span>';}
  if ($val->id_estado_cuenta==6 && $nodo->nombre=='swcobranza'){echo '<span class="purple2">CONVENIO INCUMPLIDO</span>';}
  if ($val->id_estado_cuenta==6 && $nodo->nombre=='fullpay'){echo '<span class="blue-dark">EXHORTO</span>';}
  if ($val->id_estado_cuenta==7 && $nodo->nombre=='swcobranza'){echo '<span class="blue2">SUSPENDIDO CON CONVENIO</span>';}
  if ($val->id_estado_cuenta==8 && $nodo->nombre=='swcobranza'){echo '<span class="blue3">GPVE rechazadas</span>';}
  if ($val->id_estado_cuenta==9 && $nodo->nombre=='swcobranza'){echo '<span class="yellow">ABONO</span>';}
  ?></strong>
  </td>

 <td width="10%" class="nombre" align="center">
	
	<?php echo $val->TribunalE;?><br><?php echo $val->DistritoE;?><?php if ($val->exorto=='0')
 {?> Sin Exhorto
 <?php }else{?>
		<?php if($val->rolE != ''){ ?><?php echo $val->rolE;?> <?php } else {echo '-'; ?><?php } ?>
	<?php }?>
 
 </td> <!--Rol Exorto-->
   <!--<td><?php if($val->DistritoE != ''){ ?><?php echo $val->DistritoE;}?> </td>-->
    <!--<td>
	
	<?php if ($val->exorto=='0')
 {?> Sin Exhorto
 <?php }else{?>
		<?php if($val->rolE != ''){ ?><?php echo $val->rolE;?> <?php } else {echo '-'; ?><?php } ?>
	<?php }?>
	
	</td>-->
  
  
 <!--<td><?php if($val->rol != ''){ ?><?php echo $val->rol;?> <?php } else {echo '-'; ?><?php } ?></td>
  <td><?php if($val->tribunal_padre_comuna != ''){ ?><?php echo $val->tribunal_padre_comuna;?> <?php } else {echo '-'; ?><?php } ?></td> -->  
  <?php if ($nodo->nombre=='swcobranza'):?>
  <td>
  <?php if ($val->fecha_asignacion_pagare!='' && $val->fecha_asignacion_pagare!='0000-00-00'){ echo date("d-m-Y", strtotime($val->fecha_asignacion_pagare)); } else {echo '-';}?></td>
  <td><?php if($val->monto_deuda != ''){ ?> <?php echo number_format($val->monto_deuda,0,',','.');?>  <?php  }?> <?php // monto_deuda?></td>
   <?php 
  $deuda = 0; 
  $pagado = is_numeric($val->total_pagado) ? (int)$val->total_pagado : 0 ; 
  ?>
  <td><span class="<?php  if ($pagado>0){ echo 'green';}?>"><?php echo number_format($val->monto_deuda - $val->total_pagado,0,',','.');?></span></td>
  
  <?php endif;?>

 
  <!-- </span> -->
  <?php if ($nodo->nombre=='fullpay'):?>
    <td><?php if($val->nombre_comuna != ''){ ?><?php echo $val->nombre_comuna;?> <?php } else {echo '-'; ?><?php } ?></td>
    <td><?php echo date('d-m-Y',strtotime($val->fecha_asignacion));?></td>
    <td> <?php if($val->diferencia <= 60){ ?>   
			<span class="green"><?php echo $val->diferencia;?></span>  
		 <?php }elseif($val->diferencia < 90 && $val->diferencia > 60){ ?> 
			  <span class="blue"><?php echo $val->diferencia;?></span>  			  
		 <?php }elseif($val->diferencia < 120 && $val->diferencia > 90){ ?>   
			  <span class="red"><?php echo $val->diferencia;?></span>   
		 <?php }elseif($val->diferencia > 120 ){ ?>   
			  <span class="purple"><?php echo $val->diferencia;?></span>   
		 <?php }?> </td>
    <?php endif;?>
    <td>
<input type="hidden" name="id_cuenta" value="<?php echo $val->id?>">
<input type="hidden" name="id" value="<?php echo $val->idcob?>">
	<?php echo form_dropdown('estadoscobros', $estados_cobros, $val->estadocobro ,'autocomplete="off" id="select_'.$val->id.'" data-id="'.$val->id.'" style="width:100px;" ');?>
	<input style="width:75px" class="boton" type="submit" value="Guardar" >
	</td>
  </td>
  


</tr></form>

<?php ++$i;endforeach;?>

</table>
<?php endif;?>