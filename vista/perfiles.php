<?php
require_once('../modelo/clsPerfil.php');

$objPer = new clsPerfil();

$listaOpciones = $objPer->listaGeneralOpcion();
$listaOpciones = $listaOpciones->fetchAll(PDO::FETCH_NAMED);

?>
<section class="content-header">
  <div class="container-fluid">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Listado de Perfiles</h3>
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
            <button type="button" class="btn btn-success" onclick="abrirModalPerfil()"><i class="fa fa-plus"></i> Nuevo</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-success">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12" id="divListadoPerfil">

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="modalPerfil">
  <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary">
              <h4 class="modal-title">Perfil</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form name="formPerfil" id="formPerfil">
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                    <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="">
                <input type="hidden" name="idperfil" id="idperfil" value="">
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
                <button type="button" class="btn btn-primary" onclick="guardarPerfil()" ><i class="fa fa-save"></i> Registrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modalPerfil_permisos">
  <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-primary">
              <h4 class="modal-title">Permisos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form id="formPerfil_permiso" name="formPerfil_permiso">
                <div class="row">
                  <div class="col-md-12">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="bg-info">
                          <tr>
                            <th>
                              <input type="checkbox" id="permiso_all" onclick="seleccionarOpciones()" />
                            </th>
                            <th>Opcion
                              <input type="hidden" name="idperfil_permiso" id="idperfil_permiso" value="0">
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach($listaOpciones as $k=>$v){ ?>
                            <tr>
                              <td>
                                <input type="checkbox" value="" class="permiso" name="permiso<?= $v['idopcion'] ?>" id="permiso<?= $v['idopcion'] ?>" idopcion="<?= $v['idopcion'] ?>" onclick="verificarPermiso(<?= $v['idopcion'] ?>)" >
                              </td>
                              <td>
                                <?= $v['descripcion'] ?>
                              </td>
                            </tr>
                          <?php } ?>
                        </tbody>
                    </table>
                  </div>
                </div>
              </form>
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
      url: "vista/perfiles_listado.php",
      data:{
        nombre: $('#txtBusquedaNombre').val(),
        estado: $('#cboBusquedadEstado').val()
      }
    })
    .done(function(resultado){
      $('#divListadoPerfil').html(resultado);
    })
  }

  verListado();

  function guardarPerfil(){
    if(validarFormulario()){
      var datos = $('#formPerfil').serializeArray();
      var idperfil = $('#idperfil').val();
      if(idperfil!=""){
        datos.push({name: "accion", value: "ACTUALIZAR"});
      }else{
        datos.push({name: "accion", value: "NUEVO"});
      }

      $.ajax({
        method: "POST",
        url: "controlador/contPerfil.php",
        data: datos,
        dataType: 'json'
      })
      .done(function(resultado){
        if(resultado.correcto==1){
          toastCorrecto(resultado.mensaje);
          $('#modalPerfil').modal('hide');
          $('#formPerfil').trigger('reset');
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

  function abrirModalPerfil(){
    $('#formPerfil').trigger('reset');
    $('#idperfil').val("");
    $('#modalPerfil').modal('show');
  }
</script>