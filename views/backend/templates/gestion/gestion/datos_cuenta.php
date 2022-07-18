
 <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
 <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

 <?php $rol2_y = date('Y'); if ($cuenta->rol2_y>0){$rol2_y=$cuenta->rol2_y;} if (isset($_REQUEST['rol2_y'])){$rol2_y = $_REQUEST['rol2_y'];} ?>
 <?php $rol1_y = date('Y'); if ($cuenta->rol1_y>0){$rol1_y=$cuenta->rol1_y;}  if (isset($_REQUEST['rol1_y'])){$rol1_y = $_REQUEST['rol1_y'];} ?>
<?php $no_llamar = ''; if ($cuenta->no_llamar>0){$no_llamar=$cuenta->no_llamar;}  if (isset($_REQUEST['no_llamar'])){$no_llamar = $_REQUEST['no_llamar'];} ?>
 
 
<table width="100%" cellpadding="0" cellspacing="0" border="1">
  <tr>
    <td width="50%">
     <legend style="border: 1px groove #ddd !important;padding: 0 1.4em 1.4em 1.4em !important;margin: 0 0 1.5em 0 !important;-webkit-box-shadow:  0px 0px 0px 0px #000;box-shadow:  0px 0px 0px 0px #000;"><fieldset style="font-size: 1.0em !important;font-weight: bold !important;text-align: left !important;width:auto;padding:0 10px;border-bottom:none;">Actualizar datos de la Cuenta:</fieldset><br />
    <table  width="100%">
    <?php echo form_open(site_url().'/admin/gestion/guardar_cuenta/'.$id); ?>
<!--    <tr><td colspan="4"><h3>Actualizar datos de la Cuenta:</h3><br></td></tr>-->
    <tr>
		<td>Mandante:</td>
	    <td>
            <?php echo form_dropdown('id_mandante',$mandantes,$cuenta->id_mandante);?>
            <?php echo form_error('id_mandante','<br><span class="error">','</span>');?>  
        </td> 
	</tr>
	<tr>
        <td>N° Operación:</td>
        <td>
            <input id="noperacion" name="noperacion" type="text"  value="<?php echo $cuenta->operacion;?>" style="width: 100px;" readonly>        </td>
    </tr>


     <tr>
       <td>Marcas Especiales:</td>
        <td>
            <?php echo form_dropdown('id_marca', $marcas_especiales, $cuenta->id_marcas_especiales);?>
           
       </td>
    </tr>

    
    <?php if ($this->session->userdata("usuario_perfil")==1 || $nodo->nombre == 'fullpay'):?>  
    <tr>
	<td>Procurador:</td><td><?php echo form_dropdown('id_procurador', $procuradores, $cuenta->id_procurador);?>
    <?php echo form_error('id_procurador','<br><span class="error">','</span>');?>  </td></tr>
    <?php endif;?>
    
    <tr><td>Receptor:</td><td><?php echo form_dropdown('receptor', $receptores, $cuenta->receptor);?>
    <?php echo form_error('receptor','<br><span class="error">','</span>');?>  </td></tr>

    <?php if($nodo->nombre == 'fullpay'): ?>
     <tr>
        <td>Castigo:</td>
      <td><?php echo form_dropdown('id_castigo', $castigados, $cuenta->id_castigo);?>
      <?php echo form_error('id_castigo','<br><span class="error">','</span>');?>  </td>
     </tr>    
    <?php endif;?>
    
    <?php //if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
    <tr>
        <td>Tipo Demanda:</td>
        <td>
           <?php echo form_dropdown('tipo_demanda', array(''=>'-- Selecionar --','1'=>'Propia','0'=>'Cedida'),$cuenta->tipo_demanda )?>
           <?php echo form_error('tipo_demanda','<br><span class="error">','</span>');?>        </td>
    </tr>
    <tr>
        <td>Exhorto:</td>
        <td>
            <?php echo form_dropdown('exorto', array(''=>'-- Selecionar --','1'=>'Con exorto','0'=>'Sin exorto'),$cuenta->exorto )?>
             <?php echo form_error('exorto','<br><span class="error">','</span>');?>        </td>
    </tr>	
	
	<tr>
        <td>Jurisdiccion Exhorto:</td>
		<td>				
            <?php echo form_dropdown('id_distritoExh', $jurisdiccion, $cuenta->id_distrito_ex ,'id="id_distritoExh"');?>
            <?php echo form_error('id_distritoExh','<br><span class="error">','</span>');?>

<!--
            <select id="id_distritoExh" name="id_distritoExh">
                <option value="0">--Seleccionar--</option>
                <?php 
                  //  foreach ($distritos as $i) {
                   //     echo '<option value="'. $i->id .'">'. $i->jurisdiccion .'</option>';
                 //   }
                ?>
            </select>

