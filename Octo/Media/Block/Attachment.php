<?php

namespace Octo\Media\Block;

use b8\Config;
use b8\Form\Element\Button;
use Octo\Admin\Form;
use Octo\Block;
use Octo\Store;

class Attachment extends Block
{
    public static function getInfo()
    {
        return [
            'title' => 'Files',
            'icon' => 'picture-o',
            'editor' => ['\Octo\Media\Block\Attachment', 'getEditorForm'],
        ];
    }

    public static function getEditorForm($item)
    {
        $form = new Form('block_file_' . $item['id']);
        $form->setId('block_' . $item['id']);

        $image = \b8\Form\Element\Text::create('file', 'File', false);
        $image->setId('block_file_file' . $item['id']);
        $image->setClass('octo-file-picker');

        $saveButton = new Button();
        $saveButton->setValue('Save ' . $item['name']);
        $saveButton->setClass('block-save btn btn-success');
        $form->addField($image);
        $form->addField($saveButton);

        if (isset($item['content']) && is_array($item['content'])) {
            $form->setValues($item['content']);
        }

        return $form;
    }

    public function renderNow()
    {
        $file = $this->getContent('file', '');

        $file = Store::get('File')->getByPrimaryKey($file);

        if (isset($file)) {
            $this->view->file = $file;
            return;
        }
    }
}
