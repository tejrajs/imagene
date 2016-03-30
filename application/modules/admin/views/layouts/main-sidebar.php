<?php
use cebe\gravatar\Gravatar;
use codezeen\yii2\adminlte\widgets\Menu;
use common\models\Option;
use common\models\PostType;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>

<aside class="main-sidebar">
    <section class="sidebar">

        <?php if (!Yii::$app->user->isGuest): ?>
            <div class="user-panel">
                <div class="pull-left image">
                    <?= Gravatar::widget([
                        'email' => Yii::$app->user->identity->email,
                        'options' => ['alt' => 'Gravatar 45', 'class' => 'img-circle'],
                        'size' => 45,
                    ]) ?>
                </div>
                <div class="pull-left info">
                    <p><?= Yii::$app->user->identity->username ?></p>
                    <?= Html::a(
                        '<i class="fa fa-circle text-success"></i>' . Yii::t('app', 'Online'),
                        ['/admin/user/profile']
                    ) ?>
                </div>
            </div>
        <?php endif ?>

        <?php
        $adminMenu[0] = [
            'label' => Yii::t('app', 'MAIN NAVIGATION'),
            'options' => ['class' => 'header'],
            'template' => '{label}',
        ];
        $adminMenu[1] = [
            'label' => Yii::t('app', 'Dashboard'),
            'icon' => 'fa fa-dashboard',
            'items' => [
                ['icon' => 'fa fa-circle-o', 'label' => Yii::t('app', 'Home'), 'url' => ['/admin/admin/index']],
            ],
        ];
        
        $adminMenu[10] = [
        		'label' => Yii::t('app', 'Tips Category'),
        		'icon' => 'fa fa-indent',
        		'items' => [
        				['icon' => 'fa fa-circle-o', 'label' => Yii::t('app', 'Category List'), 'url' => ['/admin/category/index']],
        				['icon' => 'fa fa-circle-o', 'label' => Yii::t('app', 'Add New'), 'url' => ['/admin/category/create']],
        		],
        ];
        
        $adminMenu[11] = [
        		'label' => Yii::t('app', 'Tips'),
        		'icon' => 'fa fa-cc-stripe',
        		'items' => [
        				['icon' => 'fa fa-circle-o', 'label' => Yii::t('app', 'Tips List'), 'url' => ['/admin/tips/index']],
        				['icon' => 'fa fa-circle-o', 'label' => Yii::t('app', 'Add New'), 'url' => ['/admin/tips/create']],
        		],
        ];

        if (isset(Yii::$app->params['adminMenu']) && is_array(Yii::$app->params['adminMenu'])) {
            $adminMenu = ArrayHelper::merge($adminMenu, Yii::$app->params['adminMenu']);
        }

        ksort($adminMenu);
        echo Menu::widget(['items' => $adminMenu]);
        ?>

    </section>
</aside>
