<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
        'css/site.css',
        'css/bootstrap-treeview.min.css',
        'http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',
    ];
    public $js = [
        'js/main.js',
        'js/jquery.maskedinput.min.js',
         'js/bootstrap-treeview.min.js',
         'http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
         'https://code.jquery.com/ui/1.12.1/jquery-ui.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];



}
