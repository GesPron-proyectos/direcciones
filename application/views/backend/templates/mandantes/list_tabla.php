<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>



<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>



<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">



  <td>
  <a href="<?php echo site_url().'/admin/mandantes/form/editar/'.$val->id;?>" title="">

      <?php if($nodo->nombre == 'fullpay') { ?>
      <?php    if($val->clase_html == 1  ) {  ?>
      <?php echo '<span class="red">'.$val->codigo_mandante.'</span>' ?> <?php  } else {  echo $val->codigo_mandante; } ?>      </td>
       <?php } ?>

    <?php if($nodo->nombre == 'swcobranza') { ?>

    <?php }?>


  <?php $row_current=array(); $row_current=$val;  $row_current->field_categoria=1;include APPPATH.'views/backend/templates/mod/table_tools.php';?>



</tr>



<?php ++$i;endforeach;?>



