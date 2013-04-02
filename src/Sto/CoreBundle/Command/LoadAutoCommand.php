<?php
namespace Sto\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface;
use Sto\CoreBundle\Entity\AutoCatalog,
    Sto\CoreBundle\Entity\AutoCatalogCar,
    Sto\CoreBundle\Entity\AutoCatalogItem;



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
        $aCatalog = $this->parseMainPage($output, $mark);
        $iMarks = 0; $iModel = 0; $iBody = 0; $iKompl = 0;
        $output->writeln('Parsing finished.');
        if (count($aCatalog)>0)
            $output->writeln('Importing data...');
        else
            $output->writeln('No data found');

        foreach ($aCatalog as $sMark => $aModels) {
            $rs =$this->manager->getRepository('StoCoreBundle:AutoCatalogCar')->findOneByName($sMark);
            if ($rs) {
                $oMark=$rs;
                $output->writeln('Mark: '. $sMark.' was in database');
            } else {
                $oMark = new AutoCatalogCar;
                $oMark->setName($sMark);
                $this->manager->persist($oMark);
                $output->writeln('Mark: '.$sMark.' added');
                $iMarks++;
            }
            foreach ($aModels as $sModel => $aBodies) {
                $rs =$this->manager->getRepository('StoCoreBundle:AutoCatalogCar')->findOneByName($sModel);
                if ($rs) {
                    $oModel = $rs;
                    $output->writeln('   Model: '. $sModel.' was in database');
                } else {
                    $oModel = new AutoCatalogCar;
                    $oModel->setName($sModel);
                    $oModel->setParent($oMark);
                    $this->manager->persist($oModel);
                    $output->writeln('   '.$sMark.' '.$sModel.' added');
                    $iModel++;
                }
                foreach ($aBodies as  $sBody => $aKomplect) {
                    $rs =$this->manager->getRepository('StoCoreBundle:AutoCatalogCar')->findOneByName($sBody);
                    if ($rs){
                        $oBody = $rs;
                        $output->writeln('      Body: '.$sBody.' was in database');
                    }
                    else {
                        $oBody = new AutoCatalogCar;
                        $oBody->setName($sBody);
                        $oBody->setParent($oModel);
                        $this->manager->persist($oBody);
                        $iBody++;
                    }
                    foreach ($aKomplect as $sKompl => $aParams) {
                        $rs = $this->manager->getRepository('StoCoreBundle:AutoCatalogItem')->findOneByName($sKompl);
                        if ($rs){
                            $oKompl = $rs;
                            $output->writeln('         Komplect: '.$sKompl.' was in database');
                        }
                        else {
                            $oKompl = new AutoCatalogItem;
                            $oKompl->setName($sKompl);
                            $oKompl->setParent($oBody);
                            $oKompl->setBodyType($aParams['bodyType']);
                            $oKompl->setEngineVolume($aParams['engineVolume']);
                            $oKompl->setPower($aParams['power']);
                            $oKompl->setPrivod($aParams['privod']);
                            $oKompl->setTransmission($aParams['transmission']);
                            $oKompl->setTransmissionCount($aParams['transmissionCount']);
                            $oKompl->setStartProduction($aParams['startProduction']);
                            $oKompl->setEndProduction($aParams['endProduction']);
                            $this->manager->persist($oKompl);
                            $iKompl++;
                        }
                    }
                }
            }
        }
        $this->manager->flush();

       $output->writeln('Saved: Marks: '.$iMarks.'; Models: '.$iModel.'; Bodies: '.$iBody).'; Komplects: '.$iKompl;
    }

    private function parseMainPage(OutputInterface $output, $str = '')
    {
        $page_content = file_get_contents('http://avtomedia.ru/board/catalog/'); //данная функция может не работать если на другом сервере стоит защита.)
        if ($str!='')
            preg_match_all('/<A href="(\/board\/models\/.*?.htm)">('.$str.')<\/A>/', $page_content, $marks);
        else
            preg_match_all('/<A href="(\/board\/models\/.*?.htm)">(.*?)<\/A>/', $page_content, $marks);

        $auto = array();

        foreach ($marks[2] as $key=>$mark) {
            $output->writeln('Scanning mark '.iconv('windows-1251', 'UTF-8', $mark).'...');
            if ($str!='') {
                if ($str==$mark)
                    $auto[iconv('windows-1251', 'UTF-8', $mark)] = $this->getModel($output, $marks[1][$key]);
            } else
                $auto[iconv('windows-1251', 'UTF-8', $mark)] = $this->getModel($output, $marks[1][$key]);
        }

        return $auto;
    }

    private function getModel(OutputInterface $output, $link)
    {
        $page_content = file_get_contents('http://avtomedia.ru'.$link); //данная функция может не работать если на другом сервере стоит защита.)
        preg_match_all('/<A href="(\/board\/items\/.*?.htm)">(.*?)<\/A>/', $page_content, $models);
        $auto = array();

        foreach ($models[2] as $key => $one) {
            $output->writeln('Scanning model '.iconv('windows-1251', 'UTF-8', $one).'...');
            $auto[iconv('windows-1251', 'UTF-8', $one)] = $this->getBody($output, $models[1][$key]);
        }

        return $auto;
    }

    private function getBody(OutputInterface $output, $link)
    {
        $page_content = file_get_contents('http://avtomedia.ru'.$link); //данная функция может не работать если на другом сервере стоит защита.)

        preg_match_all('/<A href="(\/board\/item\/.*?.htm)">(.*?)<\/A>/', $page_content, $years);

        $auto = array();
        foreach ($years[1] as $key => $value) {
            $page_content2 = file_get_contents('http://avtomedia.ru'.$years[1][$key]);
            preg_match_all('/<a href="\/board\/items\/.*?.htm"> (.*?)<\/a>\/ (.*?)\n<\/span><\/div>/', $page_content2, $res);

            // Регулярные выражения для параметров автомобиля


            $page_content2 = str_replace(array("\n", "\r"), array("",""), $page_content2);

            $reg = iconv('UTF-8', 'windows-1251', 'Тип кузова');
            preg_match_all('/<TD><strong>'.$reg.'<\/strong><\/TD><TD>(.*?)<\/TD>/', $page_content2, $bodyType);
            $reg = iconv('UTF-8', 'windows-1251', 'Объем двигателя');
            preg_match_all('/<TD><strong>'.$reg.'<\/strong><\/TD><TD>(.*?)<\/TD>/', $page_content2, $engineVolume);
            $reg = iconv('UTF-8', 'windows-1251', 'Мощность');
            preg_match_all('/<TD><strong>'.$reg.'<\/strong><\/TD><TD>(.*?)<\/TD>/', $page_content2, $power);
            $reg = iconv('UTF-8', 'windows-1251', 'Привод');
            preg_match_all('/<TD><strong>'.$reg.'<\/strong><\/TD><TD>(.*?)<\/TD>/', $page_content2, $privod);
            $reg = iconv('UTF-8', 'windows-1251', 'Кол-во передач (механика)');
            preg_match_all('/<TD><strong>'.$reg.'<\/strong><\/TD><TD>(.*?)<\/TD>/', $page_content2, $transmissionM);
            $reg = iconv('UTF-8', 'windows-1251', 'Кол-во передач (автомат)');
            preg_match_all('/<TD><strong>'.$reg.'<\/strong><\/TD><TD>(.*?)<\/TD>/', $page_content2, $transmissionA);
            $reg = iconv('UTF-8', 'windows-1251', 'Начало выпуска');
            preg_match_all('/<TD><strong>'.$reg.'<\/strong><\/TD><TD>(.*?)<\/TD>/', $page_content2, $startProduction);
            $reg = iconv('UTF-8', 'windows-1251', 'Оконч. выпуска');
            preg_match_all('/<TD><strong>'.$reg.'<\/strong><\/TD><TD>(.*?)<\/TD>/', $page_content2, $endProduction);

            $params = [
                'bodyType' => iconv('windows-1251', 'UTF-8', $bodyType[1][0]),
                'power' => iconv('windows-1251', 'UTF-8', $power[1][0]),
                'privod' => iconv('windows-1251', 'UTF-8', $privod[1][0]),
                'transmission' => '',
                'transmissionCount' => '',
                'startProduction' => iconv('windows-1251', 'UTF-8', $startProduction[1][0]),
                'endProduction' => iconv('windows-1251', 'UTF-8', $endProduction[1][0]),
                'engineVolume' => iconv('windows-1251', 'UTF-8', $engineVolume[1][0]),
            ];

            if (isset($transmissionA[1][0]) && $transmissionA[1][0] != '-'){
                $params['transmission'] = 'Автомат';
                $params['transmissionCount'] = $transmissionA[1][0];
            }
            elseif (isset($transmissionM[1][0]) && $transmissionM[1][0] != '-') {
                $params['transmission'] = 'Механика';
                $params['transmissionCount'] = $transmissionM[1][0];
            }


            if (isset($res[1][0]) && isset($res[2][0])){
                $output->writeln('Scanning '.iconv('windows-1251', 'UTF-8', $res[1][0]).' and komplect '. iconv('windows-1251', 'UTF-8', $res[2][0]).'...');

                $auto[iconv('windows-1251', 'UTF-8', $res[1][0])][iconv('windows-1251', 'UTF-8', $res[2][0])] = $params;
            }
        }

        return $auto;
    }
}
