<?php

namespace Octo;

use Exception;
use b8\Exception\HttpException;
use b8\Http\Response;
use b8\Http\Response\RedirectResponse;
use Octo;
use Octo\Admin;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\BlockManager;
use Octo\System\Model\Log;
use Octo\Store;
use Octo\Template;

class Application extends \b8\Application
{
    public function init()
    {
        $path = $this->request->getPath();

        if (substr($path, -1) == '/' && $path != '/') {
            header('HTTP/1.1 301 Moved Permanently', true, 301);
            header('Location: ' . substr($path, 0, -1));
            die;
        }

        $this->router->clearRoutes();
        $this->router->register('/', ['controller' => 'Page', 'action' => 'View']);
        $this->router->register('/:controller/:action', ['namespace' => 'Controller', 'action' => 'index']);

        $route = '/'.$this->config->get('site.admin_uri').'/:controller/:action';
        $defaults = ['namespace' => 'Admin\\Controller', 'controller' => 'Dashboard', 'action' => 'index'];
        $request =& $this->request;

        $denied = [$this, 'permissionDenied'];

        $this->router->register($route, $defaults, function (&$route, Response &$response) use (&$request, &$denied) {
            session_start();

            if ($route['controller'] != 'session') {
                if (!empty($_SESSION['user_id'])) {
                    $user = Store::get('User')->getByPrimaryKey($_SESSION['user_id']);

                    if ($user) {
                        $_SESSION['user'] = $user;

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

                    unset($_SESSION['user_id']);
                }

                if ($request->isAjax()) {
                    $response->setResponseCode(401);
                    $response->setContent('');
                } else {
                    $_SESSION['previous_url'] = $_SERVER['REQUEST_URI'];
                    $response = new RedirectResponse($this->response);
                    $response->setHeader('Location', '/'.$this->config->get('site.admin_uri').'/session/login');
                }

                return false;

            }

            return true;
        });
    }

    public function handleRequest()
    {
        try {
            $rtn = parent::handleRequest();
        } catch (HttpException $ex) {
            throw $ex;
            $rtn = $this->handleHttpError($ex->getErrorCode());
        } catch (Exception $ex) {
            throw $ex;
            $rtn = $this->handleHttpError(500);
        }

        return $rtn;
    }

    protected function handleHttpError($code)
    {
        if (Template::exists('Error/' . $code)) {

            $this->response->setResponseCode($code);

            $template = Template::getPublicTemplate('Error/' . $code);
            $blockManager = new BlockManager();
            $blockManager->setRequest($this->request);
            $blockManager->setResponse($this->response);
            $blockManager->attachToTemplate($template);

            $this->response->setContent($template->render());
        }

        return $this->response;
    }

    /**
     * @return \b8\Controller
     */
    public function getController()
    {
        if (empty($this->controller)) {
            $controller = $this->toPhpName($this->route['controller']);
            $controllerClass = '\\Octo\\' . $this->route['namespace'];
            $class = $controllerClass::getClass($controller);

            if (!is_null($class)) {
                $this->controller = $this->loadController($class);
            }
        }

        return $this->controller;
    }

    protected function controllerExists($route)
    {
        $controller = $this->toPhpName($route['controller']);
        $controllerClass = '\\Octo\\' . $route['namespace'];
        $class = $controllerClass::getClass($controller);

        return !is_null($class);
    }

    protected function permissionDenied($user, $uri, &$response)
    {
        $_SESSION['GlobalMessage']['error'] = 'You do not have permission to access: ' . $uri;

        $log = Log::create(Log::TYPE_PERMISSION, 'user', 'Unauthorised access attempt.');
        $log->setUser($user);
        $log->setLink($uri);
        $log->save();

        $response = new RedirectResponse();
        $response->setHeader('Location', '/'.$this->config->get('site.admin_uri'));
    }
}
