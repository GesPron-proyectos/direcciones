 <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
 <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<style>
#mask{position:absolute;background:rgba(0,0,0,.3);display:none;height:100%;width:100%;left:0;top:0;z-index:999998}
.preloader {
  width: 90px;
  height: 90px;
  margin: 25% auto;
  border: 10px solid #eee;
  border-top: 10px solid #666;
  border-radius: 50%;
  animation-name: girar;
  animation-duration: 1s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
<?php
	$sistema_p = ''; if (isset($_REQUEST['sistema_p'])){$sistema_p = $_REQUEST['sistema_p'];}
	$procurador_p = ''; if (isset($_REQUEST['procurador_p'])){$procurador_p = $_REQUEST['procurador_p'];}
	//print_r($_REQUEST); die;
?>
<div id="mask"><div class="preloader"></div></div>
<div class="table-m-sep">

  <div class="table-m-sep-title">

  <h2><strong>Causas (<?php echo number_format($total,0,',','.');?>)</strong></h2>

  </div>

</div>

<div class="agregar-noticia">
  <br/>
    <div class="clear"></div>
<div class="">
	<td width="50%" >
		<legend style="border: 1px groove #ddd !important;padding: 0 1.4em 1.4em 1.4em !important;margin: 0 0 1.5em 0 !important;-webkit-box-shadow:  0px 0px 0px 0px #000;box-shadow:  0px 0px 0px 0px #000;"><fieldset style="font-size: 1.0em !important;font-weight: bold !important;text-align: left !important;width:auto;padding:0 10px;border-bottom:none;">Configuracion control de envios:</fieldset><br />
        <?php echo form_open(site_url().'/admin/control_envios/guardar_configuracion/'.$id); ?>
    <table width="100%">
      <tr>
        <td>Procurador</td>
        <td>
        <?php echo form_dropdown('procurador', $procurador, $id, "id='procurador'");?>
        <?php echo form_error('procurador');?>
      </tr>
      <tr>
        <td>Designado</td>
        <td>
        <?php echo form_dropdown('designado', $procurador, $id, "id='designado'");?>
        <?php echo form_error('designado');?>
      </tr>
      <tr>
        <td>Sistema</td>
        <td>
          <select id="sistema" name="sistema">
              <option value="0">Seleccionar</option>
              <option value="sup">SUPERIR</option>
              <option value="prop">PROPIAS</option>
              <option value="cat">CAT</option>
              <option value="cae">CAE</option>
			  <option value="na">N/A</option>
              <!--<option value="cae-na">CAE - N/A</option>
              <option value="sup-na">SUP - N/A</option>-->
              <!--<option value="na">N/A</option>-->
              <option value="apel">APELACIONES</option>
          </select>
        </td>
      </tr>
	  <tr id="trtipoc" style="display:none;">
        <td>Tipo Causa</td>
        <td>
          <select id="tipoproc" name="tipoproc">
              <option value="1">CAUSAS LIQUIDACIÓN</option>
              <option value="2">JUICIOS COMPLEMENTARIOS</option>
              <!--<option value="3">CAUSAS N/A</option>-->
          </select>
        </td>
      </tr>
	  <tr id="trjuicio" style="display:none;">
        <td>Tipo Juicio</td>
        <td>
          <select id="tipojui" name="tipojui">
              <option value="0">Seleccionar</option>
              <option value="1">CIVIL</option>
              <option value="2">LABORAL</option>
              <option value="3">COBRANZA</option>
              <option value="4">PENAL</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Mandante</td>
        <td>
          <select id="mandante" name="mandante">
              <option value="0">Seleccionar</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Estado</td>
        <td>
          <select id="estado" name="estado">
              <option value="0">Seleccionar</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Juridiscción</td>
        <td>
           <select id="id_distritonew" name="id_distritonew">
                <option value="0">Seleccionar</option>
                <?php 
                    foreach ($distritos as $i) {
                        echo '<option value="'. $i->id .'">'. $i->jurisdiccion .'</option>';
                    }
                ?>
            </select>
        </td>
      </tr>
	  <tr>
        <td>Comuna</td>
        <td>
			<select id="id_comuna" name="id_comuna">
				<option value="0">Seleccionar</option>
			</select>
        </td>
      </tr>
      <tr>
        <td>Tribunal</td>
        <td>
			<select id="tribunal" name="tribunales[]" class="tribunales" onmousedown="multipleSelTribunal(this)" multiple style="height:100px;width:500px">
			</select>
        </td>
		<td></td>
      </tr>
      <tr>
        <td>Letra Rol</td>
        <td>
          <select id="tipoc" name="tipoc">
              <option value="0">Seleccionar</option>
              <option value="C">C</option>
              <option value="E">E</option>
          </select>
        </td>
      </tr>
	  <tr id="tr_gestor" style="display:none;">
        <td>Gestor</td>
        <td>
          <select id="gestor" name="gestor">
              <option value="0">Seleccionar</option>
              <?php 
                    foreach ($gestores as $k => $v) {
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }
                ?>
          </select>
        </td>
      </tr>
	  <tr>
        <td>Grupo Correo</td>
        <td>
          <select id="grupo" name="grupo">
              <option value="0">Seleccionar</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
          </select>
        </td>
      </tr>
<tr><td colspan="2"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
	<input type="hidden" id="id_config" name="id_config"/>
    <?php echo form_close();?>
    <tr><td colspan="2"><br></td></tr>
        <tr><td colspan="2"><br></td></tr>
    </table>
</div><!-- campo -->
<div class="">
    <h3 style="text-decoration:underline;margin-bottom:-15px;">Cambio entre procuradores</h3>
    <table width="600">
      <?php echo form_open(site_url().'/admin/control_envios/'); ?>
      <tr>
        <td>
          <label>Sistema</label>
          <select id="sistema_p" name="sistema_p">
              <option value="0">Seleccionar</option>
              <option value="sup" <?php if($sistema_p == 'sup') echo "selected"; ?>>SUPERIR</option>
              <option value="prop" <?php if($sistema_p == 'prop') echo "selected"; ?>>PROPIAS</option>
              <option value="cat" <?php if($sistema_p == 'cat') echo "selected"; ?>>CAT</option>
              <option value="cae" <?php if($sistema_p == 'cae') echo "selected"; ?>>CAE</option>
          </select>
        </td>
        <td>
          <label>Procurador</label>
          <select id="procurador_p" name="procurador_p">
              <option value="0">Seleccionar</option>
              <?php foreach($procurador as $key=>$val): ?>
              <option value="<?php echo $key; ?>" <?php if($key == $procurador_p) echo 'selected'; ?>><?php echo $val; ?></option>
              <?php endforeach;?>
          </select>
        </td>
        <td>
          <input type="submit" value="Buscar" style="margin-top:10px;height:27px;cursor:pointer;">
        </td>
      </tr>
      <?php echo form_close();?>
      <tr>
        <td colspan="2">
          <hr/>
          <label>Asignar a:</label>
          <select id="procurador_a" name="procurador_a" style="width:350px;">
              <option value="0">Seleccionar</option>
              <?php foreach($procurador as $key=>$val): ?>
              <option value="<?php echo $key; ?>" <?php if($key == $_POST['procurador_a']) echo 'selected'; ?>><?php echo $val; ?></option>
              <?php endforeach;?>
          </select>
          <input type="hidden" id="id_configs">
        </td>
        <td>
          <hr style="position:absolute;margin:-5px 0 0 -1px;width:132px;"/>
          <input id="btn-asignar" type="button" value="Asignar" style="margin-top:10px;height:27px;cursor:pointer;">
        </td>
      </tr>
  </table>
</div>

<div class="clear height"></div>
</div>

<?php if (count($lists)>0): ?>
<?php echo $this->pagination->create_links(); ?>
<div class="clear"></div>
<div class="tabla-listado">

<div class="content_tabla">
 <?php include APPPATH.'views/backend/templates/cuentas/list_tabla_control_envios.php';?>
</div>

<?php $colspan=2;//$this->load->view('backend/templates/mod/multiselect');?>

</div>

<?php endif;?>  

<?php echo $this->pagination->create_links(); ?>

<script type="text/javascript">
	function multipleSelTribunal(){
		$(".tribunales option").on('mousedown', function(e){
		  var $this = $(this),
			  that = this,
			  scroll = that.parentElement.scrollTop;

		  e.preventDefault();

		  //last_valid_selection = $(this).val();

		  $this.prop('selected', !$this.prop('selected'));

		  setTimeout(function() {
			  that.parentElement.scrollTop = scroll;
		  }, 0);

		  //return false;
		});
	}
	$(document).ready(function() {
		$("#sistema, #tipoproc, #tipojui").change(function(){ //alert('sss');
			if($("#sistema").val() == 'sup')
				$("#trtipoc").css({'display': 'table-row'});
			else{
				$("#trtipoc, #trjuicio").css({'display': 'none'});
			}
			
			if($("#sistema").val() == 'cat')
				$("#tr_gestor").css({'display': 'table-row'});
			else{
				$("#tr_gestor").css({'display': 'none'});
			}
			
			if($('#tipoproc').val() == 1)
				$("#trjuicio").css({'display': 'none'});
			else
				$("#trjuicio").css({'display': 'table-row'});
			
			$("#mask").css({"display":"block", "height": $(document).height()});
			var actionData = { 'sistema': $('#sistema').val(), 'tipoproc': $('#tipoproc').val(), 'tipojui': $('#tipojui').val() };
			$.ajax({
			  type: "POST",
			  dataType: 'json',
			  data: actionData,
			  url: "<?php echo base_url(); ?>index.php/admin/control_envios/cargarSelects",
			  success: function(response){
				$("#mask").css({"display":"none"});
				var data = response.result;
				$("#mandante, #estado, #id_comuna, #tribunal").empty();
				$("#mandante, #estado, #id_comuna").append("<option value='0'>Seleccionar</option>");
				if(data.mandantes){
					$.each(data.mandantes, function(id, value){
						var opt = '<option value="'+value+'">'+value+'</option>';
						$("#mandante").append(opt);
					});
				}
				if(data.estados){
					$.each(data.estados, function(id, value){
						var opt = '<option value="'+value+'">'+value+'</option>';
						$("#estado").append(opt);
					});
				}
				if(data.comunas){
					$.each(data.comunas, function(id, value){
						var opt = '<option value="'+id+'">'+value+'</option>';
						$("#id_comuna").append(opt);
					});
				}
				if(data.jurisdicciones){
					$("#id_distritonew").empty();
					$("#id_distritonew").append("<option value='0'>Seleccionar</option>");
					$.each(data.jurisdicciones, function(id, value){
						var opt = '<option value="'+id+'">'+value+'</option>';
						$("#id_distritonew").append(opt);
					});
				}
				if(data.letras){
					$("#tipoc").empty();
					$("#tipoc").append("<option value='0'>Seleccionar</option>");
					$.each(data.letras, function(id, value){
						var opt = '<option value="'+id+'">'+value+'</option>';
						$("#tipoc").append(opt);
					});
				}
			  }
			});
		});
		
		$("#id_distritonew").change(function(){
			if($("#sistema").val() != 0){
				$("#comuna").val(0);
				$("#mask").css({"display":"block", "height": $(document).height()});
				var actionData = { 'id_jurisdiccion': $(this).val(), 'sistema': $("#sistema").val(), 'idtc': $("#tipoproc").val() };
				$.ajax({
				  type: "POST",
				  dataType: 'json',
				  data: actionData,
				  url: "<?php echo base_url(); ?>index.php/admin/control_envios/cargarTribunales",
				  success: function(response){
					$("#mask").css({"display":"none"});
					var data = response.result;
					$("#tribunal").empty();
					//$("#tribunal").append("<option value='0'>Seleccionar</option>");

					$.each(data.tribunales, function(id, value){
						var opt = '<option value="'+value.id+'">'+value.name+'</option>';
						$("#tribunal").append(opt);
					});
				  }
				});
			}
			else{
				alert("Debe especificar el sistema");
				$(this).val(0);
			}
		});
		
		$("#btn-asignar").click(function(e){
			var sistema = $("#sistema_p").val();
			var id_configs = $("#id_configs").val();
			var id_asignac = $("#procurador_a").val();

			if(id_configs == '')
			  alert('Debe seleccionar al menos un registro');
			else if(id_asignac == 0)
			  alert('Debe seleccionar un procurador para asignar');
			else{
			  $("#mask").css({"display":"block", "height": $(document).height()});
			  var actionData = { 'id_configs': id_configs, 'id_asigna': id_asignac, 'sistema': sistema };
			  $.ajax({
				type: "POST",
				dataType: 'json',
				data: actionData,
				url: "<?php echo base_url(); ?>index.php/admin/control_envios/asignar",
				success: function(response){
				  $("#mask").css({"display":"none"});
				  var d = response.result;
				  if(d == 1)
					 window.location.href = "<?php echo base_url(); ?>index.php/admin/control_envios";
				  else
					alert('Ha ocurrido un error');
				}
			  });
			}
		});
		
		$("#checkAll").click(function(){
          if($(this).is(':checked')){
            $("#tb_configs tbody tr td:nth-child(1) input[type=checkbox]").each(function(){
              var id = $(this).val();
              if(id != 'on'){ 
                  var text = $("#id_configs").val();
                  $("#id_configs").val(id+','+text);
                  $(this).prop('checked', true);
              }
            });

          }
          else{
            $("#id_configs").val('');
            $("#tb_configs input[type=checkbox]").prop('checked', false);
          }
        });

	});
	
	function verCheck(x){
      var tds = 'ok';
      var id = $(x).val();
      var text = $("#id_configs").val();
      $("#tb_configs tbody input[type=checkbox]").each(function(){
        if(!$(this).is(':checked'))
          tds='nok';
      });

      if($(x).is(':checked')){
        if(text != '')
          $("#id_configs").val(id+','+text);
        else
          $("#id_configs").val(id);
      }
      else{
        var aux = id.split(',');
        if(aux.length>1)
          text = text.replace(id+',', '');
        else{
          text = text.replace(id, '');
          text = text.replace(',', '');
        }
        $("#id_configs").val(text);
      }
      
      if(tds == 'ok'){
        $("#checkAll").prop('checked', true);
      }else{
        $("#checkAll").prop('checked', false);
      }
      
    }
</script>