<?php 
$tipo = $this->input->post('tipo');
$observacion = $this->input->post('observacion');
$tipo_vehiculo = $this->input->post('tipo_vehiculo');
$marca = $this->input->post('marca');
$modelo = $this->input->post('modelo');
$n_motor = $this->input->post('n_motor');
$color = $this->input->post('color');
$inscripcion = $this->input->post('inscripcion');
if ($idregistro!=''){
	$tipo = $bien->tipo;
	$observacion = $bien->observacion;
	$tipo_vehiculo = $bien->tipo_vehiculo;
	$marca = $bien->marca;
	$modelo = $bien->modelo;
	$n_motor = $bien->n_motor;
	$color = $bien->color;
	$inscripcion = $bien->inscripcion;
}
?>
<?php echo form_open(site_url().'/admin/gestion/guardar_garantia/'.$id.'/'.$idregistro); ?>
<table class="stable" width="100%" border="1">
<tr><td >
	<table class="stable" width="100%">
	<tr><td colspan="4"><h3><?php if ($idregistro==''):?>Ingresar una nueva garantia:<?php else:?>Editar garantia #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="bienes">Cerrar</a><?php endif;?></h3><br></td></tr>
	<tr>
		<td>Tipo:</td>
		<td>
		<?php echo form_dropdown('tipo_', $tipogarantias,  $tipo);?>
		<?php echo form_error('tipo_','<br><span class="error">','</span>');?>
		</td>
	</tr>
	<tr id="iddes">
		<td>Desglose:</td>
		<td>	
			<div id="ajax_id_desglosegarantias">
				  <?php echo form_dropdown('tipo', $tipogarantias_,  $tipo);?>        
			</div>
			<?php echo form_error('tipo','<br><span class="error">','</span>');?>
		</td>
	</tr>
	<tr id="idobs">		<td>Observación:</td>
		<td>
		<input name="observacion" value="<?php echo $observacion;?>">
		<?php echo form_error('observacion','<br><span class="error">','</span>');?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div id="autos" style="display:none;">
			<table>
				<tr >
					<td>Tipo Vehículo:</td>
					<td>
					<?php echo form_dropdown('tipo_vehiculo', $tipos_vehiculos,  $tipo);?>
					<?php echo form_error('tipo_vehiculo','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr >
					<td>Fecha Contrato:</td>
					<td>
					<input name="FechaCont" value="<?php echo $FechaCont;?>">
					<?php echo form_error('FechaCont','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr>
					<td>N° Repertorio:</td>
					<td>
					<input name="Nrepertorio" value="<?php echo $Nrepertorio;?>">
					<?php echo form_error('Nrepertorio','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr>
					<td>Marca:</td>
					<td>
					<input name="marca" value="<?php echo $marca;?>">
					<?php echo form_error('marca','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr>
					<td>Modelo:</td>
					<td>
					<input name="modelo" value="<?php echo $modelo;?>">
					<?php echo form_error('modelo','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr >
					<td>Nº motor:</td>
					<td>
					<input name="n_motor" value="<?php echo $n_motor;?>">
					<?php echo form_error('n_motor','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr>
					<td>Color:</td>
					<td>
					<input name="color" value="<?php echo $color;?>">
					<?php echo form_error('color','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr>
					<td>N° Chasis:</td>
					<td>
					<input name="nchasis" value="<?php echo $nchasis;?>">
					<?php echo form_error('nchasis','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr>
					<td>Año:</td>
					<td>
					<input name="anio" value="<?php echo $anio;?>">
					<?php echo form_error('anio','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr >
					<td>Placa unica:</td>
					<td>
					<input name="placaunica" value="<?php echo $placaunica;?>">
					<?php echo form_error('placaunica','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr>
					<td>Placa patente:</td>
					<td>
					<input name="placapatente" value="<?php echo $placapatente;?>">
					<?php echo form_error('placapatente','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr>
					<td>Fecha exigible:</td>
					<td>
						<input name="fechaex" value="<?php echo $fechaex;?>">
						<?php echo form_error('fechaex','<br><span class="error">','</span>');?>
					</td>
				</tr>
				<tr >
					<td>Inscripción:</td>
					<td>
					<input name="inscripcion" value="<?php echo $inscripcion;?>">
					<?php echo form_error('inscripcion','<br><span class="error">','</span>');?>
					</td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div id="personal" style="display:none;">			
				<table>
					<tr >
						<td>Tipo Garante Personal:</td>
						<td>
						<input name="tipog" value="<?php echo $tipog;?>">
						<?php echo form_error('tipog','<br><span class="error">','</span>');?>
						</td>
					</tr>
					<tr >
						<td>Nombre Garante:</td>
						<td>
						<input name="nombreg" value="<?php echo $nombreg;?>">
						<?php echo form_error('nombreg','<br><span class="error">','</span>');?>
						</td>
					</tr>
					<tr>
						<td>Rut Garante:</td>
						<td>
						<input name="rutg" value="<?php echo $rutg;?>">
						<?php echo form_error('rutg','<br><span class="error">','</span>');?>
						</td>
					</tr>
					<tr >
						<td>Domicilio Garante:</td>
						<td>
						<input name="domiciliog" value="<?php echo $domiciliog;?>">
						<?php echo form_error('domiciliog','<br><span class="error">','</span>');?>
						</td>
					</tr>
				</table>		

			</div>
			</td>
			</tr>
	<tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
	</table>
</td></tr>
<tr><td >

<table class="stable" width="100%">
<tr><td >

<table class="stable" width="100%"> 
<tr><td colspan="2"><h3>Prenda:</h3><br></td></tr>
<tr>
    <td colspan="2">
		<table class="stable grilla" width="100%">
			<tr class="titulos-tabla">
				<td>#</td>
				<td>Tipo</td>
				<td>Observación</td>
				<?php if($nodo->nombre == 'fullpay'):?>
				<td>Tipo vehículo</td>
				<td>Marca</td>
				<td>Modelo</td>
				<td>Nº Motor</td>
				<td>Color</td>
				<td>Inscripción</td>
				<?php endif;?>
				
			  </tr>
			<?php if (count($prendas)>0):?>
			<?php foreach ($prendas as $key=>$bien):?>
				<tr>
				<td>#<?php echo $bien->id;?></td>
				<td><?php echo $tipos_bienes[$bien->tipo];?></td>
				<td><?php echo $bien->observacion;?></td>
				<?php if($nodo->nombre == 'fullpay'):?>
				<td><?php echo $tipos_vehiculos[$bien->tipo_vehiculo];?></td>
				<td><?php echo $bien->marca;?></td>
				<td><?php echo $bien->modelo;?></td>
				<td><?php echo $bien->n_motor;?></td>
				<td><?php echo $bien->color;?></td>
				<td><?php echo $bien->inscripcion;?></td>
				<?php endif;?>
				<td>
				
			   </tr>
			<?php endforeach;?>
			<?php else:?>
			<tr><td colspan="4">No hay registros ingresados</td></tr>
			<?php endif;?>
		</table>
	</td></tr>
		</table>        
	</td>
</tr>

<tr><td >

<table class="stable" width="100%"> 
<tr><td colspan="2"><h3>Inmueble:</h3><br></td></tr>
<tr>
    <td colspan="2">
    <table class="stable grilla" width="100%">
        <tr class="titulos-tabla">
        	<td>#</td>
            <td>Observación</td>
          </tr>
        <?php if (count($inmueblesg)>0):?>
        <?php foreach ($inmueblesg as $key=>$bien):?>
            <tr>
            <td>#<?php echo $bien->id;?></td>
            <td><?php echo $bien->observacion;?></td>            
           </tr>
        <?php endforeach;?>
        <?php else:?>
        <tr><td colspan="4">No hay registros ingresados</td></tr>
        <?php endif;?>
    </table>
</td></tr>
</table>

</td></tr>

<tr><td >

<table class="stable" width="100%"> 
<tr><td colspan="2"><h3>Personal:</h3><br></td></tr>
<tr>
    <td colspan="2">
    <table class="stable grilla" width="100%">
        <tr class="titulos-tabla">
        	<td>#</td>
            <td>Tipo Garante</td>
            <td>Nombre G.</td>
            <td>Rut G.</td>
            <td>Domicilio</td>
          </tr>
        <?php if (count($personalg)>0):?>
        <?php foreach ($personalg as $key=>$bien):?>
            <tr>
            <td>#<?php echo $bien->id;?></td>
            <td><?php echo $bien->tipog;?></td>
            <td><?php echo $bien->nombreg;?></td>
            <td><?php echo $bien->rutg;?></td>
            <td><?php echo $bien->domiciliog;?></td>            
           </tr>
        <?php endforeach;?>
        <?php else:?>
        <tr><td colspan="4">No hay registros ingresados</td></tr>
        <?php endif;?>
    </table>
</td></tr>
</table>
</td></tr>
</table>

</td></tr>
</table>

<?php echo form_close();?>

<script type="text/javascript">
$(window).load(function() {
	
	$(document).on("change", "select[name='tipo_']", function(event){
		var id_distrito = $(this).val();
		$.ajax({	
		  type: 'GET',
		  url: '<?php echo site_url();?>/admin/garantias/anidado/'+id_distrito,
		  success: function(data) {
			  $("#ajax_id_desglosegarantias").html(data);
		  },
		 
		});
	});
	
	$(document).on("change", "select[name='tipo_']", function(event){
		var id_tipo = $(this).val();
		switch(id_tipo)
		{
			case "1":
			case "2":
				//$('#autos').show();
				$('#iddes').show();
				$('#idobs').show();				
				$('#personal').hide();
			break;
			case "3":
				$('#autos').hide();
				$('#iddes').hide();
				$('#idobs').hide();				
				$('#personal').show();
				$('#personal').css("personal", "block");			
			break;
		}
	});
	
	$(document).on("change", "select[name='tipo']", function(event){
		var id_tipo = $(this).val();
		//alert(id_tipo);
		switch(id_tipo)
		{
			case "4":
				$('#autos').show();
				$('#autos').css("autos", "block");			
			break;
			
			case "5":
			case "6":				
				$('#iddes').show();
				$('#idobs').show();				
				$('#personal').hide();
				$('#autos').hide();
			break;
			
			case "7":
				$('#autos').hide();
				$('#iddes').hide();
				$('#idobs').hide();				
				$('#personal').show();
				$('#personal').css("personal", "block");			
			break;
		}

	});

	$(document).on("change", "select[name='id_distritoE']", function(event){
		var id_distrito = $(this).val();
		$.ajax({	
		  type: 'GET',
		  url: '<?php echo site_url();?>/admin/tribunales/anidado/'+id_distrito,
		  success: function(data) {
			  $("#ajax_id_distritoE").html(data);
		  },
		 
		});
	});	


});
</script>