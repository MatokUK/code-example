<?php

namespace Matok\Bundle\BlogWebBundle\Media;

class ArticleMedia
{
    public static function grabImageIds($articles)
    {
        $result = array();
        foreach ($articles as $article) {
            $result[$article['article_id']] = $article['top_image_id'];
        }

        return $result;
    }
}