<?php

namespace Sto\ContentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends Controller
{
    /**
     * @Route("/sitemap.{_format}", name="sitemap", requirements={"_format" = "xml"})
     * @Template("StoContentBundle:Sitemap:index.xml.twig")
     * @Cache(expires="tomorrow", public=true)
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $staticPages = ['about', 'advertisers' , 'business', 'tour', 'contact', 'useRules'];

        $companies = $em->getRepository('StoCoreBundle:Company')
            ->getVisibleCompanies();
        $deals = $em->getRepository('StoCoreBundle:Deal')->getAllActiveDeals();

        return [
            'staticPages' => $staticPages,
            'companies' => $companies,
            'deals' => $deals,
            'now' => (new \DateTime('now'))->format('Y-m-d')
        ];
    }
}
