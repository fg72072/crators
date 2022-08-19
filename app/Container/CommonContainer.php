<?php

namespace App\Container;


class CommonContainer 
{

    public function getFileName($image)
    {
        return time() . '.' . str_replace(' ', '_', strtolower($image->getClientOriginalName()));
    }


    public function getProfilePicPath($folder)
    {
        return public_path() . "/assets/images/".$folder."/";
    }
    public function unlinkProfilePic($file,$folder)
    {
        $file_path = $this->getProfilePicPath($folder);
        $file = $file_path . $file;
        // return $file;
        if (file_exists($file)) {
            @unlink($file);
            // return true;
        }

        // return false;
    }
    public function test(){
        return 'test';
    }
}