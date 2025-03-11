<?php

namespace Flynt\Components\ElementPageHeader;

add_filter('Flynt/addComponentData?name=ElementPageHeader', function ($data) {
    $data['dateFormat'] = get_option('date_format');
    return $data;
});
