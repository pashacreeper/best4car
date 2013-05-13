<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\CssSelector\CssSelector;
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
        $this->parseModification($output, $models);

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
                ->setUri(self::URI_PRIMARY . trim($node->getAttribute('href')))
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
            $catalog->loadHTMLFile($mark->getUri());

            $nodes = new DOMXPath($catalog);
            foreach ($nodes->query(CssSelector::toXPath('div.cars_list_column > div > a')) as $node) {
                $models[$mark->getName() . " - " . $node->nodeValue] = (new Catalog\Model)
                    ->setParent($mark)
                    ->setName(trim($node->nodeValue))
                    ->setUri(self::URI_PRIMARY . trim($node->getAttribute('href')))
                ;
                $this->manager->persist($models[$mark->getName() . " - " . $node->nodeValue]);
                $output->write("  <comment>".(new \DateTime('now'))->format('H:i:s')."</comment>  <info>[{$mark->getName()}] - {$node->nodeValue}</info> --> write to DB\n");
            }

            $this->manager->flush();
        }

        return $models;
    }

    public function parseModification(OutputInterface $output, $models)
    {
        libxml_use_internal_errors(true);
        foreach ($models as $model) {
            $catalog = new \DOMDocument;
            $catalog->loadHTMLFile($model->getUri());

            $nodes = new DOMXPath($catalog);
            foreach ($nodes->query(CssSelector::toXPath('div.cars_list > table.cars_list_table > tr')) as $key => $node) {
                if ($node->getAttribute('class') == "header") {
                    continue;
                } else {
                    $columns = $node->getElementsByTagName('td');
                    for ($j=0; $j < $columns->length; $j++) {
                        $column[$j] = $columns->item($j);
                    }

                    $v = $column[0]->getElementsByTagName('a');
                    if ($v->length) {
                        $modofication = (new Catalog\Modification)
                            ->setParent($model)
                            ->setName(trim($v->item(0)->nodeValue))
                            ->setUri(self::URI_PRIMARY . trim($v->item(0)->getAttribute('href')))
                            ->setNumberOfDoors((int) trim($column[1]->nodeValue) ? (int) trim($column[1]->nodeValue) : null)
                            ->setEngine((int) trim($column[2]->nodeValue) ? (int) trim($column[2]->nodeValue) : null)
                            ->setPower((int) trim($column[3]->nodeValue) ? (int) trim($column[3]->nodeValue) : null)
                            ->setFullSpeed((int) trim($column[4]->nodeValue) ? (int) trim($column[4]->nodeValue) : null)
                            ->setBodyType(((int) trim($column[5]->nodeValue) or strlen(trim($column[5]->nodeValue))) ? trim($column[5]->nodeValue) : null)
                            ->setStartOfProduction((int) (trim($column[6]->nodeValue)) ? trim($column[6]->nodeValue) : null)
                            ->setClosingOfProduction((int) (trim($column[7]->nodeValue)) ? trim($column[7]->nodeValue) : null)
                        ;

                        $this->manager->persist($modofication);
                        $output->write("  <comment>".(new \DateTime('now'))->format('H:i:s')."</comment>  <info>[{$model->getParent()->getName()}] - [{$model->getName()}] - {$modofication->getName()}</info> - <question>With detail</question> --> write to DB\n");
                    } else {
                        $v = $column[0]->getElementsByTagName('span');

                        $modofication = (new Catalog\Modification)
                            ->setParent($model)
                            ->setName(trim($v->item(0)->nodeValue))
                            ->setNumberOfDoors((int) trim($column[1]->nodeValue) ? (int) trim($column[1]->nodeValue) : null)
                            ->setEngine((int) trim($column[2]->nodeValue) ? (int) trim($column[2]->nodeValue) : null)
                            ->setPower((int) trim($column[3]->nodeValue) ? (int) trim($column[3]->nodeValue) : null)
                            ->setFullSpeed((int) trim($column[4]->nodeValue) ? (int) trim($column[4]->nodeValue) : null)
                            ->setBodyType(((int) trim($column[5]->nodeValue) or strlen(trim($column[5]->nodeValue))) ? trim($column[5]->nodeValue) : null)
                            ->setStartOfProduction((int) (trim($column[6]->nodeValue)) ? trim($column[6]->nodeValue) : null)
                            ->setClosingOfProduction((int) (trim($column[7]->nodeValue)) ? trim($column[7]->nodeValue) : null)
                        ;

                        $this->manager->persist($modofication);
                        $output->write("  <comment>".(new \DateTime('now'))->format('H:i:s')."</comment>  <info>[{$model->getParent()->getName()}] - [{$model->getName()}] - {$modofication->getName()}</info> - <error>Without detail</error> --> write to DB\n");
                    }
                }

                $this->manager->flush();
            }
        }
    }
}
