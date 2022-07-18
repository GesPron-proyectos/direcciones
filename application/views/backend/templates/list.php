<script type="text/javascript">
	$().ready(function() {
		$(".xtool").click(function(){		
			var idd=$(this).attr("id");
			var action=$(this).attr("rel");
			var iscall=$(this).attr("lang");
			var verif=false;
			if ((action=='<?php echo LANG_DISABLE;?>')||(action=='<?php echo LANG_ENABLE;?>')){
				if (action=='<?php echo LANG_DISABLE;?>'){if (confirm('¿Está seguro que desea eliminar este registro?')){verif=true;}}
				if (action=='<?php echo LANG_ENABLE;?>'){if (confirm('¿Está seguro que desea restaurar este registro?')){verif=true;}}
			}else{verif=true;}
			
			if (verif){
				$("tr#row-"+idd).addClass("c");
				$.ajax({
					type: 'get',
					url: '<?php echo PUBLIC_ADMIN_V.$controller;?>/gen/'+action+'/'+idd+'/?g=list&pag=<?php echo $_GET['pag'].$filter;?>',
					//data: 'id=<?php echo $id;?>&i=' + parent.attr('id').replace('img-',''),
					//beforeSend: function() { if (iscall==''){$("tr#row-"+idd).addClass('c',1,callback);}},
					success: function(data) {
						$('#content_tabla').html(data); 
						$("tr#row-"+idd).addClass('c',1,callback);
					}
				});	
			}
		});
		function callback(){setTimeout(function(){$('tr').removeClass('c',1000);}, 2500);}
		function callbackload(){
			$(".content").load('<?php echo PUBLIC_ADMIN_V.$controller;?>/<?php echo LANG_LIST;?>/?g=list&pag=<?php echo $_GET['pag'].$filter;?>',{orden:'<?php echo $_REQUEST['orden'];?>',ascdesc:'<?php echo $_REQUEST['ascdesc'];?>'});
		}
		$(".mtool").click(function(){
			var idd=$(this).attr("id");
			var action=$(this).attr("rel");
			var selectedItems = new Array();
			$("input[@name='chk-"+idd+"[]']:checked").each(function(){
				//alert($(this).parent().attr('id'));//selectedItems.push($(this).attr('id'));
				if ($(this).parent().attr('id')!=''){
				 selectedItems.push($(this).parent().attr('id'));
				}
			});//each
			var verif=false;
			if (action=='<?php echo LANG_DISABLE;?>'){
				if (confirm('¿Está seguro que desea eliminar este registro?')){verif=true;}
			}else{verif=true;}
			if (verif){
				for (i=0;i<selectedItems.length;i++){
					$("#not").load('<?php echo PUBLIC_ADMIN_V.$controller;?>/'+action+'/'+selectedItems[i]+'?nl=not',{},callbackload);
				}
			}
		});
		
		$("span.orden").click(function(){	
			var jorden=$(this).attr("id");
			var jascdesc=$(this).attr("title");
			$(".content").load('<?php echo PUBLIC_ADMIN_V.$controller;?>/<?php echo LANG_LIST;?>/?g=list&pag=<?php echo $_GET['pag'].$filter;?>',{orden:jorden,ascdesc:jascdesc});
		});
		
		$("input.ctd").click(function(){
			//alert($(this).parent('td').parent('tr').attr('id'));
			if ($(this).is(':checked')){$(this).parent('td').parent('tr').addClass('c');}
			else{$(this).parent('td').parent('tr').removeClass('c');}
		});
		
		$("input.all").click(function(){
			var id=$(this).attr("name");
			id=id.replace('all-','chk-');
			$("input[name='"+id+"']").attr("checked",$(this).is(":checked"));
			
			if ($(this).is(":checked")){$(this).parent().children("span").html('Deseleccionar Todos'); $("input[name='"+id+"']").parent("td").parent("tr").addClass("c");}
			else{$(this).parent().children("span").html('Seleccionar Todos'); $("input[name='"+id+"']").parent("td").parent("tr").removeClass("c");}
		});
		//function callback(){$('tr').removeClass('c',5000);}

	});//#D2EB7C
</script>
        <div class="clear"></div>
        <div class="m-sec-title" style="margin-top:15px;">
        <span class="ico-big-news"></span>
        <div class="table-m-sep-title">
          <h1>Gestor de <?php echo $controller_nombre;?> <b>(<?php echo $total;?>)</b></h1>
          <div class="breadcrumbs">Administrador > <?php echo $controller_nombre;?> > Listado</div>
        </div>
        <?php if ($controller_nuevo!=''):?>
        <div class="agregar-noticia">
            <div class="agregar">
               <a href="<?php echo PUBLIC_ADMIN_V.$controller.'/'.LANG_NEW;?>" class="nueva"><?php echo $controller_nuevo;?></a>
            </div><!--agregar-->
            <div class="campo">
              <?php $tpl3=DIR_MAIN_ADMIN_MOD.$controller.'/filtros.php'; if (is_file($tpl3)){include $tpl3;}?>
            </div><!--campo-->
        </div><!--agregar-noticia-->   
        <?php endif;?>
        <div class="clear"></div>            
        </div>
        <?php $tpl2=DIR_MAIN_ADMIN_MOD.$controller.'/'.$view.'.php'; if (is_file($tpl2)){include $tpl2;}?>        	
        <!---->     
		<div class="clear"></div>
        <?php if (($controller!='pedidos')and($controller!='log')):?>
        <div class="table-m-sep-tools" style="float:left; margin-top:-9px;">
            <span class="editar">Editar</span>
            <span class="subir">Subir</span>
            <span class="bajar">Bajar</span>
            <span class="publicado">Publicado</span>
            <span class="despublicado">Despublicado</span>
            <span class="eliminar">Eliminar</span>
        </div>
		<div class="clear"></div>   
        <?php endif;?> 
