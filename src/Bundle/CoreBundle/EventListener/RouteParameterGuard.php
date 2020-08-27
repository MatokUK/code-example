<?php

namespace Matok\Bundle\CoreBundle\EventListener;

use Matok\Bundle\CoreBundle\RequestGuard\GuardedController;
use Matok\Bundle\CoreBundle\RequestGuard\RequestGuard;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Routing\RouterInterface;

class RouteParameterGuard
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $reflection = new \ReflectionClass($event->getController()[0]);
        $routeName = $event->getRequest()->attributes->get('_route');
        if ($reflection->implementsInterface(GuardedController::class)) {
            $config = $reflection->getProperty('guardConfig');

            try {
                $guard = new RequestGuard($this->router);
                $guard->checkArguments($event->getRequest());
            } catch (\Exception $exception) {
                $arguments = $event->getArguments();
                $arguments[count($arguments) - 1] = $guard->getRedirectResponse();
                $event->setArguments($arguments);
            }
        }

    }
}