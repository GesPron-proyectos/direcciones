
<div class="table-m-sep">

  <div class="table-m-sep-title">

    <h2><strong>Receptores (<?php echo count($lists)?>)</strong></h2>
    <?php $this->load->view('backend/templates/mod/cat_tools');?>
  </div>

</div>
<?php
$orden_rut = ''; if (isset($_REQUEST['rut'])){$orden_rut = $_REQUEST['rut'];}  

$orden_tribunal = ''; if (isset($_REQUEST['orden_tribunal'])){$orden_tribunal = $_REQUEST['orden_tribunal'];}


if (count($lists)>0){
	
	//print_r($lists);

	$id=$lists->id; 
	$nombre = $lists->nombre;
	$rut = $lists->rut;
	$direccion = $lists->direccion;
	$comuna = $lists->comuna;
	$ciudad = $lists->ciudad;
	$telefono = $lists->telefono;
	$celular = $lists->celular;
	$nombre_secretaria = $lists->nombre_secretaria;
	$id_tribunal = $lists->id_tribunal;
	$email = $lists->email;
	$id_estado = $lists->id_estado;
	$celular_secretaria=$lists->celu_secre;
	$Telefono_secretaria=$lists->fono_secre;
	$ApeMat=$lists->apmat;
	$ApePat=$lists->appat;
}

?>
<div class="agregar-noticia">

<table width="100%" border="1">
  <tr>
    <td>&nbsp;</td>
	<td width="50px" >Rut</td>
    <td>  <input id="rut" name="rut" type="text" value="<?php echo $rut;?>" style="width:150px;" readonly>  </td>
	<td>&nbsp;</td>
    <td width="60px">Nombre</td>
    <td><input id="nombre" name="nombre" type="text" value="<?php echo $nombre;?>" style="width:150px;" readonly></td>
    <td>&nbsp;</td>
	<td width="100px">Ap. Paterno</td>
	<td><input id="apmaterno" name="apmaterno" type="text" value="<?php echo $apmaterno;?>" style="width:150px;" readonly></td>
	<td>&nbsp;</td>
	<td width="100px">Ap. Paterno</td>
	<td><input id="apmaterno" name="apmaterno" type="text" value="<?php echo $apmaterno;?>" style="width:150px;" readonly></td>
	<td>&nbsp;</td>
  </tr>  
</table>
  <!-- agregar -->

  <br>
  
</div>

<!-- agregar-noticia -->



<div class="tabla-listado">

    <table class="listado" width="100%">

    <tr class="menu">

     <td width="5%" class="nombre">
	Mandante
  </td>
  <td width="5%" class="nombre">
  Fecha Asignación
  </td>
  
  <td width="5%" class="nombre">D. Nombre</td>
  <td width="5%" class="nombre">
  
  
  
  <a href="<?php echo site_url().'/admin/receptor/cuentas/'. $id.'?rut=';?><?php if($orden_rut!='' && $orden_rut=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">D. Rut</a>
  
  
  </td>
  <td width="5%" class="nombre">
    Procurador
  </td>
  
  <td width="5%" class="nombre">
      
	  <a href="<?php echo site_url().'/admin/receptor/cuentas/'. $id.'?orden_tribunal=';?><?php if($orden_tribunal!='' && $orden_tribunal=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Tribunal</a>
  </td>
  
  <td width="5%" class="nombre">Rol</td>
  
   <td width="5%" class="nombre">Rol Exhorto</td>
  
  <!--<td width="5%" class="nombre">Fecha Pagare</td>-->
  
  
  <td width="5%" class="nombre">
  Dias Alerta (Diferencia)
  </td>
  <td width="5%" class="nombre">Etapa</td>
  
  <td width="5%" class="nombre">Fecha Ingreso Etapa</td>
  <td width="5%" class="nombre">Observacion</td>


    </tr>

    <?php if (count($causas)>0): ?>

    <div class="content_tabla">

      <?php include APPPATH.'views/backend/templates/receptor/list_tabla_causas.php';?>

    </div>

    <?php endif;?>

    <?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

    </table>

</div>

<?php //$this->load->view('backend/templates/mod/paginacion'); ?>

<?php //echo $this->pagination->create_links(); ?> 