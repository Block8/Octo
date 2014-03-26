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

    public function setUriExtension($uri)
    {
        $this->uri = $uri;
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

        return $block->render();
    }

    public function uriExtensionsHandled()
    {
        return $this->uriExtensionsHandled;
    }
}
