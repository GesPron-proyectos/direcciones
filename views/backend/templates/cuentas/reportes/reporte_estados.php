<div class="table-m-sep">
  <div class="table-m-sep-title">
  	<h2><strong>Estado de Cuentas (<?php echo number_format($total,0,',','.');?>)</strong></h2>
  </div>
</div><!--table-m-sep-->
<div class="agregar-noticia">
    <div class="">
    <?php 
    $rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
    $id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
	$etapa = ''; if (isset($_REQUEST['etapa'])){$etapa = $_REQUEST['etapa'];}
    $id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
	$estado = ''; if (isset($_REQUEST['estado'])){$estado = $_REQUEST['estado'];}
	$id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
	//$fecha_etapa_month = '0';if (isset($_REQUEST['fecha_etapa_month'])){$fecha_etapa_month = $_REQUEST['fecha_etapa_month'];}//date ( 'd' ); if 
   // $fecha_etapa_year = '0';if (isset($_REQUEST['fecha_etapa_year'])){$fecha_etapa_year = $_REQUEST['fecha_etapa_year'];}//date ( 'm' );
        echo form_open(site_url().'/admin/cuentas/reporte/estados/',array('id' => 'form_reg'));
        
        echo '<div class="campo">';
        echo form_label('Rut', 'rut'/*,$attributes*/);
        echo form_input('rut', $rut);
        echo form_error('rut');
        echo '</div>';

        echo '<div class="campo">';
        echo form_label('Mandante', 'id_mandante'/*,$attributes*/);
        echo form_dropdown('id_mandante', $mandantes, $id_mandante);
        echo form_error('id_mandante');
        echo '</div>';

    echo '<div class="campo">';
    echo form_label('Etapa', 'etapa'); 
		echo form_dropdown('etapa', $etapas, $etapa);
    echo form_error('etapa');
    echo '</div>';

    echo '<div class="campo">';
    echo form_label('Estado cuenta', 'estado'); 
		echo form_dropdown('estado', $estados_cuenta, $estado);
    echo form_error('estado');
    echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Procurador', 'id_procurador'/*,$attributes*/);
	echo form_dropdown('id_procurador',$procuradores, $id_procurador);
	echo form_error('id_procurador');
	echo '</div>';

    //echo '<div class="campo">';
    //echo form_label('Fecha', 'fecha_etapa_year');
    	// echo month_dropdown('fecha_etapa_month',$fecha_etapa_month).year_dropdown('fecha_etapa_year',$fecha_etapa_year,2010);
        // echo form_error('fecha_etapa_year');
    // echo '</div>';
    
    echo '<div class="campo">';    
    echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
    echo '</div>';

        echo form_close();
    ?>
    <?php if ($nodo->nombre=='swcobranza'):?>
    <a href="<?php echo site_url();?>/admin/cuentas/cuenta_estado?rut=<?php echo $rut?>&id_mandante=<?php echo $id_mandante;?>&etapa=<?php echo $etapa;?>&estado=<?php echo $estado;?>&id_procurador=<?php echo $id_procurador;?>" class="ico-excel">Exportar a Excel</a>
    <?php endif;?>
    
    <?php if ($nodo->nombre=='fullpay'):?>
    <a href="<?php echo site_url();?>/admin/cuentas/cuenta_estado_fullpay?rut=<?php echo $rut?>&id_mandante=<?php echo $id_mandante;?>&etapa=<?php echo $etapa;?>&estado=<?php echo $estado;?>&id_procurador=<?php echo $id_procurador;?>" class="ico-excel">Exportar a Excel</a>
   <?php endif;?>
   
    </div><!-- campo -->
	<div class="clear height"></div>
