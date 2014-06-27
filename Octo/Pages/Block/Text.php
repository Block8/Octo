<?php

namespace Octo\Pages\Block;

use b8\Config;
use b8\Form\Element\Button;
use b8\Form\Element\TextArea;
use b8\Form\FieldSet;
use Octo\Admin\Form;
use Octo\Block;
use Octo\Store;
use Octo\Template;

class Text extends Block
{
    public static function getInfo()
    {
        return [
            'title' => 'Text Content',
            'icon' => 'file-text-o',
            'editor' => ['\Octo\Pages\Block\Text', 'getEditorForm'],
        ];
    }

    public static function getEditorForm($item)
    {
        $form = new Form('block_text_' . $item['id']);
        $form->setId('block_' . $item['id']);
        $fieldset = new FieldSet();
        $form->addField($fieldset);

        $content = TextArea::create('content', 'Content', false);
        $content->setId('block_text_content_' . $item['id']);
        $content->setClass('ckeditor advanced');

        if (isset($item['content']['content'])) {
            $content->setValue($item['content']['content']);
        }

        $saveButton = new Button();
        $saveButton->setValue('Save ' . $item['name']);
        $saveButton->setClass('block-save btn btn-success');
        $fieldset->addField($content);
        $fieldset->addField($saveButton);

        return $form;
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
