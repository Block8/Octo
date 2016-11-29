<?php
namespace Octo\System\Event;

use b8\Config;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Http\Response;
use Octo\Template;
use Octo\Admin\Template as AdminTemplate;
use Octo\Html;

class AssetManager extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('Response.Flush', array($this, 'injectAssets'));
    }

    public function injectAssets(Response $response)
    {
        $inject = $this->getAssetCode();

        $html = $response->getContent();

        if (stripos($html, '</head>') !== false) {
            $html = str_replace('</head>', $inject['css'] . PHP_EOL . '</head>', $html);
        }

        if (stripos($html, '</body>') !== false) {
            $html = str_replace('</body>', $inject['js'] . PHP_EOL . '</body>', $html);
        }

        $response->setContent($html);
    }

    protected function getAssetCode()
    {
        $config = $this->config;

        /** @var \Octo\Http\Response $response */
        $response = $config->get('http.response');
        $paths = $config->get('Octo.paths.modules');

        /** @var \Octo\AssetManager $assets */
        $assets = $config->get('Octo.AssetManager');
        $inject = ['css' => '', 'js' => ''];

        foreach ($assets->getCss() as $css) {
            $path = $paths[$css['module']] . 'Public/css/' . $css['name'] . '.css';

            if (is_file($path)) {
                $href = '/asset/css/'.$css['module'].'/'.$css['name'].'?t='.filemtime($path);
                $url = $config->get('site.url') . $href;
                $response->setHeader('Link', '<'.$href.'>; rel=preload; as=style');
                $inject['css'] .= '<link rel="stylesheet" type="text/css" href="'.$url.'">';
            }
        }

        foreach ($assets->getExternalJs() as $js) {
            $inject['js'] .= '<script src="'.$js.'"></script>';
        }

        foreach ($assets->getJs() as $js) {
            $path = $paths[$js['module']] . 'Public/js/' . $js['name'] . '.js';

            if (is_file($path)) {
                $href = '/asset/js/'.$js['module'].'/'.$js['name'].'?t='.filemtime($path);
                $url = $config->get('site.url') . $href;
                $response->setHeader('Link', '<'.$href.'>; rel=preload; as=script');
                $inject['js'] .= '<script src="'.$url.'"></script>';
            }
        }
        

        if (!defined('OCTO_ADMIN') || OCTO_ADMIN == false) {
            // Site CSS and JS:
            $siteNamespace = $this->config->get('site.namespace');
            $path = APP_PATH . $siteNamespace . '/Public/css/site.css';

            if (file_exists($path)) {
                $href = '/asset/css/site?t='.filemtime($path);
                $url = $this->config->get('site.url') . $href;
                $response->setHeader('Link', '<'.$href.'>; rel=preload; as=style');
                $inject['css'] .= '<link rel="stylesheet" type="text/css" href="'.$url.'">';
            }

            $path = APP_PATH . $siteNamespace . '/Public/js/site.js';

            if (file_exists($path)) {
                $href = '/asset/js/site?t='.filemtime($path);
                $url = $config->get('site.url') . $href;
                $response->setHeader('Link', '<'.$href.'>; rel=preload; as=script');
                $inject['js'] .= '<script src="'.$url.'"></script>';
            }
        }


        return $inject;
    }
}
