<?php
class App {
    public function run(): void {
        $url = trim($_GET['url'] ?? '', '/');
        $parts = $url === '' ? ['home', 'index'] : explode('/', $url);
        $isAdmin = ($parts[0] ?? '') === 'admin';
        if ($isAdmin) {
            array_shift($parts);
            $controllerPart = $parts[0] ?? 'dashboard';
            $action = $parts[1] ?? 'index';
            $params = array_slice($parts, 2);
            $class = 'Admin' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $controllerPart))) . 'Controller';
            $file = __DIR__ . '/../controllers/admin/' . $class . '.php';
        } else {
            $controllerPart = $parts[0] ?? 'home';
            $action = $parts[1] ?? 'index';
            $params = array_slice($parts, 2);
            $class = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $controllerPart))) . 'Controller';
            $file = __DIR__ . '/../controllers/' . $class . '.php';
        }
        if (!file_exists($file)) { http_response_code(404); require __DIR__ . '/../views/errors/404.php'; return; }
        require_once $file;
        if (!class_exists($class) || !method_exists($class, $action) || str_starts_with($action, '_')) {
            http_response_code(404); require __DIR__ . '/../views/errors/404.php'; return;
        }
        (new $class())->$action(...$params);
    }
}
