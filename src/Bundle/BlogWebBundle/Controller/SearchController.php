<?php

declare(strict_types=1);

namespace Matok\Bundle\BlogWebBundle\Controller;

use Matok\Bundle\BlogWebBundle\Repository\ArticleRepository;
use Matok\Bundle\CoreBundle\RequestGuard\GuardedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController implements GuardedController
{
    public function search(string $search, ArticleRepository $repository, RedirectResponse $guardRedirect = null): Response
    {
        if (null !== $guardRedirect) {
            return $guardRedirect;
        }

        $articles = $repository->searchArticles($search);

        return $this->render('@blog/ListArticle/search_result.html.twig', [
            'articles' => $articles,
            'search' => $search,
        ]);
    }
}