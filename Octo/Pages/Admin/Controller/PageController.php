<?php

namespace Octo\Pages\Admin\Controller;

use b8\Form;
use b8\Http\Response\RedirectResponse;
use Octo\Admin\Controller;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Menu;
use Octo\Block;
use Octo\Event;
use Octo\Pages\Model\Page;
use Octo\Pages\Model\PageVersion;
use Octo\Store;
use Octo\System\Model\ContentItem;
use Octo\Template;

class PageController extends Controller
{
    /**
     * @var \Octo\Pages\Store\PageStore
     */
    protected $pageStore;

    /**
     * @var \Octo\Pages\Store\PageVersionStore
     */
    protected $versionStore;

    /**
     * @var \Octo\System\Store\ContentItemStore
     */
    protected $contentStore;

    public static function registerMenus(Menu $menu)
    {
        $pages = $menu->addRoot('Pages', '/page')->setIcon('sitemap');
        $pages->addChild(new Menu\Item('Add Page', '/page/add'));

        $manage = new Menu\Item('Manage Pages', '/page');
        $manage->addChild(new Menu\Item('Edit Page', '/page/edit', true));
        $manage->addChild(new Menu\Item('Delete Page', '/page/delete', true));
        $manage->addChild(new Menu\Item('Save Page', '/page/save', true));
        $manage->addChild(new Menu\Item('Publish Page', '/page/publish', true));
        $pages->addChild($manage);
    }

    public function init()
    {
        $this->pageStore = Store::get('Page');
        $this->versionStore = Store::get('PageVersion');
        $this->contentStore = Store::get('ContentItem');

        $this->addBreadcrumb('Pages', '/page');
    }

    public function index()
    {
        $this->setTitle('Manage Pages');

        $parentId = $this->getParam('parent', null);

        if (is_null($parentId)) {
            $parent = $this->pageStore->getHomepage();

            if (is_null($parent)) {
                $this->successMessage('Create your first page using the form below.', true);

                $this->response = new RedirectResponse();
                $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri').'/page/add');
                return;
            }

            $parentId = $parent->getId();
        }

        $pages = $this->pageStore->getByParentId($parentId, ['order' => [['position', 'ASC']]]);

        if (isset($parent)) {
            array_unshift($pages, $parent);
        }

        $this->view->pages = $pages;
        $this->view->parentId = $parentId;
    }

    public function add()
    {
        if ($this->request->getMethod() == 'POST') {
            return $this->createPage();
        }

        $this->setTitle('Add Page');
        $this->addBreadcrumb('Add Page', '/page/add');

        $form = new FormElement();
        $form->setMethod('POST');
        $form->setAction('/' . $this->config->get('site.admin_uri') . '/page/add');
        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $fieldset->addField(Form\Element\Text::create('title', 'Page Title', true));
        $fieldset->addField(Form\Element\Text::create('short_title', 'Short Title', true));
        $fieldset->addField(Form\Element\Text::create('description', 'Description', true));
        $fieldset->addField(Form\Element\Text::create('meta_description', 'Meta Description', true));

        $templates = [];
        foreach ($this->getTemplates() as $template) {
            $templates[$template] = ucwords($template);
        }

        if (!count($templates)) {
            $this->errorMessage('You cannot create pages until you have created at least one page template.', true);

            $this->response = new RedirectResponse();
            $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri'));
            return;
        }

        $field = Form\Element\Select::create('template', 'Template', true);
        $field->setOptions($templates);
        $field->setClass('select2');
        $fieldset->addField($field);

        $field = Form\Element\Select::create('parent_id', 'Parent Page', true);
        $field->setOptions($this->pageStore->getParentPageOptions());
        $field->setClass('select2');
        $fieldset->addField($field);

        $field = new Form\Element\Submit();
        $field->setValue('Create Page');
        $field->setClass('btn-success');
        $fieldset->addField($field);

        $this->view->form = $form;
    }

