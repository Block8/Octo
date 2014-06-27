<?php

namespace Octo\Pages\Block;

use b8\Database;
use b8\Form\Element\Button;
use Octo\Admin\Form;
use Octo\Block;
use Octo\Pages\Model\Page;
use Octo\Store;
use Octo\Template;

class SectionCollection extends Block
{
    /**
     * @var \Octo\Pages\Store\PageStore
     */
    protected $pageStore;

    public static function getInfo()
    {
        return [
            'title' => 'Section Collections',
            'icon' => 'sitemap',
            'editor' => ['\Octo\Pages\Block\SectionCollection', 'getEditorForm']
        ];
    }

    public static function getEditorForm($item)
    {
        $form = new Form();
        $form->setId('block_' . $item['id']);

        $formSelect = \b8\Form\Element\Text::create('parent', 'Parent Page');
        $formSelect->setId('block_sectioncollection_parent_' . $item['id']);
        $formSelect->setClass('octo-page-picker');
        $form->addField($formSelect);

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
        $this->pageStore = Store::get('Page');
        $this->limit = 25;

        if (array_key_exists('limit', $this->templateParams)) {
            $this->limit = $this->templateParams['limit'] ? $this->templateParams['limit'] : $this->limit;
        }

        $this->parent = $this->page;

        if (array_key_exists('parent', $this->content)) {
            $this->parent = $this->pageStore->getById($this->content['parent']);
        }

        $this->view->pages = $this->getChildren($this->parent);
    }

    protected function getChildren(Page $page)
    {
        $options = ['order' => [['position', 'ASC']], 'limit' => $this->limit];
        $children = $this->pageStore->getByParentId($page->getId(), $options);

        if (count($children)) {
            return $children;
        }

        return null;
    }
}
