<?php 

$id=''; $perfil='0'; $username=''; $password=''; $nombre=''; $apellido=''; $correo=''; $cargo=''; $rut=''; $check= '';

if($_POST){
    $perfil=$_POST['perfil']; $username=$_POST['username']; $password=$_POST['password']; 
	$rut = $_POST['rut'];
    $check = $_POST['check'];
	$nombres=$_POST['nombre']; $apellidos=$_POST['apellido']; $correo=$_POST['correo']; $cargo=$_POST['cargo'];

}

  if (count($lists)>0){
  
	  	$id=$lists->id;
   		$nombre=$lists->nombre;
  		$apellido=$lists->apellido;
    	$correo=$lists->correo;
	  	$rut=$lists->rut;
 
	 }

?>

<script type="text/javascript">

$(document).ready(function(){ 

	/*$("#categoria").keyup(function(){

		var ss=$(this).val();

		if (ss.length>70){$("#mrubro").html("error");}

		else{$("#mcategoria").html(70-(ss.length)+" caracteres");}

	});*/

});

</script>

<form action="<?php echo site_url().'/admin/procurador/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">

<?php //echo 'aa '.validation_errors().form_error('username').form_error('password').form_error('perfil');?>

<div class="titulo">

 <strong style="float:left; margin-right:10px;">Seleccione el Perfil del Usuario</strong> 

  <?php if (validation_errors()!='' && (isset($_POST['enviar']) && $_POST['enviar']!='')): ?>

  <span class="alerta"></span><span class="error">Faltan campos por completar</span> 

  <?php endif;?>

  <span class="flechita"></span>

  <div class="clear"></div>

</div>

    

<div class="titulo">
    <?php if (validation_errors()!='' && (isset($_POST['enviar']) && $_POST['enviar']!='')): ?>

    <span class="alerta"></span><span class="error">Faltan campos por completar</span> 

    <?php endif;?>

    <span class="flechita"></span>

    <div class="clear"></div>

</div>

<div class="blq">
   <div class="dos">
      <div class="cont-form">
            <label style="width:135px!important; float:left">Rut Procurador*:</label>
        <input id="rut_procurador" name="rut" type="text" value="<?php echo $rut;?>" style="width:150px;">
        <div class="clear"></div>
        <span class="error" id="error_rut"></span>
      </div>

    <div class="dos">
      <div class="cont-form">
            <label for="nombres" style="width:135px;">Nombre:</label>
            <input id="nombres" name="nombre" type="text" value="<?php echo $nombre;?>" style="width:150px;">
      </div>
      <div class="clear"></div>
        <div class="cont-form">
              <label for="apellidos" style="width:135px;">Apellido:</label>
              <input id="apellidos" name="apellido" type="text" value="<?php echo $apellido;?>" style="width:150px;">
        </div>

     <div class="clear"></div>
      <div class="cont-form">
            <label for="correo" style="width:135px;">Correo:</label>
            <input id="correo" name="correo" type="text" value="<?php echo $correo;?>" style="width:195px;">
            <div class="clear"></div>
            <?php echo form_error('correo', '<span class="error">', '</span>');?>
      </div>
     

      <div class="clear"></div>   

    </div><!--dos-->

    <div class="clear"></div>

    <input type="submit" value="Guardar" name="enviar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div>

</form>

<script src="<?php echo base_url()?>js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>js/jquery.rut.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function(){ 

		$("#rut_procurador").rut().on('rutInvalido', function(e) {
			$(this).val('');
			 //$('#submit').attr('disabled', 'disabled');
			 $('#error_rut').html("Rut incorrrecto.");
			 //$('#submit').hide();
		});


		$("#rut_procurador").rut().on('rutValido', function(e, rut, dv) {
			//$('#submit').removeAttr('disabled');
			$('#error_rut').html('');
			//$('#submit').show();
		});


		//$("#rut").rut({ validateOn: 'focusin' });

		//$( "#rut" ).focus();

});

</script>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
<!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">-->

<script>
$(function() {
  $('#datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        maxDate: "+0m +0d",
        onSelect: function(datetext){
            var d = new Date(); // for now
            var h = d.getHours();
            h = (h < 10) ? ("0" + h) : h ;

            var m = d.getMinutes();
            m = (m < 10) ? ("0" + m) : m ;

            var s = d.getSeconds();
            s = (s < 10) ? ("0" + s) : s ;

            datetext = datetext + " " + h + ":" + m + ":" + s;
            $('#datepicker').val(datetext);
        },
    });
});

</script>

<?php 

$observaciones_m = $this->input->post('observaciones');     
$fecha_etapa_m = $this->input->post('fecha_etapa');
if ($fecha_etapa_m==''){ $fecha_etapa_m = date('d-m-Y H:i:s');}

if ($idregistro!=''){
	$observaciones_m = $etapa_juicio_cuenta->observaciones;
	$id_etapa_m = $this->input->post('id_etapa');   
	$id_etapa_m =  $etapa_juicio_cuenta->id_etapa;
	$fecha_etapa_m = date('d-m-Y H:i:s',strtotime($etapa_juicio_cuenta->fecha_etapa));
}
?>  

<!--######################### INGRESAR CORREOS ############################-->
<?php if ($id){ ?>
<?php echo form_open(site_url().'/admin/procurador/guardar_correo/'.$id); ?>
<table class="stable" width="100%">
  <tr><td colspan="4"><h3><br><?php if ($idregistro==''):?>Ingresar Correo:<?php else:?>Editar Etapa de Juicio #<?php echo $idregistro;?> <a href="#" class="close" style="float:right;" data-gtab="direccion">Cerrar</a><?php endif;?></h3><br></td></tr>
  <tr>
      <td>Correo:</td>
      <td>
      <?php echo form_dropdown('id', $correos, $id,' class="select_dos" autocomplete="off" data-id="'.$id.'" ');?>
    
      <?php echo form_error('id_etapa','<br><span class="error">','</span>');?>
      </td>
    
  </tr> 
  <tr>

  <style type="text/css">
    .custom_input{
    width:573px;/*use according to your need*/
    height:23px;/*use according to your need*/
}
  </style>
   
  </tr>
  <tr><td colspan="4"><br><input type="submit" value="<?php if ($idregistro==''):?>Crear correo<?php else:?>Editar<?php endif;?>" style="float:right"></td></tr>
  </table>
  <?php echo form_close();?>

<table class="stable" width="100%">
  <div id="box-form-etapas_juicio"> </div>
 <tr><td colspan="4"><h3>Listado de Correos:</h3><br></td></tr>
  <tr>
      <td colspan="4">
      <table class="stable grilla" width="100%">
          <tr class="titulos-tabla">
            <td>#</td>
              <td>Correo</td>
             
              <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
                <td>Gestión</td>
                <?php endif;?>
          </tr>
          <?php if(count($data_correos)>0): ?>
          <?php $pos=1; foreach ($data_correos as $key=>$value):?>
              <tr>
              <td><?php echo $pos; ?></td>
              <td><?php echo $value->correo; ?></td>  
              <?php if ($this->session->userdata("usuario_perfil")==1 || $this->session->userdata("usuario_perfil")==2):?>
              <td>  
                  <a href="<?php echo site_url('admin/procurador/eliminar_correo/'.$id.'/'.$value->id); ?>" class="delete">Eliminar</a>
              </td>
              <?php endif; $pos++;?>
              </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="7">No hay correos ingresados</td></tr>
          <?php endif;?>
      </table>
  </td></tr>
</table>
<?php } ?>
<div class="clear height"></div>
</div>