<div class="table-m-sep">
  <div class="table-m-sep-title">
  	<h2><strong>Reporte Fecha Asignación<?php //  echo number_format($total,0,',','.');?></strong></h2>
  </div>
</div><!--table-m-sep-->
<div class="agregar-noticia">
    <!-- campo -->
	</div><!--gregar-noticia-->
<?php if (count($lists)>0): ?>
<?php //echo $this->pagination->create_links(); ?>
<div class="clear"><div>
<div class="tabla-listado">
<div class="content_tabla">
<table class="listado" height="82" width="100%">
<tr class="menu"  style="line-height:20px; height:50px;">
   <td width="4%" class="nombre">Menor o igual a 90 Días</td>
 </tr>
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val): ?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>" height="32">
<td width="10%"><?php echo $val->dias; //echo date("d-m-Y", strtotime($val->fecha_asignacion));?></td>
</tr>
<?php ++$i;endforeach;?>
</table>


<table class="listado" height="82" width="100%">
<tr class="menu"  style="line-height:20px; height:50px;">
 <td width="4%" class="nombre">Mayor a 90 Días y menor a 120 días</td>
</tr>
<?php $i=1; $check_id=1;foreach ($lists3 as $key=>$val): ?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>" height="32">
 <td width="10%"><?php echo $val->dias; //echo date("d-m-Y", strtotime($val->fecha_asignacion));?></td>
</tr>
<?php ++$i;endforeach;?>
</table>



<table class="listado" height="82" width="100%">
<tr class="menu"  style="line-height:20px; height:50px;">
 <td width="4%" class="nombre">Mayor a 120 días</td>
</tr>
<?php $i=1; $check_id=1;foreach ($lists4 as $key=>$val): ?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>" height="32">
 <td width="10%"><?php echo $val->dias; //echo date("d-m-Y", strtotime($val->fecha_asignacion));?></td>
</tr>
<?php ++$i;endforeach;?>
</table>



</div><!--content_tabla-->
</div><!--tabla-listado-->
<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>
<?php endif;?>  
<?php // echo $this->pagination->create_links(); ?>