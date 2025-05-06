<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config.php';
require_once 'auth.php';

verificar_acesso();

$conn = new mysqli($host, $user, $pass, $dbname);

// Variáveis do formulário
$id = 0;
$nome = $razao_social = $nome_fantasia = $cnpj = $cpf = $tipo_pessoa = '';
$endereco = $complemento = $bairro = $cidade = $uf = $cep = $pais = '';
$ddd = $telefone1 = $telefone2 = $telefone3 = $whatsapp = $email = '';
$cargo = $vinculo = $classificacao = $status = $tipo_interacao = '';
$data_interacao = $observacoes = $atividade = $natureza_juridica = '';
$tipo_empresa = $mei = $data_cadastro = $website = '';

// Salvar contato
if (isset($_POST['save_contact'])) {
    $id = intval($_POST['id']);
    $nome = $conn->real_escape_string($_POST['nome'] ?? '');
    $razao_social = $conn->real_escape_string($_POST['razao_social'] ?? '');
    $nome_fantasia = $conn->real_escape_string($_POST['nome_fantasia'] ?? '');
    $cnpj = $conn->real_escape_string($_POST['cnpj'] ?? '');
    $cpf = $conn->real_escape_string($_POST['cpf'] ?? '');
    $tipo_pessoa = in_array($_POST['tipo_pessoa'], ['fisica', 'juridica']) ? $_POST['tipo_pessoa'] : null;
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
    $status = in_array($_POST['status'], ['ativo', 'inativo', 'bloqueado']) ? $_POST['status'] : null;
    $tipo_interacao = $conn->real_escape_string($_POST['tipo_interacao'] ?? '');
    $data_interacao = $_POST['data_interacao'] ?: null;
    $observacoes = $conn->real_escape_string($_POST['observacoes'] ?? '');
    $atividade = $conn->real_escape_string($_POST['atividade'] ?? '');
    $natureza_juridica = $conn->real_escape_string($_POST['natureza_juridica'] ?? '');
    $tipo_empresa = in_array($_POST['tipo_empresa'], ['matriz', 'filial']) ? $_POST['tipo_empresa'] : null;
    $mei = in_array($_POST['mei'], ['sim', 'nao']) ? $_POST['mei'] : null;
    $data_cadastro = $_POST['data_cadastro'] ?: null;
    $website = $conn->real_escape_string($_POST['website'] ?? '');

    if (!$nome) die("O campo Nome é obrigatório.");

    $origem = usuario_tipo() === 'admin' ? $conn->real_escape_string($_POST['origem'] ?? '') : 'Calculador';

    if ($id > 0) {
        $sql = "UPDATE tabela_principal SET 
            nome='$nome', razao_social='$razao_social', nome_fantasia='$nome_fantasia',
            cnpj='$cnpj', cpf='$cpf', tipo_pessoa=" . ($tipo_pessoa ? "'$tipo_pessoa'" : "NULL") . ",
            endereco='$endereco', complemento='$complemento', bairro='$bairro', cidade='$cidade', uf='$uf',
            cep='$cep', pais='$pais', ddd='$ddd', telefone1='$telefone1', telefone2='$telefone2',
            telefone3='$telefone3', whatsapp='$whatsapp', email='$email', cargo='$cargo',
            vinculo='$vinculo', classificacao='$classificacao', status=" . ($status ? "'$status'" : "NULL") . ",
            origem='$origem', tipo_interacao=" . ($tipo_interacao ? "'$tipo_interacao'" : "NULL") . ",
            data_interacao=" . ($data_interacao ? "'$data_interacao'" : "NULL") . ",
            observacoes='$observacoes', atividade='$atividade', natureza_juridica='$natureza_juridica',
            tipo_empresa=" . ($tipo_empresa ? "'$tipo_empresa'" : "NULL") . ",
            mei=" . ($mei ? "'$mei'" : "NULL") . ",
            data_cadastro=" . ($data_cadastro ? "'$data_cadastro'" : "NULL") . ",
            website='$website'
            WHERE id=$id";
        if (usuario_tipo() === 'operador') {
            $sql .= " AND origem = 'Calculador'";
        }
    } else {
        $sql = "INSERT INTO tabela_principal (
            nome, razao_social, nome_fantasia, cnpj, cpf, tipo_pessoa, endereco,
            complemento, bairro, cidade, uf, cep, pais, ddd, telefone1, telefone2,
            telefone3, whatsapp, email, cargo, vinculo, classificacao, status,
            origem, tipo_interacao, data_interacao, observacoes, atividade,
            natureza_juridica, tipo_empresa, mei, data_cadastro, website
        ) VALUES (
            '$nome', '$razao_social', '$nome_fantasia', '$cnpj', '$cpf', " . ($tipo_pessoa ? "'$tipo_pessoa'" : "NULL") . ",
            '$endereco', '$complemento', '$bairro', '$cidade', '$uf', '$cep', '$pais', '$ddd', '$telefone1', '$telefone2',
            '$telefone3', '$whatsapp', '$email', '$cargo', '$vinculo', '$classificacao', " . ($status ? "'$status'" : "NULL") . ",
            '" . $origem . "', " . ($tipo_interacao ? "'$tipo_interacao'" : "NULL") . ", 
            " . ($data_interacao ? "'$data_interacao'" : "NULL") . ",
            '$observacoes', '$atividade', '$natureza_juridica',
            " . ($tipo_empresa ? "'$tipo_empresa'" : "NULL") . ", " . ($mei ? "'$mei'" : "NULL") . ",
            " . ($data_cadastro ? "'$data_cadastro'" : "NULL") . ", '$website'
        )";
    }

    if ($conn->query($sql)) {
        header("Location: crm.php?saved=1");
        exit();
    } else {
        die("Erro ao salvar contato: " . $conn->error);
    }
}

