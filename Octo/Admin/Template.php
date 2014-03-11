<?php

namespace Octo\Admin;

class Template extends \Octo\Template
{
    public static function createFromFile($file, $path = null)
    {
        if (!static::exists($file, $path)) {
            throw new \Exception('View file does not exist: ' . $file);
        }

        $viewFile = static::getViewFile($file, $path);
        return new static(file_get_contents($viewFile));
    }

    public static function getAdminTemplate($template)
    {
        $rtn = parent::getAdminTemplate($template);

        $rtn->addFunction('pagination', array($rtn, 'handlePagination'));

        $rtn->addFunction('hash', function ($args, $view) {
            return md5($view->getVariable($args['value']));
        });

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
