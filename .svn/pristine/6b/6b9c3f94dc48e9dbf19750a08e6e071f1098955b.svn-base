<?php

/**
 * @property integer $id
 * @property integer $category_id
 * @property integer $brand_id
 * @property integer $is_inactive
 *
 * @property Category $category
 * @property Brand $brand
 * @property CategoryBrandBody[] $categoryBrandBodys
 * @property CategoryBrandConnection[] $categoryBrandConnections
 * @property CategoryBrandDisc[] $categoryBrandDiscs
 * @property CategoryBrandHandling[] $categoryBrandHandlings
 * @property CategoryBrandType[] $categoryBrandTypes
 */
class CategoryBrandBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_brand';
    }

    public function rules() {
        return array(
            array('category_id, brand_id', 'required'),
            array('category_id, brand_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_id, brand_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'brand' => array(self::BELONGS_TO, 'Brand', 'brand_id'),
            'categoryBrandBodys' => array(self::HAS_MANY, 'CategoryBrandBody', 'category_brand_id'),
            'categoryBrandConnections' => array(self::HAS_MANY, 'CategoryBrandConnection', 'category_brand_id'),
            'categoryBrandDiscs' => array(self::HAS_MANY, 'CategoryBrandDisc', 'category_brand_id'),
            'categoryBrandHandlings' => array(self::HAS_MANY, 'CategoryBrandHandling', 'category_brand_id'),
            'categoryBrandTypes' => array(self::HAS_MANY, 'CategoryBrandType', 'category_brand_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_id' => 'Category',
            'brand_id' => 'Brand',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('brand_id', $this->brand_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}