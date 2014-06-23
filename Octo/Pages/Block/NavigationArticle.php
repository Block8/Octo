<?php

namespace Octo\Pages\Block;

use b8\Database;
use Octo\Block;
use Octo\Categories\Model\Category;
use Octo\Pages\Model\Page;
use Octo\Store;
use Octo\Template;

use Octo\Categories\Store\CategoryStore;
use Octo\Pages\Model\Article;

class NavigationArticle extends Block
{
    /**
     * @var \Octo\Pages\Store\PageStore
     */
    protected $pageStore;

    /**
     * @var \Octo\Articles\Store\ArticleStore
     */
    protected $articleStore;

    /**
     * @var CategoryStore
     */
    protected $categoryStore;

    protected $scope;
    /**
     * @var \Octo\Categories\Model\Category
     */
    protected $category;

    public static function getInfo()
    {
        return ['title' => 'NavigationArticle'];
    }

    public function init()
    {
        $this->pageStore     = Store::get('Page');
        $this->articleStore  = Store::get('Article');
        $this->categoryStore = Store::get('Category');
    }

    public function renderNow()
    {
        if (array_key_exists('template', $this->templateParams)) {
            $this->view = Template::getPublicTemplate('Block/Navigation/' . $this->templateParams['template']);
        }

        $topMenu = $this->getTopMenu();

        $this->category = $this->getScopeAndCategoryIdFromUri();

        //Top categories for scope
        $categoriesMenu = $this->getTopTreeForScope();
        if (!is_null($this->category))
        {
            //Add Subcategory
            $subCategoriesMenu =  $this->getCategoryMenu();
            $categoriesMenu = $this->putCategoriesToMenu($categoriesMenu, $subCategoriesMenu, $this->page->getUri() . "/" . $subCategoriesMenu['slug']);
        }
        $topMenu = $this->putCategoriesToMenu($topMenu, $categoriesMenu, $this->page->getUri());

        $this->view->items = $topMenu;
    }

    protected function getTopTreeForScope()
    {
        $parents = $this->categoryStore->getNamesAndScopeForParents($this->scope);

        for ($i=0; $i<count($parents); $i++)
        {
            $parents[$i]['uri'] = $this->page->getUri() . "/" . $parents[$i]['slug'];
        }
        return $parents;
    }

    protected function putCategoriesToMenu($topMenu, $categoriesMenu, $keyToPut)
    {
        $position = -1;
        foreach ($topMenu as $key => $menuLeaf)
        {
            if ($menuLeaf['uri'] == $keyToPut)
            {
                $position = $key;
            }
        }

        if($this->page->getUri() == $keyToPut)
        {
            $topMenu[$position]['children'] = $categoriesMenu;
            $topMenu[$position]['current'] = true;
        } else {

            if(isset($categoriesMenu['children'])){
                $topMenu[$position]['children'] = $categoriesMenu['children'];
                $topMenu[$position]['active'] = true;
                $topMenu[$position]['current'] = true;
            } else {
                $topMenu[$position]['current'] = true;

            }

        }

        return $topMenu;
    }

    protected function getSlug()
    {
        $s = explode("/", $this->request->getPath());
        unset($s[0]); //empty
        unset($s[1]);
        unset($s[2]);
        $slug = "/" . implode("/", $s);

        return $slug;
    }



    /**
     * Get category_id from DB and set scope
     * @return Category Model
     */
    protected function getScopeAndCategoryIdFromUri()
    {
        $uriParts = $this->request->getPathParts();

        if ($uriParts[1] == 'blogs')
        {
            $this->scope = "blog"; //Hack for scope blog(s)
        } else {
            $this->scope = $uriParts[1];
        }

        if (count($uriParts) == 2) //Root category - blog/news/case-studies
        {
           return null;
        } elseif (count($uriParts) == 3) {
            //just category
            $probablyCategory = array_pop($uriParts);
            $category = $this->categoryStore->getByScopeAndSlug($this->scope, $probablyCategory);
            return $category;
        } else {
            //maybe article or subcategory, we do not know here
            $slug = $this->getSlug();

            $probablyArticle = $this->articleStore->getBySlug($slug);
            if (!$probablyArticle) {
                $slug = array_pop($uriParts);
                $category = $this->categoryStore->getByScopeAndSlug($this->scope, $slug);
                return $category;
            }
            return $probablyArticle->getCategory();
        }
    }

