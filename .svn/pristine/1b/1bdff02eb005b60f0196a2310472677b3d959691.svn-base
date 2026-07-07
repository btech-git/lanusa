<?php

/**
 * @property integer $id
 * @property integer $category_id
 * @property integer $variety_id
 * @property integer $is_inactive
 *
 * @property Category $category
 * @property Variety $variety
 */
class CategoryVarietyBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_variety';
    }

    public function rules() {
        return array(
            array('category_id, variety_id', 'required'),
            array('category_id, variety_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_id, variety_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'variety' => array(self::BELONGS_TO, 'Variety', 'variety_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_id' => 'Category',
            'variety_id' => 'Variety',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('variety_id', $this->variety_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}