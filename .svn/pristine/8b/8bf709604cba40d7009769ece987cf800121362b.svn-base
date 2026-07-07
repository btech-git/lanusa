<?php

/**
 * @property integer $id
 * @property integer $category_classification_id
 * @property integer $material_id
 * @property integer $is_inactive
 *
 * @property Material $material
 * @property CategoryClassification $categoryClassification
 */
class CategoryClassificationMaterialBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_classification_material';
    }

    public function rules() {
        return array(
            array('category_classification_id, material_id', 'required'),
            array('category_classification_id, material_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_classification_id, material_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'material' => array(self::BELONGS_TO, 'Material', 'material_id'),
            'categoryClassification' => array(self::BELONGS_TO, 'CategoryClassification', 'category_classification_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_classification_id' => 'Category Classification',
            'material_id' => 'Material',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_classification_id', $this->category_classification_id);
        $criteria->compare('material_id', $this->material_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}