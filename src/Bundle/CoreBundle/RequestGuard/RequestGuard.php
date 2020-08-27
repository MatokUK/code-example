<?php

namespace Matok\Bundle\CoreBundle\RequestGuard;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class RequestGuard
{
    private $redirectUrl;

    /** @var RouterInterface */
    private $router;

    private $isRegExp = false;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getRedirectResponse()
    {
        if ($this->redirectUrl) {
            return new RedirectResponse($this->redirectUrl);
        }
    }

    public function checkArguments(Request $request, $allowed = [])
    {
        $allowed = $this->normalizeAllowedParameters($allowed);
        $requestQueryParameters = array_keys($request->query->all());
        $this->redirectUrl = $this->router->generate($request->get('_route'), $request->get('_route_params'));

        foreach ($requestQueryParameters as $requestQueryParameter) {
            if (!isset($allowed[$requestQueryParameter])) {
                throw new \Exception(sprintf('Parameter %s is not allowed in URL.', $requestQueryParameter));
            }

            $this->checkRegExpConstraint($request->query->get($requestQueryParameter), $allowed[$requestQueryParameter]);
        }
    }

    private function checkRegExpConstraint($value, $regExp)
    {
        if ($this->isRegExp && !preg_match($regExp, $value)) {
            throw new \Exception(sprintf('Parameter value %s violates regular expression constraint.', $value));
        }
    }

    private function normalizeAllowedParameters($allowed)
    {
        if (is_scalar($allowed)) {
            return [$allowed => $allowed];
        }

        return $this->getKeysAndValues($allowed);
    }

    private function getKeysAndValues($params)
    {
        $keys = array_keys($params);
        $values = array_values($params);

        if (isset($keys[0]) && is_numeric($keys[0])) {
            $keys = $values;
        } else {
            $this->isRegExp = true;
        }

        return array_combine($keys, $values);
    }
}