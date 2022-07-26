<?php $rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];} ?>
<div class="table-m-sep-title">
<h2><strong>Direcciones(<?php echo number_format($total,0,',','.');?>)</strong></h2>
</div>
<div class="agregar-noticia">
	<div class="agregar">
		<a class="nueva" href="<?php echo site_url();?>/admin/importar/cargar_excel_mora/">IMPORTAR DIRECCIONES</a>
	</div>
	

<div class="tabla-listado">
      <form action="<?php echo site_url().'/admin/procurador/';?>" method="get">

          <label style="width:135px; float:center">RUT:</label>
          <input rut="rut" name="rut" type="text" value="<?php echo $rut;?>" style="width:100px;">
          <input type="submit" name="Buscar" value="Buscar" class="boton" style="width:7%;">
      </form>
  </div>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">
	
    <table class="listado" width="100%">
		<tr class="menu">
			<td class="id">ID</td>
			<td class="nombre">RUT</td>
			<td class="apellido">Digito Verificador</td>
			<td class="rut">Cuenta RUT</td>
			<td class="correo">Direccion</td>
		</tr>

		<div class="content_tabla">
		  <?php if (count($lists)>0): ?>
		  <?php include APPPATH.'views/backend/templates/procurador/list_tabla.php';?>
		  <?php endif;?>
		  <?php echo $this->pagination->create_links(); ?>
		</div>
    </table>
</div>
