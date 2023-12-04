<section class="content-header">
  <div class="container-fluid">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Listado de Categorias</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
              </div>
              <!-- Con el evento onkeyup puedes realizar la busquedad cada vez que escriba una letra onkeyup="verListado()" -->
              <input type="text" class="form-control" name="txtBusquedaNombre" id="txtBusquedaNombre" onkeyup="if(event.keyCode=='13'){ verListado(); }" >
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
            <button type="button" class="btn btn-success" onclick="abrirModalCategoria()"><i class="fa fa-plus"></i> Nuevo</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-success">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12" id="divListadoCategoria">

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="modalCategoria">
  <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary">
              <h4 class="modal-title">Categoria</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form name="formCategoria" id="formCategoria">
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                    <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre Categoria">
                <input type="hidden" name="idcategoria" id="idcategoria" value="">
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
                <button type="button" class="btn btn-primary" onclick="guardarCategoria()" ><i class="fa fa-save"></i> Registrar</button>
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
      url: "vista/categorias_listado.php",
      data:{
        nombre: $('#txtBusquedaNombre').val(),
        estado: $('#cboBusquedadEstado').val()
      }
    })
    .done(function(resultado){
      $('#divListadoCategoria').html(resultado);
    })
  }

  verListado();

  function guardarCategoria(){
    if(validarFormulario()){
      var datos = $('#formCategoria').serializeArray();
      var idcategoria = $('#idcategoria').val();
      if(idcategoria!=""){
        datos.push({name: "accion", value: "ACTUALIZAR"});
      }else{
        datos.push({name: "accion", value: "NUEVO"});
      }

      $.ajax({
        method: "POST",
        url: "controlador/contCategoria.php",
        data: datos,
        dataType: 'json'
      })
      .done(function(resultado){
        if(resultado.correcto==1){
          toastCorrecto(resultado.mensaje);
          $('#modalCategoria').modal('hide');
          $('#formCategoria').trigger('reset');
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
      toastError('Ingrese el nombre de la Categoria')
      correcto = false;
    }

    return correcto;
  }

  function abrirModalCategoria(){
    $('#formCategoria').trigger('reset');
    $('#idcategoria').val("");
    $('#modalCategoria').modal('show');
  }
</script>