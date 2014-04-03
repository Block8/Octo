<?php
namespace Octo\Admin;

use b8\Config;
use b8\Http\Request;
use b8\Http\Response;
use Octo\Admin\Menu;
use Octo\Admin\Template;

abstract class Controller extends \b8\Controller
{
    protected $className = '';

    /**
     * @var \Octo\System\Model\User
     */
    protected $currentUser;

    /**
     * @var Menu
     */
    protected $menu;

    /**
     * @var View Turn view public to make it accessible to listeners
     */
    public $view;

    /**
     * @param Config $config
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Config $config, Request $request, Response $response)
    {
        $class = explode('\\', get_class($this));
        $this->className = substr(array_pop($class), 0, -10);
        $this->layout = Template::getAdminTemplate('layout');

        if (isset($_SESSION['user'])) {
            $this->menu = new Menu();
            $this->currentUser = $_SESSION['user'];
        }

        $this->layout->siteName = $config->get('site.name');
        $this->layout->breadcrumb = array();
        $this->layout->currentUser = $this->currentUser;
        $this->layout->menu = $this->menu;

        if (file_exists(APP_PATH . 'public/assets/images/cms-logo.png')) {
            $this->layout->siteLogo = true;
        }

        return parent::__construct($config, $request, $response);
    }

    public function init()
    {
    }

    public function handleAction($action, $params)
    {
        try {
            $thisClass = explode('\\', get_class($this));
            $module = $thisClass[1];

            $this->view = Template::getAdminTemplate($this->className . '/' . $action, $module);
            $this->view->currentUser = $this->currentUser;
        } catch (\Exception $ex) {
            $error = '<div class="alert alert-danger">You have not created a view for: ';
            $error .= $this->className . '/' . $action . '</div>';
            $this->view = new Template($error);
        }

        // Set up GlobalMessage:
        if (!empty($this->view)) {
            if (!empty($_SESSION['GlobalMessage']['success'])) {
                $this->view->GlobalMessage()->success = $_SESSION['GlobalMessage']['success'];
                unset($_SESSION['GlobalMessage']['success']);
            }

            if (!empty($_SESSION['GlobalMessage']['error'])) {
                $this->view->GlobalMessage()->error = $_SESSION['GlobalMessage']['error'];
                unset($_SESSION['GlobalMessage']['error']);
            }

            if (!empty($_SESSION['GlobalMessage']['info'])) {
                $this->view->GlobalMessage()->info = $_SESSION['GlobalMessage']['info'];
                unset($_SESSION['GlobalMessage']['info']);
            }
        }

        $output = parent::handleAction($action, $params);

        if (empty($output) && !empty($this->view)) {
            $output = $this->view->render();
        }

        $this->response->setContent($output);

        if ($this->response->hasLayout()) {
            $this->layout->user = $_SESSION['user'];
            $this->layout->content = $this->response->getContent();
            $this->response->setContent($this->layout->render());
        }

        return $this->response;
    }

    public function successMessage($message, $afterRedirect = false)
    {
        if ($afterRedirect) {
            $_SESSION['GlobalMessage']['success'] = $message;
        }

        if (!empty($this->view)) {
            $this->view->GlobalMessage()->success = $message;
        }
    }

    public function errorMessage($message, $afterRedirect = false)
    {
        if ($afterRedirect) {
            $_SESSION['GlobalMessage']['error'] = $message;
        }

        if (!empty($this->view)) {
            $this->view->GlobalMessage()->error = $message;
        }
    }

    public function infoMessage($message, $afterRedirect = false)
    {
        if ($afterRedirect) {
            $_SESSION['GlobalMessage']['info'] = $message;
        }

        if (!empty($this->view)) {
            $this->view->GlobalMessage()->info = $message;
        }
    }

    public function setTitle($title)
    {
        $this->layout->title = $title;
    }

    public function addBreadcrumb($title, $link = null)
    {
        $breadcrumb = $this->layout->breadcrumb;
        $breadcrumb[] = array('title' => $title, 'link' => $link);
        $this->layout->breadcrumb = $breadcrumb;
    }

    public function popBreadcrumb() {
        $breadcrumbs = $this->layout->breadcrumb;
        $item = array_pop($breadcrumbs);
        $this->layout->breadcrumb = $breadcrumbs;
        return $item;
    }

    public static function getClass($controller) {
        $config = Config::getInstance();
        $siteModules = array_reverse($config->get('ModuleManager')->getEnabled());

        foreach ($siteModules as $namespace => $modules) {
            foreach ($modules as $module) {
                $class = "\\{$namespace}\\{$module}\\Admin\\Controller\\{$controller}Controller";

                if (class_exists($class)) {
                    return $class;
                }
            }
        }

        return null;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
