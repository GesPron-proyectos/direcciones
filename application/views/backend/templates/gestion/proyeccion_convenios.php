<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Proyección</strong></h2>
  </div>

</div>


<div class="agregar-noticia">
 

<div class="">

	
</div><!-- campo -->





<div class="clear"></div>
<div class="tabla-listado">
<style>
table.stable tr td.right { text-align:right}
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
</style>

<div class="content_tabla">
<?php 
$totales = array();
for ($i=0;$i<=12;$i++){
	$totales[$i] = 0;
}
?>
<?php if (count($cuentas)>0):?>
<table class="stable grilla" width="100%">
<tr>
<td>RUT</td><td>Fecha Primero Pago</td><td>Nº Abonos</td><td>Nº Abonos pendientes</td><td>Mes Actual Real</td><td>Mes Actual</td>
<?php for ($i=1;$i<=13;$i++):?>
<td>Mes <?php echo $i;?></td>
<?php endfor;?>
</tr>
<?php foreach ($cuentas as $key=>$val):?>
<tr>
<td><?php echo $val->rut;?></td>
<?php 
$y = date('Y'); 
$m=date('m'); 
$y_pp = date('Y',strtotime($val->fecha_primer_pago)); $m_pp = date('m',strtotime($val->fecha_primer_pago));
$queda_por_pagar = $val->n_cuotas-(12-$m_pp);
?>
<td class="right"><?php echo date('d-m-Y',strtotime($val->fecha_primer_pago));?></td>
<td class="right"><?php echo $val->n_cuotas;?></td>
<td class="right"><?php if ($queda_por_pagar>0){echo $queda_por_pagar;}?></td>
<td class="right"><?php if (array_key_exists($val->id,$pagos)):?><?php echo  number_format($pagos[$val->id]['monto_pagado'],0,',','.'); $totales[0]+=$pagos[$val->id]['monto_pagado'];?><?php endif;?></td>
<td class="right"><?php echo number_format($val->valor_cuota,0,',','.'); $totales[1]+=$val->valor_cuota?></td>
<?php for ($i=2;$i<=13;$i++):?>
	<?php if ($queda_por_pagar>0 && ($queda_por_pagar-$i)>0): $totales[$i]+=$val->valor_cuota; ?>
    <td class="right"><?php echo number_format($val->valor_cuota,0,',','.');?></td>
    <?php else:?>
    <td>-</td>
    <?php endif;?>
<?php endfor;?>
</tr>


<?php endforeach;?>
<tr>
<td colspan="4"></td>
<?php for ($i=0;$i<=13;$i++):?>
<td class="right"><?php echo number_format($totales[$i],0,',','.');?></td>
<?php endfor;?>
</tr>
</table>
<?php endif;?>


</div>




</div>





