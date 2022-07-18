<div class="tabla-listado">
	<div class="content_tabla">
    	<h3>Tabla de Cálculo de Intereses</h3>
        <div class="clear"></div>
		<table class="listado" border="1" style="border:1px solid #CDCCCC; width:100%; border-collapse:collapse;">
		<tbody>
		<tr class="menu" style="line-height:20px; height:50px; text-align: center;">
		  <td width="10%" class="nombre"><?php echo $fecha_title?></td>
		  <td width="10%" class="nombre">Tasa de Interés</td>
		  <td width="10%" class="nombre">Monto</td>
		  <td width="10%" class="nombre">Interes</td>
		  <td width="10%" class="nombre">Deuda Total</td>
		  <td width="10%" class="nombre">Valor de cada Abono</td>
		</tr>
		
		<?php $i=1;?>
		<?php foreach ($intereses as $key=>$val):?>
		
		<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> style="line-height:20px; height:30px;font-size: 14px; text-align: center;" id="row-<?php //echo $val->id;?>">
		
			<td><?php if( $fecha_comparacion != ''):?>
					<?php echo date("d-m-Y",strtotime($fecha_comparacion))?>
				<?php else:?>
					-
				<?php endif;?>
			</td>
						
			<td><?php echo number_format( $val , 2, ',','.')?> %<?php $porcentaje = ($val/100)?></td>
			<td> <?php echo number_format( $total_deuda , 0, '','.')?></td>
			<td> <?php echo number_format( $porcentaje*$total_deuda*abs($diferencia_dias/30) , 0, '', '.')?></td>
			
			<?php $interes = $porcentaje*$total_deuda*abs($diferencia_dias/30)?>
			<?php $total = ($total_deuda+ $interes)?>
			<td> 
				<?php echo number_format($total , 0, '', '.')?>
			</td>

			<td> <span class="total_n_cuotas" data-total="<?php echo $total ?>"><?php echo number_format( ceil($total)  , 0, ',', '.')?></span></td>
		</tr>
		
		<?php ++$i;endforeach;?>
		</tbody>
		</table>
	</div>
</div>
