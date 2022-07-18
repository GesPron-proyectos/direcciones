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
 <td width="13%" class="nombre">Jurisdicción</td>
 <td width="13%" class="nombre">Herramienta</td>
</tr>
<?php $i=1; $check_id=1;
foreach ($lists as $key=>$val):?>
<tr id="tools_" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>">
<td><?php echo $val->jurisdiccion;?></td>
 <?php $row_current=array(); $row_current=$val;  include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
</tr>

<?php ++$i;endforeach;?>

</table>
<?php endif;?>