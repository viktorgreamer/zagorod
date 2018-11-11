<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/paper.css',
        'css/site.css',
        'css/bootstrap-treeview.min.css',
    ];
    public $js = [
        'js/main.js',
        'js/jquery.maskedinput.min.js',
        'js/bootstrap-treeview.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
