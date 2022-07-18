<?php
$color_bg = '';
$color = '';
$estado_msg = '';

?>

<?php if($nodo->nombre=='fullpay'){ ?> 
<?php if ($cuenta->id_estado_cuenta==1){ $color_bg = "#BDD95E"; $color = "#758B22"; $estado_msg = "VIGENTE"; }?>
<?php if ($cuenta->id_estado_cuenta==2){ $color_bg = "#999"; $color = "#666"; $estado_msg = "Rechazo INGRESA"; }?>
<?php if ($cuenta->id_estado_cuenta==3){ $color_bg = "#1186B6"; $color = "#00365F"; $estado_msg = "SUSPENDIDO"; }?>
<?php if ($cuenta->id_estado_cuenta==4){ $color_bg = "#DD808C"; $color = "#9E0404"; $estado_msg = "TERMINADO"; }?>
<?php if ($cuenta->id_estado_cuenta==5){ $color_bg = "#B8A0BD"; $color = "#7A318D"; $estado_msg = "DEVUELTO"; }?>
<?php if ($cuenta->id_estado_cuenta==6){ $color_bg = "#036"; $color = "#fff"; $estado_msg = "EXHORTO"; }?>
<?php if ($cuenta->id_estado_cuenta==7){ $color_bg = "#036"; $color = "#fff"; $estado_msg = "Dev. Documentos"; }?>
<?php if ($cuenta->id_estado_cuenta==8){ $color_bg = "#FF4500"; $color = "#fff"; $estado_msg = "AVENIMIENTO"; }?>
<?php if ($cuenta->id_estado_cuenta==9){ $color_bg = "#0078ff"; $color = "#fff"; $estado_msg = "EXCEPCIONES"; }?>
<?php if ($cuenta->id_estado_cuenta==10){ $color_bg = "#8FFF0F"; $color = "#fff"; $estado_msg = "REINGRESO"; }?>
<?php if ($cuenta->id_estado_cuenta==11){ $color_bg = "#28B463"; $color = "#fff"; $estado_msg = "LIQUIDACION"; }?>
<?php if ($cuenta->id_estado_cuenta==12){ $color_bg = "#229954"; $color = "#fff"; $estado_msg = "RETIRO POR REPROGRAMACION"; }?>
<?php }?>

<?php if($nodo->nombre=='swcobranza'){ ?> 
<?php if ($cuenta->id_estado_cuenta==1){ $color_bg = "#BDD95E"; $color = "#758B22"; $estado_msg = "VIGENTE"; }?>
<?php if ($cuenta->id_estado_cuenta==2){ $color_bg = "#999"; $color = "#666"; $estado_msg = "ABANDONADO"; }?>
<?php if ($cuenta->id_estado_cuenta==3){ $color_bg = "#1186B6"; $color = "#00365F"; $estado_msg = "SUSPENDIDO"; }?>
<?php if ($cuenta->id_estado_cuenta==4){ $color_bg = "#DD808C"; $color = "#9E0404"; $estado_msg = "TERMINADO"; }?>
<?php if ($cuenta->id_estado_cuenta==5){ $color_bg = "#B8A0BD"; $color = "#7A318D"; $estado_msg = "CONVENIO"; }?>
<?php if ($cuenta->id_estado_cuenta==6){ $color_bg = "#EDB2AC"; $color = "#E64128"; $estado_msg = "CONVENIO INCUMPLIDO"; }?>
<?php if ($cuenta->id_estado_cuenta==7){ $color_bg = "#AFE3F6"; $color = "#00A8E7"; $estado_msg = "SUSPENDIDO CON CONVENIO"; }?>
<?php if ($cuenta->id_estado_cuenta==8){ $color_bg = "#59D2E4"; $color = "#3C9EE4"; $estado_msg = "GPVE RECHAZADA"; }?>
<?php if ($cuenta->id_estado_cuenta==9){ $color_bg = "#8E3E06"; $color = "#E9B431"; $estado_msg = "ABONO"; }?>
<?php }?>

