<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

<?php
//print_r($lists);
 $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>"  >

  <td width="11%"><?php echo $val->nombre;?></td>
  <td width="11%"><?php echo $val->ApePat.' ';?><?php echo $val->ApeMat;?></td> 
  <td width="11%"><?php echo $val->tribunal;?></td>
   <?php if ($nodo->nombre=='fullpay'):?>
  <!--<td width="11%"><?php echo $val->tribunal;?></td>-->
  <?php endif;?>
  <td width="11%"><?php echo $val->email;?></td>
  <td width="11%"><?php echo $val->telefono;?></td>
  <td width="11%"><?php echo $val->celular;?></td>

  
  	<td class="tools" width="100%">
		<table width="75%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		  
		    <td width="15%" height="20">
		    <a href="<?php  echo site_url().'/admin/'.$current.'/form/editar/'.$val->id; ?>" class="editar" title="editar"></a>
			</td>
		
		    <td width="15%">
			    <?php if ($this->session->userdata("usuario_perfil")<=2):?>
			    	<a style="cursor:pointer;" class="eliminar xtool" rel="N" id="<?php echo $val->id;?>" title="activo"></a>
			    <?php endif;?>
		    </td>
			<td width="15%" height="20">
		    <a href="<?php  echo site_url().'/admin/'.$current.'/cuentas/'.$val->id; ?>" class="receptor" title="Cuentas"></a>
			</td>
		
		  </tr>
		</table>
	</td>
	
</tr>

<?php ++$i;endforeach;?>



