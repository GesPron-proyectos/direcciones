<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">-->


<script>
$(function() {
  $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(datetext){
            var d = new Date(); // for now
            var h = d.getHours();
            h = (h < 10) ? ("0" + h) : h ;

            var m = d.getMinutes();
            m = (m < 10) ? ("0" + m) : m ;

            var s = d.getSeconds();
            s = (s < 10) ? ("0" + s) : s ;

            datetext = datetext + " " + h + ":" + m + ":" + s;
            $('.datepicker').val(datetext);
        },
    });
});



/*

 $(document).ready(function() {
        var cambios = new Array();
        var registrando = true;
        $('.dateTxt').datetimepicker({
            onChangeDateTime: function(ct, $input) {
                if (registrando) {
                    cambios[cambios.length] = $input.val();

                }
            },
            onClose: function(ct, $input) {
                if (registrando) {
                    miFuncion(cambios);
                    registrando = false;
                }   
            },
            onShow: function(ct, $input) {
                registrando = false;
            },
        });
    });

*/
/*

  $(document).ready(function() {
        var cambios = new Array();
        var registrando = false;
        $('#idCalendario').datetimepicker({
            onChangeDateTime: function(ct, $input) {
                if (registrando) {
                    cambios[cambios.length] = $input.val();
                }
            },
            onClose: function(ct, $input) {
                if (registrando) {
                    miFuncion(cambios);
                    registrando = false;
                }   
            },
            onShow: function(ct, $input) {
                registrando = true;
            },
        });
    });


*/


/*

$(function() {
  $('.dateTxt').datetimepicker();
});

*/



/*

$('.Timestamp').datetimepicker({
    format: 'DD/MM/YYYY HH:mm:ss'
});


$(function () {
    var date = new Date();
    var currentMonth = date.getMonth();
    var currentDate = date.getDate();
    var currentYear = date.getFullYear();
    $('#datetimepicker,#datetimepicker1').datetimepicker({
        pickTime: false,
        format: "DD-MM-YYYY",  
        maxDate: new Date(currentYear, currentMonth, currentDate + 1)
    });
});

*/



/*
$('.datepicker').each(function(i) {
    dateFormat: 'dd-mm-yy',
    this.id = 'datepicker' + i;
}).datepicker();


  */

/*
$(document).ready(function(){
        $("#boton").click(function(){
        $("#listas li").each(function(){
              alert($(this).text())
          });
  });
});

*/

/*

$(document).ready(function() {
    $("#datepicker").datepicker({
  dateFormat: 'dd/mm/yy',
    }).datepicker("setDate", new Date());
});
¨*/

/*

$(function() {
$('.datepick').each(function(){
    $(this).datepicker();
    });
});

*/

/*

  $(function(){
    $('#datepicker').datepicker({
      dateFormat: 'dd-mm-yy',
      timeFormat:  "hh:mm:ss"

      });
    });

*/


/*

$(function() {
   $('input').filter('.datepicker').datepicker({
    changeDay: true,
    changeMonth: true,
    changeYear: true,
  //  showOn: 'button',
  //  buttonImage: 'jquery/images/calendar.gif',
 //   buttonImageOnly: true
   });
  }); 

*/
      
</script>
<!---
 <p>Date1 <input  class="datepicker" type="text" readonly="true"></p>
 <p>Date2<input class="datepicker" type="text" readonly="true"></p>
 <p>Date3<input  class="datepicker" type="text" readonly="true"></p>
-->



<?php

$observaciones_m = $this->input->post('observaciones');     
$id_etapa_m = $this->input->post('id_etapa');   

$fecha_etapa_m = $this->input->post('fecha_etapa');
if ($fecha_etapa_m==''){ $fecha_etapa_m = date('Y-m-d H:i:s');}



if ($idregistro!='')
{
  $observaciones_m = $etapa_juicio_cuenta->observaciones;
  $id_etapa_m =  $etapa_juicio_cuenta->id_etapa;
  $fecha_etapa_m = datetime('Y-m-d H:i:s',strtotime($etapa_juicio_cuenta->fecha_etapa));

}



