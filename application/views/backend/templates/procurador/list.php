
<div class="table-m-sep-title">
<h2><strong>Direcciones(<?php echo count($lists);?>)</strong></h2>
</div>
<div class="agregar-noticia">
  <div class="agregar"> 
  	<a class="nueva" href="<?php echo site_url();?>/admin/importar/cargar_excel_mora">Importar Direcciones</a>
  </div>

  <?php $rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];} 
	$rut_parcial = ''; if (isset($_REQUEST['rut_parcial'])){$rut_parcial = $_REQUEST['rut_parcial'];}
	?>
  <div class="clear height"></div>
   <form action="<?php echo site_url().'/admin/procurador';?>" method="post">
    <label style="width:135px; float:left">RUT:</label>
      <input id="rut" name="rut" type="text" value="<?php echo $rut;?>" style="width:300px;">
   <input type="submit" name="Buscar" value="Buscar" class="boton" style="width:7%;">
    </form>
     <div class="clear"></div>
     <div class="clear"></div>

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
			<td class="fecha_crea">Fecha</td>
		</tr>
		<div class="content_tabla">
		  <?php if (count($lists)>0): ?>
		  <?php include APPPATH.'views/backend/templates/procurador/list_tabla.php';?>
		  <?php endif;?>
		  <?php echo $this->pagination->create_links(); ?>
		</div>
    </table>

</div>