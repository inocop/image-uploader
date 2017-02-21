<?php
class orientaion_fixed
{

    public function fixed_image($filepath)
    {
        $base_image = imagecreatefromjpeg($filepath);
        $w = imagesx($base_image);
        $h = imagesy($base_image);

        $fix_image = imagecreatetruecolor($w, $h);
        imagecopyresampled($fix_image, $base_image, 0, 0, 0, 0, $w, $h, $w, $h);
        imagedestroy($base_image);

        $exif_datas = @exif_read_data($filepath);
        if (empty($base_image) || empty($exif_datas['Orientation'])) {
            return false;
        }

        $orientation = $exif_datas['Orientation'];
        if ($orientation == 2) {
            imageflip($fix_image, IMG_FLIP_HORIZONTAL);

        } elseif ($orientation == 3) {
            $fix_image = imagerotate($fix_image, 180, 0);

        } elseif ($orientation == 4) {
            imageflip($fix_image, IMG_FLIP_VERTICAL);

        } elseif ($orientation == 5) {
            $fix_image = imagerotate($fix_image, 90, 0);
            imageflip($fix_image, IMG_FLIP_VERTICAL);

        } elseif ($orientation == 6) {
            $fix_image = imagerotate($fix_image, -90, 0);

        } elseif ($orientation == 7) {
            $fix_image = imagerotate($fix_image, -90, 0);
            imageflip($fix_image, IMG_FLIP_VERTICAL);

        } elseif ($orientation == 8) {
            $fix_image = imagerotate($fix_image, 90, 0);

        } else {
            // undefined or normal
            return true;
        }

        imagejpeg($fix_image, $filepath);
        return true;
    }
}