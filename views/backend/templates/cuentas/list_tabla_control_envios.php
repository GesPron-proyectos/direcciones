<?php //include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

<?php //$id_estado_cuenta = ''; if (isset($_REQUEST['id_estado_cuenta'])){$id_estado_cuenta = $_REQUEST['id_estado_cuenta']; } ?>
<?php if (count($lists)>0): ?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">
 <td>PROCURADOR</td>
  <td>MANDANTE</td>
  <td>ESTADO</td>
  <td>DISTRITO</td>
  <!--<td>JUZGADO</td>-->
  <td>GESTION</td>
</tr>
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">

  <td><?php echo $val->procurador;?></td>
  <td><?php echo $val->mandante;?></td>
  <td><?php echo $val->estado;?></td>
  <td><?php echo $val->distrito;?></td>
  <!--<td><?php echo $val->juzgado;?></td>-->
  <td>
    <!--<a href="#" rel="<?php echo $val->id; ?>" class="editar" title="editar"></a>-->
    <a style="cursor:pointer;" class="eliminar xtool" href="<?php echo site_url().'/admin/control_envios/eliminar_configuracion/'.$val->id; ?>"></a>
  </td>
</tr>
<?php ++$i;endforeach;?>
</table>
<?php endif;?>