<br>
<div class="titulo">
	<strong style="float:left; margin-right:10px;">
	<a href="<?php echo site_url()?>/admin/cuentas/form/editar/<?php echo $id?>">Datos de la cuenta</a></strong> / 
	Datos Adicionales
			<span class="flechita"></span>
	<div class="clear"></div>
</div>

<div style="padding:15px;background-color:#FFF; border:1px solid #CDCCCC;">
<div class="agregar-noticia">
<div>
<?php 
$rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
$nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];}
$ap_pat = ''; if (isset($_REQUEST['ap_pat'])){$ap_pat = $_REQUEST['ap_pat'];}
$id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}

	echo form_open(site_url().'/admin/'.$current.'/datos_adicionales/'.$id,array('id' => 'form_reg','autocomplete'=>'off'));
	
	echo '<div class="campo">';
	echo form_label('Nueva Dirección', 'direccion');
	echo form_input('direccion', $rut,'style="width:300px;"');
	echo form_error('direccion');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Observación', 'observacion');
	echo form_input('observacion', $rut,'style="width:300px;"');
	echo form_error('observacion');
	echo '</div>';
	
	echo '<div class="campo">';
	echo '<label>&nbsp;</label>';
	echo form_submit(array('name' => 'Agregar', 'class' => 'boton'), 'Agregar');
	echo '</div>';

	echo form_close();
?>
</div><!-- campo -->
<div class="clear height"></div>
</div>

<?php $estados =array('0'=>'Sin confirmación','1'=>'Vigente','2'=>'No Vigente');?>
<?php if (count($direccion_list)>0): ?>
<?php //echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">

<div class="content_tabla">
	<table class="listado" width="100%">
		<tr class="titulos-tabla" style="line-height:20px; height:30px;">
		  <td>Dirección</td>
		  <td>Observación</td>
		  <td>Estado</td>
		  <td></td>
		</tr>
		<?php $i='b'; $check_id=1;foreach ($direccion_list as $key=>$val):?>
		
		<tr<?php if ($i=='a'){echo ' class="b"'; $i='b'; }else{echo ' class="a"'; $i='a';}?> id="row-<?php echo $val->id;?>">
		  <td><?php echo $val->direccion;?></td>
		  <td><?php echo $val->observacion;?></td>
		  <td><?php echo $estados[$val->estado]?></td>
		  <td class="tools" width="100%">

			<table width="75%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			  
			    <td width="15%" height="20">
			    <a href="<?php  echo site_url().'/admin/'.$current.'/datos_adicionales_edit/direccion/'.$val->id; ?>" class="editar" title="editar"></a>
				</td>
			
			    <td width="15%">
			    <?php if ($this->session->userdata("usuario_perfil")<=2):?>
			    <a style="cursor:pointer;" class="eliminar xtool" rel="N" href="<?php echo site_url()?>/admin/<?php echo $current?>/datos_adicionales_delete/direccion/<?php echo $val->id?>" title="activo"></a>
			    <?php endif;?>
			    </td>
			    
			  </tr>
			</table>
		</td>
		</tr>
		
		<?php endforeach;?>
	</table>
</div>
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
</div>
<?php //echo $this->pagination->create_links(); ?>
<?php endif;?>
</div>
<div class="agregar-noticia">
	<div class="agregar" style="float:right;">
		<a class="nueva" style="background:none;" href="<?php echo site_url()?>/admin/cuentas/form/editar/<?php echo $id?>">volver a cuentas</a>
	</div>
	<div class="clear height"></div>
</div>









<div class="titulo">Telefonos</div>
<div style="padding:15px;background-color:#FFF; border:1px solid #CDCCCC;">
<div class="agregar-noticia">
<div>
<?php 
$tipos = array('1'=>'Particular','2'=>'Comercial','3'=>'Celular','4'=>'Otro');

	echo form_open(site_url().'/admin/'.$current.'/datos_adicionales/'.$id,array('id' => 'form_reg','autocomplete'=>'off'));
	
	echo '<div class="campo">';
	echo form_label('Nuevo Telefono', 'telefono');
	echo form_input('telefono', $rut,'style="width:300px;"');
	echo form_error('telefono');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Tipo', 'tipo');
	echo form_dropdown('tipo', $tipos,'style="width:300px;"');
	echo form_error('tipo');
	echo '</div>';
	
	
	echo '<div class="campo">';
	echo form_label('Observación', 'observacion');
	echo form_input('observacion', $rut,'style="width:300px;"');
	echo form_error('observacion');
	echo '</div>';
	
	echo '<div class="campo">';
	echo '<label>&nbsp;</label>';
	echo form_submit(array('name' => 'Agregar', 'class' => 'boton'), 'Agregar');
	echo '</div>';

	echo form_close();
