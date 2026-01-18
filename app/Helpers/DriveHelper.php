<?php

namespace App\Helpers;

class DriveHelper
{
    public static function embed($url)
    {
        if (preg_match('/\/d\/(.*?)\//', $url, $m)) {
            return 'https://drive.google.com/file/d/'.$m[1].'/preview';
        }

        if (preg_match('/id=([^&]+)/', $url, $m)) {
            return 'https://drive.google.com/file/d/'.$m[1].'/preview';
        }

        return $url;
    }
}
