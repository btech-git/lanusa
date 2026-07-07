<?php

class CategoryMaterialGrade extends CategoryMaterialGradeBase
{
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	
	public function getName()
	{
		return $this->categoryMaterial->category->name . ' - ' . $this->categoryMaterial->material->name. ' - ' . $this->grade->name;
	}
	
}