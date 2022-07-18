<div class="table-m-sep">
  <div class="table-m-sep-title">
  	<h2><strong>Etapas de Juicio (<?php echo number_format($total,0,',','.');?>)</strong></h2>
  </div>
</div><!--table-m-sep-->
<div class="agregar-noticia">
    <div class="filtro">
    <?php 
    $rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
    $id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
	$etapa = ''; if (isset($_REQUEST['etapa'])){$etapa = $_REQUEST['etapa'];}
    $id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
    $fecha_etapa_day = '0';if (isset($_REQUEST['fecha_etapa_day'])){$fecha_etapa_day = $_REQUEST['fecha_etapa_day'];}
    $fecha_etapa_month = '0';if (isset($_REQUEST['fecha_etapa_month'])){$fecha_etapa_month = $_REQUEST['fecha_etapa_month'];}
	$rango = '0';if (isset($_REQUEST['rango'])){$rango = $_REQUEST['rango'];}
    $rol= ''; if (isset($_REQUEST['rol'])){$rol = $_REQUEST['rol'];}
    //date ( 'd' ); if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
    $fecha_etapa_year = '0';if (isset($_REQUEST['fecha_etapa_year'])){$fecha_etapa_year = $_REQUEST['fecha_etapa_year'];}//date ( 'm' );
	$nombre= ''; if (isset($_REQUEST['nombre'])){$nombre = $_REQUEST['nombre'];}
	
	//$fecha_etapa_f_month = '0';if (isset($_REQUEST['fecha_etapa_f_month'])){$fecha_etapa_f_month = $_REQUEST['fecha_etapa_f_month'];}//date ( 'd' ); if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
    //$fecha_etapa_f_year = '0';if (isset($_REQUEST['fecha_etapa_f_year'])){$fecha_etapa_f_year = $_REQUEST['fecha_etapa_f_year'];}//date ( 'm' );
        echo form_open(site_url().'/admin/cuentas/reporte/etapas/',array('id' => 'form_reg'));
        
        echo '<div class="campo">';
        echo form_label('Rut', 'rut');
        echo form_input('rut', $rut,'');
        echo form_error('rut');
        echo '</div>';
        
        echo '<div class="campo">';
        echo form_label('Procurador', 'id_procurador');
        echo form_dropdown('id_procurador',$procuradores, $id_procurador);
        echo form_error('id_procurador');
        echo '</div>';

        echo '<div class="campo">';
        echo form_label('Mandante', 'id_mandante');
        echo form_dropdown('id_mandante',$mandantes, $id_mandante);
        echo form_error('id_mandante');
        echo '</div>';

        echo '<div class="campo">';
        echo form_label('Etapa', 'etapa');
        echo form_dropdown('etapa',$etapas, $etapa);
        echo form_error('etapa');
        echo '</div>';
        
        echo '<div class="campo">';
        echo form_label('Fecha etapa', 'fecha_etapa_year');
        echo day_dropdown('fecha_etapa_day',$fecha_etapa_day).month_dropdown('fecha_etapa_month',$fecha_etapa_month).year_dropdown('fecha_etapa_year',$fecha_etapa_year,2010);
        echo form_error('fecha_etapa_year');
        echo '</div>';
		
		
		echo '<div class="campo">';
        echo form_label('Rango', 'rango');
        echo form_input('rango', $rango,'id="rango" style="width:180px"');
        echo form_error('rango');
        echo '</div>';
        
		
        // -- agregado campo estado ---
        $estado = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : array();
        if(isset($estados_cuenta[-1])) unset($estados_cuenta[-1]);
        echo '<div class="campo">';
        echo form_label('Estado cuenta', 'estado[]');        
        echo form_multiselect('estado[]', $estados_cuenta, $estado);
        echo '</div>';
        // ---------------------------
		
		
        $modo = isset($_REQUEST['modo']) ? $_REQUEST['modo'] : 'todas';
        echo '<div class="campo">'.
            '<span>Modo de busqueda</span>'.
            '<label>'.form_radio(array('name'=>'modo', 'value'=>'todas', 'checked'=> ($modo == 'todas' ? true : false), 
                      'class'=>'check')).' Listar todas las etapas </label>'.
            '<label>'.form_radio(array('name'=>'modo', 'value'=>'ultima', 'checked'=> ($modo == 'ultima' ? true : false), 
                      'class'=>'check')).' Listar ultima etapa de cuenta </label>'.
            '</div>';
        // ------------------------------

        
		 echo '<div class="campo">';
        echo form_label('Rol', 'rol');
        echo form_input('rol', $rol,'id="rol"');
        echo form_error('rol');
        echo '</div>';

		echo '<div class="campo">';
		echo form_label('Comunas', 'nombre'/*,$attributes*/);
		echo form_dropdown('nombre', $comunas, $nombre);
		echo form_error('nombre');
		echo '</div>';

        echo '<div class="campo">';
        echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
        echo '</div>';
        
         
		echo form_close();
    
	?>
    
    <?php if($nodo->nombre == 'swcobranza'):
		 echo '<div class="campo">';
         echo '<a href="'.site_url().'/admin/cuentas/cuenta_exportar'.$suffix.'" class="ico-excel">Exportar a Excel</a>';
         echo '</div>';
		 endif;?>
    
    
    <?php if($nodo->nombre == 'fullpay'):  
	echo '<div class="campo">';
         echo '<a href="'.site_url().'/admin/cuentas/exportador_cuentas_etapas_fullpay'.$suffix.'" class="ico-excel">Exportar a Excel</a>';
         echo '</div>';
	  endif;?>
    
    
    
  <?php	/*print_r($suffix);*/ ?>
     <div class="clear"></div>
    </div><!-- campo -->
	<div class="clear height"></div>
