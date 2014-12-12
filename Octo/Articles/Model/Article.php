<?php

/**
 * Article model for table: article
 */

namespace Octo\Articles\Model;

use b8\Config;
use Octo;
use Octo\Store;
use Octo\Categories\Store\CategoryStore;
use Octo\Utilities\StringUtilities;
use Octo\System\Model\Setting;

/**
 * Article Model
 * @uses Octo\Articles\Model\Base\ArticleBaseBase
 */
class Article extends Octo\Model
{
    use Base\ArticleBase;

    /**
     * @var CategoryStore
     */
    protected $categoryStore;

    public function __construct($initialData = [])
    {
        parent::__construct($initialData);

        $this->getters['full_url'] = 'getFullUrl';
    }

    /**
     * @return string Slug for the article based on category
     */
    public function generateSlug()
    {
        $slug = StringUtilities::generateSlug($this->getTitle());

        $this->categoryStore = Store::get('Category');
        $category = $this->categoryStore->getById($this->getCategoryId());

        $slug = $this->getParentSlugs($category) . '/' . $slug;

        //check if it is unique
        $uniqueId = '';
        while (!$this->isUniqueSlug($slug . $uniqueId)) {
            $uniqueId = '-' . substr(sha1(uniqid('', true)), 0, 5);
        }

        return $slug . $uniqueId;
    }

    /**
     * @return bool check if Slug for the article based on category is unique
     */
    public function isUniqueSlug($slug)
    {
        $articleStore = Store::get('Article');

        try {
            $rtn = $articleStore->getBySlug($slug);
        } catch (StoreException $se) {
            $rtn = false;

        }

        return !$rtn;
    }

    /**
     * Get the parent categories' slugs for an article *recursive*
     *
     * @param $category
     * @param string $separator
     * @param array $visited
     * @return string
     */
    protected function getParentSlugs($category, $separator = '/', $visited = array())
    {
        $slug = '';
        $parent = $category->getParent();

        if (($parent != null) && !in_array($parent, $visited)) {
            $visited[] = $parent;
            $slug .= $this->getParentSlugs($parent, $separator = '/', $visited = array());
        }

        $slug .= $separator . StringUtilities::generateSlug($category->getName());

        return $slug;
    }

    /**
     * Get the parent categories' slugs for an article in reverse order *recursive*
     *
     * @param $category
     * @param string $slug
     * @return string
     */
    protected function getParentSlugsReverse($category, $slug = '')
    {
        $slug .= '/' . StringUtilities::generateSlug($category->getName());
        if ($category->getParent() == null) {
            return $slug;
        } else {
            return $this->getParentSlugs($category->getParent(), $slug);
        }
    }

    /**
     * @return string Slug for the article based on category
     */
    public function generateSummary()
    {
        $words = Setting::get('news', 'summary_word_limit');

        $summary = json_decode($this->getContentItem()->getContent())->content;
        $summary = StringUtilities::firstWords(strip_tags($summary), $words, '...');
        return $summary;
    }


    public function getFullUrl()
    {
        $config = Config::getInstance();

        $baseUrl = $config->get('site.url');
        $scopeSlugs = $config->get('site.scope_url', []);
        $scope = $this->getCategory()->getScope();

        $scopeUrl = array_key_exists($scope, $scopeSlugs) ? $scopeSlugs[$scope] : '/' . $scope;

        return $baseUrl . $scopeUrl . $this->getSlug();
    }

    /**
     * Get IndexableContent - Returns a string containing any keywords we want to
     * be considered by the search index as relevant.
     *
     * @return string
     */
    public function getIndexableContent()
    {
        $content = "";
        $content .= $this->getTitle() . " ";
        $content .= $this->getSummary() . " ";
        $content .= json_decode($this->getContentItem()->getContent())->content;
        return $content;
    }
}
