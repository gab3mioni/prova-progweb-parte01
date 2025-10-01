<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Config\Controller;
use App\Models\Produto;
use App\Models\Venda;

final class VendaController extends Controller
{
    public function __construct(private string $basePath) {}

    public function registrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('/public/index.php?controller=produto&action=listar');

        $produtoId = (int)($_POST['produto_id'] ?? 0);
        $quantidade = (int)($_POST['quantidade'] ?? 0);

        $produtoModel = new Produto();
        $vendaModel = new Venda();

        $produto = $produtoModel->buscar($produtoId);
        if (!$produto || $quantidade <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Dados inválidos'];
            $this->redirect('/public/index.php?controller=produto&action=listar');
        }

        if ($produto['estoque'] < $quantidade) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Estoque insuficiente'];
            $this->redirect('/public/index.php?controller=produto&action=listar');
        }

        $total = $produtoModel->calcularTotal((float)$produto['valor'], $quantidade);
        $ok = $produtoModel->diminuir($produtoId, $quantidade);
        if (!$ok) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Falha ao atualizar estoque'];
            $this->redirect('/public/index.php?controller=produto&action=listar');
        }

        $vendaModel->criar($produtoId, $quantidade, $total);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Venda registrada'];
        $this->redirect('/public/index.php?controller=produto&action=listar');
    }

    public function historico(): void
    {
        $vendas = (new Venda())->all();
        $this->view($this->basePath, 'historico', compact('vendas'));
    }
}
