<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Conexao;

final class Produto
{
    public function all(): array
    {
        $sql = 'SELECT id, nome, valor, estoque FROM produtos ORDER BY nome';
        return Conexao::get()->query($sql)->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $st = Conexao::get()->prepare('SELECT id, nome, valor, estoque FROM produtos WHERE id = ? LIMIT 1');
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function diminuir(int $id, int $qty): bool
    {
        $st = Conexao::get()->prepare('UPDATE produtos SET estoque = estoque - ? WHERE id = ? AND estoque >= ?');
        $st->execute([$qty, $id, $qty]);
        return $st->rowCount() > 0;
    }

    public function calcularTotal(float $valor, int $quantidade): float
    {
        return round($valor * $quantidade, 2);
    }
}
