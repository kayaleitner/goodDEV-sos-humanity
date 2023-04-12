<?php

namespace Flynt\Components\BlockInsightHeader;

use Flynt\FieldVariables;
use Flynt\Utils\Options;
use Timber\Timber;

add_filter('Flynt/addComponentData?name=BlockInsightHeader', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});
