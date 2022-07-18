<?php $id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}  ?>
<?php $rol = ''; if (isset($_REQUEST['rol'])){$rol = $_REQUEST['rol'];}  ?>
<?php $id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}  ?>
<?php $id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta'];}  ?>
<?php $id_etapa = ''; if (isset($_REQUEST['id_etapa'])){$id_etapa = $_REQUEST['id_etapa'];}  ?>
<?php $id_tribunal = ''; if (isset($_REQUEST['id_tribunal'])){$id_tribunal = $_REQUEST['id_tribunal'];}  ?>

<?php $procurador = ''; if (isset($_REQUEST['procurador'])){$procurador = $_REQUEST['procurador'];}  ?>
<?php $mandante = ''; if (isset($_REQUEST['mandante'])){$mandante = $_REQUEST['mandante'];}  ?>
<?php $fechaa = ''; if (isset($_REQUEST['fechaa'])){$fechaa = $_REQUEST['fechaa'];}  ?>
<?php $tribunal = ''; if (isset($_REQUEST['tribunal'])){$tribunal = $_REQUEST['tribunal'];}  ?>
<?php $diasdiferencia = ''; if (isset($_REQUEST['diasdiferencia'])){$diasdiferencia = $_REQUEST['diasdiferencia'];}  ?>


<?php $Nombre = ''; if (isset($_REQUEST['Nombre'])){$Nombre = $_REQUEST['Nombre'];}  ?>
<?php $ap_mat = ''; if (isset($_REQUEST['ap_mat'])){$ap_mat = $_REQUEST['ap_mat'];}  ?>
<?php $ap_pat = ''; if (isset($_REQUEST['ap_pat'])){$ap_pat = $_REQUEST['ap_pat'];}  ?>
<?php $Rut = ''; if (isset($_REQUEST['Rut'])){$Rut = $_REQUEST['Rut'];}  ?>
<?php $Operacion = ''; if (isset($_REQUEST['Operacion'])){$Operacion = $_REQUEST['Operacion'];}  ?>


<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Cuentas (<?php echo number_format($total,0,',','.');?>)</strong></h2>
  </div>
</div>
<style>
table.listado tr,table.listado input, table.listado select {
    font-size: 11px;
    margin: 5px 0 5px 5px;
	line-height: 26px !important;
}
</style>


<div class="tabla-listado">


<div class="agregar-noticia">
    <?php if (isset($nodo) && count($nodo)==1 && $nodo->nombre=='fullpay'):?>
    <a href="<?php echo site_url();?>/admin/alertas/exportador_alertas?id_mandante=<?php echo $id_mandante?>&rol=<?php echo $rol;?>&id_procurador=<?php echo $id_procurador;?>&id_estado_cuenta=<?php echo $id_estado_cuenta;?>&id_etapa=<?php echo $id_etapa;?>&id_tribunal=<?php echo $id_tribunal;?>  "class="ico-excel" style="width:150px;">Exportar a Excel</a>


    <?php endif; ?>

 <?php if (isset($nodo) && count($nodo)==1 && $nodo->nombre=='swcobranza'):?>
 <a class="ico-excel" href="<?php echo site_url();?>/admin/alertas/exportar_excel" style="float:right;"  target="_blank">Exportar a Excel</a><div class="clear height"></div>
 <?php endif;?>
<div class "clear height"></div>

