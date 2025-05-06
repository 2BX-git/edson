<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: crm.php");
} else {
    header("Location: login.php");
}
exit;