-->
                
        </td>


    </tr> 
	
    <tr>
        <td>Juzgado Exhortado:</td>
        <td>
		
			<div id="ajax_id_exhor">
				<?php echo form_dropdown('id_tribunal_ex_intermedio', $tribunales, $cuenta->id_tribunal_ex, 'id="id_tribunal_ex_intermedio"' );?>
			</div>
			<input type="hidden" id="id_tribunal_exw" name="id_tribunal_exw" value="<?php echo $cuenta->id_tribunal_ex;?>" >

<!--
          <select id="id_tribunal_ex_intermedio" name="id_tribunal_ex_intermedio" class="form-control" style="width:350px">
       </select>

-->
			
		</td>
    </tr>

    <tr>
        <td>Rol Exhorto:</td>
        <td>
             <?php echo form_dropdown('letra_rol_e', $tipo_causa, $cuenta->letraE);?>
            <input id="rolE" name="rolE" min="1" type="number"  value="<?php echo $cuenta->rolE;?>" style="width: 75px;">  
                 <?php echo form_dropdown('anio_ex', $anio_rol, $cuenta->anioE);?>
               </td>
    </tr>
	
    <tr>
       <td>Estado de la Cuenta:</td>
        <td>
            <?php echo form_dropdown('id_estado_cuenta', $estados_cuenta, $cuenta->id_estado_cuenta);?>
            <?php echo form_error('id_estado_cuenta','<br><span class="error">','</span>');?>

            <div id="suspension"<?php if ($cuenta->id_estado_cuenta!=3){echo ' style="display:none"';}?>>
            	<label>Suspendido hasta:</label>
            	<input type="text" class="datepicker" name="fecha_termino_suspension" value="<?php if ($cuenta->fecha_termino_suspension!='' && $cuenta->fecha_termino_suspension!='0000-00-00'){ echo date('d-m-Y',strtotime($cuenta->fecha_termino_suspension));}?>">
				<?php echo form_error('fecha_termino_suspension','<br><span class="error">','</span>');?>            </div>        </td>
    </tr>
    <tr>
        <td>Fecha Asignación:</td>
        <td>
            <input type="text" readonly name="fecha_asignacion" value="<?php if ($cuenta->fecha_asignacion!='0000-00-00'){ echo date('d-m-Y',strtotime($cuenta->fecha_asignacion));} ?>"><?php echo form_error('fecha_asignacion','<br><span class="error">','</span>');?>        </td>
    </tr>



    <!--  ################# NUEVOS CAMPOS ################################# -->

      <tr>
        <td>Dias Mora Asignación:</td>
        <td>
            <input id="dias_mora_asig" name="dias_mora_asig" type="text"  value="<?php echo $cuenta->dias_mora_asig;?>" style="width: 100px;" readonly>        </td>
      </tr>

        <tr>
        <td>Licitación:</td>
        <td>
            <input id="licitacion" name="licitacion" type="text"  value="<?php echo $licitacion = substr($cuenta->operacion, 0, 4);?>" style="width: 100px;" readonly>        </td>
      </tr>


              <tr>
        <td>Fecha Primer Vcto Impago:</td>
        <td>   
			<input type="text"  name="fecha_primer_vcto" value="<?php if ($cuenta->fecha_primer_vcto!='0000-00-00'){ echo date('d-m-Y',strtotime($cuenta->fecha_primer_vcto));}?>" readonly><?php echo form_error('fecha_primer_vcto','<br><span class="error">','</span>');?>      
             </td>
      </tr>


       <tr>
        <td>Tipo Deudor:</td>
        <td>
            <input id="tipo_deudor" name="tipo_deudor" type="text"  value="<?php echo $cuenta->tipo_deudor;?>" style="width: 100px;" >   
        </td>
      </tr>


       <tr>
        <td>Dias Mora:</td>
        <td>
              <input name="dias_mora" type="text" value="<?php echo $cuenta->dias_mora;?>">
             <?php echo form_error('dias_mora','<br><span class="error">','</span>');?>
             Tramo Mora:
             <input id="tramo_mora" name="tramo_mora" type="text"  value="<?php echo $cuenta->tramo_mora;?>" style="width: 100px;"> 
               </td>
    </tr>

   <!--  ################# NUEVOS CAMPOS ################################# -->



    <!--
    <tr>
        <td>Fecha escritura personería:</td>
        <td>
            <input type="text" class="datepicker" name="fecha_escritura_personeria" value="<?php //if ($cuenta->fecha_escritura_personeria!='0000-00-00'){ echo date('d-m-Y',strtotime($cuenta->fecha_escritura_personeria));}?>"><?php echo form_error('fecha_escritura_personeria','<br><span class="error">','</span>');?>        </td>
    </tr>

-->


