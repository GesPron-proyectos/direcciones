<div class="table-m-sep">
	<div class="table-m-sep-title">
    <?php $this->load->view('backend/templates/mod/cat_tools'); ?>
	</div>
</div>

<div class="agregar-noticia">
	<div class="agregar">
		<a class="nueva" href="<?php echo site_url();?>/admin/procurador/form/">Crear Nuevo Procurador</a>
	</div>
	<div class="clear height"></div>
</div>

<div class="clear"></div>
<div class="tabla-listado">
    <table class="listado" width="100%">
		<tr class="menu">
			<td class="nombre">RUT</td>
			<td class="nombre">DV</td>
			<td class="apellido">CUENTA RUT</td>
			<td class="rut">DATOS</td>
			
			<td width="50" class="herramientas">Herramientas</td>
		</tr>

		<div class="content_tabla">
		  <?php if (count($lists)>0): ?>
		  <?php include APPPATH.'views/backend/templates/procurador/list_tabla.php';?>
		  <?php endif;?>
		  <?php echo $this->pagination->create_links(); ?>
		</div>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>