<?php
namespace Controller;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class FrontController
 * @package Pam\Controller
 */
class FrontController
{
    const DEFAULT_PATH        = 'Controller\\';
    const DEFAULT_CONTROLLER  = 'HomeController';
    const DEFAULT_ACTION      = 'indexAction';
    /**
     * @var null
     */
    protected $twig = null;
    /**
     * @var null
     */
    protected $page = null;
    /**
     * @var string
     */
    protected $controller = self::DEFAULT_CONTROLLER;
    /**
     * @var string
     */
    protected $action = self::DEFAULT_ACTION;
    /**
     * FrontController constructor
     */
    public function __construct()
    {
        $this->setTemplate();
        $this->parseUrl();
        $this->setController();
        $this->setAction();
    }
    /**
     * @return mixed|void
     */
    public function setTemplate()
    {
        $loader = new FilesystemLoader('../src/View');
        $twig   = new Environment($loader, array(
            'cache' => false,
            'debug' => true
        ));

        $this->twig = $twig;
    }
    /**
     * @return mixed|void
     */
    public function parseUrl()
    {
        if (!array_key_exists('p', $_GET) || !isset($_GET['p'])) {
            $_GET['p'] = 'home';
        }
        $this->page = strval($_GET['p']);
        $access = explode('!', $this->page);
        $this->controller   = $access[0];
        $this->action       = count($access) == 1 ? 'index' : $access[1];
    }
    /**
     * @return mixed|void
     */
    public function setController()
    {
        $this->controller = ucfirst(strtolower($this->controller)) . 'Controller';
        $this->controller = self::DEFAULT_PATH . $this->controller;
        if (!class_exists($this->controller)) {
            $this->controller = self::DEFAULT_PATH . self::DEFAULT_CONTROLLER;
        }
    }
    /**
     * @return mixed|void
     */
    public function setAction()
    {
        $this->action = strtolower($this->action) . 'Action';
        if (!method_exists($this->controller, $this->action)) {
            $this->action = self::DEFAULT_ACTION;
        }
    }
    /**
     * @return mixed|void
     */
    public function run()
    {
        $this->controller   = new $this->controller($this->twig);
        $response           = call_user_func([$this->controller, $this->action]);
        echo $response;
    }
}
