<!--h-tools-->
<div class="h-tools">
    <a href="<?php echo site_url("admin/adm/logout");?>" class="logout">Cerrar Sesión</a>
<div class="clear"></div>
</div>

<div class="mainmenu">

    <ul>   
		<li><a href="<?php echo site_url();?>/admin/administradores" style="border-left:none;"<?php if ($current=='administradores'){echo ' id="current"';}?>> ADMINISTRADORES</a></li>
		<li><a href="<?php  echo site_url()?>/admin/procurador"<?php  if ($current=='procurador'){echo ' id="current"';}?>>DIRECCIONES</a></li>
	</ul>

</div>

<!--mainmenu-->


<?php if ($current=='cuentas'):?>

<div class="mainmenu">

<ul>
<?php  if( $this->session->userdata("usuario_perfil") ==  1  && $nodo->nombre == 'swcobranza'):?>

<?php endif; ?>



<?php if($nodo->nombre == 'fullpay' ): ?>

<?php endif; ?>

</ul>

</div>

<?php endif;?>



<?php if ($current=='procurador'):?>

<div class="">

<ul>


 <?php  if( $this->session->userdata("usuario_perfil") <  3  && $nodo->nombre == 'swcobranza'):?>

<?php endif;?>

<?php if($nodo->nombre == 'fullpay' && $this->session->userdata("usuario_perfil") ==  3 ): ?>

<?php endif;?>

<?php if($nodo->nombre == 'fullpay' && $this->session->userdata("usuario_perfil")  < 3 ): ?>
    <!-- <li><a href="<?php echo site_url();?>/admin/procurador/reporte_jurisdiccion/"<?php // if ($sub_current=='reporte_jurisdiccion'){echo ' id="current"';}?>>Reporte de Juridicción</a> -->
    <!-- <li><a href="<?php echo site_url();?>/admin/procurador/reporte_fecha_asignacion/"<?php // if ($sub_current=='reporte_fecha_asignacion'){echo ' id="current"';}?>>Reporte de Fecha de Asignación</a> -->
        <!-- <li><a href="<?php echo site_url();?>/admin/gestion/proyeccion_convenios/"<?php // if ($sub_current=='proyeccion-convenios'){echo ' id="current"';}?>>Proyección según Convenios</a> -->
 <!-- <li><a href="<?php echo site_url();?>/admin/gestion/historial_pagos/"<?php // if ($sub_current=='historial_pagos'){echo ' id="current"';}?>>Historial de pagos</a> -->

<?php endif; ?>

</li>

</ul>

</div>

<?php endif;?>




<?php if ($current=='alertas'):?>
<div class="mainmenu">
<ul>

</ul>
</div>
<?php endif;?>

<?php if ($current=='llamadas'):?>
<div class="mainmenu">
<ul>

</ul>
</div>
<?php endif;?>

<!--sec-m-menu-->