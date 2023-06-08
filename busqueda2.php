<?php
// Conexión a la base de datos
require_once 'conexion.php';

$search = $_GET['search'];
$current_page = $_GET['current_page'];
$records_per_page = $_GET['records_per_page'];

// Calcular el offset para la consulta
$offset = ($current_page - 1) * $records_per_page;

// Consulta SQL para obtener los resultados paginados
$query = "SELECT * FROM usuarios WHERE nombre LIKE '%$search%' OR ape_pat LIKE '%$search%' OR ape_mat LIKE '%$search%' LIMIT $offset, $records_per_page";
$result = mysqli_query($conn, $query);

// Consulta SQL para obtener el total de resultados sin paginación
$count_query = "SELECT COUNT(*) as total_count FROM usuarios WHERE nombre LIKE '%$search%' OR ape_pat LIKE '%$search%' OR ape_mat LIKE '%$search%'";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_count = $count_row['total_count'];

// Calcular el total de páginas
$total_pages = ceil($total_count / $records_per_page);

// Generar el HTML de los resultados
$results_html = '';
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $results_html .= '<tr>';
        $results_html .= '<td>' . $row['id'] . '</td>';
        $results_html .= '<td>' . $row['nombre'] . '</td>';
        $results_html .= '<td>' . $row['ape_pat'] . '</td>';
        $results_html .= '<td>' . $row['ape_mat'] . '</td>';
        $results_html .= '<td>' . $row['edad'] . '</td>';
        $results_html .= '</tr>';
    }
} else {
    $results_html .= '<tr><td colspan="5">No se encontraron resultados.</td></tr>';
}

// Generar el HTML de la paginación
$pagination_html = '';
if ($total_pages > 1) {
    $pagination_html .= '<li class="page-item ' . ($current_page == 1 ? 'disabled' : '') . '"><a class="page-link pagination-link" href="#" data-page="1">Primera</a></li>';
    $pagination_html .= '<li class="page-item ' . ($current_page == 1 ? 'disabled' : '') . '"><a class="page-link pagination-link" href="#" data-page="' . ($current_page - 1) . '">Anterior</a></li>';

    for ($i = 1; $i <= $total_pages; $i++) {
        $pagination_html .= '<li class="page-item ' . ($current_page == $i ? 'active' : '') . '"><a class="page-link pagination-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
    }

    $pagination_html .= '<li class="page-item ' . ($current_page == $total_pages ? 'disabled' : '') . '"><a class="page-link pagination-link" href="#" data-page="' . ($current_page + 1) . '">Siguiente</a></li>';
    $pagination_html .= '<li class="page-item ' . ($current_page == $total_pages ? 'disabled' : '') . '"><a class="page-link pagination-link" href="#" data-page="' . $total_pages . '">Última</a></li>';
}

// Crear un array con los resultados y la paginación
$response = array(
    'results' => $results_html,
    'pagination' => $pagination_html
);

// Enviar la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>

