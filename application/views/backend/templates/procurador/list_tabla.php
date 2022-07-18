<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

<?php $i=1; foreach ($lists as $key=>$val): ?>

<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">
	<td><?php echo $i;?></td>
	<td><?php echo $val->nombre;?></td>
	<td><?php echo $val->apellido;?></td>
	<td><?php echo $val->rut;?></td>
	<td><?php echo $val->correo;?></td> 
	<td> 
		<a href="<?php echo site_url().'/admin/'.$current.'/form/editar/'.$val->id;?>" class="editar" title="editar"></a> 
		<a style="cursor:pointer;" class="eliminar xtool" rel="N" id="actualizar/<?php echo $val->id;?>" title="activo"></a>
    </td>
</tr>
<?php ++$i;endforeach;?>