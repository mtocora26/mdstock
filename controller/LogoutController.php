<?php
// LogoutController.php
session_start();
// Destruir la sesión y redirigir al inicio
session_unset();
session_destroy();
header('Location: ../view/pages/index.php');
exit;
