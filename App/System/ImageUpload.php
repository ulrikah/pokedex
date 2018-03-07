<?php
namespace App\System;

class ImageUpload {

    // T0D0 - add constraints to file upload
    public function add($media) {
        $target_dir    = __DIR__ . '/../../public/uploads/';
        $temp          = explode('.', $media['name']);
        $file          = $media['name'];
        $target_file   = $target_dir . $file;


        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if  ($imageFileType != "jpg" 
            && $imageFileType != "png" 
            && $imageFileType != "jpeg") {
            
            throw new \Error("File couldn't be uploaded.");
        }


        if(move_uploaded_file($media["tmp_name"], $target_file)) {
            return $file;
        }

        else {
            throw new \Error("File couldn't be uploaded.");
        }
    }
}
