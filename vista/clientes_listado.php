<?php
	require_once('../modelo/clsCliente.php');

	$objCli = new clsCliente();

	$nombre = $_POST['nombre'];
	$estado = $_POST['estado'];

	$listaCliente = $objCli->listarCliente($nombre, $estado);
	$listaCliente = $listaCliente->fetchAll(PDO::FETCH_NAMED);


?>
<table id="tableCliente" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>COD</th>
			<th>NOMBRE</th>
			<th>TIPO DOC.</th>
			<th>NRO. DOC.</th>
			<th>DIRECCION</th>
			<th>ESTADO</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($listaCliente as $key => $value) { 
			$class = "";
			if($value['estado']==0){
				$class = "text-red";
			}
		?>
		<tr class="<?= $class ?>">
			<td><?= $value['idcliente'] ?></td>
			<td><?= $value['nombre'] ?></td>
			<td><?= $value['idtipodocumento'] ?></td>
			<td><?= $value['nrodocumento'] ?></td>
			<td><?= $value['direccion'] ?></td>
			<td>
				<?php
					if($value['estado']==1){
						echo "Activo";
					}else{
						echo "Anulado";
					}
				?>		
			</td>
			<td>
				<button type="button" class="btn btn-info btn-sm" onclick="editarCliente(<?= $value['idcliente'] ?>)"><i class="fa fa-edit"></i> Editar</button>
			</td>
			<td>
				<?php if($value['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" onclick="cambiarEstadoCliente(<?= $value['idcliente'] ?>,0)"><i class="fa fa-trash"></i> Anular</button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" onclick="cambiarEstadoCliente(<?= $value['idcliente'] ?>,1)"><i class="fa fa-check"></i> Activar</button>
				<?php } ?>
			</td>
			<td><button type="button" class="btn btn-danger btn-sm" onclick="cambiarEstadoCliente(<?= $value['idcliente'] ?>,2)"><i class="fa fa-times"></i> Eliminar</button></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<script>
	$("#tableCliente").DataTable({
    	"responsive": true, 
    	"lengthChange": true, 
    	"autoWidth": false,
    	"searching": false,
    	"ordering": true,
    	//Mantener la Cabecera de la tabla Fija
    	// "scrollY": '200px',
        // "scrollCollapse": true,
        // "paging": false,
    	"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
    	"language": {
			"decimal":        "",
		    "emptyTable":     "Sin datos",
		    "info":           "Del _START_ al _END_ de _TOTAL_ filas",
		    "infoEmpty":      "Del 0 a 0 de 0 filas",
		    "infoFiltered":   "(filtro de _MAX_ filas totales)",
		    "infoPostFix":    "",
		    "thousands":      ",",
		    "lengthMenu":     "Ver _MENU_ filas",
		    "loadingRecords": "Cargando...",
		    "processing":     "Procesando...",
		    "search":         "Buscar:",
		    "zeroRecords":    "No se encontraron resultados",
		    "paginate": {
		        "first":      "Primero",
		        "last":       "Ultimo",
		        "next":       "Siguiente",
		        "previous":   "Anterior"
		    },
		    "aria": {
		        "sortAscending":  ": orden ascendente",
		        "sortDescending": ": orden descendente"
		    }
		},
    	"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#tableCliente_wrapper .col-md-6:eq(0)');

    function editarCliente(id){
    	$.ajax({
    		method: "POST",
    		url: "controlador/contCliente.php",
    		data: {
    			accion: 'CONSULTAR_CLIENTE',
    			idcliente: id
    		},
    		dataType: "json"
    	})
    	.done(function(resultado){
    		console.log(resultado);
    		$('#nombre').val(resultado.nombre);
    		$('#idtipodocumento').val(resultado.idtipodocumento);
    		$('#nrodocumento').val(resultado.nrodocumento);
    		$('#direccion').val(resultado.direccion);
    		$('#estado').val(resultado.estado);
    		// $('#formCategoria').trigger('reset');
	    	$('#idcliente').val(id);
	    	$('#modalCliente').modal('show');
    	});    	
    }

    function cambiarEstadoCliente(idcliente, estado){
    	proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
    	mensaje = "¿Esta Seguro de <b>"+proceso[estado]+"</b> el cliente?";
    	accion = "EjecutarCambiarEstadoCliente("+idcliente+","+estado+")";

    	mostrarModalConfirmacion(mensaje, accion);

    }

    function EjecutarCambiarEstadoCliente(idcliente,estado){
    	$.ajax({
    		method: 'POST',
    		url: 'controlador/contCliente.php',
    		data:{
    			'accion': 'CAMBIAR_ESTADO_CLIENTE',
    			'idcliente': idcliente,
    			'estado': estado
    		},
    		dataType: 'json'
    	})
    	.done(function(resultado){
    		if(resultado.correcto==1){
    			toastCorrecto(resultado.mensaje);
    			verListado();
    		}else{
    			toastError(resultado.mensaje);
    		}
    	});
    }
</script>