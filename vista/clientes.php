<?php 
require_once('../modelo/clsCliente.php');

$objCli = new clsCliente();

$listaTipoDoc = $objCli->consultarTipoDocumneto();
$listaTipoDoc = $listaTipoDoc->fetchAll(PDO::FETCH_NAMED);

?>
<section class="content-header">
  <div class="container-fluid">
	<div class="card card-success">
	  <div class="card-header">
		<h3 class="card-title">Listado de Clientes</h3>
	  </div>
	  <div class="card-body">
		<div class="row">
		  <div class="col-md-4">
			<div class="input-group">
			  <div class="input-group-prepend">
				<span class="input-group-text">Nombre</span>
			  </div>
			  <!-- Con el evento onkeyup puedes realizar la busquedad cada vez que escriba una letra onkeyup="verListado()" -->
			  <input type="text" class="form-control" name="txtBusquedaNombre" id="txtBusquedaNombre" autocomplete="off" onkeyup="if(event.keyCode=='13'){ verListado(); }" >
			</div>
		  </div>
		  <div class="col-md-4">
			<div class="input-group">
			  <div class="input-group-prepend">
				<span class="input-group-text">Estado</span>
			  </div>
			  <select class="form-control" name="cboBusquedadEstado" id="cboBusquedadEstado" onchange="verListado()">
				<option value="">- Todos -</option>
				<option value="1">Activos</option>
				<option value="0">Anulados</option>
			  </select>
			</div>
		  </div>
		  <div class="col-md-4">
			<button type="button" class="btn btn-primary" onclick="verListado()"><i class="fa fa-search"></i> Buscar</button>
			<button type="button" class="btn btn-success" onclick="abrirModalCliente()"><i class="fa fa-plus"></i> Nuevo</button>
		  </div>
		</div>
	  </div>
	</div>
	<div class="card card-success">
	  <div class="card-body">
		<div class="row">
		  <div class="col-md-12" id="divListadoCliente">

		  </div>
		</div>
	  </div>
	</div>
  </div>
</section>
<div class="modal fade" id="modalCliente">
  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header bg-primary">
			  <h4 class="modal-title">Cliente</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div class="modal-body">
			  <form name="formCliente" id="formCliente">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="idtipodocumento">Tipo Doc.</label>
							<select name="idtipodocumento" id="idtipodocumento" class="form-control">
							<?php foreach($listaTipoDoc as $k=>$v){ ?>
							  	<option value="<?= $v['idtipodocumento'] ?>"><?= $v['nombre'] ?></option>
							  <?php } ?>
							</select>
			  			</div>
			  			<div class="form-group">
							<label for="nrodocumento">Nro Doc</label>
							<div class="input-group input-group">
								<input type="text" class="form-control" id="nrodocumento" name="nrodocumento" onblur="verificarTipoDocumento()">
								<span class="input-group-append">
									<button type="button" class="btn btn-info btn-flat" onclick="consultarDatoCliente()"><i class="fa fa-search"></i></button>
								</span>
							</div>
							<input type="hidden" name="idcliente" id="idcliente" value="">
			  			</div>
			  			<div class="form-group">
							<label for="nombre">Nombre</label>
							<input type="text" autocomplete="off" class="form-control" id="nombre" name="nombre" >
			  			</div>
					</div>
				  	<div class="col-md-6">
			  			<div class="form-group">
							<label for="direccion">Direccion</label>
							<textarea class="form-control" id="direccion" name="direccion" rows="8"></textarea>
			  			</div>
			  			<div class="form-group" style="display: none;">
							<label for="estado">Estado</label>
							<select name="estado" id="estado" class="form-control">
							  <option value="1">ACTIVO</option>
							  <option value="0">ANULADO</option>
							</select>
			  			</div>
					</div>
				</div>
			  </form>
			</div>
			<div class="modal-footer justify-content-between">
			  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="guardarCliente()" ><i class="fa fa-save"></i> Registrar</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<script>
  
  function verListado(){
	$.ajax({
	  method: "POST",
	  url: "vista/clientes_listado.php",
	  data:{
		nombre: $('#txtBusquedaNombre').val(),
		estado: $('#cboBusquedadEstado').val()
	  }
	})
	.done(function(resultado){
	  $('#divListadoCliente').html(resultado);
	})
  }

  verListado();

  function guardarCliente(){
	if(validarFormulario()){
	  var datos = $('#formCliente').serializeArray();
	  var idcliente = $('#idcliente').val();
	  if(idcliente!=""){
		datos.push({name: "accion", value: "ACTUALIZAR"});
	  }else{
		datos.push({name: "accion", value: "NUEVO"});
	  }

	  $.ajax({
		method: "POST",
		url: "controlador/contCliente.php",
		data: datos,
		dataType: 'json'
	  })
	  .done(function(resultado){
		if(resultado.correcto==1){
		  toastCorrecto(resultado.mensaje);
		  $('#modalCliente').modal('hide');
		  $('#formCliente').trigger('reset');
		  verListado()
		}else{
		  toastError(resultado.mensaje)
		}
	  });
	}
  }

  function validarFormulario(){
	let correcto = true;
	let nombre = $('#nombre').val();

	if(nombre==""){
	  toastError('Ingrese el nombre del cliente')
	  correcto = false;
	}

	return correcto;
  }

  function abrirModalCliente(){
	$('#formCliente').trigger('reset');
	$('#idcliente').val("");
	$('#modalCliente').modal('show');
  }


  function consultarDatoCliente(){
  	$("#formCliente").LoadingOverlay("show");
  	$.ajax({
		method: "POST",
		url: "controlador/contCliente.php",
		data: {
				accion : 'CONSULTAR_WS',
				idtipodocumento: $('#idtipodocumento').val(),
				nrodocumento: $('#nrodocumento').val()
		},
		dataType: 'json'
	  })
	  .done(function(resultado){
	  	$("#formCliente").LoadingOverlay("hide");
		$('#idtipodocumento').val(resultado.idtipodocumento);
		$('#nombre').val(resultado.nombre);
		$('#direccion').val(resultado.direccion);
	  });
  }

  function verificarTipoDocumento(){
  	if($('#nrodocumento').val().length==8){
  		$('#idtipodocumento').val(1);
  	}else if($('#nrodocumento').val().length==11){
  		$('#idtipodocumento').val(6);
  	}
  }
</script>