<?php   echo form_open(site_url().'/admin/alertas/etapas',array('id' => 'form_reg'));
	
		echo '<div class="campo">';
		echo form_label('N° Operacion', 'N° Operacion'/*,$attributes*/);
		echo form_input('Operacion', $Operacion);
		echo form_error('operacion');
		echo '</div>';
	
		echo '<div class="campo">';
		echo form_label('Nombre Demandado', 'Nombre'/*,$attributes*/);
		echo form_input('Nombre', $Nombre);
		echo form_error('Nombre');
		echo '</div>'; 
		
		echo '<div class="campo">';
		echo form_label('Ap. Paterno Demandado', 'ap_pat'/*,$attributes*/);
		echo form_input('ap_pat', $ap_pat);
		echo form_error('ap_pat');
		echo '</div>'; 
		
		echo '<div class="campo">';
		echo form_label('Ap. Materno Demandado', 'ap_mat'/*,$attributes*/);
		echo form_input('ap_mat', $ap_mat);
		echo form_error('ap_mat');
		echo '</div>';		
		
		echo '<div class="campo">';
		echo form_label('Rut', 'Rut'/*,$attributes*/);
		echo form_input('Rut', $Rut);
		echo form_error('Rut');
		echo '</div>';
	
		echo '<div class="campo">';
		echo form_label('Mandante', 'id_mandante'/*,$attributes*/);
		echo form_dropdown('id_mandante', $mandantes, $id_mandante);
		echo form_error('id_mandante');
		echo '</div>';
	
		echo '<div class="campo">';
		echo form_label('Rol', 'rol'/*,$attributes*/);
		echo form_input('rol', $rol);
		echo form_error('rol');
		echo '</div>'; 
		
		echo '<div class="campo">';
		echo form_label('Procurador', 'id_procurador'/*,$attributes*/);
		echo form_dropdown('id_procurador', $procuradores, $id_procurador);
		echo form_error('id_procurador');
		echo '</div>';	
		
		echo '<div class="campo">';
		echo form_label('Tribunal', 'id_tribunal');
		echo '<div id="anidadotribunal">';
		echo form_dropdown('id_tribunal', $tribunales);
		echo form_error('id_tribunal');
		echo '</div>';
		echo '</div>';
	
	
	//	echo '<div class="campo">';
	//	echo form_label('Estado', 'id_estado_cuenta'/*,$attributes*/);
	//	echo form_dropdown('id_estado_cuenta', $estado, $id_estado_cuenta);
	//	echo form_error('id_estado_cuenta');
	//	echo '</div>';

//##########CAMPO SELECT ESTADO ###################################//
		$estado = array(
		'-1'         => 'Seleccionar',
        '1'         => 'VIGENTE',
        '2'         => 'RECHAZO INGRESA',
        '6'         => 'EXHORTO',
        '7'         => 'DEVOL. DOCUMENTOS',
        '8'         => 'AVENIMIENTO',

);
		echo '<div class="campo">';
		echo form_label('Estado', 'id_estado_cuenta'/*,$attributes*/);
		echo form_dropdown('id_estado_cuenta', $estado, $id_estado_cuenta);
		echo form_error('id_estado_cuenta');  # to show error
		echo '</div>';
//############### FIN SELECT ESTADO ###############################//
		
		echo '<div class="campo">';
		echo form_label('Etapa de Juicio', 'id_etapa'/*,$attributes*/);
		echo form_dropdown('id_etapa', $etapas, $id_etapa);
		echo form_error('id_etapa');
		echo '</div>';
	
	    echo '<div class="campo">';
		echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
		echo '</div>';
	
   	    echo form_close();	
?>


<div class="clear height"></div>
</div>
<?php if (count($lists)>0): ?>
<div class="content_tabla">

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">
  <td width="5%" class="nombre">
	<a class="nombre">Operación</a>
  </td>
  <td width="5%" class="nombre">
	<a>Mandante</a>
  </td>
  <td width="5%" class="nombre">
  <a>Fecha Asignación</a>
  </td>

  <td width="5%" class="nombre">D. Nombres</td>
  <td width="5%" class="nombre">D. Rut</td>
  <td width="5%" class="nombre">
    <a>Procurador</a>
  </td>  

  <td width="5%" class="nombre">Estado Cuenta</td>



  <td width="5%" class="nombre">
      <a>Tribunal</a>
  </td>  
  <td width="5%" class="nombre">Rol</td>  
  <td width="5%" class="nombre">Rol Exhorto</td>
  <td width="5%" class="nombre">
  <a>Dias Alerta (Diferencia)</a>
  </td>
  <td width="5%" class="nombre">Etapa</td> 
  <td width="5%" class="nombre">Fecha Etapa</td>
    <td width="5%" class="nombre">Dias Etapa</td>
  <td width="5%" class="nombre">Instrucción</td>
</tr>

 
<!--print_r -->
<?php $i=1; $check_id=1;
//print_r($lists);
foreach ($lists as $key=>$val):?>

<?php

