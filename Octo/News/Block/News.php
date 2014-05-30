<?php

namespace Octo\News\Block;

use b8\Config;
use b8\Exception\HttpException\NotFoundException;
use Octo\Block;
use Octo\Store;
use Octo\Template;

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

        if (!empty($this->uri)) {
            return $this->renderNewsItem($this->uri);
        } else {
            return $this->renderNewsList();
        }
    }

    public function renderNewsList()
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

        $category = !empty($this->content['category']) ? $this->content['category'] : null;

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
            $criteria[] = 'category_id = :category_id';
            $params[':category_id'] = $category;
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

        $this->view->articles = $news;
        $this->view->base = $base;
        $this->view->pagination = $pagination;

        return $news;
    }

    public function renderNewsItem($slug)
    {
        $item = $this->newsStore->getBySlug($slug);

        if (!$item) {
            throw new NotFoundException('News item not found: ' . $slug);
        }

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
