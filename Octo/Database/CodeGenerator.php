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

            $column['default_formatted'] = 'null';

            if (array_key_exists('default', $column) && !is_null($column['default'])) {
                if (is_numeric($column['default'])) {
                    $column['default_formatted'] = $column['default'];
                } elseif ($column['default'] != 'CURRENT_TIMESTAMP') {
                    $column['default_formatted'] = '\''.$column['default'].'\'';
                }
            }

            $methods[$column['name']] = $column['php_name'];
        }

        $use = [];

        if (isset($table['relationships']['toOne'])) {
            foreach ($table['relationships']['toOne'] as $fk) {
                $methods[$fk['php_name']] = $fk['php_name'];
                $use[$fk['table_php_name']] = $this->getNamespace($fk['table_php_name']) . '\\Model\\' . $fk['table_php_name'];
            }
        }

        $template = new Template($template, 'db');
        $template->itemNamespace = $this->getNamespace($table['php_name']);
        $template->name = $tableName;
        $template->table = $table;
        $template->counts = $this->counts;
        $template->methods = $methods;
        $template->use = $use;

        return $template->render();
    }
}
