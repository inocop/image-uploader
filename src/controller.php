<?php
$controller = new controller();
$data = (isset($_GET['method'])) ? $_GET['method'] : 'index';
call_user_func([$controller, $data], '');

class controller
{

    public function __construct()
    {
        session_start();
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

        $this->view(__DIR__ . '/views/index.php', $data);
    }

    /**
     * image confirm (jpeg orientaiton fixed)
     */
    public function confirm()
    {
        // check select file
        if (!is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $_SESSION['message'] = 'not select file';
            $this->index();
            return;
        }
        $filepath = $_FILES['userfile']['tmp_name'];

        // check mime type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($filepath);
        if (!preg_match('/^image\//', $mime_type)) {
            $_SESSION['message'] = 'not select file';
            $this->index();
            return;
        }

        if ($mime_type == 'image/jpeg') {
            // read exif & orienttation fixed
            require_once(__DIR__ . '/models/orientaion_fixed.php');
            $orientaion_fixed = new orientaion_fixed();
            $orientaion_fixed->fixed_image($filepath);
        }

        $image = file_get_contents($filepath);
        $base64 = base64_encode($image);
        $data['base64'] = $base64;
        $data['mime_type'] = $mime_type;

        $validation_token = hash("sha256", $base64) . strlen($base64);
        $_SESSION['validation_token'] = $validation_token;

        $this->view(__DIR__ . '/views/confirm.php', $data);
    }

    /**
     * file upload
     */
    public function upload()
    {
        if (empty($_POST['base64']) || $_SESSION['validation_token']){
            $_SESSION['message'] = 'file is invalid';
            header('location: ./controller.php');
            exit();
        }
        $base64 = $_POST['base64'];
        $token = hash("sha256", $base64) . strlen($base64);

        // validation
        if ($_SESSION['validation_token'] !== $token){
            $_SESSION['message'] = 'file is invalid';
            header('location: ./controller.php');
            exit();
        }
        unset($_SESSION['validation_token']);

        // define filename
        $timestamp = microtime(true);
        $timestamp = $timestamp*10000;
        $filepath = __DIR__ . "/uploaded/image_{$timestamp}";
        // image saving
        $image = base64_decode($base64);
        file_put_contents($filepath, $image);

        // add extension
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($filepath);
        $extension = str_replace('image/', '', $mime_type);
        rename($filepath, $filepath.".{$extension}");

        $_SESSION['message'] = 'upload success!';
        header('location: ./controller.php');
        exit();
    }

    private function view($viewfile, $params = [])
    {
        if (is_file($viewfile)) {
            extract($params);
            include ($viewfile);
            return;
        }
        throw new \LogicException('view file not found');
    }
}
