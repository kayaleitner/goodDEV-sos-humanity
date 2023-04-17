<?php

function getRelatedPosts()
{
    $post = get_post();

    $related_posts = relevanssi_related_posts($post_id);

    if (empty($related_posts)) {
        return array();
    }

    return $related_posts;
}
