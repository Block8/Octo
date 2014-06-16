<?php

namespace Octo\System\Controller;

use Octo\Block;
use Octo\Controller;
use Octo\Store;
use Octo\Template;

class SearchController extends Controller
{
    /**
     * @var \Octo\System\Store\SearchStore
     */
    protected $searchStore;

    public function init()
    {
        $this->searchStore = Store::get('SearchIndex');
    }

    public function index()
    {
        $query = $this->getParam('q', '');
        $results = $this->searchStore->search($query);
        $results = array_map([$this, 'render'], $results);

        $view = Template::getPublicTemplate('Search/results');
        $view->query = $query;
        $view->results = $results;

        return $view->render();
    }

    public function render($item)
    {
        if (!is_null($item)) {
            $parts = explode('\\', get_class($item));
            $class = array_pop($parts);

            $view = Template::getPublicTemplate('Search/Type/' . $class);
            $view->result = $item;

            return $view->render();
        }
    }
}
