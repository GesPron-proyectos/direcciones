<?php  $rut_parcial = ''; if (isset($_REQUEST['rut_parcial'])){$rut_parcial = $_REQUEST['rut_parcial'];} ?>

<div class="table-m-sep">
  <div class="table-m-sep-title" style="height:auto;">
  
  <?php if($nodo->nombre == 'swcobranza') { ?>
  <h2><strong><?php echo $cuenta->nombres.' '.$cuenta->ap_pat.' '.$cuenta->ap_mat; ?>  <?php echo 'Rut :'; echo $cuenta->rut; ?>  <?php echo 'Rol:'; echo $cuenta->rol; ?>  <?php echo 'Tribunal:'; echo $cuenta->tribunal; ?> <?php echo 'Procurador:'; echo $cuenta->nombres_adm.''.$cuenta->apellidos; ?>   <?php echo 'Mandante:'; echo $cuenta->razon_social; ?>   <?php if($cuenta->monto_deuda != ''){ ?> <?php echo 'Monto:'; echo '$'.number_format($cuenta->monto_deuda,0,',','.'); echo '  + Intereses'; ?>  <?php } ?> <?php } ?>  
  </strong></h2>   
  
  
  <div class="agregar-noticia" style="float:right; padding:2px; margin-right:10px">
   <?php if($nodo->nombre=='swcobranza'){ ?>
<?php 
	echo form_open(site_url().'/admin/cuentas/',array('id' => 'form_reg'));
	
	echo '<div class="campo">';
	echo form_label('Rut parcial','rut_parcial');
	echo form_input('rut_parcial',$rut_parcial,'id="rut_parcial"');
	echo form_error('rut_parcial');
	echo '</div>';

    echo '<div class="campo">';
	echo form_label('&nbsp;','');
	echo form_submit(array('name' => 'buscar', 'class' => 'boton'), 'Buscar');
	echo '</div>';

	echo form_close();
?>
<?php  } ?>
<div class="clear"></div>
</div>
<div class="clear"></div>
     
  </div>
  
</div>
<style>
table.stable { border:1px solid #CDCCCC; background:#fff; margin:5px; padding:10px; display:block;}
table.stable td { font-size:11px; padding:2px; }
table.stable input, table.stable select { font-size: 11px; margin: 5px 5 5px 5px;}
table.grilla tr { border-bottom: 1px solid #cfcccc; }
span.error { font-size:10px; }
div.success { border:1px solid #74B71B; color:#74b71b; padding:10px; margin:10px; float:left; text-align:left; display:block; font-size:14px;}
table.table-destacado td { font-size:13px; padding:4px;}
</style>




<div class="tabla-listado">
    <div class="content_tabla">
   	  <div id="tabs">
          <ul>
          	<?php for ($i=1;$i<=$total_tabs;$i++):?>
            	 <?php foreach ($tabs as $key=>$utab): if ($utab==$i):?>
            	 <li><a href="#tabs<?php echo $i;?>-html"><?php echo $tabs_nombres[$key];?></a><li>
                 <?php endif; endforeach;?>
            <?php endfor;?>
          </ul>
          
          <?php for ($i=1;$i<=$total_tabs;$i++):?>
			 <?php foreach ($tabs as $key=>$utab): if ($utab==$i):?>
             <div id="tabs<?php echo $i;?>-html">
				<?php $this->load->view('backend/templates/gestion/gestion/'.$key); ?>
              </div>
             <?php endif; endforeach;?>
        <?php endfor;?>
          
		  
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	
	$(document).on("change","select[name='id_estado_cuenta']",function(e){
		
		if ($(this).val()==3){
			$('#suspension').show();
		} else {
			$('#suspension').hide();
		} 
	});
	$(".datepicker").datepicker({ format: 'dd-mm-yyyy',});
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
		   url: '<?php echo site_url()?>/admin/gestion/actualizar_estado/'+id+'/'+tipo,
		   data: 'estado='+value,
		   success: function (data) {
			  $("#response_"+tipo+"_"+id).html(data);
		   },
	   });
	});

    $(document).on("change",".tipo",function(e){
        var id = $(this).attr('id');
        var tipo = $(this).attr('data-td');
        var value = $(this).val();
        $.ajax({
            type: 'post',
            url: '<?php echo site_url()?>/admin/gestion/actualizar_tipo_direccion/'+id+'/'+tipo,
            data: 'tipo='+value,
            success: function (data) {
                $("#response_"+tipo+"_"+id).html(data);
            },
        });
    });


	$(document).on("change","#chk_estado_retencion",function(e){
		if ($(this).is(':checked')){
			var porc = $('#gasto_monto').val();
			$('#gasto_retencion').val(Math.round(porc*0.1));
		} else {
			$('#gasto_retencion').val(0);
		}
	});
	$(document).on("change","#diligencia_p",function(e){
		var id = $(this).val();
		var box = $(this);
	
		   $.ajax({
			   type: 'post',
			   url: '<?php echo site_url()?>/admin/cuentas/cal_diligencia/'+id+'/<?php echo $cuenta->id;?>',
			   data: $('#form_guardar-gastos').serialize(),
			   success: function (data) {
				   var json_obj = $.parseJSON(data);
				   
				   if(json_obj.status == 'exito'){
						$('#gasto_monto').val(json_obj.monto_gasto);
						if ($('input[name="estado_retencion"]').is(':checked')){
							$('#gasto_retencion').val(json_obj.retencion);
						} else {
							$('#gasto_retencion').val(0);
						}
				   }
			   },
			   error: function(objeto, quepaso, otroobj) {
			   },
		   });
	
		return false;
	});
	$(document).on("click",".delete",function(e){
		if (!confirm('¿Está seguro que desea eliminar este registro?')){
			return false;
		}
	});
	$(document).on("click",".close",function(e){
		var gtab = $(this).data('gtab');
		$("#box-form-"+gtab).html('');
		$(".stable tr").removeClass('current');
		return false;
	});
	$(document).on("click",".edit",function(e){
		tr = $(this).parent('td').parent('tr');
		tr.addClass('current');
		var id = $(this).data('id');
		var gtab = $(this).data('gtab');
		 $.ajax({
		   type: 'post',
		   url: '<?php echo site_url()?>/admin/gestion/loadform/<?php echo $id;?>/'+gtab+'/'+id,
		   success: function (data) {
			  $("#box-form-"+gtab).html(data);
		   },
	   });
	   return false;
	});
	$(document).on("change","#rol",function(e){
		var val = $(this).val();
		
		if( val.contains("-")){
			str = val.split("-");
			if( str[1].length == 4){
				$('#label_rol').css('color','#666666');
				$('#btn_submit_cuenta').show();
			}else{
				alert("ROL: Formato incorrecto. Ej(15487-2014)");
				$('#label_rol').css('color','#F00000');
				$('#btn_submit_cuenta').hide();
			}
		}else{
			if( val.length != 0){
				alert("ROL: Formato incorrecto. Ej(15487-2014)");
				$('#label_rol').css('color','#F00000');
				$('#btn_submit_cuenta').hide();
			}else{
				$('#label_rol').css('color','#666666');
				$('#btn_submit_cuenta').show();
			}
		}
		
	});
	$(document).on("change","select[name='tipo']",function(e){
		if ($(this).val()==1){
			$('tr.vehiculo').show();
		} else {
			$('tr.vehiculo').hide();
		}
	});
});
</script>