<?php

/**
 * @property integer $id
 * @property integer $category_id
 * @property integer $grade_id
 * @property integer $is_inactive
 *
 * @property Category $category
 * @property Grade $grade
 */
class CategoryGradeBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_grade';
    }

    public function rules() {
        return array(
            array('category_id, grade_id', 'required'),
            array('category_id, grade_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_id, grade_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'grade' => array(self::BELONGS_TO, 'Grade', 'grade_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_id' => 'Category',
            'grade_id' => 'Grade',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('grade_id', $this->grade_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}