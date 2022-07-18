

<?php if (count($lists)>0): ?>
<div class="tabla-listado">
  <table class="listado" width="100%">
 <tr class="menu" style="line-height:20px; height:50px;">
  <td width="13%" class="nombre">Rut</td>
  <td width="13%" class="nombre">Nombres</td>
  <td width="13%" class="nombre">Apellidos</td>
  <td width="13%" class="nombre">Procurador</td>
 </tr>
<?php $i=1; $check_id=1;
foreach ($lists as $key=>$val):?>

<tr id="tools_" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>">
     <td><?php echo $val->rut;?></td>
     <td><?php echo $val->nombres;?></td>
     <td><?php echo $val->ap_pat.' '.$val->ap_mat;?></td>
	 <td><?php echo $val->apellidos;?></td>
  <?php $row_current=array(); $row_current=$val; include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?> 
  </tr>

<?php ++$i;endforeach;?>
 </table>
 </div>
<?php endif;?>