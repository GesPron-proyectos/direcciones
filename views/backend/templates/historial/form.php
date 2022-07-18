<?php 

$id=''; $historial=''; 
$id_cuenta = '';
$fecha_day = date ( 'd' );
$fecha_month = date ( 'm' );
$fecha_year = date ( 'Y' );


if($_POST){

	$razon_social=$_POST['historial']; 	
	
	$id_cuenta = $_POST['id_cuenta'];
	
	if (!empty($_POST ['fecha_day'])){ $fecha_day = $_POST ['fecha_day'];}

	if (!empty($_POST ['fecha_month'])){ $fecha_month = $_POST ['fecha_month'];}

	if (!empty($_POST ['fecha_year'])){ $fecha_year = $_POST ['fecha_year'];}
}

if (count($lists)>0){

	$id=$lists->id; 
	
	$id_cuenta = $lists->id_cuenta;

	$historial=$lists->historial;
	
	$explode = explode ( '-', $lists->fecha );

	$fecha_day = $explode [2];

	$fecha_month = $explode [1];

	$fecha_year = $explode [0];

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

<form action="<?php echo site_url().'/admin/historial/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">

<?php //echo 'aa '.validation_errors().form_error('username').form_error('password').form_error('perfil');?>

    

<div class="titulo">

    <strong style="float:left; margin-right:10px;">Ingrese el historial</strong> 

      <?php if (validation_errors()!='' && (isset($_POST['enviar']) && $_POST['enviar']!='')): ?>

    <span class="alerta"></span><span class="error">Faltan campos por completar</span> 

    <?php endif;?>

    <span class="flechita"></span>

    <div class="clear"></div>

</div>

<div class="blq">

    <div class="dos">
    <div class="cont-form">
        <label style="width:135px;">Cuenta*:</label>
        <?php echo form_dropdown('id_cuenta', $cuentas, $id_cuenta);?>
        <div class="clear"></div>
        <?php echo form_error('id_cuenta', '<span class="error">', '</span>');?>
    </div> 
    	<div class="clear"></div>
    <div class="cont-form">
	  <label for="monto_deuda" style="width: 135px;">Fecha*:</label>
      <?php echo day_dropdown('fecha_day',$fecha_day).month_dropdown('fecha_month',$fecha_month).year_dropdown('fecha_year',$fecha_year);?>
      <div class="clear"></div>
      <?php echo form_error('fecha_year','<span class="error">', '</span>');?>
	</div><!-- cont-form -->
	<div class="clear"></div>
	<div class="cont-form">
      <label for="historial" style="width:135px;">Historial*:</label>
      <textarea id="historial" name="historial" style="width:450px; height:100px"><?php echo $historial;?></textarea>
      <div class="clear"></div>
      <?php echo form_error('historial', '<span class="error">', '</span>');?>
	</div>


    </div><!--dos-->

    <div class="clear"></div>

    <input type="submit" value="Guardar" class="boton" name="enviar" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq-->  

</form>

