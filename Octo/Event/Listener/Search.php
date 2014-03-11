<?php
namespace Octo\Event\Listener;

use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Model;
use Octo\Store;

class Search extends Listener
{
    /**
     * @var \Octo\Store\SearchIndexStore
     */
    protected $searchStore;

    public function __construct()
    {
        $this->searchStore = Store::get('SearchIndex');
    }

    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('ContentPublished', array($this, 'addToSearchIndex'));
    }

    public function addToSearchIndex(&$data)
    {
        $class = get_class($data['model']);
        $this->searchStore->updateSearchIndex($class, $data['content_id'], $data['content']);

        return true;
    }
}
