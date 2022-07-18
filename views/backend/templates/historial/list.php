
<?php   $rut= ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}  ?>
<?php  $rango = '0';if (isset($_REQUEST['rango'])){$rango = $_REQUEST['rango'];} ?>

<div class="table-m-sep">
<div class="table-m-sep-title">
<h2><strong>Historial (<?php echo number_format($total,0,',','.');?>)</strong></h2>
 <?php $this->load->view('backend/templates/mod/cat_tools');?>
 </div>
</div>
<div class="agregar-noticia">


<?php   echo form_open(site_url().'/admin/historial/',array('id' => 'form_reg'));
	
		echo '<div class="campo">';
		echo form_label('Rut', 'rut');
		echo form_input('rut', $rut);
		echo form_error('rut');
		echo '</div>';
	
	
	    echo '<div class="campo">';
        echo form_label('Rango', 'rango');
        echo form_input('rango', $rango,'id="rango" style="width:180px"');
        echo form_error('rango');
        echo '</div>';
	
	
		echo '<div class="campo">';
		echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
		echo '</div>';
	
   	    echo form_close();	
?>


<div class="clear height"></div>

</div><!-- agregar-noticia -->

<?php if (count($lists)>0): ?>



<div class="tabla-listado">



<table class="listado" width="100%">



<tr class="menu">

  <td class="nombre">Usuario</td>
   <td class="nombre">Fecha</td>
   <td class="nombre">Rut</td>
  <td class="nombre">Historial</td>
  <td class="nombre">Rol</td>
  
 </tr>



<div class="content_tabla">



  <?php include APPPATH.'views/backend/templates/historial/list_tabla.php';?>



</div>



<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>





</table>

</div>



<?php endif;?>


<?php echo $this->pagination->create_links(); ?>
<?php //$this->load->view('backend/templates/mod/paginacion'); ?>  

<script type="text/javascript">
$(document).ready(function() {
 $("#rut").Rut({
 	on_error: function(){ alert('El R.U.T. es incorrecto. Formato: 11.111.111-1'); $("#rut").val('');  } 
 }); 
 $('#rango').daterangepicker();


}); 
</script>
  