<div style="width:500px; margin-left:5px; height:25px; border:1px solid <?php echo $color;?>; padding:5px; background:<?php echo $color_bg;?>;">
	<span style="color:<?php echo $color;?>; font-size:18px; text-align:center; float:left; width:490px;">ESTADO DE LA CUENTA: <?php echo $estado_msg;?></span>
</div>
<div class="clear"></div>
<table class="stable table-destacado grilla" width="100%">
<tr><td colspan="4"><h3>Resumen de la Cuenta: </h3><br></td></tr>

<?php if($nodo->nombre=='swcobranza'): ?> 
<tr><td>R.U.T:</td><td><?php echo $cuenta->rut;?></td></tr>  
<tr><td>Deudor</td><td> <?php echo $cuenta->nombres.' '.$cuenta->ap_pat.' '.$cuenta->ap_mat;?>  </td></tr>
<tr><td>N° Operación</td><td> <?php echo $cuenta->operacion;?>  </td></tr>
<tr><td>Mandante</td><td><?php echo $cuenta->codigo_mandante;?></td></tr>
<tr><td>Procurador</td><td><?php echo $cuenta->nombres_adm.' '.$cuenta->apellidos;?></td></tr>
<tr><td>Receptor</td><td><?php echo $cuenta->nombre_receptor?></td></tr>
<tr><td>Fecha Suscripcion</td><td><?php if( $cuenta->fecha_suscripcion=='0000-00-00' || $cuenta->fecha_suscripcion=='1969-12-31' || $cuenta->fecha_suscripcion=='') { echo '-';} else { echo date('d-m-Y',strtotime($cuenta->fecha_suscripcion)); } ?><span style="font-size:10px;"> (fecha ingresada en archivo excel)</span></td></tr>
<tr><td>Fecha Creación</td><td><?php if( $cuenta->fecha_crea=='0000-00-00' || $cuenta->fecha_crea=='1969-12-31' || $cuenta->fecha_crea=='') { echo '-';} else { echo date('d-m-Y',strtotime($cuenta->fecha_crea)); } ?><span style="font-size:10px;"> (fecha creación del registro en el sistema)</span></td></tr>

<?php if ($this->session->userdata("usuario_perfil")==1):?>                              
<tr><td>Fecha escritura</td>
	<td><?php if( $cuenta->fecha_escritura_personeria=='0000-00-00' || $cuenta->fecha_escritura_personeria=='1969-12-31' || $cuenta->fecha_escritura_personeria=='') { echo '-';} else { echo $cuenta->fecha_escritura_personeria; } ?></td></tr> 
<tr><td>Notaria personeria</td><td><?php echo $cuenta->notaria_personeria; ?></td></tr>
<tr><td>Titular personeria</td><td><?php echo $cuenta->titular_personeria; ?></td></tr>

<?php endif;?>
<tr><td>Distrito</td><td><?php if($cuenta->distrito!=''){echo $cuenta->distrito;}?></td></tr>
<tr><td>Juzgado</td><td><?php if($cuenta->tribunal!=''){echo $cuenta->tribunal;}?></td></tr>
<tr><td>Rol</td><td><?php echo $cuenta->rol?></td></tr>
<tr><td>Tipo Demanda</td><td><?php if ($cuenta->tipo_demanda==1){ echo 'Propia ';} if ($cuenta->tipo_demanda==0){ echo 'Cedida';} if ($cuenta->exorto==1){ echo ' con Exhorto';} if ($cuenta->exorto==0){ echo ' sin Exhorto';}?></td></tr>

<tr><td>Estado de la Cuenta</td><td><?php echo $cuenta->estado; ?></td></tr>
<tr><td>Estado Juicio Actual</td><td><?php echo $cuenta->etapa; ?></td></tr>
<?php if ($this->session->userdata("usuario_perfil")==1):?>  
<tr><td>Deuda</td><td><?php echo $cuenta->monto_deuda-$cuenta->monto_pagado_new;?> + intereses</td></tr>
<?php endif;?>
<!-- solo los qu esta n rodados son etsado 1  style color:#7FBA00 -->

