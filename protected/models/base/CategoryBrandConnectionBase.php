<?php

/**
 * @property integer $id
 * @property integer $category_brand_id
 * @property integer $connection_id
 * @property integer $is_inactive
 *
 * @property Connection $connection
 * @property CategoryBrand $categoryBrand
 */
class CategoryBrandConnectionBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_brand_connection';
    }

    public function rules() {
        return array(
            array('category_brand_id, connection_id', 'required'),
            array('category_brand_id, connection_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_brand_id, connection_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'connection' => array(self::BELONGS_TO, 'Connection', 'connection_id'),
            'categoryBrand' => array(self::BELONGS_TO, 'CategoryBrand', 'category_brand_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_brand_id' => 'Category Brand',
            'connection_id' => 'Connection',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_brand_id', $this->category_brand_id);
        $criteria->compare('connection_id', $this->connection_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}