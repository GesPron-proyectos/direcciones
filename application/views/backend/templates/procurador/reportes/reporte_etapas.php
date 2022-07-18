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
    $fecha_etapa_month = '0';if (isset($_REQUEST['fecha_etapa_month'])){$fecha_etapa_month = $_REQUEST['fecha_etapa_month'];}//date ( 'd' ); if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
    $fecha_etapa_year = '0';if (isset($_REQUEST['fecha_etapa_year'])){$fecha_etapa_year = $_REQUEST['fecha_etapa_year'];}//date ( 'm' );
	
	$fecha_etapa_f_month = '0';if (isset($_REQUEST['fecha_etapa_f_month'])){$fecha_etapa_f_month = $_REQUEST['fecha_etapa_f_month'];} date ( 'd' ); if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
    $fecha_etapa_f_year = '0';if (isset($_REQUEST['fecha_etapa_f_year'])){$fecha_etapa_f_year = $_REQUEST['fecha_etapa_f_year'];} date ( 'm' );
        echo form_open(site_url().'/admin/procurador/reporte/etapas/',array('id' => 'form_reg'));
        
        echo '<div class="campo">';
        echo form_label('Rut', 'rut');
        echo form_input('rut', $rut);
        echo form_error('rut');
        echo '</div>';
        
       // echo '<div class="campo">';
        //echo form_label('Procurador', 'id_procurador');
        //echo form_dropdown('id_procurador', $procuradores, $id_procurador);
        //echo form_error('id_procurador');
        //echo '</div>';

        echo '<div class="campo">';
        echo form_label('Mandante', 'id_mandante');
        echo form_dropdown('id_mandante', $mandantes, $id_mandante);
        echo form_error('id_mandante');
        echo '</div>';

        echo '<div class="campo">';
        echo form_label('Etapa', 'etapa');
        echo form_dropdown('etapa', $etapas, $etapa);
        echo form_error('etapa');
        echo '</div>';
        
        echo '<div class="campo">';
        echo form_label('Fecha etapa', 'fecha_etapa_year');
        echo month_dropdown('fecha_etapa_month',$fecha_etapa_month).year_dropdown('fecha_etapa_year',$fecha_etapa_year,2010);
        echo form_error('fecha_etapa_year');
        echo '</div>';

        // -- agregado campo estado ---
        $estado = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : array();
        if(isset($estados_cuenta[-1])) unset($estados_cuenta[-1]);
        echo '<div class="campo">';
        echo form_label('Estado cuenta', 'estado[]');        
        echo form_multiselect('estado[]', $estados_cuenta, $estado);
        echo '</div>';
        // ---------------------------

		    // ------------------------------
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
        echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
        echo '</div>';
        
        echo '<div class="campo">';
		 if ($nodo->nombre=='swcobranza'):
        echo '<a href="'.site_url().'/admin/procurador/reporte/etapas/exportar'.$suffix.'" class="ico-excel">Exportar a CSV</a>';
         endif;
		 
		 if($nodo->nombre=='fullpay'){
			 echo '<a href="'.site_url().'/admin/procurador/reporte/etapas/exportar_etapas_juicio'.$suffix.'" class="ico-excel">Exportar a Excel</a>';
			 }
		
		echo '</div>';

        echo form_close();
    ?>
    <div class="clear"></div>
    </div><!-- campo -->
	<div class="clear height"></div>
</div><!--gregar-noticia-->
<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">
<div class="content_tabla">
<table width="100%" height="82" class="listado">
<tr class="menu"  style="line-height:20px; height:50px;">
   <td width="9%" height="42" class="nombre">Mandante</td>
  <td width="6%" class="nombre">Rut</td>
  <td width="11%" class="nombre">Deudor</td>
  <td width="20%" class="nombre">Dirección</td>
  <td width="10%" class="nombre">Procurador</td>
  <td width="14%" class="nombre">Etapa del Juicio</td>
  <td width="7%" class="nombre">Fecha Etapa</td>
  <td width="6%" class="nombre">Estado Cuenta</td>
  <td width="10%" class="nombre">Juzgado</td>
  <td width="7%" class="nombre">Nº Rol</td>
</tr>
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>" height="50">
  <td width="9%"><?php echo $i.' '.$val->razon_social;?></td>
  <td width="6%"><?php echo $val->rut;?></td>
  <td width="11%"><?php echo $val->usr_nombres.' '.$val->usr_ap_pat.' '.$val->usr_ap_mat;?></td>
  <td width="20%"><?php echo $val->direccion.' Nº '.$val->direccion_numero.' dpto '.$val->direccion_dpto; if ($val->comuna!=''){ echo ', '.$val->comuna;} echo ', '.$val->ciudad;?></td>
  <td width="10%"><?php echo $val->nombres.' '.$val->apellidos;?></td>
  <td width="14%"><?php echo $val->etapa?></td>
  <td width="7%"><?php echo date("d-m-Y", strtotime($val->fecha_etapa));?></td>
  <td width="6%"><?php echo $val->estado;?></td>
  <td width="10%"><?php echo $val->tribunal.' / '.$val->distrito;?></td>
  <td width="7%"><?php echo $val->rol;?></td>
</tr>
<?php ++$i;endforeach;?>
</table>
</div><!--content_tabla-->
</div><!--tabla-listado-->
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
<?php endif;?>  
<?php echo $this->pagination->create_links(); ?>