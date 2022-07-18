<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Datos Adicionales</strong></h2>
  </div>
</div>


<div class="titulo"><?php echo ucwords($titulo)?></div>
<div style="padding:15px;background-color:#FFF; border:1px solid #CDCCCC;">
<div class="agregar-noticia">
<div>
<?php 
	echo form_open(site_url().'/admin/'.$current.'/datos_adicionales_edit/'.$titulo.'/'.$id,array('id' => 'form_reg','autocomplete'=>'off'));
	
	if( $titulo != 'bien'){
		
		echo '<div class="campo">';
		echo form_label(ucwords($titulo), $titulo);
		echo form_input($titulo, $dato,'style="width:300px;"');
		echo form_error($titulo);
		echo '</div>';
		
	}else{// bien
		$bienes = array('1'=>'Vehículo','2'=>'Inmueble');
		
		echo '<div class="campo">';
		echo form_label(ucwords($titulo), $titulo);
		echo form_dropdown($titulo, $bienes,$dato,'style="width:300px;"');
		echo form_error($titulo);
		echo '</div>';

	}
	
	echo '<div class="campo">';
	echo form_label('Observacion', 'observacion');
	echo form_input('observacion', $observacion,'style="width:300px;"');
	echo form_error('observacion');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Estado', 'estado');
	echo form_dropdown('estado', array('0'=>'Sin confirmación','1'=>'Vigente','2'=>'No Vigente'), $estado);
	echo form_error('estado');
	echo '</div>';
	
	
	
	echo '<div class="campo">';
	echo '<label>&nbsp;</label>';
	echo form_submit(array('name' => 'Modificar', 'class' => 'boton'), 'Modificar');
	echo '</div>';

	echo form_close();
?>
</div><!-- campo -->
<div class="clear height"></div>
</div>

</div>


<div class="agregar-noticia">
	<div class="agregar" style="float:right;">
		<a class="nueva" style="background:none;" href="<?php echo site_url()?>/admin/cuentas/datos_adicionales/<?php echo $id_cuenta?>">Volver a datos adicionales</a>
	</div>
	<div class="agregar" style="float:right;">
		<a class="nueva" style="background:none;" href="<?php echo site_url()?>/admin/cuentas/form/editar/<?php echo $id_cuenta?>">volver a cuentas</a>
	</div>
	<div class="clear height"></div>
</div>



