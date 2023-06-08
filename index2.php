<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda por boton</title>
    <!-- Incluir los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Búsqueda de usuarios</h2>
        <div class="form-group">
            <input type="text" id="search" class="form-control" placeholder="Ingrese su búsqueda">
        </div>
        <div class="form-group">
            <label for="records_per_page">Registros por página:</label>
            <select id="records_per_page" class="form-control">
                <option value="4">4</option>
                <option value="6">6</option>
                <option value="10">10</option>
                <option value="15">15</option>
            </select>
        </div>
        <div class="form-group">
            <button id="search-btn" class="btn btn-primary">Buscar</button>
        </div>
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
            <tbody id="results"></tbody>
        </table>
        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var current_page = 1;
            var records_per_page = 5;

            function load_data(search, page, per_page) {
                $.ajax({
                    url: 'busqueda2.php',
                    method: 'GET',
                    data: { search: search, current_page: page, records_per_page: per_page },
                    dataType: 'json',
                    success: function(response) {
                        $('#results').html(response.results);
                        $('#pagination').html(response.pagination);
                    }
                });
            }

            function search() {
                var search = $('#search').val();
                load_data(search, current_page, records_per_page);
            }

            function updateRecordsPerPage() {
                var per_page = $('#records_per_page').val();
                records_per_page = per_page;
                var search = $('#search').val();
                load_data(search, current_page, records_per_page);
            }

            $(document).on('click', '.pagination-link', function() {
                var page = $(this).data('page');
                current_page = page;
                var search = $('#search').val();
                load_data(search, current_page, records_per_page);
            });

            $('#search').keyup(function() {
                clearTimeout($.data(this, 'timer'));
                var search = $(this).val();
                if (search.length >= 3) {
                    $(this).data('timer', setTimeout(search, 300));
                }
            });

            $('#search-btn').click(function() {
                search();
            });

            $('#records_per_page').change(function() {
                updateRecordsPerPage();
            });

            load_data('', current_page, records_per_page);
        });
    </script>
</body>
</html>
