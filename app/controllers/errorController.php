<?php // Esta clase maneja los errores en la aplicación
class ErrorController {
    public function notFound() {
        http_response_code(404);
        echo "<h1>Error 404</h1><p>La ruta solicitada no existe.</p>";
    }
}
?>

 