 <?php $this->load->view('backend/templates/gestion/gestion/pagares_form'); ?>
<div id="box-form-pagares"> </div>
<table class="stable" width="100%"> 
  <tr><td colspan="4"><h3>Listado de Pagarés:</h3><br></td></tr>
  <tr>
      <td colspan="4">
      <table class="stable grilla" width="100%">
          <tr class="titulos-tabla">
           <td>#</td>

              <?php if($nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ){ ?>
               <td>Fecha Asignación</td>
              <?php } ?>


              <?php if($nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ){ ?>
              <td>Fecha Suscripción</td>
              <?php } ?>


              <td>Tipo de Producto</td>
               <td>Nº</td>
               <td>&nbsp; &nbsp; &nbsp;</td>
               <td>Monto</td>
               <td>Fecha Vencimiento</td>
               
              <?php if($nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ){ ?>
              <td>Tasa Interes Anual</td>
              <td>Días Transcurridos</td>
              <td>Intereses</td>
			  <?php } ?> 
               
              <?php if($nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ){ ?>
               <td> Tasa de Interés</td>
               <td> Nº de Cuotas</td>
               <td> Valor primera cuota</td>
               <td> Valor última cuota</td>
               <td> Venc. primera cuota</td>
               <td> Venc. restantes cuotas</td>
               <td> Aval</td>
               <td> Ult. cuota pagada</td>
               <td> Fecha última cuota pagada</td>
               <td> Valor última cuota pagada </td>
               <td> Saldo Deuda </td>
              <?php } ?>
              
              <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>Gestión</td>
                <?php endif;?>
           </tr>

          <?php if (count($pagares)>0):?>
          <?php foreach ($pagares as $key=>$pagare):?>
              <tr>
              <td>#<?php echo $pagare->id;?></td>

                  <?php if($nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ){ ?>
              <td style="text-align:right;"><?php if ($pagare->fecha_asignacion!='' && $pagare->fecha_asignacion!='1969-12-31'){ echo date('d-m-Y',strtotime($pagare->fecha_asignacion));}else{ echo '-'; } ?></td>
                  <?php } ?>

               <?php if($nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ){ ?>
                 <td style="text-align:center;"><?php if ($pagare->fecha_suscripcion!='' && $pagare->fecha_suscripcion!='1969-12-31'){ echo date('d-m-Y',strtotime($pagare->fecha_suscripcion));} else { echo '-'; } ?> </td>
               <?php } ?>


                  <td><?php echo $pagare->tipo;?></td>
            <td><?php echo $pagare->n_pagare;?></td>
              <td></td>
              <td style="text-align:right;"><?php if ($nodo->nombre=='fullpay'){ echo $pagare->monto_deuda.' UF';} else { echo number_format($pagare->monto_deuda,0,',','.');}?></td>
              <td style="text-align:right;"><?php if ($pagare->fecha_vencimiento!='' && $pagare->fecha_vencimiento!='0000-00-00' && $pagare->fecha_vencimiento!='1969-12-31'){ echo date('d-m-Y',strtotime($pagare->fecha_vencimiento));} else { echo '-'; } ?></td>
              
              <?php if($nodo->nombre == 'fullpay'  && $nodo->activo == 'S' ){ ?>
              <td><?php echo $pagare->tasa_interes;?></td>
              <td><?php echo $pagare->numero_cuotas;?></td>

              <td><?php if ($pagare->valor_primera_cuota!='' && $pagare->valor_primera_cuota!='0000-00-00' && $pagare->valor_primera_cuota!='0' && $pagare->valor_primera_cuota!='1969-12-31'){ echo date('d-m-Y',strtotime($pagare->valor_primera_cuota)); } else { echo '-';  } ?></td>
                  <td><?php if ($pagare->valor_ultima_cuota!='' && $pagare->valor_ultima_cuota!='0000-00-00' && $pagare->valor_ultima_cuota!='0' && $pagare->valor_ultima_cuota!='1969-12-31'){ echo date('d-m-Y',strtotime($pagare->valor_ultima_cuota)); } else { echo '-';  } ?></td>


        <td><?php if ($pagare->vencimiento_primera_cuota!='0' && $pagare->vencimiento_primera_cuota!='' && $pagare->vencimiento_primera_cuota!='0000-00-00' && $pagare->vencimiento_primera_cuota!= '1969-12-31'){ echo date('d-m-Y',strtotime($pagare->vencimiento_primera_cuota)); } else { echo '-';  } ?></td>
        <td><?php if ($pagare->vencimiento_restantes_cuotas!='1969-12-31' && $pagare->vencimiento_restantes_cuotas!='0' && $pagare->vencimiento_restantes_cuotas!='' && $pagare->vencimiento_restantes_cuotas!='0000-00-00'){ echo date('d-m-Y',strtotime($pagare->vencimiento_restantes_cuotas));} else { echo '-'; } ?></td>

              <td><?php echo $pagare->nombre_aval;?></td>
              <td><?php if ($pagare->ultima_cuota_pagada!='' && $pagare->ultima_cuota_pagada!='0000-00-00' && $pagare->ultima_cuota_pagada!='0' && $pagare->ultima_cuota_pagada!='1969-12-31'){ echo date('d-m-Y',strtotime($pagare->ultima_cuota_pagada)); } else { echo '-';  } ?></td>

                  <td><?php if ( $pagare->fecha_pago_ultima_cuota!='0000-00-00' && $pagare->fecha_pago_ultima_cuota!='' && $pagare->fecha_pago_ultima_cuota!='1969-12-31' && $pagare->fecha_pago_ultima_cuota!='0' ){ echo date('d-m-Y',strtotime($pagare->fecha_pago_ultima_cuota)); } else { echo '-'; } ?></td>

                  <td><?php if ( $pagare->valor_ultima_cuota_pagada!='0000-00-00' && $pagare->valor_ultima_cuota_pagada!='' && $pagare->valor_ultima_cuota_pagada!='1969-12-31' && $pagare->valor_ultima_cuota_pagada!='0' ){ echo date('d-m-Y',strtotime($pagare->valor_ultima_cuota_pagada)); } else { echo '-'; } ?></td>

               <td><?php echo $pagare->saldo_deuda;?></td>
			  <?php } ?>

              <?php if($nodo->nombre == 'swcobranza'  && $nodo->activo == 'S' ){ ?>
              <td> <?php if($pagare->tasa_interes_anual > 0 ) { echo $pagare->tasa_interes_anual.'%'; }  ?></td>
              <td><?php echo $pagare->dias_transcurridos;?></td>
             <?php  $pag = '' ?>
			 <?php  $pag = $pagare->dias_transcurridos; ?>
             <?php $tasa_interes_anual = ''; ?>
			 <?php $tasa_interes_anual = $pagare->tasa_interes_anual; ?>
             <?php  $monto_deuda = $pagare->monto_deuda; ?>
             <?php $div_pag = ''; ?>
			 <?php $div_pag_t = ''; ?>
             <?php $div_pag_t_m = '' ?>

             <?php if($pag >0 && $monto_deuda > 0 && $tasa_interes_anual > 0){ ?>
			 <?php $div_pag = ($pag/365); ?>
             <?php $div_pag_t = $div_pag * $tasa_interes_anual; ?>
             <?php $div_pag_t_m = $div_pag_t * $monto_deuda ?>
             <?php }else { $div_pag_t_m = '-'; } ?>
             
             <td><?php echo $div_pag_t_m; ?></td>
              <?php } ?>
			 
              <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>
                <a href="<?php echo site_url('admin/gestion/editar_pagares/'.$id.'/'.$pagare->id);?>" class="edit"  data-id="<?php echo $pagare->id;?>" data-gtab="pagares" >Editar</a>
                <a href="<?php echo site_url('admin/gestion/eliminar_pagares/'.$id.'/'.$pagare->id);?>" class="delete">Eliminar</a></td>
                <?php endif;?>
               </tr>
             
          <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="7">No hay pagarés ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>