<?php

/**
 * Interface control
 */
interface Controller {
    // public function readDir();

    public function folderExists($dir, $extension);

    public function createFolder($dir, $extension);

    public function deleteFolder($arg);

    public function list($dir);

    public function check($dir, $file);

    public function noExtension($dir, $file);
    
    public function rollback($arg);

    public function message($status);
}