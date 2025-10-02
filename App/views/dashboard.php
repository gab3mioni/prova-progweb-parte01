<div class="container my-4">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Vendas Totais</h6>
                    <h3 class="card-title">R$ <?php echo number_format((float)($totalVendas ?? 0.0), 2, ',', '.'); ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Quantidade de Itens Vendidos</h6>
                    <h3 class="card-title"><?php echo number_format((int)($totalItensVendidos ?? 0), 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Produtos com Vendas</h6>
                    <h3 class="card-title"><?php echo number_format(count($produtosRanking ?? []), 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Vendas por período</h5>
            <canvas id="lineDaily" height="120"></canvas>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Composição do Valor Total (por produto)</h5>
                    <canvas id="pieComposition" height="260"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Vendas por Produto (Quantidade)</h5>
                    <canvas id="barByProduct" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Valor Médio por Unidade vs Quantidade Vendida (por produto)</h5>
            <canvas id="scatterValueQty" height="150"></canvas>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title">Ranking de Produtos (Quantidade vendida)</h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Produto</th>
                        <th>Quantidade Vendida</th>
                        <th>Valor Total Vendido</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rank = 1; foreach ($produtosRanking ?? [] as $p): ?>
                        <tr>
                            <td><?php echo $rank++; ?></td>
                            <td><?php echo htmlspecialchars($p['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo number_format((int)$p['total_quantidade'], 0, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format((float)$p['total_valor'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailyLabels = <?php echo json_encode($dailyLabels ?? []); ?>;
    const dailyValues = <?php echo json_encode($dailyValues ?? []); ?>;
    const pieLabels = <?php echo json_encode($pieLabels ?? []); ?>;
    const pieValues = <?php echo json_encode($pieValues ?? []); ?>;
    const barLabels = <?php echo json_encode($barLabels ?? []); ?>;
    const barValues = <?php echo json_encode($barValues ?? []); ?>;
    const scatterData = <?php echo json_encode($scatterData ?? []); ?>;

    function formatCurrencyBR(value) {
        return value.toLocaleString('pt-BR', { style:'currency', currency:'BRL' });
    }

    if (document.getElementById('lineDaily')) {
        const ctxLine = document.getElementById('lineDaily').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: { labels: dailyLabels, datasets: [{ label: 'Vendas (R$)', data: dailyValues, tension: 0.3, fill: true, borderWidth: 2 }] },
            options: { plugins: { tooltip: { callbacks: { label: (ctx) => formatCurrencyBR(ctx.raw) } } }, scales: { x: { ticks: { maxRotation:45, minRotation:0 } } } }
        });
    }

    if (document.getElementById('pieComposition')) {
        const ctxPie = document.getElementById('pieComposition').getContext('2d');
        new Chart(ctxPie, { type: 'pie', data: { labels: pieLabels, datasets: [{ data: pieValues }] }, options: { plugins: { tooltip: { callbacks: { label: (ctx) => ctx.label + ': ' + formatCurrencyBR(ctx.raw) } } } } });
    }

    if (document.getElementById('barByProduct')) {
        const ctxBar = document.getElementById('barByProduct').getContext('2d');
        new Chart(ctxBar, { type: 'bar', data: { labels: barLabels, datasets: [{ label: 'Quantidade', data: barValues }] }, options: { indexAxis: 'x', plugins: { legend: { display: false } }, scales: { x: { ticks: { autoSkip: false } } } } });
    }

    if (document.getElementById('scatterValueQty')) {
        const ctxScatter = document.getElementById('scatterValueQty').getContext('2d');
        new Chart(ctxScatter, {
            type: 'scatter',
            data: { datasets: [{ label: 'Produtos', data: scatterData, pointRadius: 6 }] },
            options: {
                plugins: { tooltip: { callbacks: { label: function (ctx) { const d = ctx.raw; return d.label + ' — Quantidade: ' + d.x + ', Preço médio: ' + formatCurrencyBR(d.y); } } } },
                scales: { x: { title: { display: true, text: 'Quantidade vendida' } }, y: { title: { display: true, text: 'Preço médio por unidade (R$)' } } }
            }
        });
    }
</script>
