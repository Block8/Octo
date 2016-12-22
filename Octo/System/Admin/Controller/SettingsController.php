<?php
namespace Octo\System\Admin\Controller;

use b8\Form;
use Octo\Store;
use Octo\Admin\Controller;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Menu;

class SettingsController extends Controller
{
    /**
     * Setup page
     *
     * @return void
     */
    public function init()
    {
        $this->setTitle('Settings');
        $this->addBreadcrumb('Settings', '/settings');
    }

    public function info()
    {
        ob_start();
        phpinfo();
        $info = ob_get_contents();
        ob_end_clean();

        $matches = [];
        preg_match('/\<body\>(.*)?\<\/body\>/si', $info, $matches);
        $info = $matches[0];

        $info = str_replace('<table>', '<table class="table">', $info);

        $this->template->info = $info;
    }
}
