<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = $model->product_name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'product_name',
            'product_price',
            'product_desc:ntext',
            'status:boolean',
        ],
    ]) ?>

</div>

<html>
<head>
<style>
div.gallery {
  margin: 20px;
  border: 1px solid #ccc;
  float: left;
  width: 180px;
}

div.gallery:hover {
  border: 1px solid #777;
}

div.gallery img {
  width: 178px;
  height: 178px;
}

</style>
</head>
<body>
<?php
foreach($productImgs as $prod_img)
{
?>
<div class="gallery">
  <a target="_blank" href="<?php echo Yii::getAlias('@web') . "/uploads/" . $prod_img->image_path; ?>">
    <img src="<?php echo Yii::getAlias('@web') . "/uploads/" . $prod_img->image_path; ?>" width="200" height="200">
  </a>
  <div class="desc"><?= Html::a("Delete",Url::to(["delete-image","id"=>$prod_img->product_id,"image_id"=>$prod_img->id,"redirect"=>"view"])); ?></div>
</div>
<?php
}
?>

</body>
</html> 
