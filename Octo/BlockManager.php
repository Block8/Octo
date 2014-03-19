<?php

namespace Octo;

use b8\Http\Request;
use b8\Http\Response;
use Octo\Model\Page;
use Octo\Model\PageVersion;
use Octo\Template;

class BlockManager
{
    /**
     * @var \Octo\Model\Page
     */
    protected $page;

    /**
     * @var \Octo\Model\PageVersion
     */
    protected $version;

    /**
     * @var array
     */
    protected $args = [];

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

    public function __construct()
    {
    }

    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    public function setPageVersion(PageVersion $version)
    {
        $this->version = $version;
    }

    public function setArgs(array $args)
    {
        $this->args = $args;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    public function setContent(array $content)
    {
        $this->content = $content;
    }

    public function attachToTemplate(Template $template)
    {
        $template->addFunction('block', [$this, 'renderBlock']);
    }

    public function renderBlock($args, &$view)
    {
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
        $block->setArgs($this->args);
        $block->setTemplateParams($args);

        if (isset($this->page)) {
            $block->setPage($this->page);
        }

        if (isset($this->version)) {
            $block->setPageVersion($this->version);
        }

        return $block->render();
    }
}