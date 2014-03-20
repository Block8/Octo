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
        return $slug;
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

    /**
     * Get the parent categories' slugs for an article *recursive*
     *
     * @param $category
     * @param string $slug
     * @return string
     */
    protected function getParentSlugs($category, $slug = '')
    {
        $slug .= '/' . StringUtilities::generateSlug($category->getName());
        if ($category->getParent() == null) {
            return $slug;
        } else {
            return $this->getParentSlugs($category->getParent(), $slug);
        }
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
}
