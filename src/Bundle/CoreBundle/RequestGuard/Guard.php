<?php

namespace Matok\Bundle\CoreBundle\RequestGuard;

use Symfony\Component\HttpFoundation\RedirectResponse;

trait Guard
{
    /** @var RedirectResponse */
    private $requestGuardRedirect;

    public function setRouteParamsViolatedRedirect(RedirectResponse $redirectResponse): void
    {
        $this->requestGuardRedirect = $redirectResponse;
    }

    public function hasRouteParamMismatch(): bool
    {
        return $this->requestGuardRedirect !== null;
    }

    public function getRedirect(): RedirectResponse
    {
        return $this->requestGuardRedirect;
    }
}