?>



<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>


<?php
$orden_id_etapa= ''; if (isset($_REQUEST['orden_id_etapa'])){$orden_id_etapa = $_REQUEST['orden_id_etapa'];} 
$orden_distrito = ''; if (isset($_REQUEST['orden_distrito'])){$orden_distrito = $_REQUEST['orden_distrito'];} 
$orden_rol = ''; if (isset($_REQUEST['orden_rol'])){$orden_rol = $_REQUEST['orden_rol'];} 
$orden_ap_pat = ''; if (isset($_REQUEST['orden_ap_pat'])){$orden_ap_pat = $_REQUEST['orden_ap_pat'];}
$orden_id_tribunal = ''; if (isset($_REQUEST['orden_id_tribunal'])){$orden_id_tribunal = $_REQUEST['orden_id_tribunal'];} 
$nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];}
$ap_pat = ''; if (isset($_REQUEST['ap_pat'])){$ap_pat = $_REQUEST['ap_pat'];}
$id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
$id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
$id_distrito = ''; if (isset($_REQUEST['id_distrito'])){$id_distrito = $_REQUEST['id_distrito'];}
$id_tribunal= ''; if (isset($_REQUEST['id_tribunal'])){$id_tribunal = $_REQUEST['id_tribunal'];}
$nombres = ''; if (isset($_REQUEST['nombres'])){$nombres = $_REQUEST['nombres'];}
$receptor = ''; if (isset($_REQUEST['receptor'])){$receptor = $_REQUEST['receptor'];}
$rol = ''; if (isset($_REQUEST['rol'])){$rol = $_REQUEST['rol'];}
$etapa_actual = ''; if (isset($_REQUEST['etapa_actual'])){$etapa_actual = $_REQUEST['etapa_actual'];}
$id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta']; }
$id_etapa = ''; if (isset($_REQUEST['id_etapa'])){$id_etapa = $_REQUEST['id_etapa']; } 
$mandante = ''; if (isset($_REQUEST['mandante'])){$mandante = $_REQUEST['mandante']; }
$diasatraso = ''; if (isset($_REQUEST['diasatraso'])){$diasatraso = $_REQUEST['diasatraso']; }
$fechaa = ''; if (isset($_REQUEST['fechaa'])){$fechaa = $_REQUEST['fechaa']; }
$rolE = ''; if (isset($_REQUEST['rolE'])){$rolE = $_REQUEST['rolE']; }
$rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];}
$rut_mandante = ''; if (isset($_REQUEST['rut_mandante'])){$rut_mandante = $_REQUEST['rut_mandante'];}
$id_distritoE = ''; if (isset($_REQUEST['id_distritoE'])){$id_distritoE = $_REQUEST['id_distritoE'];}  

//$id_tribunalE = ''; if (isset($_REQUEST['id_tribunalE'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta'];}
//print_r($_REQUEST);
?>

<style>
table.stable input, table.listado select {
    font-size: 11px;
    margin: 5px 0 5px 5px;
}
table.listado select {
	width:120px;
}



.chk_all,.chk { width:25px}
</style>
<?php if (count($lists)>0): ?>


<?php 
//print_r lista procurador
//echo '<pre>'; print_r($lists); echo '</pre>';?>
<?php  
//$fecha_inicio_day = date ( 'd' );
//$fecha_inicio_month = date ( 'm' );
//$fecha_inicio_year = date ( 'Y' );

$user_id = '';
if( $this->session->userdata("usuario_perfil") == 3 ){
	$user_id = $this->session->userdata("usuario_id");
}

?>

