<div class="table-m-sep">
	<div class="table-m-sep-title">
    <?php $this->load->view('backend/templates/mod/cat_tools'); ?>
	</div>
</div>

<div class="agregar-noticia">
	<div class="agregar">
		<a class="nueva" href="<?php echo site_url();?>/admin/cuentas/form/">IMPORTAR</a>
	</div>
	<div class="clear height"></div>
</div>

<div class="clear"></div>
<div class="tabla-listado">
    <table class="listado" width="100%">
		<tr class="menu">
			<td class="nombre">rut</td>
			<td class="nombre">dv</td>
			<td class="apellido">cuenta rut</td>
			<td class="rut">datos</td>
		
			<td width="50" class="herramientas">Herramientas</td>
		</tr>

		<div class="content_tabla">
		  <?php if (count($lists)>0): ?>
		  <?php include APPPATH.'views/backend/templates/cuentas/list_tabla.php';?>
		  <?php endif;?>
		  <?php echo $this->pagination->create_links(); ?>
		</div>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>