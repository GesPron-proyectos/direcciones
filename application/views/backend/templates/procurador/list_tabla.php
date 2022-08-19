
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-">
<td><?php echo $i;?></td>

  <td><a href="<?php echo site_url('admin/procurador/index/'.$val->rut);?>"><?php echo $val->rut;?></a></td>
  <td><?php echo $val->dv;?></td>
	<td><?php echo $val->cuenta_rut;?></td>
	<td><?php echo $val->datos;?></td> 
</tr>
<?php ++$i;endforeach;?>



