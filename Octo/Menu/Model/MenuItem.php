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
}
