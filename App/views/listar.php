<div class="card">
    <div class="card-header">Produtos</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Valor</th>
                    <th>Estoque</th>
                    <th>Comprar</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td><?= (int)$p['id'] ?></td>
                        <td><?= htmlspecialchars($p['nome']) ?></td>
                        <td>R$ <?= number_format((float)$p['valor'], 2, ',', '.') ?></td>
                        <td><?= (int)$p['estoque'] ?></td>
                        <td>
                            <form method="post" action="/public/index.php?controller=venda&action=registrar" class="d-flex gap-2">
                                <input type="hidden" name="produto_id" value="<?= (int)$p['id'] ?>">
                                <input type="number" name="quantidade" min="1" max="<?= (int)$p['estoque'] ?>" value="1" class="form-control form-control-sm" style="width:100px">
                                <button type="submit" class="btn btn-primary btn-sm">Vender</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
