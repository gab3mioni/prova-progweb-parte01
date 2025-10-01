<?php
declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

session_start();

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__).'/.env');

$root = dirname(__DIR__);
spl_autoload_register(function ($class) use ($root) {
    $prefix = 'App\\';
    $baseDir = $root.'/App/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $relative = substr($class, strlen($prefix));
    $file = $baseDir.str_replace('\\', '/', $relative).'.php';
    if (is_file($file)) require $file;
});

$controller = isset($_GET['controller']) ? strtolower($_GET['controller']) : 'produto';
$action = $_GET['action'] ?? 'listar';

$map = [
    'produto' => [App\Controllers\ProdutoController::class, ['listar']],
    'venda'   => [App\Controllers\VendaController::class, ['registrar','historico']],
];

if (!isset($map[$controller])) {
    http_response_code(404);
    exit('404 - Controller não encontrado');
}

[$class, $actions] = $map[$controller];
if (!in_array($action, $actions, true)) {
    http_response_code(404);
    exit('404 - Action não encontrada');
}

$instance = new $class($root);
call_user_func([$instance, $action]);
