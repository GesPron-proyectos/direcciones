<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
  <table width="100%" cellpadding="0" cellspacing="0" border="1">
  <tr>
    <td width="50%">
     <legend style="border: 1px groove #ddd !important;padding: 0px 1.4em 5.4em 1.4em !important;margin: 6px 0px 2.5em -1px !important;-webkit-box-shadow:  0px 0px 0px 0px #000;box-shadow:  0px 0px 0px 0px #000;"><br><fieldset style="font-size: medium !important;font-weight: bold !important;text-align: left !important;width:auto;padding:0 10px;border-bottom:none;">Reportes:</fieldset>

    <table  width="100%">
    <?php echo form_open(site_url().'/admin/gestion/exportar_reportes/'.$id); ?>
    <tr>
    	<td>
    		<?php if($nodo->nombre == 'fullpay'){ ?> 
			<a href="<?php echo site_url();?>/admin/cuentas/exportar_reportes_fullpay?rut=<?php echo $rut?>&id_mandante=<?php echo $id_mandante;?>&id_estado_cuenta=<?php echo $id_estado_cuenta;?>&id_procurador=<?php echo $id_procurador;?>&rol=<?php echo $rol;?>&id_distrito=<?php echo $id_distrito;?>&id_tribunal=<?php echo $id_tribunal;?>&rut_parcial=<?php echo $rut_parcial;?>&nombres=<?php echo $nombres;?>&ap_pat=<?php echo $ap_pat;?>&nombre=<?php echo $nombre;?>&id_tribunal_comuna=<?php echo $id_tribunal_comuna;?>&fecha_asignacion_pagare=<?php echo $fecha_asignacion_pagare;?>&diferencia=<?php echo $diferencia;?>&tribunal=<?php echo $tribunal;?>&tribunalE=<?php echo $tribunalE;?>" class="ico-excel" style="width: 110px;">Exportar Reporte</a>
    	</td>
    </tr>
<?php } ?>

    <?php echo form_close();?>
	</table>
</table>
</body>
</html>