    protected function createPage()
    {
        // Create the models that we'll be using:
        $page = new Page();
        $version = new PageVersion();

        // Determine our page's parent, and set it if required:
        $parentId = $this->getParam('parent_id', null);

        if (!empty($parentId)) {
            $parent = $this->pageStore->getById($parentId);
            $page->setParent($parent);
        }

        // Create an ID for the page, which will also create a temporary URI for it:
        $page->generateId();

        /** @var \Octo\Pages\Model\Page $page */
        $page = $this->pageStore->saveByInsert($page);

        // Set up the current version of the page:
        $version->setValues($this->getParams());
        $version->setPage($page);
        $version->setVersion(1);
        $version->setUserId($this->currentUser->getId());
        $version->setUpdatedDate(new \DateTime());

        $content = '{}';
        $hash = md5($content);
        $contentObject = $this->contentStore->getById($hash);

        if (is_null($contentObject)) {
            $contentObject = new ContentItem();
            $contentObject->setId($hash);
            $contentObject->setContent($content);

            $this->contentStore->saveByInsert($contentObject);
        }
        $version->setContentItemId($hash);
        $version = $this->versionStore->saveByInsert($version);

        $page->setCurrentVersion($version);

        $page->generateUri();
        $this->pageStore->save($page);

        $this->response = new RedirectResponse();
        $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri').'/page/edit/' . $page->getId());
    }

    public function edit($pageId)
    {
        $page = $this->pageStore->getById($pageId);
        $latest = $this->pageStore->getLatestVersion($page);

        if ($page->getCurrentVersionId() == $latest->getId()) {
            $data = $latest->getDataArray();
            $data['version']++;
            unset($data['id']);

            $latest = new PageVersion();
            $latest->setValues($data);
        }

        $latest->setUpdatedDate(new \DateTime());
        $latest->setUser($this->currentUser);
        $latest = $this->versionStore->save($latest);

        $this->setTitle($latest->getTitle(), 'Manage Pages');
        $this->addBreadcrumb($latest->getTitle(), '/page/edit/' . $pageId);


        $pageBlocks = $this->parseTemplate($latest->getTemplate());
        $blocks = Block::getBlocks();

        $hasEditableBlocks = false;


        $pageContent = [];

        if ($latest->getContentItemId()) {
            $pageContent = json_decode($latest->getContentItem()->getContent(), true);
        }

        foreach ($pageBlocks as &$block) {
            if (!isset($blocks[$block['type']])) {
                $block['editable'] = false;
                continue;
            }

            $blocks[$block['type']]['blocks'][] =& $block;

            if (array_key_exists('editable', $block) && !$block['editable']) {
                $block['editable'] = false;
                continue;
            }

            if (isset($blocks[$block['type']]['editor']) && is_callable($blocks[$block['type']]['editor'])) {
                $block['editable'] = true;
            } else {
                $block['editable'] = false;
            }

            if (array_key_exists($block['id'], $pageContent)) {
                $block['content'] = $pageContent[$block['id']];
            }

            if ($block['editable']) {
                $hasEditableBlocks = true;
                $blocks[$block['type']]['haseditable'] = true;
                $block['editor'] = $blocks[$block['type']]['editor']($block);
            }
        }


        $this->view->page = $page;
        $this->view->latest = $latest;
        $this->view->blocks = $blocks;
        $this->view->hasEditableBlocks = $hasEditableBlocks;
        $this->view->templates = json_encode($this->getTemplates());
        $this->view->pages = json_encode($this->pageStore->getParentPageOptions());

        if ($latest->getContentItemId()) {
            $this->view->pageContent = $latest->getContentItem()->getContent();
        } else {
            $this->view->pageContent = '{}';
        }
    }

    protected function getTemplates()
    {
        $rtn = [];
        $dir = new \DirectoryIterator(SITE_TEMPLATE_PATH);

        foreach ($dir as $item) {
            if ($item->isDot()) {
                continue;
            }

            if (!$item->isFile()) {
                continue;
            }

            if ($item->getExtension() !== 'html') {
                continue;
            }

            $rtn[$item->getBasename('.html')] = $item->getBasename('.html');
        }

        return $rtn;
    }

    protected function parseTemplate($template)
    {
        $blocks = array();
        $template = Template::getPublicTemplate($template);
        $template->addFunction('block', function ($block, $view) use (&$blocks) {
            foreach ($block as &$value) {
                $value = $view->getVariable($value);
            }

            $blocks[] = $block;
        });

        $template->render();

        return $blocks;
    }

