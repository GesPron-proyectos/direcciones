<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Historial de etapas</strong></h2>
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
<?php if (count($etapas)>0):?>
<table class="stable grilla" width="100%">
<tr>
<td>Etapa</td>
<?php 
$y = date('Y'); 
$m=date('n');
if ($m==12){$m=1;} else {$y--;} 
?>
<?php for ($i=1;$i<=12;$i++):?>
<?php $mc = $m; if ($mc<10){$mc='0'.$mc;}?>
<td>
	<?php echo $mc.'-'.$y;?> <?php if ($m==12){ $m=1; $y++;} else {$m++;}?>
</td>
<?php endfor;?>
<td><?php $mc = $m; if ($mc<10){$mc='0'.$mc;} echo $mc.'-'.$y;?></td>
</tr>

<?php foreach ($etapas as $key=>$val):?>
<tr>
<td><?php echo $val['etapa'];?></td>
<?php 
$y = date('Y'); 
$m=date('n');
if ($m==12){$m=1;} else {$y--;} 
?>
<?php for ($i=1;$i<=12;$i++):?>
    <?php if (array_key_exists($y.'-'.$m,$val['historial'])):?>
    <td class="right"><?php echo number_format($val['historial'][$y.'-'.$m]['cuantos'],0,',','.'); $totales[$i]+=$val['historial'][$y.'-'.$m]['cuantos'];?></td>
    <?php else:?>
    <td class="right">0</td>
    <?php endif;?>
    
    
    <?php /*MES ACTUAL*/?>
    <?php if ($i==12):?>
    <?php if (array_key_exists(date('Y').'-'.date('n'),$val['historial'])):?>
    <td class="right"><?php echo number_format($val['historial'][date('Y').'-'.date('n')]['cuantos'],0,',','.'); $totales[$i]+=$val['historial'][date('Y').'-'.date('n')]['cuantos'];?></td>
    <?php else:?>
    <td class="right">0</td>
    <?php endif;?>
    <?php endif;?>
    <?php if ($m==12){$m=1; $y++;} else {$m++;}?>
<?php endfor;?>
</tr>
<?php endforeach;?>



<tr><td colspan="1"></td>
<?php for ($i=1;$i<=13;$i++):?>
<td class="right"><?php echo number_format($totales[$i],0,',','.');?></td>
<?php endfor;?>
</tr>
</table>
<?php endif;?>


</div>



</div>





