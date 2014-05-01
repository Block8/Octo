<?php

namespace Octo\Categories\Block;

use Octo\Block;
use Octo\Store;
use Octo\Store\CategoryStore;

class Category extends Block
{
    /**
     * @var \Octo\Store\CategoryStore
     */
    protected $categoryStore;

    public static function getInfo()
    {
        return ['title' => 'Category List', 'editor' => false, 'js' => []];
    }

    public function renderNow()
    {
        $scope = $this->templateParams['scope'];

        $requiresPresence = null;
        if (isset($this->templateParams['requiresPresence'])) {
            $presence = $this->templateParams['requiresPresence'];
        }

        $this->categoryStore = Store::get('Category');
        $this->view->categories = $this->categoryStore->getAllForScope($scope, 'position ASC, name ASC', $presence);
    }
}
