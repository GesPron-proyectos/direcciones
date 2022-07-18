<?php 

$id=''; $rut=''; $monto=''; $forma_pago='';
$fecha_pago_day = date ( 'd' );
$fecha_pago_month = date ( 'm' );
$fecha_pago_year = date ( 'Y' );
if($_POST){
	$rut=$_POST['$rut'];
	$monto=$_POST['monto'];
	$forma_pago=$_POST['forma_pago']; 
	if (!empty($_POST ['fecha_pago_day'])){ $fecha_pago_day = $_POST ['fecha_pago_day'];}
	if (!empty($_POST ['fecha_pago_month'])){ $fecha_pago_month = $_POST ['fecha_pago_month'];}
	if (!empty($_POST ['fecha_pago_year'])){ $fecha_pago_year = $_POST ['fecha_pago_year'];}

}
if (count($lists)>0){
	$id=$lists->id; 
	$rut=$lists->rut;
	$id_cuenta=$lists->id_cuenta;
	$monto=$lists->monto;
	$forma_pago=$lists->forma_pago;

	$explode = explode ( '-', $lists->fecha_pago );
	$fecha_pago_day = $explode [2];
	$fecha_pago_month = $explode [1];
	$fecha_pago_year = $explode [0];
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

<form action="<?php echo site_url().'/admin/comprobantes/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post">

<?php //echo 'aa '.validation_errors().form_error('username').form_error('password').form_error('perfil');?>

    

<div class="titulo">

    <strong style="float:left; margin-right:10px;">Complete los datos del comprobante</strong> 

    

    <span class="flechita"></span>

    <div class="clear"></div>

</div>

<div class="blq">

    <div class="dos">
    <input type="hidden" name="id_cuenta" value="<?php echo $id_cuenta;?>" />
    <label for="monto" style="width:135px;">MANDANTE :</label><?php echo $cuenta->razon_social;?>
    <div class="clear"></div>
    <label for="monto" style="width:135px;">CLIENTE :</label><?php echo $cuenta->nombres.' '.$cuenta->ap_pat.' '.$cuenta->ap_mat;?>
    <div class="clear"></div>
    <label for="monto" style="width:135px;">JUZGADO :</label><?php echo $cuenta->tribunal.' '.$cuenta->distrito;?>
    <div class="clear"></div>
    <label for="monto" style="width:135px;">ROL :</label><?php echo $cuenta->rol;?>
    <div class="clear"></div>
    <div class="cont-form">
      <label for="monto" style="width:135px;">Monto*:</label>
      <input id="monto" name="monto" type="text" value="<?php echo $monto;?>" style="width:150px;">
      <?php echo form_error('monto', '<span class="error">', '</span>');?>
	</div>
    <div class="clear"></div>
    <div class="cont-form">
        <label for="fecha_pago_year" style="width: 135px;">Fecha de Pago*:</label>
        <?php echo day_dropdown('fecha_pago_day',$fecha_pago_day).month_dropdown('fecha_pago_month',$fecha_pago_month).year_dropdown('fecha_pago_year',$fecha_pago_year,2010,date('Y')+10);?>
        <div class="clear"></div>
        <?php echo form_error('fecha_pago_year','<span class="error">', '</span>');?>
    </div>
    <div class="clear"></div>
    <div class="cont-form">
      <label for="monto" style="width:135px;">Forma Pago*:</label>
      <?php echo form_dropdown('forma_pago', $forma_pagos, $forma_pago);?>
      <?php echo form_error('forma_pago', '<span class="error">', '</span>');?>
	</div>
    <div class="clear"></div>

    </div><!--dos-->

    <div class="clear"></div>

    <input type="submit" value="Guardar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq-->  

</form>

