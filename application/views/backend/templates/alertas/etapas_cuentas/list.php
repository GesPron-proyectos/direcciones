<?php $id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}  ?>
<?php $rol = ''; if (isset($_REQUEST['rol'])){$rol = $_REQUEST['rol'];}  ?>
<?php $id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}  ?>
<?php $id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta'];}  ?>
<?php $id_etapa = ''; if (isset($_REQUEST['id_etapa'])){$id_etapa = $_REQUEST['id_etapa'];}  ?>
<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Cuentas (<?php echo number_format($total,0,',','.');?>)</strong></h2>
  </div>
</div>
<style>
table.listado tr,table.listado input, table.listado select {
    font-size: 11px;
    margin: 5px 0 5px 5px;
	line-height: 26px !important;
}
</style>


<div class="tabla-listado">


<div class="agregar-noticia">
 <?php if (isset($nodo) && count($nodo)==1 && $nodo->nombre=='swcobranza'):?>
 <a class="ico-excel" href="<?php echo site_url();?>/admin/alertas/exportar_excel" style="float:right;"  target="_blank">Exportar a Excel</a><div class="clear height"></div>
 <?php endif;?>

    <?php if ($nodo->nombre=='fullpay'):?>
        <a class="ico-excel" href="<?php echo site_url();?>/admin/alertas/cuentas_alertas_proceso" style="float:right;"  target="_blank">Exportar a Excel</a><div class="clear height"></div>
    <?php endif;?>

<div class="clear height"></div>

<?php   echo form_open(site_url().'/admin/alertas/etapas_cuentas',array('id' => 'form_reg'));
	
		echo '<div class="campo">';
		echo form_label('Mandante', 'id_mandante'/*,$attributes*/);
		echo form_dropdown('id_mandante', $mandantes, $id_mandante);
		echo form_error('id_mandante');
		echo '</div>';
	
		echo '<div class="campo">';
		echo form_label('Rol', 'rol'/*,$attributes*/);
		echo form_input('rol', $rol);
		echo form_error('rol');
		echo '</div>'; 
		
		echo '<div class="campo">';
		echo form_label('Procurador', 'id_procurador'/*,$attributes*/);
		echo form_dropdown('id_procurador', $procuradores, $id_procurador);
		echo form_error('id_procurador');
		echo '</div>';
		
		
		echo '<div class="campo">';
		echo form_label('Tribunal', 'id_tribunal');
		echo '<div id="anidadotribunal">';
		echo form_dropdown('id_tribunal', $tribunales);
		echo form_error('id_tribunal');
		echo '</div>';
		echo '</div>';
	
		echo '<div class="campo">';
		echo form_label('Estado', 'id_estado_cuenta'/*,$attributes*/);
		echo form_dropdown('id_estado_cuenta', $estados, $id_estado_cuenta);
		echo form_error('id_estado_cuenta');
		echo '</div>';
		
		echo '<div class="campo">';
		echo form_label('Etapa de Juicio', 'id_etapa'/*,$attributes*/);
		echo form_dropdown('id_etapa', $etapas, $id_etapa);
		echo form_error('id_etapa');
		echo '</div>';
	
	
	    echo '<div class="campo">';
		echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
		echo '</div>';
	
   	    echo form_close();	
?>

<div class="clear height"></div>
</div>
<?php if (count($lists)>0): ?>
	<div class="content_tabla">
<?php /*echo '<pre>'; print_r($lists); echo '</pre>';*/?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  
  <td width="5%" class="nombre">Mandante</td>
  <td width="5%" class="nombre">D. Rut</td>
  <td width="5%" class="nombre">D. Nombre</td>
  <td width="5%" class="nombre">Rol</td>
  <td width="5%" class="nombre">Fecha Asignación</td>
  <td width="5%" class="nombre">Fecha Pagare</td>
  <td width="5%" class="nombre">Procurador</td>
  <td width="5%" class="nombre">Tribunal</td>
  <td width="5%" class="nombre"> Dias atrasos</td>
  <!-- m<td width="5%" class="nombre"> Días atrasos </td> -->
  <td width="5%" class="nombre">Etapa</td>
  <td width="5%" class="nombre">Fecha Ingreso Etapa</td>

</tr>

<?php $fecha_anterior = date('Y-m-d'); ?> 
<?php $intervalo = ''; ?>
<?php $r = '0'; ?>
<?php $c = '0'; ?>

<?php $i=1; $check_id=1;

foreach ($lists as $key=>$val):?>
 
<?php // $intervalo = date_diff(date_create($fecha_anterior),date_create($val->fecha_etapa));  ?>
<?php  $intervalo = date_diff(date_create($val->fecha_asignacion),date_create($val->fecha_etapa));  ?>

<tr id="tools_" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>">

	<td><?php echo $val->codigo_mandante;?></td>
	<td><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>"><?php echo $val->usuarios_rut;?></a></td>
	<td><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>"><?php echo $val->usuarios_nombres;?> <?php echo $val->usuarios_ap_pat;?> <?php echo $val->usuarios_ap_mat;?></a></td>
    <td><?php echo $val->rol;?></td>
      <td><?php echo date("d-m-Y",strtotime($val->fecha_asignacion))?></td>
      <td><?php echo date("d-m-Y",strtotime($val->fecha_asignacion_pagare))?></td>
      <td><?php echo $val->nombres_adm.' '.$val->apellidos_adm;?></td>
       <td><?php echo $val->tribunal?></td>
       
       <?php $c = abs($val->dias_diferencia);
       $r =	$c - $val->dias_alerta_proceso;
		?>
       
    
       
       
       
       <td style="color:#F00;text-align: center;"><?php echo $r;?> </td>
       
       <?php $c = 0; ?>
       <?php $intervalo_limpio = 0; ?>
       <?php $c = $intervalo->format('%R%a días'); ?>
       <?php if($c < 0){
      $intervalo_limpio =  $c *(-1); } 
       elseif($c > 0){ $intervalo_limpio = $c *(1);  }?> 
      
      <?php // echo  $intervalo_limpio.'aaaaaa'; ?>
      
 <!-- <td style="color:#F00;text-align: center;"> <?php // if($val->dias_alerta > $intervalo_limpio || $val->dias_alerta == 0 || $val->dias_alerta == $intervalo_limpio){ echo '-'; } elseif($intervalo_limpio > $val->dias_alerta) { ?>  <?php //echo $intervalo_limpio - $val->dias_alerta; }?>  </td> 	 -->  
      
      
      <td><?php echo $val->etapa?></td>
	  <td style="text-align: center;"><?php echo date("d-m-Y",strtotime($val->fecha_etapa))?></td>

  <?php $row_current=array(); $row_current=$val; //include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
</tr>

		<?php //if($fecha_anterior != $val->fecha_etapa){
			//$fecha_anterior = $val->fecha_etapa; 
		//}?>
			  

<?php ++$i;endforeach;?>

</table>
<?php endif;?>
<?php echo $this->pagination->create_links(); ?>
</div></div>


