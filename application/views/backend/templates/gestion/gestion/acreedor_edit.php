<?php 
     
  $monto_a            = $this->input->post('monto');
	$id_prefrencia_a    = $this->input->post('preferencia_idpreferencia');
	$id_verif_a			    = $this->input->post('tipo_verificacion_idtipo_verificacion');


if ($idregistro!=''){

	$monto_a    			  = $acreedor->monto;
	$id_prefrencia_a  	= $acreedor->preferencia_idpreferencia;
	$id_verif_a  	    	= $acreedor->tipo_verificacion_idtipo_verificacion;
	
}
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<style type="text/css">
    .custom_input{
    width:573px;/*use according to your need*/
    height:23px;/*use according to your need*/
}
</style>
<body>

<div>
 <?php echo form_open(site_url().'/admin/gestion/actualizar_acreedor/'.$id.'/'.$idregistro); ?>

<table class="stable" width="100%">
  <tr><td colspan="4"><h3><?php if ($idregistro==''):?>VERIFICACI&Oacute;N DE CREDITOS<?php else:?>Editar acreedor #<?php echo $idregistro;?><?php endif;?></h3><br></td></tr>
   <tr>
      <td>Acreedor:</td>
      <td>
        <?php echo form_dropdown('razon_social', $razon_sc_acreedor, $cuenta->razon_social);?>
        <?php echo form_error('razon_social','<br><span class="error">','</span>');?>
      </td>
  </tr>


  <tr>
      <td>Monto:</td>
      <td>
      <input id="monto" autocomplete="off" name="monto" type="text" value="<?php echo $acreedores->monto; ?>" style="width:131px;">
      <?php echo form_error('monto', '<span class="error">', '</span>');?>
      </td>
  </tr>
  <tr>
      <td>Preferencia:</td>
      <td>
      	<?php echo form_dropdown('preferencia_idpreferencia', $sct_preferencia, $acreedores->preferencia);?>
        <?php echo form_error('preferencia_idpreferencia','<br><span class="error">','</span>');?>
      </td>
  </tr>
  <tr>
      <td>Verificaci&oacute;n:</td>
      <td>
		<?php echo form_dropdown('tipo_verificacion_idtipo_verificacion', $sct_verificacion, $acreedores->tipo_verificacion);?>
        <?php echo form_error('tipo_verificacion_idtipo_verificacion','<br><span class="error">','</span>');?>
      </td>
  </tr>


     <tr>
      <td>
        <input id="id_cuenta_a" name="id_cuenta_a" type="hidden" value="<?php echo $idcuenta; ?>">
      </td>
  </tr>

  <tr><td colspan="2"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear Nuevo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
  </table>
</div>
<div class="cover">
  
</div>

  <script>
    //Plugin Select2
    jQuery(document).ready(function($){
    $(document).ready(function() {
        $('.mi-selector').select2();
      });
    });

    //Formato Datepicker
        $(".datepicker").datepicker({
      dateFormat:'dd-mm-yy'
    });

    /*Punto para miles, decena de miles, etc..
        $("#monto").on({
    "focus": function (event) {
        $(event.target).select();
    },
    "keyup": function (event) {
        $(event.target).val(function (index, value ) {
            return value.replace(/\D/g, "")
                        .replace(/([0-9])([0-9]{3})$/, '$1.$2')
                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
        });
    }
}); */
$('#monto').keyup(function(event) {
      

    $(this).val(function(index, value) {
        return value
            .replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        ;
    });
});
  </script>
</body>
</html>

<?php $this->load->view('backend/templates/gestion/gestion/acreedor_list');?>






<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Dialog - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#dialog" ).dialog();
  } );
  </script>
</head>
<body>
 
<div id="dialog" title="Basic dialog">
  <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>
 
 
</body>
</html>