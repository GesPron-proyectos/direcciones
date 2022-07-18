<?php if ($nodo->nombre=='swcobranza'):?>

    <table class="stable table-destacado" width="100%">
        <tr>
            <?php $fecha_pagare = ''; $diferencia_dias=''; $diferencia_dias_primer_pago = '';?>
            <td><strong>Fecha Pagaré:</strong></td><td><?php if (count($pagares)>0){$i=1;foreach ($pagares as $pagare){ if ($i==1){ $fecha_pagare = $pagare->fecha_asignacion; $diferencia_dias = $pagare->diferencia_dias; $diferencia_dias_primer_pago = $pagare->diferencia_dias_primer_pago; echo date('d-m-Y',strtotime($pagare->fecha_asignacion)); } $i++;}}?>
            <td><strong>Fecha Último Pago:</strong></td><td><?php if (count($pagos)>0){$i=1; foreach ($pagos as $pago){ if ($i==1){ echo date('d-m-Y',strtotime($pago->fecha_pago)); } $i++;}}?></td>
        </tr>
        <tr>
            <td><strong>Total Pagaré:</strong></td>
            <td> <?php if($cuenta->monto_deuda != ''){ ?>  <?php echo number_format($cuenta->monto_deuda,0,',','.');?>  <?php } ?> </td>
            <td><strong>Abonado hasta hoy:</strong></td>
            <td><?php echo number_format($cuenta->monto_pagado_new,0,',','.');?></td>
        </tr>
        <tr>
            <td><strong>Deuda:</strong></td>
            <td><span style="color:#9E0404"><?php  $deuda = $cuenta->monto_deuda-$cuenta->monto_pagado_new; echo number_format($cuenta->monto_deuda-$cuenta->monto_pagado_new,0,',','.');?></span></td>
            <td><strong>Intereses (2.5%)</strong></td>
            <?php $deuda = $cuenta->monto_deuda;?>

            <td>
                <?php
                if ($cuenta->intereses){
                    echo  number_format($cuenta->intereses,0,',','.').'*<br>';
                    $intereses = $cuenta->intereses;
                } else {
                    $intereses = 0;
                    if ($cuenta->n_cuotas>0){
                        if (abs($diferencia_dias_primer_pago)>0){
                            $intereses = abs(0.025*$deuda*($diferencia_dias_primer_pago/30));
                            echo number_format(abs(0.025*$deuda*($diferencia_dias_primer_pago/30)),0,',','.').'<br>';

                        }
                        echo 'No se estan generando intereses';

                    }
                    else {
                        if (abs($diferencia_dias)>0){
                            $intereses = abs(0.025*$deuda*($diferencia_dias/30)); echo number_format(abs(0.025*$deuda*($diferencia_dias/30)),0,',','.');
                        }
                    }
                }
                ?>
                <?php if ($nodo->nombre=='swcobranza'):?>
                    <?php echo form_open(site_url().'/admin/gestion/guardar_intereses/'.$id,'id="intereses"');?>
                    <?php  if( $this->session->userdata("usuario_perfil") ==  1  && $nodo->nombre == 'swcobranza'):?>
                    <input type="text" name="intereses" value="<?php echo $cuenta->intereses;?>">
                    <input type="submit" value="Guardar">
					<?php endif; ?>	                    
					<?php  if( $nodo->nombre == 'fullpay'):?>
                    <input type="submit" value="Guardar">
					<?php endif; ?>
                    
					<?php echo form_close();?>
                <?php endif;?>
            </td>
        </tr>
        <tr>
            <td><strong>Total Gastos:</strong></td>
            <td><?php echo number_format($cuenta->monto_gasto_new,0,',','.');?></td>
        </tr>
        <tr>
            <td><strong>Deuda Total (deuda+gastos+intereses):</strong></td>
            <td><?php echo number_format($cuenta->monto_deuda-$cuenta->monto_pagado_new+$cuenta->monto_gasto_new+$intereses,0,',','.');?></td>
        </tr>
    </table>
<?php endif; ?>



<div style="border:1px solid #cdcccc; margin:5px; padding:10px;background-color: #dfdcd9;">
            <h3>Acuerdo de Abonos:</h3>
            <a href="#" id="mostrar-acuerdo-abonos" style="float: right; margin-top: -13px; text-decoration: underline;">Mostrar/Ocultar</a>
        </td>
    </tr>
