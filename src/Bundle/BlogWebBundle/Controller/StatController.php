<?php
namespace Matok\Bundle\BlogWebBundle\Controller;

use Matok\Bundle\BlogWebBundle\Repository\ArticleRepository;
use Matok\Bundle\BlogWebBundle\Repository\ArticleStatRepository;
use Matok\Bundle\BlogWebBundle\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StatController extends Controller
{
    public function usefulAction(Request $request, $articleId)
    {
        $blogRepository = $this->get('blog_web.repository.article');
        $statRepository = $this->get('blog_web.repository.stat');
        $article = $blogRepository->getArticle($articleId);

        if ($article) {
            $statRepository->articleWasHelpful($articleId, $request->getClientIp());
            return new JsonResponse(1);
        }

        return new JsonResponse(0);
    }

    public function articleWasDisplayedAction(Request $request, int $articleId, ArticleStatRepository $statRepository, ArticleRepository $articleRepository)
    {
        if (empty($request->query->get('tm'))) {
            return new JsonResponse(0);
        }

        $article = $articleRepository->find($articleId);

        if (null !== $article) {
            $statRepository->articleWasDisplayed($articleId, $request->getClientIp());

            return new JsonResponse(1);
        }

        return new JsonResponse(0);
    }
}