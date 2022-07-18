<script type="text/javascript">
	$().ready(function() {
		$(".xtool").click(function(){
			var idd=$(this).attr("id");
			var campo=$(this).attr("title");
			var value=$(this).attr("rel");

			if (confirm("¿Está seguro que desea eliminar este registro?") == true) {
				$("tr#row-"+idd).addClass("c");
				$.ajax({
					type: 'post',
					url: '<?php echo site_url();?>/admin/<?php echo $current;?>/delete/'+idd,
					data: campo+'='+value,
					beforeSend: function() { 
						$('#tools_k'+idd).html('<img src="<?php echo base_url();?>images/ajax-loader.gif">');
					},
					success: function(data) {
						
						$('#tools_'+idd).fadeOut('slow', function(){
							$('#tools_'+idd).remove();
						});
			
						
					},
					complete: function(data){
						setTimeout(function(){
								clase = 'a';
								$('.tr').each( function() {
									$(this).removeClass('a');
									$(this).removeClass('b');
		
									if(clase == 'a'){
										$(this).addClass('a');
										clase = 'b';
									}else{
										$(this).addClass('b');
										clase = 'a';
									}
								});
						},1000);
					}
				});	
			}
		});
	});
</script>


<style>
table.listado tr,table.listado input, table.listado select {
    font-size: 11px;
    margin: 5px 0 5px 5px;
	line-height: 26px !important;
}
</style>
<?php if (count($lists)>0): ?>


<?php /*echo '<pre>'; print_r($lists); echo '</pre>';*/?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  
  <td width="16%" class="nombre">Rango1</td>
  <td width="16%" class="nombre">Rango2</td>
  <td width="16%" class="nombre">Monto Gasto</td>
  <td width="16%" class="nombre">Mandante</td>
  <td width="16%" class="nombre">Diligencia</td>
  <td class="herramientas" width="9%">Herramientas</td> 

</tr>
<?php $i=1; $check_id=1;
foreach ($lists as $key=>$val):?>

<tr id="tools_<?php echo $val->gasto_regla_id?>" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>" id="row-<?php echo $val->gasto_regla_id;?>">

	<td>$ <?php echo number_format( $val->gasto_regla_rango1 , 0, '','.')?></td>
	<td>$ <?php echo number_format( $val->gasto_regla_rango2 , 0, '','.')?></td>
	<td>$ <?php echo number_format( $val->gasto_regla_monto_gasto , 0, '','.')?></td>
	<td><?php echo $val->mandantes_codigo_mandante?></td>
	<td><?php echo $val->diligencia_nombre?></td>
	 
	<td class="tools" width="100%" id="tools_k<?php echo $val->gasto_regla_id?>">
	<table width="75%" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
		  		<td width="15%" height="20">
		  			<a title="editar" class="editar" href="<?php echo site_url()?>/admin/<?php echo $current?>/form/editar/<?php echo $val->gasto_regla_id?>"></a>
		  		</td>
		  		<td width="15%" height="20">
		  			<a id="<?php echo $val->gasto_regla_id?>" class="eliminar xtool" title="activo" rel="N" style="cursor:pointer;"></a>
		  		</td>
		  	</tr>
	  	</tbody>
  	</table>
  </td>
  <?php $row_current=array(); $row_current=$val; //include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
</tr>

<?php ++$i;endforeach;?>

</table>
<?php endif;?>