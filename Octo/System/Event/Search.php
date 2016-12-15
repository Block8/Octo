<?php
namespace Octo\System\Event;

use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Model;
use Octo\Store;
use Octo\System\Searchable;

class Search extends Listener
{
    /**
     * @var \Octo\System\Store\SearchIndexStore
     */
    protected $searchStore;

    public function registerListeners(Manager $manager)
    {

        $manager->registerListener('Octo.Model.Updated', [$this, 'modelUpdated']);
        $manager->registerListener('Octo.Model.Deleted', [$this, 'modelDeleted']);
    }

    public function modelUpdated(Model $model)
    {
        if ($model instanceof Searchable) {
            $this->searchStore = Store::get('SearchIndex');

            $this->searchStore->updateSearchIndex(
                $model->getModelName(),
                $model->getSearchId(),
                $model->getSearchContent()
            );
        }
    }
    
    public function modelDeleted(Model $model)
    {
        if ($model instanceof Searchable) {
            $this->searchStore = Store::get('SearchIndex');
            $this->searchStore->removeFromIndex($model->getModelName(), $model->getSearchId());
        }
    }
}
