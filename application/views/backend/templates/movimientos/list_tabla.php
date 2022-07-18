<?php if (count($lists)>0): ?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">
 <td>CUADERNO</td>
 <td>DATOS RETIRO</td>
  <td>ESTADO</td>
</tr>
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">

  <td><?php echo $val->cuaderno;?></td>
  <td><?php echo $val->datos_retiro;?></td>
  <td><?php echo $val->estado;?></td>
</tr>
<?php ++$i;endforeach;?>
</table>
<?php endif;?>