    public function editPing($pageId)
    {
        $page = $this->pageStore->getById($pageId);

        if ($page) {
            $latest = $page->getLatestVersion();
            $latest->setUpdatedDate(new \DateTime());
            $this->versionStore->save($latest);
        }

        die('OK');
    }

    public function save($pageId)
    {
        $content = $this->getParam('content', null);

        if (!is_null($content)) {
            $page = $this->pageStore->getById($pageId);
            $latest = $this->pageStore->getLatestVersion($page);
            $hash = md5($content);

            if ($latest->getContentItemId() !== $hash) {
                $contentObject = $this->contentStore->getById($hash);

                if (is_null($contentObject)) {
                    $contentObject = new ContentItem();
                    $contentObject->setId($hash);
                    $contentObject->setContent($content);

                    $this->contentStore->saveByInsert($contentObject);
                }

                $latest->setContentItemId($hash);
                $latest->setUpdatedDate(new \DateTime());
                $latest->setUser($this->currentUser);

                $this->versionStore->save($latest);
            }
        }

        $pageData = $this->getParam('page', null);

        if (!is_null($pageData)) {

            if (empty($pageData['image_id'])) {
                unset($pageData['image_id']);
            }

            $page = $this->pageStore->getById($pageId);

            if ($pageData['parent_id'] != $page->getParentId()) {
                $page->setParentId($pageData['parent_id']);
                $this->pageStore->saveByUpdate($page);
            }

            $latest = $this->pageStore->getLatestVersion($page);
            $latest->setValues($pageData);
            $latest->setUpdatedDate(new \DateTime());
            $latest->setUser($this->currentUser);

            $this->versionStore->save($latest);
        }

        die('OK');
    }

    public function publish($pageId)
    {
        $page = $this->pageStore->getById($pageId);
        $latest = $this->pageStore->getLatestVersion($page);
        $latest->setUpdatedDate(new \DateTime());
        $this->versionStore->save($latest);

        $page->setCurrentVersion($latest);
        $page->generateUri();
        $this->pageStore->save($page);

        $content = $latest->getContentItem()->getContent();

        $data = ['model' => $page, 'content_id' => $page->getId(), 'content' => $content];
        Event::trigger('ContentPublished', $data);

        $this->successMessage($latest->getTitle() . ' has been published!', true);
        $this->response = new \b8\Http\Response\RedirectResponse($this->response);
        $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri').'/page');
    }

    public function delete($pageId)
    {
        $page = $this->pageStore->getById($pageId);
        $this->successMessage($page->getCurrentVersion()->getShortTitle() . ' has been deleted.', true);

        $this->pageStore->delete($page);

        $this->response = new RedirectResponse();
        $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri').'/page');
    }

    public function autocomplete($identifier = 'id')
    {
        $pages = $this->pageStore->search($this->getParam('q', ''));

        $rtn = ['results' => [], 'more' => false];

        foreach ($pages as $page) {

            $id = $page->getId();

            if ($identifier == 'uri') {
                $id = $page->getUri();
            }

            $rtn['results'][] = ['id' => $id, 'text' => $page->getCurrentVersion()->getTitle()];
        }

        die(json_encode($rtn));
    }

    // Get meta information about a set of pages described by Id.
    public function metadata()
    {
        $pageIds = json_decode($this->getParam('q', '[]'));
        $rtn = ['results' => [], 'more' => false];
        foreach($pageIds as $pageId) {
            $page = $this->pageStore->getById($pageId);
            if($page) {
                $rtn['results'][] = ['id' => $page->getId(), 'text' => $page->getCurrentVersion()->getTitle()];
            }
        }
        die(json_encode($rtn));
    }

    public function sort()
    {
        $positions = $this->getParam('positions', []);

        foreach ($positions as $id => $position) {
            $page = $this->pageStore->getById($id);

            if ($page instanceof Page) {
                $page->setPosition($position);
                $this->pageStore->save($page);
            }
        }

        die('OK');
    }
}
