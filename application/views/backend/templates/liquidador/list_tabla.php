<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php'; ?>

<?php $i=1; $check_id=1;
foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">

  <td>
  <?php $this->load->view('backend/templates/mod/cat_tools');?>
  <a href="<?php echo site_url().'/admin/liquidador/form/editar/'.$val->id;?>" title="Editar">

      <?php if($nodo->nombre == 'fullpay') { ?>

      <?php echo '<span>'.$val->representante_nombre. ' ' .$val->representante_apepat.'</span>' ?>
      <?php } ?>

  </td>
  
  <td>
    <?php echo '<span>'.$val->rut.'</span>' ?>

  </td>
       

    <?php if($nodo->nombre == 'swcobranza') { ?>

    <?php }?>

  <?php $row_current=array(); $row_current=$val;  $row_current->field_categoria=1;include APPPATH.'views/backend/templates/mod/table_tools.php'; ?>

</td>
<?php ++$i;
endforeach;?>

