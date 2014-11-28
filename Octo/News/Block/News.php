<?php

namespace Octo\News\Block;

use b8\Config;
use b8\Exception\HttpException\NotFoundException;
use b8\Form\Element\Button;
use b8\Form\Element\Select;
use Octo\Admin\Form;
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
            'title' => static::$articleType,
            'editor' => ['\Octo\News\Block\News', 'getEditorForm'],
            'icon' => 'bullhorn',
        ];
    }

    public static function getEditorForm($item)
    {
        $form = new Form();
        $form->setId('block_' . $item['id']);

        $store = Store::get('Category');
        $rtn = $store->getAllForScope(static::$scope);

        $categories = [];
        foreach ($rtn as $category) {
            $categories[$category->getId()] = $category->getName();
        }

        $categoryField = Select::create('category', 'Category');
        $categoryField->setId('block_articles_category_' . $item['id']);
        $categoryField->setOptions($categories);
        $categoryField->setClass('select2');
        $form->addField($categoryField);

        $perpage = Select::create('perPage', 'Items Per Page');
        $perpage->setId('block_articles_perpage_' . $item['id']);
        $perpage->setClass('select2');

        $perpage->setOptions([
            0 => 'All',
            5 => 5,
            10 => 10,
            15 => 15,
            25 => 25,
            50 => 50,
        ]);
        $form->addField($perpage);

        $saveButton = new Button();
        $saveButton->setValue('Save ' . $item['name']);
        $saveButton->setClass('block-save btn btn-success');
        $form->addField($saveButton);

        if (isset($item['content']) && is_array($item['content'])) {
            $form->setValues($item['content']);
        }

        return $form;
    }

    public function renderNow()
    {
        $this->newsStore = Store::get('Article');
        $this->categoryStore = Store::get('Category');


        if (isset($this->templateParams['context']) && $this->templateParams['context'] == 'mailshot')
        {
            return $this->renderNewsMailshot();
        }

        if (!empty($this->uri)) {
            return $this->renderNewsItem($this->uri);
        } else {
            return $this->renderNewsList();
        }
    }

    /*Page with news to be send by Newsletter*/
    public function renderNewsMailshot()
    {
        $template = 'Block/' . static::$articleType . '/' . 'Mailshot';

        $this->view = Template::getPublicTemplate($template);

        //Last 14 days
        $endDate = new \DateTime();
        $startDate = new \DateTime();
        $startDate = $startDate->sub(new \DateInterval('P14D'));

        $news = $this->newsStore->getNewsforMailshot($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));

        $base = $this->request->getPath();

        if ($base == '/') {
            $base = '';
        }

        $this->view->uri = $this->page->getUri();
        $this->view->articles = $news;
        $this->view->base = $base;

        return $news;
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
        $criteria[] = 'publish_date <= NOW()';
        $params[':scope'] = static::$scope;

        if (!is_null($category)) {
            $subcategories = $this->categoryStore->getSubCategories($category);

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
        $pagination['totalPages'] = $pagination['total'] / $limit;

        if ($pagination['current'] < $pagination['totalPages']) {
            $pagination['next'] = $pagination['current'] + 1;
        }

        if ($pagination['current'] > 0) {
            $pagination['prev'] = $pagination['current'] - 1;
        }

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

        $this->dataStore['meta_title'] = $item->getTitle();
        $this->dataStore['meta_description'] = $item->getSummary();

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
}
