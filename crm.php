<?php
require_once 'config.php';
require_once 'auth.php';
verificar_acesso();

$conn = new mysqli($host, $user, $pass, $dbname);

// ... resto do código da tabela, formulários etc.