</div>
<div id="caja-acuerdo-abonos">
    <?php echo form_open(site_url().'/admin/gestion/guardar_convenio/'.$id,'id="form_guardar-acuerdo"'); $diferencia_dias = 0;?>
    <table class="stable" width="100%">
        <tr>
            <td>Nº Cuotas:</td><td>
                <input type="hidden" name="id_cuenta" value="<?php echo $id?>">
                <input type="text" name="n_cuotas" value="<?php echo $cuenta->n_cuotas;?>" class="n_cuotas_intereses" id="n_cuotas_intereses" autocomplete="off"/>
                <?php echo form_error('n_cuotas','<span class="error">', '</span>');?>
                <input type="hidden" name="n_cuotas_real" value="<?php echo $cuenta->n_cuotas_real;?>" id="n_cuotas_real"/></td>

            <?php if ($nodo->nombre=='swcobranza'):?>
                <td>Tasa de Interés:</td>
                <td>
                    <?php echo form_dropdown('intereses',array('2'=>'2,00','2.1'=>'2,10','2.2'=>'2,20','2.3'=>'2.30','2.4'=>'2,40','2.5'=>'2,50'),$cuenta->intereses,'id="intereses"' );?>
                    <input type="hidden" name="monto_deuda" id="global_total_saldo" value="<?php echo $cuenta->monto_deuda-$cuenta->monto_pagado_new;?>">
                </td>
            <?php endif; ?>
        </tr>
        <tr>
            <td>Valor de cada Abono:</td>
            <td>
                <input type="text" name="valor_cuota" value="<?php echo $cuenta->valor_cuota?>" id="valor_cuota"/>
                <?php echo form_error('valor_cuota','<span class="error">', '</span>');?>
                <input type="hidden" name="valor_cuota_real" id="valor_cuota_real" value="<?php echo $cuenta->valor_cuota_real;?>" />
            </td>
        </tr>
        <tr>
            <td>Día Vencimiento:</td>
            <td>
                <?php echo day_dropdown('dia_vencimiento_cuota',$cuenta->dia_vencimiento_cuota);?>
                <?php echo form_error('dia_vencimiento_cuota','<span class="error">', '</span>');?>
            </td>
            <td>Fecha del Convenio</td>
            <td><input type="text" class="datepicker" name="fecha_primer_pago" value="<?php if ($cuenta->fecha_primer_pago!='0000-00-00'){echo date('d-m-Y',strtotime($cuenta->fecha_primer_pago));}?>"><?php echo form_error('fecha_primer_pago','<br><span class="error">','</span>');?></td>
        </tr>
        
		<?php  if( $this->session->userdata("usuario_perfil") ==  1  && $nodo->nombre == 'swcobranza'):?>
        <tr><td colspan="4"><br><input type="submit" value="Celebrar Convenio" style="float:right"></td></tr>
        <?php endif; ?>
        
        <?php  if( $nodo->nombre == 'fullpay'):?>
        <tr><td colspan="4"><br><input type="submit" value="Celebrar Convenio" style="float:right"></td></tr>
        <?php endif; ?>
        
        
        <tr><td colspan="4">
                <?php /*if ($nodo->nombre=='swcobranza'):?>
          <div id="lista_intereses" style="width:450px;"></div>
      <?php endif*/?>
            </td>
        </tr>

    </table>
    <?php echo form_close();?>
</div>
<?php $this->load->view('backend/templates/gestion/gestion/pagos_form');?>
<div id="box-form-pagos"></div>
<!--caja-acuerdo-abonos-->
<?php echo form_open(site_url().'/admin/gestion/edit_pagos/'.$id.'/'.$idregistro); ?>


<?php
$id_acuerdo_pago = $this->input->post('id_acuerdo_pago');
$fecha_pago = $this->input->post('fecha_pago');
if ($fecha_pago!=''){ $fecha_pago = date('d-m-Y',strtotime($fecha_pago));}
$monto_pagado = $this->input->post('monto_pagado');
$n_comprobante_interno = $this->input->post('n_comprobante_interno');
$forma_pago = $this->input->post('forma_pago');

if ($idregistro!=''){
    $id_acuerdo_pago = $pago->id_acuerdo_pago;
    $fecha_pago = date('d-m-Y',strtotime($pago->fecha_pago));
    $monto_pagado = $pago->monto_pagado;
    $n_comprobante_interno = $pago->n_comprobante_interno;
    $forma_pago = $pago->forma_pago;
}
?>






