<?php

/**
 * @property integer $id
 * @property integer $category_material_grade_id
 * @property integer $brand_id
 * @property integer $is_inactive
 *
 * @property Brand $brand
 * @property CategoryMaterialGrade $categoryMaterialGrade
 */
class CategoryMaterialGradeBrandBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_material_grade_brand';
    }

    public function rules() {
        return array(
            array('category_material_grade_id, brand_id', 'required'),
            array('category_material_grade_id, brand_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_material_grade_id, brand_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'brand' => array(self::BELONGS_TO, 'Brand', 'brand_id'),
            'categoryMaterialGrade' => array(self::BELONGS_TO, 'CategoryMaterialGrade', 'category_material_grade_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_material_grade_id' => 'Category Material Grade',
            'brand_id' => 'Brand',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_material_grade_id', $this->category_material_grade_id);
        $criteria->compare('brand_id', $this->brand_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}