<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

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

<?php //$id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta']; } ?>
<?php if (count($lists)>0): ?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  <td width="8%" class="nombre">ROL</td> 
  <!--<td width="8%" class="nombre">Jurisdicción</td>-->
  <!--<td width="8%" class="nombre">Tribunal</td>-->
  <!--<td width="12%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&tribunal=';?><?php if($tribunal!='' && $tribunal=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Tribunal</a></td>     -->
 
  
  <td width="8%" class="nombre">FECHA INGRESO</td>

  
  <td width="20%" class="nombre">CARATULADO</td>
  <td width="14%" class="nombre">TRIBUNAL</td>
  <td width="12%" class="nombre">ABOGADO</td>

  <td width="14%" class="nombre">ETAPA</td>
  <td width="10%" class="nombre">TRAMITE</td>
  <td width="10%" class="nombre">DESC. TRAMITE</td>
  <td width="8%" class="nombre">FECHA TRAMITE</td>

  <!--<td width="12%" class="nombre">Juzgado</td>-->

</tr>

<?php  //print_r($lists); ?>

<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">

<td style="float: left; width: 80px ! important;">
<?php echo $val->rol?> 
	
</td>
<td align="center"><?php echo $val->fecha_ingreso?></td>
<td><?php echo $val->caratulado;?></td>
    <?php if($nodo->nombre == 'swcobranza') { ?>
    <?PHP echo $val->caratulado; ?>
    <?php }?></a></td>

  <td><?php echo $val->tribunal;?></td>
  <td><?php echo $val->nombres;?></td>
  <td><?php echo $val->etapa;?></td>
  <td><?php echo $val->tramite;?></td>
  <td><?php echo $val->descripcion;?></td>
  <td><?php echo $val->fecha;?></td>
</tr>
<?php ++$i;endforeach;?>
</table>
<?php endif;?>