<!--
    <tr>
        <td>Notaría Personería:</td>
        <td>
            <input name="notaria_personeria" type="text" value="<?php echo $cuenta->notaria_personeria;?>">
             <?php echo form_error('notaria_personeria','<br><span class="error">','</span>');?>        </td>
    </tr>

    -->
    
     <?php // if($nodo->nombre == 'fullpay'){ ?>

     <!--
    <tr>
        <td>Número de Contrato:</td>
        <td>
            <input name="numero_contrato" type="text" value="<?php echo $cuenta->numero_contrato;?>">
             <?php echo form_error('numero_contrato','<br><span class="error">','</span>');?>        </td>
    </tr>

    -->
    <?php // } ?>
    
    <?php if($nodo->nombre == 'swcobranza') :?>
    <td>
	  
		  <?php $no_llamar_check = FALSE; if ($no_llamar == '1'){$no_llamar_check = TRUE;}?>
          <?php echo form_checkbox(array('name'=>'no_llamar','class'=>'check','style'=>'width:25px'),'1',$no_llamar_check);?> No
          <?php echo form_error('no_llamar', '<span class="error" style="margin-left:165px;">', '</span>');?>
     
      llamar:</td>
    <?php endif;?>   
    
    <div class="clear"></div>
    <?php if($nodo->nombre == 'swcobranza') { ?>
    <tr>
        <td>Notificaciones:</td>
           <td>
        <?php $notificaciones_check = FALSE; if ($cuenta->notificaciones == '1'){$notificaciones_check = TRUE;}?>
		<?php echo form_checkbox(array('name'=>'notificaciones','class'=>'check','style'=>'width:25px'),'S',$notificaciones_check);?> Activar notificaciones
        <div class="clear"></div>
		<?php echo form_error('notificaciones', '<span class="error" style="margin-left:165px;">', '</span>');?>
            <?php } ?>         </td>
    </tr>
   
   <?php if($nodo->nombre == 'swcobranza') { ?>
    <tr>
        <td>Medio por el que nos contacta el cliente:</td>
      <td><?php echo form_dropdown('medio_contacto', $medios_contactos,$cuenta->medio_contacto);?>
      <?php echo form_error('medio_contacto','<br><span class="error">','</span>');?>  </td>
     </tr>
     
    <tr>
        <td>Otros:</td>
        <td>
            <input name="medio_contacto_otro" type="text" value="<?php echo $cuenta->medio_contacto_otro;?>">
             <?php echo form_error('medio_contacto_otro','<br><span class="error">','</span>');?>        </td>
    </tr>
     
    <tr>
        <td>Canal por el que se informa de la deuda:</td>
      <td><?php echo form_dropdown('medio_informado',$medios_informados, $cuenta->medio_informado);?>
      <?php echo form_error('medio_informado','<br><span class="error">','</span>');?>  </td>
    </tr>
    <tr> 
    <td>Otros:</td>
    <td>
            <input name="medio_informado_otro" type="text" value="<?php echo $cuenta->medio_informado_otro;?>">
             <?php echo form_error('medio_informado_otro','<br><span class="error">','</span>');?>        </td>
    </tr>
     
     <?php } ?>
     
    
    
    <?php //endif?>
    
    <tr><td colspan="2"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
    <?php echo form_close();?>
</table>
</legend>
    </td>
    <td  width="50%" >
      <legend style="border: 1px groove #ddd !important;padding: 0 1.4em 1.4em 1.4em !important;margin: 0 0 1.5em 0 !important;-webkit-box-shadow:  0px 0px 0px 0px #000;box-shadow:  0px 0px 0px 0px #000;"><fieldset style="font-size: 1.0em !important;font-weight: bold !important;text-align: left !important;width:auto;padding:0 10px;border-bottom:none;">Roles de la Cuenta:</fieldset><br />
        <?php echo form_open(site_url().'/admin/gestion/guardar_juzgado/'.$id); ?>
    <table width="100%">
      <tr>
        <td>Distrito</td>
        <td>
		<?php //echo form_dropdown('id_distritonew', $distritos);?> 
    <?php //echo form_error('id_distritonew','<br><span class="error">','</span>');?>  

    <?= form_open(base_url().'index.php/gestion/hacerAlgo'); ?>
           <select id="id_distritonew" name="id_distritonew">
                <option value="0">--Seleccionar--</option>
                <?php 
                    foreach ($distritos as $i) {
                        echo '<option value="'. $i->id .'">'. $i->jurisdiccion .'</option>';
                    }
                ?>
            </select>


    </td>
      </tr>


         
        

      <!-- , null,'id="distrito"'  -->


      <tr>
        <td>Juzgado</td>
        <td>

       <select id="id_tribunalnew" name="id_tribunalnew" class="form-control" style="width:350px">
       </select>
	
      <?php //echo form_dropdown('id_tribunalnew', $tribunales);?> 
		
			<?php //echo form_error('id_tribunalnew','<br><span class="error">','</span>');?> </td>
      </tr>

      <!-- ,null,'id="tribunal"' -->

    <tr>
        <td>Rol</td>
        <td>
          <?php echo form_dropdown('id', $tipo_causa);?>
            <input id="rolE" min="1" name="rolE" type="number" style="width: 75px;">     
            <?php echo form_dropdown('anio', $anio_rol);?>
        </td>
    </tr>
