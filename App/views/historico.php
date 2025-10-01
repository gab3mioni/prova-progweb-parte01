<div class="card">
    <div class="card-header">Histórico de Vendas</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Total</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($vendas as $v): ?>
                    <tr>
                        <td><?= (int)$v['id'] ?></td>
                        <td><?= htmlspecialchars($v['nome']) ?></td>
                        <td><?= (int)$v['quantidade'] ?></td>
                        <td>R$ <?= number_format((float)$v['valor_total'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($v['data_venda']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
