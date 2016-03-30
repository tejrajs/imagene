<?php
use cebe\gravatar\Gravatar;
use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<header class="main-header">
    <a href="<?= Yii::$app->urlManager->createUrl(['/admin/default/index']) ?>" class="logo">
        <span class="logo-mini"><?= Html::img(Yii::getAlias('@web/img/logo-mini.png')) ?></span>
        <span class="logo-lg"><b>Admin</b>Panel</span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?= Gravatar::widget([
                                'email' => Yii::$app->user->identity->email,
                                'options' => ['alt' => 'Gravatar 25', 'class' => 'user-image'],
                                'size' => 25,
                            ]) ?>
                            <span class="hidden-xs"><?= Yii::$app->user->identity->username ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <?= Gravatar::widget([
                                    'email' => Yii::$app->user->identity->email,
                                    'options' => ['alt' => 'Gravatar 84', 'class' => 'img-circle'],
                                    'size' => 84,
                                ]) ?>
                                <p>
                                    <?= Yii::$app->user->identity->username ?>
                                    <small>
                                        <?= Yii::t('app', 'Member since {date}', [
                                            'date' => Yii::$app
                                                ->formatter
                                                ->asDate(Yii::$app->user->identity->created_at, 'php:F d, Y'),
                                        ]) ?>
                                    </small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <?= Html::a(
                                        Yii::t('app', 'Profile'),
                                        ['/user/profile'],
                                        ['class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                                <div class="pull-right">
                                    <?= Html::a(
                                        Yii::t('app', 'Sign Out'),
                                        ['/site/logout'],
                                        ['class' => 'btn btn-default btn-flat', 'data-method' => 'post']
                                    ) ?>
                                </div>
                            </li>
                        </ul>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </nav>
</header>
