<?php

namespace Brisum\Lib\Excel\Xlsx;

class Document
{
    protected $filepath;
    protected $dirTmp;
    protected $dirFiles;

    public function __construct($filepath, $tmpDir)
    {
        $this->filepath = $filepath;
        $this->dirTmp = rtrim($tmpDir, DIRECTORY_SEPARATOR);
        $this->dirFiles = $this->dirTmp . DIRECTORY_SEPARATOR . str_replace('.xlsx', '', basename($this->filepath));
    }

    public function extract()
    {
        if (!file_exists($this->filepath)) {
            throw new \Exception("File not exist", 1);
        }
        if (!file_exists($this->dirTmp)) {
            throw new \Exception("Temp directory doesn't exist", 1);
        }
        if (!file_exists($this->dirFiles) && !mkdir($this->dirFiles, 0777, true)) {
            throw new \Exception("Can't create temp folder", 1);
        }

        self::rrmdir($this->dirFiles);

        $zip = new \ZipArchive();
        $zip->open($this->filepath);
        $zip->extractTo($this->dirFiles);
        chmod($this->dirFiles, 0777);
    }

    public function clear()
    {
        self::rrmdir($this->dirFiles);
    }

    public function getSourcePath($source)
    {
        return $this->dirFiles . DIRECTORY_SEPARATOR . $source;
    }

    /**
     * Remove recursively directory
     *
     * @param string $dir
     * @return bool
     */
    protected static function rrmdir($dir) {
        if ( !is_dir($dir) ) {
            return false;
        }

        $filelist = scandir($dir);
        foreach ($filelist as $file) {
            if ('.' == $file || '..' == $file) {
                continue;
            }

            $filepath = $dir . DIRECTORY_SEPARATOR . $file;

            if ('dir' == filetype($filepath) && self::rrmdir($filepath)) {
                continue;
            }
            if (unlink($filepath) ) {
                continue;
            }

            return false;
        }

        return rmdir($dir);
    }
}
