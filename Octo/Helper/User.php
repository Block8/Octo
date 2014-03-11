<?php

namespace Octo\Helper;

/**
 * User Helper - Provides access to logged in user information in views.
 */
class User
{
    public function __call($method, $params = array())
    {
        $user = $_SESSION['user'];
        return call_user_func_array(array($user, $method), $params);
    }

    public function __get($key)
    {
        $user = $_SESSION['user'];

        if (method_exists($user, $key)) {
            return $user->{$key}();
        }

        return '';
    }
}
