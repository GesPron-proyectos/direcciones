 <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
 <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<div class="table-m-sep">

  <div class="table-m-sep-title">

  <h2><strong>Causas (<?php echo number_format($total,0,',','.');?>)</strong></h2>

  </div>

</div>

<div class="agregar-noticia">
    
  <br/>
	
  	
    <div class="clear"></div>
<div class="">


   <td  width="50%" >
      <legend style="border: 1px groove #ddd !important;padding: 0 1.4em 1.4em 1.4em !important;margin: 0 0 1.5em 0 !important;-webkit-box-shadow:  0px 0px 0px 0px #000;box-shadow:  0px 0px 0px 0px #000;"><fieldset style="font-size: 1.0em !important;font-weight: bold !important;text-align: left !important;width:auto;padding:0 10px;border-bottom:none;">Configuracion control de envios:</fieldset><br />
        <?php echo form_open(site_url().'/admin/control_envios/guardar_configuracion/'.$id); ?>
    <table width="100%">
      <tr>
        <td>Procurador</td>
        <td>
        <?php echo form_dropdown('procurador', $procurador, $id);?>
        <?php echo form_error('procurador');?>
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
        <td>Juridisccion</td>
        <td>
        <?= form_open(base_url().'index.php/control_envios/hacerAlgo'); ?>
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
      <!--<tr>
        <td>Juzgado</td>
        <td>
       <select id="id_tribunalnew" name="id_tribunalnew" class="form-control" style="width:350px">
       </select>
      </tr>-->
<tr><td colspan="2"><br><input type="submit" value="Guardar" style="float:right"></td></tr>
    <?php echo form_close();?>
    <tr><td colspan="2"><br></td></tr>
        <tr><td colspan="2"><br></td></tr>
    </table>
</div><!-- campo -->

<?php
if($current == 'hist_cuentas'){
?>
<a href="<?php echo site_url();?>/admin/hist_cuentas/reporte/estados/exportar<?php echo $suffix;?>" class="ico-excel">Exportar a CSV</a>
<?php } ?>
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
            $(document).ready(function() {
                $("#sistema").change(function(){ //alert('sss');
                    var actionData = { 'sistema': $(this).val() };
                    $.ajax({
                      type: "POST",
                      dataType: 'json',
                      data: actionData,
                      url: "<?php echo base_url(); ?>index.php/admin/control_envios/cargarSelects",
                      success: function(response){
                        var data = response.result;
                        $("#estado").empty();
                        $("#mandante").empty();
                        $("#estado").append("<option value='0'>Seleccionar</option>");
                        $("#mandante").append("<option value='0'>Seleccionar</option>");
                        $.each(data.mandantes, function(id, value){
                            var opt = '<option value="'+value+'">'+value+'</option>';
                            $("#mandante").append(opt);
                        });
                        $.each(data.estados, function(id, value){
                            var opt = '<option value="'+value+'">'+value+'</option>';
                            $("#estado").append(opt);
                        });
                      }
                    });
                });

                $("#id_distritoExh").change(function() {
                    $("#id_distritoExh option:selected").each(function() {
                        idEstado = $('#id_distritoExh').val();
                        $.post("<?php echo base_url(); ?>index.php/admin/control_envios/fillCiudades", {
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
                $.post("<?php echo base_url(); ?>index.php/admin/control_envios/fillCiudades", {
                    idEstado : idEstado
                }, function(data) {
                    $("#id_tribunalnew").html(data);
                });
            });
        });

    });
</script>