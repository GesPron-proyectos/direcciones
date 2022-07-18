<?php $rut_orden = ''; if (isset($_REQUEST['rut_orden'])){$rut_orden = $_REQUEST['rut_orden'];} ?>
<?php $nombres_orden = ''; if (isset($_REQUEST['nombres_orden'])){$nombres_orden = $_REQUEST['nombres_orden'];} ?>

<?php $nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];} ?>
<?php $rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];} ?>


<div class="table-m-sep">

  <div class="table-m-sep-title">

    <h2><strong>Deudores (<?php echo number_format($total,0,',','.');?>)</strong></h2>

    <?php $this->load->view('backend/templates/mod/cat_tools');?>

  </div>

</div>

<div class="agregar-noticia">

  <div class="agregar"> <a class="nueva" href="<?php echo site_url();?>/admin/usuarios/form/">Crear Nuevo Deudor</a> </div>

  <!-- agregar -->
	<?php echo form_open(site_url().'/admin/'.$current.'/',array('id' => 'form_reg'));
	
	echo '<div class="campo">';
	echo form_label('Rut','nombres_orden'/*,$attributes*/);
	echo form_input('nombres_orden',$nombres_orden);
	echo form_error('nombres_orden');
	echo '</div>'; ?>
  
  <input type="submit" value="Guardar" class="boton" style="width:99%; float:left;">

	<?php echo form_close(); ?>

  <div class="clear height"></div>

</div>

<!-- agregar-noticia -->



<div class="tabla-listado">

    <table class="listado" width="100%">

    <tr class="menu">


 <td width="25%" class="nombre"><a href="<?php echo site_url().'/admin/usuarios/?nombres='.$nombres.'&nombres_orden=';?><?php if($nombres_orden!='' && $nombres_orden=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Deudor Completo</a></td> 

      
  <td width="9%" class="nombre"><a href="<?php echo site_url().'/admin/usuarios/?rut='.$rut.'&rut_orden=';?><?php if($rut_orden!='' && $rut_orden=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Rut</a></td>

      <td width="14%" class="nombre">ID</td>
	  <td width="9%" class="herramientas">Herramientas</td>

    </tr>

    <?php if (count($lists)>0): ?>

    <div class="content_tabla">

      <?php include APPPATH.'views/backend/templates/usuarios/list_tabla.php';?>

    </div>

    <?php endif;?>

    <?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

    </table>

</div>

<?php //$this->load->view('backend/templates/mod/paginacion'); ?>

<?php echo $this->pagination->create_links(); ?> 