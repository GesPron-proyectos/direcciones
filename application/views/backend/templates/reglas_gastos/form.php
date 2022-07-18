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
	echo form_label('Rango1', 'rango1'/*,$attributes*/);
	echo form_input('rango1', $datos['rango1']);
	echo form_error('rango1');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Rango2', 'rango2'/*,$attributes*/);
	echo form_input('rango2', $datos['rango2']);
	echo form_error('rango2');
	echo '</div>';
	
	
	echo '<div class="campo">';
	echo form_label('Monto Gasto', 'monto_gasto'/*,$attributes*/);
	echo form_input('monto_gasto', $datos['monto_gasto']);
	echo form_error('monto_gasto');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Mandante', 'id_mandante'/*,$attributes*/);
	echo form_dropdown('id_mandante', $mandantes, $datos['id_mandante'], ' ');
	echo form_error('id_mandante');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Diligencia', 'id_diligencia'/*,$attributes*/);
	echo form_dropdown('id_diligencia', $diligencias, $datos['id_diligencia'], ' ');
	echo form_error('id_diligencia');
	echo '</div>';
	
		
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