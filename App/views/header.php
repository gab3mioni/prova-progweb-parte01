<?php
$flash = $flash ?? null;
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Projeto Vendas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="/public/index.php?controller=produto&action=listar">Vendas</a>
        <div>
            <a class="btn btn-outline-light btn-sm" href="/public/index.php?controller=venda&action=historico">Histórico</a>
        </div>
    </div>
</nav>
<div class="container">
    <?php if ($flash): ?>
        <div class="alert <?= $flash['type']==='success'?'alert-success':'alert-danger' ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>
