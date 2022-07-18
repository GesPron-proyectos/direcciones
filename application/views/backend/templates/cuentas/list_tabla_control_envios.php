<?php //include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

<?php $tipoproc = array(1 => 'CAUSAS LIQUIDACIÓN', 2 => 'JUICIOS COMPLEMENTARIOS'); ?>
<?php if (count($lists)>0): ?>

<table id="tb_configs" class="listado" width="100%">
<tr class="menu" style="line-height:20px; height:50px;">
	<td style="width:30px;"><input type="checkbox" id="checkAll" value="" name="checkAll" style="width:25px;"/></td>
	<td>PROCURADOR</td>
	<td>DESIGNADO</td>
	<td>SISTEMA</td>
	<td>TIPO CAUSA</td>
	<td>MANDANTE</td>
	<td>ESTADO</td>
	<td>JURISDICCIÓN</td>
	<td>COMUNA</td>
	<td>TRIBUNAL</td>
	<td>LETRA ROL</td>
	<td>GESTOR</td>
	<td>GRUPO</td>
	<td width="80">GESTION</td>
</tr>
<?php $i=1; $check_id=1;foreach ($lists as $key=>$val):?>
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>">
  <td>
    <input type="checkbox" onclick='verCheck(this)' id="<?php echo $val->id?>" value="<?php echo $val->id?>" name="procuradores[]" style="width:25px;"/>
  </td>
  <td><?php echo $val->procurador;?></td>
  <td><?php echo $val->designado;?></td>
  <td><?php echo $val->sistema;?></td>
  <td><?php if($val->sistema == 'SUPERIR') echo $tipoproc[$val->tipo_causa]; ?></td>
  <td><?php echo $val->mandante;?></td>
  <td><?php echo $val->estado;?></td>
  <td><?php echo $val->distrito;?></td>
  <td><?php echo $val->comuna;?></td>
  <td><?php echo $val->tribunal;?></td>
  <td><?php echo $val->tipoc;?></td>
  <td><?php echo $val->gestor;?></td>
  <td><?php echo $val->grupo;?></td>
  <td>
    <a style="cursor:pointer;" class="eliminar xtool" onclick="eliminar(<?php echo $val->id; ?>)" href="#"></a>
	<a href="#" title="Editar" onclick="getDatos(<?php echo $val->id; ?>)">
		<div class="table-m-sep-tools">
			<span class="editar"></span>
		</div>
	</a>
  </td>
</tr>
<?php ++$i;endforeach;?>
</table>
<?php endif;?>
<script>
	function eliminar(id){
		if (confirm('¿Está seguro que desea eliminar este registro?')){
			actionData = { 'id_config': id };
			$("#mask").css({"display":"block", "height": $(document).height()});

			$.ajax({
				type: "POST",
				dataType: 'json',
				data: actionData,
				url: "<?php echo base_url(); ?>index.php/admin/control_envios/eliminar_configuracion",
				success: function(response){
					$("#mask").css({"display":"none"});
					var d = response.result;
					if(d == 1)
						location.reload();
				}
			});
		}
	}
	
    function getDatos(id){
		actionData = { 'id_config': id };
    	$("#mask").css({"display":"block", "height": $(document).height()});

    	$.ajax({
          type: "POST",
          dataType: 'json',
          data: actionData,
          url: "<?php echo base_url(); ?>index.php/admin/control_envios/editar",
          success: function(response){
            $("#mask").css({"display":"none"});
  	        var d = response.result;
			$("#id_config").val(d.config.id);
			$("#mandante, #id_comuna, #tribunal, #estado").empty();
			$("#procurador").val(d.config.id_procurador);
			$("#designado").val(d.config.id_designado);
			$("#sistema").val(d.config.sistema);
			$("#grupo").val(d.config.grupo);
			$("#mandante, #id_comuna, #estado").append("<option value='0'>Seleccionar</option>");
			if(d.jurisdicciones){
				$("#id_distritonew").empty();
				$("#id_distritonew").append("<option value='0'>Seleccionar</option>");
				$.each(d.jurisdicciones, function(id, value){
					var opt = '<option value="'+id+'">'+value+'</option>';
					$("#id_distritonew").append(opt);
				});
			}
			if(d.letras){
				$("#tipoc").empty();
				$("#tipoc").append("<option value='0'>Seleccionar</option>");
				$.each(d.letras, function(id, value){
					var opt = '<option value="'+id+'">'+value+'</option>';
					$("#tipoc").append(opt);
				});
			}
			if(d.mandantes)
				$.each(d.mandantes, function(id, value){
					var opt = '<option value="'+value+'">'+value+'</option>';
					$("#mandante").append(opt);
				});
			if(d.estados)
				$.each(d.estados, function(id, value){
					var opt = '<option value="'+value+'">'+value+'</option>';
					$("#estado").append(opt);
				});
			if(d.comunas)
				$.each(d.comunas, function(id, value){
					var opt = '<option value="'+id+'">'+value+'</option>';
					$("#id_comuna").append(opt);
				});
			if(d.config.tipo_causa){
				$("#trtipoc").css({'display': 'table-row'});
				$("#tipoproc").val(d.config.tipo_causa);
				if(d.config.tipo_causa == 1)
					$("#trjuicio").css({'display': 'none'});
				else{
					$("#trjuicio").css({'display': 'table-row'});
					$("#tipojui").val(d.config.tipo_juicio);
				}
			}
			else
				$("#trtipoc").css({'display': 'none'});
			if(d.config.sistema == 'cat'){
				$("#tr_gestor").css({'display': 'table-row'});
				$("#gestor").val(d.config.id_gestor);
			}
			else
				$("#tr_gestor").css({'display': 'none'});
			if(d.mandantes)
				$("#mandante").val(d.config.mandante);
			if(d.config.estado)
				$("#estado").val(d.config.estado);
			if(d.config.tipoc)
				$("#tipoc").val(d.config.tipoc);
			if(d.config.id_distrito)
				$("#id_distritonew").val(d.config.id_distrito);
			if(d.config.id_comuna)
				$("#id_comuna").val(d.config.id_comuna);
			
			if(d.config.id_distrito > 0){
				$("#comuna").val(0);
				$("#mask").css({"display":"block", "height": $(document).height()});
				var actionData = { 'id_jurisdiccion': d.config.id_distrito, 'sistema': $("#sistema").val() };
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
					$("#tribunal").val(d.config.id_juzgado);
				  }
				});
			}
          }
        });
	}
</script>