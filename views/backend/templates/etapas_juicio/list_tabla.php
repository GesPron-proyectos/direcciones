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


<?php //echo '<pre>'; print_r($lists); echo '</pre>';?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  
  <td class="nombre">Etapa</td>
  <td class="nombre">Codigo</td>
  <td class="nombre">Sucesor</td>
  <td class="nombre">Dias Alerta</td>
   <td class="nombre">Tipo</td>
   <td class="nombre">Posición</td>
  <!-- <td class="nombre">Clasificación</td>  -->
  
  <td class="herramientas" width="9%">Herramientas</td> 

</tr>
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>

<tr id="tools_<?php echo $val->id?>" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>" id="row-<?php echo $val->id;?>">

	<td><?php echo $val->etapa;?></td>
	<td><?php echo $val->codigo;?></td>
	<td><?php echo $val->sucesor;?></td>
	<td><?php echo $val->dias_alerta;?></td>
    <td><?php echo $val->tipo;?></td>
    <td><?php echo $val->posicion;?></td>
	<!-- <td><?php echo $val->clasificacion;?></td>  -->
 
	<td class="tools" width="100%" id="tools_k<?php echo $val->id?>">
	<?php if( $this->session->userdata("usuario_perfil") != 3 ):?>
	<table width="75%" cellspacing="0" cellpadding="0" border="0">
		<tbody>
			<tr>
		  		<td width="15%" height="20">
		  			<a title="editar" class="editar" href="<?php echo site_url()?>/admin/<?php echo $current?>/form/editar/<?php echo $val->id?>"></a>
		  		</td>
		  		<td width="15%" height="20">
		  			<a id="<?php echo $val->id?>" class="eliminar xtool" title="activo" rel="N" style="cursor:pointer;"></a>
		  		</td>
		  	</tr>
	  	</tbody>
  	</table>
  	<?php endif;?>
  </td>
  <?php $row_current=array(); $row_current=$val; //include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
</tr>

<?php ++$i;endforeach;?>

</table>
<?php endif;?>