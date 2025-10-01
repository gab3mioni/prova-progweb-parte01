<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Conexao;
use PDO;

final class Venda
{
    public function criar(int $produtoId, int $quantidade, float $valorTotal): int
    {
        $st = Conexao::get()->prepare('INSERT INTO vendas (produto_id, quantidade, valor_total, data_venda) VALUES (?, ?, ?, NOW())');
        $st->execute([$produtoId, $quantidade, $valorTotal]);
        return (int) Conexao::get()->lastInsertId();
    }

    public function all(): array
    {
        $sql = 'SELECT v.id, v.produto_id, v.quantidade, v.valor_total, v.data_venda, p.nome 
                FROM vendas v 
                JOIN produtos p ON p.id = v.produto_id
                ORDER BY v.data_venda DESC, v.id DESC';
        return Conexao::get()->query($sql)->fetchAll();
    }
}
