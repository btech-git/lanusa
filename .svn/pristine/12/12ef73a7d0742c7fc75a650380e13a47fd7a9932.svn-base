<?php

/**
 * @property integer $id
 * @property integer $category_material_grade_id
 * @property integer $thickness_id
 * @property integer $is_inactive
 *
 * @property Thickness $thickness
 * @property CategoryMaterialGrade $categoryMaterialGrade
 */
class CategoryMaterialGradeThicknessBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_material_grade_thickness';
    }

    public function rules() {
        return array(
            array('category_material_grade_id, thickness_id', 'required'),
            array('category_material_grade_id, thickness_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_material_grade_id, thickness_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'thickness' => array(self::BELONGS_TO, 'Thickness', 'thickness_id'),
            'categoryMaterialGrade' => array(self::BELONGS_TO, 'CategoryMaterialGrade', 'category_material_grade_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_material_grade_id' => 'Category Material Grade',
            'thickness_id' => 'Thickness',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_material_grade_id', $this->category_material_grade_id);
        $criteria->compare('thickness_id', $this->thickness_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}