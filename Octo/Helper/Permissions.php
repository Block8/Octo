<?php

namespace Octo\Helper;

class Permissions
{
    public function __get($key)
    {
        if ($key == 'render') {
            return $this->renderPermissionsTree($this->view->permTree);
        }
    }

    protected function renderPermissionsTree($base)
    {
        $rtn = '';

        foreach ($base as $item) {
            $checked = '';

            if ($item['can_access']) {
                $checked = ' checked';
            }

            if (isset($item['children'])) {
                $rtn .= '<li class="parent_li" role="treeitem">';
                $rtn .= '<span><label class="checkbox inline-block">';
                $rtn .= '<input class="parent-check" type="checkbox" name="'.$item['uri'].'"'.$checked.'>';
                $rtn .= '<i></i>';
                $rtn .= $item['title'];
                $rtn .= '</label></span>';
                $rtn .= '<ul role="group">';
                $rtn .= $this->renderPermissionsTree($item['children']);
                $rtn .= '</ul>';
                $rtn .= '</li>';
            } else {
                $rtn .= '<li style="">';
                $rtn .= '<span>';
                $rtn .= '<label class="checkbox inline-block">';
                $rtn .= '<input type="checkbox" name="'.$item['uri'].'"'.$checked.'>';
                $rtn .= '<i></i>';
                $rtn .= $item['title'];
                $rtn .= '</label>';
                $rtn .= '</span>';
                $rtn .= '</li>';
            }
        }

        return $rtn;
    }
}
