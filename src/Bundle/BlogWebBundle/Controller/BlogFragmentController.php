<?php

declare(strict_types=1);

namespace Matok\Bundle\BlogWebBundle\Controller;

use Matok\Bundle\BlogWebBundle\Entity\Article;
use Matok\Bundle\BlogWebBundle\Form\Type\SearchType;
use Matok\Bundle\BlogWebBundle\Repository\ArticleStatRepository;
use Matok\Bundle\BlogWebBundle\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BlogFragmentController extends AbstractController
{
    public function searchForm(Request $request)
    {
        $searchForm = $this->createForm(SearchType::class, null, ['csrf_protection' => false]);

        return $this->render('@blog/Fragment/search_form.html.twig', [
                'search_form' => $searchForm->createView(),
            ]
        );
    }

    public function tags(Request $request, TagRepository $tagRepository)
    {
        return $this->render('@blog/Fragment/tags.html.twig', [
                'tags' => $tagRepository->getTags()
            ]
        );
    }

    public function newestArticles(Request $request)
    {
        return $this->render('@blog/Fragment/newest_articles.html.twig', [
                'articles' => $this->getDoctrine()->getRepository(Article::class)->getNewestArticles(),
            ]
        );
    }

    public function stats(Request $request, ArticleStatRepository $repository)
    {
        $blogBegins = new \DateTime('2017-06-13');
        $diff = $blogBegins->diff(new \DateTime());

        return $this->render('@blog/Fragment/stats.html.twig', [
                'article_count' => $repository->getPublishedArticles(),
                'up_days' => $diff->days,
            ]
        );
    }
}