<?php

namespace Flynt\Components\CopyButton;

add_action('Flynt/registerComponents', function () {
  \Flynt\registerComponent('CopyButton', __DIR__);
});
