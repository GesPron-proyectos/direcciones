<?php $this->load->view('backend/a_common/head'); ?>
<body>
<div class="wrapper">
  <div class="cont">
    <div class="panel">
    <span class="header-blk-right-2">Sistema de Direcciones 
      <b>fv1r3285</b></span>
    </div>
    <div class="registrar">
      <div class="table-m-sep-title">
        <span class="icono"></span>
        <h1><strong>Iniciar Sesión</strong></h1>
      </div>
      <div class="caja">
          <div class="ingreso">
            <?php echo form_open('admin/adm/login'); ?>
              <label for="username">Usuario:</label>
              <input name="username" type="text" value="<?php echo set_value('username'); ?>">
              <label for="password">Clave:</label>
              <input name="password" type="password" value="<?php echo set_value('password'); ?>">
              <input type="submit" name="Entrar" value="Entrar" class="boton" style="width:83px;">
            </form>
          </div>
      </div>
    </div>

    <div class="error">
      <span class="alerta"></span>
      <?php
		if(isset($error)){
			echo "<p>".$error."</p>";
		}
		echo form_error('username');
		?>

    </div>
    <span class="creditos">Desarrollado por:</span>
  </div>
</div>
</body>
</html>