<table class="stable" width="100%">
    <tr><td colspan="4"><h3>Listado de Abonos:</h3><br></td></tr>
    <tr>
        <td colspan="4">
            <table class="stable grilla" width="100%">
                <tr class="titulos-tabla">
                    <td>#</td>
                    <td>Tipo de Abono</td>
                    <td>Fecha</td>
                    <td>Monto</td>
                    <td>Nº Comprobante</td>
                    <td>Forma de Abono</td>
                    <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                        <td>Gestión</td>
                    <?php endif;?>

                </tr>
                <?php $i=1;if (count($pagos)>0):?>
                    <?php foreach ($pagos as $key=>$pago):?>
                        <tr>
                            <td>#<?php echo $pago->id;?></td>

                            <td><?php
                                if (array_key_exists($pago->id_acuerdo_pago,$acuerdos_pago)){
                                    echo $acuerdos_pago[$pago->id_acuerdo_pago];
                                    if ($i==1){
                                        $d_acuerdo=date('d',strtotime($pago->fecha_pago));
                                        $m_acuerdo=date('n',strtotime($pago->fecha_pago));
                                        $y_acuerdo=date('Y',strtotime($pago->fecha_pago));
                                    }
                                    $i++;
                                }
                                ?>
                            </td>
                            <td><?php echo date('d-m-Y',strtotime($pago->fecha_pago));?></td>
                            <td><?php echo number_format($pago->monto_pagado,0,',','.');?></td>
                            <td><?php echo $pago->n_comprobante_interno;?></td>
                            <td><?php echo $formas_pago[$pago->forma_pago];?></td>

                            <?php  if( $this->session->userdata("usuario_perfil") ==  1  && $nodo->nombre == 'swcobranza'):?>
                                <td>
                                    <a href="<?php echo site_url('admin/gestion/editar_pagos/'.$id.'/'.$pago->id);?>">Editar</a>
                                    <a href="<?php echo site_url('admin/gestion/eliminar_pagos/'.$id.'/'.$pago->id);?>" class="delete">Eliminar</a></td>
                            <?php endif;?>
                       
                       		<?php  if( $this->session->userdata("usuario_perfil") ==  2 && $nodo->nombre == 'fullpay'):?>
                                <td><a href="<?php echo site_url('admin/gestion/editar_pagos/'.$id.'/'.$pago->id);?>">Editar</a></td>
                              <?php endif;?>

                                <?php  if( $this->session->userdata("usuario_perfil") ==  1 && $nodo->nombre == 'fullpay'):?>
                            <td> <a href="<?php echo site_url('admin/gestion/editar_pagos/'.$id.'/'.$pago->id);?>">Editar</a>
                            <a href="<?php echo site_url('admin/gestion/eliminar_pagos/'.$id.'/'.$pago->id);?>" class="delete">Eliminar</a></td>

                            <?php endif;?>

                       
                       
                        </tr>

                    <?php endforeach;?>

                    <?php $k = 1; $ncuotareal = ceil($cuenta->n_cuotas_real); if ($ncuotareal>100){$ncuotareal = 100;}?>
                    <?php if ($i<=$ncuotareal):?>
                        <?php for ($j=$i;$j<=$ncuotareal;$j++):?>
                            <?php if ($m_acuerdo==12){$m_acuerdo=1; $y_acuerdo++;} else {$m_acuerdo++;}?>


                            <?php if($k == 1): ?>
                                <tr>
                                    <td> </td>

                                    <td> <?php echo form_dropdown('id_acuerdo_pago', $acuerdos_pago, 1);?></td>

                                    <?php $date = date('d-m-Y'); ?>
                                    <td><input type="text" class="datepicker" name="fecha_pago" value="<?php echo $date?>"><?php echo form_error('fecha_pago','<br><span class="error">','</span>');?></td>

                                    <?php $monto_pagado = ''; ?>
                                    <td> <?php $monto_pagado =  number_format($pago->monto_pagado,0,',','.');  ?>
                                        <input type="text" name="monto_pagado" value="<?php echo $pago->monto_pagado ?>" /> </td>

                                    <td><?php echo form_input(array('name'=>'n_comprobante_interno','style'=>'width:135px'), $n_comprobante_interno);?>
                                        <?php echo form_error('n_comprobante_interno','<br><span class="error">','</span>');?></td>

                                    <td>
                                        <?php echo form_dropdown('forma_pago', $formas_pago, $forma_pago,' class="" autocomplete="off" data-id="'.$id.'" ');?>
                                        <?php echo form_error('forma_pago','<br><span class="error">','</span>');?>
                                    </td>

                                    <td colspan="4"><br><input type="submit" value="<?php if ($idregistro==''):?>Guardar<?php else:?>Guardar<?php endif;?>" style="float:right"></td>
                                    <td colspan="4">&nbsp;</td>
                                </tr>

                            <?php endif; ?>
                            <?php $k++; ?>



                            <tr style="color:#999">
                            <td>#Abono <?php  echo $j;?></td>
                            <td>Acuerdo*</td>
                            <td><?php echo $d_acuerdo;?>-<?php if ($m_acuerdo<10){echo '0';} echo $m_acuerdo;?>-<?php echo $y_acuerdo;?></td>
                            <td><?php if ($j>$cuenta->n_cuotas_real){ echo number_format((abs($cuenta->valor_cuota*($cuenta->n_cuotas_real-$j))),0,',','.');} else { echo number_format($cuenta->valor_cuota,0,',','.');}?></td>


                        <?php endfor;?>
                    <?php endif;?>
                <?php else:?>
                    <tr><td colspan="7">No hay abonos ingresados</td></tr>
                <?php endif;?>

            </table>
        </td>
    </tr>
