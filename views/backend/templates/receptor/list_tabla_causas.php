<?php include APPPATH.'views/backend/templates/mod/jquery_tools.php';?>

<?php

 $i=1; $check_id=1;foreach ($causas as $key=>$val): ?>
<?php

if($val->id_etapa==1 || $val->id_etapa==0)
{
$diseno = "style=background:#F7FE2E;";$color = "#000000;";	
}
else
{
	if($val->exorto==0)
	{
		if($val->codigo_mandante=="TANNER")
		{
			if ($val->dias_ >= 50)
			{	
				$diseno = "style=background:#F75D5D;";$color = "#000000;";$color_atraso = $color;
			}
			else
			{
				if ($i%2==0){$diseno = ' class="b"';}else{$diseno = ' class="a"';}
				$color = "#0000;";
				$color_atraso = "#F00";
			}
		}
		else
		{
			if ($i%2==0){$diseno = ' class="b"';}else{$diseno = ' class="a"';}
			$color = "#0000;";
			$color_atraso = "#F00";
		}
	}else
	{
		if($val->codigo_mandante=="TANNER")
		{
			if ($val->dias_ >= 75)
			{	
				$diseno = "style=background:#F75D5D"; $color = "#000000;";$color_atraso = $color;
			}
			else
			{
				if ($i%2==0){ $diseno = ' class="b"';}else{ $diseno = ' class="a"';}
				$color = "#0000;";
				$color_atraso = "#F00";
			}	
		}
		else
		{
			if ($i%2==0){$diseno = ' class="b"';}else{$diseno = ' class="a"';}
			$color = "#0000;";
			$color_atraso = "#F00";
		}
	}
}

//#F00       class="tr <?php if ($i%2==0){echo 'b';}else{echo 'a';}"
?>  
<tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $val->id;?>"  >

  <tr id="tools_" <?php echo $diseno;?>>

	<td><font color="<?php echo $color;?>"><?php /*echo $val->diass_."<br>". $val->id_etapa. "<br>". $diseno;*/ echo $val->codigo_mandante;?></font></td>
	<td><font color="<?php echo $color;?>"><?php echo date("d-m-Y",strtotime($val->fecha_asignacion))?></font></td>
	
	<td><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>">
	<font color="<?php echo $color;?>"><?php echo $val->usuarios_nombres;?> <?php echo $val->usuarios_ap_pat;?> <?php echo $val->usuarios_ap_mat;?></font></a></td>
	<td><font color="<?php echo $color;?>"><a href="<?php echo site_url();?>/admin/gestion/index/<?php echo $val->cuentas_id;?>">
	<font color="<?php echo $color;?>"><?php echo $val->usuarios_rut;?></font></a></td>
    <td><font color="<?php echo $color;?>"><?php echo $val->nombres_adm.' '.$val->apellidos_adm;?></font></td>
	
	<td  style="text-align: center;"><font color="<?php echo $color;?>"><?php echo $val->tribunal?><br><?php echo $val->tribunal_padre?></font></td>
	
	<td><font color="<?php echo $color;?>"><?php echo $val->rol;?></font>	</td>
	
	<td style="text-align: center;"><font color="<?php echo $color;?>">
	
	<?php if ($val->exorto=='0')
 {?> Sin Exhorto
 <?php }else{?>
		<?php if($val->rolE != ''){ ?><?php echo $val->TribunalE."<br>".$val->DistritoE."<br>".$val->rolE;?> <?php } else {echo '-'; ?><?php } ?>
	<?php }?>
	</font>
	</td>
      
      <!--<td><?php echo date("d-m-Y",strtotime($val->fecha_asignacion_pagare))?></td>-->
      
    
      <td style="color:<?php echo $color_atraso;?>;text-align: center;"><?php echo $val->dias_diferencia;?></td>
	  <td><font color="<?php echo $color;?>"><?php echo $val->etapa?></font></td>
	  <td style="text-align: center;"><font color="<?php echo $color;?>"><?php echo date("d-m-Y",strtotime($val->fecha_etapa))?></font></td>
	  <td><font color="<?php echo $color;?>"><?php echo $val->texto_alerta?></font></td>  

  <?php $row_current=array(); $row_current=$val; //include APPPATH.'views/backend/templates/mod/table_tools_abr.php';?>
</tr>

	
</tr>

<?php ++$i;endforeach;?>



