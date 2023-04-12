<?php

namespace Flynt\Components\BlockInsightSidebar;

use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockInsightSidebar', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});
