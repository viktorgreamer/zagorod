<?php
use kartik\tree\TreeViewInput;
use kartik\tree\TreeView;
use common\models\Tree;


echo TreeView::widget([
    'query' => Tree::find()->addOrderBy('root, lft'),
    'headingOptions' => ['label' => 'Store'],
    'rootOptions' => ['label'=>'<span class="text-primary">Products</span>'],
    'topRootAsHeading' => true, // this will override the headingOptions
    'fontAwesome' => true,
    'isAdmin' => true,
    'iconEditSettings'=> [
        'show' => 'list',
        'listData' => [
            'folder' => 'Folder',
            'file' => 'File',
            'mobile' => 'Phone',
            'bell' => 'Bell',
        ]
    ],
    'softDelete' => true,
    'cacheSettings' => ['enableCache' => true]
]);