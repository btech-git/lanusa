<?php

class CategoryMaterial extends CategoryMaterialBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getName()
	{
		return $this->category->name . ' - ' . $this->material->name;
	}
}