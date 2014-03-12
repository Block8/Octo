<?php

namespace Octo\Block;

use b8\Config;
use Octo\Template;
use Octo\Block;
use Octo\Model\File;

class Text extends Block
{
    public static function getInfo()
    {
        $config = Config::getInstance();
        return ['title' => 'Text', 'editor' => true, 'js' => ['/assets/backoffice/js/block/text.js']];
    }

    public function renderNow()
    {
        if (array_key_exists('content', $this->content)) {
            $content = $this->content['content'];

            // Replace file blocks
            $content = preg_replace_callback('/\<img id\=\"([a-zA-Z0-9]{32})".*>/', [$this, 'replaceFile'], $content);
            $this->view->content = $content;
        }
    }

    public function replaceFile($matches)
    {
        if (isset($matches[1])) {
            $file = File::getById($matches[1]);
            if ($file) {
                $template = Template::getPublicTemplate('Block/Text/File');
                $template->file = $file;
                return $template->render();
            }
        }
    }
}