    protected function getCategoryMenu()
    {
        $this->ancestors = $this->getCategoryAncestors();

        $this->allChildren = false;
        $start = count($this->ancestors);
        $maxDepth = 1;

        if (array_key_exists('allChildren', $this->templateParams)) {
            $this->allChildren = $this->templateParams['allChildren'] ? true : false;
        }

        if (array_key_exists('depth', $this->templateParams)) {
            $maxDepth = (int)$this->templateParams['depth'];
        }

        if (isset($this->ancestors[$start -1])) {
            $items = $this->buildCategoryTree($this->ancestors[$start -1], 0, $maxDepth, $this->page->getUri());

            return $items;
        }

    }

    protected function getCategoryAncestors()
    {
        $rtn = [$this->category];
        $category = $this->category;

        while ($category->getParentId()) {
            $category = $category->getParent();
            $rtn[] = $category;
        }

        return $rtn;
    }

    protected function isCategoryAncestor(Category $page)
    {
        foreach ($this->ancestors as $ancestor) {
            if ($page->getId() == $ancestor->getId()) {
                return true;
            }
        }

        return false;
    }

    protected function getCategoryChildren(Category $category)
    {
        $children = $this->categoryStore->getAllForParent($category->getId());

        if (count($children)) {
            return $children;
        }

        return null;
    }

    protected function buildCategoryTree(Category $item, $depth, $maxDepth, $uri)
    {
        $isAncestor = $this->isCategoryAncestor($item);

        $rtn = [];
        $rtn['uri'] = $uri . "/". $item->getSlug();
        $rtn['slug'] = $item->getSlug();
        $rtn['title'] = $item->getName();
        $rtn['active'] = $isAncestor;

        if ($item->getId() == $this->category->getId()) {
            $rtn['current'] = true;
        }

        if ($depth == $maxDepth || (!$this->allChildren && !$isAncestor)) {
            return $rtn;
        }

        $children = $this->getCategoryChildren($item);

        if (!is_null($children)) {
            $rtn['children'] = [];

            foreach ($children as $child) {
                $rtn['children'][] = $this->buildCategoryTree($child, $depth + 1, $maxDepth, $uri . "/". $item->getSlug());
            }
        }

        return $rtn;
    }

    protected function getTopMenu()
    {
        $this->ancestors = $this->getAncestors();

        $this->allChildren = false;
        $start = count($this->ancestors);
        $maxDepth = 1;

        if (array_key_exists('allChildren', $this->templateParams)) {
            $this->allChildren = $this->templateParams['allChildren'] ? true : false;
        }

        if (array_key_exists('start', $this->templateParams)) {
            $start = (int)$this->templateParams['start'];
        }

        if (array_key_exists('depth', $this->templateParams)) {
            $maxDepth = (int)$this->templateParams['depth'];
        }

        if (isset($this->ancestors[$start - 1])) {
            $items = $this->buildTree($this->ancestors[$start - 1], 0, $maxDepth);

            if (isset($items['children'])) {
                return $items['children'];
            } else {
                return [];
            }

        }
    }

    protected function getAncestors()
    {
        $rtn = [$this->page];
        $page = $this->page;

        while ($page->getParentId()) {
            $page = $page->getParent();
            $rtn[] = $page;
        }

        return array_reverse($rtn);
    }

    protected function getChildren(Page $page)
    {
        $options = [
            'order' => [
                ['position', 'ASC'],
            ]
        ];

        $children = $this->pageStore->getByParentId($page->getId(), $options);

        if (count($children)) {
            return $children;
        }

        return null;
    }

    protected function buildTree(Page $item, $depth, $maxDepth)
    {
        $isAncestor = $this->isAncestor($item);

        $rtn = [];
        $rtn['uri'] = $item->getUri();
        $rtn['title'] = $item->getCurrentVersion()->getShortTitle();
        $rtn['active'] = $isAncestor;

        if ($item->getId() == $this->page->getId()) {
            $rtn['current'] = true;
        }

        if ($depth == $maxDepth || (!$this->allChildren && !$isAncestor)) {
            return $rtn;
        }

        $children = $this->getChildren($item);

        if (!is_null($children)) {
            $rtn['children'] = [];

            foreach ($children as $child) {
                $rtn['children'][] = $this->buildTree($child, $depth + 1, $maxDepth);
            }
        }

        return $rtn;
    }

    protected function isAncestor(Page $page)
    {
        foreach ($this->ancestors as $ancestor) {
            if ($page->getId() == $ancestor->getId()) {
                return true;
            }
        }

        return false;
    }

}
