 <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
 <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<?php if (count($lists)>0): ?>
<div class="clear"></div>
<div class="tabla-listado">
  <div class="content_tabla">
    <table class="listado" width="100%">
    <tr class="menu" style="line-height:20px; height:50px;">
     <td>CUADERNO</td>
     <td>DATOS RETIRO</td>
      <td>ESTADO</td>
    </tr>
    <?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>
    <tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">

      <td><?php echo $val->cuaderno;?></td>
      <td><?php echo $val->datos_retiro;?></td>
      <td><?php echo $val->estado;?></td>
    </tr>
    <?php ++$i;endforeach;?>
    </table>
  </div>
</div>
<?php endif;?>