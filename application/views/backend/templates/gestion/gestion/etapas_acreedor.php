<?php $this->load->view('backend/templates/gestion/gestion/etapas_acreedor_form'); ?>

<table class="stable" width="100%"> 
  <div class="box-form-etapas_juicio"> </div>
  <tr><td><h3>Listado de Etapas de Acreedor:</h3><br></td></tr>
  <tr>
    <td>
      <table id="tbetapas" class="stable grilla" width="100%">
        <thead>
          <tr class="titulos-tabla">
            <th width="30px">#</th>
            <th width="110px">Fecha</th>
            <th width="100px">Etapa</th>
            <th width="100px">Etapa Objetado</th>
            <th width="100px">Usuario</th>
            <th width="150px">Observaciones</th>
            <th width="100px">Administrador</th>
            <th width="150px">Observaciones Administrador</th>
            <th width="110px">Fecha Gestión</th>
            <th width="80px">Gestión</th>
          </tr>
          </thead>
          <tbody>
		  <?php /*print_r($etapas_juicio_cuenta);*/ ?>
		  
		  <?php if (count($etapas_acreedor_cuenta2)>0):?>
          <?php $fecha_anterior = ''; ?>
          <?php foreach ($etapas_acreedor_cuenta2 as $key=>$etapa_acreedor_cuenta):?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td><?php echo date('d-m-Y H:i:s',strtotime($etapa_acreedor_cuenta->fecha_etapa));?></td>
              <td><?php echo $etapa_acreedor_cuenta->etapa;?></td>
              <td><?php echo $etapa_acreedor_cuenta->etapa2;?></td>
              <td><?php echo $etapa_acreedor_cuenta->procurador_nombres.' '.$etapa_acreedor_cuenta->procurador_apellidos;?></td>
              <td><?php echo $etapa_acreedor_cuenta->observaciones;?></td>
              <td><?php echo $etapa_acreedor_cuenta->nombres.' '.$etapa_acreedor_cuenta->apellidos;?></td>
              <td><?php echo $etapa_acreedor_cuenta->obs_administrador;?></td>
              <td><?php echo date('d-m-Y H:i:s',strtotime($etapa_acreedor_cuenta->fecha_crea));?></td>
              <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
              <td>
                <a href="#" class="edit_etapa_acr" style="cursor:pointer;" data-id="<?php echo $etapa_acreedor_cuenta->id?>" data-gtab="etapa_acreedor">Editar</a>
                <a href="<?php echo site_url('admin/gestion/eliminar_etapa_acreedor/'.$id.'/'.$id_acreedor.'/'.$etapa_acreedor_cuenta->id);?>" class="delete">Eliminar</a>
              </td>
              <?php endif;?>
            </tr>
            <?php  if($fecha_anterior != $etapa_acreedor_cuenta->fecha_etapa){
					     $fecha_anterior = $etapa_acreedor_cuenta->fecha_etapa; 
					   }?>
          <?php endforeach;?>
          <?php endif;?>
          </tbody>
      </table>
    </td>
  </tr>
</table>

