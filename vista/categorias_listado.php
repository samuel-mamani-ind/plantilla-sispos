<?php
	require_once('../modelo/clsCategoria.php');

	$objCat = new clsCategoria();

	$nombre = $_POST['nombre'];
	$estado = $_POST['estado'];

	$listaCategoria = $objCat->listarCategoria($nombre, $estado);
	$listaCategoria = $listaCategoria->fetchAll(PDO::FETCH_NAMED);


?>
<table id="tableCategoria" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>COD</th>
			<th>DESCRIPCION</th>
			<th>ESTADO</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($listaCategoria as $key => $value) { 
			$class = "";
			if($value['estado']==0){
				$class = "text-red";
			}
		?>
		<tr class="<?= $class ?>">
			<td><?= $value['idcategoria'] ?></td>
			<td><?= $value['nombre'] ?></td>
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
				<button type="button" class="btn btn-info btn-sm" onclick="editarCategoria(<?= $value['idcategoria'] ?>)"><i class="fa fa-edit"></i> Editar</button>
			</td>
			<td>
				<?php if($value['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" onclick="cambiarEstadoCategoria(<?= $value['idcategoria'] ?>,0)"><i class="fa fa-trash"></i> Anular</button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" onclick="cambiarEstadoCategoria(<?= $value['idcategoria'] ?>,1)"><i class="fa fa-check"></i> Activar</button>
				<?php } ?>
			</td>
			<td><button type="button" class="btn btn-danger btn-sm" onclick="cambiarEstadoCategoria(<?= $value['idcategoria'] ?>,2)"><i class="fa fa-times"></i> Eliminar</button></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<script>
	$("#tableCategoria").DataTable({
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
    }).buttons().container().appendTo('#tableCategoria_wrapper .col-md-6:eq(0)');

    function editarCategoria(id){
    	$.ajax({
    		method: "POST",
    		url: "controlador/contCategoria.php",
    		data: {
    			accion: 'CONSULTAR_CATEGORIA',
    			idcategoria: id
    		},
    		dataType: "json"
    	})
    	.done(function(resultado){
    		console.log(resultado);
    		$('#nombre').val(resultado.nombre);
    		$('#estado').val(resultado.estado);
    		// $('#formCategoria').trigger('reset');
	    	$('#idcategoria').val(id);
	    	$('#modalCategoria').modal('show');
    	});    	
    }

    function cambiarEstadoCategoria(idcategoria, estado){
    	proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
    	mensaje = "¿Esta Seguro de <b>"+proceso[estado]+"</b> la categoria?";
    	accion = "EjecutarCambiarEstadoCategoria("+idcategoria+","+estado+")";

    	mostrarModalConfirmacion(mensaje, accion);

    }

    function EjecutarCambiarEstadoCategoria(idcategoria,estado){
    	$.ajax({
    		method: 'POST',
    		url: 'controlador/contCategoria.php',
    		data:{
    			'accion': 'CAMBIAR_ESTADO_CATEGORIA',
    			'idcategoria': idcategoria,
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