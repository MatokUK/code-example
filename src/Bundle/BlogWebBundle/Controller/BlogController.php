<?php

namespace Matok\Bundle\BlogWebBundle\Controller;

use Matok\Bundle\BlogWebBundle\Repository\ArticleRepository;
use Matok\Bundle\BlogWebBundle\Repository\TagRepository;
use Matok\Bundle\CoreBundle\RequestGuard\GuardedController;
use Matok\Bundle\CoreBundle\RequestGuard\RequestGuard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends AbstractController implements GuardedController
{
    public array $guardConfig = [
        'blog_homepage' => ['page' => '/^[1-9][0-9]*$/'],
    ];
   /* public function countdownAction(Request $request)
    {
        $dt = new \DateTime();
        $dtDeadline = new \DateTime('2017-07-09');
        $deadline = $dtDeadline->diff($dt);

        return $this->render('BlogWebBundle::countdown.html.twig', array(
            'deadline' => $deadline->d*24*60*60 + $deadline->h*60*60 + $deadline->i*60,
        ));
    }

    public function subscribeAction(Request $request)
    {
        $repository = $this->get('blog_web.repository.contact');
        $email = $request->request->get('email');

        $errorList = $this->get('validator')->validate($email, new Email());
        $result = $errorList->count() === 0;

        $repository->storeEmail($email, $request->getClientIp());

        return new JsonResponse(array('result' => $result));
    }*/

    public function homepage(Request $request, ArticleRepository $blogRepository, RequestGuard $requestGuard, RedirectResponse $guardRedirect = null)
    {
        try {
            $requestGuard->checkArguments($request, ['page' => '/^[1-9][0-9]*$/']);
        } catch (\Exception $exception) {
            $redirectUrl = $requestGuard->getRedirectResponse();

            return $this->redirect($redirectUrl);
        }

        $page = $request->query->getInt('page', 1);
        $articles = $blogRepository->getArticlesForHomepage($page, 10);
        $hasNextPage = isset($articles[10]);
        if ($hasNextPage) {
            unset($articles[10]);
        }



        return $this->render('@blog/Homepage/homepage.html.twig', [
            'articles' => $articles,
            'actual_page' => $page,
            'next_page' => $hasNextPage,
        ]);
    }

    /**
     * List articles of given tag
     */
    public function tagList(int $tagId, string $slug, TagRepository $repository, ArticleRepository $blogRepository, RedirectResponse $guardRedirect = null): Response
    {
        if (null !== $guardRedirect) {
            return $guardRedirect;
        }

        $tag = $repository->find($tagId);
        if (!$tag) {
            throw new NotFoundHttpException(sprintf('Tag with ID "%s" does not exits!', $tagId));
        }

        $redirect = $this->checkTagSlug($tag->getSlug(), $slug, $tag->getTagId());
        if ($redirect instanceof RedirectResponse) {
            return $redirect;
        }

        $articles = $blogRepository->getArticlesByTag($tagId);

        return $this->render('@BlogWeb/ListArticle/tag.html.twig', [
            'articles' => $articles,
            'tag' => $tag,
        ]);
    }

    private function checkTagSlug(string $expectedSlug, string $realSlug, int $tagId)
    {
        if ($expectedSlug !== $realSlug) {
            return new RedirectResponse($this->generateUrl('blog_list_by_tag', ['slug' => $expectedSlug, 'tagId' => $tagId]));
        }
    }
}