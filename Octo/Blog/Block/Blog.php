<?php

namespace Octo\Blog\Block;

use b8\Config;
use Octo\News\Block\News;

class Blog extends News
{
    /**
     * @var string Type of article to load
     */
    protected static $articleType = 'Blog';

    /**
     * @var string Scope of articles to filter
     */
    protected static $scope = 'blog';

    public static function getInfo()
    {
        $info = parent::getInfo();
        $info['icon'] = 'list-alt';
        $info['editor'] = ['\Octo\Blog\Block\Blog', 'getEditorForm'];

        return $info;
    }
}