?>

</div><!-- campo -->
<div class="clear height"></div>
</div>

<?php if (count($telefono_list)>0): ?>
<?php //echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">

<div class="content_tabla">
	<table class="listado" width="100%">
		<tr class="titulos-tabla" style="line-height:20px; height:30px;">
		  <td>Telefono</td>
		  <td>Observación</td>
		  <td>Tipo</td>
		  <td>Estado</td>
		  <td></td>
		</tr>
		<?php $i='b'; $check_id=1;foreach ($telefono_list as $key=>$val):?>
				<tr<?php if ($i=='a'){echo ' class="b"'; $i='b'; }else{echo ' class="a"'; $i='a';}?> id="row-<?php echo $val->id;?>">
				  <td><?php echo $val->numero;?></td>
				  <td><?php echo $val->observacion;?></td>
				  <td><?php echo $tipos[$val->tipo];?></td> 
				  <td><?php echo $estados[$val->estado]?></td>
				  
					  <td class="tools" width="100%">
	
						<table width="75%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
						  
						    <td width="15%" height="20">
						    <a href="<?php  echo site_url().'/admin/'.$current.'/datos_adicionales_edit/telefono/'.$val->id; ?>" class="editar" title="editar"></a>
							</td>
						
						    <td width="15%">
						    <?php if ($this->session->userdata("usuario_perfil")<=2):?>
						    <a style="cursor:pointer;" class="eliminar xtool" rel="N" href="<?php echo site_url()?>/admin/<?php echo $current?>/datos_adicionales_delete/telefono/<?php echo $val->id?>" title="activo"></a>
						    <?php endif;?>
						    </td>
						    
						  </tr>
						</table>
					</td>
				
				</tr>
		<?php endforeach;?>
	</table>
</div>
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
</div>
<?php //echo $this->pagination->create_links(); ?>
<?php endif;?>
</div>
<div class="agregar-noticia">
	<div class="agregar" style="float:right;">
		<a class="nueva" style="background:none;" href="<?php echo site_url()?>/admin/cuentas/form/editar/<?php echo $id?>">volver a cuentas</a>
	</div>
	<div class="clear height"></div>
</div>







<div class="titulo">Bienes</div>
<div style="padding:15px;background-color:#FFF; border:1px solid #CDCCCC;">
<div class="agregar-noticia">
<div>
<?php 
$tipos = array('1'=>'Particular','2'=>'Comercial','3'=>'Celular','4'=>'Otro');

	echo form_open(site_url().'/admin/'.$current.'/datos_adicionales/'.$id,array('id' => 'form_reg','autocomplete'=>'off'));
	
	$bienes = array('1'=>'Vehículo','2'=>'Inmueble');
	
	echo '<div class="campo">';
	echo form_label('Bien', 'bien');
	echo form_dropdown('bien', $bienes,'style="height:300px;"');
	echo form_error('bien');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Observación', 'observacion');
	echo form_input('observacion', $rut,'style="width:300px;"');
	echo form_error('observacion');
	echo '</div>';
	
	echo '<div class="campo">';
	echo '<label>&nbsp;</label>';
	echo form_submit(array('name' => 'Agregar', 'class' => 'boton'), 'Agregar');
	echo '</div>';

	echo form_close();
?>

</div><!-- campo -->
<div class="clear height"></div>
</div>

<?php if (count($bienes_list)>0): ?>
<?php //echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">

