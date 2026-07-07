<?php

class CategoryBrand extends CategoryBrandBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getName()
	{
		return $this->category->name . ' - ' . $this->brand->name;
	}
}