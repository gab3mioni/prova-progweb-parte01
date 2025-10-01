<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Config\Controller;
use App\Models\Produto;

final class ProdutoController extends Controller
{
    public function __construct(private string $basePath) {}

    public function listar(): void
    {
        $produtos = (new Produto())->all();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        $this->view($this->basePath, 'listar', compact('produtos','flash'));
    }
}
