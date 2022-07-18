<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
.voucher { font-family: Helvetica; font-size:10pt;}
.clear { clear:both;}
.height { height:10px;}
h1 { text-align:center;font-size:14pt;}
h2 { text-align:center;font-size:12pt;}
tr td { padding:2px;}
.title{ /*text-decoration:underline;*/}
.foo{ font-weight: bold;}
</style>
<script type="text/javascript">
window.print();
</script>
<?php if (count($lists)>0):?>
<div class="voucher" style="width:7cm; float:left; padding:5px; border:0px solid #000">
<!--img src="<?php echo base_url();?>images/sample-logo-trans.png" style="margin:auto;display:block;width:148px;"-->
<h1 style="text-align:center!important">STEFANO BERTERO SICHEL</h1>
<h2 style="text-align:center!important;font-size:11pt">ABOGADO</h2>
<div class="clear height"></div>
<h2>RECIBO DE DINERO Nº <span class="title"><?php echo $lists->id;?></span></h2>
<table border="0" style="font-size:10pt; font-weight: bold; width:100%">
  <tr>
    <td colspan="2" align="left" valign="top" class="title">JUICIO CARATULADO</td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><?php echo $lists->razon_social.'<br>'.$lists->nombres.' '.$lists->ap_pat.' '.$lists->ap_mat;?></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="title">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top" class="title">JUZGADO</td>
    <td align="left" valign="top">ROL</td>
  </tr>
  <tr>
    <td valign="top" class="title"><?php echo $lists->tribunal.' '.$lists->distrito;?></td>
    <td align="left" valign="top"><?php echo $lists->rol;?></td>
  </tr>
  <tr>
    <td colspan="2" valign="top" class="title">&nbsp;</td>
    </tr>
  <tr>
    <td width="60%" valign="top" class="title">MONTO CANCELADO</td>
    <td width="40%" align="left" valign="top">FECHA PAGO</td>
  </tr>
  <tr>
    <td valign="top" class="title"><?php echo '$'.number_format($lists->monto,0,',','.')?></td>
    <td align="left" valign="top"><?php echo date("d-m-Y", strtotime($lists->fecha_pago));?></td>
  </tr>
  <tr>
    <td valign="top" colspan="2" class="title">TIPO PAGO</td>
  </tr>
  <tr>
    <td valign="top" colspan="2" class="title"><?php echo $forma_pagos[$lists->forma_pago];?></td>
  </tr>
  <tr>
    <td valign="top" colspan="2" class="title">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" valign="top" class="title"><table width="100%" border="0" style="font-size:8pt; font-weight: bold;">
  <tr>
    <td width="33%">FECHA</td>
    <td width="33%">HORA</td>
    <td width="34%">OPERACIÓN</td>
  </tr>
  <tr>
    <td><?php echo date('d-m-Y');?></td>
    <td><?php echo date('H:i:s');?></td>
    <td><?php echo $lists->verify_sign;?></td>
  </tr>
</table></td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top" class="title">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top" class="title">SAN ANTNIO 385 OFICINA 802, Santiago</td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top" class="title">Fono 8969430</td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top" class="title">www.servicobranza.cl</td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="top" class="title">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="right" valign="top" class="title">voucher v1.0 r2</td>
  </tr>
  </table>

</div>
<?php endif?>
