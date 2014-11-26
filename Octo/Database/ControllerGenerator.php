<?php

namespace Octo\Database;

use b8\Config;
use b8\Database as DbConnection;
use b8\Database\Map;
use b8\View\Template;

class ControllerGenerator
{
    /**
     * @var \b8\Database
     */
    protected $database;

    /**
     * @var Map
     */
    protected $map;

    /**
     * @var array[]
     */
    protected $tables;

    /**
     * @var array
     */
    protected $namespaces;

    /**
     * @var array
     */
    protected $paths;

    /**
     * @param Database $database
     * @param array $namespaces
     * @param $paths
     */
    public function __construct(DbConnection $database, array $namespaces, $paths)
    {
        $this->database = $database;
        $this->namespaces = $namespaces;
        $this->paths = $paths;
        $this->map = new Map($this->database);
        $this->tables = $this->map->generate();
    }

    protected function getNamespace($modelName)
    {
        if (array_key_exists($modelName, $this->namespaces)) {
            return $this->namespaces[$modelName];
        } elseif (isset($this->namespaces['default'])) {
            return $this->namespaces['default'];
        } else {
            return null;
        }
    }

    public function getPath($namespace)
    {
        return array_key_exists($namespace, $this->paths) ? $this->paths[$namespace] : $this->paths['default'];
    }

    public function generateControllers()
    {
        print PHP_EOL . 'GENERATING CONTROLLERS' . PHP_EOL . PHP_EOL;

        foreach ($this->tables as $tableName => $table) {
            $namespace = $this->getNamespace($table['php_name']);

            if (is_null($namespace)) {
                $msg = 'If you would like to create an admin controller for ' .
                    $table['php_name'] .
                    ', please choose a namespace:';
                $namespace = $this->ask($msg, array_keys($this->paths));

                if (is_null($namespace)) {
                    continue;
                }

                $this->namespaces[$table['php_name']] = $namespace;
            }

            $controllerPath = 'Controller/';//$this->getPath($namespace) . 'Controller/';
            $controllerFile = $controllerPath . $table['php_name'] . 'Controller.php';

            if (is_file($controllerFile)) {
                //continue;
            }

            $formFields = $this->generateForm($table);
            $table['form'] = $formFields;
            $table['uri'] = str_replace('_', '-', $tableName);

            if (!is_dir($controllerPath)) {
                $old = umask(0);
                @mkdir($controllerPath, 02775, true);
                umask($old);
            }

            $controller = $this->processTemplate($tableName, $table, 'ControllerTemplate');

            print '-- ' . $table['php_name'] . PHP_EOL;
            print '-- -- Creating controller: ' . $table['php_name'] . 'Controller' . PHP_EOL;
            file_put_contents($controllerFile, $controller);
        }
    }

    protected function generateForm($table)
    {
        $fields = [];

        foreach ($table['columns'] as $column) {
            $relationship = null;

            if (isset($table['relationships']['toOne'][$column['name']])) {
                $relationship = $table['relationships']['toOne'][$column['name']];
            }

            $fields[] = $this->getFormFieldDefinition($column, $relationship);
        }

        return $fields;
    }

    protected function columnNameToLabel($text)
    {
        $text = str_replace('_', ' ', $text);
        $text = ucwords($text);

        return $text;
    }

    protected function getFormFieldDefinition($column, $relationship)
    {
        $field = [];

        if ($column['is_primary_key']) {
            $field['type'] = 'hidden';
            $field['name'] = $column['name'];

            return $field;
        }

        if (!is_null($relationship)) {
            $field['type'] = 'relationship';
            $field['name'] = $column['name'];
            $field['label'] = $this->columnNameToLabel($relationship['table']);
            $field['table'] = $relationship['table'];
            $field['model'] = $relationship['php_name'];
            $field['column'] = $relationship['col'];

            return $field;
        }

        if ($column['php_type'] == 'DateTime') {
            $field['name'] = $column['name'];
            $field['label'] = $this->columnNameToLabel($column['name']);
            $field['type'] = 'date';
            $field['validate'] = $column['php_type'];

            return $field;
        }

        $field['name'] = $column['name'];
        $field['label'] = $this->columnNameToLabel($column['name']);
        $field['type'] = 'text';
        $field['validate'] = $column['php_type'];

        return $field;
    }

    protected function processTemplate($tableName, $table, $template)
    {
        $tpl = Template::createFromFile($template, CMS_PATH . 'Database/CodeGenerator/');
        $tpl->appNamespace = 'Octo';
        $tpl->itemNamespace = $this->getNamespace($table['php_name']);
        $tpl->name = $tableName;
        $tpl->table = $table;
        $tpl->counts = $this->counts;

        $callback = function ($args, $view) {
            return $this->getNamespace($args['model']);
        };

        $tpl->addFunction('get_namespace', $callback);

        return $tpl->render();
    }

    protected function ask($question, $options)
    {
        print $question . PHP_EOL;

        print '0. Do not create' . PHP_EOL;
        foreach ($options as $key => $value) {
            print ($key + 1) . '. ' . $value . PHP_EOL;
        }

        print 'Enter the number representing your choice: ';

        $stdin = fopen('php://stdin', 'r');
        $rtn = fgets($stdin);
        fclose($stdin);

        $rtn = intval(trim($rtn));

        if ($rtn == 0) {
            return null;
        }

        if (!array_key_exists($rtn - 1, $options)) {
            return $this->ask($question, $options);
        }

        return $options[$rtn - 1];
    }

    protected function askText($question, $default)
    {
        print $question . PHP_EOL;
        print 'Leave blank to use: ' . $default . PHP_EOL . PHP_EOL;

        $stdin = fopen('php://stdin', 'r');
        $rtn = fgets($stdin);
        fclose($stdin);

        $rtn = trim($rtn);

        if (empty($rtn)) {
            return $default;
        }

        return $rtn;
    }
}
