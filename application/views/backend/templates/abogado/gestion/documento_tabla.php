<table class="stable grilla" width="100%">
  <tr class="titulos-tabla">
      <td>Fecha</td>
      <td>Tipo</td>
      <td>Link</td>
  </tr>
  <?php if (count($documentos)>0):?>
  <?php foreach ($documentos as $key=>$documento):?>
      <tr>
      <td><?php echo date('d-m-Y H:i:s',strtotime($documento->fecha_crea));?></td>
      <td><?php echo $documento->tipo_documento;?></td>
      <td><a href="<?php echo base_url();?>documentos/<?php echo $documento->nombre_documento;?>" target="_blank">Descargar</a></td>
      </tr>
  <?php endforeach;?>
  <?php else:?>
  <tr><td colspan="4">No hay registros ingresados</td></tr>
  <?php endif;?>
</table>