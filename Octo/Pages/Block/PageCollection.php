<?php

namespace Octo\Pages\Block;

use b8\Database;
use b8\Form\Element\Button;
use Octo\Admin\Form;
use Octo\Admin\Template as AdminTemplate;
use Octo\Block;
use Octo\Page\Model\Page;
use Octo\Store;
use Octo\Template;

class PageCollection extends Block
{
    /**
     * @var \Octo\Page\Store\PageStore
     */
    protected $pageStore;

    public static function getInfo()
    {
        return [
            'title' => 'Page Collections',
            'icon' => 'sitemap',
            'editor' => ['\Octo\Pages\Block\PageCollection', 'getEditorForm']
        ];
    }

    public static function getEditorForm($item)
    {
        $form = new Form();
        $form->setId('block_' . $item['id']);

        $formSelect = \b8\Form\Element\Text::create('page', 'Add a Page');
        $formSelect->setId('block_pagecollection_parent_' . $item['id']);
        $formSelect->setClass('octo-page-picker skip-autosave');
        $form->addField($formSelect);

        $saveButton = new Button();
        $saveButton->setValue('Save ' . $item['name']);
        $saveButton->setClass('block-save btn btn-success');
        $form->addField($saveButton);

        if (empty($item['content'])) {
            $item['content'] = [];
        }

        $template = AdminTemplate::getAdminTemplate('BlockEditor/PageCollection');
        $template->form = $form;
        $template->blockContent = json_encode($item['content']);
        $template->blockId = $item['id'];

        return $template->render();
    }

    public function init()
    {
        $this->pageStore = Store::get('Page');
    }

    public function renderNow()
    {
        $this->limit = 25;

        if (array_key_exists('limit', $this->templateParams)) {
            $this->limit = $this->templateParams['limit'] ? $this->templateParams['limit'] : $this->limit;
        }

        if (array_key_exists('pages', $this->content)) {
            $pages = [];
            $rendered = 0;
            foreach ($this->content['pages'] as $pageId) {
                if (++$rendered >= $this->limit) {
                    break;
                }

                $page = $this->pageStore->getById($pageId);

                if (!is_null($page)) {
                    $pages[] = $page;
                }
            }

            $this->view->pages = $pages;
        }
    }
}
