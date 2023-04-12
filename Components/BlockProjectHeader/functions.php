<?php

namespace Flynt\Components\BlockProjectHeader;

use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=BlockProjectHeader', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});
