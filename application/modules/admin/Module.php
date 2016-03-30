<?php

namespace app\modules\admin;

class Module extends \yii\base\Module
{
	public $mainLayout = '@app/modules/admin/views/layouts/main.php';
	
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function init()
    {
    	$this->layout = $this->mainLayout;
    	
        parent::init();
        // custom initialization code goes here
    }
}
