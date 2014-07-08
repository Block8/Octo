<?php

namespace Octo\Pages\Block;

use b8\Config;
use Octo\Block;
use b8\Form\Element\Button;
use b8\Form\Element\TextArea;
use Octo\Admin\Form;

class Map extends Block
{
    public static function getInfo()
    {
        return [
            'title' => 'Maps',
            'icon' => 'map-marker',
            'editor' => ['\Octo\Pages\Block\Map', 'getEditorForm']
        ];
    }

    public static function getEditorForm($item)
    {
        $form = new Form();
        $form->setId('block_' . $item['id']);

        $url = TextArea::create('code', 'Paste your Google Maps embed code here:');
        $url->setId('block_map_code_' . $item['id']);
        $form->addField($url);

        $saveButton = new Button();
        $saveButton->setValue('Save ' . $item['name']);
        $saveButton->setClass('block-save btn btn-success');
        $form->addField($saveButton);

        if (isset($item['content']) && is_array($item['content'])) {
            $form->setValues($item['content']);
        }

        return $form;
    }

    public function renderNow()
    {
        if (array_key_exists('code', $this->content)) {
            $code = $this->content['code'];
            $matches = [];

            if (preg_match('/src="([^"]+)"/', $code, $matches)) {
                $this->view->link = $matches[1];
            }
        }
    }
}
