<?php
class AdminController extends Controller {
    public function __construct() { Auth::requireRole(['admin','staff']); }
    protected function adminView(string $path,array $data=[]): void {
        extract($data); $viewFile=__DIR__.'/../../views/'.$path.'.php';
        ob_start(); require $viewFile; $content=ob_get_clean();
        require __DIR__.'/../../views/layouts/admin.php';
    }
}
