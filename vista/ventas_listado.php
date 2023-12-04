<?php 
include_once("../modelo/clsVenta.php");
$objVen = new clsVenta();
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
$cliente = $_POST['cliente'];
$tipocomprobante = $_POST['tipocomprobante'];
$correlativo = $_POST['correlativo'];
$listado = $objVen->listarVenta($desde, $hasta,"%".$cliente."%",$tipocomprobante,$correlativo);
?>
<table id="tablaVenta" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th>Comprobante</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Editar</th>
            <th>Anular</th>
            <th>Eliminar</th>
            <th>PDF</th>
            <th>Ticket</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($listado as $k=>$v){ 
            $bgclass = $v['estado']==1?"bg-warning":"bg-success";
            $icono = $v['estado']==1?"fa fa-trash":"fa fa-check";
            $texto = $v['estado']==1?"Anular":"Activar";
            $estado = $v['estado']==1?0:1;

            $bgclasstr = $v['estado']==0?"text-danger":"";
            $documento = $v['comprobante'].' '.$v['serie'].'-'.$v['correlativo'];
            ?>
            <tr class="<?= $bgclasstr ?>">
                <td><?= $v['idventa'] ?></td>
                <td><?= $v['fecha'] ?></td>
                <td><?= $documento ?></td>
                <td><?= $v['cliente'] ?></td>
                <td><?= $v['total'] ?></td>
                <td><?= $v['estado']==1?"ACTIVO":"ANULADO"; ?></td>
                <td><button onclick="EditarVenta(<?= $v['idventa'] ?>)" class="btn bg-info btn-sm"><i class="fa fa-edit"></i> Editar</button></td>
                <td><button onclick="CambiarEstadoVenta(<?= $v['idventa'] ?>,<?= $estado ?>,'<?= $documento ?>')" class="btn <?= $bgclass ?> btn-sm"><i class="<?= $icono ?>"></i><?= $texto ?></button></td>
                <td><button onclick="CambiarEstadoVenta(<?= $v['idventa'] ?>,2,'<?= $documento ?>')" class="btn bg-danger btn-sm"><i class="fa fa-times"></i> Eliminar</button></td>
                <td><a class="btn btn-sm bg-primary" href="vista/pdfVenta.php?id=<?= $v['idventa'] ?>" target="_blank"><i class="fa fa-file-pdf"></i> PDF</a></td>
                <td><a class="btn btn-sm bg-maroon" href="vista/pdfTicket.php?id=<?= $v['idventa'] ?>" target="_blank"><i class="fa fa-file-pdf"></i> Ticket</a></td>
            </tr>
        <?php }?>
    </tbody>
</table>
<script>
$('#tablaVenta').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "order":[[1,'asc']],
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#tablaVenta_wrapper .col-md-6:eq(0)');

function EditarVenta(idventa){
    $.ajax({
        method: "POST",
        url: "vista/ventas_formulario.php",
        data:{
            'proceso': "EDITAR",
            'idventa': idventa
        }
    }).done(function(resultado){
        $("#divPrincipal").html(resultado);
    });
}

function CambiarEstadoVenta(idventa, estado, documento){
    proceso = estado==0?"ANULAR":(estado==1?"ACTIVAR":"ELIMINAR");
    mensaje = "¿Esta seguro de <b>"+proceso+"</B> el comprobante <b>"+documento+"</b>?";
    accion = "EjecutarCambiarEstadoVenta("+idventa+",'"+proceso+"')";
    mostrarModalConfirmacion(mensaje, accion);
}

function EjecutarCambiarEstadoVenta(idventa,proceso){    
    $.ajax({
        method: "POST",
        url: "controlador/contVenta.php",
        data: {
            'accion': proceso,
            'idventa': idventa
        }
    }).done(function(resultado){
        if(resultado==1){
            toastCorrecto("Cambio de estado satisfactorio.");
            listarVentas();
        }else if(resultado==0){
            toastError("Problemas en la actualización de estado. Inténtelo nuevamente.");
        }else{
            toastError(resultado);
        }
    });
}
</script>