<tr><td>Direcciones</td><td>
<?php foreach ($direcciones as $key=>$direccion): if ($direccion->estado<2): $style=''; if ($direccion->estado==1){$style=' style="color:#7FBA00"';}?>
<?php echo '<span'.$style.'>'.$direccion->direccion.' '.$direccion->nombre_comuna.'</span><br>';?>
<?php endif; endforeach;?>
</td></tr>

<tr><td>Teléfonos</td><td>
<?php foreach ($telefonos as $key=>$telefono): if ($telefono->estado<2): $style=''; if ($telefono->estado==1){$style=' style="color:#7FBA00"';}?>
<?php echo '<span'.$style.'>'.$tipos[$telefono->tipo].' '.$telefono->numero.' '.$telefono->observacion.'</span><br>';?>
<?php endif; endforeach;?>
</td></tr>

<tr><td>Bienes</td><td>
<?php foreach ($bienes as $key=>$bien): if ($bien->estado<2): $style=''; if ($bien->estado==1){$style=' style="color:#7FBA00"';}?>
<?php echo '<span'.$style.'>'.$tipos_bienes[$bien->tipo].' '.$bien->observacion.'</span><br>';?>
<?php endif; endforeach;?>
</td></tr>


<tr><td>Correo electrónico</td><td>
<?php foreach ($mail as $key=>$val): if ($val->estado<2): $style=''; if ($val->estado==1){$style=' style="color:#7FBA00"';}?>
<?php echo '<span'.$style.'>'.$val->mail.'</span><br>';?>
<?php endif; endforeach;?>
</td></tr>


<tr><td>Pagares</td><td>
<?php foreach ($pagares as $key=>$pagare):?>
<?php echo '<span>'.date('d-m-Y',strtotime($pagare->fecha_asignacion)); if ($nodo->nombre=='fullpay'){ echo ' - '.number_format($pagare->monto_deuda,0,',','.').' UF'; }else { echo ' - '.number_format($pagare->monto_deuda,0,',','.');} echo '</span><br>';?>

<?php endforeach;?>
</td></tr>

<tr><td>Etapas</td><td>
<?php foreach ($etapas_juicio_cuenta as $key=>$etapa):?>
<?php echo '<span>'.date('d-m-Y',strtotime($etapa->fecha_etapa)).' - '.$etapa->etapa.'</span><br>';?>
<?php endforeach;?>
</td></tr>
<?php endif;?>

<?php if($nodo->nombre=='fullpay'): ?> 
<tr><td>Mandante</td><td><?php echo $cuenta->codigo_mandante;?></td></tr>
<tr><td>R.U.T:</td><td><?php echo $cuenta->rut;?>   <a href="<?php echo site_url().'/admin/usuarios/form/editar/'.$cuenta->id_usuario;?>" title="" class="editar" ></a>  </td></tr>
																					
<tr><td>Deudor</td><td> <?php echo $cuenta->nombres.' '.$cuenta->ap_pat.' '.$cuenta->ap_mat;?>  </td></tr>
<tr><td>N° Operación</td><td> <?php echo $cuenta->operacion;?>  </td></tr>
<tr><td>Marcas Especiales</td><td> <?php echo $cuenta->marca;?>  </td></tr>
<tr><td>Direcciones</td><td>
<?php foreach ($direcciones as $key=>$direccion): if ($direccion->estado<2): $style=''; if ($direccion->estado==1){$style=' style="color:#7FBA00"';}?>
<?php echo '<span'.$style.'>'.$direccion->direccion.' '.$direccion->nombre_comuna.'</span><br>';?>
<?php endif; endforeach;?>
</td></tr>

<tr><td>Tipo Demanda</td><td><?php if($cuenta->tipo_demanda==1){ echo 'Propia'; } elseif($cuenta->exorto==0) { echo 'Cedida';  }    ?></td></tr>

<?php if ($this->session->userdata("usuario_perfil")==1):?>  
<tr><td>Deuda</td><td><?php echo $cuenta->monto_deuda-$cuenta->monto_pagado_new;?> + intereses</td></tr>
<?php endif;?>
<tr><td>Pagares</td><td>


