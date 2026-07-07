<?php

/**
 * @property integer $id
 * @property integer $category_classification_id
 * @property integer $variety_id
 * @property integer $is_inactive
 *
 * @property Variety $variety
 * @property CategoryClassification $categoryClassification
 */
class CategoryClassificationVarietyBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_classification_variety';
    }

    public function rules() {
        return array(
            array('category_classification_id, variety_id', 'required'),
            array('category_classification_id, variety_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_classification_id, variety_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'variety' => array(self::BELONGS_TO, 'Variety', 'variety_id'),
            'categoryClassification' => array(self::BELONGS_TO, 'CategoryClassification', 'category_classification_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_classification_id' => 'Category Classification',
            'variety_id' => 'Variety',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_classification_id', $this->category_classification_id);
        $criteria->compare('variety_id', $this->variety_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}