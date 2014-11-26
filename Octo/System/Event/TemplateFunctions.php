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
        $template->addFunction('canAccess', function ($args, &$view) {
            return $_SESSION['user']->canAccess($args['uri']);
        });

    }

    public function globalTemplateFunctions(Template &$template)
    {
        $config = Config::getInstance();
        $template->addFunction('date_format', function ($args, &$view) {
            $date = $args['date'];

            $format = null;

            if (isset($args['format'])) {
                $format = $args['format'];
            }

            if (!($date instanceof \DateTime)) {
                return '';
            }

            if (empty($format)) {
                $format = 'jS F Y, g:ia';
            } elseif ($format == 'friendly') {
                return $this->friendlyDate($date);
            } elseif ($format == 'short') {
                $format = 'd/m/Y g:ia';
            } elseif ($format == 'long_date') {
                $format = 'jS F Y';
            } elseif ($format == 'date') {
                $format = 'd/m/Y';
            } elseif ($format == 'time') {
                $format = 'g:ia';
            } else {
                $format = 'jS F Y, g:ia';
            }

            return $date->format($format);
        });
		
		$template->set('date_now', new \DateTime());
        $template->set('adminUri', $config->get('site.admin_uri'));
        $template->set('config', $config);
        $template->addFunction('pagination', array($this, 'handlePagination'));
        $template->addFunction('var_dump', function ($args, Template $view) {
            ob_start();
            var_dump($args['variable']);
            $rtn = ob_get_contents();
            ob_end_clean();

            return $rtn;
        });
        $template->addFunction('is_mobile', function ($args, &$view) {
                if (class_exists('\Mobile_Detect')) {
                    $mobileDetect = new \Mobile_Detect();
                    return $mobileDetect->isMobile();
                }

                return false;
            });
    }

    protected function friendlyDate(\DateTime $date)
    {
        $now = new \DateTime();
        $yesterday = new \DateTime('-1 day');

        if ($date->format('Y-m-d') == $now->format('Y-m-d')) {
            return $date->format('g:ia');
        } elseif ($date->format('Y-m-d') == $yesterday->format('Y-m-d')) {
            return 'Yesterday, ' . $date->format('g:ia');
        } else {
            return $date->format('M j Y, g:ia');
        }
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
        $uri = $args['uri'];
        $current = $args['current'];
        $total = $args['total'];
        $limit = $args['limit'];

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