<?php if($nodo->nombre=='fullpay'): ?> 
<table class="stable grilla" width="100%">
          <tr class="titulos-tabla">           
             <td>Fecha Gestión</td>
             <td>días transcurridos</td>  
             <td>Alerta al día</td> 
             <td> Días Atrasos </td>  
             <td>Etapa</td>
             <td>Fecha Asignación</td> 
             <td>Días transcurridos cuenta</td> 
             <?php if ($this->session->userdata("usuario_perfil")==1):?>
             <td>Gestión</td>
             <?php endif;?>
          </tr>
          
		  <?php /*print_r($etapas_juicio_cuenta);*/ ?>
		  
		  <?php if (count($etapas_acreedor_cuenta2)>0):?>
          <?php $fecha_anterior = date('Y-m-d H:i:s'); ?>
          <?php $dia_alerta =''; ?>
          <?php $dias_retraso =''; ?>
          <?php $dias_retrasos = '' ?>
          <?php  $suma_intervalo_limpio = 0; ?>
          <?php  $suma_intervalo_proceso_limpio = 0; ?> 
          <?php  $suma_dias_atrasos = 0; ?> 
		  
		  
		  <?php foreach ($etapas_acreedor_cuenta2 as $key=>$etapa_acreedor_cuenta):?>
             
             <?php  $dia_alerta = $etapa_acreedor_cuenta->dias_alerta;?>
             <?php $intervalo = date_diff(date_create($fecha_anterior),date_create($etapa_acreedor_cuenta->fecha_etapa));  ?>
			 <!--  diferencia entre la creación de la cuenta y la fecha de la etapa  -->
			 <?php $intervalo_proceso = date_diff(date_create($etapa_acreedor_cuenta->fecha_asignacion),date_create($etapa_acreedor_cuenta->fecha_etapa));  ?>	
			 <?php  $intervalo_limpio_proceso = ''; ?>
            <?php  $intervalo_limpio_proceso =  $intervalo_proceso->format('%R%a días')*(-1); ?> 
             <?php  $suma_intervalo_proceso_limpio = $suma_intervalo_proceso_limpio + $intervalo_limpio_proceso; ?> 	
             <?php  $intervalo_limpio = ''; ?>
            
			<?php $c = 0; ?>
            <?php $c = $intervalo->format('%R%a días'); ?>
            
            <?php if($c < 0){
             $intervalo_limpio =  $c *(-1); } 
             elseif($c > 0){ $intervalo_limpio = $c *(1);  }?>
			 
			 <?php  $suma_intervalo_limpio = $suma_intervalo_limpio + $intervalo_limpio; ?> 
             <?php //$dias_retraso = $dia_alerta - $intervalo_limpio; ?> 
             
             <?php if($dia_alerta == $intervalo_limpio || $dia_alerta == 0 || $dia_alerta > $intervalo_limpio){  
                	$dias_retrasos = 0;
              		}elseif($dia_alerta < $intervalo_limpio ){
				  $dias_retrasos = $intervalo_limpio - $dia_alerta;
					} ?>
              
              <?php $suma_dias_atrasos = $suma_dias_atrasos + $dias_retrasos ?>
              
               <tr>

              <td><?php echo date('d-m-Y H:i:s',strtotime($etapa_acreedor_cuenta->fecha_etapa));?></td>
             
             <?php $d = 0; ?>
             <?php $d = $intervalo->format('%R%a días'); ?>
             <td>  <?php if ($d < 0){ ?> <?php  echo $d*(-1); } elseif($d > 0){  echo $d*(1);} elseif($d == 0){ echo '-'; } ?></td> 
             
             <td><?php if($etapa_acreedor_cuenta->dias_alerta > 0) { echo $etapa_acreedor_cuenta->dias_alerta; } elseif($etapa_acreedor_cuenta->dias_alerta ==0 ){ echo '-'; }?></td>
             <td> <?php   echo $dias_retrasos;  ?> </td>
               
                
                <td><?php echo $etapa_acreedor_cuenta->etapa;?></td>
                 <?php if ($this->session->userdata("usuario_perfil")==1):?>
                
              
                 <td><?php echo date('d-m-Y',strtotime($etapa_acreedor_cuenta->fecha_asignacion));?></td>
             <?php $c = 0; ?>
             <?php $c =  $intervalo_proceso->format('%R%a días') *(1); ?>
             
             <td> <?php if($c >0){ ?> <?php echo $c; }elseif($c < 0){ echo '-'; }?> </td> 
              
              
              <td> <a href="<?php echo site_url('admin/gestion/editar_etapa_acreedor/'.$id.'/'.$etapa_acreedor_cuenta->id);?>"   class="edit"  data-id="<?php echo $etapa_acreedor_cuenta->id;?>" data-gtab="etapas_juicio" >Editar</a>
                <a href="<?php echo site_url('admin/gestion/eliminar_etapa_juicio/'.$id.'/'.$etapa_acreedor_cuenta->id);?>" class="delete">Eliminar</a></td>
                <?php endif;?> 
             
             
             
              </tr>
              <?php  if($fecha_anterior != $etapa_acreedor_cuenta->fecha_etapa){
					 $fecha_anterior = $etapa_acreedor_cuenta->fecha_etapa; 
					}?>
          <?php endforeach;?>
          <tr>
          <td><?php echo 'Total'; ?></td>
          <td> <?php echo $suma_intervalo_limpio; ?> </td>
		   <td>&nbsp;</td>
          <td> <?php echo $suma_dias_atrasos ?> </td>
		  </tr>
		  <?php else:?>
          <tr><td colspan="7">No hay etapas ingresados</td></tr>
          <?php endif;?>
      	</table>

	<?php endif;  ?>