<?php if ( $nodo->nombre=='fullpay' && $cuenta->codigo_mandante == 'CAE' ||  $nodo->nombre=='fullpay' && $cuenta->codigo_mandante == 'SCOB' || $nodo->nombre=='fullpay' && $cuenta->codigo_mandante == 'Falabella' || $nodo->nombre=='fullpay' && $cuenta->codigo_mandante == '2013' || $nodo->nombre=='fullpay' && $cuenta->codigo_mandante == 'FURUM'): ?>
    <?php foreach ($pagares as $key=>$pagaree): ?>
        <span>

        <?php $pag =date('d-m-Y',strtotime($pagaree->fecha_suscripcion)); ?>
        <?php  if($pag == '30-11--0001' ||  $pag == '00-00--0000'){
                    $pag = '-';
            } ?>

    <?php echo 'F.Suscripción &nbsp;'.'<span>'.$pag.'&nbsp; F.Vencimiento &nbsp;';  if( $pagaree->fecha_vencimiento=='0000-00-00' || $pagaree->fecha_vencimiento=='1969-12-31' || $pagaree->fecha_vencimiento=='') { echo '-';} else { echo date('d-m-Y',strtotime($pagaree->fecha_vencimiento)); }  if ($nodo->nombre=='fullpay'){ echo ' - '.$pagaree->monto_deuda.' UF';} else { echo ' - '.number_format($pagaree->monto_deuda,0,',','.');} echo '</span><br>';?>
        </span>
    <?php endforeach;?>
<?php else:?>
    <?php foreach ($pagares as $key=>$pagare):?>

        <?php echo 'F.Vencimiento -';  if( $pagare->fecha_vencimiento=='0000-00-00' || $pagare->fecha_vencimiento=='1969-12-31' || $pagare->fecha_vencimiento=='') { echo '-';} else { echo date('d-m-Y',strtotime($pagare->fecha_vencimiento)); }  if ($nodo->nombre=='fullpay' && $cuenta->codigo_mandante != 'Falabella'){ echo ' - '.$pagare->monto_deuda.' UF';} elseif($nodo->nombre=='fullpay' && $cuenta->codigo_mandante == 'Falabella') { echo ' - '.$pagare->monto_deuda.' CLP';} else { echo ' - '.number_format($pagare->monto_deuda,0,',','.');}  echo '</span><br>';?>
    <?php endforeach;?>
<?php endif; ?>


</td></tr>



    <?php if ($nodo->nombre=='swcobranza' ):  ?>
<tr><td>Fecha Asignacion</td><td>
 <?php if( $pagare->fecha_asignacion=='0000-00-00' || $pagare->fecha_asignacion=='1969-12-31' || $pagare->fecha_asignacion=='') { echo '-';} else  { echo date('d-m-Y',strtotime($pagare->fecha_asignacion)); } ?><span style="font-size:10px;"> (fecha ingresada en archivo excel)</span>
 <?php endif; ?>

 <?php if ( $nodo->nombre=='fullpay' && $cuenta->codigo_mandante != 'CAE' ||  $nodo->nombre=='fullpay' && $cuenta->codigo_mandante != 'SCOB'): ?>
        <tr><td>Fecha Suscrpción</td><td>
 <?php if( $cuenta->fecha_suscripcion=='0000-00-00' || $cuenta->fecha_suscripcion=='1969-12-31' || $cuenta->fecha_suscripcion=='') { echo '-';} else  { echo date('d-m-Y',strtotime($cuenta->fecha_suscripcion)); } ?><span style="font-size:10px;"> (fecha ingresada en archivo excel)</span>
 <?php endif; ?>


</td></tr>

