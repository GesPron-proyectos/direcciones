<div class="table-m-sep">
  <div class="table-m-sep-title"> 
    <div class="table-m-sep-tools">
      <span class="editar">Editar</span>
      <span class="subir">Subir</span>
      <span class="bajar">Bajar</span>
      <span class="publicado">Publicado</span>
      <span class="despublicado">Despublicado</span>
      <span class="eliminar">Eliminar</span>
    </div>
  </div>
</div>
<div class="tabla-listado">
  <br/>
  <div class="content_tabla">
    <table id="tpagare" class="listado" width="100%">
      <thead>
        <tr class="menu" style="line-height:20px; height:50px;">
          <td class="nombre">Fecha</td>
          <td class="nombre">Registros</td> 
          <td class="nombre">Codigo</td>
        </tr>
      </thead>
      <tbody>
        <?php  $i=1; $check_id=1; foreach ($registros as $key => $value): ?>
          <tr<?php if ($i%2==0){echo ' class="b"';}else{echo ' class="a"';}?> id="row-<?php echo $value['id'];?>">
              <td><?php echo $value['fecha']; ?></td>
              <td><?php echo $value['registros']; ?></td>
              <td><?php echo $value['codigo']; ?></td>
          </tr>
        <?php ++$i; endforeach; ?>
      </tbody>
    </table>
  </div>
</div>