</div><!--gregar-noticia-->
<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"><div>
<div class="tabla-listado">
<div class="content_tabla">
<table class="listado" height="82" width="100%">
<tr class="menu"  style="line-height:20px; height:50px;">
  <td width="4%" class="nombre">Id</td>
  <td width="10%" class="nombre">Mandante</td>
  <td width="10%" class="nombre">Procurador</td>
  <td width="5%" class="nombre">Estado Cuenta</td>
  <td width="10%" class="nombre">Rut</td>
  <td width="21%" class="nombre">Deudor</td>
  <td width="10%" class="nombre">Monto Pagaré</td>
  <td width="10%" class="nombre">Fecha Pagaré</td>
  <td width="10%" class="nombre">Fecha Asignación</td>
  <td width="17%" class="nombre">Etapa del Juicio</td>
  <td width="10%" class="nombre">Fecha Último Pago</td>
  <td width="10%" class="nombre">Saldo deuda</td>
  <td width="10%" class="nombre">Juzgado</td>
  
  
</tr>
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>" height="32">
  <td width="4%"><a href="<?php echo site_url().'/admin/gestion/index/'.$val->id;?>" title="ver cuenta"><?php echo $val->id;?></a></td>

    <td> <!--input name="chk-<?php echo $check_id;?>" type="checkbox" value="" class="check"--><a href="<?php echo site_url().'/admin/gestion/index/'.$val->id;?>" title=""><?php if($nodo->nombre == 'fullpay') { ?>
            <?php    if($val->clase_html == 1  ) {  ?>
                <?php echo '<span class="red">'.$val->codigo_mandante.'</span>' ?> <?php  } else {  echo $val->codigo_mandante; } ?>      </td>
    <?php } ?></td>


  <td><?php echo $val->nombres.'<br>'.$val->apellidos?>
  <td width="5%"><strong><?php 
  if ($val->id_estado_cuenta==0){echo '<span>SIN ASIGNAR</span>';}
  if ($val->id_estado_cuenta==1){echo '<span class="green">VIGENTE</span>';}
  if ($val->id_estado_cuenta==2){echo '<span class="gris">ABONADO</span>';}
  if ($val->id_estado_cuenta==3){echo '<span class="blue">SUSPENDIDO</span>';}
  if ($val->id_estado_cuenta==4){echo '<span class="red">TERMINADO</span>';}
  if ($val->id_estado_cuenta==5){echo '<span class="purple">CONVENIO</span>';}
  ?></strong></td>
  <td width="10%" style="text-align:right;"><?php echo $val->rut;?></td>
  <td width="14%" style="text-align:right;"><?php echo $val->usr_nombres.' '.$val->usr_ap_pat.' '.$val->usr_ap_mat;?></td>
  
  <td style="text-align:right; padding-right:70px;"><?php if ($val->monto_deuda>0){ echo '$'.number_format($val->monto_deuda,0,',','.');}?></td>
  <td><?php echo date("d-m-Y", strtotime($val->fecha_pagare));?></td>
  <td><?php echo date("d-m-Y", strtotime($val->fecha_asignacion));?></td>
  <td width="17%"><?php echo $val->etapa?></td>
  <td><?php if ($val->fecha_pago!='0000-00-00' && $val->fecha_pago!=''){ echo date("d-m-Y", strtotime($val->fecha_pago));}?></td>
  <?php $deuda = 0; $pagado = 0; if ((int)$val->total>0){ $pagado = $val->total;}?>
  <td style="text-align:right;"> <span class="<?php if ($pagado>0){ echo 'green';}?>">
  <?php  $deuda = $val->monto_deuda-$val->monto_pagado_new; echo number_format($deuda,0,',','.');?>
  </span></td>
  <td><?php if($val->tribunal_padre_comuna != ''){ ?><?php echo $val->tribunal_padre_comuna;?> <?php } else {echo '-'; ?><?php } ?></td>   
 
</tr>
<?php ++$i;endforeach;?>
</table>
</div><!--content_tabla-->
</div><!--tabla-listado-->
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
<?php endif;?>  
<?php echo $this->pagination->create_links(); ?>