/*
if($val->id_etapa==1 || $val->id_etapa==0)
{
$diseno = "style=background:#F7FE2E;";$color = "#000000;";	
}
else
{
	if($val->exorto==0)
	{
		if($val->codigo_mandante=="TANNER")
		{
			if ($val->dias_ >= 50)
			{	
				$diseno = "style=background:#F75D5D;";$color = "#000000;";$color_atraso = $color;
			}
			else
			{
				if ($i%2==0){$diseno = ' class="b"';}else{$diseno = ' class="a"';}
				$color = "#0000;";
				$color_atraso = "#F00";
			}
		}
		else
		{
			if ($i%2==0){$diseno = ' class="b"';}else{$diseno = ' class="a"';}
			$color = "#0000;";
			$color_atraso = "#F00";
		}
	}else
	{
		if($val->codigo_mandante=="TANNER")
		{
			if ($val->dias_ >= 75)
			{	
				$diseno = "style=background:#F75D5D"; $color = "#000000;";$color_atraso = $color;
			}
			else
			{
				if ($i%2==0){ $diseno = ' class="b"';}else{ $diseno = ' class="a"';}
				$color = "#0000;";
				$color_atraso = "#F00";
			}	
		}
		else
		{
			if ($i%2==0){$diseno = ' class="b"';}else{$diseno = ' class="a"';}
			$color = "#0000;";
			$color_atraso = "#F00";
		}
	}
}
*/
//#F00       class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}"

//############################ ALERTAS #####################////

##################################################################

$dias_etapas = $val->dias_alerta;
$porcent_holgura = 1;
$holgura = $porcent_holgura*$dias_etapas;
$porcent75= 75/100;
$porcent100= 100/100;
//$causas_verificar = $val->titular;
/*

if($causas_verificar)
	{
		$diseno = "style=background:#c542f4;";$color = "#000000;";
	}
*/


if($val->acuse==0)
{
	if($val->dias_alerta_diferencia / $holgura)
	{
	

		if ($val->dias_alerta_diferencia <= $holgura*$porcent75)
			{	
				//ALERTA COLOR VERDE
			  $diseno = "style=background:#17a10a;";$color = "#000000;";
			
		}else {
			  
		if ($val->dias_alerta_diferencia > $holgura*$porcent75  AND $val->dias_alerta_diferencia <= $holgura*$porcent100)
			      {	
			      	//ALERTA COLOR AMARILLO
						$diseno = "style=background:#e7fd38;";$color = "#000000;";

			      

         		}else{
         			//ALERTA COLOR ROJO
         			$diseno = "style=background:#F75D5D;";$color = "#000000;";$color_atraso = $color;

                }

          }
		
					
				
    }

   }

    
  ######################################################################


/*
$dias_alerta_diferencia = $val->dias_diferencia - $val->dias_alerta ;

if($val->acuse==0)
{
	if($val->id_etapa and $val->dias_diferencia < $val->dias_alerta*101/100 )
	{
	

		if ($val->dias_diferencia <= $val->dias_alerta*75/100)
			{	
			  $diseno = "style=background:#17a10a;";$color = "#000000;";
			
			}else {
			  
		if ($val->dias_diferencia> $val->dias_alerta*75/100 and $val->dias_diferencia <= $val->dias_alerta*100/100)
			      {	
						$diseno = "style=background:#e7fd38;";$color = "#000000;";

			      

                }

          }

			
		}else {
		
	$diseno = "style=background:#F75D5D;";$color = "#000000;";$color_atraso = $color;
		
		}			
				
    }
  


*/


?>   



