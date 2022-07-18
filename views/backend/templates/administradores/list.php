<div class="table-m-sep">

  <div class="table-m-sep-title"> 

    

    <!--h2><strong>Rubros</strong></h2-->

    

    <?php $this->load->view('backend/templates/mod/cat_tools');?>

  </div>

</div>

<div class="agregar-noticia">

  <div class="agregar"> <a class="nueva" href="<?php echo site_url();?>/admin/administradores/form/">Crear Nuevo Administrador</a> </div>

  <!-- agregar -->

  <div class="clear height"></div>

</div>

<div class="clear"></div>

<div class="tabla-listado">

    <table class="listado" width="100%">

    <tr class="menu">

      <td width="41%" class="nombre">Usuario</td>

      <td width="35%" class="nombre">Perfil</td>
      <td width="35%" class="nombre">ID</td>
      <td width="35%" class="herramientas">Herramientas</td>

    </tr>

    <div class="content_tabla">

	  <?php if (count($lists)>0): ?>

      <?php include APPPATH.'views/backend/templates/administradores/list_tabla.php';?>

      <?php endif;?>

    </div>

    </table>

<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

</div>

<?php //$this->load->view('backend/templates/mod/paginacion'); ?>