<?php //print_r($cuenta);?>
<tr><td>Fecha Creación</td><td><?php if( $cuenta->fecha_crea=='0000-00-00' || $cuenta->fecha_crea=='1969-12-31' || $cuenta->fecha_crea=='') { echo '-';} else { echo date('d-m-Y',strtotime($cuenta->fecha_crea)); } ?><span style="font-size:10px;"> (fecha creación del registro en el sistema)</span></td></tr>
<tr><td>Jurisdicción</td><td><?php if($cuenta->jurisdiccion!=''){echo $cuenta->jurisdiccion;}?></td></tr>
<tr><td>Tribunal</td><td><?php if($cuenta->tribunal!=''){echo $cuenta->tribunal;}?></td></tr>
<tr><td>Rol</td><td><?php echo $cuenta->rol?></td></tr>
<tr><td>Procurador</td><td><?php echo $cuenta->nombres_adm.' '.$cuenta->apellidos;?></td></tr>
<tr><td>Estado Juicio Actual</td><td><?php echo $cuenta->etapa; ?></td></tr>
<tr><td>Receptor</td><td><?php echo $cuenta->nombre_receptor?></td></tr>
<tr><td>Estado de la Cuenta</td><td><?php echo $cuenta->estado; ?></td></tr>
<tr><td>Teléfonos</td><td>
<?php foreach ($telefonos as $key=>$telefono): if ($telefono->estado<2): $style=''; if ($telefono->estado==1){$style=' style="color:#7FBA00"';}?>
<?php echo '<span'.$style.'>'.$tipos[$telefono->tipo].' '.$telefono->numero.' '.$telefono->observacion.'</span><br>';?>
<?php endif; endforeach;?>
</td></tr>

<tr><td>Bienes</td><td>
<?php foreach ($bienes as $key=>$bien): if ($bien->estado<2): $style=''; if ($bien->estado==1){$style=' style="color:#7FBA00"';}?>
<?php echo '<span'.$style.'>'.$tipos_bienes[$bien->tipo].' '.$bien->observacion.'</span><br>';?>
<?php endif; endforeach;?>
</td></tr>
<tr><td>Correo electrónico</td><td>
<?php foreach ($mail as $key=>$val): if ($val->estado<2): $style=''; if ($val->estado==1){$style=' style="color:#7FBA00"';}?>
<?php echo '<span'.$style.'>'.$val->mail.'</span><br>';?>
<?php endif; endforeach;?>
</td></tr>
<tr><td>Etapas</td><td>
<?php foreach ($etapas_juicio_cuenta as $key=>$etapa):?>
<?php echo '<span>'.date('d-m-Y',strtotime($etapa->fecha_etapa)).' - '.$etapa->etapa.'</span><br>';?>
<?php endforeach;?>
</td></tr>

<tr><td>Exhorto</td><td><?php if($cuenta->exorto==1){ echo 'Con exhorto'; } elseif($cuenta->exorto==0) { echo 'Sin Exhorto';  }    ?></td></tr>

<tr><td>Jurisdiccion Exhorto</td><td><?php echo $cuenta->jurisdiccionE;?></td></tr>
<tr><td>Juzgado Exhortado</td><td><?php echo $cuenta->tribunalE;?></td></tr>
<tr><td>Rol Exhorto</td><td><?php echo $cuenta->rolEx;    ?></td></tr>


<?php if($nodo->nombre=='fullpay'): ?> 
<tr><td>Castigo</td><td><?php if($cuenta->id_castigo==2){ echo 'Con Castigo'; } elseif($cuenta->id_castigo==1) { echo 'Sin Castigo';  }    ?></td></tr>
<?php endif;?>

<?php if($nodo->nombre=='fullpay'): ?> 
<tr><td>Nº Contrato </td><td><?php echo $cuenta->numero_contrato; ?></td></tr>
<?php endif;?>

<!--<tr><td>Roles adicionales</td><td>
<?php if (count($juzgados)>0):?>
	<?php foreach ($juzgados as $key=>$juzgado):?>
	<?php echo '<span>'.$juzgado->Distrito.' - '.$juzgado->Juzgado.' - '.$juzgado->Rol.'</span><br>';?>
	<?php endforeach;?>
<?php endif;?>

</td></tr>-->


<?php endif;?>


</table>