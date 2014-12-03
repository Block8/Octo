<?php

namespace Octo\System\Command;

use b8\Config;
use b8\Database;
use Octo\Store;
use Octo\Event;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Build a search index for the site
 *
 */

class BuildSearchIndex extends Command
{
    protected $systemModels;

    protected function configure()
    {
        $this
            ->setName('search:index')
            ->setDescription('Build the site search index from content in the database');
    }

    /* command entrypoint */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = glob(CMS_PATH.'*/Model/*.php');

        foreach ($items as $item) {
            $model = str_replace([CMS_BASE_PATH, '/', '.php'], ['', '\\', ''], $item);
            $systemModels[] = $model;
        }

        foreach (glob(APP_PATH."*", GLOB_ONLYDIR) as $path) {
            $items = glob($path.'/*/Model/*.php');
            foreach ($items as $item) {
                $model = str_replace([APP_PATH, '/', '.php'], ['', '\\', ''], $item);
                $systemModels[] = $model;
            }
        }

        foreach ($systemModels as $model) {
            $myModel = new $model;
            if (method_exists($myModel, 'getIndexableContent')) {
                $reflect = new \ReflectionClass($myModel);
                $myStore = Store::get($reflect->getShortName());
                $modelsToIndex = $myStore->getModelsToIndex();
                $output->write('Indexing: '.$reflect->getShortName());
                $count=0;

                foreach ($modelsToIndex as $newModel) {
                    $count++;
                    $content = $newModel->getIndexableContent();
                    $data = ['model' => $newModel, 'content_id' => $newModel->getId(), 'content' => $content];
                    Event::trigger('ContentPublished', $data);
                }

                $output->writeln(' ('.$count.' items)');
            }
        }
    }
}
