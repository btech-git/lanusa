<?php

/**
 * @property integer $id
 * @property integer $category_brand_id
 * @property integer $body_type_id
 * @property integer $is_inactive
 *
 * @property BodyType $bodyType
 * @property CategoryBrand $categoryBrand
 */
class CategoryBrandBodyBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_brand_body';
    }

    public function rules() {
        return array(
            array('category_brand_id, body_type_id', 'required'),
            array('category_brand_id, body_type_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_brand_id, body_type_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'bodyType' => array(self::BELONGS_TO, 'BodyType', 'body_type_id'),
            'categoryBrand' => array(self::BELONGS_TO, 'CategoryBrand', 'category_brand_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_brand_id' => 'Category Brand',
            'body_type_id' => 'Body Type',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_brand_id', $this->category_brand_id);
        $criteria->compare('body_type_id', $this->body_type_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}