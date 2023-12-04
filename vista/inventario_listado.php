<?php
	require_once('../modelo/clsProducto.php');

	$objPro = new clsProducto();

	$nombre = $_POST['nombre'];
	$estado = $_POST['estado'];
	$idcategoria = $_POST['idcategoria'];
	$codigo = $_POST['codigo'];
	$filtro = $_POST['filtro'];

	$listaProducto = $objPro->listarProducto($codigo, $nombre, $idcategoria, $estado, $filtro);
	$listaProducto = $listaProducto->fetchAll(PDO::FETCH_NAMED);


?>
<table id="tableProducto" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>COD</th>
			<th>CODIGO</th>
			<th>PRODUCTO</th>
			<th>UNIDAD</th>
			<th class="bg-maroon">STOCK</th>
			<th class="bg-orange">STOCK SEGURIDAD</th>
			<th>ESTADO</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($listaProducto as $key => $value) { 
			$class = "";
			$tdclass = "";
			if($value['estado']==0){
				$class = "text-red";
				$tdclass = "bg-danger";
			}
		?>
		<tr class="<?= $class ?>">
			<td><?= $value['idproducto'] ?></td>
			<td><?= $value['codigobarra'] ?></td>
			<td><?= $value['nombre'] ?></td>
			<td><?= $value['unidad'] ?></td>
			<td style="font-weight: bold; text-align: right;" class="bg-maroon"><?= $value['stock'] ?></td>
			<td style="font-weight: bold; text-align: right;" class="bg-orange"><?= $value['stockseguridad'] ?></td>
			<!-- <td><?= $value['pcompra'] ?></td> -->
			<td data-sort="<?= $value['stock']>=$value['stockseguridad']?1:0 ?>" class="<?= $tdclass; ?>" style="text-align: center;">
				<?php
					if($value['stock']>=$value['stockseguridad']){
						if($value['stock']<=0){
							echo "<i class='fa fa-thumbs-down text-red fa-2x'></i>";
						}else{
							echo "<i class='fa fa-thumbs-up text-success fa-2x'></i>";
						}
					}else{
						echo "<i class='fa fa-thumbs-down text-red fa-2x'></i>";
					}
				?>		
			</td>
			<td>
				<button type="button" class="btn btn-info btn-sm" onclick="kardexProducto(<?= $value['idproducto'] ?>)"><i class="fa fa-eye"></i> Ver</button>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<script>
	$("#tableProducto").DataTable({
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
    }).buttons().container().appendTo('#tableProducto_wrapper .col-md-6:eq(0)');

    function kardexProducto(id){
    	$.ajax({
    		method: "POST",
    		url: "controlador/contProducto.php",
    		data: {
    			accion: 'DETALLE_VENTA_PRODUCTO',
    			idproducto: id
    		}
    	})
    	.done(function(resultado){
    		// $('#nombre').val(resultado.nombre);
    		// $('#estado').val(resultado.estado);
    		// $('#codigobarra').val(resultado.codigobarra);
    		// $('#pventa').val(resultado.pventa);
    		// $('#pcompra').val(resultado.pcompra);
    		// $('#stock').val(resultado.stock);
    		// $('#idunidad').val(resultado.idunidad);
    		// $('#idcategoria').val(resultado.idcategoria);
    		// $('#idafectacion').val(resultado.idafectacion);
    		// $('#afectoicbper').val(resultado.afectoicbper);
    		// $('#stockseguridad').val(resultado.stockseguridad);

	    	// $('#idproducto').val(id);
	    	$('#tablaKardex').html(resultado);
	    	$('#modalProductoKardex').modal('show');
    	});    	
    }

    function cambiarEstadoProducto(idproducto, estado){
    	proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
    	mensaje = "¿Esta Seguro de <b>"+proceso[estado]+"</b> el producto?";
    	accion = "EjecutarCambiarEstadoProducto("+idproducto+","+estado+")";

    	mostrarModalConfirmacion(mensaje, accion);

    }

    function EjecutarCambiarEstadoProducto(idproducto,estado){
    	$.ajax({
    		method: 'POST',
    		url: 'controlador/contProducto.php',
    		data:{
    			'accion': 'CAMBIAR_ESTADO_PRODUCTO',
    			'idproducto': idproducto,
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


    function subirImagen(id){
    	$.ajax({
    		method: "POST",
    		url: "controlador/contProducto.php",
    		data: {
    			accion: 'CONSULTAR_PRODUCTO',
    			idproducto: id
    		},
    		dataType: "json"
    	})
    	.done(function(resultado){
    		$('#nombre_imagen').val(resultado.nombre);
    		$('#urlimagen').val(resultado.urlimagen);

    		// $('#formCategoria').trigger('reset');
	    	$('#idproducto_imagen').val(id);

	    	$("#uploadFile").fileinput({
					language: 'es',
					showRemove: false,
					uploadAsync: true,
					uploadExtraData: {
						accion: 'SUBIR_IMAGEN', 
						idproducto: $('#idproducto_imagen').val()
					},
					uploadUrl: 'controlador/contProducto.php',
					maxFileCount: 1,
					autoReplace: true, 
					allowedFileExtensions: ['jpg','png','jpeg']
			}).on('fileuploaded', function(event, data, id, index) {
			    $('#modalProducto_Imagen').modal('hide');
			    verListado();
			    $('#uploadFile').fileinput('destroy');
			})


	    	$('#modalProducto_Imagen').modal('show');
    	});    	
    }
</script>