</div><!--gregar-noticia-->
<?php $etapas_view = array(); $e = $this->etapas_m->get_all(); if (count($e)>0){ foreach ($e as $key=>$val){$etapas_view[$val->id] = $val->etapa;}} ?>
<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">
<div class="content_tabla">
<table width="100%" height="82" class="listado">
<tr class="menu"  style="line-height:20px; height:50px;">
<td width="9%" height="42" class="nombre">Id</td>
  <td width="9%" height="42" class="nombre">Mandante</td>
  <td width="6%" class="nombre">Rut</td>
  <td width="11%" class="nombre">Deudor</td>
  <td width="10%" class="nombre">Procurador</td>
  <td width="10%" class="nombre">Etapa del Juicio Anterior</td>
  <td width="10%" class="nombre">Fecha Anterior</td>
  <td width="14%" class="nombre">Etapa del Juicio</td>
  <td width="7%" class="nombre">Fecha Etapa</td>
  <td width="7%" class="nombre">Días transcurridos</td>
  <td width="7%" class="nombre">Días de atraso</td>
  <td width="6%" class="nombre">Estado Cuenta</td>
  <td width="10%" class="nombre">Juzgado</td>
  <td width="7%" class="nombre">Nº Rol</td>
  <td width="10%" class="nombre">Comuna</td>
  <td width="10%" class="nombre">Juzgado</td>
 <!-- <td width="10%" class="nombre">Deuda total</td> -->
  
  
</tr>
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>" height="50">
    <td width="2%"><a href="<?php echo site_url().'/admin/gestion/index/'.$val->idcuenta;?>" title=""><?php echo $val->idcuenta;?></a></td>

    <td> <!--input name="chk-<?php echo $check_id;?>" type="checkbox" value="" class="check"--><a href="<?php echo site_url().'/admin/gestion/index/'.$val->id;?>" title=""><?php if($nodo->nombre == 'fullpay') { ?>
            <?php    if($val->clase_html == 1  ) {  ?>
                <?php echo '<span class="red">'.$val->codigo_mandante.'</span>' ?> <?php  } else {  echo $val->codigo_mandante; } ?>      </td>
    <?php } ?></td>


  <td width="6%"><?php echo $val->rut;?></td>
  <td width="11%"><?php echo $val->usr_nombres.' '.$val->usr_ap_pat.' '.$val->usr_ap_mat;?></td>
  <td width="10%"><?php echo $val->nombres.' '.$val->apellidos;?></td>
  <td width="10%">
  <?php $etapa_anterior = array(); $this->db->order_by('fecha_etapa DESC'); $etapa_anterior = $this->cuentas_etapas_m->get_by(array('id_cuenta'=>$val->id_cuenta,'id !='=>$val->id,'activo'=>'S','fecha_etapa <'=>$val->fecha_etapa));?>
  <?php if (count($etapa_anterior)==1 && array_key_exists($etapa_anterior->id_etapa,$etapas_view)){echo $etapas_view[$etapa_anterior->id_etapa];}?>
  </td>
  <td width="10%"> <?php if (count($etapa_anterior)==1 && array_key_exists($etapa_anterior->id_etapa,$etapas_view)){echo date("d-m-Y", strtotime($etapa_anterior->fecha_etapa));}?></td>
  <td width="14%"><?php echo $val->etapa;?> <?php if ($val->dias_alerta>0):?>(<?php echo $val->dias_alerta;?>)<?php endif;?></td>
  <td width="7%"><?php echo date("d-m-Y", strtotime($val->fecha_etapa));?></td>
  <td width="7%"><?php echo $val->duracion?></td>
  <td width="7%"><?php if ($val->dias_alerta>0 && $val->duracion>$val->dias_alerta){echo $val->duracion-$val->dias_alerta;}?></td>
  <td width="6%"><?php echo $val->estado;?></td>
  <td width="10%"><?php echo $val->tribunal.' / '.$val->distrito;?></td>
 <td width="7%"><?php echo $val->rol;?></td>
   <?php if ($nodo->nombre=='fullpay'):?>
  <td width="10%"> 
    <?php if($val->comuna != ''){ ?><?php echo $val->comuna;?> <?php } else {echo '-'; ?><?php } ?>
 </td>
 <td><?php if($val->tribunal_padre_comuna != ''){ ?><?php echo $val->tribunal_padre_comuna;?> <?php } else {echo '-'; ?><?php } ?></td>   
 <td><?php // echo $val->monto_deuda;?></td>  
 <?php endif;?>
  
</tr>
<?php ++$i;endforeach;?>
</table>
</div><!--content_tabla-->
</div><!--tabla-listado-->
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
<?php endif;?>  
<?php echo $this->pagination->create_links(); ?>

<script type="text/javascript">
$(document).ready(function() {
 $("#rut").Rut({
 	on_error: function(){ alert('El R.U.T. es incorrecto. Formato: 11.111.111-1'); $("#rut").val('');  } 
 }); 
 $('#rango').daterangepicker();


}); 
</script>
