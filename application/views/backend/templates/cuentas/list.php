<style>
#mask{position:absolute;background:rgba(0,0,0,.3);display:none;height:100%;width:100%;left:0;top:0;z-index:999998}
.preloader {
  width: 90px;
  height: 90px;
  margin: 25% auto;
  border: 10px solid #eee;
  border-top: 10px solid #666;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 1s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
#selector{margin-left:-8px;}
#pjud1, #pjud2{width:20px;}
</style>
<div id="mask"><div class="preloader"></div></div>
<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Causas (<?php echo number_format($total,0,',','.');?>)</strong></h2>
  </div>
</div>
<div class="agregar-noticia" style="min-height:200px;">
	<form id="form_pjud" method="POST" >
	
		
			
		
			  <div width="20%" class="agregar">
				<a class="nueva" href="<?php echo site_url();?>/admin/importar/cargar_excel/">IMPORTAR EXCEL</a>
			  </div>
			
			
	
		<br/>
		
		<div class="clear">

		</div>
		<input type="hidden" id="site_url" value="<?php echo site_url();?>">
	</form>
</div>

<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">
	<div class="content_tabla">
		<?php include APPPATH.'views/backend/templates/cuentas/list_tabla.php';?>
	</div>
</div>
<?php endif;?>  
<?php echo $this->pagination->create_links(); ?>
<script type="text/javascript">
$(document).ready(function(){
	
	var url = window.location.href;
	var hash = url.split('#')[1];

	if (hash == 'pjud=2'){
		$("#pjud1").attr({'checked': false});
		$("#pjud2").attr({'checked': true});
		$("#pjud_old").css({'display': 'block'});
		$("#pjud_new").css({'display': 'none'});
	}
	else {
		$("#pjud1").attr({'checked': true});
		$("#pjud2").attr({'checked': false});
		$("#pjud_old").css({'display': 'none'});
		$("#pjud_new").css({'display': 'block'});
	}

	$("#pjud1").click(function(e){
		$("#pjud_old").css({'display': 'none'});
		$("#pjud_new").css({'display': 'block'});
		window.location.hash = "pjud=1";
	});
	
	$("#pjud2").click(function(e){
		$("#pjud_old").css({'display': 'block'});
		$("#pjud_new").css({'display': 'none'});
		window.location.hash = "pjud=2";
	});
	
	// Al entrar, marcar el check viejo
	var $radios = $('input:radio[name=pjud]');
    $radios.filter('[value=2]').prop('checked', true);
	$("#pjud_old").css({'display': 'block'});
	$("#pjud_new").css({'display': 'none'});
	window.location.hash = "pjud=2";


	



}); 

contador = 0;

	
</script>