<?php

declare(strict_types=1);

namespace Matok\Bundle\BlogWebBundle\Controller;

use Matok\Bundle\CoreBundle\RequestGuard\GuardedController;
use Matok\Bundle\CoreBundle\RequestGuard\RequestGuard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Matok\Bundle\BlogWebBundle\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends AbstractController implements GuardedController
{
    public function contact(Request $request, RedirectResponse $guardRedirect = null): Response
    {
        if (null !== $guardRedirect) {
            return $guardRedirect;
        }

        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $data = $contactForm->getData();
            $repository = $this->get('blog_web.repository.contact');
            $repository->storeContactForm($data['email'], $data['subject'], $data['message'], $request->getClientIp());

            $this->addFlash('success', sprintf('Thank you for feedback. I try to reach you on email %s', $data['email']));

            return $this->redirectToRoute($request->attributes->get('_route'));
        }

        return $this->render('@BlogWeb/Contact/contact.html.twig', array(
            'contact_form' => $contactForm->createView(),
        ));
    }

    public function hire(RedirectResponse $guardRedirect = null): Response
    {
        if (null !== $guardRedirect) {
            return $guardRedirect;
        }

        return $this->render('@BlogWeb/Contact/hire.html.twig');
    }

    public function curriculum(Request $request, RequestGuard $requestGuard): Response
    {
        try {
            $requestGuard->checkArguments($request, ['ref']);
        } catch (\Exception $exception) {
            $redirectUrl = $requestGuard->getRedirectResponse();
            return $this->redirect($redirectUrl);
        }

        return $this->render('@BlogWeb/Contact/curriculum.html.twig', [
            'show_salary' => $request->query->get('ref') == 'linked'
        ]);
    }
}