<table class="stable" width="100%">
	<tr class="titulos-tabla" height="23">
    	<?php if (count($log_etapas)>0):?>
        <td colspan="5">
        <div style="float:right">
        <?php 
			$rango = '';if (isset($_REQUEST['rango'])){$rango = $_REQUEST['rango'];}
			echo form_open(site_url().'/admin/cuentas/exportar_log/'.$id); 
			echo form_label('Rango de fechas: ', 'rango',array('style'=>'width:150px; font-size:12px'));
			echo form_input('rango', $rango,'id="rango" style="width:180px"');
			echo form_error('rango');
			echo form_submit(array('name' => 'Exportar', 'class' => 'boton','style'=>'width:100px'), 'Exportar a CSV');
			echo form_close();
		?>
        </div>
        </td>
        <?php endif;?>
    </tr>
    <tr class="titulos-tabla" height="23">
		<td>Usuario</td>
	    <td>Acción</td>
	    <td>Etapa Anterior</td>
	    <td>Etapa Nueva</td>
	    <td>Fecha Registro</td>
	</tr>
	<?php foreach ($log_etapas as $key=>$val):?>
		<tr class="detalle-tabla" height="23">
			<td><?php echo $val->administradores_nombres;?></td>
	        <td><?php echo $val->operacion;?></td>
	        <td><?php echo $val->s_etapas_etapa;?></td>
	        <td><?php echo $val->etapa_nueva;?></td>
	        <td><?php echo date("d-m-Y H:i:s",strtotime($val->fecha) );?></td>
	    </tr>
	<?php endforeach;?>
</table>


<script type="text/javascript">
$(document).ready(function(){

  jQuery('#id_acreedor_s').change(function(){
      var idacr = jQuery(this).val();
      $("#tbetapas tbody").empty();
      var actionData = { 'id_acreedor': idacr };
      $.ajax({
        type: "POST",
        dataType: 'json',
        data: actionData,
        url: "<?php echo base_url()?>index.php/admin/gestion/buscarlist",
        success: function(response){
          i=1;
          $.each(response.result, function(id, value){
              //console.log(value.id);
              var cl = ' class="a">';
              if (i%2==0) cl = ' class="b">';
              var tr = '<tr id="row-'+value.id+'"'+cl+
                       '<td>'+ value.nro + '</td>' +
                       '<td>' + value.fecha_etapa + '</td>' +
                       '<td>' + value.etapa + '</td>' +
                       '<td>' + value.etapa2 + '</td>' +
                       '<td>' + value.nombres + '</td>' +
                       '<td>' + value.observaciones + '</td>' +
                       '<td>' + value.administrador + '</td>' +
                       '<td>' + value.obs_administr + '</td>' +
                       '<td>' + value.fecha_crea + '</td>' +
                       '<td><a href="'+ "<?php echo base_url()?>index.php/admin/gestion/editar_etapa_acreedor/" +
                        value.id + '">Editar</a>' + '&nbsp;&nbsp;' +
                       '<a href="'+ "<?php echo base_url()?>index.php/admin/gestion/eliminar_etapa_acreedor/" +
                        value.id + '">Eliminar</a></td></tr>';
              $("#tbetapas tbody").append(tr);
              i++;
          });
        }
      });
  });

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