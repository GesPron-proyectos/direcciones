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
  <td>ETAPA</td>
  <td>TRAMITE</td>
  <td>DESC. TRAMITE</td>
  <td>FECHA TRAMITE</td>
  <td>DOC</td>
  
   <td>RUT</td>
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


  <td><?php echo $val->etapa;?></td>
  <td><?php echo $val->tramite;?></td>
  <td><?php echo $val->descripcion;?></td>
  <td>
  <?php if ($val->fecha!='' && $val->fecha!='0000-00-00'){ echo date("d-m-Y", strtotime($val->fecha)); } else {echo '';}?></td>
  <td style="text-align:center;">
    <?php 
      if($val->url){ ?>
        <a href="<?php echo $val->url; ?>" style="" target="_blank">
          <img src="<?php echo base_url().'images/generarpdf.gif';?>">
        </a>
    <?php } ?>
  </td>
  <td><?php echo $val->rut;?></td>
  <td><?php echo $val->fecha_etapa;?></td>
  <td><?php echo $val->etapa_cuenta;?></td>
  <td><?php echo $val->estado;?></td>
  <td><?php echo $val->procurador;?></td>
  <td><?php echo $val->mandante;?></td>
</tr>
<?php ++$i;endforeach;?>
</table>
<?php endif;?>