<div class="table-m-sep">

    <div class="agregar-noticia">



    <div class="">
        <?php if($nodo->nombre == 'fullpay'){ ?>
        <?php

        $rol = ''; if (isset($_REQUEST['rol'])){$rol = $_REQUEST['rol'];}
        $id_procurador = ''; if (isset($_REQUEST['id_procurador'])){$id_procurador = $_REQUEST['id_procurador'];}
        $id_mandante = ''; if (isset($_REQUEST['id_mandante'])){$id_mandante = $_REQUEST['id_mandante'];}
        $id_tribunal= ''; if (isset($_REQUEST['id_tribunal'])){$id_tribunal = $_REQUEST['id_tribunal'];}
         $id_etapa = ''; if (isset($_REQUEST['id_etapa'])){$id_etapa = $_REQUEST['id_etapa'];}
        $id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta']; }




        echo form_open(site_url().'/admin/alertas/etapasfa',array('id' => 'form_reg'));


        echo '<div class="campo">';
        echo form_label('Mandante', 'id_mandante');
        echo form_dropdown('id_mandante', $mandantes, $id_mandante);
        echo form_error('id_mandante');
        echo '</div>';

        echo '<div class="campo">';
        echo form_label('Etapa Juicio', 'id_etapa');
        echo form_dropdown('id_etapa', $etapas, $id_etapa);
        echo form_error('id_etapa');
        echo '</div>';

        echo '<div class="campo">';
        echo form_label('Tribunales', 'id_tribunal');
        echo '<div id="anidadotribunal">';
        echo form_dropdown('id_tribunal', $tribunales, $id_tribunal);
        echo form_error('id_tribunal');
        echo '</div>';
        echo '</div>';

        echo '<div class="campo">';
        echo form_label('Procurador', 'id_mandante');
        echo form_dropdown('id_procurador',$procuradores,$id_procurador);
        echo form_error('id_procurador');
        echo '</div>';



        echo '<div class="campo">';
        echo form_label('Rol', 'rol'/*,$attributes*/);
        echo form_input('rol', $rol);
        echo form_error('rol');
        echo '</div>';

        echo '<div class="campo">';
        echo form_label('Estado', 'id_estado_cuenta');
        echo form_dropdown('id_estado_cuenta', $estados, $id_estado_cuenta);
        echo form_error('id_estado_cuenta');
        echo '</div>';

        echo '<div class="campo">';
	    echo form_label('&nbsp;', ''/*,$attributes*/);
	    echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
	    echo '</div>';

        echo form_close();

        ?>
        <?php } ?>

      </div>
    </div>










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
<?php if (count($lists)>0): ?>

<div class="tabla-listado">

	<div class="content_tabla">
<?php //echo '<pre>'; print_r($lists); echo '</pre>';?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  
  <td width="13%" class="nombre">Mandante</td>
  <td width="13%" class="nombre">Procurador</td>
  <td width="13%" class="nombre">Deudor Rut</td>
  <td width="13%" class="nombre">Deudor Nombre</td>
  <td width="13%" class="nombre">Dias Alerta (Diferencia)</td>
  <td width="13%" class="nombre">Texto Alerta</td>
  <td width="13%" class="nombre">Etapa</td>
  <td width="13%" class="nombre">Fecha Ingreso De La Etapa</td>

</tr>
<?php $i=1; $check_id=1;
foreach ($lists as $key=>$val):?>

<tr id="tools_" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>">

	<td><?php echo $val->codigo_mandante;?></td>
    <td><?php echo $val->nombres_adm.' '.$val->apellidos_adm;?></td>
	<td><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>"><?php echo $val->usuarios_rut;?></a></td>
	<td><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>"><?php echo $val->usuarios_nombres;?> <?php echo $val->usuarios_ap_pat;?> <?php echo $val->usuarios_ap_mat;?></a></td>
	<td style="color:#F00;text-align: center;"><?php echo $val->dias_diferencia;?></td>
	<td><?php echo $val->texto_alerta?> (<?php echo $val->dias_alerta;?>)</td>
	<td><?php echo $val->etapa?></td>
	<td style="text-align: center;"><?php echo date("d-m-Y",strtotime($val->fecha_etapa))?></td>

  <?php $row_current=array(); $row_current=$val; //include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
</tr>

<?php ++$i;endforeach;?>

</table>
<?php echo $this->pagination->create_links(); ?>
</div></div>


<?php endif;?>