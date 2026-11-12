<?php
class Controller {
    protected function view(string $path, array $data = []): void {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $path . '.php';
        if (!file_exists($viewFile)) { http_response_code(404); echo 'Không tìm thấy giao diện.'; return; }
        ob_start(); require $viewFile; $content = ob_get_clean();
        require __DIR__ . '/../views/layouts/main.php';
    }
    protected function redirect(string $path): void { header('Location: ' . base_url($path)); exit; }
}
