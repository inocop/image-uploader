<?php
class image_crop
{

    private $crop_width;
    private $crop_height;

    public function __construct($crop_width = 250, $crop_height = 250)
    {
        $this->crop_width = $crop_width;
        $this->crop_height = $crop_height;
    }

    public function crop($filepath)
    {
        // Not yet
    }
}