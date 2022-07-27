<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Sistema Direcciones</title>

<?php $ie6=false;$ie7=false;$ie8=false;$chrome=false;$safari=false;?>

<?php echo link_tag('css/main.css');?>

<?php if(strpos($_SERVER['HTTP_USER_AGENT'],'IE')):?>

<link href="<?php echo base_url();?>css/fix/ie.css" rel="stylesheet" type="text/css">

<?php endif;?>

<?php if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0')): $ie6=true;?>

<link href="<?php echo base_url();?>css/fix/ie6.css" rel="stylesheet" type="text/css">

<?php endif;?>

<?php if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 7.0')): $ie7=true;?>

<link href="<?php echo base_url();?>css/fix/ie7.css" rel="stylesheet" type="text/css">

<?php endif;?>

<?php if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 8.0')): $ie8=true;?>

<link href="<?php echo base_url();?>css/fix/ie8.css" rel="stylesheet" type="text/css">

<?php endif;?>

<?php if((strpos($_SERVER['HTTP_USER_AGENT'],'Chrome'))and(strpos($_SERVER['HTTP_USER_AGENT'],'Safari'))): $chrome=true;?>

<link href="<?php echo base_url();?>css/fix/chrome.css" rel="stylesheet" type="text/css">

<?php endif;?>

<?php if((!strpos($_SERVER['HTTP_USER_AGENT'],'Chrome'))and(strpos($_SERVER['HTTP_USER_AGENT'],'Safari'))): $safari=true;?>

<link href="<?php echo base_url();?>css/fix/safari.css" rel="stylesheet" type="text/css">

<?php endif;?>


<?php echo link_tag('img/favicon.ico','shortcut icon','image/ico');?>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.rut.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.rutnew.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/daterangepicker/lib/underscore.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/daterangepicker/lib/moment.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/daterangepicker/lib/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js"></script>

<!--<script type="text/javascript" src="http://vertutoriales.dkreativo.es/codigo-fuente/bootstrap/bootstrap/js/bootstrap.min.js"></script>-->
<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/tinybox.js"></script>


<!--<link href="http://vertutoriales.dkreativo.es/codigo-fuente/bootstrap/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://vertutoriales.dkreativo.es/codigo-fuente/bootstrap/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css">-->
<link href="<?php echo base_url();?>css/prettyPhoto.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>js/daterangepicker/css/picker.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>js/jquery-ui-1.10.4.custom/css/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css">



<!--script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script-->

  <!--[if lt IE 7]>
    <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js"></script>
  <![endif]-->
  
  
<script type="text/javascript" src="<?php echo base_url();?>datepicker/bootstrap-datepicker.js"></script>
<link href="<?php echo base_url();?>datepicker/datepicker.css" rel="stylesheet" type="text/css">

</head>