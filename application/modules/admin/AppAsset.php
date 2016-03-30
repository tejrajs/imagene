<?php

namespace app\modules\admin;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
	public $depends = [
        'codezeen\yii2\adminlte\AdminLteAsset',
        'app\modules\admin\AppAssetIe9',
    ];

    public function init()
    {
        if (YII_DEBUG) {
            $this->css = ['css/site.css'];
            $this->js = ['js/site.js'];
        } else {
            $this->css = ['css/min/site.css'];
            $this->js = ['js/min/site.js'];
        }
        
        $this->sourcePath = dirname(__FILE__) . '/assets';
        parent::init();
    }
}