<?php

return array(
    'maniple-pages.pages.index' => array(
        'route'    => 'pages',
        'defaults' => array(
            'module'     => 'maniple-pages',
            'controller' => 'pages',
            'action'     => 'index',
        ),
    ),
    'maniple-pages.pages.view' => array(
        'route'    => 'pages/:page_id',
        'defaults' => array(
            'module'     => 'maniple-pages',
            'controller' => 'pages',
            'action'     => 'view',
        ),
        'reqs' => array(
            'page_id' => '^\d+',
        ),
    ),
);