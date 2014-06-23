<?php

namespace Octo\System\Admin\Controller;

use b8\Config;
use Octo\Admin\Controller;
use Octo\Admin\Template;
use Octo\Event;
use Octo\Store;
use Octo\System\Model\Setting;

class SearchController extends Controller
{
    /**
     * @var \Octo\System\Store\SearchIndexStore
     */
    protected $searchStore;

    public function index()
    {
        $this->searchStore = Store::get('SearchIndex');

        $query = $this->getParam('q', '');
        $this->setTitle($query, 'Search ' . $this->config->get('site.name'));
        $this->addBreadcrumb('Search', '/search?q=' . $query);

        $results = $this->searchStore->search($query);
        $results = array_map([$this, 'render'], $results);

        $this->view->query = $query;
        $this->view->results = $results;
    }

    public function render($item)
    {
        if (!is_null($item)) {
            $parts = explode('\\', get_class($item));
            $class = array_pop($parts);

            $view = Template::getAdminTemplate('Search/Type/' . $class);
            $view->result = $item;

            return $view->render();
        }
    }
}
