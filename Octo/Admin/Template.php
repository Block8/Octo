<?php

namespace Octo\Admin;

class Template extends \Octo\Template
{
    protected static $templateType = 'admin_templates';

    public static function createFromFile($file, $path = null)
    {
        return new static(file_get_contents($file));
    }

    public static function getAdminTemplate($template, $module = null)
    {
        $rtn = parent::getAdminTemplate($template, $module);

        if (!is_null($rtn)) {
            $rtn->addFunction('pagination', array($rtn, 'handlePagination'));

            $rtn->addFunction('hash', function ($args, $view) {
                    return md5($view->getVariable($args['value']));
                });
        }

        return $rtn;
    }

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
