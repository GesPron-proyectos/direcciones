<div class=" agregar-noticia">
<?php echo form_open(site_url().'/admin/cuentas/enviaracuse/'.$id); ?>
<div class="modal fade" id="popupNuevaAventura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">
		<div id="nuevaAventura" class="modal-body" style="vertical-align:text-top">
            <form role="form">
			
			  <div class="form-group">
                <label for="nombreAventura">Tipo Operación</label>                
              </div>
              <div class="form-group">
            <select name="tipooperacion" class="input-large form-control">
                    <option value="1" selected="selected">Informar cobro</option>
                    <option value="2">Informar rojo</option>
					<option value="3">Otros</option>
                </select>
              </div>
			
              <div class="form-group">
                <label for="nombreAventura">Comentario a enviar</label>
                
              </div>
              <div class="form-group">
                <textarea rows="2" name="operacion" cols="41" class="form-control" id="operacion" placeholder="Ingrese comentario a enviar" required="required"></textarea>
              </div>
              </form>      
		</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"onClick="$('#popupNuevaAventura').hide();">Cerrar</button>
        <button type="submit" class="btn btn-success">Crear</button>        
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>
</div>
