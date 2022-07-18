<?php /*if (count($lists)>0):
$txt='<body><style>body {font-family: Lucida Console;font-size:12px;width:180px;}</style>';
$txt.='SERVICOBRANZA S.A'.chr(13);
$txt.='RECIBO DE DINERO'. chr(13);
$txt.='JUICIO CARATULADO'.chr(13);
$txt.=$lists->razon_social.' / '.$lists->nombres.' '.$lists->ap_pat.' '.$lists->ap_mat.chr(13);
$txt.='JUZGADO :'.chr(13);
$txt.=$lists->tribunal.' '.$lists->distrito.chr(13);
$txt.='ROL :'.chr(13);
$txt.=$lists->rol.chr(13);
$txt.='MONTO'.chr(13);
$txt.='$'.number_format($lists->monto,0,',','.').chr(13);
$txt.='FECHA :'.chr(13);
$txt.=date("d-m-Y", strtotime($lists->fecha_pago)).chr(13);
$txt.='COMPROBANTE Nº'. chr(13);
$txt.=$lists->id;?><?php echo chr(13);
$txt.='SERVICOBRANZA S.A.'.chr(13);
$txt.='SAN ANTONIO 835 OFICNA 802, Santiago'.chr(13);
$txt.='Fono 6886561'.chr(13);
$txt.=date('d-m-Y H:i:s').chr(13).$lists->verify_sign.chr(13);
$txt.='voucher v1.0 r1'.chr(13).'</body>';
endif;
$fp = fopen('./uploads/voucher.txt', 'w');
fwrite($fp, $txt);
fclose($fp);*/
//header('Content-type: text/html; charset=utf-8');
//include("./uploads/voucher.txt");
//readfile("./uploads/text.txt");


$file = "./uploads/test2.doc";

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}
?> 