// Edição
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $sql = "SELECT * FROM tabela_principal WHERE id = $id";
    if (usuario_tipo() === 'operador') {
        $sql .= " AND origem = 'Calculador'";
    }
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        foreach ($row as $key => $value) {
            $$key = $value;
        }
    }
}

// Filtros
$where = [];
if (!empty($_GET['nome'])) $where[] = "nome LIKE '%" . $conn->real_escape_string($_GET['nome']) . "%'";
if (!empty($_GET['email'])) $where[] = "email LIKE '%" . $conn->real_escape_string($_GET['email']) . "%'";
if (!empty($_GET['empresa'])) $where[] = "razao_social LIKE '%" . $conn->real_escape_string($_GET['empresa']) . "%' OR nome_fantasia LIKE '%" . $conn->real_escape_string($_GET['empresa']) . "%'";
if (usuario_tipo() === 'operador') $where[] = "origem = 'Calculador'";
$whereClause = implode(' AND ', $where);

// Paginação
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

$total_sql = "SELECT COUNT(*) FROM tabela_principal" . ($whereClause ? " WHERE $whereClause" : "");
$total_res = $conn->query($total_sql);
$total_row = $total_res->fetch_row();
$total = $total_row[0];

$sql = "SELECT id, nome, razao_social, email, telefone1, origem FROM tabela_principal" . ($whereClause ? " WHERE $whereClause" : "") . " LIMIT $offset, $por_pagina";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Leads - Irrigação</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .header-title { font-size: 1.75rem; font-weight: 600; color: #343a40; }
        .card { border: none; box-shadow: 0 0.15rem 1.25rem rgba(0, 0, 0, 0.05); }
        .table th, .table td { vertical-align: middle; }
        .required::after { color: red; content: " *"; }
        footer { margin-top: 60px; text-align: center; font-size: 0.85rem; color: #888; }
        .pagination-responsive { flex-wrap: wrap; justify-content: center; gap: 0.25rem; }
        .pagination-responsive .page-item { flex: 1 1 auto; max-width: 60px; text-align: center; }
        .pagination-responsive .page-link { padding: 0.375rem 0.5rem; font-size: 0.875rem; }
        @media (max-width: 576px) {
            .pagination-responsive .page-item:not(:first-child):not(:last-child) { display: none; }
            .pagination-responsive .page-item:nth-child(2), .pagination-responsive .page-item:nth-last-child(2) { display: block; }
            .pagination-responsive .page-item.active, .pagination-responsive .page-item:first-child, .pagination-responsive .page-item:last-child { display: block; }
        }
    </style>
</head>
<body>
<div class="container py-4">
    <!-- Cabeçalho -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h1 class="header-title">Clientes - CRM</h1>
        <div class="d-flex gap-2 mt-2 mt-md-0">
            <a href="?new" class="btn btn-success">+ Novo Contato</a>
            <a href="logout.php" class="btn btn-secondary">Sair</a>
        </div>
    </div>

    <!-- Filtros -->
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-3 col-12">
            <input type="text" name="nome" class="form-control form-control-sm" placeholder="Nome" value="<?= h($_GET['nome'] ?? '') ?>">
        </div>
        <div class="col-md-3 col-12">
            <input type="text" name="email" class="form-control form-control-sm" placeholder="Email" value="<?= h($_GET['email'] ?? '') ?>">
        </div>
        <div class="col-md-3 col-12">
            <input type="text" name="empresa" class="form-control form-control-sm" placeholder="Empresa" value="<?= h($_GET['empresa'] ?? '') ?>">
        </div>
        <div class="col-md-3 col-12 d-grid">
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        </div>
    </form>

    <!-- Tabela -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th><th>Nome</th><th>Razão Social</th><th>Email</th><th>Telefone</th><th>Origem</th><th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows === 0): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Nenhum contato encontrado.</td>
                            </tr>
                        <?php endif; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= h($row['id']) ?></td>
                                <td><?= h($row['nome']) ?></td>
                                <td><?= h($row['razao_social']) ?></td>
                                <td><?= h($row['email']) ?></td>
                                <td><?= h($row['telefone1']) ?></td>
                                <td><?= h($row['origem']) ?></td>
                                <td class="text-end pe-3">
                                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm w-100">Editar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <!-- Paginação Otimizada -->
            <nav class="mt-3 px-3 pb-3">
                <ul class="pagination justify-content-center flex-wrap gap-1">
                    <!-- Botão Primeira -->
                    <?php if ($pagina > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => 1])) ?>">&laquo; Primeira</a>
                        </li>
                    <?php endif; ?>

                    <!-- Botão Anterior -->
                    <?php if ($pagina > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])) ?>">Anterior</a>
                        </li>
                    <?php endif; ?>

                    <!-- Exibir páginas dinamicamente -->
                    <?php
                    $range = 2;
                    $start = max(1, $pagina - $range);
                    $end = min(ceil($total / $por_pagina), $pagina + $range);
                    // Mostrar primeira página se fora do range
                    if ($start > 1): ?>
                        <li class="page-item"><a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => 1])) ?>">1</a></li>
                        <?php if ($start > 2): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Páginas dentro do range -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= $pagina == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Mostrar última página se fora do range -->
                    <?php if ($end < ceil($total / $por_pagina)): ?>
                        <?php if ($end < ceil($total / $por_pagina) - 1): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => ceil($total / $por_pagina)])) ?>"><?= ceil($total / $por_pagina) ?></a>
                        </li>
                    <?php endif; ?>

                    <!-- Botão Próxima -->
                    <?php if ($pagina < ceil($total / $por_pagina)): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])) ?>">Próxima</a>
                        </li>
                    <?php endif; ?>

                    <!-- Botão Última -->
                    <?php if ($pagina < ceil($total / $por_pagina)): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['pagina' => ceil($total / $por_pagina)])) ?>">Última &raquo;</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal Cadastro/Edição -->
