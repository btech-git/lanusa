<?php

/**
 * @property integer $id
 * @property integer $category_classification_id
 * @property integer $connection_id
 * @property integer $is_inactive
 *
 * @property Connection $connection
 * @property CategoryClassification $categoryClassification
 */
class CategoryClassificationConnectionBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_classification_connection';
    }

    public function rules() {
        return array(
            array('category_classification_id, connection_id', 'required'),
            array('category_classification_id, connection_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_classification_id, connection_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'connection' => array(self::BELONGS_TO, 'Connection', 'connection_id'),
            'categoryClassification' => array(self::BELONGS_TO, 'CategoryClassification', 'category_classification_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_classification_id' => 'Category Classification',
            'connection_id' => 'Connection',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_classification_id', $this->category_classification_id);
        $criteria->compare('connection_id', $this->connection_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}