</table>

<?php echo form_close();?>

<?php if ($nodo->nombre=='fullpay'):?>
    <div id="box-form-gastos"></div>
    <table class="stable" width="100%">
        <tr><td colspan="4"><h3>Listado de gastos:</h3><br></td></tr>
        <tr>
            <td colspan="4">
                <table class="stable grilla" width="100%">
                    <tr class="titulos-tabla">
                        <td>#</td>
                        <td>Fecha</td>
                        <td>Ítem</td>
                        <td>Diligencia</td>
                        <td>Nº Boleta</td>
                        <td>Receptor</td>
                        <td>Rol</td>
                        <td>Monto</td>
                        <td>Retención</td>

                        <td>Fecha Recepción</td>
                        <td>Fecha Ingreso Banco</td>
                        <td>Estado Pago</td>


                        <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                            <td>Gestión</td>
                        <?php endif;?>
                    </tr>
                    <?php if (count($gastos)>0):?>
                        <?php foreach ($gastos as $key=>$gasto):?>
                            <tr>
                                <td>#<?php echo $gasto->id;?></td>
                                <td><?php echo date('d-m-Y',strtotime($gasto->fecha));?></td>
                                <td><?php echo $gasto->item_gasto;?></td>
                                <td><?php echo $gasto->diligencia;?></td>
                                <td><?php echo $gasto->n_boleta;?></td>
                                <td><?php echo $gasto->nombre_receptor;?></td>
                                <td><?php echo $gasto->rol;?></td>
                                <td><?php echo number_format($gasto->monto,0,',','.');?></td>
                                <td><?php echo number_format($gasto->retencion,0,',','.');?></td>
                                <?php if ($nodo->nombre=='fullpay'):?>
                                <td><?php echo date('d-m-Y',strtotime($gasto->fecha_recepcion));?></td>
                                <td><?php echo date('d-m-Y',strtotime($gasto->fecha_ingreso_banco));?></td>
                                <td> <?php if($gasto->id_estado_pago=='2'){ echo 'Pendiente'; }elseif($gasto->id_estado_pago=='1'){ echo 'Pagado'; } ?>
                                    <?php endif;?>
                                    <?php if ($this->session->userdata("usuario_perfil")==1):?>
                                <td>

                                    <a href="<?php echo site_url('admin/gestion/editar_gastos/'.$id.'/'.$gasto->id);?>" class="edit"  data-id="<?php echo $gasto->id;?>" data-gtab="gastos" >Editar</a>
                                    <a href="<?php echo site_url('admin/gestion/eliminar_gastos/'.$id.'/'.$gasto->id);?>" class="delete">Eliminar</a></td>
                            <?php endif;?>
                                <?php if ($this->session->userdata("usuario_perfil")==2):?>
                                <td>
                                 <a href="<?php echo site_url('admin/gestion/editar_gastos/'.$id.'/'.$gasto->id);?>" class="edit"  data-id="<?php echo $gasto->id;?>" data-gtab="gastos" >Editar</a>
                                </td>
                                <?php endif;?>

                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td colspan="7">No hay gastos ingresados</td></tr>
                    <?php endif;?>
                </table>
            </td></tr>
    </table>
<?php endif;?>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("keyup","#gasto_monto",function(e){
            var monto = parseInt($(this).val());
            var retencion = 0;
            if ($(this).val()!=''){retencion = Math.round(monto*0.1,0);}
            $('#gasto_retencion').val(retencion);
        });
        $(document).on("change","#gasto_monto",function(e){
            var monto = parseInt($(this).val());
            var retencion = 0;
            if ($(this).val()!=''){retencion = Math.round(monto*0.1,0);}
            $('#gasto_retencion').val(retencion);
        });
    });

