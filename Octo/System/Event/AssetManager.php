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
            $html = str_replace('</head>', $inject['header'] . PHP_EOL . '</head>', $html);
        }

        if (stripos($html, '</body>') !== false) {
            $html = str_replace('</body>', $inject['footer'] . PHP_EOL . '</body>', $html);
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
        $inject = ['header' => '', 'footer' => ''];

        foreach ($assets->getAssets() as $asset) {
            $thisAsset = '';

            switch ($asset['type']) {
                case 'css':
                    $path = $paths[$asset['module']] . 'Public/css/' . $asset['name'] . '.css';

                    if (is_file($path)) {
                        $thisHref = '/asset/css/'.$asset['module'].'/'.$asset['name'].'?t='.filemtime($path);
                        $url = $config->get('site.url') . $thisHref;
                        $response->setHeader('Link', '<'.$thisHref.'>; rel=preload; as=style');
                        $thisAsset = '<link rel="stylesheet" type="text/css" href="'.$url.'">';
                    }
                break;

                case 'js':
                    $path = $paths[$asset['module']] . 'Public/js/' . $asset['name'] . '.js';

                    if (is_file($path)) {
                        $thisHref = '/asset/js/'.$asset['module'].'/'.$asset['name'].'?t='.filemtime($path);
                        $url = $config->get('site.url') . $thisHref;
                        $response->setHeader('Link', '<'.$thisHref.'>; rel=preload; as=script');
                        $thisAsset = '<script src="'.$url.'"></script>';
                    }
                break;

                case 'third-party.css':
                    $path = $paths[$asset['module']] . 'Public/third-party/' . $asset['name'];

                    if (is_file($path)) {
                        $thisHref = '/asset/third-party/'.$asset['module'].'/'.$asset['name'].'?t='.filemtime($path);
                        $url = $config->get('site.url') . $thisHref;
                        $response->setHeader('Link', '<'.$thisHref.'>; rel=preload; as=style');
                        $thisAsset = '<link rel="stylesheet" type="text/css" href="'.$url.'">';
                    }
                    break;

                case 'third-party.js':
                    $path = $paths[$asset['module']] . 'Public/third-party/' . $asset['name'];

                    if (is_file($path)) {
                        $thisHref = '/asset/third-party/'.$asset['module'].'/'.$asset['name'].'?t='.filemtime($path);
                        $url = $config->get('site.url') . $thisHref;
                        $response->setHeader('Link', '<'.$thisHref.'>; rel=preload; as=script');
                        $thisAsset = '<script src="'.$url.'"></script>';
                    }
                    break;
            }

            if (!empty($thisAsset)) {
                $inject[$asset['head'] ? 'header' : 'footer'] .= $thisAsset . PHP_EOL;
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
                $inject['header'] .= '<link rel="stylesheet" type="text/css" href="'.$url.'">';
            }

            $path = APP_PATH . $siteNamespace . '/Public/js/site.js';

            if (file_exists($path)) {
                $href = '/asset/js/site?t='.filemtime($path);
                $url = $config->get('site.url') . $href;
                $response->setHeader('Link', '<'.$href.'>; rel=preload; as=script');
                $inject['footer'] .= '<script src="'.$url.'"></script>';
            }
        }


        return $inject;
    }
}
