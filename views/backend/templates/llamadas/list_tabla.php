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
		$(document).on('submit','.formendcall', function(e){
	     if (confirm("¿Está seguro de marcar esta llamada con finalizada?") == true) {		
			 var form = $(this);
			 var tr = form.parent('td').parent('tr');
			 $.ajax({
				type: form.attr('method'),
				url:  form.attr('action'),
				data: form.serialize(),
				success: function(data) {
					tr.remove();
				},
			 });
		 }
		 return false
	   });
	   $(document).on('click','.telefono-act-estado', function(e){
		   if (confirm('¿Está seguro de realizar esta acción?')){
			   var id_telefono = $(this).attr('rel');
			   var estado = $(this).data('estado');
			   $.ajax({
					type: 'post',
					url: '<?php echo site_url();?>/admin/<?php echo $current;?>/actualizar_estado/'+id_telefono,
					data: 'estado='+estado,
					beforeSend: function() { 
						$('#box_'+id_telefono).html('<img src="<?php echo base_url();?>images/ajax-loader.gif">');
					},
					success: function(data) {
						
						$('#box_'+id_telefono).html(data);	
					},
			   });
		   }
		   return false;
	   });
	   
	   $(".datepicker").datepicker({ format: 'dd-mm-yyyy',});
	});
</script>


<style>
table.listado tr,table.listado input, table.listado select {
    font-size: 11px;
    margin: 5px 0 5px 5px;
	line-height: 12px !important;
}
</style>

<?php if (count($lists)>0): ?>


<?php /*echo '<pre>'; print_r($lists); echo '</pre>';*/?>

<table class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">

  
  <td class="nombre">Mandante</td>
  <td class="nombre">ID</td>
  <td class="nombre">ID llamadas</td>

  <td class="nombre">Deudor Rut</td>
  <td class="nombre">Deudor Nombre</td>
  <td class="nombre">Día convenio</td>
  <td class="nombre">Últ. pago</td>
  <td class="nombre">Últ. llamada</td>
  <td class="nombre"># Llamadas</td>
  <td class="nombre" width="250">Historial (últimos 5)</td>
  <td class="nombre" width="250">Teléfonos</td>
    <td class="nombre" width="250"> Observación </td>
  <td class="nombre" width="250">Estados</td>
  <td>&nbsp;</td>

</tr>
<?php $i=1; $check_id=1; $tipos = array('1'=>'Particular','2'=>'Comercial','3'=>'Celular','4'=>'Otro'); $estados = array('0'=>'Sin confirmación','1'=>'Vigente');



foreach ($lists as $key=>$val): ?>

<tr id="tools_" class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}?>">


	<td><?php echo $val->codigo_mandante;?></td>
    <td><?php echo $val->id_cuenta;?></td>
    <td><?php echo $val->llamadas_cuenta_id;?></td>

    <td> <a href="<?php echo site_url()?>/admin/gestion/index/<?php echo $val->cuentas_id ?>" style="" class=""><?php echo $val->usuarios_rut;?></a></td>
  <td><a href="<?php echo site_url()?>/admin/gestion/index/<?php echo $val->cuentas_id ?>" style="" class=""><?php echo $val->usuarios_nombres;?> <?php echo $val->usuarios_ap_pat;?> <?php echo $val->usuarios_ap_mat;?></a></td>
    <td><?php echo $val->cuentas_dia_vencimiento_cuota?></td>
	<td><?php if ($val->fecha_pago!='0000-00-00' && $val->fecha_pago!='' && $val->fecha_pago!='1969-12-31'){echo date('d-m-Y',strtotime($val->fecha_pago));}?>
    <?php if ($val->dias_diferencia>0):?> (hace <?php echo $val->dias_diferencia;?>) día(s)<?php endif;?></td>
    <td><?php if ($val->fecha_ultima_llamada!=''){echo date('d-m-Y',strtotime($val->fecha_ultima_llamada));}?></td>
    <td><?php echo $val->llamadas;?></td>
    <td style="font-size:11px;">
    <?php 
		$this->db->order_by('fecha desc'); $this->db->limit(5); $historial = $this->cuentas_historial_m->get_many_by(array('id_cuenta'=>$val->cuentas_id,'observaciones !='=>'','activo'=>'S'));
		if (count($historial)>0){
		foreach ($historial as $h){
			echo ''.date('d-m-Y H:i',strtotime($h->fecha)).' - '.$h->observaciones.'<br>';
		}
		echo '<a href="'.site_url().'/admin/gestion/index/'.$val->cuentas_id.'" style="" class=""><< ver todas</a>';
		}
	?>
     </td>

    <td>
    <?php $telefonos = $this->telefono_m->get_many_by(array('id_cuenta'=>$val->cuentas_id,'estado !='=>2));
	if (count($telefonos)>0){ 
		foreach ($telefonos as $k=>$telefono){
			echo '<div id="box_'.$telefono->id.'">';
			if ($telefono->estado==0){echo '<a href="#" class="img-check telefono-act-estado" rel="'.$telefono->id.'" data-estado="1"><img src="'.base_url().'img/ico-uncheked.png"></a>';}
			if ($telefono->estado==1){echo '<a href="#" class="img-check telefono-act-estado" rel="'.$telefono->id.'" data-estado="2"><img src="'.base_url().'img/ico-cheked-no.png"></a>';}
			if ($telefono->estado==0){echo '<a href="#" class="img-check telefono-act-estado" rel="'.$telefono->id.'" data-estado="2"><img src="'.base_url().'img/eliminar.jpg"></a>';}
			$style='';if ($telefono->estado==1){$style = ' style="color:#7FBA00"';}
			echo '<div class="phone-box">';
			echo '<span'.$style.'>'.$telefono->numero.'</span> ('.$tipos[$telefono->tipo].')'; 
			echo '</div><div class="clear"></div></div>';
		}
	}
	?>
     </td>

      <td>
        <form method="post" action="<?php echo site_url('admin/llamadas/historial/'.$val->llamadas_cuenta_id);?>"> <!-- class="formendca" -->
            <input type="text" value="" name="obs" style="width:120px" />
            <input type="hidden" name="id_cuenta" value= <?php print $val->id_cuenta; ?>>
            <input type="submit"value="Guardar" />
        </form>
    </td>



    <td>
         <form action="<?php echo site_url().'/admin/llamadas/form_llamada/'.$val->llamadas_cuenta_id;?>"method="post">
     <?php echo form_dropdown('tipo_llamada', $tipo_llamadas, $this->input->post('tipo_llamada'));?>
             <input type="hidden" name="id_cuenta" value= <?php print $val->id_cuenta; ?>>
     <?php echo form_error('tipo_llamada','<br><span class="error">','</span>');?>
    <input type="submit" value="Guardar" name="Guardar" class="boton" style="width:99%; float:left;">
     <?php echo form_close(); ?>
    </td>




<?php ++$i;endforeach;?>

</table>
<?php endif;?>