<?php
class image_save
{

    /**
     * save image file
     */
    public function save($image, $dir)
    {
        // define filename
        $timestamp = microtime(true);
        $timestamp = $timestamp*10000;
        $filepath = rtrim($dir, '/') . "/image_{$timestamp}";
        file_put_contents($filepath, $image);

        // add extension
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($filepath);
        $extension = str_replace('image/', '', $mime_type);
        rename($filepath, $filepath.".{$extension}");
    }
}