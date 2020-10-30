<?php

namespace backend\controllers;

use Yii;
use backend\models\Products;
use backend\models\ProductImages;
use backend\models\ProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use linslin\yii2\curl;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
		$productImgs = $model->getProductImages()->all();
		
        return $this->render('view', [
            'model' => $model,
			'productImgs' => $productImgs,
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();
		$uploadModel = new ProductImages();

		if ($model->load(Yii::$app->request->post())) {
			$model->created_by = Yii::$app->user->identity->id;
			
			$model->save();

			$uploadModel->imageFiles = \yii\web \UploadedFile::getInstances($uploadModel, 'imageFiles');
			if ($uploadModel->upload($model->id)) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				echo "Failed to Upload";
			}
		} else {
			return $this->render('create', [
				'model' => $model,
				'uploadModel' => $uploadModel,
			]);
		}
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$uploadModel = new ProductImages();
		$productImgs = $model->getProductImages()->all();

		if ($model->load(Yii::$app->request->post())) {
			$uploadModel->imageFiles = \yii\web \UploadedFile::getInstances($uploadModel, 'imageFiles');
			$model->save();
			if ($uploadModel->upload($id)) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				echo "Failed to Upload";
			}
		} else {
			return $this->render('update', [
				'model' => $model,
				'uploadModel' => $uploadModel,
				'productImgs' => $productImgs,
			]);
		}
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
	public function actionDeleteImage($id,$image_id,$redirect)
	{
        $imageModel = ProductImages::findOne($image_id);
		$imagePath = Yii::getAlias('@app') . '/web/uploads/'. $imageModel->image_path;
        unlink($imagePath);
        $imageModel->delete();

        Yii::$app->session->setFlash("success","Image Deleted successfully");

        return $this->redirect([$redirect,"id"=>$id]);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
    public function actionList()
	{
        $model = new Products();
        $data = $model->getProducts();
        return $this->render("product_list",[
            "data"=>$data
        ]);
    }
	
    /**
     * cURL POST example with post body params.
     */
    public function actionProductAdd($id)
    {
        $curl = new curl\Curl();
        $curl->setHeaders(['Authorization' => 'Basic dmFpYmhhdjp2YWliaGF2MjM=']);
        $response = $curl->setOption(
                CURLOPT_POSTFIELDS, 
                http_build_query(array(
                    'product_id' => $id,
					'quantity' => '1'
                )
            ))
            ->post('http://localhost/mycart/backend/web/index.php/api/products/add-to-cart');
			
		Yii::$app->session->setFlash("success","Product Added successfully to Cart!!!");

        return $this->redirect(["cart/index"]);
    }
	
	public function actionProductGet()
    {
        $curl = new curl\Curl();
        $curl->setHeaders(['Authorization' => 'Basic dmFpYmhhdjp2YWliaGF2MjM=']);
        $response = $curl->get('http://localhost/mycart/backend/web/index.php/api/products/get-products');
		
		$data = json_decode($response, true);

        return $this->render("product_list",[
            "data"=>$data["data"]
        ]);
    }
}
