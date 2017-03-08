<?php

define('SRC_PATH', dirname(__FILE__) . '/../');

$controller = new controller();
$data = (isset($_GET['method'])) ? $_GET['method'] : 'index';

if (method_exists($controller,$data)){
    call_user_func([$controller, $data], '');
}else{
    header("HTTP/1.1 404 Not Found");
    echo 'Not Found';
}


class controller
{

    public function __construct()
    {
        if (!isset($_SESSION)){
            session_start();
        }

        require_once(SRC_PATH . 'models/image_crop.php');
    }

    /**
     * upload image list
     */
    public function index()
    {
        $data['message'] = isset($_SESSION['message']) ? $_SESSION['message'] : '';
        unset($_SESSION['message']);

        // get file list
        $file_links = [];
        foreach (glob("./uploaded/*") as $filename) {
            $file_links[] = $filename;
        }
        $data['file_links'] = $file_links;

        $this->view('index.php', $data);
    }

    /**
     * image confirm (jpeg orientaiton fixed)
     */
    public function confirm()
    {
        // check select file
        if (empty($_FILES['userfile']['tmp_name']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $_SESSION['message'] = 'file is not select';
            $this->index();
            return;
        }
        $filepath = $_FILES['userfile']['tmp_name'];

        // check mime type & adjust
        $info = getimagesize($filepath);
        $image_crop = new image_crop();
        if (!$info || !$image_crop->crop($filepath, $info['mime'])) {
            $_SESSION['message'] = 'file format is invalid';
            $this->index();
            return;
        }

        // encode base64
        $image = file_get_contents($filepath);
        $base64 = base64_encode($image);
        $data['base64'] = $base64;
        $data['mime_type'] = $info['mime'];

        $validation_token = hash("sha256", $base64) . strlen($base64);
        $_SESSION['validation_token'] = $validation_token;

        $this->view('confirm.php', $data);
    }

    /**
     * file upload
     */
    public function upload()
    {
        $is_valid = isset($_POST['base64'], $_SESSION['validation_token']);
        if ($is_valid){
            $base64 = $_POST['base64'];
            $token = hash("sha256", $base64) . strlen($base64);
            $is_valid = ($_SESSION['validation_token'] == $token);
        }
        if (!$is_valid){
            $_SESSION['message'] = 'upload file is invalid';
            header('location: ./controller.php');
            exit();
        }
        unset($_SESSION['validation_token']);

        // define filename
        $timestamp = microtime(true);
        $timestamp = $timestamp*10000;
        $filepath = SRC_PATH . "public/uploaded/image_{$timestamp}";
        // encode and image save
        $image = base64_decode($base64);
        file_put_contents($filepath, $image);
        // add extension
        $extension = image_type_to_extension(exif_imagetype($filepath));
        rename($filepath, $filepath . "{$extension}");

        $_SESSION['message'] = 'upload success!';
        header('location: ./controller.php');
        exit();
    }

    private function view($viewfile, $params = [])
    {
        $file = SRC_PATH . 'views/' . $viewfile;

        if (is_file($file)) {
            extract($params);
            include ($file);
            return;
        }
        throw new \LogicException('view file not found');
    }
}