<tr id="tools_" <?php echo $diseno;?>>

	<td><font color="<?php echo $color;?>"><?php /*echo $val->diass_."<br>". $val->id_etapa. "<br>". $diseno;*/ echo $val->operacion;?></font></td>
	<td><font color="<?php echo $color;?>"><?php /*echo $val->diass_."<br>". $val->id_etapa. "<br>". $diseno;*/ echo $val->codigo_mandante;?></font></td>
	<td><font color="<?php echo $color;?>"><?php echo date("d-m-Y",strtotime($val->fecha_asignacion))?></font></td>
	
	<td><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>">
	<font color="<?php echo $color;?>"><?php echo $val->usuarios_nombres;?> <?php echo $val->usuarios_ap_pat;?> <?php echo $val->usuarios_ap_mat;?></font></a></td>
	<td><font color="<?php echo $color;?>"><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>">
	<font color="<?php echo $color;?>"><?php echo $val->usuarios_rut;?></font></a></td>
    <td><font color="<?php echo $color;?>"><?php echo $val->nombres_adm.' '.$val->apellidos_adm;?></font></td>

      <td><strong><?php 
  if ($val->id_estado_cuenta==1){echo '<span class="blue-dark">VIGENTE</span>';}
  if ($val->id_estado_cuenta==7){echo '<span class="blue-dark">DEV. DOCUMENTOS</span>';}
  if ($val->id_estado_cuenta==2 && $nodo->nombre=='fullpay'){echo '<span class="blue-dark">RECHAZO INGRESA</span>';}
  if ($val->id_estado_cuenta==2 && $nodo->nombre=='swcobranza'){echo '<span class="blue-dark">Rechazo INGRESA</span>';}
 // if ($val->id_estado_cuenta==3){echo '<span class="blue">SUSPENDIDO</span>';}
 // if ($val->id_estado_cuenta==4){echo '<span class="red">TERMINADO</span>';}
  //if ($val->id_estado_cuenta==5 && $nodo->nombre=='swcobranza'){echo '<span class="purple">CONVENIO</span>';}
  //if ($val->id_estado_cuenta==5 && $nodo->nombre=='fullpay'){echo '<span class="blue-dark">DEVUELTO</span>';}
  //if ($val->id_estado_cuenta==6 && $nodo->nombre=='swcobranza'){echo '<span class="purple2">CONVENIO INCUMPLIDO</span>';}
  if ($val->id_estado_cuenta==6 && $nodo->nombre=='fullpay'){echo '<span class="blue-dark">EXHORTO</span>';}
 // if ($val->id_estado_cuenta==7 && $nodo->nombre=='swcobranza'){echo '<span class="blue2">SUSPENDIDO CON CONVENIO</span>';}
  if ($val->id_estado_cuenta==8 && $nodo->nombre=='fullpay'){echo '<span class="blue-dark";font-weight:bold">AVENIMIENTO</span>';}
 // if ($val->id_estado_cuenta==8 && $nodo->nombre=='swcobranza'){echo '<span class="blue3">GPVE rechazadas</span>';}
 // if ($val->id_estado_cuenta==9 && $nodo->nombre=='swcobranza'){echo '<span class="yellow">ABONO</span>';}
  ?></strong>
  </td>








	<!--<td><font color="<?php echo $color;?>"><?php echo $val->estado_cuenta?></font></td>  -->
	<td  style="text-align: center;"><font color="<?php echo $color;?>"><?php echo $val->tribunal?><br><?php echo $val->tribunal_padre?></font></td>
	
	<td><font color="<?php echo $color;?>"><?php echo $val->rol;?></font>	</td>
	
	<td style="text-align: center;"><font color="<?php echo $color;?>">
	
	<?php if ($val->exorto=='0')
 {?> Sin Exhorto
 <?php }else{?>
		<?php if($val->rolE != ''){ ?><?php echo $val->TribunalE."<br>".$val->DistritoE."<br>".$val->rolE;?> <?php } else {echo '-'; ?><?php } ?>
	<?php }?>
	</font>
	</td>
       
    <!--<td><?php echo date("d-m-Y",strtotime($val->fecha_asignacion_pagare))?></td>-->
      
    <!-- Muestra los dias de diferencia (dias restantes entre la fecha etapa y fecha actual-->
     <td style="color:<?php echo $color_atraso;?>;text-align: center;"><?php echo $val->dias_alerta_diferencia?></td>
     <td style="color:<?php echo $color_atraso;?>;text-align: center;"><?php echo $val->titular?></td>
      <!--<td style="color:<?php echo $color_atraso;?>;text-align: center;"><?php echo $val->dias_diferencia;?></td>-->
	  <td><font color="<?php echo $color;?>"><?php echo $val->etapa?></font></td> 
	  <td style="text-align: center;"><font color="<?php echo $color;?>"><?php echo date("d-m-Y",strtotime($val->fecha_etapa))?></font></td>
	  <td><font color="<?php echo $color;?>"><?php echo $holgura?></font></td>
	  <td><font color="<?php echo $color;?>"><?php echo $val->texto_alerta?></font></td>  




  <?php $row_current=array(); $row_current=$val; //include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
</tr>

<?php ++$i;endforeach;?>

</table>
<?php endif;?>
<?php echo $this->pagination->create_links(); ?>
</div></div>


