<?php

namespace Octo\System\Controller;

use Octo\Block;
use Octo\BlockManager;
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

        $view = Template::getPublicTemplate('Search/results');
        $view->query = $query;
        $view->results = $results;

        $dataStore = [
            'breadcrumb' => [
                ['uri' => '/search', 'title' => 'Search', 'active' => false],
                ['uri' => '/search?q=' . $query, 'title' => $query, 'active' => true]
            ]
        ];

        $blockManager = new BlockManager();
        $blockManager->setDataStore($dataStore);
        $blockManager->setRequest($this->request);
        $blockManager->setResponse($this->response);
        $blockManager->attachToTemplate($view);

        $output = $view->render();

        $data = [
            'page' => null,
            'version' => null,
            'output' => &$output,
            'datastore' => $blockManager->getDataStore(),
        ];

        Event::trigger('PageLoaded', $data);

        return $output;
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
