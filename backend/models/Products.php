<?php

namespace backend\models;

use Yii;
use common\models\User;
use yii\helpers\Url;


/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $product_name
 * @property float $product_price
 * @property string $product_desc
 * @property int $status
 *
 * @property ProductImages[] $productImages
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_name', 'product_price', 'product_desc'], 'required'],
            [['product_price'], 'number'],
            [['product_desc'], 'string'],
            [['status'], 'integer'],
            [['product_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_name' => 'Product Name',
            'product_price' => 'Product Price',
            'product_desc' => 'Product Desc',
            'status' => 'Active',
        ];
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImages::className(), ['product_id' => 'id']);
    }
	
    public function getUser(){
        return $this->hasOne(User::className(),["id"=>"created_by"]);
    }
	
	public function getProducts(){

        $return = [];
        $products = Products::find()->alias("p")->select("p.id,p.product_name,p.product_price")
                    ->joinWith(["productImages"])
                    ->asArray()
                    ->all();

        if($products){
            $return["image_base_url"] = Url::base(true)."/uploads/";
            $return["products"] = $products;
        }
        return $return;            
    }
}
