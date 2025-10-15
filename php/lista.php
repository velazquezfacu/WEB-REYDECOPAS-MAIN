<?php
session_start();

// 2. CORRECCIÓN DE SEGURIDAD: REDIRIGIR SI NO HAY SESIÓN INICIADA
if (!isset($_SESSION['email'])) {
    header("Location: sesion.php");
    exit();
}

// Crear conexión
$conexion = new mysqli("localhost", "root", "", "reyescopas");

// Verificar conexión
if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

$sql = "SELECT turnos.id, usuarios.nombre, turnos.dia, turnos.hora, usuarios.telefono 
        FROM turnos
        INNER JOIN usuarios ON turnos.usuario_id = usuarios.id";
$result = $conexion->query($sql);

$data = array();
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "0 results";
}
$conexion->close();
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
        
        // 1. Inicializar DataTables y configurar lenguaje
        // Usamos la inicialización que tienes para la tabla que cargas en PHP
        var table = $('#productTable').DataTable({
            "order": [
                [0, "asc"]
            ],
            // Usamos tu configuración de lenguaje DataTables:
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
            // Deshabilita el procesamiento por servidor ya que cargas los datos en PHP
            "bServerSide": false,
        });


        // 2. LÓGICA DEL BOTÓN MODIFICAR (sin cambios, ya estaba bien)
        $('#productTable tbody').on('click', '.modificar-btn', function() {
            var fila = $(this).closest('tr');
            // DataTables te devuelve los datos de la fila como un array de valores
            var datosFila = table.row(fila).data(); 
            
            $('#idInput').val(datosFila[0]);
            $('#nombreInput').val(datosFila[1]);
            $('#diaInput').val(datosFila[2]);
            $('#horarioInput').val(datosFila[3]);
            $('#contactoInput').val(datosFila[4]);

            $('#modificarModal').modal('show');
        });

        // 3. LÓGICA DE GUARDAR CAMBIOS (sin cambios)
        $('#guardarCambiosBtn').on('click', function() {
            var id = $('#idInput').val();
            var nombre = $('#nombreInput').val();
            var dia = $('#diaInput').val();
            var hora = $('#horarioInput').val();
            var contacto = $('#contactoInput').val();
            
            // Actualizar la fila en el DataTables (lado del cliente)
            var fila = table.row(function(idx, data, node) {
                return data[0] === id;
            }).node();
            
            table.cell(fila, 1).data(nombre);
            table.cell(fila, 2).data(dia);
            table.cell(fila, 3).data(hora);
            table.cell(fila, 4).data(contacto).draw();

            // Llamada AJAX a modificar.php
            $.ajax({
                url: 'php/modificar.php',
                method: 'POST',
                data: { id: id, hora: hora, dia: dia, nombre: nombre }, // Añadí 'nombre' para completar
            });

            $('#modificarModal').modal('hide');
        });


        // 4. LÓGICA DEL BOTÓN ELIMINAR (sin cambios, ya estaba bien)
        var filaAEliminar; 

        $('#productTable tbody').on('click', '.eliminar-btn', function() {
            filaAEliminar = $(this).closest('tr');
            $('#eliminarModal').modal('show');
        });

        $('#confirmarEliminarBtn').on('click', function() {
            var datosFila = table.row(filaAEliminar).data();
            var id = datosFila[0];

            $.ajax({
                url: 'php/eliminar.php',
                method: 'POST',
                data: { id: id },
                success: function(response) {
                    table.row(filaAEliminar).remove().draw();
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


</body>

</html>