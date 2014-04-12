<?php

/**
 * MenuItem model for table: menu_item
 */

namespace Octo\Menu\Model;

use Octo;

/**
 * MenuItem Model
 * @uses Octo\Menu\Model\Base\MenuBaseItemBase
 */
class MenuItem extends Octo\Model
{
    use Base\MenuItemBase;

    public function __construct($initialData = array())
    {
        parent::__construct($initialData);

        $this->getters['current'] = 'getCurrent';
        $this->setters['current'] = 'setCurrent';
    }

    /**
     * Get the path to the URL
     *
     * @return string
     * @author James Inman
     */
    public function getUrl()
    {
        $page = $this->getPage();
        if (isset($page)) {
            return $page->getUri();
        } else {
            return $this->data['url'];
        }
    }
    
    /**
    * Get the value of Current / current.
    *
    * @param $value string
    */
    public function getCurrent()
    {
        if (isset($this->data['current'])) {
            return $this->data['current'];
        }
    }
    
    /**
    * Set the value of Current / current.
    *
    * @param $value string
    */
    public function setCurrent($value)
    {
        $this->data['current'] = $value;
    }
}
