<?php
	require_once('../modelo/clsPerfil.php');

	$objPer = new clsPerfil();

	$nombre = $_POST['nombre'];
	$estado = $_POST['estado'];

	$listaPerfil = $objPer->listarPerfil($nombre, $estado);
	$listaPerfil = $listaPerfil->fetchAll(PDO::FETCH_NAMED);


?>
<table id="tablePerfil" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>COD</th>
			<th>PERFIL</th>
			<th>ESTADO</th>
			<th>PERMISOS</th>
			<th>EDITAR</th>
			<th>ANULAR</th>
			<th>ELIMINAR</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($listaPerfil as $key => $value) { 
			$class = "";
			if($value['estado']==0){
				$class = "text-red";
			}
		?>
		<tr class="<?= $class ?>">
			<td><?= $value['idperfil'] ?></td>
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
				<button type="button" class="btn bg-navy btn-sm" onclick="asignarPermisos(<?= $value['idperfil'] ?>)"><i class="fa fa-lock"></i> Permisos</button>
			</td>
			<td>
				<button type="button" class="btn btn-info btn-sm" onclick="editarPerfil(<?= $value['idperfil'] ?>)"><i class="fa fa-edit"></i> Editar</button>
			</td>
			<td>
				<?php if($value['estado']==1){ ?>
				<button type="button" class="btn btn-warning btn-sm" onclick="cambiarEstadoPerfil(<?= $value['idperfil'] ?>,0)"><i class="fa fa-trash"></i> Anular</button>
				<?php }else{ ?>
				<button type="button" class="btn btn-success btn-sm" onclick="cambiarEstadoPerfil(<?= $value['idperfil'] ?>,1)"><i class="fa fa-check"></i> Activar</button>
				<?php } ?>
			</td>
			<td><button type="button" class="btn btn-danger btn-sm" onclick="cambiarEstadoPerfil(<?= $value['idperfil'] ?>,2)"><i class="fa fa-times"></i> Eliminar</button></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<script>
	$("#tablePerfil").DataTable({
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
    }).buttons().container().appendTo('#tablePerfil_wrapper .col-md-6:eq(0)');

    function editarPerfil(id){
    	$.ajax({
    		method: "POST",
    		url: "controlador/contPerfil.php",
    		data: {
    			accion: 'CONSULTAR_PERFIL',
    			idperfil: id
    		},
    		dataType: "json"
    	})
    	.done(function(resultado){
    		console.log(resultado);
    		$('#nombre').val(resultado.nombre);
    		$('#estado').val(resultado.estado);
    		// $('#formCategoria').trigger('reset');
	    	$('#idperfil').val(id);
	    	$('#modalPerfil').modal('show');
    	});    	
    }

    function cambiarEstadoPerfil(idperfil, estado){
    	proceso = new Array('ANULAR','ACTIVAR','ELIMINAR');
    	mensaje = "¿Esta Seguro de <b>"+proceso[estado]+"</b> el perfil?";
    	accion = "EjecutarCambiarEstadoPerfil("+idperfil+","+estado+")";

    	mostrarModalConfirmacion(mensaje, accion);

    }

    function EjecutarCambiarEstadoPerfil(idperfil,estado){
    	$.ajax({
    		method: 'POST',
    		url: 'controlador/contPerfil.php',
    		data:{
    			'accion': 'CAMBIAR_ESTADO_PERFIL',
    			'idperfil': idperfil,
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


    function asignarPermisos(id){
    	$('#formPerfil_permiso').trigger('reset');
    	$.ajax({
    		method: "POST",
    		url: "controlador/contPerfil.php",
    		data: {
    			accion: 'CONSULTAR_ACCESO',
    			idperfil: id
    		},
    		dataType: "json"
    	})
    	.done(function(resultado){
    		console.log(resultado);
    		if(resultado.length>0){
    			for(i=0; i<resultado.length; i++){
    				$('#permiso'+resultado[i].idopcion).prop("checked",true);
    			}
    		}
    		verificarCheckGlobal();
	    	$('#idperfil_permiso').val(id);
	    	$('#modalPerfil_permisos').modal('show');
    	});    	
    }

    function verificarPermiso(idopcion){
    	asignar = 0;
    	if($("#permiso"+idopcion).is(':checked')){
    		asignar = 1;
    	}
    	$.ajax({
    		method: "POST",
    		url: "controlador/contPerfil.php",
    		data: {
    			accion: 'VERIFICAR_ACCESO',
    			idperfil: $('#idperfil_permiso').val(),
    			idopcion: idopcion,
    			estado: asignar
    		},
    		dataType: "json"
    	})
    	.done(function(resultado){
    		if(resultado.correcto==1){
    			toastCorrecto(resultado.mensaje);
    			verificarCheckGlobal();
    		}else{
    			toastError(resultado.mensaje);
    		}
    	}); 

    }


    function seleccionarOpciones(){
    	var idopcion = [];
    	$('.permiso').each(function(){
    		if($('#permiso_all').is(':checked')){
    			$('#'+this.id).prop("checked",true);
    		}else{
    			$('#'+this.id).prop("checked",false);
    		}

    		verificarPermiso($('#'+this.id).attr('idopcion'));
    	});
    }

    function verificarCheckGlobal(){
    	$('.permiso').each(function(){
    		activo = true;
    		if(!($('#'+this.id).is(':checked'))){
    			activo = false;
    		}
    	});

    	if(activo){
    		$('#permiso_all').prop("checked",true);
    	}else{
    		$('#permiso_all').prop("checked",false);
    	}
    }
</script>