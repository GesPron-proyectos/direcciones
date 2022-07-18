<?php 

$id=''; $rut=''; $nombres=''; $ap_pat = ''; $ap_mat = ''; $id_comuna=''; $ciudad = '';
$direccion=''; $direccion_numero='';
$direccion_dpto='';   $celular='';
$direccion_estado = '';
$telefono_estado = '';
$celular_estado = '';

if($_POST){

	$rut=$_POST['rut']; $nombres=$_POST['nombres']; $ap_pat=$_POST['ap_pat']; $ap_mat=$_POST['ap_mat']; 
	$id_comuna=$_POST['id_comuna'];
	$direccion=$_POST['direccion']; 
	$ciudad = $_POST['ciudad'];
	$direccion_numero=$_POST['direccion_numero'];
	$direccion_dpto=$_POST['direccion_dpto'];
	//$telefono=$_POST['telefono']; $celular=$_POST['celular']; 

}

if (count($lists)>0){

	$id=$lists->id; 
	$rut=$lists->rut; $nombres=$lists->nombres; $ap_pat=$lists->ap_pat; $ap_mat=$lists->ap_mat;
	$id_comuna=$lists->id_comuna;
	$ciudad = $lists->ciudad;
	//$direccion=$lists->direccion;
	//$direccion_numero=$lists->direccion_numero;
	//$direccion_dpto=$lists->direccion_dpto;
	//$telefono=$lists->telefono; $celular=$lists->celular;

}

//if (count($direccion_list)>0){
	//$direccion = $direccion_list->direccion;
	//$direccion_estado = $direccion_list->estado;
//}

//if (count($telefono_list)>0){
	//foreach($telefono_list as $key=>$val){
	  //  if($val->tipo == 1){
	    //	$telefono = $val->numero;
	    	// $telefono_estado = $val->estado;
	   // }
	   // if( $val->tipo == 3){
	    //	$celular = $val->numero;
	    //	$celular_estado = $val->estado;
	   // }
  //	} 
// }

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

<form action="<?php echo site_url().'/admin/usuarios/form/guardar/';?><?php if (!empty($id)){echo $id;}?>" method="post" autocomplete="off">
   
<div class="titulo">
    <strong style="float:left; margin-right:10px;">Complete los datos de la cuenta</strong>
     <?php if (validation_errors()!=''): ?>
    <span class="alerta"></span><span class="error">Faltan campos por completar</span> 
    <?php endif;?>
    <span class="flechita"></span>
    <div class="clear"></div>
</div>

<div class="blq">

    <div class="dos">
      <div class="cont-form">
            <label for="rut" style="width:135px;">Rut*:</label>
            <input id="rut" name="rut" type="text" value="<?php echo $rut;?>" style="width:150px;">
            <?php form_error('rut', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>
      <div class="cont-form">
          <label for="nombres" style="width:135px;">Nombres*:</label>
          <input id="nombres" name="nombres" type="text" value="<?php echo $nombres;?>" style="width:210px;">
          <?php form_error('nombres');?>
      </div>
      <div class="clear"></div>
      <div class="cont-form">
          <label for="ap_pat" style="width:135px;">Ap. Paterno*:</label>
          <input id="ap_pat" name="ap_pat" type="text" value="<?php echo $ap_pat;?>" style="width:210px;">
          <?php form_error('ap_pat');?>
      </div>
      <div class="clear"></div>
      
      <div class="cont-form">
          <label for="ap_mat" style="width:135px;">Ap. Materno*:</label>
          <input id="ap_mat" name="ap_mat" type="text" value="<?php echo $ap_mat;?>" style="width:210px;">
          <?php form_error('ap_mat');?>
      </div>
      <div class="clear"></div>
      
	  <div class="cont-form">
        <label style="width:135px; float:left">Comuna*:</label>
        <?php echo form_dropdown('id_comuna', $comunas, $id_comuna);?>
        <?php form_error('id_comuna', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>
      
	 <div class="cont-form">
      <label for="ciudad" style="width:135px;">Ciudad:</label>
      <input id="ciudad" name="ciudad" type="text" value="<?php echo $ciudad;?>" style="width:150px;">
      <?php form_error('ciudad', '<span class="error">', '</span>');?>
      </div>
      <div class="clear"></div>


    </div><!--dos-->

    <div class="clear"></div>

    <input type="submit" value="Guardar" class="boton" style="width:99%; float:left;">

    <div class="clear"></div>

</div><!--blq-->  

</form>

