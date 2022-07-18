<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">
  <td><?php echo $i; ?></td>
  <td><?php echo $val->nombre.' '.$val->apellidop.' '.$val->apellidom;?></td>
  <td><?php echo $val->rut;?></td>
  <td><?php echo $val->sistema;?></td>
 <?php $row_current=array(); $row_current=$val; // include APPPATH.'views/backend/templates/mod/table_tools.php';?>

    <td> <a href="<?php echo site_url().'/admin/'.$current.'/form/editar/'.$row_current->id;?>" class="editar" title="editar"></a> 
       <a style="cursor:pointer;" class="eliminar xtool" rel="N" id="actualizar/<?php echo $row_current->id;?>" title="activo"></a>
    </td>
</tr>

<?php ++$i;endforeach;?>

