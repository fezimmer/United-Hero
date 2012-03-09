<?php
session_start();

if (!isset($_SESSION['admin_is_logged_in']) || $_SESSION['admin_is_logged_in'] !== true)
{
header('Location: ../index.php?from='.rawurlencode($_SERVER['REQUEST_URI']));
exit();
}

?>
