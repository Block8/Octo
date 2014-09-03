<?php
namespace Octo\Utilities;

/**
 * Class StringUtilities
 */
class StringUtilities
{
    /**
     * Generate a slug from a string
     *
     * @param string $string
     * @return string Slug
     */
    public static function generateSlug($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/([^a-z0-9]+)/', '-', $string);
        $string = str_replace('--', '-', $string);

        if (substr($string, 0, 1) == '-') {
            $string = substr($string, 1);
        }

        if (substr($string, -1) == '-') {
            $string = substr($string, 0, -1);
        }

        return $string;
    }

    /**
     * Turn a string from its plural form into singular
     *
     * This works for not very many use-cases, so if it breaks one, add it!
     *
     * @param string $string
     * @return string
     * @return void
     */
    public static function singularize($string)
    {
        if (substr($string, -1) == 's') {
            return substr($string, 0, -1);
        }

        return $string;
    }

    /**
     * Turns a string into a valid URL by prepending http:// if no protocol exists
     *
     * @param string $string
     * @return string $string
     * @author James Inman
     */
    public static function makeValidUrl($string)
    {
        $protocols = ['http://', 'https://', 'ftp://'];

        if ($string != '') {
            foreach ($protocols as $p) {
                if (substr($string, 0, strlen($p)) == $p) {
                    return $string;
                }
            }
        }

        if ($string != '' && $string != 'http://') {
            $string = 'http://' . $string;
        }

        return $string;
    }

    /**
     * Return the first $num words from a string
     *
     * @param $string
     * @param $num
     * @param $append String to append to end, e.g. ...
     * @return string
     */
    public static function firstWords($string, $num, $append)
    {
        $words = explode(' ', $string);
        $string = implode(' ', array_splice($words, 0, $num));

        // Don't append e.g. ... to the end if we're below the limit!
        if (count($words) <= $num) {
            $append = '';
        }
        return $string . $append;
    }
}
