<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>



<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>



<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>"  >



  <td width="25%"><a href="<?php echo site_url().'/admin/usuarios/form/editar/'.$val->id;?>" title=""><?php echo $val->nombres.' '.$val->ap_pat.' '.$val->ap_mat;?></a></td>

  <td width="11%"><?php echo $val->rut;?></td>

<td width="11%"><a href="<?php echo site_url('admin/gestion/index/'.$val->id_cuenta);?>"> <?php echo $val->id_cuenta; ?></a></td>

<?php $row_current=array(); $row_current=$val; $row_current->field_categoria=1;include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?> 



</tr>



<?php ++$i;endforeach;?>



