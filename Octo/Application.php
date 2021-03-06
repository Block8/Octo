<?php

namespace Octo;

use b8\Http\Request;
use Exception;
use b8\Exception\HttpException;
use Octo\Http\Response;
use b8\Http\Response\RedirectResponse;
use Octo;
use Octo\Admin;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\BlockManager;
use Octo\System\Model\Log;
use Octo\Store;
use Octo\Template;

/**
 * Class Application
 * @package Octo
 */
class Application extends \b8\Application
{
    public function __construct(\b8\Config $config)
    {
        $request = new Request();
        $response = new Response();

        parent::__construct($config, $request, $response);
    }

    /**
     * Setup the application and register basic routes
     */
    public function init()
    {
        $this->config->set('http', [
            'request' => &$this->request,
            'response' => &$this->response,
        ]);

        Event::trigger('BeforeSystemInit', $this);

        if (!defined('IS_CONSOLE')) {
            $this->initHttpRequest();
        }

        Event::trigger('AfterSystemInit', $rtn);

        return $rtn;
    }

    public function initHttpRequest()
    {
        Event::trigger('BeforeHttpRequestInit', $this);

        $path = $this->request->getPath();

        if (substr($path, -1) == '/' && $path != '/') {
            header('HTTP/1.1 302 Moved Permanently', true, 301);
            header('Location: ' . $this->config->get('site.url') . substr($path, 0, -1));
            die;
        }

        $this->router->clearRoutes();
        Event::trigger('BackupRoutes', $this->router);
        $this->router->register('/:controller/:action', ['namespace' => 'Controller', 'action' => 'index']);

        $route = '/'.$this->config->get('site.admin_uri').'/:controller/:action';
        $defaults = ['namespace' => 'Admin\\Controller', 'controller' => 'Dashboard', 'action' => 'index'];
        $request =& $this->request;

        Event::trigger('PrimaryRoutes', $this->router);

        $denied = [$this, 'permissionDenied'];

        $rtn = $this->registerRouter($route, $defaults, $request, $denied);

        $this->router->register('/robots.txt', ['controller' => 'Page'], function (&$route, Response &$response) {
            header('Content-Type: text/plain');
            $robots = <<<ROBOTS
User-Agent: *
Disallow: /manage/
Allow: /

Sitemap: {$this->config->site['url']}/sitemap.xml
ROBOTS;

            die($robots);
        });

        Event::trigger('AfterHttpRequestInit', $this);
    }

    /**
     * Register advanced routers
     *
     * @param $route
     * @param $defaults
     * @param $request
     * @param $denied
     */
    public function registerRouter($route, $defaults, $request, $denied)
    {
        $bypass = $this->config->get('Octo.bypass_auth');

        $this->router->register($route, $defaults, function (&$route, Response &$response) use (&$bypass, &$request, &$denied) {
            define('OCTO_ADMIN', true);

            if (!empty($_GET['session_auth'])) {
                session_id($_GET['session_auth']);
            }

            if (session_status() != PHP_SESSION_ACTIVE) {
                session_start();
            }

            if (array_key_exists($route['controller'], $bypass)) {
                $bypass = $bypass[$route['controller']];

                // If we're bypassing authentication for an entire controller:
                if ($bypass === true) {
                    return true;
                }

                // If we're bypassing authentication for a specific action:
                if (is_array($bypass) && in_array($route['action'], $bypass)) {
                    return true;
                }
            }

            if (!empty($_SESSION['user_id'])) {
                return $this->setupUserProperties($route, $response, $denied);
            }

            if ($request->isAjax()) {
                $response->setResponseCode(401);
                $response->setContent('');
            } else {
                $_SESSION['previous_url'] = $this->config->get('site.url') . $_SERVER['REQUEST_URI'];
                $response = new RedirectResponse($this->response);
                $response->setHeader('Location', $this->config->get('site.full_admin_url').'/session/login');
            }

            return false;
        });
    }

