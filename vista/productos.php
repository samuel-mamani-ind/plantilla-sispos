<?php
    require_once('../modelo/clsCategoria.php');
  require_once('../modelo/clsProducto.php');

    $objCat = new clsCategoria();
  $objPro = new clsProducto();

    $listaCategoria = $objCat->listarCategoria('','1');
    $listaCategoria = $listaCategoria->fetchAll(PDO::FETCH_NAMED);

  $listaUnidad = $objPro->consultarUnidad();
  $listaUnidad = $listaUnidad->fetchAll(PDO::FETCH_NAMED);

  $listaAfectacion = $objPro->consultarAfectacion();

?>
<section class="content-header">
  <div class="container-fluid">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Listado de Productos</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Codigo</span>
              </div>
              <!-- Con el evento onkeyup puedes realizar la busquedad cada vez que escriba una letra onkeyup="verListado()" -->
              <input type="text" class="form-control" name="txtBusquedaCodigo" id="txtBusquedaCodigo" onkeyup="if(event.keyCode=='13'){ verListado(); }" >
            </div>
          </div>
          <div class="col-md-4">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Nombre</span>
              </div>
              <input type="text" class="form-control" name="txtBusquedaNombre" id="txtBusquedaNombre" onkeyup="if(event.keyCode=='13'){ verListado(); }" >
            </div>
          </div>
          <div class="col-md-4">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Categoria</span>
              </div>
              <select class="form-control" name="cboBusquedadCategoria" id="cboBusquedadCategoria" onchange="verListado()">
                <option value="">- Todos -</option>
                <?php foreach($listaCategoria as $k=>$v){ ?>
                <option value="<?= $v['idcategoria'] ?>"><?= $v['nombre'] ?></option>
                <?php } ?>
              </select>
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
            <button type="button" class="btn btn-success" onclick="abrirModalProducto()"><i class="fa fa-plus"></i> Nuevo</button>
          </div>
        </div>
      </div>
    </div>
    <div class="card card-success">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12" id="divListadoProducto">

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade" id="modalProducto">
  <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary">
              <h4 class="modal-title">Producto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form name="formProducto" id="formProducto">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="codigo">Codigo</label>
                      <input type="text" class="form-control" id="codigobarra" name="codigobarra" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="nombre">Nombre</label>
                      <input type="text" class="form-control obligatorio" id="nombre" name="nombre" placeholder="Nombre Producto">
                      <input type="hidden" name="idproducto" id="idproducto" value="">
                    </div>
                    <div class="form-group">
                      <label for="pventa">Precio Venta</label>
                      <input type="number" step="0.01" class="form-control obligatorio" id="pventa" name="pventa" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="pcompra">Precio Compra</label>
                      <input type="number" step="0.01" class="form-control" id="pcompra" name="pcompra" placeholder="">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="stock">Stock</label>
                      <input type="number" step="0.01" class="form-control" id="stock" name="stock" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="stock">Stock de Seguridad</label>
                      <input type="stockseguridad" step="0.01" class="form-control" id="stockseguridad" name="stockseguridad" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="idunidad">Unidad</label>
                      <select name="idunidad" id="idunidad" class="form-control">
                        <option value="">- Seleccione -</option>
                        <?php foreach($listaUnidad as $k=>$v){ ?>
                          <option value="<?= $v['idunidad'] ?>"><?= $v['descripcion'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="idcategoria">Categoria</label>
                      <select name="idcategoria" id="idcategoria" class="form-control">
                        <option value="">- Seleccione -</option>
                        <?php foreach($listaCategoria as $k=>$v){ ?>
                          <option value="<?= $v['idcategoria'] ?>"><?= $v['nombre'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="idafectacion">Afectación</label>
                      <select name="idafectacion" id="idafectacion" class="form-control">
                        <?php while($fila = $listaAfectacion->fetch(PDO::FETCH_NAMED)){ ?>
                        <option value="<?= $fila['idafectacion'] ?>"><?= $fila['descripcion'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="afectoicbper">¿Afecto al ICBPER?</label>
                      <select name="afectoicbper" id="afectoicbper" class="form-control">
                        <option value="0">NO</option>
                        <option value="1">SI</option>
                      </select>
                    </div>
                    <div class="form-group">
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
                <button type="button" class="btn btn-primary" onclick="guardarProducto()" ><i class="fa fa-save"></i> Registrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modalProducto_Imagen">
  <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary">
              <h4 class="modal-title">Subir Imagen</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form name="formProducto_imagen" id="formProducto_imagen" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="nombre">Nombre</label>
                      <input type="text" class="form-control" id="nombre_imagen" name="nombre_imagen" disabled placeholder="Nombre Producto">
                      <input type="hidden" name="idproducto_imagen" id="idproducto_imagen" value="">
                    </div>
                    <div class="form-group">
                      <label for="urlimagen">Imagen</label>
                      <input type="text" class="form-control" disabled id="urlimagen" name="urlimagen" placeholder="">
                    </div>
                     <input name="uploadFile" id="uploadFile" class="file-loading" type="file" multiple data-min-file-count="1">
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
      url: "vista/productos_listado.php",
      data:{
        nombre: $('#txtBusquedaNombre').val(),
        estado: $('#cboBusquedadEstado').val(),
        idcategoria: $('#cboBusquedadCategoria').val(),
        codigo: $('#txtBusquedaCodigo').val()
      }
    })
    .done(function(resultado){
      $('#divListadoProducto').html(resultado);
    })
  }

  verListado();

  function guardarProducto(){
    if(validarFormulario()){
      var datos = $('#formProducto').serializeArray();
      var idproducto = $('#idproducto').val();
      if(idproducto!=""){
        datos.push({name: "accion", value: "ACTUALIZAR"});
      }else{
        datos.push({name: "accion", value: "NUEVO"});
      }

      $.ajax({
        method: "POST",
        url: "controlador/contProducto.php",
        data: datos,
        dataType: 'json'
      })
      .done(function(resultado){
        if(resultado.correcto==1){
          toastCorrecto(resultado.mensaje);
          $('#modalProducto').modal('hide');
          $('#formProducto').trigger('reset');
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

    $('.obligatorio').removeClass('is-invalid');

    if(nombre==""){
      toastError('Ingrese el nombre del Producto');
      $('#nombre').addClass('is-invalid');
      correcto = false;
    }

    return correcto;
  }

  function abrirModalProducto(){
        $('#formProducto').trigger('reset');
        $('#idproducto').val("");
        $('#modalProducto').modal('show');
        $('.obligatorio').removeClass('is-invalid');
  }

</script>