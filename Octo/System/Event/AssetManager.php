<?php
namespace Octo\System\Event;

use b8\Config;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Template;
use Octo\Admin\Template as AdminTemplate;
use Octo\Html;

class AssetManager extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('OnTemplateRender', array($this, 'injectAssets'));
        $manager->registerListener('OnHtmlTemplateRender', array($this, 'injectAssets'));
    }

    public function injectAssets(&$html)
    {
        $inject = $this->getAssetCode();

        if (stripos($html, '</head>') !== false) {
            $html = str_replace('</head>', $inject['css'] . PHP_EOL . '</head>', $html);
        }

        if (stripos($html, '</body>') !== false) {
            $html = str_replace('</body>', $inject['js'] . PHP_EOL . '</body>', $html);
        }

        return $html;
    }

    protected function getAssetCode()
    {
        /** @var \b8\Config */
        $config = Config::getInstance();
        $paths = $config->get('Octo.paths.modules');

        /** @var \Octo\AssetManager $assets */
        $assets = $config->get('Octo.AssetManager');
        $inject = ['css' => '', 'js' => ''];

        foreach ($assets->getCss() as $css) {
            $path = $paths[$css['module']] . 'Public/css/' . $css['name'] . '.css';

            if (is_file($path)) {
                $href = $config->get('site.url').'/asset/css/'.$css['module'].'/'.$css['name'].'?t='.filemtime($path);
                $inject['css'] .= '<link rel="stylesheet" type="text/css" href="'.$href.'">';
            }
        }

        foreach ($assets->getExternalJs() as $js) {
            $inject['js'] .= '<script src="'.$js.'"></script>';
        }

        foreach ($assets->getJs() as $js) {
            $path = $paths[$js['module']] . 'Public/js/' . $js['name'] . '.js';

            if (is_file($path)) {
                $href = $config->get('site.url').'/asset/js/'.$js['module'].'/'.$js['name'].'?t='.filemtime($path);
                $inject['js'] .= '<script src="'.$href.'"></script>';
            }
        }

        return $inject;
    }
}
