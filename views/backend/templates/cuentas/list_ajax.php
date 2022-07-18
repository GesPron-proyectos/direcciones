<?php //print_r($etapas)?>
 	<td colspan="13">
	 	<table class="listado" width="100%" style="font-size:11px">
		 	<tr class="titulos-tabla" style="line-height:20px; height:30px; background-color:#CACACA !important">
		 		<td>Dirección</td>
		 		<td>Comuna</td>
		 		<td>Ciudad</td>
		 		<td>Teléfonos</td>
                <td>Bienes</td>
		 		<td>Juzgado</td>
		 		<td>Distrito</td>
		 		<td>Rol</td>
		 		<td>Fecha de Inicio</td>
		 		<td>Deuda</td>
		 		<td>Estado de la cuenta</td>
		 	</tr>
		 	<?php foreach ($cuenta AS $key => $val):?>
            
            <?php 
				$direccion = '';
				if (count($direccion_list)>0){
					$direccion = $direccion_list->direccion;
				}
			
				$telefonos = '';
				if (count($telefono_list)>0){
					foreach($telefono_list as $k=>$v){
						$style = '';
						if ($v->estado==1){ $style='color:#7FBA00"';}
						if($v->tipo == 1){
							if ($telefonos!=''){$telefonos.='<br>';}$telefonos.=' Particular:';
						}
						if( $v->tipo == 2){
							if ($telefonos!=''){$telefonos.='<br>';}$telefonos.=' Comercial:';
						}
						if( $v->tipo == 3){
							if ($telefonos!=''){$telefonos.='<br>';}$telefonos.=' Celular:';
						}
						if( $v->tipo == 4){
							if ($telefonos!=''){$telefonos.='<br>';}$telefonos.=' Otro:';
						}
						$telefonos.=' <span style="'.$style.'">'.$v->numero.'</span>';
					} 
				}
				?>
            
            
		 	<tr class="dentro">
		 		<td><?php echo $direccion;?></td>
		 		<td><?php echo $val->s_comunas_nombre?></td>
		 		<td><?php echo $val->usuarios_ciudad?></td>
		 		<td><?php echo $telefonos?></td>
                <td>
                <?php $bienes = array('1'=>'Vehículo','2'=>'Inmueble'); ?>
				<?php if( count($bien_list)>0):?>
                	<?php foreach ($bien_list as $key=>$val):?>
					<?php if (array_key_exists($val->tipo, $bienes)) :?>
						<?php 
						$style='';
						if ($val->estado==1){ $style='color:#7FBA00"';}
						echo '<span style="'.$style.'">'.$bienes[$val->tipo].': '.$val->observacion.'</span><br>';
						?>
					<?php endif;?>
                    <?php endforeach;?>
				<?php endif;?>
                </td>
		 		<td><?php echo $val->tribunal?></td>
		 		<td><?php $val->s_tribunales_tribunal?></td>
		 		<td><?php echo $val->cuentas_rol?></td>
		 		
		 		
		 		<?php if( isset($val->cuentas_fecha_inicio) && $val->cuentas_fecha_inicio == '0000-00-00'):?>
		 		<td> - </td>
		 		<?php else:?>
		 		<td><?php echo date("d-m-Y", strtotime($val->cuentas_fecha_inicio) )?></td>
		 		<?php endif;?>
		 		
		 		<?php $monto_pagado_new = 0; if (isset($val->monto_pagado_new) && $val->monto_pagado_new>0){$monto_pagado_new = $val->monto_pagado_new;}?>
		 		<td><?php echo number_format( ($monto_deuda - $monto_pagado_new) ,0,',','.')?></td>
		 		<td><?php echo $val->s_estado_cuenta_estado?></td>
		 	</tr>
		 	<?php endforeach;?>
		 	<tr class="dentro">
		 		<td colspan="11"> </td>
		 	</tr>
	 	</table>
	 	
	 	<table class="listado" width="100%" style="font-size:11px">
		 	<tr class="titulos-tabla" style="line-height:20px; height:30px; background-color:#CACACA !important">
		 		<td>Etapa</td>
		 		<td>Fecha</td>
		 		<td>Observaciones Procurador</td>
		 		<td>Observaciones Adminsitrador</td>
		 	</tr>
		 	<?php foreach ($etapas AS $key => $val):?>
		 	<tr class="dentro">
		 		<td><?php echo $val->s_etapas_etapa?></td>
		 		<?php if( $val->cuentas_etapas_fecha_etapa == '0000-00-00'):?>
		 		<td> - </td>
		 		<?php else:?>
		 		<td><?php echo date("d-m-Y", strtotime($val->cuentas_etapas_fecha_etapa) )?></td>
		 		<?php endif;?>
		 		<td><?php echo $val->cuentas_etapas_observaciones?></td>
		 		<td><?php echo $val->cuentas_etapas_obs_administrador?></td>
		 		
		 	</tr>
		 	<?php endforeach;?>
		 	<tr class="dentro">
		 		<td colspan="11"> </td>
		 	</tr>
	 	</table>
	 	
	 	<table class="listado" width="100%" style="font-size:11px">
		 	<tr class="titulos-tabla" style="line-height:20px; height:30px; background-color:#CACACA !important">
		 		<td>N° Pagare</td>
		 		<td>Fecha Asignación</td>
		 		<td>Monto Deuda</td>
		 	</tr>
		 	<?php foreach ($pagares AS $key => $val):?>
		 	<tr class="dentro">
		 		<td><?php echo $val->n_pagare?></td>
		 		<td><?php echo date('d-m-Y',strtotime($val->fecha_asignacion))?></td>
		 		<td><?php echo number_format( $val->monto_deuda ,0,',','.')?></td>
		 	</tr>
		 	<?php endforeach;?>
		 	<tr class="dentro">
		 		<td colspan="10"> </td>
		 	</tr>
	 	</table>
	 	
	</td>