<style>
table.stable tr.menu { background-color:#F3F3F3; border-top:1px solid #CDCCCC; }
table.stable td.nombre { font-weight:bold; height:30px;}
table.stable tr.b {background-color:#F3F3F3}
table.stable { border:1px solid #CDCCCC; background:#fff; margin:5px; padding:10px; display:block;}
table.stable td { font-size:11px; padding:2px 5px; line-height:12px; }
table.stable input, table.stable select { font-size: 11px; margin: 5px 5 5px 5px;}
table.grilla tr { border-bottom: 1px solid #cfcccc; }
span.error { font-size:10px; }
div.success { border:1px solid #74B71B; color:#74b71b; padding:10px; margin:10px; float:left; text-align:left; display:block; font-size:14px;}
table.table-destacado td { font-size:13px; padding:4px;}
div.success { border:1px solid #74B71B; color:#74b71b; padding:10px; margin:10px; float:left; text-align:left; display:block; font-size:14px;}
</style>

<?php if (isset($nodo) && count($nodo)==1 && $nodo->nombre=='swcobranza'):?>
<form method="post" action="<?php echo site_url();?>/admin/procurador/estado_masivo<?php echo '?id_distrito='.$id_distrito.'&nombres='.$nombres.'&apellido_demandado='.$apellido_demandado.'&id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&receptor='.$receptor.'&id_mandante='.$id_mandante.'&id_procurador='.$id_procurador.'&orden_distrito=';?>">

<div class="agregar-noticia">
<?php if ($this->session->flashdata('success_etapas')!=''):?>

<div class="success">
<?php echo $this->session->flashdata('success_etapas');?>
</div><div class="clear"></div>
<?php endif;?>
<?php 
	echo form_label('Etapa de Juicio', 'id_etapa'/*,$attributes*/);
	echo form_dropdown('id_etapa_masiva', $etapas, '');
?>
<input type="submit" value="Cambiar estado a cuentas seleccionadas" class="boton" style="width:350px" />
<input type="hidden" name="chks" value="" />
</div>
</form>

<?php endif;?>

<div id="box-form-informar"><br></div>

<?php if (isset($nodo) && count($nodo)==1 && $nodo->nombre=='fullpay'):?>
<div class="agregar-noticia">
    
    <a href="<?php echo site_url();?>/admin/procurador/exportar_excel?rut=<?php echo $rut_mandante?>&id_mandante=<?php echo $id_mandante;?>&id_estado_cuenta=<?php echo $id_estado_cuenta;?>&id_procurador=<?php echo $id_procurador;?>&rol=<?php echo $rol;?>&id_distrito=<?php echo $id_distrito;?>&id_tribunal=<?php echo $id_tribunal;?>&nombres=<?php echo $nombres;?>&ap_pat=<?php echo $ap_pat;?>&id_etapa=<?php echo $id_etapa;?>&rol=<?php echo $rol;?>&role=<?php echo $rolE;?>&id_distritoe=<?php echo $id_distritoE;?>&id_tribunale=<?php echo $id_tribunalE;?>"  class="ico-excel" style="width:150px;">Exportar a Excel</a>
  
  </div><div class="clear height"></div>
    <?php endif?>
<?php echo $this->pagination->create_links(); ?><div class="clear height"></div>
<table class="stable grilla" width="100%" >
<tr class="menu">
	<!--<td class="nombre"><input type="checkbox" name="all" class="chk_all" value="1" /> </td>	-->

<!-- PROCURADOR TITULO  -->
  <td class="nombre">Procurador</td>
	
 <td class="nombre"><a href="<?php echo site_url().'/admin/procurador/?id_distrito='.$id_distrito.'&nombres='.$nombres.'&id_estado_cuenta='.$id_estado_cuenta.'&apellido_demandado='.$apellido_demandado.'&id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&receptor='.$receptor.'&id_mandante='.$id_mandante.'&id_procurador='.$id_procurador.'&rol='.$rol.'&orden_id_tribunal=';?><?php if($orden_id_tribunal!='' && $orden_id_tribunal=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Tribunal</a></td>
 <td class="nombre"><a href="<?php echo site_url().'/admin/procurador/?id_distrito='.$id_distrito.'&nombres='.$nombres.'&id_estado_cuenta='.$id_estado_cuenta.'&apellido_demandado='.$apellido_demandado.'&id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&receptor='.$receptor.'&id_mandante='.$id_mandante.'&id_procurador='.$id_procurador.'&rol='.$rol.'&fechaa=';?><?php if($fechaa!='' && $fechaa=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Fecha Asignación</a></td> 
 <td class="nombre"><a href="<?php echo site_url().'/admin/procurador/?id_distrito='.$id_distrito.'&nombres='.$nombres.'&id_estado_cuenta='.$id_estado_cuenta.'&apellido_demandado='.$apellido_demandado.'&id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&receptor='.$receptor.'&id_mandante='.$id_mandante.'&id_procurador='.$id_procurador.'&rol='.$rol.'&orden_rol=';?><?php if($orden_rol!='' && $orden_rol=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Rol</a></td> 
 <td class="nombre"><a href="<?php echo site_url().'/admin/procurador/?id_distrito='.$id_distrito.'&nombres='.$nombres.'&id_estado_cuenta='.$id_estado_cuenta.'&apellido_demandado='.$apellido_demandado.'&id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&receptor='.$receptor.'&id_mandante='.$id_mandante.'&id_procurador='.$id_procurador.'&rol='.$rol.'&mandante=';?><?php if($mandante!='' && $mandante=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Mandante</a></td>
 <td class="nombre"><a href="<?php echo site_url().'/admin/procurador/?id_distrito='.$id_distrito.'&nombres='.$nombres.'&id_estado_cuenta='.$id_estado_cuenta.'&apellido_demandado='.$apellido_demandado.'&id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&receptor='.$receptor.'&id_mandante='.$id_mandante.'&id_procurador='.$id_procurador.'&rol='.$rol.'&orden_ap_pat=';?><?php if($orden_ap_pat!='' && $orden_ap_pat=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Demandado</a></td> 
 
 <!--<td class="nombre"><a href="<?php echo site_url().'/admin/procurador/?id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&id_estado_cuenta='.$id_estado_cuenta.'&apellido_demandado='.$apellido_demandado.'&id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&receptor='.$receptor.'&id_mandante='.$id_mandante.'&id_procurador='.$id_procurador.'&rol='.$rol.'&orden_id_tribunal=';?><?php if($orden_id_tribunal!='' && $orden_id_tribunal=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Trib. Exhortado</a></td> !-->
 <td class="nombre"><a title="">Rol. Exhorto</a></td> 

 <?php if ($nodo->nombre=='fullpay'):?>


  <td class="nombre">Etapa anterior</td>
  <?php endif;?>


  <?php if ($nodo->nombre=='swcobranza'):?>
  <td class="nombre">Última observación</td>
  <?php endif;?>
  
  <td class="nombre"><a href="<?php echo site_url().'/admin/procurador/?etapa_actual='.$etapa_actual.'&nombres='.$nombres.'&id_estado_cuenta='.$id_estado_cuenta.'&apellido_demandado='.$apellido_demandado.'&id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&receptor='.$receptor.'&id_mandante='.$id_mandante.'&id_procurador='.$id_procurador.'&rol='.$rol.'&orden_id_etapa=';?><?php if($orden_id_etapa!='' && $orden_id_etapa=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Etapa Actual</a></td> 
  
  
  <td class="nombre">Observacion Actual</td>
  <td class="nombre" ><a href="<?php echo site_url().'/admin/procurador/?id_distrito='.$id_distrito.'&nombres='.$nombres.'&id_estado_cuenta='.$id_estado_cuenta.'&apellido_demandado='.$apellido_demandado.'&id_tribunal='.$id_tribunal.'&nombres='.$nombres.'&ap_pat='.$ap_pat.'&receptor='.$receptor.'&id_mandante='.$id_mandante.'&id_procurador='.$id_procurador.'&rol='.$rol.'&diasatraso=';?><?php if($diasatraso!='' && $diasatraso=='asc'){ echo 'desc'; } else { echo 'asc'; } ?>" title="">Dia Atraso</a></td>
  <td class="nombre">Etapa de juicio</td>
  <td class="nombre">Fecha</td>  
    
  <td class="nombre">Observación</td>
  
  
</tr>


<!-- print_r muestra array en la lista procurador  -->
<?php //print_r($lists) ?>


<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>
<form action="<?php echo site_url()?>/admin/procurador/etapa/save" method="post" class="form_table" data-id="<?php echo $val->id;?>" autocomplete="off">



<?php
//########################### LOGICA DE NUMEROS DE COLORES - DIAS ALERTA ##############// 

/*
if($val->acuse==0)
{
	if($val->eta==1 || $val->eta==0)
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
					if ($i%2==0){$diseno = ' class="b"';}else{$diseno = 'class="a"';}
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
}
else
{
	if ($i%2==0){$diseno = ' class="b"';}else{$diseno = ' class="a"';}
	$color = "#0000;";
	$color_atraso = "#F00";
}
//#F00

*/



//################# NEW ALERTAS NUMEROS ##################### //
$dias_etapas = $val->dias_alerta;
$porcent_holgura = 1;
$holgura = $porcent_holgura*$dias_etapas;
$porcent75= 75/100;
$porcent100= 100/100;

//echo $holgura;


if($val->acuse==0)
{
  if($val->dias_alerta_diferencia / $holgura)
  {
  

    if ($val->dias_alerta_diferencia <= $holgura*$porcent75)
      { 
        //ALERTA COLOR VERDE
        $color_atraso = "#17a10a";
      
    }else {
        
    if ($val->dias_alerta_diferencia > $holgura*$porcent75  AND $val->dias_alerta_diferencia <= $holgura*$porcent100)
            { 
              //ALERTA COLOR AMARILLO
            $color_atraso = "#f1ff0e";

            

            }else{
                 //ALERTA COLOR ROJO
                $color_atraso = "#f20d0d";

                }

          }
    
          
        
    }

   }



?>   

<tr <?php echo $diseno;?> id="row-<?php echo $val->id;?>">
  <!--<td> -->
  <!-- <?php echo $val->eta;?> -->
  
 <!-- <input type="checkbox" name="chk[<?php echo $val->id;?>]" class="chk" value="1" data-id="<?php echo $val->id;?>" autocomplete="off"/></td> -->

 <!-- DATA PROCURADOR-->
  <td><font color="<?php echo $color;?>"><?php echo $val->username;?></font></td>  

  <td style="text-align: center;"><font color="<?php echo $color;?>"> <?php echo $val->tribunal;?><br><?php echo $val->distrito;?></font></td>
  <td><font color="<?php echo $color;?>"><?php echo $val->fecha_asignacion;?></font></td>  
  <!--<td><?php echo $val->tribunal;?></td>-->
  <td><font color="<?php echo $color;?>"><?php echo strtoupper($val->rol);?></font></td>
  <td><font color="<?php echo $color;?>"><?php echo $val->codigo_mandante;?></font></td>
  <td><font color="<?php echo $color;?>"><?php echo strtoupper($val->ap_pat).'<br>'.$val->rut;?>
 <?php if(($val->monto_deuda <= '200.000' && $nodo->nombre == 'swcobranza')){?>
 <?php } ?>
  <?php 
  if ($val->id_estado_cuenta==1){echo '<span style="color:green;font-weight:bold; font-family:verdana;">VIGENTE</span>';}
  if ($val->id_estado_cuenta==7){echo '<span style="color:yellow;font-weight:bold; font-family:verdana;">DEV. DOCUMENTOS</span>';}
  if ($val->id_estado_cuenta==2 && $nodo->nombre=='fullpay'){echo '<span style="color:grey;font-family:verdana;font-weight:bold;class="gris">RECHAZO INGRESA</span>';}
  if ($val->id_estado_cuenta==2 && $nodo->nombre=='swcobranza'){echo '<span class="gris">Rechazo INGRESA</span>';}
  if ($val->id_estado_cuenta==3){echo '<span style="color:blue;font-weight:bold; font-family:verdana;span class="blue">SUSPENDIDO</span>';}
  if ($val->id_estado_cuenta==4){echo '<span style="color:red;font-weight:bold; font-family:verdana;span class="red">TERMINADO</span>';}
  //if ($val->id_estado_cuenta==5 && $nodo->nombre=='swcobranza'){echo '<span class="purple">CONVENIO</span>';}
  if ($val->id_estado_cuenta==5 && $nodo->nombre=='fullpay'){echo '<span style="color:purple;font-weight:bold; font-family:verdana;span class="purple">DEVUELTO</span>';}
  //if ($val->id_estado_cuenta==6 && $nodo->nombre=='swcobranza'){echo '<span class="purple2">CONVENIO INCUMPLIDO</span>';}
  if ($val->id_estado_cuenta==6 && $nodo->nombre=='fullpay'){echo '<span style="color:blue;font-weight:bold; font-family:verdana;span class="blue">EXHORTO</span>';}
  //if ($val->id_estado_cuenta==7 && $nodo->nombre=='swcobranza'){echo '<span class="blue2">SUSPENDIDO CON CONVENIO</span>';}
  if ($val->id_estado_cuenta==8 && $nodo->nombre=='fullpay'){echo '<span style="color:orange;font-weight:bold; font-family:verdana;span class="orange">AVENIMIENTO</span>';}
  //if ($val->id_estado_cuenta==8 && $nodo->nombre=='swcobranza'){echo '<span class="blue3">GPVE rechazadas</span>';}
  //if ($val->id_estado_cuenta==9 && $nodo->nombre=='swcobranza'){echo '<span class="yellow">ABONO</span>';}

  ?>
    
  </font>
  </td>
  <td style="text-align: center;"><font color="<?php echo $color;?>">
  <?php echo $val->rolE;?><br>
  <?php echo $val->tribunalE;?><br><?php echo $val->DistritoE;  ?>


  </font> </td>
  
  
  <?php if ($nodo->nombre=='fullpay'):?>
  <td><font color="<?php echo $color;?>"><?php 
  $cuentas_etapas = $this->cuentas_etapas_m->get_cuentas_etapas($val->id);
  if (count($cuentas_etapas)>1){
  	if ($cuentas_etapas[1]->fecha_etapa!='' && $cuentas_etapas[1]->fecha_etapa!='0000-00-00'){ 
		echo date('d-m-Y H:i:s',strtotime($cuentas_etapas[1]->fecha_etapa)).' ';
	} 
	echo $cuentas_etapas[1]->etapa;
  }
  
  ?></font></td>
  <?php endif;?>
  <?php if ($nodo->nombre=='swcobranza'):?>
  <td><font color="<?php echo $color;?>">
  <?php 
  $this->db->order_by('fecha DESC');
  if ($user_id!=''){ $this->db->where('user_crea',$user_id); }
  $historial = $this->cuentas_historial_m->get_by(array('id_cuenta'=>$val->id));
  if (count($historial)==1){
  	echo $historial->historial;
  }
  ?></font>
  </td>
  <?php endif;?>
  <td id="text_etapa_<?php echo $val->id ?>"><font color="<?php echo $color;?>">
  	<?php echo $val->etapa_actual; 
	if ($val->fecha_etapa!='' && $val->fecha_etapa!='0000-00-00'){ 
		echo '<br>'.date('d-m-Y H:i:s',strtotime($val->fecha_etapa));
	}
	?></font></td>
  
  
  <td title="<?php echo $val->observaciones;?>"><font color="<?php echo $color;?>">
	<?php echo substr($val->observaciones, 0, 19);?>...
  </font></td>
  <td style="color:<?php echo $color_atraso;?>;text-align: center; font-size: 17px; font-family: impact; text-shadow: -1px -1px 1px #000, 1px 1px 1px #000, -1px 1px 1px #000; ">
	<!--<?php echo $val->dias_diferencia;?>-->
  <?php echo $val->dias_alerta_diferencia?>

  </td>

  <!--
  <td>
	<?php echo form_dropdown('etapa_juicio', $etapas_juicio, $val->id_etapa/*$this->input->post('etapa_juicio')*/ ,'autocomplete="off" id="select_'.$val->id.'" data-id="'.$val->id.'" style="width:200px;" ');?>
  </td>
-->


  <!-- ############### ETAPAS DE JUICIO ############## -->
      <td>
      <?php echo form_dropdown('etapa_juicio', $etapas_juicio, $id_etapa_m,' class="select_dos" autocomplete="off" data-id="'.$id.'" style="width:200px;"');?>
     
      <?php echo form_error('id_etapa','<br><span class="error">','</span>');?>
      </td>



  <!-- ############### FECHA ETAPA ############## -->

  <td>
	<input type="text" style="width:100px;" class="datepicker" name="fecha_etapa" value="<?php echo $fecha_etapa_m;?>"><?php echo form_error('fecha_etapa','<br><span class="error">','</span>' );?>
  </td>
  
  <!-- FIN FECHA ETEPA -->

  <select style="width:97%; display:none;" name="etapa_otro" >
  	<option value="">Seleccionar</option>
  </select>
  
  <input type="hidden" name="id_cuenta" value="<?php echo $val->id?>">
  
    
  <td><span id="fecha_box_<?php echo $val->id?>" style="display:none;">
		<?php echo day_dropdown('fecha_inicio_day',$fecha_inicio_day).month_dropdown('fecha_inicio_month',$fecha_inicio_month).year_dropdown('fecha_inicio_year',$fecha_inicio_year,2010,date('Y')+10); ?>
	</span>
	
	<input style="width:120px" type="text" name="observaciones">
	<!--<input style="width:75px" class="boton" type="submit" value="Guardar" >-->
  </td>  
  <td><!--input data-id="<?php echo $val->id;?>" style="padding-right: 0px;height: 18px;" class="boton info" type="buttom" value="Ver Cuenta" ><br /-->
	  <a href="<?php echo site_url('admin/gestion/index/'.$val->id);?>" >Editar</font></a>
  </td>
  
   <!-- <td>	  
		<!--<a id="enviar" alt="<?php echo $val->id?>">Informar</a>-->
		<!--<a href="#"   class="edit"  data-id="<?php echo $val->id;?>" data-gtab="informar_reg" >Informarxx</a>
		<?php if($this->session->userdata("usuario_perfil") ==  1):?>	  
			<a id="eliminar" alt="<?php echo $val->id?>" >Sacar de Rojo </a>
		<?php endif; ?>
	</td>-->
	 
	<!--<td>  
	   <a href="#"   class="edit"  data-id="<?php echo $val->id;?>" data-gtab="informar_reg" >Informar</a>	   
	</td>-->
  
  <?php $row_current=array(); $row_current=$val; //include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
 
 
 <tr id="info_<?php echo $val->id;?>" style="display: none;" >

 </tr></form>

<?php ++$i;endforeach;?>


</table>

<?php echo $this->pagination->create_links(); ?>
<style>
.dentro td{
	background:#FFF;
	line-height:20px;
	height:30px;
}
</style>

<script type="text/javascript">
$(document).ready(function(){

	$( '.info' ).each(function( index ) {
		$(this).val('Ver Cuenta');
	});
	$(".datepicker").datepicker({ format: 'dd-mm-yyyy',});
	$( 'select[name=etapa_juicio]' ).each(function( index ) {
			var obj = $(this);
			var id = obj.val();

	        $.ajax({
	            type: 'post',
	            url: '<?php echo site_url()?>/admin/procurador/dropdown_ajax_etapa/'+obj.val(),
	            data: '',
	            success: function (data) {
	                $( '#select_'+obj.attr('data-id') ).html( data );
	                
	            },
	            error: function(objeto, quepaso, otroobj) {
	            },
	        });

	});

	
	$(document).on("click",".info",function(e){
		var id_box = $(this).attr('data-id');
		
		$('#info_'+id_box).fadeIn();
		$('#info_'+id_box).html('<td colspan="2"><b>Cargando Datos.</b></td>');
		$(this).attr('value','Cerrar');
		$(this).addClass('close-info');
		$(this).removeClass('info');

		$.ajax({
            type: 'post',
            url: '<?php echo site_url()?>/admin/cuentas/list_ajax/'+id_box,
            data: '',
            success: function (data) {
            	$('#info_'+id_box).html( data );
                
            },
            error: function(objeto, quepaso, otroobj) {
            },
        });
		return false;
	});

	
	$(document).on("click","#eliminar",function(e){
		var id_box = $(this).attr('alt');
		var r = confirm("Desea procesar la cuenta?");
		if (r == true) {
			$.ajax({
				type: 'post',
				url: '<?php echo site_url()?>/admin/cuentas/eliminaracuse/'+id_box,
				data: '',
				success: function (data) {
					//$('#info_'+id_box).html( data );
					alert('Actualizado');
					location.reload();               
				},
				error: function(objeto, quepaso, otroobj) {
				},
			});
		} else {
			alert('Proceso cancelado');
		}
		return false;
	});
	
	$(document).on("click","#enviar",function(e){
		var id_box = $(this).attr('alt');
		var r = confirm("Desea procesar la cuenta?");
		if (r == true) {
			$.ajax({
				type: 'post',
				url: '<?php echo site_url()?>/admin/cuentas/enviaracuse/'+id_box,
				data: '',
				success: function (data) {
					alert('Actualizado');					
				},
				error: function(objeto, quepaso, otroobj) {
				},
			});
		} else {
			alert('Proceso cancelado');
		}		
		return false;
	});
	
	$(document).on("click",".edit",function(e){
		tr = $(this).parent('td').parent('tr');
		tr.addClass('current');
		var id = $(this).data('id');
		var gtab = $(this).data('gtab');
		 $.ajax({
		   type: 'post',
		   url: '<?php echo site_url()?>/admin/gestion<?php echo $id;?>/'+gtab+'/'+id,
		   success: function (data) {
			  $("#box-form-informar").html(data);
			  $('body, html').animate({
				scrollTop: '0px'
			  }, 300);
		   },
	   });
	   return false;
	});
	
	$(document).on("click",".close-info",function(e){
		var id_box = $(this).attr('data-id');
		$('#info_'+id_box).fadeOut();
		$(this).attr('value','Ver Cuenta');
		$(this).addClass('info');
		$(this).removeClass('close-info');
		return false;
	});

	$('select[name=etapa_juicio]').change(function(){
		
		var idotro = $(this).attr('data-id');
		var valor = $(this).val();

		$.ajax({
            type: 'post',
            url: '<?php echo site_url()?>/admin/procurador/fecha_etapa/'+valor,
            data: '',
            success: function (data) {
                if(data == 1){
	            	$( '#fecha_box_'+idotro ).show();
            	}else{
                	
            		var boxx = $( '#fecha_box_'+idotro );
                    blockCont = boxx.find('select'); 
                    blockCont.val('');
                    boxx.hide();
                    
             	}
            },
            error: function(objeto, quepaso, otroobj) {
            },
        });
        
		if( $(this).val() == 'otro'){

			$.ajax({
	            type: 'post',
	            url: '<?php echo site_url()?>/admin/procurador/dropdown_ajax_etapa_otro/',
	            data: '',
	            success: function (data) {
	            	$( '#otro_'+idotro+' select' ).css( 'display','block' );
	                $( '#otro_'+idotro+' select' ).show(  );
					$( '#otro_'+idotro ).html( '<select name="etapa_otro">'+data+'</select>' );
	            },
	            error: function(objeto, quepaso, otroobj) {
	            },
	        });
			
		}//fin if otro
	});

	return false;
           
});

$(document).on('change','.chk', function(){
	var chk = '';
	console.log('a');
	$('.chk').each(function(){
		if ($(this).is(":checked")){
			if (chk!=''){chk=chk+',';}
			chk=chk+$(this).attr('data-id');
		}
	});	
	$("input[name='chks']").val(chk);
});

$(document).on('submit','.form_table',function(){
	var idbox = $(this).attr('data-id');
	//alert($(this).serialize());
    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (data) {
            $( '#text_etapa_'+idbox ).html( data );
        },
		complete: function(){
			
			var obj = $("#select_"+idbox);

	        $.ajax({
	            type: 'post',
	            url: '<?php echo site_url()?>/admin/procurador/dropdown_ajax_etapa/'+obj.val(),
	            data: '',
	            success: function (data) {
	                obj.html( data );
	                
	            },
	        });
			
		},
        error: function(objeto, quepaso, otroobj) {
        },
    });
    return false;
});
</script>
<?php endif;?>