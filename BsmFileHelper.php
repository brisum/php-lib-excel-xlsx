<?php

class BsmFileHelper {
    public static function rmdir($dir) {
        if ( !is_dir($dir) ) {
            return false;
        }

        $objects = scandir($dir);
        $path = null;

        foreach ( $objects as $object ) { 
            if ( '.' == $object || '..' == $object ) { 
                continue;
            }

            $path = $dir . '/' . $object;

            if ( ('dir' == filetype($path)) && self::rmdir($path) ) {
                continue;
            }
            if ( unlink($path) ) {
                continue;
            } 

            return false;
        } 

        unset($objects); 
        return rmdir($dir);
    }
}