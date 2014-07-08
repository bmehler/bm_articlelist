<?php
$sMetadataVersion = '1.0';
$aModule = array(
    'id'                        => 'bm_articlelist',
    'title'                     => 'My first Oxid Module',
    'description'               => 'Displays articles on sidebar. Choose your number of items.',
    'thumbnail'                 => 'screenshot.jpg',
    'version'                   => '1.0',
    'author'                    => 'Bernhard Mehler',
    'url'                       => 'https://gitub.com/bmehler/ox_top',
    'email'                     => 'bernhard.mehler@gmail.com',
    'extend'                    => array(
        "oxcmp_utils"           => "bm_articlelist/controllers/bm_oxcmp_utils"
    ),
    'blocks'    => array(
        array(
            'template' =>   'layout/sidebar.tpl',
            'block'    =>   'sidebar_categoriestree',
            'file'     =>   '/views/blocks/sidebar.tpl'
        )
    ),
    'settings'  =>  array(
        array(
            'group'    =>   'main',
            'name'     =>   'iArticleLimit',
            'type'     =>   'str',
            'value'    =>   '5'
        )
    )
);