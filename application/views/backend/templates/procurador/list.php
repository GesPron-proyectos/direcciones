  <?php $rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
		$rut_parcial = ''; if (isset($_REQUEST['rut_parcial'])){$rut_parcial = $_REQUEST['rut_parcial'];}

  ?>

	<div class="table-m-sep-title">
<h2><strong>Direcciones(<?php echo count($lists);?>)</strong></h2>
</div>
<div class="agregar-noticia">
  <div class="agregar"> 
  	<a class="nueva" href="<?php echo site_url();?>/admin/importar/cargar_excel_mora">Importar Direcciones</a>
  </div>


  <div class="clear height"></div>
  <?php   echo form_open(site_url().'/admin/procurador/',array('id' => 'form_reg'));
	
		echo '<div class="campo">';
		echo('Rut:');echo form_input('rut',$rut);
		echo form_error('rut');
		echo '</div>';

		echo '<div class="campo">';
		echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
		echo '</div>';
	
   	    echo form_close();	
?>
     <div class="clear height"></div>
	 <?php if (count($lists)>0): ?>

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

		  <?php include APPPATH.'views/backend/templates/procurador/list_tabla.php';?>
		  <div>
		  <?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

		  <?php endif;?>
		  <?php echo $this->pagination->create_links(); ?>
		</div>
    </table>

	<?php echo $this->pagination->create_links(); ?>
<?php //$this->load->view('backend/templates/mod/paginacion'); ?>  

<script type="text/javascript">
$(document).ready(function() {
 $("#rut").Rut({
 	on_error: function(){ alert('El R.U.T. es incorrecto. Formato: 11.111.111-1'); $("#rut").val('');  } 
 }); 
 $('#rango').daterangepicker();


}); 

</div>