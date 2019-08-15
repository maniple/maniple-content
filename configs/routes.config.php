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
    'maniple-pages.pages.create' => array(
        'route'    => 'pages/create',
        'defaults' => array(
            'module'     => 'maniple-pages',
            'controller' => 'pages',
            'action'     => 'create',
        ),
    ),
    'maniple-pages.pages.edit' => array(
        'route'    => 'pages/:page_id/edit',
        'defaults' => array(
            'module'     => 'maniple-pages',
            'controller' => 'pages',
            'action'     => 'edit',
        ),
        'reqs' => array(
            'page_id' => '^\d+',
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
    'maniple-pages.pages.slug' => array(
        'route'    => 'pages/slug',
        'defaults' => array(
            'module'     => 'maniple-pages',
            'controller' => 'pages',
            'action'     => 'slug',
        ),
    ),
);
