<?php

namespace Sto\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture,
    Doctrine\Common\DataFixtures\OrderedFixtureInterface,
    Doctrine\Common\Persistence\ObjectManager;
use Sto\CoreBundle\Entity\AutoCatalog;

class LoadAutoCatalogData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $aCatalog = [
            "Alfa Romeo" => [
                "145" => [
                "145 930 1.9 JTD 1999 г.в.", "145 930 1.7 i I4 16V 1998 г.в.", "145 930 2.0 I4 16V Quadrifoglio 1998 г.в.", "145 930 1.4 i I4 16V T.S. 1996 г.в.", "145 930 1.6 i I4 16V T.S. 1996 г.в.", "145 930 2.0 I4 16V T.S. 1995 г.в.", "145 930 1.7 i H4 16V 1994 г.в.", "145 930 1.6 i I4 8V 1994 г.в.", "145 930 1.9 I4 8V TD 1994 г.в.", "145 930 1.4 i I4 8V 1994 г.в.",], "146" => [
                "146 930 2.0 I4 16V Qudrofoglio 1998 г.в.", "146 930 1.7 i I4 16V T.S. 1998 г.в.", "146 930 1.4 i I4 16V T.Spark 1997 г.в.", "146 930 1.6 i I4 16V T.S. 1996 г.в.", "146 930 1.7 i 16V T.S. 1996 г.в.", "146 930 2.0 I4 16V T.S. 1995 г.в.", "146 930 1.9 TD 1994 г.в.", "146 930 1.7 i H4 8V 1994 г.в.", "146 930 1.6 i H4 8V 1994 г.в.", "146 930 1.4 i H4 8V 1994 г.в.",], "147" => [
                "147 5-door 1.9 JTDm 2007 г.в.", "147 5-door 1.6 T.Spark 16V 2007 г.в.", "147 5-door 1.6 T.Spark 16V Veloce 2007 г.в.", "147 5-door 2.0 T.Spark 16V 2007 г.в.", "147 3-door 2.0 T.Spark 16V 2007 г.в.", "147 3-door 1.9 JTDm 16V 2007 г.в.", "147 3-door 1.6 T.Spark 16V Veloce 2007 г.в.", "147 3-door 1.6 T.Spark 16V 2007 г.в.", "147 3-door 1.9 JTD 120 2006 г.в.", "147 5-door 1.9 JTD 120 2006 г.в.", "147 3-door 1.6 T.Spark 16V 2005 г.в.", "147 3-door 1.6 T.Spark 16V Veloce 2005 г.в.", "147 3-door 1.9 JTD 100 2005 г.в.", "147 3-door 1.9 JTD 115 2005 г.в.", "147 3-door 1.9 JTD 16V 2005 г.в.", "147 5-door 1.9 JTD 16V 2005 г.в.", "147 3-door 2.0 T.Spark 16V 2005 г.в.", "147 5-door 1.9 JTD 100 2005 г.в.", "147 5-door 1.9 JTD 115 2005 г.в.", "147 5-door 2.0 T.Spark 16V 2005 г.в.", "147 5-door 1.6 T.Spark 16V Veloce 2005 г.в.", "147 5-door 1.6 T.Spark 16V 2005 г.в.", "147 5 d 1.9 JTD 16V 2003 г.в.", "147 5 d 1.9 JTD 100 2003 г.в.", "147 1.9 I4 16V JTD 2002 г.в.", "147 GTA 3.2 i V6 24V 2002 г.в.", "147 5 d 1.6 T.Spark 16V 2001 г.в.", "147 5 d 1.6 T.Spark 16V Veloce 2001 г.в.", "147 5 d 2.0 T.Spark 16V 2001 г.в.", "147 5 d 2.0 T.Spark 16V Selespeed 2001 г.в.", "147 5 d 1.9 JTD 115 2001 г.в.", "147 2.0 i I4 16V T.Spark 2000 г.в.", "147 1.9 I4 8V JTD 2000 г.в.", "147 1.6 i I4 16V T.Spark 2000 г.в.", "147 1.6 i I4 16V 2000 г.в.",], "155" => [
                "155 167 1.6 I4 16V T.S. 1996 г.в.", "155 167 1.8 I4 16V T.Spark 1996 г.в.", "155 2.0 I4 16V T.S. (A2G) 1995 г.в.", "155 1.7 I4 16V T.S. (A4D,A4H) 1993 г.в.", "155 2.5 I4 8V TD (A1A) 1993 г.в.", "155 1.9 I4 8V TD (A3) 1993 г.в.", "155 2.0 I4 8V T.S. (A2A) 1992 г.в.", "155 167 2.0 I4 16V Q4 TD 1992 г.в.", "155 167 2.0 I4 16V Q4 TD 1992 г.в.", "155 167 1.8 T.Spark 1992 г.в.", "155 167 1.8 I4 8V T.Spark 1992 г.в.", "155 2.5 V6 12V (A1) 1992 г.в.",], "164" => [
                "164 3.0 24V Q4 (K1M,K1C) 1994 г.в.", "164 3.0 V6 (H1A,H1B,K1) 1992 г.в.", "164 3.0 24V QV (H1) 1992 г.в.", "164 3.0 24V (K1) 1992 г.в.", "164 2.5 TD (K2A,K2B) 1992 г.в.", "164 2.0 V6 Turbo (K3) 1992 г.в.", "164 2.0 T.S. (H3) 1992 г.в.", "164 2.0 V6 Turbo (A2G,A2F) 1991 г.в.", "164 3.0 i V6 24V 1991 г.в.", "164 3.0 i QV (AG) 1990 г.в.", "164 2.5 TD (A1A) 1989 г.в.", "164 3.0 V6 (AD,AH,AB) 1987 г.в.", "164 2.0 T.S. (A2C,A2L) 1987 г.в.", "164 2.0 T.S. (A2H) 1987 г.в.", "164 2.0 Turbo 1987 г.в.", "164 2.0 T.S. 1987 г.в.",], "166" => [
                "166 936 3.2 i V6 24V 2003 г.в.", "166 936 2.4 JTD 20V 2003 г.в.", "166 936 2.4 JTD 2002 г.в.", "166 936 2.5 i V6 24V 2001 г.в.", "166 936 2.4 JTD 2001 г.в.", "166 936 3.0 i V6 24V 2001 г.в.", "166 936 2.0 i 16V T.Spark 2001 г.в.", "166 936 3.0 i V6 24V 1998 г.в.", "166 936 2.5 i V6 24V 1998 г.в.", "166 936 2.0 i V6 1998 г.в.", "166 936 2.4 JTD 1998 г.в.", "166 936 2.0 i 16V T.Spark 1998 г.в.",], "75" => [
                "75 3.0 V6 (B6C) 1990 г.в.", "75 1.6 (B2B,B2C) 1990 г.в.", "75 1.8 (B1L,B1F) 1990 г.в.", "75 2.0 T.S. (B4) 1990 г.в.", "75 1.8 (B1H) 1989 г.в.", "75 1.8 (B1A,B1B) 1987 г.в.", "75 3.0 V6 (B6A) 1987 г.в.", "75 2.0 T.S (B4A) 1987 г.в.", "75 2.0 TD (BD,BG) 1986 г.в.", "75 1.6 (B2B,B2C) 1986 г.в.", "75 1.8 i Turbo (B1E) 1986 г.в.", "75 2.5 V6 (B3) 1985 г.в.", "75 2.0 (BA) 1985 г.в.",], "90" => [
                "90 2.0 (A1A) 1984 г.в.", "90 2.0 i (A2A,A2E) 1984 г.в.", "90 2.4 TD (B5) 1984 г.в.", "90 2.5 i V6 (A,AA) 1984 г.в.",], "Arna" => [
                "Arna 1.2 (AA) 1985 г.в.", "Arna 1.5 1985 г.в.",], "Brera" => [
                "Brera 2.2 2005 г.в.", "Brera 3.2 2005 г.в.", "Brera 2.4 JTDm 20v 2005 г.в.",], "GT" => [
                "GT Coupe 1.8 TS 2003 г.в.", "GT Coupe 1.9 JTD 2003 г.в.", "GT Coupe 2.0 JTS 2003 г.в.", "GT Coupe 3.2 V6 2003 г.в.",], "GTA" => [
                "GTA 1300 Junior 1968 г.в.",], "GTV" => [
                "GTV 916 2.0 JTS 2002 г.в.", "GTV 916 3.0 i V6 24V 2002 г.в.", "GTV 916 3.2 i V6 24V 2002 г.в.", "GTV 916 1.8 i 16V T.Spark 1998 г.в.", "GTV 916 3.0 i V6 24V 1997 г.в.", "GTV 916 2.0 i 16V T.Spark 1995 г.в.", "GTV 916 2.0 i V6 TB 1995 г.в.", "GTV 2.0 1981 г.в.", "GTV 2.5i V6 1980 г.в.",], "Kamal" => [
                "Kamal Concept '2003 2003 г.в.",], "Montreal" => [
                "Montreal (64) 2.6 1972 г.в.",], "Spider" => [
                "Spider 3.2 JTS V6 Q4 2006 г.в.", "Spider 2.2 MT High 2006 г.в.", "Spider 916 3.2 i V6 24V 2002 г.в.", "Spider 916 3.0 i V6 24V 2002 г.в.", "Spider 916 2.0 JTS 2002 г.в.", "Spider 916 2.0 i V6 TB 1998 г.в.", "Spider 916 2.0 i 16V T.Spark 1998 г.в.", "Spider 916 1.8 i 16V T.Spark 1998 г.в.", "Spider 916 2.0 i 16V T.Spark 1995 г.в.", "Spider 916 3.0 i V6 1995 г.в.", "Spider 2.0 (115) 1986 г.в.", "Spider 2.0 Veloce (115) 1977 г.в.", "Spider 1.6 (115) 1976 г.в.",],],
        ];

        foreach ($aCatalog as $sMark => $aModels) {
            $oMark = new AutoCatalog;
            $oMark->setName($sMark);
            $manager->persist($oMark);
            foreach ($aModels as $sModel => $aYears) {
                $oModel = new AutoCatalog;
                $oModel->setName($sModel);
                $oModel->setParent($oMark);
                $manager->persist($oModel);
                foreach ($aYears as  $sYear) {
                    $oYear = new AutoCatalog;
                    $oYear->setName($sYear);
                    $oYear->setParent($oModel);
                    $manager->persist($oYear);
                }
            }
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
