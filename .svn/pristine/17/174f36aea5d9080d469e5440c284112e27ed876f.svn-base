<?php

class CategoryClassification extends CategoryClassificationBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getName()
	{
		return $this->category->name . ' - ' . $this->classification->name;
	}
}