</script>




<script type="text/javascript">
    $(document).ready(function(){
        $(document).on("keyup","#gasto_monto",function(e){
            var monto = parseInt($(this).val());
            var retencion = 0;
            if ($(this).val()!=''){retencion = Math.round(monto*0.1,0);}
            $('#gasto_retencion').val(retencion);
        });
        $(document).on("change","#gasto_monto",function(e){
            var monto = parseInt($(this).val());
            var retencion = 0;
            if ($(this).val()!=''){retencion = Math.round(monto*0.1,0);}
            $('#gasto_retencion').val(retencion);
        });


        $(document).ready(function(){
            $('#rango').daterangepicker();
            $.ajax({
                type: 'post',
                url: '<?php echo site_url()?>/admin/cuentas/tabla_intereses/<?php echo $id?>',
                data: $('#form_guardar-acuerdo').serialize(),
                success: function (data) {
                    $('#lista_intereses').html(data);
                },
                error: function(objeto, quepaso, otroobj) {
                },
            });

        });

        $(document).on("change","select[name=fecha_primer_pago_day],select[name=fecha_primer_pago_month],select[name=fecha_primer_pago_year]",function(e){
            $.ajax({
                type: 'post',
                url: '<?php echo site_url()?>/admin/cuentas/tabla_intereses/<?php echo $id?>',
                data: $('#form_guardar-acuerdo').serialize(),
                success: function (data) {
                    $('#lista_intereses').html(data);
                },
                error: function(objeto, quepaso, otroobj) {
                },
            });
        });

        $(document).on("change",".n_cuotas_intereses",function(e){
            var cuotas = $(this).val();
            $('.total_n_cuotas').each( function() {
                var box = $(this);
                $.ajax({
                    type: 'post',
                    url: '<?php echo site_url()?>/admin/cuentas/cal_cuotas/',
                    data: 'cuotas='+cuotas+'&total='+box.attr('data-total'),
                    success: function (data) {
                        box.html(data);
                    },
                    error: function(objeto, quepaso, otroobj) {
                    },
                });
            });
            return false;
        });
        $(document).on("change","#intereses,.n_cuotas_intereses",function(e){
            $.ajax({
                type: 'post',
                url: '<?php echo site_url()?>/admin/cuentas/cal_interes/x_cuotas',
                data: $('#form_guardar-acuerdo').serialize(),
                success: function (data) {
                    $('#valor_cuota').val(Math.ceil(data));
                    $('#valor_cuota_con_intereses').val(data);
                    $('#valor_cuota_real').val(data);
                },
            });
        });

        $(document).on("keyup","#monto_pagado",function(e){
            var monto_pagado = parseInt($(this).val());
            var honorarios = 0; var monto_remitido = 0;
            if ($(this).val()!=''){honorarios = Math.round(monto_pagado*0.15,0);}
            if ($(this).val()!=''){monto_remitido = Math.round(monto_pagado*0.85,0);}
            $('input[name="honorarios"]').val(honorarios);
            $('input[name="monto_remitido"]').val(monto_remitido);
        });
        $(document).on("change","#valor_cuota",function(e){
            var valor = $(this).val();

            $('#valor_cuota_con_intereses').val(valor);
            $('#valor_cuota_real').val(valor);

            $.ajax({
                type: 'post',
                url: '<?php echo site_url()?>/admin/cuentas/cal_interes/x_valor_cuota',
                data: $('#form_guardar-acuerdo').serialize(),
                success: function (data) {
                    var json_obj = $.parseJSON(data);
                    var cuotas = json_obj.n_cuotas;
                    $('#n_cuotas_real').val( json_obj.n_cuotas_real );
                    $('#n_cuotas_intereses').val(cuotas);

                    $('.total_n_cuotas').each( function() {
                        var box = $(this);
                        $.ajax({
                            type: 'post',
                            url: '<?php echo site_url()?>/admin/cuentas/cal_cuotas/',
                            data: 'cuotas='+cuotas+'&total='+box.attr('data-total'),
                            success: function (data) {
                                box.html(data);
                            },
                        });
                    });
                },
            });
        });
        $(document).on("click","#mostrar-acuerdo-abonos",function(e){
            if ($("#caja-acuerdo-abonos").is(":visible")){
                $("#caja-acuerdo-abonos").hide();
            } else {
                $("#caja-acuerdo-abonos").show();
            }
            return false;
        });

    });

</script>