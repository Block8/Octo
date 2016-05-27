<?php

namespace Octo\Admin\Menu;

use b8\Config;
use b8\Http\Request;
use Octo\Admin\Menu;

class Item
{
    public static function create($title, $link, $hidden = false, $root = false)
    {
        return new static($title, $link, $hidden, $root);
    }

    protected $title;
    protected $link;
    protected $hidden;
    protected $icon;
    protected $isRoot;

    /**
     * @var \Octo\System\Model\User
     */
    protected $user;

    /**
     * @var Item
     */
    protected $parent;

    /**
     * @var Item[]
     */
    protected $children = [];

    public function __construct($title, $link, $hidden = false, $root = false)
    {
        $this->title = $title;
        $this->hidden = $hidden;
        $this->link = $link;
        $this->isRoot = $root;
        $this->user = $_SESSION['user'];
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function setParent(Item $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    public function addChild(Item $child)
    {
        $child->setParent($this);
        $this->children[] = $child;
    }

    /**
     * @return array
     */
    public function getTree()
    {
        $rtn = [];

        foreach ($this->children as $item) {
            $thisItem = [];
            $thisItem['title'] = $item->getTitle();
            $thisItem['uri'] = $item->getLink(false);

            if ($item->hasChildren()) {
                $thisItem['children'] = $item->getTree();
            }

            $rtn[] = $thisItem;
        }

        return $rtn;
    }

    public function hasChildren()
    {
        return count($this->children) ? true : false;
    }

    public function hasVisibleChildren()
    {
        if (!$this->hasChildren()) {
            return false;
        }

        foreach ($this->children as $child) {
            if (!$child->isHidden()) {
                return true;
            }
        }
    }

    public function isRoot()
    {
        return $this->isRoot;
    }

    public function isHidden()
    {
        if (!$this->user->canAccess($this->link)) {
            return true;
        }

        return $this->hidden;
    }

    public function getLink($forDisplay = true)
    {
        if ($forDisplay && $this->hasVisibleChildren()) {
            return '#';
        }

        $config = Config::getInstance();

        if ($forDisplay && $this->link == '@') {
            return $config->get('site.url');
        }

        $link = $this->link;

        if ($forDisplay) {
            $link = $config->get('site.full_admin_url') . $link;
        }

        return $link;
    }

    public function __toString()
    {
        if ($this->isHidden()) {
            return '';
        }

        $rtn = '<li class="'.$this->getClass().'">';

        $hasVisibleChildren = $this->hasVisibleChildren();

        if ($hasVisibleChildren || $this->isRoot()) {

            $icon = ($this->icon ? '<i class="fa fa-lg fa-fw fa-' . $this->icon . '"></i> ' : '');

            $rtn .= '<a href="'.$this->getLink().'"' .($this->link == '@' ? ' target="_blank"' : ''). '>';
            $rtn .= $icon. '<span class="menu-item-parent">' . $this->title . '</span>';

            if ($hasVisibleChildren) {
                $rtn .= '<i class="fa fa-angle-left pull-right"></i>';
            }

            $rtn .= '</a>';

            if ($hasVisibleChildren) {
                $rtn .= '<ul class="treeview-menu">';
                foreach ($this->children as $child) {
                    $rtn .= $child;
                }
                $rtn .= '</ul>';
            }

        } else {
            $rtn .= '<a href="'.$this->getLink().'"><i class="fa fa-angle-double-right"></i> ' . $this->title . '</a>';
        }

        return $rtn;
    }

    protected function getClass()
    {
        $hasChildren = $this->hasVisibleChildren();

        $rtn = '';

        if ($hasChildren) {
            $rtn .= 'treeview ';
        }

        if ($this->isActive()) {
            $rtn .= 'active ';
        }

        return $rtn;
    }

    public function isActive()
    {
        $config = Config::getInstance();
        $parts = explode($config->get('site.full_admin_url').'/', $_SERVER['REQUEST_URI']);

        if (count($parts) < 2) {
            return;
        }

        $search = '/'.$parts[1];

        if ($this->link == $search) {
            return true;
        }

        if ($this->hasActiveChildren()) {
            return true;
        }

        $partialMatch = (substr($search, 0, strlen($this->link)) == $this->link);
        if ($this->hidden && !$this->isRoot() && !$this->hasChildren() && $partialMatch) {
            return true;
        }

        return false;
    }

    protected function hasActiveChildren()
    {
        if ($this->hasChildren()) {
            foreach ($this->children as $child) {
                if ($child->isActive()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
