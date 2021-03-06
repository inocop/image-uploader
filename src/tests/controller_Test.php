<?php

class controller_Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var controller
     */
    protected $controller;

    public static function setUpBeforeClass()
    {
        @session_start();
        require_once(dirname(__FILE__) . '/../vendor/autoload.php');
    }

    protected function setUp()
    {
    }

    public function test_show_index()
    {
        ob_start();

        require_once(dirname(__FILE__) . '/../public/controller.php');
        $this->controller = new controller();

        $_SESSION['message'] = 'test_message';
        $this->controller->index();
        $output = ob_get_clean();

        $this->assertContains('Image upload', $output);
        $this->assertContains('test_message', $output);
    }

    public function test_not_select_file()
    {
        ob_start();

        $this->controller = new controller();
        $this->controller->confirm();
        $output = ob_get_clean();

        $this->assertContains('Image upload', $output);
        $this->assertContains('not select file', $output);
    }
}