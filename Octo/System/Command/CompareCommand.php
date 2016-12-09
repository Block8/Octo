<?php

namespace Octo\System\Command;

use b8\Config;
use Block8\Database\Connection;
use Block8\Database\Mapper;
use Octo\Database\CodeGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompareCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:compare')
            ->setDescription('Compare two databases.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        unset($input, $output);

        $c = Config::getInstance();
        
        $connection = Connection::get();
        
        $mapper = new Mapper($connection);

        $source = @$mapper->generate();

        Connection::$database = 'aircadets_1832';
        $connection->query('USE aircadets_1832');

        $mapper = new Mapper($connection);
        $compare = @$mapper->generate();

        foreach ($source as $table => $definition) {
            if (!array_key_exists($table, $compare)) {
                print 'Missing table: ' . $table . PHP_EOL;
                continue;
            }

            foreach ($definition['columns'] as $column) {
                if (!array_key_exists($column['name'], $compare[$table]['columns'])) {
                    print 'Missing column: ' . $table . '.' . $column['name'] . PHP_EOL;
                    continue;
                }

                $cCol = $compare[$table]['columns'][$column['name']];

                if ($column['type'] != $cCol['type']) {
                    print 'Column type: ' . $table . '.' . $column['name'] . ' ' . $column['type'] . ' vs. ' . $cCol['type'] . PHP_EOL;
                }

                if (isset($column['length']) && $column['length'] != $cCol['length']) {
                    print 'Column length: ' . $table . '.' . $column['name'] . ' ' . $column['length'] . ' vs. ' . $cCol['length'] . PHP_EOL;
                }

                if ($column['null'] != $cCol['null']) {
                    print 'Column null: ' . $table . '.' . $column['name'] . ' ' . $column['null'] . ' vs. ' . $cCol['null'] . PHP_EOL;
                }

                if ($column['auto_increment'] != $cCol['auto_increment']) {
                    print 'Column auto increment: ' . $table . '.' . $column['name'] . ' ' . $column['auto_increment'] . ' vs. ' . $cCol['auto_increment'] . PHP_EOL;
                }
            }
        }
    }
}
