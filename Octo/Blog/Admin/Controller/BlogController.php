<?php
namespace Octo\Blog\Admin\Controller;

use b8\Form;
use Octo\Store;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Articles\Model\Article;
use Octo\System\Model\ContentItem;

class BlogController extends \Octo\News\Admin\Controller\NewsController
{
    /**
     * Return the menu nodes required for this controller
     *
     * @return void
     *
     */
    public static function registerMenus(Menu $menu)
    {
        $news = $menu->addRoot('Blog', '#')->setIcon('list-alt');
        $news->addChild(new Menu\Item('Add Entry', '/blog/add'));
        $manage = new Menu\Item('Manage Entries', '/blog');
        $manage->addChild(new Menu\Item('Edit Entry', '/blog/edit', true));
        $news->addChild($manage);
        $categories = new Menu\Item('Manage Categories', '/categories/manage/blog');
        $news->addChild($categories);
    }

    /**
     * Setup initial menu
     *
     * @return void
     *
     */
    public function init()
    {
        $this->userStore = Store::get('User');
        $this->categoryStore = Store::get('Category');
        $this->contentItemStore = Store::get('ContentItem');
        $this->articleStore = Store::get('Article');

        $this->scope = 'blog';
        $this->articleType = 'Entry';
        $this->lowerArticleType = 'entry';
        $this->articleTypeMulti = 'Entries';

        $this->setTitle('Blog');
        $this->addBreadcrumb('Blog', '/blog');
    }
}
