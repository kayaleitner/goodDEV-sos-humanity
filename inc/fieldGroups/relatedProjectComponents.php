<?php

// use ACFComposer\ACFComposer;
// use Flynt\Components;
// use Flynt\ComponentManager;
// use Timber\Timber;

// add_action('Flynt/afterRegisterComponents', function () {
//     ACFComposer::registerFieldGroup([
//         'name' => 'relatedProjectComponents',
//         'title' => __('Related Project Blocks', 'flynt'),
//         'style' => 'seamless',
//         'fields' => [
//             [
//                 'name' => 'relatedProjectComponents',
//                 'label' => __('Related Project Blocks', 'flynt'),
//                 'type' => 'flexible_content',
//                 'button_label' => __('Add Block', 'flynt'),
//                 'layouts' => [
//                     Components\RelatedProjects\getACFLayout(),
//                 ],
//             ],
//         ],
//         'location' => [
//             [
//                 [
//                     'param' => 'post_type',
//                     'operator' => '==',
//                     'value' => 'project',
//                 ],
//             ],
//         ],
//     ]);
// });
