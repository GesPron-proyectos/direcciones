<div class="table-m-sep">

</div>

<div class="agregar-noticia">
	<div class="agregar">
		<a class="nueva" href="<?php echo site_url();?>/admin/importar/cargar_excel_mora/">IMPORTAR DIRECCIONES</a>
	</div>
	<div class="agregar">
		<a class="nueva" href="<?php echo site_url();?>/admin/procurador/form2/">CREAR PROCURADOR</a>
	</div>
	<div class="agregar">
		<a class="nueva" href="<?php echo site_url();?>/admin/importar/importar_excel_dir/">import</a>
	</div>
	<div class="clear height"></div>
</div>

<div class="clear"></div>
<div class="tabla-listado">
    <table class="listado" width="100%">
		<tr class="menu">
			<td class="nombre">ID</td>
			<td class="nombre">RUT</td>
			<td class="apellido">Digito Verificador</td>
			<td class="rut">Cuenta RUT</td>
			<td class="correo">DATOS</td>

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