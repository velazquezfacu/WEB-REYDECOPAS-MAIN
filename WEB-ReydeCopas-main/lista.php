<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nutricionista";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT turnos.id, usuarios.nombre, turnos.dia, turnos.hora, usuarios.telefono 
        FROM turnos
        INNER JOIN usuarios ON turnos.usuario_id = usuarios.id";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Lista de turnos</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
	<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>


<body>

	<main class="container">
		<h3 class="text-center pt-3">Lista de turnos</h3>

		<div class="table-responsive-sm">
			<table id="productTable" class="display">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Dia</th>
						<th>Horario</th>
						<th>Contacto</th>
						<th width="5%"></th>
						<th width="5%"></th>
					</tr>
				</thead>

				<tbody>
                <?php foreach($data as $row): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['dia']; ?></td>
                <td><?php echo $row['hora']; ?></td>
                <td><?php echo $row['telefono']; ?></td>
                <td><button class="modificar-btn btn btn-primary">Modificar</button></td>
                <td><button class="eliminar-btn btn btn-danger">Eliminar</button></td>
            </tr>
            <?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</main>

<!-- Modal Modificar -->
<div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modificar Datos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modificarForm">
                    <div class="form-group">
                        <label for="idInput">ID</label>
                        <input type="text" class="form-control" id="idInput" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nombreInput">Nombre</label>
                        <input type="text" class="form-control" id="nombreInput">
                    </div>
					<div class="form-group">
                        <label for="diaInput">Dia</label>
                        <input type="date" class="form-control" id="diaInput">
                    </div>
					<div class="form-group">
                        <label for="horarioInput">Horario</label>
                        <input type="text" class="form-control" id="horarioInput">
                    </div>
                    <div class="form-group">
                        <label for="contactoInput">Contacto</label>
                        <input type="text" class="form-control" id="contactoInput" readonly>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarCambiosBtn">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal Eliminar -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este turno?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminarBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modificar y Eliminar valores de la tabla -->
	<script>
				$(document).ready(function() {
			// Inicializar DataTable
			var table = $('#productTable').DataTable();

			// Manejar el evento click del botón "Modificar"
			$('#productTable tbody').on('click', '.modificar-btn', function() {
				// Obtener la fila actual
				var fila = $(this).closest('tr');
				
				// Obtener los datos de la fila
				var datosFila = table.row(fila).data();
				
				// Rellenar el formulario del modal con los datos de la fila
				$('#idInput').val(datosFila[0]);
				$('#nombreInput').val(datosFila[1]);
				$('#diaInput').val(datosFila[2]);
				$('#horarioInput').val(datosFila[3]);
				$('#contactoInput').val(datosFila[4]);

				// Mostrar el modal
				$('#modificarModal').modal('show');
			});

			// Manejar el evento click del botón "Guardar Cambios"
			$('#guardarCambiosBtn').on('click', function() {
				// Obtener los valores del formulario
				var id = $('#idInput').val();
				var nombre = $('#nombreInput').val();
				var dia = $('#diaInput').val();
				var hora = $('#horarioInput').val();
				var contacto = $('#contactoInput').val();

				// Actualizar los datos en la tabla
				var fila = table.row(function(idx, data, node) {
					return data[0] === id;
				}).node();
				
				table.cell(fila, 1).data(nombre);
				table.cell(fila, 2).data(dia);
				table.cell(fila, 3).data(hora);
				table.cell(fila, 4).data(contacto).draw();

				$.ajax({
					url: 'php/modificar.php',
					method: 'POST',
					data: {
						id: id,
						hora: hora,
						dia: dia,
					},
				});

				// Cerrar el modal
				$('#modificarModal').modal('hide');
			});


			// Manejar el evento click del botón "Eliminar"
			$('#productTable tbody').on('click', '.eliminar-btn', function() {
			// Obtener la fila actual
			filaAEliminar = $(this).closest('tr');
			
			// Mostrar el modal de confirmación
			$('#eliminarModal').modal('show');
		});

		// Manejar el evento click del botón "Confirmar Eliminar" en el modal
		$('#confirmarEliminarBtn').on('click', function() {
			// Obtener los datos de la fila
			var datosFila = table.row(filaAEliminar).data();
			var id = datosFila[0];

			// Enviar solicitud AJAX para eliminar el registro en la base de datos
			$.ajax({
				url: 'php/eliminar.php',
				method: 'POST',
				data: { 
					id: id
				},
				success: function(response) {
					// Eliminar la fila de la tabla
					table.row(filaAEliminar).remove().draw();
					// Cerrar el modal de confirmación
					$('#eliminarModal').modal('hide');
					console.log('Registro eliminado correctamente');
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.error('Error al eliminar el registro: ' + textStatus, errorThrown);
				}
			});
		});
		});
	</script>
  
	<script>
        $(document).ready(function() {
            $('#productTable').DataTable();
        });
    </script>

	<script>
		$(document).ready(function() {
			$('#mitabla').DataTable({
				"order": [
					[0, "asc"]
				],
				"language": {
					"lengthMenu": "Mostrar _MENU_ registros por pagina",
					"info": "Mostrando pagina _PAGE_ de _PAGES_",
					"infoEmpty": "No hay registros disponibles",
					"infoFiltered": "(filtrada de _MAX_ registros)",
					"loadingRecords": "Cargando...",
					"processing": "Procesando...",
					"search": "Buscar:",
					"zeroRecords": "No se encontraron registros coincidentes",
					"paginate": {
						"next": "Siguiente",
						"previous": "Anterior"
					},
				},
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "server_process.php"
			});
		});

		let eliminaModal = document.getElementById('eliminaModal')
		eliminaModal.addEventListener('shown.bs.modal', event => {
			let button = event.relatedTarget
			let url = button.getAttribute('data-bs-href')
			eliminaModal.querySelector('.modal-footer .btn-ok').href = url
		})
	</script>



</body>

</html>