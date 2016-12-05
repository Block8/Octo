<?php
namespace Octo\System\Event;

use b8\Config;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Http\Response;
use Octo\Template;
use Octo\Admin\Template as AdminTemplate;
use Octo\Html;

class AdminAssets extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('Controller.Loaded.Admin', array($this, 'defineAdminAssets'));
    }
    
    public function defineAdminAssets()
    {
        /** @var \Octo\AssetManager $assets */
        $assets = $this->config->get('Octo.AssetManager');
        $assets->addThirdParty('css', 'System', 'bootstrap/css/bootstrap.min.css', true);
        $assets->addThirdParty('css', 'System', 'admin-lte/css/adminlte.min.css', true);
        $assets->addThirdParty('css', 'System', 'bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css');
        $assets->addThirdParty('css', 'System', 'select2/css/select2.min.css');
        $assets->addThirdParty('css', 'System', 'font-awesome/css/font-awesome.min.css');

        $assets->addThirdParty('js', 'System', 'jquery/jquery.min.js', true);
        $assets->addThirdParty('js', 'System', 'bootstrap/js/bootstrap.min.js', true);
        $assets->addThirdParty('js', 'System', 'jquery-ui/jquery-ui.min.js', true);
        $assets->addThirdParty('js', 'System', 'class.js', true);
        $assets->addThirdParty('js', 'System', 'admin-lte/js/app.min.js');
        $assets->addThirdParty('js', 'System', 'packery/packery.min.js');
        $assets->addThirdParty('js', 'System', 'moment/moment.js');
        $assets->addThirdParty('js', 'System', 'bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');
        $assets->addThirdParty('js', 'System', 'select2/js/select2.full.min.js');
        $assets->addThirdParty('js', 'System', 'ckeditor/ckeditor.js');
        $assets->addThirdParty('js', 'System', 'ckeditor/adapters/jquery.js');

        $assets->addCss('System', 'octo');
        $assets->addJs('System', 'system', true);
        $assets->addJs('System', 'forms', true);

        $assets->addJs('System', 'ckeditor/init');
        $assets->addJs('System', 'ckeditor/octo.image/plugin');
        $assets->addJs('System', 'ckeditor/octo.link/plugin');
    }
}
