<?php
namespace Octo\News\Admin\Controller;

use DateTime;
use b8\Form;
use Octo\Store;
use Octo\Admin\Controller;
use Octo\Admin\Form as FormElement;
use Octo\Admin\Menu;
use Octo\Event;
use Octo\Articles\Model\Article;
use Octo\System\Model\ContentItem;

class NewsController extends Controller
{
    /**
     * @var Scope
     */
    protected $scope;
    /*
     * @var Article Type
     */
    protected $articleType;
    /*
     * @var Lowercase Article Type
     */
    protected $lowerArticleType;

    /**
     * @var string Article type
     */
    protected $modelType = '\Octo\Articles\Model\Article';

    /**
     * Return the menu nodes required for this controller
     *
     * @param Menu $menu
     * @author James Inman
     */
    public static function registerMenus(Menu $menu)
    {
        $news = $menu->addRoot('News', '#')->setIcon('bullhorn');
        $news->addChild(new Menu\Item('Add Article', '/news/add'));
        $manage = new Menu\Item('Manage Articles', '/news');
        $manage->addChild(new Menu\Item('Edit Article', '/news/edit', true));
        $manage->addChild(new Menu\Item('Delete Article', '/news/delete', true));
        $news->addChild($manage);
        $categories = new Menu\Item('Manage Categories', '/categories/manage/news');
        $news->addChild($categories);
    }

    /**
     * Setup initial menu
     *
     * @return void
     * @author James Inman
     */
    public function init()
    {
        $this->userStore = Store::get('User');
        $this->categoryStore = Store::get('Category');
        $this->contentItemStore = Store::get('ContentItem');
        $this->articleStore = Store::get('Article');

        $this->scope = 'news';
        $this->articleType = 'Article';
        $this->lowerArticleType = 'article';

        $this->setTitle($this->articleType);
        $this->addBreadcrumb($this->articleType, '/' . $this->lowerArticleType);
    }

    public function index()
    {
        $this->view->articles = $this->articleStore->getAllForCategoryScope($this->scope);
    }

