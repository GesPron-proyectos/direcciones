<style>
table.listado input, table.listado select{ font-size:11px; margin:5px 0 5px 5px;}

div.campo{
	margin-top:9px;
}

div.campo p {
	float: inherit !important;
	margin-left: 107px;
	margin-bottom: 0px !important;
}

div.campo label {
    width: 100px !important;
}

div.campo input {
    width: 285px; !important;
}

</style>
<form action="<?php echo site_url().'/admin/'.$current.'/form/guardar/';?><?php if (!empty($id)){echo $id;}?>"	method="post">
    
	<div class="titulo">
	<strong style="float:left; margin-right:10px;">Datos de la etapa</strong><a name="top">&nbsp;</a>
	
	<?php if (validation_errors()!=''): ?>
		<span class="alerta"></span><span class="error">Faltan campos por completar</span> 
	<?php endif;?>
	    
	    <span class="flechita"></span>
	</div>
	
	<div class="blq">
		<div class="dos" style=" width:100%;">
		
<?php 
	
	if($_POST){ foreach($_POST as $key=>$val){ $datos[$key] = $val; } }

	echo form_open(site_url().'/admin/'.$current.'/',array('id' => 'form_reg'));
	
	echo '<div class="campo">';
	echo form_label('Nombre Etapa', 'etapa'/*,$attributes*/);
	echo form_input('etapa', $datos['etapa']);
	echo form_error('etapa');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Código', 'codigo'/*,$attributes*/);
	echo form_input('codigo', $datos['codigo']);
	echo form_error('codigo');
	echo '</div>';
	
	
	echo '<div class="campo">';
	echo form_label('Tipo', 'tipo'/*,$attributes*/);
	echo form_input('tipo', $datos['tipo']);
	echo form_error('tipo');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('posicion', 'posicion'/*,$attributes*/);
	echo form_input('posicion', $datos['posicion']);
	echo form_error('posicion');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Sucesor', 'sucesor'/*,$attributes*/);
	echo form_input('sucesor', $datos['sucesor']);
	echo form_error('sucesor');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Dias Alerta','dias_alerta'/*,$attributes*/);
	echo form_input('dias_alerta',$datos['dias_alerta']);
	echo form_error('dias_alerta');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Texto Alerta', 'texto_alerta'/*,$attributes*/);
	echo form_input('texto_alerta', $datos['texto_alerta']);
	echo form_error('texto_alerta');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Clasificación', 'clasificacion'/*,$attributes*/);
	echo form_input('clasificacion', $datos['clasificacion']);
	echo form_error('clasificacion');
	echo '</div>';
	
	if($nodo->nombre == 'fullpay'){
	echo '<div class="campo">';
	echo form_label('Dias Alerta Proceso','dias_alerta_proceso'/*,$attributes*/);
	echo form_input('dias_alerta_proceso',$datos['dias_alerta_proceso']);
	echo form_error('dias_alerta_proceso');
	echo '</div>';
	}
	
	 if($nodo->nombre == 'swcobranza'){
	echo '<div class="campo">';
	echo form_label('Etapa Comisión', 'etapa_comision'/*,$attributes*/);
	echo form_input('etapa_comision', $datos['etapa_comision']);
	echo form_error('etapa_comision');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Etapa procesal', 'etapa_procesal'/*,$attributes*/);
	echo form_input('etapa_procesal', $datos['etapa_procesal']);
	echo form_error('etapa_procesal');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Alias', 'alias'/*,$attributes*/);
	echo form_input('alias', $datos['alias']);
	echo form_error('alias');
	echo '</div>';
	
	
	echo '<div class="campo">';
	echo form_label('Sub etapa procesal', 'sub_etapa_procesal'/*,$attributes*/);
	echo form_input('sub_etapa_procesal', $datos['sub_etapa_procesal']);
	echo form_error('sub_etapa_procesal');
	echo '</div>';

         echo '<div class="clear"></div><div class="campo">';
         echo form_label('Diligencias', 'id_diligecia');
         echo form_dropdown('id_diligencia', $diligencias,$datos['id_diligencia']);
         echo form_error('id_diligencia');
         echo '</div>';
	
	
	
	}



/*
echo '<div class="campo">';
echo form_label('Tributal', 'id_tribunal');
echo form_dropdown('id_tribunal', $tribunales);
echo form_error('id_tribunal');
echo '</div>';*/

	echo '<div class="campo">';
	echo form_label('&nbsp;', ''/*,$attributes*/);
	echo form_submit(array('name' => 'Guardar', 'class' => 'boton'), 'Guardar');
	echo '</div>';

	echo form_close();
?>
		
		    
		</div>
		<div class="clear"></div>
	</div>
	
</form>