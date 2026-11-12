<?php
class AdminProductImageController extends AdminController {
    public function upload(): void {
        verify_csrf($_POST['csrf_token']??''); $pid=(int)($_POST['product_id']??0); $product=Product::find($pid);
        if(!$product) { flash('error','Sản phẩm không tồn tại.'); $this->redirect('admin/product'); }
        $files=$this->normalize($_FILES['images']??[]); if(!$files) { flash('error','Vui lòng chọn ảnh.'); $this->redirect('admin/product/edit/'.$pid); }
        $count=ProductImage::countForProduct($pid); $messages=[];
        foreach($files as $file) {
            if($count>=UPLOAD_MAX_IMAGES) { $messages[]='Đã đạt tối đa 8 ảnh.'; continue; }
            $errors=ImageService::validate($file); if($errors) { $messages[]=e($file['name']).': '.e(implode(' ',$errors)); continue; }
            try {
                $saved=ImageService::processAndSave($file);
                $primary=$count===0?1:0;
                ProductImage::insert(['product_id'=>$pid,'file_name'=>$saved['file_name'],'original_name'=>$saved['original_name'],'alt_text'=>$product['name'],'is_primary'=>$primary,'sort_order'=>ProductImage::nextSortOrder($pid)]);
                $count++; $messages[]=e($file['name']).': tải lên thành công.';
            } catch(Throwable $ex) { error_log($ex->getMessage()); $messages[]=e($file['name']).': lỗi xử lý ảnh.'; }
        }
        flash('success',implode('<br>',$messages)); $this->redirect('admin/product/edit/'.$pid);
    }
    public function setPrimary(): void {
        verify_csrf($_POST['csrf_token']??''); $pid=(int)($_POST['product_id']??0); ProductImage::setPrimary((int)($_POST['image_id']??0),$pid);
        flash('success','Đã đặt ảnh đại diện.'); $this->redirect('admin/product/edit/'.$pid);
    }
    public function delete(): void {
        verify_csrf($_POST['csrf_token']??''); $pid=(int)($_POST['product_id']??0); ProductImage::deleteImage((int)($_POST['image_id']??0),$pid);
        flash('success','Đã xóa ảnh.'); $this->redirect('admin/product/edit/'.$pid);
    }
    public function reorder(): void {
        verify_csrf($_POST['csrf_token']??''); $pid=(int)($_POST['product_id']??0); ProductImage::swapOrder((int)($_POST['image_id']??0),$pid,($_POST['direction']??'')==='down'?'down':'up');
        $this->redirect('admin/product/edit/'.$pid);
    }
    public function updateAlt(): void {
        verify_csrf($_POST['csrf_token']??''); $pid=(int)($_POST['product_id']??0); ProductImage::updateAlt((int)($_POST['image_id']??0),$pid,trim($_POST['alt_text']??''));
        flash('success','Đã cập nhật alt ảnh.'); $this->redirect('admin/product/edit/'.$pid);
    }
    private function normalize(array $f): array {
        $out=[]; if(empty($f['name'])||!is_array($f['name'])) return $out;
        foreach($f['name'] as $i=>$name) if(($f['error'][$i]??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_NO_FILE)
            $out[]=['name'=>$name,'type'=>$f['type'][$i]??'','tmp_name'=>$f['tmp_name'][$i]??'','error'=>$f['error'][$i]??UPLOAD_ERR_NO_FILE,'size'=>$f['size'][$i]??0];
        return $out;
    }
}
