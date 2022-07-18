<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

<?php $i=1; $check_id=1;
foreach ($lists as $key=>$val): ?>

<tr <?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">   

  <td>
    <?php $this->load->view('backend/templates/mod/cat_tools');?>
	<a href="<?php echo site_url().'/admin/institucion/form/editar/'.$val->idinstituciones;?>" title="Editar">

    <?php if($nodo->nombre == 'fullpay') { ?>

    <?php echo '<span>'.$val->razon_social.'</span>' ?>
    <?php } ?>
  </td>

  <td>
  <?php echo '<span>'.$val->alias.'</span>'?> 
  </td>

  <td width="30%">
	<?php echo '<span>'.$val->representante_direccion.' '.$val->representante_direccion_n.'</span>'?>	
  </td>

  <?php   $row_current=array(); $row_current=$val;$row_current->field_categoria=1;include APPPATH.'views/backend/templates/mod/table_tools.php'; ?>
  </td>
<!-- <form > 
<td width="6%">
    <?php /* if ($nodo->nombre=='fullpay'):

    $cartas_check = FALSE; if ($lists->priori == '1'){$cartas_check = TRUE;}
          
    echo form_checkbox(array('name'=>'priori','class'=>'chk_cartas','style'=>'width:135px'),1,$cartas_check);

    echo form_error('priori', '<span class="error" style="margin-left:200px;">', '</span>');
         endif; */?>
<input type="checkbox" name="page" value="<?php echo $val->id;?>">  -->   
</td>    
<?php ++$i;
endforeach;?>