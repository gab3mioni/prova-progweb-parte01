<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Conexao;
use PDO;
use InvalidArgumentException;
use RuntimeException;

final class Venda
{
    public function criar(int $produtoId, int $quantidade, float $valorTotal): int
    {
        $db = Conexao::get();
        $st = $db->prepare(
            'INSERT INTO vendas (produto_id, quantidade, valor_total, data_venda) VALUES (:produto_id, :quantidade, :valor_total, NOW())'
        );
        $st->bindValue(':produto_id', $produtoId, PDO::PARAM_INT);
        $st->bindValue(':quantidade', $quantidade, PDO::PARAM_INT);
        $st->bindValue(':valor_total', (string)$valorTotal, PDO::PARAM_STR);
        $st->execute();
        return (int)$db->lastInsertId();
    }

    public function all(): array
    {
        $db = Conexao::get();
        $sql = 'SELECT v.id, v.produto_id, v.quantidade, v.valor_total, v.data_venda, p.nome 
                FROM vendas v 
                JOIN produtos p ON p.id = v.produto_id
                ORDER BY v.data_venda DESC, v.id DESC';
        $st = $db->prepare($sql);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalVendas(): float
    {
        $db = Conexao::get();
        $sql = 'SELECT COALESCE(SUM(valor_total),0) as total FROM vendas';
        $st = $db->prepare($sql);
        $st->execute();
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return isset($row['total']) ? (float)$row['total'] : 0.0;
    }

    public function vendasPorProduto(): array
    {
        $db = Conexao::get();
        $sql = 'SELECT p.nome, SUM(v.quantidade) as total_quantidade, SUM(v.valor_total) as total_valor
                FROM vendas v
                JOIN produtos p ON p.id = v.produto_id
                GROUP BY p.nome
                ORDER BY total_valor DESC';
        $st = $db->prepare($sql);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalItensVendidos(): int
    {
        $db = Conexao::get();
        $sql = 'SELECT COALESCE(SUM(quantidade),0) as total_itens FROM vendas';
        $st = $db->prepare($sql);
        $st->execute();
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return isset($row['total_itens']) ? (int)$row['total_itens'] : 0;
    }

    public function vendasDiarias(string $dateColumn = 'data_venda'): array
    {
        $col = preg_match('/^[a-z0-9_]+$/i', $dateColumn) ? $dateColumn : 'data_venda';
        $db = Conexao::get();
        $sql = "SELECT DATE({$col}) AS dia, COALESCE(SUM(valor_total),0) AS total
                FROM vendas
                WHERE {$col} IS NOT NULL
                GROUP BY DATE({$col})
                ORDER BY DATE({$col}) ASC";
        $st = $db->prepare($sql);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function scatterData(): array
    {
        $produtos = $this->vendasPorProduto();
        $out = [];
        foreach ($produtos as $p) {
            $quant = (int)$p['total_quantidade'];
            $valorMedio = $quant > 0 ? ((float)$p['total_valor'] / $quant) : 0.0;
            $out[] = ['x' => $quant, 'y' => round($valorMedio, 2), 'label' => $p['nome']];
        }
        return $out;
    }

    public function dashboardData(): array
    {
        $totalVendas = $this->totalVendas();
        $totalItens = $this->totalItensVendidos();
        $vendasPorProduto = $this->vendasPorProduto();
        $vendasDiarias = $this->vendasDiarias();
        $scatter = $this->scatterData();

        $dailyLabels = [];
        $dailyValues = [];
        foreach ($vendasDiarias as $r) {
            $dailyLabels[] = $r['dia'];
            $dailyValues[] = (float)$r['total'];
        }

        $pieLabels = [];
        $pieValues = [];
        $barLabels = [];
        $barValues = [];
        foreach ($vendasPorProduto as $p) {
            $pieLabels[] = $p['nome'];
            $pieValues[] = (float)$p['total_valor'];
            $barLabels[] = $p['nome'];
            $barValues[] = (int)$p['total_quantidade'];
        }

        return [
            'totalVendas' => $totalVendas,
            'totalItensVendidos' => $totalItens,
            'produtosRanking' => $vendasPorProduto,
            'dailyLabels' => $dailyLabels,
            'dailyValues' => $dailyValues,
            'pieLabels' => $pieLabels,
            'pieValues' => $pieValues,
            'barLabels' => $barLabels,
            'barValues' => $barValues,
            'scatterData' => $scatter,
        ];
    }
}
