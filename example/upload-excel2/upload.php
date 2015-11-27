<?php 


require_once('init.php');
require_once('bsm-tools/BsmTranslit.php');
require_once('bsm-tools/file-manager/FileManager.php');

$fm = new FileManager(DIR_UPLOAD);

$fm->response();
