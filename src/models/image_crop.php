<?php
class image_crop
{

    private $new_width;
    private $new_height;

    public function __construct($new_width = 200, $new_height = 250)
    {
        require_once(SRC_PATH . 'models/orientaion_fixed.php');

        $this->new_width = $new_width;
        $this->new_height = $new_height;
    }

    /**
     *
     * @param string $filepath
     * @param int $mime IMAGETYPE_*
     */
    public function crop($filepath, $mime)
    {
        if ($mime == image_type_to_mime_type(IMAGETYPE_JPEG) ||
            $mime == image_type_to_mime_type(IMAGETYPE_JPEG2000))
        {
            // read exif & orienttation fixed
            $orientaion_fixed = new orientaion_fixed();
            $orientaion_fixed->fixed_image($filepath);

            $base_image = imagecreatefromjpeg($filepath);
            $new_image = $this->resize_and_crop($base_image);
            imagejpeg($new_image, $filepath, 90);
        }
        elseif ($mime == image_type_to_mime_type(IMAGETYPE_GIF))
        {
            $base_image = imagecreatefromgif($filepath);
            $new_image = $this->resize_and_crop($base_image);
            imagegif($new_image, $filepath);
        }
        elseif ($mime == image_type_to_mime_type(IMAGETYPE_PNG))
        {
            $base_image = imagecreatefrompng($filepath);
            $new_image = $this->resize_and_crop($base_image);
            imagepng($new_image, $filepath, 9);
        }
        else
        {
            return false;
        }

        return true;
    }


    private function resize_and_crop(&$base_image)
    {
        $base_width = imagesx($base_image);
        $base_height = imagesy($base_image);

        $crop_x = 0;
        $crop_y = 0;

        $base_aspect_ratio = $base_width / $base_height;
        $new_aspect_ratio = $this->new_width / $this->new_height;

        //if ($new_aspect_ratio > $base_aspect_ratio){        // Full image with margin
        if ($new_aspect_ratio < $base_aspect_ratio){        // Trim image
            $crop_width = $base_height * $new_aspect_ratio;
            $crop_height = $base_height;
            $crop_x = (($base_width - $crop_width) / 2);
        }else{
            $crop_width = $base_width;
            $crop_height = $base_width / $new_aspect_ratio;
            $crop_y = (($base_height - $crop_height) / 2);
        }

        $new_image = imagecreatetruecolor($this->new_width, $this->new_height);
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);

        imagecopyresampled($new_image, $base_image, 0, 0, $crop_x, $crop_y, $this->new_width, $this->new_height, $crop_width, $crop_height);
        imagedestroy($base_image);

        return $new_image;
    }
}