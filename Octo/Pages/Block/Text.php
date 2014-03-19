<?php

namespace Octo\Pages\Block;

use b8\Config;
use Octo\Block;
use Octo\Store;
use Octo\Template;

class Text extends Block
{
    public static function getInfo()
    {
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
            $file = Store::get('File')->getById($matches[1]);
            if ($file) {
                $template = Template::getPublicTemplate('Block/Text/File');
                $template->file = $file;
                return $template->render();
            }
        }
    }
}
