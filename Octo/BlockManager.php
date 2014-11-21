<?php

namespace Octo;

use b8\Http\Request;
use b8\Http\Response;
use Octo\Pages\Model\Page;
use Octo\Pages\Model\PageVersion;
use Octo\Template;

class BlockManager
{
    /**
     * @var \Octo\Pages\Model\Page
     */
    protected $page;

    /**
     * @var \Octo\Pages\Model\PageVersion
     */
    protected $version;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var \b8\Http\Request
     */
    protected $request;

    /**
     * @var \b8\Http\Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $content = [];

    /**
     * @var bool
     */
    protected $uriExtensionsHandled = false;

    protected $dataStore = [];

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @param Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @param PageVersion $version
     */
    public function setPageVersion(PageVersion $version)
    {
        $this->version = $version;
    }

    /**
     * @param $uri
     */
    public function setUriExtension($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param array $content
     */
    public function setContent(array $content)
    {
        $this->content = $content;
    }

    /**
     * @param Template $template
     */
    public function attachToTemplate(Template $template)
    {
        $template->addFunction('block', [$this, 'renderBlock']);
        $template->addFunction('has_content', function ($args, &$view) {
            if (!empty($this->content[$view->getVariable($args['id'])])) {
                return true;
            } else {
                return false;
            }
        });
    }

    /**
     * @param $args
     * @param $view
     * @return string|void
     */
    public function renderBlock($args, &$view)
    {
        // Allow passing variables to blocks
        $templateVariables = [];
        if (isset($args['variables'])) {
            if (!is_array($args['variables'])) {
                $args['variables'] = array($args['variables']);
            }

            foreach ($args['variables'] as $variable) {
                $variable = explode('=>', $variable);
                $variable = array_map('trim', $variable);

                if (count($variable) == 1) {
                    $templateVariables[$variable[0]] = $variable[0];
                } else {
                    $templateVariables[$variable[0]] = $variable[1];
                }
            }

            unset($args['variables']);
        }

        foreach ($args as &$value) {
            $value = $view->getVariable($value);
        }

        $type = $args['type'];
        $blockId = isset($args['id']) ? $args['id'] : null;
        $content = [];

        if (array_key_exists($blockId, $this->content)) {
            $content = $this->content[$blockId];
        }

        $block = Block::create($type, $content);
        $block->setRequest($this->request);
        $block->setResponse($this->response);

        $args = array_merge(['variables' => $templateVariables], $args);

        $block = $this->setBlockProperties($block, $args);

        return $block->render();
    }

    /**
     * Set template parameters and other properties on a block
     *
     * @param $block
     * @param $args
     * @return mixed
     */
    protected function setBlockProperties($block, $args)
    {
        $block->setTemplateParams($args);

        if ($block->hasUriExtensions()) {
            $this->uriExtensionsHandled = true;
            $block->setUriExtension($this->uri);
        }

        if (isset($this->page)) {
            $block->setPage($this->page);
        }

        if (isset($this->version)) {
            $block->setPageVersion($this->version);
        }

        $block->setDataStore($this->dataStore);

        return $block;
    }

    /**
     * Whether the block handles URI extensions, e.g. /some_page_with_block/news/article-1/
     *
     * @return bool
     */
    public function uriExtensionsHandled()
    {
        return $this->uriExtensionsHandled;
    }

    public function getDataStore()
    {
        return $this->dataStore;
    }

    public function setDataStore(array $data)
    {
        $this->dataStore = $data;
    }
}
