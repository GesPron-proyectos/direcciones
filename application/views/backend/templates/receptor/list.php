<div class="table-m-sep">

  <div class="table-m-sep-title">

    <h2><strong>Receptores (<?php echo count($lists)?>)</strong></h2>

    <?php $this->load->view('backend/templates/mod/cat_tools');?>

  </div>

</div>

<div class="agregar-noticia">

  <div class="agregar"> 
  	<a class="nueva" href="<?php echo site_url();?>/admin/receptor/form/">Crear Nuevo Receptor</a>
  </div>

  <!-- agregar -->

  <br>
  <?php
  
$id_distrito = ''; if (isset($_REQUEST['id_distrito'])){$id_distrito = $_REQUEST['id_distrito'];}  

$nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];}

  
  	echo form_open(site_url().'/admin/'.$current.'/',array('id' => 'form_reg'));
	
	echo '<div class="campo">';
	echo form_label('Nombres', 'nombres'/*,$attributes*/);
	echo form_input('nombres', $nombres);
	echo form_error('nombres');
	echo '</div>';

	echo '<div class="campo">';
	echo form_label('Distrito', 'id_distrito');
	echo form_dropdown('id_distrito', $tribunales, $id_distrito);
	echo form_error('id_distrito');
	echo '</div>';
		
	echo '<div class="campo">';
	echo form_label('Rol', 'rol'/*,$attributes*/);
	echo form_input('rol', $rol);
	echo form_error('rol');
	echo '</div>';
	
	echo '<div class="campo">';
	echo form_label(' .', ' ');
	echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
	echo '</div>';


	echo form_close();

?>  
  
  
  
  
  
  <div class="clear height"></div>

</div>

<!-- agregar-noticia -->



<div class="tabla-listado">

    <table class="listado" width="100%">

    <tr class="menu">

      <td width="25%" class="nombre">Nombre</td>
	  <td width="20%" class="nombre">Apellidos</td>
       <td width="10%" class="nombre">Jurisdicción</td>
	  <?php if ($nodo->nombre=='fullpay'):?>
      <!--<td width="25%" class="nombre">Tribunal</td>-->
        <?php endif;?>
       <td width="20%" class="nombre">Email</td>
	   
	   <td width="20%" class="nombre">Telefono</td>
	   <td width="20%" class="nombre">Celular</td>

      <td width="9%" class="herramientas">Herramientas</td>

    </tr>

    <?php if (count($lists)>0): ?>

    <div class="content_tabla">

      <?php include APPPATH.'views/backend/templates/receptor/list_tabla.php';?>

    </div>

    <?php endif;?>

    <?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

    </table>

</div>

<?php //$this->load->view('backend/templates/mod/paginacion'); ?>

<?php //echo $this->pagination->create_links(); ?> 