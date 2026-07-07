<?php

/**
 * @property integer $id
 * @property integer $category_id
 * @property integer $classification_id
 * @property integer $is_inactive
 *
 * @property Category $category
 * @property Classification $classification
 * @property CategoryClassificationConnection[] $categoryClassificationConnections
 * @property CategoryClassificationMaterial[] $categoryClassificationMaterials
 * @property CategoryClassificationVariety[] $categoryClassificationVarieties
 */
class CategoryClassificationBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_classification';
    }

    public function rules() {
        return array(
            array('category_id, classification_id', 'required'),
            array('category_id, classification_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_id, classification_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'classification' => array(self::BELONGS_TO, 'Classification', 'classification_id'),
            'categoryClassificationConnections' => array(self::HAS_MANY, 'CategoryClassificationConnection', 'category_classification_id'),
            'categoryClassificationMaterials' => array(self::HAS_MANY, 'CategoryClassificationMaterial', 'category_classification_id'),
            'categoryClassificationVarieties' => array(self::HAS_MANY, 'CategoryClassificationVariety', 'category_classification_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_id' => 'Category',
            'classification_id' => 'Classification',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('classification_id', $this->classification_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}