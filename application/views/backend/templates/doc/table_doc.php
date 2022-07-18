<table width="100%" border="0">
  <tr class="titulos-tabla">
    <td><b>Nombre Documento</b></td>
    <td><b>Fecha Creacion</b></td>
    <td><b>Tipo Documento</b></td>
    <td><b>Etapa Juicio</b></td>
    <td><b>Descargar Documento</b></td>
    <td><b>Eliminar Documento</b></td>
  </tr>
  <?php foreach($documentos as $key=>$val): ?> 
  <tr class="detalle-tabla" id="doc_<?php echo $val->documento_iddocumento?>">
    <td><?php echo $val->documento_nombre_documento?></td>
    <td><?php echo date("d-m-Y H:i:s", strtotime($val->documento_fecha_crea))?></td>
    <td><?php echo $val->documento_tipo_documento?></td>
    <td><?php echo $val->s_etapas_etapa?></td>
    <td><a href="<?php echo base_url().'documentos/'.$val->documento_nombre_documento?>">Link</a></td>
    <td><a data-id="<?php echo $val->documento_iddocumento?>" class="delete_doc" href="<?php echo site_url().'/admin/doc/delete/'.$val->documento_iddocumento?>">Eliminar</a></td>
  </tr>
  <?php endforeach;?>
</table>