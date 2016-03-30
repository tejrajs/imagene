<?php
use yii\widgets\LinkPager;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">
	<div id="products" class="row list-group">
    <?php foreach ($models as $model):?>
    	<div class="item  col-xs-4 col-lg-4">
            <div class="thumbnail">
                <img class="group list-group-image" src="<?= \yii\helpers\Url::to(['/imagane/view','id'=> $model->id])?>" alt="" />
                <div class="caption">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <?= \yii\helpers\Html::a('Process',['/site/process','id' => $model->id],['class' => 'btn btn-success'])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?>
    </div>
</div>
<?php 
// display pagination
echo LinkPager::widget([
		'pagination' => $pages,
]);
?>