<?php

/**
 * @property integer $id
 * @property integer $category_id
 * @property integer $connection_id
 * @property integer $is_inactive
 *
 * @property Category $category
 * @property Connection $connection
 */
class CategoryConnectionBase extends ActiveRecord {

    public function tableName() {
        return 'tblla_category_connection';
    }

    public function rules() {
        return array(
            array('category_id, connection_id', 'required'),
            array('category_id, connection_id, is_inactive', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            array('id, category_id, connection_id, is_inactive', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
            'connection' => array(self::BELONGS_TO, 'Connection', 'connection_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'category_id' => 'Category',
            'connection_id' => 'Connection',
            'is_inactive' => 'Status',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('connection_id', $this->connection_id);
        $criteria->compare('is_inactive', $this->is_inactive);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}