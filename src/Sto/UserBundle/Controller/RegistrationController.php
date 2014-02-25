<?php
namespace Sto\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sto\UserBundle\Entity\Group;

class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        $registration_type = $request->get('type');
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        // Установка Рейтинговой группы пользователя
        $ratingGroupService = $this->container->get('rating_group');
        $ratingGroup = $ratingGroupService->getRatingGroupByRating($user->getRating());
        $user->setRatingGroup($ratingGroup);
        // end

        $user->addRole('ROLE_USER');
        $user->setEnabled(true);

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, new UserEvent($user, $request));

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                if (null === $response = $event->getResponse()) {
                    if ($registration_type != 'company') {
                        $group = $this->container->get('doctrine')->getManager()->getRepository('StoUserBundle:Group')->find(Group::USER);
                        $user->setGroups([$group]);
                    }
                }

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    if ($registration_type != 'company') {
                        $url = $this->container->get('router')->generate('content_companies');
                        $response = new Response('redirect');
                    } else {
                        $url = $this->container->get('router')->generate('registration_company_owner');
                        $response = new RedirectResponse($url);
                    }
                }

                $this->container->get('sto.notifications.email')->sendRegistrationEmail($user);

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }

    public function registerCompanyAction(Request $request)
    {
        $registration_type = $request->get('type');

        $formFactory = $this->container->get('fos_user.registration.form.factory');
        $userManager = $this->container->get('fos_user.user_manager');
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $user->setRatingGroupId(1);
        $user->addRole('ROLE_MANAGER');

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, new UserEvent($user, $request));

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    if ($registration_type!='company') {
                        $url = $this->container->get('router')->generate('content_companies');
                        $response = new RedirectResponse($url);
                    } else {
                        $url = $this->container->get('router')->generate('registration_company_owner');
                        $response = new RedirectResponse($url);
                    }
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->container->get('templating')->renderResponse('StoUserBundle:Registration:registration.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }
}
