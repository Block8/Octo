<?php

namespace Octo\Pages\Block;

use b8\Config;
use b8\Form\Element\Button;
use b8\Form\Element\Text as TextElement;
use b8\Form\Element\TextArea;
use b8\Form\FieldSet;
use Octo\Admin\Form;
use Octo\Block;
use Octo\Store;
use Octo\Template;

class SimpleText extends Block
{
    public static function getInfo()
    {
        return [
            'title' => 'Text Content',
            'icon' => 'file-text-o',
            'editor' => ['\Octo\Pages\Block\SimpleText', 'getEditorForm'],
        ];
    }

    public static function getEditorForm($item)
    {
        $form = new Form('block_simpletext_' . $item['id']);
        $form->setId('block_' . $item['id']);

        $fieldset = new FieldSet();
        $form->addField($fieldset);

        $content = TextElement::create('content', '', false);
        $content->setId('block_simpletext_content_' . $item['id']);

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
        $this->view->content = $this->getContent('content', '');
    }
}
