<?php

namespace frontend\modules\api\controllers;

class ProductsController extends \yii\web\Controller
{
    public function actionIndex()
    {
		echo "Test API";
		die;
        return $this->render('index');
    }

}
