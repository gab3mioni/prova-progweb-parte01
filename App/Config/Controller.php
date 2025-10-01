<?php

namespace App\Config;

class Controller
{
    public function view(string $basePath, string $view, array $data = []): void {
        extract($data);
        $viewPath = $basePath . '/App/views/'.$view.'.php';
        require $basePath . '/App/views/header.php';
        require $viewPath;
        require $basePath . '/App/views/footer.php';
    }

    public function redirect(string $url): void {
        header("Location: $url");
        exit;
    }
}