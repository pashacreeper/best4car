<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface;
use Sto\CoreBundle\Entity\AutoCatalog;

class LoadAutoCommand extends ContainerAwareCommand
{
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output); //initialize parent class method

        $this->manager = $this->getContainer()->get('doctrine')->getEntityManager(); // This loads Doctrine, you can load your own services as well
    }

    protected function configure()
    {
        $this
            ->setName('sto:load:auto')
            ->setDescription('Load autocatalog')
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
        $mark = $input->getOption('mark');

        $output->write('Parsing data...');
        if ($mark != '')
            $output->writeln(' '.$mark);
        $mark = iconv('UTF-8', 'windows-1251', $mark);
        $aCatalog = $this->parseMainPage($mark);
        $iMarks = 0; $iModel = 0; $iYears = 0;
        $output->writeln('Parsing finished.');
        if (count($aCatalog)>0)
            $output->writeln('Importing data...');
        else
            $output->writeln('No data found');
        foreach ($aCatalog as $sMark => $aModels) {
            $rs =$this->manager->getRepository('StoCoreBundle:AutoCatalog')->findOneByName($sMark);
            if ($rs) {
                $oMark=$rs;
                $output->writeln('Mark: '. $sMark.' was in database');
            } else {
                $oMark = new AutoCatalog;
                $oMark->setName($sMark);
                $this->manager->persist($oMark);
                $output->writeln('Mark: '.$sMark.' added');
                $iMarks++;
            }
            foreach ($aModels as $sModel => $aYears) {
                $rs =$this->manager->getRepository('StoCoreBundle:AutoCatalog')->findOneByName($sModel);
                if ($rs) {
                    $oModel = $rs;
                    $output->writeln('   Model: '. $sModel.' was in database');
                } else {
                    $oModel = new AutoCatalog;
                    $oModel->setName($sModel);
                    $oModel->setParent($oMark);
                    $this->manager->persist($oModel);
                    $output->writeln('   '.$sMark.' '.$sModel.' added');
                    $iModel++;
                }
                foreach ($aYears as  $sYear) {
                    $rs =$this->manager->getRepository('StoCoreBundle:AutoCatalog')->findOneByName($sYear);
                    if ($rs)
                        $oYear = $rs;
                    else {
                        $oYear = new AutoCatalog;
                        $oYear->setName($sYear);
                        $oYear->setParent($oModel);
                        $this->manager->persist($oYear);
                        $iYears++;
                    }
                }
            }
            //$this->manager->flush();
        }
        $this->manager->flush();

       $output->writeln('Saved: Marks: '.$iMarks.'; Models: '.$iModel.'; Years: '.$iYears);
    }

    private function parseMainPage($str = '')
    {
        $page_content = file_get_contents('http://catalog.aw.by'); //данная функция может не работать если на другом сервере стоит защита.)
        if ($str!='')
            preg_match_all('/<a href="(.*?)"><b class="pop\d">('.$str.')<\/b> <\/a>/', $page_content, $marks);
        else
            preg_match_all('/<a href="(.*?)"><b class="pop\d">(.*?)<\/b> <\/a>/', $page_content, $marks);

        $auto = array();
        foreach ($marks[2] as $key=>$mark) {
            if ($str!='') {
                if ($str==$mark)
                    $auto[iconv('windows-1251', 'UTF-8', $mark)] = $this->getModel($marks[1][$key]);
            } else
                $auto[iconv('windows-1251', 'UTF-8', $mark)] = $this->getModel($marks[1][$key]);
        }

        return $auto;
    }

    private function getModel($link)
    {
        $page_content = file_get_contents($link); //данная функция может не работать если на другом сервере стоит защита.)
        preg_match_all('/<a href="(.*?)" class="[a-zA-z0-9,]*" title=\'[a-zA-z0-9]*\'><b>(.*?)<\/b> <\/a><br>/', $page_content, $models);
        $auto = array();

        foreach ($models[2] as $key => $one) {
            $auto[iconv('windows-1251', 'UTF-8', $one)] = $this->getYears($models[1][$key]);
        }

        return $auto;
    }

    private function getYears($link)
    {
        $page_content = file_get_contents($link); //данная функция может не работать если на другом сервере стоит защита.)

        preg_match_all('/<a href="http:\/\/catalog.aw.by\/\d*\/">\<b>\s*(.*?)<\/b>/', $page_content, $title);
        preg_match_all('/style="padding: 3px;"><a href="http:\/\/catalog.aw.by\/\d*\/">(.*?)<\/a>/', $page_content, $years);
        $auto = array();
        foreach ($title[1] as $key => $one) {
            $auto[] = iconv('windows-1251', 'UTF-8', $one) .' '. iconv('windows-1251', 'UTF-8', $years[1][$key]);
        }

        return $auto;
    }
}
