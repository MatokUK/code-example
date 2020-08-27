<?php

declare(strict_type=1);

namespace Matok\Bundle\BlogWebBundle\Controller;

use Matok\BBCode\Parser;
use Matok\Bundle\BlogWebBundle\BBConverter\ArticleStat;
use Matok\Bundle\BlogWebBundle\Entity\Article;
use Matok\Bundle\BlogWebBundle\Entity\ArticleStatus;
use Matok\Bundle\BlogWebBundle\Repository\ArticleRepository;
use Matok\Bundle\BlogWebBundle\Repository\BlogRepository;
use Matok\Bundle\BlogWebBundle\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Matok\Bundle\BlogWebBundle\Media\ArticleMedia;

class ArticleController extends AbstractController
{
    public function detail(Request $request, $articleId, string $slug, TagRepository $tagRepository): Response
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->getPublishedArticle($articleId);

        $this->checkArticle($article);

        $redirect = $this->checkArticleSlug($slug, $article);
        if ($redirect instanceof RedirectResponse) {
            return $redirect;
        }

        $tags = $article->getTags2();
       // dump($tags[0]->getTag());

        //$tags = $tagRepository->getArticleTags($articleId);

      //  dump($article);

        /*$parser = new Parser($article->getContent());

        $statConverter = new ArticleStat();
        $parser->registerConverter($statConverter);
        $parsedCode = $parser->parse();
        dump($parsedCode);*/

        return $this->render('@BlogWeb/Article/detail.html.twig', [
            'article' => $article,
            'primary_tag' => $tags ?? $tags[0]->getTag(),
            'tags' => $tags,
            'serie' =>  $this->getDoctrine()->getRepository(Article::class)->findSerieArticles($article),
        ]);
    }

    private function checkArticle($article)
    {
        if (empty($article)) {
            throw new NotFoundHttpException('Article not found!');
        }
    }

    private function checkArticleSlug(string $realSlug, Article $article): ?RedirectResponse
    {
        if ($article->getSlug() !== $realSlug) {
            return new RedirectResponse($this->generateUrl('blog_article', ['slug' => $article->getSlug(), 'articleId' => $article->getArticleId()]));
        }
    }
}
