<?php

namespace Octo\Block;

use b8\Config;
use Octo\Block;
use Octo\Store;
use Octo\Template;

class News extends Block
{
    /**
     * @var string Type of article to load
     */
    protected static $articleType = 'News';

    /**
     * @var string Scope of articles to filter
     */
    protected static $scope = 'news';

    public static function getInfo()
    {
        $config = Config::getInstance();
        return [
            'title' => self::$articleType . ' Archive',
            'editor' => true,
            'js' => ['/assets/backoffice/js/block/' . self::$articleType . '.js']
        ];
    }

    public function renderNow()
    {
        if (count($this->args)) {
            $slug = '/' . implode('/', $this->args);
            return $this->renderNewsItem($slug);
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

        $limit = 100;

        if (!empty($this->templateParams['count'])) {
            $limit = $this->templateParams['count'];
        }

        if (!empty($this->content['perPage'])) {
            $limit = $this->content['perPage'];
        }

        $category = !empty($this->content['category']) ? $this->content['category'] : null;

        $news = Store::get('Article')->getRecent($category, $limit, static::$scope);
        $base = $this->request->getPath();

        if ($base == '/') {
            $base = '';
        }

        $articleType = static::$articleType;
        $this->view->articles = $news;
        $this->view->base = $base;

        return $news;
    }

    public function renderNewsItem($slug)
    {
        $item = Store::get('Article')->getBySlug($slug);

        if (!$item) {
            $this->response->setResponseCode(404);
            return false;
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
