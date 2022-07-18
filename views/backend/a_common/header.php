<!--h-tools-->
<div class="h-tools">
    <a href="<?php echo site_url("admin/adm/logout");?>" class="logout">Cerrar Sesión</a>
<div class="clear"></div>
</div>

<div class="mainmenu">

    <ul>

		<?php  if( $this->session->userdata("usuario_perfil") ==  1  && $nodo->nombre == 'swcobranza'):?>

      <?php endif; ?>
      
      <?php  if($this->session->userdata("usuario_perfil") ==  2 && $nodo->nombre == 'swcobranza'):?>
   
      <?php endif; ?>
      
      
      <?php  if($this->session->userdata("usuario_perfil") ==  3 && $nodo->nombre == 'swcobranza'):?>
 

      <?php endif; ?>
      
      <?php 

	  if($this->session->userdata("usuario_perfil") ==  3  && $nodo->nombre == 'fullpay'):?>

      <?php endif; ?>
       
      <?php if($nodo->nombre == 'fullpay' && $this->session->userdata("usuario_perfil") ==  1):?>
		<li><a href="<?php echo site_url();?>/admin/administradores" style="border-left:none;"<?php if ($current=='administradores'){echo ' id="current"';}?>> Administradores</a></li>
    
    <li><a href="<?php echo site_url();?>/admin/cuentas"<?php if ($current=='cuentas'){echo ' id="current"';}?>>ED DIARIO ABOGADOS</a></li>
    <li><a href="<?php echo site_url();?>/admin/roles"<?php if ($current=='roles'){echo ' id="current"';}?>>ED ROLES</a></li>
    <li><a href="<?php echo site_url();?>/admin/control_envios"<?php if ($current=='control_envios'){echo ' id="current"';}?>>CONTROL ENVIOS</a></li>
    <li><a href="<?php  echo site_url()?>/admin/procurador"<?php  if ($current=='procurador'){echo ' id="current"';}?>>PROCURADOR</a></li> 
  
        
        <?php  if($this->session->userdata("usuario_perfil") ==  1 && $nodo->nombre == 'fullpay'):?>

      <?php endif; ?>
       
        <?php  if($this->session->userdata("usuario_perfil") ==  1  && $nodo->nombre == 'fullpay'):?>
         
		 
        <?php endif; ?>
                

       <?php endif; ?>

        <?php if($nodo->nombre == 'fullpay' && $this->session->userdata("usuario_perfil") ==  2):?>
		
        
        <?php endif; ?>

        <?php if($nodo->nombre == 'fullpay' && $this->session->userdata("usuario_perfil") ==  3):?>
	
        <?php endif; ?>

        <?php if($nodo->nombre == 'fullpay' && $this->session->userdata("usuario_perfil") ==  4):?>
   

        <?php endif; ?>
        <!-- <li><a href="<?php echo site_url();?>/admin/hist_cuentas"<?php if ($current=='hist_cuentas'){echo ' id="current"';}?>>Cuentas Historial</a></li> -->

       

  		<?php // if( $this->session->userdata("usuario_perfil") != 4 ):?>
        
       	
        <?php //echo $nodo->nombre.'efdsfsdf'; ?>
        <?php //  if( $this->session->userdata("usuario_perfil") <  3  && $nodo->nombre == 'swcobranza'):?>
        
        
         <?php // endif; ?>
        
         <?php  // if($nodo->nombre == 'fullpay'): ?>
       <!--  <li><a href="<?php // echo site_url()?>/admin/etapas_juicio"<?php if ($current=='etapas_juicio'){echo ' id="current"';}?>>Etapas de juicio</a></li> -->
       <!-- 	<li><a href="<?php // echo site_url()?>/admin/alertas"<?php if ($current=='alertas'){echo ' id="current"';}?>>Alertas</a></li> -->
       	
       <!-- 	<li><a href="<?php // echo site_url()?>/admin/reglas_gastos"<?php if ($current=='reglas_gastos'){echo ' id="current"';}?>>Reglas de gastos</a></li> -->
       	
       <!--  <li><a href="<?php  // echo site_url()?>/admin/lista/comunas"<?php if ($current=='comunas'){echo ' id="current"';}?>>Lista comunas</a></li> -->
        <?php //endif;?>
        <?php //if (isset($nodo) && count($nodo)==1 && $nodo->nombre=='swcobranza'):?>
      <!--  <li><a href="<?php // echo site_url()?>/admin/llamadas"<?php if ($current=='llamadas'){echo ' id="current"';}?>>Llamadas</a></li> -->
    <!--    <li><a href="<?php // echo site_url()?>/admin/tribunales"<?php if ($current=='tribunales'){echo ' id="current"';}?>>Tribunales</a></li> -->
     <!--   <li><a href="<?php // echo site_url()?>/admin/jurisdiccion"<?php if ($current=='jurisdiccion'){echo ' id="current"';}?>>Jurisdicciones</a></li> -->
        <?php // endif; ?>
      
        
        
      
        <?php //endif;?>
        
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

<div class="mainmenu">

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