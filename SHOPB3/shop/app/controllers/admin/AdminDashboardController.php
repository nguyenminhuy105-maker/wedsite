<?php
class AdminDashboardController extends AdminController {
    public function index(): void {
        $db=Database::getInstance();
        $stats=['users'=>(int)$db->query('SELECT COUNT(*) FROM users')->fetchColumn(),'categories'=>(int)$db->query('SELECT COUNT(*) FROM categories')->fetchColumn(),'products'=>(int)$db->query('SELECT COUNT(*) FROM products')->fetchColumn()];
        $this->adminView('admin/dashboard',['pageTitle'=>'Tổng quan','stats'=>$stats]);
    }
}
