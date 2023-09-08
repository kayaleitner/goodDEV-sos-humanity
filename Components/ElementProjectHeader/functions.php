<?php

namespace Flynt\Components\ElementProjectHeader;

use Flynt\Utils\Options;

add_filter('Flynt/addComponentData?name=ElementProjectHeader', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});
