<?php
class orientaion_fixed
{

    public function fixed_image($filepath)
    {
        $fix_image = imagecreatefromjpeg($filepath);

        $exif_datas = @exif_read_data($filepath);
        if (empty($fix_image) || empty($exif_datas['Orientation'])) {
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

        imagejpeg($fix_image, $filepath, 90);
        imagedestroy($fix_image);
        return true;
    }
}