    /**
     * Setup the user's permissions etc. for the route
     *
     * @param $route
     * @param $response
     * @param $denied
     * @return bool
     */
    protected function setupUserProperties($route, $response, $denied)
    {
        /** @var \Octo\System\Model\User $user */
        $user = Store::get('User')->getByPrimaryKey($_SESSION['user_id']);

        if ($user && $user->getActive()) {
            $_SESSION['user'] = $user;

            $user->setDateActive(new \DateTime());
            Store::get('User')->save($user);

            $uri = '/';

            if ($route['controller'] != 'Dashboard') {
                $uri .= $route['controller'];
            }

            if ($route['action'] != 'index') {
                $uri .= '/' . $route['action'];
            }

            if (in_array($route['controller'], ['categories', 'media']) && isset($route['args'][0])) {
                $uri .= '/' . $route['args'][0];
            }

            if (!$user->canAccess($uri) && is_callable($denied)) {
                $denied($user, $uri, $response);
                return false;
            }

            return true;
        }
    }

    /**
     * Handle the request
     *
     * @return mixed
     * @throws \b8\Exception\HttpException
     * @throws \Exception
     * @throws \Exception
     */
    public function handleRequest()
    {
        try {
            $rtn = parent::handleRequest();

            if (extension_loaded('newrelic')) {
                $site = $this->toPhpName($this->config->get('site.name'));
                $controller = $this->toPhpName($this->route['controller']);
                $action = $this->toPhpName($this->route['action']);

                newrelic_name_transaction($site . '.' . $controller . '.' . $action);
            }
        } catch (HttpException\NotFoundException $ex) {
            $rtn = $this->handleHttpError($ex->getErrorCode());
        } catch (HttpException $ex) {
            if ((defined('CMS_ENV') && CMS_ENV == 'development') || array_key_exists('ex', $_GET)) {
                throw $ex;
            }

            $rtn = $this->handleHttpError($ex->getErrorCode());
        } catch (Exception $ex) {
            if ((defined('CMS_ENV') && CMS_ENV == 'development') || array_key_exists('ex', $_GET)) {
                throw $ex;
            }

            $rtn = $this->handleHttpError(500);
        }

        return $rtn;
    }

    /**
     * Handle HTTP error
     *
     * @param $code
     * @return mixed
     */
    protected function handleHttpError($code)
    {
        try {
            $template = new Template('Error/' . $code, (defined('OCTO_ADMIN') && OCTO_ADMIN ? 'admin' : null));
            $template->set('page', ['title' => 'Error ' . $code . ' - ' . Response::$codes[$code]]);
            $content = $template->render();

            $this->response->setResponseCode($code);
            $this->response->setContent($content);
        } catch (\Exception $ex) {}

        return $this->response;
    }

    /**
     * @return \b8\Controller
     */
    public function getController()
    {
        if (empty($this->controller)) {
            $class = null;
            $controller = $this->toPhpName($this->route['controller']);
            $controllerClass = '\\Octo\\' . $this->route['namespace'];

            if (class_exists($controllerClass)) {
                $class = $controllerClass::getClass($controller);
            }

            if (!is_null($class)) {
                $this->controller = $this->loadController($class);
            }
        }

        return $this->controller;
    }

    /**
     * @param $route
     * @return bool True if controller exists
     */
    protected function controllerExists($route)
    {
        $controller = $this->toPhpName($route['controller']);
        $controllerClass = '\\Octo\\' . $route['namespace'];
        $class = $controllerClass::getClass($controller);

        return !is_null($class);
    }

    /**
     * Callback if permission denied to access
     *
     * @param $user
     * @param $uri
     * @param $response
     */
    protected function permissionDenied($user, $uri, &$response)
    {
        $_SESSION['GlobalMessage']['error'] = 'You do not have permission to access: ' . $uri;

        $log = Log::create(Log::TYPE_PERMISSION, 'user', 'Unauthorised access attempt.');
        $log->setUser($user);
        $log->setLink($uri);
        $log->save();

        $response = new RedirectResponse($response);
        $response->setHeader('Location', $this->config->get('site.full_admin_url'));
        $response->flush();
    }

    public function isValidRoute($route)
    {
        if ($route['namespace'] == 'Admin\Controller') {
            return true;
        }

        return parent::isValidRoute($route);
    }
}