<?php if (isset($_GET['edit']) || isset($_GET['new'])): ?>
<div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= isset($_GET['edit']) ? 'Editar Contato' : 'Novo Contato' ?></h5>
                <a href="crm.php" class="btn-close" aria-label="Close"></a>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="id" value="<?= h($id) ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label required">Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?= h($nome) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Razão Social</label>
                            <input type="text" name="razao_social" class="form-control" value="<?= h($razao_social) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nome Fantasia</label>
                            <input type="text" name="nome_fantasia" class="form-control" value="<?= h($nome_fantasia) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CNPJ</label>
                            <input type="text" name="cnpj" class="form-control" value="<?= h($cnpj) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control" value="<?= h($cpf) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Pessoa</label>
                            <select name="tipo_pessoa" class="form-select">
                                <option value="">Selecione</option>
                                <option value="fisica" <?= $tipo_pessoa === 'fisica' ? 'selected' : '' ?>>Pessoa Física</option>
                                <option value="juridica" <?= $tipo_pessoa === 'juridica' ? 'selected' : '' ?>>Pessoa Jurídica</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Endereço</label>
                            <input type="text" name="endereco" class="form-control" value="<?= h($endereco) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Complemento</label>
                            <input type="text" name="complemento" class="form-control" value="<?= h($complemento) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bairro</label>
                            <input type="text" name="bairro" class="form-control" value="<?= h($bairro) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cidade</label>
                            <input type="text" name="cidade" class="form-control" value="<?= h($cidade) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">UF</label>
                            <input type="text" name="uf" class="form-control" value="<?= h($uf) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CEP</label>
                            <input type="text" name="cep" class="form-control" value="<?= h($cep) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">País</label>
                            <input type="text" name="pais" class="form-control" value="<?= h($pais) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">DDD</label>
                            <input type="text" name="ddd" class="form-control" value="<?= h($ddd) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone 1</label>
                            <input type="text" name="telefone1" class="form-control" value="<?= h($telefone1) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone 2</label>
                            <input type="text" name="telefone2" class="form-control" value="<?= h($telefone2) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone 3</label>
                            <input type="text" name="telefone3" class="form-control" value="<?= h($telefone3) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp</label>
                            <input type="text" name="whatsapp" class="form-control" value="<?= h($whatsapp) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" value="<?= h($email) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cargo</label>
                            <input type="text" name="cargo" class="form-control" value="<?= h($cargo) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vínculo</label>
                            <input type="text" name="vinculo" class="form-control" value="<?= h($vinculo) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Classificação</label>
                            <input type="text" name="classificacao" class="form-control" value="<?= h($classificacao) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Selecione</option>
                                <option value="ativo" <?= $status === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                                <option value="inativo" <?= $status === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                                <option value="bloqueado" <?= $status === 'bloqueado' ? 'selected' : '' ?>>Bloqueado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Interação</label>
                            <input type="text" name="tipo_interacao" class="form-control" value="<?= h($tipo_interacao) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data da Interação</label>
                            <input type="date" name="data_interacao" class="form-control" value="<?= h($data_interacao) ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Observações</label>
                            <textarea name="observacoes" class="form-control" rows="3"><?= h($observacoes) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Atividade</label>
                            <input type="text" name="atividade" class="form-control" value="<?= h($atividade) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Natureza Jurídica</label>
                            <input type="text" name="natureza_juridica" class="form-control" value="<?= h($natureza_juridica) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo de Empresa</label>
                            <select name="tipo_empresa" class="form-select">
                                <option value="">Selecione</option>
                                <option value="matriz" <?= $tipo_empresa === 'matriz' ? 'selected' : '' ?>>Matriz</option>
                                <option value="filial" <?= $tipo_empresa === 'filial' ? 'selected' : '' ?>>Filial</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">MEI</label>
                            <select name="mei" class="form-select">
                                <option value="">Selecione</option>
                                <option value="sim" <?= $mei === 'sim' ? 'selected' : '' ?>>Sim</option>
                                <option value="nao" <?= $mei === 'nao' ? 'selected' : '' ?>>Não</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data de Cadastro</label>
                            <input type="date" name="data_cadastro" class="form-control" value="<?= h($data_cadastro) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control" value="<?= h($website) ?>">
                        </div>
                        <?php if (usuario_tipo() === 'admin'): ?>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Origem</label>
                            <input type="text" name="origem" class="form-control" value="<?= h($_POST['origem'] ?? $row['origem'] ?? '') ?>">
                        </div>
                        <?php else: ?>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Origem</label>
                            <input type="text" class="form-control-plaintext" value="Calculador" readonly>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4 d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" name="save_contact" class="btn btn-primary px-4">Salvar</button>
                        <a href="crm.php" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<footer class="container">
    <p class="py-4 text-center text-muted">&copy; 2025 CRM</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
