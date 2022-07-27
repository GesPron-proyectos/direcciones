<?php $rut = ''; if (isset($_REQUEST['rut'])){$rut = $_REQUEST['rut'];} 
	  $rut_parcial = ''; if (isset($_REQUEST['rut_parcial'])){$rut_parcial = $_REQUEST['rut_parcial'];}?>

<?php $i=1; foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->rut;?>">
	<td><?php echo $i;?></td>

	<td><?php echo $val->rut;?></td>
	<td><?php echo $val->dv;?></td>
	<td><?php echo $val->cuenta_rut;?></td>
	<td><?php echo $val->datos;?></td> 
	<td><?php echo $val->fecha_crea;?></td>
</tr>
<?php ++$i;endforeach;?>