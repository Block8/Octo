<?php
namespace Octo\Event\Listener;

use b8\Config;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Template;

class TemplateFunctions extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('AdminTemplateLoaded', array($this, 'globalTemplateFunctions'));
        $manager->registerListener('PublicTemplateLoaded', array($this, 'globalTemplateFunctions'));

        $manager->registerListener('AdminTemplateLoaded', array($this, 'adminTemplateFunctions'));
    }

    public function adminTemplateFunctions(Template &$template)
    {
        $template->addFunction('date_format', function ($args, &$view) {
            $date = $view->getVariable($args['date']);

            if (!($date instanceof \DateTime)) {
                return '';
            }

            if (!isset($args['format'])) {
                $format = 'jS F Y, g:ia';
            } elseif ($args['format'] == 'short') {
                $format = 'd/m/Y g:ia';
            } elseif ($args['format'] == 'long_date') {
                $format = 'jS F Y';
            } elseif ($args['format'] == 'date') {
                $format = 'd/m/Y';
            } elseif ($args['format'] == 'time') {
                $format = 'g:ia';
            } else {
                $format = 'jS F Y, g:ia';
            }

            return $date->format($format);
        });

        $template->addFunction('can_access', function ($args, &$view) {
            $uri = $view->getVariable($args['uri']);

            return $_SESSION['user']->canAccess($uri);
        });
    }

    public function globalTemplateFunctions(Template &$template)
    {
        $config = Config::getInstance();

        $template->set('adminUri', $config->get('site.admin_uri'));
        $template->set('config', $config);
    }
}
