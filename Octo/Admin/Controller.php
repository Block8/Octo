<?php
namespace Octo\Admin;

use b8\Config;
use b8\Http\Request;
use b8\Http\Response;
use Octo\Admin\Menu;
use Octo\Admin\Template as LegacyTemplate;
use Octo\Template;

/**
 * Class Controller
 * @package Octo\Admin
 */
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
     * @var Template
     */
    protected $layout;

    /**
     * @var \Octo\Template Twig template for this controller.
     */
    public $template;

    protected $title;
    protected $subtitle;
    protected $breadcrumb;

    /**
     * @param Config $config
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Config $config, Request $request, Response $response)
    {
        $class = explode('\\', get_class($this));
        $this->className = substr(array_pop($class), 0, -10);

        if (isset($_SESSION['user'])) {
            $this->menu = new Menu();
            $this->currentUser = $_SESSION['user'];
        }

        return parent::__construct($config, $request, $response);
    }

    /**
     * Every controller requires an init function
     */
    public function init()
    {
    }

    /**
     * Handle the requested action
     *
     * @param $action
     * @param $params
     * @return mixed
     */
    public function handleAction($action, $params)
    {
        $this->setupTemplate($this->className, $action);
        $output = parent::handleAction($action, $params);

        // No output and no template set:
        if (empty($output) && empty($this->template)) {
            throw new \Exception('No output or template for ' . $this->className . '.' . $action);
        }

        // No output, but we do have a legacy view to render:
        if (empty($output) && !empty($this->view)) {
            $output = $this->view->render();
        }

        // Has output, handle legacy style layout for it:
        if (!empty($output) && $this->response->hasLayout()) {
            $this->template->output = $output;
        }
        
        if (!empty($output) && !$this->response->hasLayout()) {
            return $output;
        }

        $this->template->set('title', $this->title);
        $this->template->set('subtitle', $this->subtitle);
        $this->template->set('breadcrumb', $this->breadcrumb);

        $this->response->setContent($this->template->render());
        return $this->response;
    }

    protected function setupTemplate($controller, $action)
    {
        try {
            $this->template = new Template($controller . '/' . $action, 'admin');
        } catch (\Exception $ex) {
            $this->setupLegacyTemplate($controller, $action);
        }

        if (empty($this->template)) {
            return;
        }

        $this->template->set('siteName', $this->config->get('site.name'));
        $this->template->set('breadcrumb', array());
        $this->template->set('currentUser', $this->currentUser);
        $this->template->set('user', $this->currentUser);
        $this->template->set('menu', $this->menu);

        if (file_exists(APP_PATH . 'public/assets/backoffice.css')) {
            $this->template->set('siteCss', true);
        }

        if (file_exists(APP_PATH . 'public/assets/images/cms-logo.png')) {
            $this->template->set('siteLogo', true);
        }

        if (!empty($_SESSION['GlobalMessage']['success'])) {
            $this->template->globalSuccessMessage = $_SESSION['GlobalMessage']['success'];
            unset($_SESSION['GlobalMessage']['success']);
        }

        if (!empty($_SESSION['GlobalMessage']['error'])) {
            $this->template->globalErrorMessage = $_SESSION['GlobalMessage']['error'];
            unset($_SESSION['GlobalMessage']['error']);
        }

        if (!empty($_SESSION['GlobalMessage']['info'])) {
            $this->template->globalInfoMessage = $_SESSION['GlobalMessage']['info'];
            unset($_SESSION['GlobalMessage']['info']);
        }
    }

    protected function setupLegacyTemplate($controller, $action)
    {
        if (LegacyTemplate::exists($controller . '/' . $action)) {
            $this->template = new Template('legacy', 'admin');
            $this->view = LegacyTemplate::getAdminTemplate($controller . '/' . $action);
            $this->view->currentUser = $this->currentUser;
        }
    }

    /**
     * Display a success message
     *
     * @param $message
     * @param bool $afterRedirect
     */
    public function successMessage($message, $afterRedirect = false)
    {
        if ($afterRedirect) {
            $_SESSION['GlobalMessage']['success'] = $message;
        }

        if (!empty($this->template)) {
            $this->template->globalSuccessMessage = $message;
        }
    }

    /**
     * Display an error message
     *
     * @param $message
     * @param bool $afterRedirect
     */
    public function errorMessage($message, $afterRedirect = false)
    {
        if ($afterRedirect) {
            $_SESSION['GlobalMessage']['error'] = $message;
        }

        if (!empty($this->template)) {
            $this->template->globalErrorMessage = $message;
        }
    }

    /**
     * Display an info message
     *
     * @param $message
     * @param bool $afterRedirect
     */
    public function infoMessage($message, $afterRedirect = false)
    {
        if ($afterRedirect) {
            $_SESSION['GlobalMessage']['info'] = $message;
        }

        if (!empty($this->template)) {
            $this->template->globalInfoMessage = $message;
        }
    }

    /**
     * Set the title to display
     */
    public function setTitle($title, $subtitle = null)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
    }

    /**
     * Add an item to the breadcrumbs
     *
     * @param $title
     * @param null $link
     */
    public function addBreadcrumb($title, $link = null)
    {
        $this->breadcrumb[] = array('title' => $title, 'link' => $link);
    }

    /**
     * Remove and return the last breadcrumb
     *
     * @return mixed
     */
    public function popBreadcrumb()
    {
        return array_pop($this->breadcrumb);
    }

    /**
     * Get the controller's class
     *
     * @param $controller
     * @return null|string
     */
    public static function getClass($controller)
    {
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

    /**
     * Get the request object
     *
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    protected function redirect($to, $successMessage = null)
    {
        if (!in_array(substr($to, 0, 6), ['http:/', 'https:'])) {
            $to = $this->config->get('site.full_admin_url') . $to;
        }

        if (!empty($successMessage)) {
            $this->successMessage($successMessage, true);
        }

        $this->response = new Response\RedirectResponse($this->response);
        $this->response->setHeader('Location', $to);
        $this->response->flush();
    }
}
