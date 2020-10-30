<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product_images".
 *
 * @property int $id
 * @property int $product_id
 * @property string $image_path
 * @property int $status
 *
 * @property Products $product
 */
class ProductImages extends \yii\db\ActiveRecord
{
	public $imageFiles;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'image_path'], 'required'],
            [['product_id', 'status'], 'integer'],
            [['image_path'], 'string'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
			[['imageFiles'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 10, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'image_path' => 'Image Path',
            'status' => 'Status',
        ];
    }
	
	public function upload($product_id) {
		if (count($this->imageFiles)) {
			foreach ($this->imageFiles as $file) {
				$image_name = $product_id . '_' . $file->baseName . '.' . $file->extension;
				$image_path = 'uploads/' . $image_name;
				$file->saveAs($image_path);
				
				$imagesModel = new ProductImages();
				$imagesModel->product_id = $product_id;
				$imagesModel->image_path = $image_name;
				$imagesModel->status = 1;
				$imagesModel->save(false);
			}
			return true;
		} else {
			return false;
		}
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }
}
