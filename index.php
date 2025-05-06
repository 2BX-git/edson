<?php
session_start();

// Configurações do Banco
$host = "188.245.217.172";
$user = "root";
$pass = "a5450669cdb16113b0ed";
$dbname = "empresa";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro ao conectar ao banco: " . $conn->connect_error);
}

// Função segura para escapar saída HTML
function h(?string $str): string {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Login
if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simulação de login (pode ser substituído por consulta a um banco de usuários)
    if ($username === 'admin' && $password === '1234') {
        $_SESSION['logged_in'] = true;
    } else {
        $loginError = "Login inválido.";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Salvar Contato
if (isset($_POST['save_contact'])) {
    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string($_POST['nome'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $telefone1 = $conn->real_escape_string($_POST['telefone1'] ?? '');
    $empresa_id = $conn->real_escape_string($_POST['empresa_id'] ?? '');
    $observacoes = $conn->real_escape_string($_POST['observacoes'] ?? '');

    if ($id > 0) {
        $sql = "UPDATE tabela_principal SET 
            nome='$nome', email='$email', telefone1='$telefone1',
            empresa_id='$empresa_id', observacoes='$observacoes'
            WHERE id=$id";
    } else {
        $sql = "INSERT INTO tabela_principal (nome, email, telefone1, empresa_id, observacoes, origem)
                VALUES ('$nome', '$email', '$telefone1', '$empresa_id', '$observacoes', 'Calculador')";
    }

    $conn->query($sql);
    header("Location: index.php");
    exit;
}

// Deletar Contato
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tabela_principal WHERE id=$id");
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Contatos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 50px; }
    </style>
</head>
<body>

<div class="container text-center">
    <?php if (!isset($_SESSION['logged_in'])): ?>
        <h2>Login</h2>
        <?php if (isset($loginError)): ?>
            <div class="alert alert-danger"><?= h($loginError) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Usuário" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Senha" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Entrar</button>
        </form>
    <?php else: ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Contatos - Apenas Calculador</h2>
            <a href="?logout" class="btn btn-secondary">Sair</a>
        </div>

        <a href="?new" class="btn btn-success mb-3">+ Novo Contato</a>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Nome</th><th>Empresa</th><th>Email</th><th>Telefone</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT id, nome, empresa_id, email, telefone1 FROM tabela_principal WHERE origem = 'Calculador'");
                if ($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="6" class="text-center">Nenhum contato encontrado.</td>
                    </tr>
                <?php endif; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= h($row['id']) ?></td>
                        <td><?= h($row['nome']) ?></td>
                        <td><?= h($row['empresa_id']) ?></td>
                        <td><?= h($row['email']) ?></td>
                        <td><?= h($row['telefone1']) ?></td>
                        <td>
                            <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning me-1">Editar</a>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Tem certeza?')" class="btn btn-sm btn-danger">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Modal -->
    <?php if (isset($_GET['edit']) || isset($_GET['new'])):
        $contact = null;
        if (isset($_GET['edit'])) {
            $id = intval($_GET['edit']);
            $contact = $conn->query("SELECT * FROM tabela_principal WHERE id=$id")->fetch_assoc();
        }
    ?>
        <div class="modal show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= $contact ? 'Editar Contato' : 'Novo Contato' ?></h5>
                        <a href="." class="btn-close" aria-label="Close"></a>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <input type="hidden" name="id" value="<?= h($contact['id'] ?? '') ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Nome</label>
                                    <input type="text" name="nome" class="form-control" value="<?= h($contact['nome'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= h($contact['email'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Telefone</label>
                                    <input type="text" name="telefone1" class="form-control" value="<?= h($contact['telefone1'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Empresa ID</label>
                                    <input type="text" name="empresa_id" class="form-control" value="<?= h($contact['empresa_id'] ?? '') ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Observações</label>
                                    <textarea name="observacoes" class="form-control"><?= h($contact['observacoes'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <button type="submit" name="save_contact" class="btn btn-primary w-100">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
