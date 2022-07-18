<div class="table-m-sep">
  <div class="table-m-sep-title">
  <h2><strong>Detalle de la cuenta</strong></h2>
  </div>
</div>
<style>
table.stable { border:1px solid #CDCCCC; background:#fff; margin:5px; padding:10px; display:block;}
table.stable td { font-size:11px; padding:2px; }
table.stable input, table.stable select { font-size: 11px; margin: 5px 5 5px 5px;}
table.grilla tr { border-bottom: 1px solid #cfcccc; }
span.error { font-size:10px; }
div.success { border:1px solid #74B71B; color:#74b71b; padding:10px; margin:10px; float:left; text-align:left; display:block; font-size:14px;}

</style>
<div class="tabla-listado">
    <div class="content_tabla">
   	  <div id="tabs">
          <ul>
            <li><a href="#tabs1-html">Datos de la cuenta</a><li>
            <li><a href="#tabs2-html">Historial</a><li>
            <li><a href="#tabs3-html">Teléfonos</a><li>
            <li><a href="#tabs4-html">Direcciones</a><li>
            <li><a href="#tabs5-html">Bienes</a><li>
            <li><a href="#tabs6-html">Gastos</a><li>
            <li><a href="#tabs7-html">Documentos</a><li>
            <li><a href="#tabs8-html">Etapas de Juicio</a><li>
            <li><a href="#tabs9-html">Pagarés</a><li>
          </ul>
		  <div id="tabs1-html">
		  	<?php if ($this->session->userdata('success_cuenta')):?>
            <div class="success"><?php echo $this->session->userdata('success_cuenta');?></div><div class="clear"></div><?php endif;?><?php $this->load->view('backend/templates/procurador/gestion/datos_cuenta'); ?>
          </div>
          <div id="tabs2-html">
          	<?php if ($this->session->userdata('success_historial')):?><div class="success"><?php echo $this->session->userdata('success_historial');?></div><div class="clear"></div><?php endif;?>
		  	<?php $this->load->view('backend/templates/procurador/gestion/historial'); ?>
          </div>
          <div id="tabs3-html">
          	<?php if ($this->session->userdata('success_telefono')):?><div class="success"><?php echo $this->session->userdata('success_telefono');?></div><div class="clear"></div><?php endif;?>
		  	<?php $this->load->view('backend/templates/procurador/gestion/telefono'); ?>
          </div>
          <div id="tabs4-html">
          	<?php if ($this->session->userdata('success_direccion')):?><div class="success"><?php echo $this->session->userdata('success_direccion');?></div><div class="clear"></div><?php endif;?>
		  	<?php $this->load->view('backend/templates/procurador/gestion/direccion'); ?>
          </div>
          <div id="tabs5-html">
          	<?php if ($this->session->userdata('success_bien')):?><div class="success"><?php echo $this->session->userdata('success_bien');?></div><div class="clear"></div><?php endif;?>
		  	<?php $this->load->view('backend/templates/procurador/gestion/bien'); ?>
          </div>
          <div id="tabs6-html">
          	<?php if ($this->session->userdata('success_gasto')):?><div class="success"><?php echo $this->session->userdata('success_gasto');?></div><div class="clear"></div><?php endif;?>
		  	<?php $this->load->view('backend/templates/procurador/gestion/gastos'); ?>
          </div>
          <div id="tabs7-html">
          	<?php if ($this->session->userdata('success_documento')):?><div class="success"><?php echo $this->session->userdata('success_documento');?></div><div class="clear"></div><?php endif;?>
		  	<?php $this->load->view('backend/templates/procurador/gestion/documentos'); ?>
          </div>
          <div id="tabs8-html">
		  	<?php if ($this->session->userdata('success_etapa_juicio')):?>
            <div class="success"><?php echo $this->session->userdata('success_etapa_juicio');?></div><div class="clear"></div><?php endif;?><?php $this->load->view('backend/templates/procurador/gestion/etapa_juicio'); ?>
          </div>
          <div id="tabs9-html">
		  	<?php if ($this->session->userdata('success_pagare')):?>
            <div class="success"><?php echo $this->session->userdata('success_pagare');?></div><div class="clear"></div><?php endif;?><?php $this->load->view('backend/templates/procurador/gestion/pagare'); ?>
          </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$(".datepicker").datepicker();
	$("#tabs").tabs({active:<?php echo $tab-1;?>});
	$(document).on("change",".selector_tres",function(e){
		$.ajax({
			type: 'post',
			url: '<?php echo base_url()?>index.php/admin/cuentas/get_diligencia/'+$(this).val(),
			data: '',
			success: function (data) {
				$(".selector_cuatro_box").show();
				$(".selector_cuatro").html(data);
			},
			error: function(objeto, quepaso, otroobj) {
			},
		});
	});
	$(document).on("change",".estado",function(e){
		 var id = $(this).attr('id');
		 var tipo = $(this).attr('data-tipo');
		 var value = $(this).val();
		 $.ajax({
		   type: 'post',
		   url: '<?php echo site_url()?>/admin/procurador/actualizar_estado/'+id+'/'+tipo,
		   data: 'estado='+value,
		   success: function (data) {
			  $("#response_"+tipo+"_"+id).html(data);
		   },
	   });
	});
	$(document).on("change","#diligencia_p",function(e){
		var id = $(this).val();
		var box = $(this);
	
		   $.ajax({
			   type: 'post',
			   url: '<?php echo site_url()?>/admin/cuentas/cal_diligencia/'+id,
			   data: $('#form_guardar-gastos').serialize(),
			   success: function (data) {
				   var json_obj = $.parseJSON(data);
				   
				   if(json_obj.status == 'exito'){
						$('#monto_diligencia_p').val(json_obj.monto_gasto);
						$('#retencion').val(json_obj.retencion);
				   }
			   },
			   error: function(objeto, quepaso, otroobj) {
			   },
		   });
	
		return false;
	});
});
</script>