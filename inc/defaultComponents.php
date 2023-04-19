<?php

add_filter('acf/load_value/name=projectComponents', 'projects_default_components', 10, 3);
function projects_default_components($value, $post_id, $field)
{
    if ($value !== null) {
        return $value;
    }
    $value = array(
        array(
            'acf_fc_layout' => 'BlockCta',
        )
    );
    return $value;
}

add_filter('acf/load_value/name=relatedProjectComponents', 'related_projects_default_components', 10, 3);
function related_projects_default_components($value, $post_id, $field)
{
    if ($value !== null) {
        return $value;
    }
    $value = array(
        array(
            'acf_fc_layout' => 'RelatedProjects',
        )
    );
    return $value;
}