    public function add()
    {
        $this->setTitle('Add ' . $this->articleType);
        $this->addBreadcrumb('Add ' . $this->articleType, '/' . $this->lowerArticleType . '/add');

        if ($this->request->getMethod() == 'POST') {
            $form = $this->newsForm($this->getParams());

            if ($form->validate()) {
                try {
                    $hash = md5($this->getParam('content'));

                    $contentItem = $this->contentItemStore->getById($hash);
                    if (!$contentItem) {
                        $contentItem = new ContentItem();
                        $contentItem->setId($hash);
                        $contentItem->setContent(json_encode(array('content' => $this->getParam('content'))));
                        $contentItem = $this->contentItemStore->saveByInsert($contentItem);
                    }

                    $modelName = $this->modelType;
                    $article = new $modelName();
                    $article->setValues($this->getParams());
                    $article->setUserId($this->currentUser->getId());
                    $article->setContentItemId($hash);
                    $article->setCreatedDate(new \DateTime());
                    $article->setUpdatedDate(new \DateTime());
                    $article->setSlug($article->generateSlug());
                    if (empty($this->getParam('summary'))) {
                        $article->setSummary($article->generateSummary());
                    }

                    Event::trigger('before' . $this->articleType . 'Save', $article);
                    $article = $this->articleStore->save($article);

                    $this->successMessage($article->getTitle() . ' was added successfully.', true);
                    header('Location: /' . $this->config->get('site.admin_uri') . '/' . $this->scope);
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error adding the ' . $this->lowerArticleType . '. Please try again.'
                    );
                }
            } else {
                $this->errorMessage('There was an error adding the ' . $this->lowerArticleType . '. Please try again.');
            }

            $this->view->form = $form->render();
        } else {
            $form = $this->newsForm();
            $this->view->form = $form->render();
        }
    }

    public function edit($newsId)
    {
        $article = $this->articleStore->getById($newsId);
        $this->setTitle($article->getTitle());
        $this->addBreadcrumb($article->getTitle(), $this->lowerArticleType . '/edit/' . $newsId);

        $this->view->title = $article->getTitle();

        if ($this->request->getMethod() == 'POST') {
            $values = array_merge(array('id' => $newsId), $this->getParams());
            $form = $this->newsForm($values, 'edit');

            if ($form->validate()) {
                try {
                    $hash = md5($this->getParam('content'));

                    $contentItem = $this->contentItemStore->getById($hash);
                    if (!$contentItem) {
                        $contentItem = new ContentItem();
                        $contentItem->setId($hash);
                        $contentItem->setContent(json_encode(array('content' => $this->getParam('content'))));
                        $contentItem = $this->contentItemStore->saveByInsert($contentItem);
                    }

                    $article->setValues($this->getParams());
                    $article->setUserId($this->currentUser->getId());
                    $article->setContentItemId($hash);
                    $article->setUpdatedDate(new \DateTime());

                    if (trim($this->getParam('summary')) == '') {
                        $article->setSummary($article->generateSummary());
                    }

                    $article->setSlug($article->generateSlug());

                    Event::trigger('before' . $this->articleType . 'Save', $article);

                    $article = $this->articleStore->save($article);

                    $this->successMessage($article->getTitle() . ' was edited successfully.', true);
                    header('Location: /' . $this->config->get('site.admin_uri') . '/' . $this->scope);
                } catch (Exception $e) {
                    $this->errorMessage(
                        'There was an error editing the ' . $this->lowerArticleType . '. Please try again.'
                    );
                }
            } else {
                $this->errorMessage(
                    'There was an error editing the ' . $this->lowerArticleType . '. Please try again.'
                );
            }

            $this->view->form = $form->render();
        } else {
            $article_data = $article->getDataArray();
            $article_data['content'] = json_decode($article->getContentItem()->getContent())->content;
            $form = $this->newsForm($article_data, 'edit');
            $this->view->form = $form->render();
        }
    }

    public function delete($newsId)
    {
        $article = $this->articleStore->getById($newsId);
        $this->articleStore->delete($article);
        $this->successMessage($article->getTitle() . ' was deleted successfully.', true);
        header('Location: /' . $this->config->get('site.admin_uri') . '/' . $this->scope);
    }

    public function newsForm($values = [], $type = 'add')
    {
        $form = new FormElement();
        $form->setMethod('POST');

        $adminUri = $this->config->get('site.admin_uri');
        if ($type == 'add') {
            $form->setAction('/' . $adminUri . '/' . $this->scope . '/add');
        } else {
            $form->setAction('/' . $adminUri . '/' . $this->scope . '/edit/' . $values['id']);
        }

        $form->setClass('smart-form');

        $fieldset = new Form\FieldSet('fieldset');
        $form->addField($fieldset);

        $field = new Form\Element\Text('title');
        $field->setRequired(true);
        $field->setLabel('Title');
        $fieldset->addField($field);

        $field = new Form\Element\TextArea('summary');
        $field->setRequired(false);
        $field->setRows(5);
        $field->setLabel('Summary (optional)');
        $fieldset->addField($field);

        $field = new Form\Element\TextArea('content');
        $field->setRequired(true);
        $field->setLabel('Content');
        $field->setClass('ckeditor advanced');
        $fieldset->addField($field);

        $field = new Form\Element\Select('author_id');
        $field->setOptions($this->userStore->getNames());

        if (isset($values['user_id'])) {
            $field->setValue($values['user_id']);
        } else {
            $field->setValue($this->currentUser->getId());
        }

        $field->setClass('select2');
        $field->setLabel('Author');
        $fieldset->addField($field);

        $field = new Form\Element\Select('category_id');
        $field->setOptions($this->categoryStore->getNamesForScope($this->scope));
        $field->setLabel('Category');
        $field->setClass('select2');
        $fieldset->addField($field);

        $field = new Form\Element\Text('publish_date');
        $field->setLabel('Published Date');

        if (!isset($values['publish_date'])) {
            $values['publish_date'] = (new DateTime())->format('l j F Y');
        } else {
            $values['publish_date'] = (new DateTime($values['publish_date']))->format('l j F Y');
        }

        $field->setClass('sa-datepicker');
        $fieldset->addField($field);

        $data = [&$form, &$values];
        Event::trigger($this->scope . 'Form', $data);
        list($form, $values) = $data;

        $field = new Form\Element\Submit();
        $field->setValue('Save ' . $this->articleType);
        $field->setClass('btn-success');
        $form->addField($field);

        $form->setValues($values);
        return $form;
    }
}
