<?php

namespace Octo\Database;

use Octo\Template;

class CodeGenerator extends \Block8\Database\CodeGenerator
{
    protected function processTemplate($tableName, $table, $template)
    {
        Template::init();

        Template::addFunction('getNamespace', function ($model) {
            return $this->getNamespace($model);
        });

        foreach ($table['columns'] as &$column) {
            if (isset($column['validate_int']) && $column['validate_int']) {
                $column['param_type'] = 'int';
            }

            if (isset($column['validate_string']) && $column['validate_string']) {
                $column['param_type'] = 'string';
            }

            if (isset($column['validate_float']) && $column['validate_float']) {
                $column['param_type'] = 'float';
            }

            if (isset($column['validate_date']) && $column['validate_date']) {
                $column['param_type'] = null;
            }
        }

        $template = new Template($template, 'db');
        $template->itemNamespace = $this->getNamespace($table['php_name']);
        $template->name = $tableName;
        $template->table = $table;
        $template->counts = $this->counts;

        return $template->render();
    }
}