<div class="content_tabla">
	<table class="listado" width="100%">
		<tr class="titulos-tabla" style="line-height:20px; height:30px;">
		  <td>Bien</td>
		  <td>Observación</td>
		  <td>Estado</td>
		  <td></td>
		</tr>
		<?php $i='b'; $check_id=1;foreach ($bienes_list as $key=>$val):?>
				<tr<?php if ($i=='a'){echo ' class="b"'; $i='b'; }else{echo ' class="a"'; $i='a';}?> id="row-<?php echo $val->id;?>">
				  <td><?php echo $bienes[$val->tipo];?></td>
				  <td><?php echo $val->observacion;?></td> 
				  <td><?php echo $estados[$val->estado]?></td>
				  
					  <td class="tools" width="100%">
	
						<table width="75%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
						  
						    <td width="15%" height="20">
						    <a href="<?php  echo site_url().'/admin/'.$current.'/datos_adicionales_edit/bien/'.$val->id; ?>" class="editar" title="editar"></a>
							</td>
						
						    <td width="15%">
						    <?php if ($this->session->userdata("usuario_perfil")<=2):?>
						    <a style="cursor:pointer;" class="eliminar xtool" rel="N" href="<?php echo site_url()?>/admin/<?php echo $current?>/datos_adicionales_delete/bien/<?php echo $val->id?>" title="activo"></a>
						    <?php endif;?>
						    </td>
						    
						  </tr>
						</table>
					</td>
				
				</tr>
		<?php endforeach;?>
	</table>
</div>
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
</div>
<?php //echo $this->pagination->create_links(); ?>
<?php endif;?>
</div>
<div class="agregar-noticia">
	<div class="agregar" style="float:right;">
		<a class="nueva" style="background:none;" href="<?php echo site_url()?>/admin/cuentas/form/editar/<?php echo $id?>">volver a cuentas</a>
	</div>
	<div class="clear height"></div>
</div>






<div class="titulo">Mail's</div>
<div style="padding:15px;background-color:#FFF; border:1px solid #CDCCCC;">
<div class="agregar-noticia">
<div>
<?php 
	echo form_open(site_url().'/admin/'.$current.'/datos_adicionales/'.$id,array('id' => 'form_reg','autocomplete'=>'off'));
	
	echo '<div class="campo">';
	echo form_label('Nuevo Mail', 'mail');
	echo form_input('mail', $rut,'style="width:300px;"');
	echo form_error('mail', '<div class="error">', '</div>');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label('Observación', 'observacion');
	echo form_input('observacion', $rut,'style="width:300px;"');
	echo form_error('observacion');
	echo '</div>';
	
	echo '<div class="campo">';
	echo '<label>&nbsp;</label>';
	echo form_submit(array('name' => 'Agregar', 'class' => 'boton'), 'Agregar');
	echo '</div>';

	echo form_close();
?>

</div><!-- campo -->
<div class="clear height"></div>
</div>

<?php if (count($mail_list)>0): ?>
<?php //echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">

<div class="content_tabla">
	<table class="listado" width="100%">
		<tr class="titulos-tabla" style="line-height:20px; height:30px;">
		  <td>Mail</td>
		  <td>Observación</td>
		  <td>Estado</td>
		  <td></td>
		</tr>
		<?php $i='b'; $check_id=1;foreach ($mail_list as $key=>$val):?>
				<tr<?php if ($i=='a'){echo ' class="b"'; $i='b'; }else{echo ' class="a"'; $i='a';}?> id="row-<?php echo $val->id;?>">
				  <td><?php echo $val->mail;?></td>
				  <td><?php echo $val->observacion;?></td> 
				  <td><?php echo $estados[$val->estado]?></td>
				  
					  <td class="tools" width="100%">
	
						<table width="75%" border="0" cellspacing="0" cellpadding="0">
						  <tr>
						  
						    <td width="15%" height="20">
						    <a href="<?php  echo site_url().'/admin/'.$current.'/datos_adicionales_edit/mail/'.$val->id; ?>" class="editar" title="editar"></a>
							</td>
						
						    <td width="15%">
						    <?php if ($this->session->userdata("usuario_perfil")<=2):?>
						    <a style="cursor:pointer;" class="eliminar xtool" rel="N" href="<?php echo site_url()?>/admin/<?php echo $current?>/datos_adicionales_delete/mail/<?php echo $val->id?>" title="activo"></a>
						    <?php endif;?>
						    </td>
						    
						  </tr>
						</table>
					</td>
				
				</tr>
		<?php endforeach;?>
	</table>
</div>
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
</div>
<?php //echo $this->pagination->create_links(); ?>
<?php endif;?>
</div>
<div class="agregar-noticia">
	<div class="agregar" style="float:right;">
		<a class="nueva" style="background:none;" href="<?php echo site_url()?>/admin/cuentas/form/editar/<?php echo $id?>">volver a cuentas</a>
	</div>
	<div class="clear height"></div>
</div>






<script type="text/javascript">
$(document).ready(function(){            
	$('.eliminar').click(function(){
		if (confirm("Realmente desea eliminar el registro ?") == true){
		}else{
			return false;
		}
	});
}); 
</script>