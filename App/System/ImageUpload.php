<?php
namespace App\System;

class ImageUpload {

    public function add($media) {
        $target_dir    = __DIR__ . '/../../public/uploads/';
        $temp          = explode('.', $media['name']);
        $file          = $media['name'];
        $target_file   = $target_dir . $file;

        if(move_uploaded_file($media["tmp_name"], $target_file)) {
            return $file;
        }

        else {
            throw new \Error("File couldn't be uploaded.");
        }
    }
    
}
