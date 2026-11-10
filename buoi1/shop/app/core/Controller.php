<?php
// Controller co so: render view trong layout chung hoac chuyen huong.
abstract class Controller {
    protected function view(string $view, array $data = [], string|false $layout = 'main'): void {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!is_file($viewFile)) { http_response_code(500); exit('Khong tim thay view.'); }
        if ($layout === false) { extract($data, EXTR_SKIP); require $viewFile; return; }
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) { http_response_code(500); exit('Khong tim thay layout.'); }
        $content = function () use ($viewFile, $data): void { extract($data, EXTR_SKIP); require $viewFile; };
        extract($data, EXTR_SKIP);
        require $layoutFile;
    }
    protected function redirect(string $path): void { header('Location: ' . base_url($path)); exit; }
}
