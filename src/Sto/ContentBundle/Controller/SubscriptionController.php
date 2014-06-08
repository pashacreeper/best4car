<?php

namespace Sto\ContentBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sto\ContentBundle\Form\Extension\ChoiceList\SubscriptionType;
use Sto\ContentBundle\Form\Type\CompanySubscriptionType;
use Sto\ContentBundle\Form\Type\DealSubscriptionType;
use Sto\ContentBundle\Form\FeedFilterType;
use Sto\CoreBundle\Entity\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SubscriptionController
 *
 * @package Sto\ContentBundle\Controller
 *
 * @Route("subscriptions")
 */
class SubscriptionController extends Controller
{
    /**
     * @Template
     * @Route("/", name="subscription_list", options={"expose"=true})
     * @Secure("ROLE_USER")
     */
    public function indexAction()
    {
        $request = $this->get('request');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $user->setFeedViewAt(new \DateTime());
        $em->flush();

        $form = $this->createForm(new FeedFilterType());
        $form->bind($request);

        $dealMarks = [];
        $companyMarks = [];

        if($marks = $form->get('marks')->getData()->toArray()) {
            $markIds = [];
            foreach ($marks as $mark) {
                $markIds[] = $mark->getId();
            }
            $companyMarks = $dealMarks = $markIds;
        } else {
            foreach ($user->getSubscriptions() as $subscription) {
                if($subscription->getType() == SubscriptionType::COMPANY) {
                    $companyMarks[] = $subscription->getMark()->getId();
                } else {
                    $dealMarks[] = $subscription->getMark()->getId();
                }
            }
        }

        $type = $marks = $form->get('type')->getData();
        if($type == SubscriptionType::COMPANY) {
            $dealMarks = [];
        }
        if($type == SubscriptionType::DEAL) {
            $companyMarks = [];
        }

        $query = $em->getRepository('StoCoreBundle:FeedItem')->getByMarks($dealMarks, $companyMarks);

        $page = $this->get('request')->query->get('page', 1);
        $items = $this->get('knp_paginator')->paginate($query, $page, 6);

        if($request->isXmlHttpRequest()) {
            $html = $this->renderView('StoContentBundle:Subscription:_list.html.twig', [
                'items' => $items,
                'dealMarks' => $dealMarks,
                'companyMarks' => $companyMarks,
            ]);

            return new JsonResponse([
                'success' => true,
                'html' => $html
            ]);
        }

        return [
            'user' => $user,
            'items' => $items,
            'dealMarks' => $dealMarks,
            'companyMarks' => $companyMarks,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Template
     * @Route("/manage", name="subscription_manage")
     * @Secure("ROLE_USER")
     */
    public function manageAction()
    {
        $em = $this->getDoctrine();
        $subscriptionRepository = $em->getRepository('StoCoreBundle:Subscription');

        $user = $this->getUser();

        $companySubscriptions = $subscriptionRepository->findBy(['type' => SubscriptionType::COMPANY, 'user' => $user]);
        $dealSubscriptions = $subscriptionRepository->findBy(['type' => SubscriptionType::DEAL, 'user' => $user]);

        return compact('user', 'companySubscriptions', 'dealSubscriptions');
    }

    /**
     * @Template
     * @Route("/store", name="subscription_store")
     */
    public function storeAction(Request $request)
    {
        $subscription = new Subscription();

        $type = $request->request->get('type');
        $typeClass = $this->getSubscriptionTypeClass($type);

        $form = $this->createForm($typeClass, $subscription, [
            'subscriptions' => null
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $subscription->setUser($this->getUser());

            $em->persist($subscription);
            $em->flush();

            $html = $this->renderView('StoContentBundle:Subscription:_listElement.html.twig', [
                'subscription' => $subscription
            ]);

            return new JsonResponse([
                'success' => true,
                'html' => $html
            ]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{id}/remove", name="subscription_remove", options={"expose"=true})
     */
    public function removeAction(Subscription $subscription)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($subscription);
        $em->flush();

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @Route("/subscribe_email", name="subscribe_email", options={"expose"=true})
     */
    public function subscribeEmailAction()
    {
        $em = $this->getDoctrine()->getManager();

        $request = $this->get('request');
        $user = $this->getUser();
        $user->setFeedNotify($request->get('value'));

        $em->flush();

        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @Template()
     * @param $subscriptions
     *
     * @return Response
     */
    public function renderCompanySubscriptionFormAction($subscriptions)
    {
        $form = $this->createForm(new CompanySubscriptionType(), null, ['subscriptions' => $subscriptions]);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Template()
     * @param $subscriptions
     *
     * @return Response
     */
    public function renderDealSubscriptionFormAction($subscriptions)
    {
        $form = $this->createForm(new DealSubscriptionType(), null, ['subscriptions' => $subscriptions]);

        return [
            'form' => $form->createView()
        ];
    }

    protected function getSubscriptionTypeClass($type)
    {
        $types = SubscriptionType::getTypeAndClass();

        if (! array_key_exists($type, $types)) {
            throw new \Exception('Wrong type of subscription provided');
        }

        return new $types[$type];
    }
}
