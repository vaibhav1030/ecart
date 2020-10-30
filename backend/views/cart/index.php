<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CartSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Carts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Create Cart', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,


        'columns' => [

            'id',
            [
                'label'=>'',
                'format'=>'raw',
                'value'=> function($model){
                    if(isset($model->product->productImages) && isset($model->product->productImages[0])){
                        return  Html::img(Yii::getAlias('@web')."/uploads/".$model->product->productImages[0]->image_path,["width"=>100,"height"=>100]);
                    }else{
                        return "< No Image >";
                    }
                    
                }
            ],
            'product.product_name',
            'product.product_price',
            'quantity',
            'user.username',
            'created_on',

        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
