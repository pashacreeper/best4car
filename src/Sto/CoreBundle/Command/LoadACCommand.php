<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\CssSelector\CssSelector;
use Sto\CoreBundle\Entity\Catalog;
use DOMDocument, DOMXPath;

class LoadACCommand extends ContainerAwareCommand
{
    const URI_PRIMARY         = 'http://avtomedia.ru';
    const URI_PRIMARY_CATALOG = 'http://avtomedia.ru/board/catalog';

    private $catalog;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->manager = $this->getContainer()->get('doctrine')->getEntityManager();
    }

    protected function configure()
    {
        $this
            ->setName('sto:catalog:parse')
            ->setDescription('Load auto catalog')
            ->addOption(
               'mark',
               null,
               InputOption::VALUE_NONE,
               'If you wanna import only one mark, write name'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write("<comment>".(new \DateTime('now'))->format('H:i:s')."</comment>  <info>[Start parsing] Auto catalog</info>\n");

        $marks = $this->parseMarks($output, $input->getOption('mark'));
        $models = $this->parseModels($output, $marks);
        $this->parseModelFull($output, $models);

        $output->write("<comment>".(new \DateTime('now'))->format('H:i:s')."</comment>  <info>[Done parsing]</info>  Auto catalog\n");
    }

    private function parseMarks(OutputInterface $output, $str = '')
    {
        libxml_use_internal_errors(true);
        $catalog = new DOMDocument;
        $catalog->loadHTMLFile(self::URI_PRIMARY_CATALOG);

        $nodes = new DOMXPath($catalog);
        foreach ($nodes->query(CssSelector::toXPath('div.cars_list_column > div > a')) as $node) {
            $marks[$node->nodeValue] = (new Catalog\Mark)
                ->setName(trim($node->nodeValue))
                ->setUri(trim($node->getAttribute('href')))
            ;
            $this->manager->persist($marks[$node->nodeValue]);
            $output->write("  <comment>".(new \DateTime('now'))->format('H:i:s')."</comment>  <info>[{$node->nodeValue}]</info> --> write to DB\n");
        }

        $this->manager->flush();

        return $marks;
    }

    private function parseModels(OutputInterface $output, $marks)
    {
        libxml_use_internal_errors(true);
        foreach ($marks as $mark) {
            $catalog = new DOMDocument;
            $catalog->loadHTMLFile(self::URI_PRIMARY . $mark->getUri());

            $nodes = new DOMXPath($catalog);
            foreach ($nodes->query(CssSelector::toXPath('div.cars_list_column > div > a')) as $node) {
                $models[$mark->getName() . " - " . $node->nodeValue] = (new Catalog\Model)
                    ->setParent($mark)
                    ->setName(trim($node->nodeValue))
                    ->setUri(trim($node->getAttribute('href')))
                ;
                $this->manager->persist($models[$mark->getName() . " - " . $node->nodeValue]);
                $output->write("  <comment>".(new \DateTime('now'))->format('H:i:s')."</comment>  <info>[{$mark->getName()}] - {$node->nodeValue}</info> --> write to DB\n");
            }

            $this->manager->flush();
        }

        return $models;
    }

    public function parseModelFull(OutputInterface $output, $models)
    {
        libxml_use_internal_errors(true);
        foreach ($models as $model) {
            $catalog = new \DOMDocument;
            $catalog->loadHTMLFile(self::URI_PRIMARY . $model->getUri());

            $nodes = new \DOMXPath($catalog);
            $headers = $nodes->query(CssSelector::toXPath('div.orange_title > span'));
            $contents = $nodes->query(CssSelector::toXPath('table.cars_list_table'));

            for ($i=0; $i < $headers->length; $i++) {
                $header = $headers->item($i);
                $modelsFull[$i] = (new Catalog\ModelFull)
                    ->setParent($model)
                    ->setName(trim($header->nodeValue))
                ;
                $this->manager->persist($modelsFull[$i]);
                $output->write("  <comment>".(new \DateTime('now'))->format('H:i:s')."</comment>  <info>[{$modelsFull[$i]->getParent()->getParent()->getName()}] - [{$modelsFull[$i]->getParent()->getName()}] - {$modelsFull[$i]->getName()}</info> --> write to DB\n");

                $content = $contents->item($i);
                $rows = $content->getElementsByTagName('tr');
                foreach ($rows as $key=>$row) {
                    if ($key > 0) {
                        $columns = $row->getElementsByTagName('td');
                        for ($j=0; $j < $columns->length; $j++) {
                            $column[$j] = $columns->item($j);
                        }

                        $details = (new Catalog\Details)
                            ->setParent($modelsFull[$i])
                            ->setName(trim($column[0]->nodeValue))
                            ->setNumberOfDoors((int) (trim($column[1]->nodeValue)) ? (int) trim($column[1]->nodeValue) : null)
                            ->setEngine((int) (trim($column[2]->nodeValue)) ? (int) trim($column[2]->nodeValue) : null)
                            ->setPower((int) (trim($column[3]->nodeValue)) ? (int) trim($column[3]->nodeValue) : null)
                            ->setFullSpeed((int) (trim($column[4]->nodeValue)) ? (int) trim($column[4]->nodeValue) : null)
                            ->setBodyType((int) (trim($column[5]->nodeValue)) ? trim($column[5]->nodeValue) : null)
                            ->setStartOfProduction(trim($column[6]->nodeValue))
                            ->setClosingOfProduction(trim($column[7]->nodeValue))
                        ;

                        $this->manager->persist($details);
                    }
                }
                $this->manager->flush();
            }
        }
    }
}
