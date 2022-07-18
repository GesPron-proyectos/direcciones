<script type="text/javascript">
	$().ready(function() {
		$(".xtool").click(function(){
			var idd=$(this).attr("id");
			var campo=$(this).attr("title");
			var value=$(this).attr("rel");
			var verif=false;
			if (campo=='activo'){
				if (value=='N'){if (confirm('¿Está seguro que desea eliminar este registro?')){verif=true;}}
			}else{verif=true;}
			if (verif){
				$("tr#row-"+idd).addClass("c");
				$.ajax({
					type: 'post',
					url: '<?php echo site_url();?>/admin/<?php echo $current;?>/gen/'+idd+'<?php echo '/'.$this->data['current_pag'];?>',
					data: campo+'='+value,
					beforeSend: function() { $('.content_tabla').html('<img src="<?php echo base_url();?>images/ajax-loader.gif">');},
					success: function(data) {
						$('.content_tabla').html(data); 
					},
					complete: function(data){$("tr#row-"+idd).addClass('c',1,callback);}
				});	
			}
			function callback(){setTimeout(function(){$('tr').removeClass('c',1000);}, 2500);}
		});
	});
</script>