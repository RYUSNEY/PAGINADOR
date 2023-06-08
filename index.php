<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda en tiempo real</title>
    <!-- Incluir los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Búsqueda en tiempo real</h1>
        
        <!-- Formulario con campo de búsqueda -->
        <form class="mb-4">
            <div class="form-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Ingresa el nombre, apellido paterno o apellido materno">
            </div>
        </form>
        
        <!-- Tabla para mostrar los resultados de la búsqueda -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Edad</th>
                </tr>
            </thead>
            <tbody id="resultado"></tbody>
        </table>

        <!-- Paginación -->
        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>

    <!-- Incluir jQuery y el script para realizar la búsqueda en tiempo real -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var current_page = 1;
            var records_per_page = 4;

            // Escuchar el evento keyup en el campo de búsqueda
            $('#searchInput').keyup(function() {
                current_page = 1; // Reiniciar la página actual al realizar una nueva búsqueda
                mostrarResultados();
            });

            function mostrarResultados() {
                var search = $('#searchInput').val();

                // Realizar la búsqueda en tiempo real
                $.ajax({
                    url: 'busqueda.php',
                    type: 'GET',
                    data: {
                        search: search,
                        current_page: current_page,
                        records_per_page: records_per_page
                    },
                    success: function(data) {
                        $('#resultado').html(data.results);
                        $('#pagination').html(data.pagination);
                    }
                });
            }

            // Función para cambiar de página
            $(document).on('click', '.pagination-link', function(e) {
                e.preventDefault();
                current_page = $(this).text();
                mostrarResultados();
            });

            mostrarResultados();
        });
    </script>
</body>
</html>
