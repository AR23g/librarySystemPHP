<?php
/*
 * Controlador Dashboard
 * se enseña la página principal del dashboard
 */

class DashboardController {
    /*
     Muestra el dashboard
     */
    public function index() {
        if (!isset($_SESSION['userId'])) {
            header('Location: ?route=auth/login');
            exit;
        }

        require_once VIEWS_PATH . 'dashboard.php';
    }
}
?>
