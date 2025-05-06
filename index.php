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

    if ($username === 'admin' && $password === '1234') {
        $_SESSION['logged_in'] = true;
    } else {
        $loginError = "Login inválido.";
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: crm.php");
    exit;
}

// Salvar Contato
if (isset($_POST['save_contact'])) {
    // Validação dos campos ENUM
    $tipo_pessoa_validos = ['fisica', 'juridica'];
    $status_validos = ['ativo', 'inativo', 'bloqueado'];
    $tipo_empresa_validos = ['matriz', 'filial'];
    $mei_validos = ['sim', 'nao'];

    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string($_POST['nome'] ?? '');
    $razao_social = $conn->real_escape_string($_POST['razao_social'] ?? '');
    $nome_fantasia = $conn->real_escape_string($_POST['nome_fantasia'] ?? '');
    $cnpj = $conn->real_escape_string($_POST['cnpj'] ?? '');
    $cpf = $conn->real_escape_string($_POST['cpf'] ?? '');
    $tipo_pessoa = in_array($_POST['tipo_pessoa'], $tipo_pessoa_validos) ? $_POST['tipo_pessoa'] : null;
    $endereco = $conn->real_escape_string($_POST['endereco'] ?? '');
    $complemento = $conn->real_escape_string($_POST['complemento'] ?? '');
    $bairro = $conn->real_escape_string($_POST['bairro'] ?? '');
    $cidade = $conn->real_escape_string($_POST['cidade'] ?? '');
    $uf = $conn->real_escape_string($_POST['uf'] ?? '');
    $cep = $conn->real_escape_string($_POST['cep'] ?? '');
    $pais = $conn->real_escape_string($_POST['pais'] ?? '');
    $ddd = $conn->real_escape_string($_POST['ddd'] ?? '');
    $telefone1 = $conn->real_escape_string($_POST['telefone1'] ?? '');
    $telefone2 = $conn->real_escape_string($_POST['telefone2'] ?? '');
    $telefone3 = $conn->real_escape_string($_POST['telefone3'] ?? '');
    $whatsapp = $conn->real_escape_string($_POST['whatsapp'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $cargo = $conn->real_escape_string($_POST['cargo'] ?? '');
    $vinculo = $conn->real_escape_string($_POST['vinculo'] ?? '');
    $classificacao = $conn->real_escape_string($_POST['classificacao'] ?? '');
    $status = in_array($_POST['status'], $status_validos) ? $_POST['status'] : null;
    $origem = 'Calculador';
    $tipo_interacao = $conn->real_escape_string($_POST['tipo_interacao'] ?? '');
    $data_interacao = $conn->real_escape_string($_POST['data_interacao'] ?? '');
    $observacoes = $conn->real_escape_string($_POST['observacoes'] ?? '');
    $atividade = $conn->real_escape_string($_POST['atividade'] ?? '');
    $natureza_juridica = $conn->real_escape_string($_POST['natureza_juridica'] ?? '');
    $tipo_empresa = in_array($_POST['tipo_empresa'], $tipo_empresa_validos) ? $_POST['tipo_empresa'] : null;
    $mei = in_array($_POST['mei'], $mei_validos) ? $_POST['mei'] : null;
    $data_cadastro = $conn->real_escape_string($_POST['data_cadastro'] ?? '');
    $website = $conn->real_escape_string($_POST['website'] ?? '');

    if (!$tipo_pessoa || !$status || !$tipo_empresa || !$mei) {
        die("Valores inválidos nos campos de seleção.");
    }

    if ($id > 0) {
        $sql = "UPDATE tabela_principal SET 
            nome='$nome', razao_social='$razao_social', nome_fantasia='$nome_fantasia',
            cnpj='$cnpj', cpf='$cpf', tipo_pessoa='$tipo_pessoa', endereco='$endereco',
            complemento='$complemento', bairro='$bairro', cidade='$cidade', uf='$uf',
            cep='$cep', pais='$pais', ddd='$ddd', telefone1='$telefone1', telefone2='$telefone2',
            telefone3='$telefone3', whatsapp='$whatsapp', email='$email', cargo='$cargo',
            vinculo='$vinculo', classificacao='$classificacao', status='$status',
            origem='Calculador', tipo_interacao='$tipo_interacao', data_interacao='$data_interacao',
            observacoes='$observacoes', atividade='$atividade', natureza_juridica='$natureza_juridica',
            tipo_empresa='$tipo_empresa', mei='$mei', data_cadastro='$data_cadastro', website='$website'
            WHERE id=$id";
    } else {
        $sql = "INSERT INTO tabela_principal (
            nome, razao_social, nome_fantasia, cnpj, cpf, tipo_pessoa, endereco,
            complemento, bairro, cidade, uf, cep, pais, ddd, telefone1, telefone2,
            telefone3, whatsapp, email, cargo, vinculo, classificacao, status,
            origem, tipo_interacao, data_interacao, observacoes, atividade,
            natureza_juridica, tipo_empresa, mei, data_cadastro, website
        ) VALUES (
            '$nome', '$razao_social', '$nome_fantasia', '$cnpj', '$cpf', '$tipo_pessoa', '$endereco',
            '$complemento', '$bairro', '$cidade', '$uf', '$cep', '$pais', '$ddd', '$telefone1', '$telefone2',
            '$telefone3', '$whatsapp', '$email', '$cargo', '$vinculo', '$classificacao', '$status',
            'Calculador', '$tipo_interacao', '$data_interacao', '$observacoes', '$atividade',
            '$natureza_juridica', '$tipo_empresa', '$mei', '$data_cadastro', '$website'
        )";
    }

    if ($conn->query($sql)) {
        header("Location: crm.php?saved=1");
        exit;
    } else {
        die("Erro ao salvar contato: " . $conn->error);
    }
}

// Deletar Contato
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tabela_principal WHERE id=$id");
    header("Location: crm.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRM - Calculador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f3f6; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: 250px; background-color: #212529; height: 100vh; position: fixed; top: 0; left: 0; padding-top: 20px; }
        .content { margin-left: 250px; padding: 30px; }
        .card { box-shadow: 0 0.15rem 1.75rem rgba(58,59,69,.15); border: none; }
        .form-control-sm { font-size: 0.875rem; }
        .table thead th { vertical-align: middle; font-weight: 600; background-color: #e9ecef; }
        footer { margin-top: 50px; text-align: center; color: #888; font-size: 0.9rem; }
    </style>
</head>
<body>

<?php if (isset($_SESSION['logged_in'])): ?>
    <!-- Sidebar -->
    <div class="sidebar text-white">
        <h5 class="text-center text-white mb-4">CRM - Calculador</h5>
        <ul class="nav flex-column px-3">
            <li class="nav-item"><a href="crm.php" class="nav-link text-white">Contatos</a></li>
            <li class="nav-item"><a href="?new" class="nav-link text-white">+ Novo Contato</a></li>
            <li class="nav-item"><a href="?logout" class="nav-link text-white">Sair</a></li>
        </ul>
    </div>

    <!-- Conteúdo Principal -->
    <div class="content">
        <h2 class="mb-4">Clientes</h2>

        <!-- Filtros -->
        <form method="get" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="nome" class="form-control form-control-sm" placeholder="Nome" value="<?= h($_GET['nome'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <input type="text" name="email" class="form-control form-control-sm" placeholder="Email" value="<?= h($_GET['email'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <input type="text" name="empresa" class="form-control form-control-sm" placeholder="Empresa" value="<?= h($_GET['empresa'] ?? '') ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filtrar</button>
            </div>
        </form>

        <!-- Tabela -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th><th>Nome</th><th>Razão Social</th><th>Email</th><th>Telefone</th><th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Filtros
                            $where = [];
                            if (!empty($_GET['nome'])) $where[] = "nome LIKE '%" . $conn->real_escape_string($_GET['nome']) . "%'";
                            if (!empty($_GET['email'])) $where[] = "email LIKE '%" . $conn->real_escape_string($_GET['email']) . "%'";
                            if (!empty($_GET['empresa'])) $where[] = "razao_social LIKE '%" . $conn->real_escape_string($_GET['empresa']) . "%' OR nome_fantasia LIKE '%" . $conn->real_escape_string($_GET['empresa']) . "%'";
                            $where[] = "origem = 'Calculador'";

                            $whereClause = implode(' AND ', $where);
                            $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
                            $por_pagina = 10;
                            $offset = ($pagina - 1) * $por_pagina;

                            $total_sql = "SELECT COUNT(*) FROM tabela_principal WHERE $whereClause";
                            $total_res = $conn->query($total_sql);
                            $total = $total_res->fetch_row()[0];

                            $sql = "SELECT id, nome, razao_social, email, telefone1 FROM tabela_principal WHERE $whereClause LIMIT $offset, $por_pagina";
                            $result = $conn->query($sql);

                            if ($result->num_rows === 0): ?>
                                <tr><td colspan="6" class="text-center py-4">Nenhum contato encontrado.</td></tr>
                            <?php endif; ?>

                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= h($row['id']) ?></td>
                                    <td><?= h($row['nome']) ?></td>
                                    <td><?= h($row['razao_social']) ?></td>
                                    <td><?= h($row['email']) ?></td>
                                    <td><?= h($row['telefone1']) ?></td>
                                    <td class="text-end pe-3">
                                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Tem certeza?')" class="btn btn-sm btn-outline-danger">Excluir</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <nav class="mt-3 me-3 ms-auto w-100">
                    <ul class="pagination justify-content-end mb-0">
                        <?php for ($i = 1; $i <= ceil($total / $por_pagina); $i++): ?>
                            <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>

        <footer>&copy; 2025 CRM Calculador</footer>
    </div>
<?php else: ?>
    <!-- Login -->
    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="card shadow-sm w-100" style="max-width: 400px;">
            <div class="card-header text-center">
                <h4>Login</h4>
            </div>
            <div class="card-body">
                <?php if (isset($loginError)): ?>
                    <div class="alert alert-danger"><?= h($loginError) ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Usuário</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Edição -->
<?php if (isset($_GET['edit']) || isset($_GET['new'])):
    $contact = null;
    if (isset($_GET['edit'])) {
        $id = intval($_GET['edit']);
        $contact = $conn->query("SELECT * FROM tabela_principal WHERE id=$id")->fetch_assoc();
    }
?>
<div class="modal show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $contact ? 'Editar Contato' : 'Novo Contato' ?></h5>
                <a href="." class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="id" value="<?= h($contact['id'] ?? '') ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?= h($contact['nome'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Razão Social</label>
                            <input type="text" name="razao_social" class="form-control" value="<?= h($contact['razao_social'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Nome Fantasia</label>
                            <input type="text" name="nome_fantasia" class="form-control" value="<?= h($contact['nome_fantasia'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>CNPJ</label>
                            <input type="text" name="cnpj" class="form-control" value="<?= h($contact['cnpj'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>CPF</label>
                            <input type="text" name="cpf" class="form-control" value="<?= h($contact['cpf'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Tipo de Pessoa</label>
                            <select name="tipo_pessoa" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="fisica" <?= (h($contact['tipo_pessoa'] ?? '') === 'fisica') ? 'selected' : '' ?>>Pessoa Física</option>
                                <option value="juridica" <?= (h($contact['tipo_pessoa'] ?? '') === 'juridica') ? 'selected' : '' ?>>Pessoa Jurídica</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Endereço</label>
                            <input type="text" name="endereco" class="form-control" value="<?= h($contact['endereco'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Complemento</label>
                            <input type="text" name="complemento" class="form-control" value="<?= h($contact['complemento'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Bairro</label>
                            <input type="text" name="bairro" class="form-control" value="<?= h($contact['bairro'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Cidade</label>
                            <input type="text" name="cidade" class="form-control" value="<?= h($contact['cidade'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>UF</label>
                            <input type="text" name="uf" class="form-control" value="<?= h($contact['uf'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>CEP</label>
                            <input type="text" name="cep" class="form-control" value="<?= h($contact['cep'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>País</label>
                            <input type="text" name="pais" class="form-control" value="<?= h($contact['pais'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>DDD</label>
                            <input type="text" name="ddd" class="form-control" value="<?= h($contact['ddd'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Telefone 1</label>
                            <input type="text" name="telefone1" class="form-control" value="<?= h($contact['telefone1'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Telefone 2</label>
                            <input type="text" name="telefone2" class="form-control" value="<?= h($contact['telefone2'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Telefone 3</label>
                            <input type="text" name="telefone3" class="form-control" value="<?= h($contact['telefone3'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control" value="<?= h($contact['whatsapp'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= h($contact['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Cargo</label>
                            <input type="text" name="cargo" class="form-control" value="<?= h($contact['cargo'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Vínculo</label>
                            <input type="text" name="vinculo" class="form-control" value="<?= h($contact['vinculo'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Classificação</label>
                            <input type="text" name="classificacao" class="form-control" value="<?= h($contact['classificacao'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Selecione</option>
                                <option value="ativo" <?= (h($contact['status'] ?? '') === 'ativo') ? 'selected' : '' ?>>Ativo</option>
                                <option value="inativo" <?= (h($contact['status'] ?? '') === 'inativo') ? 'selected' : '' ?>>Inativo</option>
                                <option value="bloqueado" <?= (h($contact['status'] ?? '') === 'bloqueado') ? 'selected' : '' ?>>Bloqueado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Tipo de Interação</label>
                            <input type="text" name="tipo_interacao" class="form-control" value="<?= h($contact['tipo_interacao'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Data da Interação</label>
                            <input type="date" name="data_interacao" class="form-control" value="<?= h($contact['data_interacao'] ?? '') ?>">
                        </div>
                        <div class="col-md-12">
                            <label>Observações</label>
                            <textarea name="observacoes" class="form-control" rows="3"><?= h($contact['observacoes'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Atividade</label>
                            <input type="text" name="atividade" class="form-control" value="<?= h($contact['atividade'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Natureza Jurídica</label>
                            <input type="text" name="natureza_juridica" class="form-control" value="<?= h($contact['natureza_juridica'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Tipo de Empresa</label>
                            <select name="tipo_empresa" class="form-control">
                                <option value="">Selecione</option>
                                <option value="matriz" <?= (h($contact['tipo_empresa'] ?? '') === 'matriz') ? 'selected' : '' ?>>Matriz</option>
                                <option value="filial" <?= (h($contact['tipo_empresa'] ?? '') === 'filial') ? 'selected' : '' ?>>Filial</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>MEI</label>
                            <select name="mei" class="form-control">
                                <option value="">Selecione</option>
                                <option value="sim" <?= (h($contact['mei'] ?? '') === 'sim') ? 'selected' : '' ?>>Sim</option>
                                <option value="nao" <?= (h($contact['mei'] ?? '') === 'nao') ? 'selected' : '' ?>>Não</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Data de Cadastro</label>
                            <input type="date" name="data_cadastro" class="form-control" value="<?= h($contact['data_cadastro'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Website</label>
                            <input type="url" name="website" class="form-control" value="<?= h($contact['website'] ?? '') ?>">
                        </div>
                    </div>

                    <button type="submit" name="save_contact" class="btn btn-primary mt-4 w-100">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
