<?php
namespace Octo\System\Event;

use b8\Config;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Template;

class AssetManager extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('OnTemplateRender', array($this, 'injectAssets'));
    }

    public function injectAssets(Template &$template)
    {
        $html = $template->getTemplateCode();
        $inject = $this->getAssetCode();

        if (stripos($html, '</head>') !== false) {
            $html = str_replace('</head>', $inject['css'] . PHP_EOL . '</head>', $html);
            $template->setTemplateCode($html);
        }

        if (stripos($html, '</body>') !== false) {
            $html = str_replace('</body>', $inject['js'] . PHP_EOL . '</body>', $html);
            $template->setTemplateCode($html);
        }
    }

    protected function getAssetCode()
    {
        /** @var \Octo\AssetManager $assets */
        $assets = Config::getInstance()->get('Octo.AssetManager');
        $inject = ['css' => '', 'js' => ''];

        foreach ($assets->getCss() as $css) {
            $href = '/asset/css/'.$css['module'].'/'.$css['name'];
            $inject['css'] .= '<link rel="stylesheet" type="text/css" href="'.$href.'">';
        }

        foreach ($assets->getJs() as $js) {
            $href = '/asset/js/'.$js['module'].'/'.$js['name'];
            $inject['js'] .= '<script src="'.$href.'"></script>';
        }

        return $inject;
    }
}
