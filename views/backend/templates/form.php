<script type="text/javascript">
$(document).ready(function(){ 
	$(".flechita").click(function(){
		var bq=$(this).parent().next(".blq");
		if (bq.is(":visible")){bq.slideUp(); $(this).attr("class","flechita-down");}
		else{bq.slideDown(); $(this).attr("class","flechita");}
	});
});
</script>
<div class="date" style="padding-top:5px; line-height:25px;">
<?php if ($action==LANG_SAVE):?>
<div class="max" style="float:left;  margin-right:10px;"><span class="max"></span>Guardado <?php echo $lastsave;?></div>
<?php endif;?>
<?php echo ' '.$save;?></div>
<div class="clear"></div>
<div class="m-sec-title" style="margin-top:7px;">
	<span class="ico-big-news"></span>
    <div class="table-m-sep-title">
       <h1 style="display:inline;"><strong><?php if ((LANG_EDIT==$action)or(LANG_SAVE==$action)):?>Editar <?php else:?>Creación de <?php endif;?><?php echo $controller_nombre;?></strong></h1> 
       <div class="breadcrumbs">Administrador > <?php echo $controller_nombre;?> > <?php if ((LANG_EDIT==$action)or(LANG_SAVE==$action)){ echo $controller_editar;}else{echo $controller_nuevo;}?></div>
     </div>
</div><!--m-sec-title-->
	<?php $tpl2=DIR_MAIN_ADMIN_MOD.$controller.'/'.$view.'.php'; if (is_file($tpl2)){include $tpl2;}?>
<!--content-->
<div class="clear"></div>
<!--footer-->
  