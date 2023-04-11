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
