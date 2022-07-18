<div class="table-m-sep">
	<div class="table-m-sep-title"> 
		<?php $this->load->view('backend/templates/mod/cat_tools');?>
	</div>
</div>

<div class="agregar-noticia">
	<div class="agregar">
		<a class="nueva" href="<?php echo site_url();?>/admin/abogado/form/">Crear Nuevo Abogado</a>
	</div>
	<div class="clear height"></div>
</div>
<div class="clear"></div>

<div class="tabla-listado">
    <table class="listado" width="100%">
		<tr class="menu">
			<td>Nro</td>
			<td class="nombre">Nombre y Apellidos</td>
			<td class="rut">RUT</td>
			<td class="correo">Sistema</td>
			<td width="50" class="herramientas">Herramientas</td>
		</tr>
		<div class="content_tabla">
		  <?php if (count($lists)>0): ?>
		  <?php include APPPATH.'views/backend/templates/abogado/list_tabla.php'; ?>
		  <?php endif;?>
		</div>
    </table>
</div>

<?php //$this->load->view('backend/templates/mod/paginacion'); ?>