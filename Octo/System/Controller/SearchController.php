<?php

namespace Octo\System\Controller;

use Octo\Controller;
use Octo\Event;
use Octo\Store;
use Octo\Template;

class SearchController extends Controller
{
    /**
     * @var \Octo\System\Store\SearchIndexStore
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
        $results = array_filter($results);

        $view = new Template('Search/results');
        $view->query = $query;
        $view->results = $results;
        $view->page = [
            'title' => 'Search results for "'.$query.'" on ' . $this->config->get('site.name')
        ];

        $output = $view->render();

        return $output;
    }

    public function render($item)
    {
        if (!is_null($item)) {
            $parts = explode('\\', get_class($item));
            $class = array_pop($parts);

            $view = new Template('Search/Type/' . $class);
            $view->result = $item;

            return $view->render();
        }
    }
}
