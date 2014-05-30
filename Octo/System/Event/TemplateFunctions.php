<?php
namespace Octo\System\Event;

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
        $template->addFunction('pagination', array($this, 'handlePagination'));
    }

    /**
     * Setup pagination
     *
     * @param $args
     * @param Template $view
     * @return string
     */
    public function handlePagination($args, Template $view)
    {
        $uri = $view->getVariable($args['uri']);
        $current = $view->getVariable($args['current']);
        $total = $view->getVariable($args['total']);
        $limit = $view->getVariable($args['limit']);

        $uri   = preg_replace('/offset=([0-9]+)/', '', $uri);
        $pages = ceil($total / $limit);
        $page  = $current;

        if ($pages < 2) {
            return;
        }

        $start = 1;

        if ($page >= 3) {
            $start = $page - 2;
        }

        $rtn = '<ul class="pagination">';

        $rtn .= $this->getPaginationPrevLink($page, $uri);

        for ($i = 0; $i < 5 && ($start + $i) <= $pages; $i++) {
            $num = $start + $i;
            $cls = ($page == $num ? 'active' : '');
            $rtn .= '<li class="' . $cls . '"><a href="' . $uri . 'p=' . $num . '">' . $num . '</a></li>';
        }

        $rtn .= $this->getPaginationNextLink($page, $uri, $pages);

        $rtn .= '</ul>';

        return $rtn;
    }

    /**
     * Setup pagination previous link
     *
     * @param $page
     * @param $uri
     * @return string
     */
    protected function getPaginationPrevLink($page, $uri)
    {
        $rtn = '';

        if ($page != 1) {
            $cls = ($page == 1 ? 'disabled' : '');
            $rtn .= '<li class="' . $cls . '"><a href="' . $uri . '">&laquo;</a></li>';
            $rtn .= '<li class="' . $cls . '"><a href="' . $uri . 'p=' . ($page - 1) . '">&laquo; Prev</a></li>';
        }

        return $rtn;
    }

    /**
     * Setup pagination next link
     *
     * @param $page
     * @param $uri
     * @param $pages
     * @return string
     */
    protected function getPaginationNextLink($page, $uri, $pages)
    {
        $rtn = '';

        if ($page != $pages) {
            $cls = ($page == $pages ? 'disabled' : '');
            $rtn .= '<li class="' . $cls . '"><a href="' . $uri . 'p=' . ($page + 1) . '">Next &raquo;</a></li>';
            $rtn .= '<li class="' . $cls . '"><a href="' . $uri . 'p=' . $pages . '">&raquo;</a></li>';
        }

        return $rtn;
    }
}
