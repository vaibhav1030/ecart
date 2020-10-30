<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>
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
  width: 200px;
  height: 200px;
}
</style>
<div class="products-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_price')->textInput() ?>

    <?= $form->field($model, 'product_desc')->textarea(['rows' => 6]) ?>
	
	<?= $form->field($uploadModel, 'imageFiles[]')->fileInput(['multiple' => 'multiple', 'accept' => 'image/*']) ?>

    <table class="gallery"><tr>
    <?php if(!$model->isNewRecord) { 
	
		foreach($productImgs as $prod_img)
		{
	?>
		
		  <td><a target="_blank" href="<?php echo Yii::getAlias('@web') . "/uploads/" . $prod_img->image_path; ?>">
			<img src="<?php echo Yii::getAlias('@web') . "/uploads/" . $prod_img->image_path; ?>" width="200" height="200">
		  </a>
		  <div class="desc"><?= Html::a("Delete",Url::to(["delete-image","id"=>$prod_img->product_id,"image_id"=>$prod_img->id,"redirect"=>"update"])); ?></div>
		  </td>
		
	<?php
		}
	?>
    <tr></table>
    <?= $form->field($model, 'status')->dropDownList(array("1"=>"Yes","0"=>"No")) ?>
	
	<?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>