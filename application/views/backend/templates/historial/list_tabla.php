<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-">
   <td><?php echo $val->nombres.'   '.$val->apellidos; ?></td>
  <td><?php echo date('d-m-Y H:i:s',strtotime($val->fecha));?></td>
  <td><a href="<?php echo site_url('admin/gestion/index/'.$val->id_cuenta);?>"><?php echo $val->rut;?></a></td>
  <td><?php echo $val->etapa;?> <?php echo $val->historial;?></td>
   <td> <?php echo $val->rol;?></td>
</tr>
<?php ++$i;endforeach;?>



