<?php

namespace Octo\News\Block;

use b8\Config;
use b8\Exception\HttpException\NotFoundException;
use Octo\Block;
use Octo\Store;
use Octo\Template;

use Octo\Categories\Store\CategoryStore;

class News extends Block
{
    protected $hasUriExtensions = true;

    /**
     * @var string Type of article to load
     */
    protected static $articleType = 'News';

    /**
     * @var string Scope of articles to filter
     */
    protected static $scope = 'news';

    /**
     * @var \Octo\Articles\Store\ArticleStore
     */
    protected $newsStore;

    /**
     * @var CategoryStore
     */
    protected $categoryStore;

    public static function getInfo()
    {
        return [
            'title' => self::$articleType . ' Archive',
            'editor' => true,
            'js' => ['/assets/backoffice/js/block/' . self::$articleType . '.js']
        ];
    }

    public function renderNow()
    {
        $this->newsStore = Store::get('Article');
        $this->categoryStore = Store::get('Category');

        if (!empty($this->uri)) {
            return $this->renderNewsItem($this->uri);
        } else {
            return $this->renderNewsList();
        }
    }

    /**
     * Get category_id from DB
     * @param $slug
     * @return int category_id
     * @throws \b8\Exception\HttpException\NotFoundException
     */
    protected function getCategoryFromSlug($slug)
    {
        if (is_null($slug)) return $slug;

        $uriParts = explode('/', ltrim($this->uri, '/'));
        $probablyCategory = array_pop($uriParts);

        $isCategory = $this->categoryStore->getByScopeAndSlug(static::$scope, $probablyCategory);

        if (is_null($isCategory->getId()))
        {
            throw new NotFoundException('News/Blog item not found: ' . $slug);
        }

        return $isCategory->getId();
    }


    public function renderNewsList($slug = null)
    {
        if (!empty($this->templateParams['listTemplate'])) {
            $template = 'Block/' . static::$articleType . '/' . $this->templateParams['listTemplate'];
            $this->view = Template::getPublicTemplate($template);
        }

        $limit = 10;

        if (!empty($this->templateParams['count'])) {
            $limit = (int)$this->templateParams['count'];
        }

        if (!empty($this->content['perPage'])) {
            $limit = (int)$this->content['perPage'];
        }

        $category = !empty($this->content['category']) ? $this->content['category'] : $this->getCategoryFromSlug($slug);

        $pagination = [
            'current' => (int)$this->request->getParam('p', 1),
            'limit' => $limit,
            'uri' => $this->page->getUri() . '?',
        ];

        $criteria = [];
        $params = [];

        $criteria[] = 'c.scope = :scope';
        $params[':scope'] = static::$scope;

        if (!is_null($category)) {
            $subcategories = $this->getSubCategories($category);

            if(!empty($subcategories))
            {
                $category .= "," . implode(',', $subcategories);
            }

            $criteria[] = 'category_id IN ('.$category.')';
            //$params[':category_id'] = $categoryIds;
        }

        $query = $this->newsStore->query($pagination['current'], $limit, ['publish_date', 'DESC'], $criteria, $params);
        $query->join('category', 'c', 'c.id = article.category_id');

        $pagination['total'] = $query->getCount();

        $query->execute();
        $news = $query->fetchAll();

        $base = $this->request->getPath();

        if ($base == '/') {
            $base = '';
        }

        $this->view->uri = $this->page->getUri();
        $this->view->articles = $news;
        $this->view->base = $base;
        $this->view->pagination = $pagination;

        return $news;
    }

    /**
     * Get distinct sub categories
     * @param $category_id
     * @return array
     */
    protected function getSubCategories($category_id)
    {
        $allChildren = array();

        $subChildren = $this->categoryStore->getAllForParent($category_id);

        foreach ($subChildren as $child)
        {
            $childId = $child->getId();
            $allChildren[$childId] = $childId;
            $arr = $this->getSubCategories($childId);
            if(count($arr)>0)
            {
                $allChildren = array_merge($this->getSubCategories($childId), $allChildren);
            }
        }

        return $allChildren;
    }




    public function renderNewsItem($slug)
    {
        $item = $this->newsStore->getBySlug($slug);

        if (!$item) {
            return $this->renderNewsList($slug); //That might be a category
        }

        if (!isset($this->dataStore['breadcrumb']) || !is_array($this->dataStore['breadcrumb'])) {
            $this->dataStore['breadcrumb'] = [];
        }

        foreach ($this->dataStore['breadcrumb'] as &$breadcrumb) {
            $breadcrumb['active'] = false;
        }

        $this->dataStore['breadcrumb'][] = [
            'uri' => $item->getFullUrl(),
            'title' => $item->getTitle(),
            'active' => true,
        ];

        $content = $item->getContentItem()->getContent();
        $content = json_decode($content, true);
        $content = $content['content'];

        if (!empty($this->templateParams['itemTemplate'])) {
            $template = 'Block/' . static::$articleType . '/' . $this->templateParams['itemTemplate'];
            $this->view = Template::getPublicTemplate($template);
        } else {
            $this->view = Template::getPublicTemplate('Block/' . static::$articleType . '/Item');
        }

        $this->view->item = $item;
        $this->view->content = $content;
    }
}
