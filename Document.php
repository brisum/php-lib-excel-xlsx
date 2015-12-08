<?php

namespace Brisum\Lib\Xlsx;

use Brisum\Lib\Filesystem\Filesystem;

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

        Filesystem::rrmdir($this->dirFiles);

        $zip = new \ZipArchive();
        $zip->open($this->filepath);
        $zip->extractTo($this->dirFiles);
        chmod($this->dirFiles, 0777);
    }

    public function clear()
    {
        Filesystem::rrmdir($this->dirFiles);
    }

    public function getSourcePath($source)
    {
        $sourcePath = $this->dirFiles . DIRECTORY_SEPARATOR . $source;

        if (!file_exists($sourcePath)) {
            throw new \Exception('Wrong source path: ' . $source);
        }

        return $sourcePath;
    }
}
