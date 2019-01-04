<?php

return [

    'name' => 'content',

    'routes' => [
        'content/entries(/*)?' => [
            'controller' => 'controller/content'
        ]
    ],

    'menu' => [
        'content' => [
            'icon' => 'content2Icon',
            'name' => 'Content',
            'url' => 'content/entries',
            'active' => 'content(/*)?',
            'order' => 10
        ],
        'content/entries' => [
            'parent' => 'content',
            'name' => 'Entries',
            'url' => 'content/entries',
            'active' => 'content/entries(/*)?'
        ]
    ]

];

?>
