<table class="stable" width="100%">
  <?php echo form_open(site_url().'/admin/procurador/guardar_etapa/'.$id); ?>
  <tr><td colspan="4"><h3>Ingresar un nueva Etapa de Juicio:</h3><br></td></tr>
  <tr>
      <td>Etapa:</td>
      <td>
      <?php echo form_dropdown('id_etapa', $etapas_juicio, $this->input->post('id_etapa'),' class="select_dos" autocomplete="off" data-id="'.$id.'" ');?>
      <?php echo form_error('id_etapa','<br><span class="error">','</span>');?>
      </td>
      <td co>Fecha Etapa:</td><td><input type="text" class="datepicker" name="fecha_etapa" value="<?php echo $this->input->post('fecha_etapa')?>"><?php echo form_error('fecha_etapa','<br><span class="error">','</span>');?></td>
  </tr>
  <tr>
  
  <td>Observación:</td><td><?php echo form_input(array('name'=>'observaciones','style'=>'width:135px'), $this->input->post('observaciones'));?>
  <?php echo form_error('observaciones','<br><span class="error">','</span>');?></td>
  </tr>
  <tr><td colspan="4"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
  <?php echo form_close();?>
  <tr><td colspan="4"><hr style="border:1px solid #CDCCCC"><br><h3>Listado de Etapas de Juicio de la Cuenta:</h3><br></td></tr>
  <tr>
      <td colspan="4">
      <table class="stable grilla" width="100%">
          <tr class="titulos-tabla">
              <td>Fecha</td>
              <td>Etapa</td>
              <td>Observaciones</td>
              <td>Usuario</td>
              <td>Observaciones Administrador</td>
              <td>Administrador</td>
          </tr>
          <?php if (count($etapas_juicio_cuenta)>0):?>
          <?php foreach ($etapas_juicio_cuenta as $key=>$etapa_juicio_cuenta):?>
              <tr>
              <td><?php echo date('d-m-Y',strtotime($etapa_juicio_cuenta->fecha_etapa));?></td>
              <td><?php echo $etapa_juicio_cuenta->etapa;?></td>
              <td><?php echo $etapa_juicio_cuenta->observaciones;?></td>
              <td><?php echo $etapa_juicio_cuenta->procurador_nombres.' '.$etapa_juicio_cuenta->procurador_apellidos;?></td>
              <td><?php echo $etapa_juicio_cuenta->obs_administrador;?></td>
              <td><?php echo $etapa_juicio_cuenta->nombres.' '.$etapa_juicio_cuenta->apellidos;?></td>
              </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="7">No hay etapas ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>

<script type="text/javascript">
$(document).ready(function(){
$( '.select_dos' ).each(function( index ) {
		var obj = $(this);
		var id = obj.val();

	        $.ajax({
	            type: 'post',
	            url: '<?php echo site_url()?>/admin/procurador/dropdown_ajax_etapa/'+obj.val(),
	            data: '',
	            success: function (data) {
	            	obj.html( data );
	            },
	            error: function(objeto, quepaso, otroobj) {
	            },
	        });
	});
	
	$('.select_dos,.select_lista').change(function(){
		var idotro = $(this).attr('data-id');
		
		if( $(this).val() == 'otro' || $(this).val() == '72' )
		{
			$.ajax({
				type: 'post',
				url: '<?php echo site_url()?>/admin/procurador/dropdown_ajax_etapa_otro/',
				data: '',
				success: function (data) {
					$( '.otro_'+idotro ).css( 'display','block' );
					$( '.otro_'+idotro ).html( data );
				},
				error: function(objeto, quepaso, otroobj) {
				},
			});
		} else{
			$( '.otro_'+idotro ).val( '' );
			$( '.otro_'+idotro ).css( 'display','none' );
		}
		
		return false;
	});
			   
});
</script>