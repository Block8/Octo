<?php
namespace Octo\System\Event;

use DateTime;
use b8\Config;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Member;
use Octo\System\Model\Setting;
use Octo\Template;
use Octo\Admin\Template as AdminTemplate;

class TemplateFunctions extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('Template.Loaded', array($this, 'templateLoaded'));
        $manager->registerListener('Template.Loaded.Public', array($this, 'publicTemplateLoaded'));
        $manager->registerListener('Template.Loaded.Admin', array($this, 'adminTemplateLoaded'));

        $manager->registerListener('TemplateInit', array($this, 'templateInit'));

        // Legacy admin templates:
        $manager->registerListener('AdminTemplateLoaded', array($this, 'adminTemplateFunctions'));
    }

    public function templateInit(array &$functions)
    {
        $functions['replace'] = function ($source, $find, $replace) {
            return str_replace($find, $replace, $source);
        };

        $functions['regexReplace'] = function ($source, $find, $replace) {
            return preg_replace('/'.$find.'/', $replace, $source);
        };

        $functions['wordLimit'] = function ($string, $wordLimit) {
            if (str_word_count($string, 0) > $wordLimit) {
                $words = str_word_count($string, 2);
                $pos = array_keys($words);
                $string = trim(substr($string, 0, $pos[$wordLimit])) . '...';
            }

            return $string;
        };

        $functions['canAccess'] = function ($uri) {
            return $_SESSION['user']->canAccess($uri);
        };

        $functions['md5'] = function ($string) {
            return md5($string);
        };
    }

    public function templateLoaded(Template &$template)
    {
        $config = Config::getInstance();
        $template->set('now', new DateTime());
        $template->set('config', $config);
        $template->set('adminUri', $config->get('site.full_admin_url'));

        if (isset($_SESSION) && is_array($_SESSION)) {
            $template->set('session', $_SESSION);
        }

        if (!empty($_SESSION['user'])) {
            $template->set('user', $_SESSION['user']);
        }

        try {
            $template->set('settings', Setting::getAllAsArray());
        } catch (\Exception $ex) {}
    }

    public function publicTemplateLoaded(Template &$template)
    {
        $template->member = Member::getInstance();
    }

    public function adminTemplateLoaded(Template &$template)
    {

    }

    public function adminTemplateFunctions(AdminTemplate &$template)
    {
        $template->addFunction('canAccess', function ($args) {
            return $_SESSION['user']->canAccess($args['uri']);
        });

        $config = Config::getInstance();
        $template->addFunction('date_format', function ($args) {
            $date = $args['date'];

            $format = null;

            if (isset($args['format'])) {
                $format = $args['format'];
            }

            if (!($date instanceof \DateTime)) {
                return '';
            }

            switch ($format) {
                case 'friendly':
                    return $this->friendlyDate($date);

                case 'short':
                    $format = 'd/m/Y g:ia';
                    break;

                case 'long_date':
                    $format = 'jS F Y';
                    break;

                case 'date':
                    $format = 'd/m/Y';
                    break;

                case 'time':
                    $format = 'g:ia';
                    break;

                case 'system_date':
                    $format = 'Y-m-d';
                    break;

                case 'system_datetime':
                    $format = 'Y-m-d H:i:s';
                    break;

                default:
                    $format = 'jS F Y, g:ia';
                    break;
            }

            return $date->format($format);
        });

        $template->set('date_now', new \DateTime());
        $template->set('adminUri', $config->get('site.full_admin_url'));
        $template->set('config', $config);
        $template->set('settings', Setting::getAllAsArray());
        $template->set('GET', $_GET);

        $template->addFunction('pagination', array($this, 'handlePagination'));
        $template->addFunction('var_dump', function ($args) {
            ob_start();
            var_dump($args['variable']);
            $rtn = ob_get_contents();
            ob_end_clean();

            return $rtn;
        });

        $template->addFunction('is_mobile', function () {
            if (class_exists('\Mobile_Detect')) {
                $mobileDetect = new \Mobile_Detect();
                return $mobileDetect->isMobile();
            }

            return false;
        });

        $theme = 'blue';

        if (!is_null($config->get('site.admin_theme', null))) {
            $theme = $config->get('site.admin_theme');
        }

        $template->set('theme', $theme);
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
    public function handlePagination($args)
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
