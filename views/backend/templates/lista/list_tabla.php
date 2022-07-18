
<?php if (count($lists)>0): ?>


<?php /*echo '<pre>'; print_r($lists); echo '</pre>';*/?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  
  <td width="13%" class="nombre">Nombre Comuna</td>
 <td width="13%" class="nombre">Herramienta</td>

</tr>
<?php $i=1; $check_id=1;
foreach ($lists as $key=>$val):?>

<tr id="tools_" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>">
    <td><?php echo $val->nombre;?></td>
	
  <?php $row_current=array(); $row_current=$val; include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?> 
  

  
 </tr>

<?php ++$i;endforeach;?>

</table>
<?php endif;?>