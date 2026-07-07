<?php

/**
 * @property integer $id
 * @property integer $category_id
 * @property integer $material_id
 * @property integer $is_inactive
 *
 * @property Category $category
 * @property Material $material
 * @property CategoryMaterialGrade[] $categoryMaterialGrades
 */
class CategoryMaterialBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_material';
    }

    public function rules() {
        return array(
            array('category_id, material_id', 'required'),
            array('category_id, material_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_id, material_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'material' => array(self::BELONGS_TO, 'Material', 'material_id'),
            'categoryMaterialGrades' => array(self::HAS_MANY, 'CategoryMaterialGrade', 'category_material_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_id' => 'Category',
            'material_id' => 'Material',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('material_id', $this->material_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}