<tr><td colspan="2"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
    <?php echo form_close();?>
    <tr><td colspan="2"><br></td></tr>
        <tr><td colspan="2"><br></td></tr>
    </table>
    
    <div id="box-form-pagares"> </div>
<table  width="100%" border="1"> 
  <tr><td colspan="4"><h3>Listado de Juzgados:</h3><br></td></tr>
  <tr>
      <td colspan="4">
      <table class="grilla" width="100%">
          <tr class="titulos-tabla">
              <td>#</td>
              <td>Distrito</td>
              <td>Juzgado</td>
              <td>Rol</td>
			  <td>Accion</td>
           </tr>

		  <?php if (count($juzgados)>0):?>
          
			  <?php foreach ($juzgados as $key=>$juzgado):?>
				  <tr>			  
					  <td>#<?php echo $juzgado->Id;?> 
            </td>
					  <td><?php echo $juzgado->jurisdiccion;?></td>
					  <td><?php echo $juzgado->tribunal;?></td>
            <td><?php echo $juzgado->tipo_causa;?><?php echo '-';?><?php echo $juzgado->Rol;?><?php echo '-';?><?php echo $juzgado->anio;?></td> 
			
           <!-- <td><?php echo $juzgado->letraC;?></td> -->                       
					  <td><a href="<?php echo site_url('admin/gestion/eliminar_juzgado/'.$id.'/'.$juzgado->Id);?>" class="delete">Eliminar</a></td>
				   </tr>
			  <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="7">No hay Roles ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>
    
</legend>
    </td>
  </tr>
</table>


<script type="text/javascript">

/*



	function buscarjuris(id_distrito)
	{
		
		$.ajax({	
		  type: 'GET',
		  url: '<?php //echo site_url();?>/admin/tribunales/anidadoE/'+id_distrito,
		  success: function(data) {
			  $("#ajax_id_exho").html(data);
		  },
		 
		});		
	}


$(window).load(function() {
	
	$(document).on("change", "select[name='id_distritonew']", function(event){
		var id_distrito = $(this).val();
		$.ajax({	
		  type: 'GET',
		  url: '<?php //echo site_url();?>/admin/tribunales/anidado/'+id_distrito,
		  success: function(data) {
			  $("#ajax_id_distritoadd").html(data);
		  },
		 
		});
	}); 
	
	

	$(document).on("change", "select[name='id_distritoExh']", function(event){
		var id_distrito = $(this).val();
		$.ajax({	
		  type: 'GET',
		  url: '<?php //echo site_url();?>/admin/tribunales/anidadoE/'+id_distrito,
		  success: function(data) {
			$("#ajax_id_exhor").visible();			
			$("#ajax_id_exhor").html(data);
		  },
		 
		});
	}); 
	
	$(document).on("change", "select[name='id_tribunal_ex_intermedio']", function(event){
		$("#id_tribunal_exw").val($(this).val());		
	}); 

(function($) {
    $.fn.invisible = function() {
        return this.each(function() {
            $(this).css("visibility", "hidden");
        });
    };
    $.fn.visible = function() {
        return this.each(function() {
            $(this).css("visibility", "visible");
        });
    };
}(jQuery));	

});
function MM_jumpMenu(targ,selObj,restore){ //v3.0
	alert(selObj.options[selObj.selectedIndex].value);
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

*/
</script>


<script type="text/javascript">   
            $(document).ready(function() {
                $("#id_distritoExh").change(function() {
                    $("#id_distritoExh option:selected").each(function() {
                        idEstado = $('#id_distritoExh').val();
                        $.post("<?php echo base_url(); ?>index.php/admin/gestion/fillCiudades", {
                            idEstado : idEstado
                        }, function(data) {
                            $("#id_tribunal_ex_intermedio").html(data);
                        });
                    });
                });

            });
</script>


<script type="text/javascript">   
            $(document).ready(function() {
                $("#id_distritonew").change(function() {
                    $("#id_distritonew option:selected").each(function() {
                        idEstado = $('#id_distritonew').val();
                        $.post("<?php echo base_url(); ?>index.php/admin/gestion/fillCiudades", {
                            idEstado : idEstado
                        }, function(data) {
                            $("#id_tribunalnew").html(data);
                        });
                    });
                });

            });
</script>