<?php
class ImageService {
    public static function validate(array $file): array {
        $errors=[]; $code=$file['error']??UPLOAD_ERR_NO_FILE;
        if($code!==UPLOAD_ERR_OK) return [self::uploadErrorMessage($code)];
        $ext=strtolower(pathinfo($file['name']??'',PATHINFO_EXTENSION));
        if(!in_array($ext,UPLOAD_ALLOWED_EXT,true)) $errors[]='Định dạng ảnh không được hỗ trợ.';
        if(($file['size']??0)>UPLOAD_MAX_SIZE) $errors[]='Dung lượng vượt quá 2 MB.';
        if(!is_uploaded_file($file['tmp_name']??'')) $errors[]='Tệp tải lên không hợp lệ.';
        if(!$errors) {
            $finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($file['tmp_name']);
            if(!in_array($mime,['image/jpeg','image/png','image/webp'],true)) $errors[]='Tệp không phải ảnh hợp lệ.';
            $dim=@getimagesize($file['tmp_name']);
            if($dim===false) $errors[]='Không đọc được ảnh.';
            else {
                $short=min($dim[0],$dim[1]); $long=max($dim[0],$dim[1]);
                if($short<UPLOAD_MIN_SIDE) $errors[]='Ảnh quá nhỏ, cạnh ngắn phải từ 200px.';
                if($long>UPLOAD_MAX_SIDE) $errors[]='Ảnh quá lớn, cạnh dài tối đa 5000px.';
            }
        }
        return $errors;
    }
    public static function processAndSave(array $file): array {
        $raw=file_get_contents($file['tmp_name']); $src=$raw===false?false:@imagecreatefromstring($raw);
        if($src===false) throw new RuntimeException('Không đọc được dữ liệu ảnh.');
        $name=bin2hex(random_bytes(16)).'.jpg';
        self::resizeSave($src,imagesx($src),imagesy($src),IMAGE_MAIN_WIDTH,self::mainPath($name));
        self::resizeSave($src,imagesx($src),imagesy($src),IMAGE_THUMB_WIDTH,self::thumbPath($name));
        imagedestroy($src);
        return ['file_name'=>$name,'original_name'=>basename($file['name'])];
    }
    private static function resizeSave($src,int $w,int $h,int $target,string $path): void {
        $ratio=min(1,$target/$w); $nw=max(1,(int)round($w*$ratio)); $nh=max(1,(int)round($h*$ratio));
        $dst=imagecreatetruecolor($nw,$nh); $white=imagecolorallocate($dst,255,255,255); imagefill($dst,0,0,$white);
        imagecopyresampled($dst,$src,0,0,0,0,$nw,$nh,$w,$h);
        if(!imagejpeg($dst,$path,85)) throw new RuntimeException('Không lưu được ảnh.');
        imagedestroy($dst);
    }
    public static function deleteFiles(string $name): void {
        foreach([self::mainPath($name),self::thumbPath($name)] as $path) if(is_file($path)&&!@unlink($path)) error_log('Không xóa được ảnh: '.$path);
    }
    private static function mainPath(string $name): string { return __DIR__.'/../../public/uploads/products/'.$name; }
    private static function thumbPath(string $name): string { return __DIR__.'/../../public/uploads/products/thumbs/'.$name; }
    private static function uploadErrorMessage(int $code): string {
        return match($code) {
            UPLOAD_ERR_INI_SIZE,UPLOAD_ERR_FORM_SIZE=>'Tệp vượt dung lượng máy chủ cho phép.',
            UPLOAD_ERR_PARTIAL=>'Tệp chỉ tải lên được một phần.',
            UPLOAD_ERR_NO_FILE=>'Chưa chọn tệp.',
            UPLOAD_ERR_NO_TMP_DIR,UPLOAD_ERR_CANT_WRITE,UPLOAD_ERR_EXTENSION=>'Lỗi máy chủ khi nhận tệp.',
            default=>'Lỗi tải tệp không xác định.'
        };
    }
}
