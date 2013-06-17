<?php
namespace Sto\ContentBundle\EventListener;

use Sto\ContentBundle\Controller\ChoiceCityController;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ChoiceCityListener
{

    public function onKernelController(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $controllers = $event->getController();
            if (isset($controllers[0]) && $controllers[0] instanceof ChoiceCityController) {
                $controller = $controllers[0];
                if (method_exists($controller, "preExecute")) {
                    $controller->preExecute();
                }
            }
        }
    }
}
