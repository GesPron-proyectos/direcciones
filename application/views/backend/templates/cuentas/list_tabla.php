<style>
  
figcaption {
  display: none;
  border: 1px #ccc solid;
  background-color: #fff;
  transition: all .5s;
  margin-top: 22px;
  position: absolute;
  padding: 5px 10px;
}
a.mostrar {cursor:pointer;color:red !important;font-weight:bold;}
.descript:hover > figcaption {
  display:block;
  transition: all .5s;
}

</style>
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
  <td>ROL / TRIBUNAL</td> 
  <!--<td width="8%" class="nombre">Jurisdicción</td>-->
  <!--<td width="8%" class="nombre">Tribunal</td>-->
  <!--<td width="12%" class="nombre"><a href="<?php echo site_url().'/admin/cuentas/?id_estado_cuenta='.$id_estado_cuenta.'&rut='.$rut.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&id_procurador='.$id_procurador.'&id_mandante='.$id_mandante.'&tribunal=';?><?php if($tribunal!='' && $tribunal=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Tribunal</a></td>     -->  
 <td>CARATULADO</td>
  <td style="width:80px;text-align:center;">MOV. PJUD</td>
  <td style="width:80px;text-align:center;">RUT</td>
  <td>FECHA ETAPA</td>
  <td>ETAPA</td>
  <td>ESTADO</td>
  <td>PROCURADOR</td>
  <td>MANDANTE</td>
</tr>

<?php  //print_r($lists); ?>

<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">


<td align="center"><?php echo $val->tribunal?><br><?php echo $val->rol?></td>

<td><?php echo $val->caratulado;?></td>
<td style="text-align:center;">
  <?php
	$link = "";
	if($val->cruce_pjud && isset($val->id_na))
		$link = "http://200.73.113.95:81/estadodiario/index.php/admin/movimientos/listar/0/{$val->id_na}"; 
	elseif($val->cruce_pjud)
		$link = "http://200.73.113.95:81/estadodiario/index.php/admin/movimientos/listar/{$val->id}"; 
	if($link){
  ?> 
	<a href="<?php echo $link; ?>" target="_blank">link<a/>
	<?php } ?>
  </td>
  <td>
    <?php 
      if($val->revisar){
    ?>
      <div class="descript"><a class="mostrar" href="#"><?php echo $val->rut; ?></a>
        <figcaption>Causa buscada por Rut, verificar si corresponde</figcaption>
      </div>
      <?php } else{
        echo "<b>".$val->rut."</b>";
      }
    ?>  
  </td>
  <td><?php echo $val->fecha_etapa;?></td>
  <td><?php echo $val->etapa_cuenta;?></td>
  <td><?php echo $val->estado;?></td>
  <td><?php echo $val->procurador;?></td>
  <td><?php echo $val->mandante;?></td>
</tr>
<?php ++$i;endforeach;?>
</table>
<?php endif;?>