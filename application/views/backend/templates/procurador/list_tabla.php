<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

<?php $i=1; foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">
	<td><?php echo $i;?></td>
	<td><?php echo $val->rut;?></td>
	<td><?php echo $val->dv;?></td>
	<td><?php echo $val->cuenta_rut;?></td>
	<td><?php echo $val->datos;?></td> 
	
</tr>
<?php ++$i;endforeach;?>