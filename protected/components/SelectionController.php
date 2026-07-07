<?php

class SelectionController extends Controller
{
	public $menu = array();
	
	public function actionSelectSpecificationAjax($view, $action)
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$model = new Product;

			if (isset($_POST['Product']))
			{
				$model->attributes = $_POST['Product'];
				$model->unsetAttributes(array('material_id', 'grade_id', 'thickness_id', 'type_id', 'brand_id', 'disc_material_id', 'body_type_id', 'connection_id', 'classification_id', 'variety_id', 'handling_id'));
			}

			$specificationList = $this->specificationList($model->category_id);
			$listData = $this->listData();

			$this->renderPartial($view, array(
				'model' => $model,
				'specificationList' => $specificationList,
				'listData' => $listData,
				'action' => $action,
			));
		}
	}

	public function actionSearchSpecificationAjax($view, $action)
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$model = new Product('search');
			$model->unsetAttributes();  // clear any default values

			if (isset($_POST['Product']))
			{
				$model->attributes = $_POST['Product'];
				$model->unsetAttributes(array('material_id', 'grade_id', 'thickness_id', 'type_id', 'brand_id', 'disc_material_id', 'body_type_id', 'connection_id', 'classification_id', 'variety_id', 'handling_id'));
			}

			$specificationList = $this->specificationList($model->category_id);
			$listData = $this->listData();

			$this->renderPartial($view, array(
				'model' => $model,
				'specificationList' => $specificationList,
				'listData' => $listData,
				'action' => $action,
			));
		}
	}

	public function actionCategorySelectionAjaxData($emptyText = '')
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$categoryId = isset($_POST['Product']['category_id']) ? $_POST['Product']['category_id'] : '';
			list($brandList, $classificationList, $connectionList, $gradeList, $materialList, $thicknessList, $typeList, $varietyList) = $this->categorySelectionList($categoryId);

			$htmlOptions = array('empty' => $emptyText);
			$brandOptions = CHtml::listOptions('', $brandList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$classificationOptions = CHtml::listOptions('', $classificationList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$connectionOptions = CHtml::listOptions('', $connectionList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$gradeOptions = CHtml::listOptions('', $gradeList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$materialOptions = CHtml::listOptions('', $materialList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$thicknessOptions = CHtml::listOptions('', $thicknessList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$typeOptions = CHtml::listOptions('', $typeList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$varietyOptions = CHtml::listOptions('', $varietyList, $htmlOptions);

			echo CJSON::encode(array(
				'brandOptions' => $brandOptions,
				'classificationOptions' => $classificationOptions,
				'connectionOptions' => $connectionOptions,
				'gradeOptions' => $gradeOptions,
				'materialOptions' => $materialOptions,
				'thicknessOptions' => $thicknessOptions,
				'typeOptions' => $typeOptions,
				'varietyOptions' => $varietyOptions,
			));
		}
	}

	public function actionBrandSelectionAjaxData($emptyText = '')
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$categoryId = isset($_POST['Product']['category_id']) ? $_POST['Product']['category_id'] : '';
			$brandId = isset($_POST['Product']['brand_id']) ? $_POST['Product']['brand_id'] : '';
			list($bodyList, $connectionList, $discList, $handlingList, $typeList) = $this->brandSelectionList($categoryId, $brandId);

			$htmlOptions = array('empty' => $emptyText);
			$bodyOptions = CHtml::listOptions('', $bodyList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$connectionOptions = CHtml::listOptions('', $connectionList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$discOptions = CHtml::listOptions('', $discList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$handlingOptions = CHtml::listOptions('', $handlingList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$typeOptions = CHtml::listOptions('', $typeList, $htmlOptions);

			echo CJSON::encode(array(
				'bodyOptions' => $bodyOptions,
				'connectionOptions' => $connectionOptions,
				'discOptions' => $discOptions,
				'handlingOptions' => $handlingOptions,
				'typeOptions' => $typeOptions,
			));
		}
	}

	public function actionClassificationSelectionAjaxData($emptyText = '')
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$categoryId = isset($_POST['Product']['category_id']) ? $_POST['Product']['category_id'] : '';
			$classificationId = isset($_POST['Product']['classification_id']) ? $_POST['Product']['classification_id'] : '';
			list($connectionList, $materialList, $varietyList) = $this->classificationSelectionList($categoryId, $classificationId);

			$htmlOptions = array('empty' => $emptyText);
			$connectionOptions = CHtml::listOptions('', $connectionList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$materialOptions = CHtml::listOptions('', $materialList, $htmlOptions);
			$htmlOptions = array('empty' => $emptyText);
			$varietyOptions = CHtml::listOptions('', $varietyList, $htmlOptions);

			echo CJSON::encode(array(
				'connectionOptions' => $connectionOptions,
				'materialOptions' => $materialOptions,
				'varietyOptions' => $varietyOptions,
			));
		}
	}

	public function actionMaterialSelectionAjaxData($emptyText = '')
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$categoryId = isset($_POST['Product']['category_id']) ? $_POST['Product']['category_id'] : '';
			$materialId = isset($_POST['Product']['material_id']) ? $_POST['Product']['material_id'] : '';
			list($gradeList) = $this->materialSelectionList($categoryId, $materialId);

			$htmlOptions = array('empty' => $emptyText);
			$gradeOptions = CHtml::listOptions('', $gradeList, $htmlOptions);

			echo CJSON::encode(array(
				'gradeOptions' => $gradeOptions,
			));
		}
	}

	public function actionGradeSelectionAjaxData($emptyText = '')
	{
		if (Yii::app()->request->isAjaxRequest)
		{
			$categoryId = isset($_POST['Product']['category_id']) ? $_POST['Product']['category_id'] : '';
			$materialId = isset($_POST['Product']['material_id']) ? $_POST['Product']['material_id'] : '';
			$gradeId = isset($_POST['Product']['grade_id']) ? $_POST['Product']['grade_id'] : '';
			list($brandList, $thicknessList) = $this->gradeSelectionList($categoryId, $materialId, $gradeId);

			$brandOptions = CHtml::listOptions('', $brandList, ($htmlOptions = array('empty' => $emptyText)));
			$thicknessOptions = CHtml::listOptions('', $thicknessList, ($htmlOptions = array('empty' => $emptyText)));

			echo CJSON::encode(array(
				'brandOptions' => $brandOptions,
				'thicknessOptions' => $thicknessOptions,
			));
		}
	}

	public function specificationList($categoryId)
	{
		$categorySpecifications = CategorySpecification::model()->findAllByAttributes(array('category_id' => $categoryId));

		$specificationList = CHtml::listData($categorySpecifications, 'specification_id', 'specification.name');

		return $specificationList;
	}

	public function listData($model = null)
	{
		$listData['body'] = array();
		$listData['brand'] = array();
		$listData['classification'] = array();
		$listData['connection'] = array();
		$listData['disc'] = array();
		$listData['grade'] = array();
		$listData['handling'] = array();
		$listData['material'] = array();
		$listData['thickness'] = array();
		$listData['type'] = array();
		$listData['variety'] = array();

		if ($model !== null)
		{
			if (!empty($model->category_id))
				list($listData['brand'], $listData['classification'], $listData['connection'], $listData['grade'], $listData['material'], $listData['thickness'], $listData['type'], $listData['variety']) = $this->categorySelectionList($model->category_id);
			if (!empty($model->category_id) && !empty($model->brand_id))
				list($listData['body'], $listData['connection'], $listData['disc'], $listData['handling'], $listData['type']) = $this->brandSelectionList($model->category_id, $model->brand_id);
			if (!empty($model->category_id) && !empty($model->classification_id))
				list($listData['connection'], $listData['material'], $listData['variety']) = $this->classificationSelectionList($model->category_id, $model->classification_id);
			if (!empty($model->category_id) && !empty($model->material_id))
				list($listData['grade']) = $this->materialSelectionList($model->category_id, $model->material_id);
			if (!empty($model->category_id) && !empty($model->material_id) && !empty($model->grade_id))
				list($listData['brand'], $listData['thickness']) = $this->gradeSelectionList($model->category_id, $model->material_id, $model->grade_id);
		}

		return $listData;
	}

	public function categorySelectionList($categoryId)
	{
		$categoryBrands = CategoryBrand::model()->findAllByAttributes(array('category_id' => $categoryId));
		$categoryClassifications = CategoryClassification::model()->findAllByAttributes(array('category_id' => $categoryId));
		$categoryConnections = CategoryConnection::model()->findAllByAttributes(array('category_id' => $categoryId));
		$categoryGrades = CategoryGrade::model()->findAllByAttributes(array('category_id' => $categoryId));
		$categoryMaterials = CategoryMaterial::model()->findAllByAttributes(array('category_id' => $categoryId));
		$categoryThicknesses = CategoryThickness::model()->findAllByAttributes(array('category_id' => $categoryId));
		$categoryTypes = CategoryType::model()->findAllByAttributes(array('category_id' => $categoryId));
		$categoryVarieties = CategoryVariety::model()->findAllByAttributes(array('category_id' => $categoryId));

		$brandList = CHtml::listData($categoryBrands, 'brand_id', 'brand.name');
		$classificationList = CHtml::listData($categoryClassifications, 'classification_id', 'classification.name');
		$connectionList = CHtml::listData($categoryConnections, 'connection_id', 'connection.name');
		$gradeList = CHtml::listData($categoryGrades, 'grade_id', 'grade.name');
		$materialList = CHtml::listData($categoryMaterials, 'material_id', 'material.name');
		$thicknessList = CHtml::listData($categoryThicknesses, 'thickness_id', 'thickness.name');
		$typeList = CHtml::listData($categoryTypes, 'type_id', 'type.name');
		$varietyList = CHtml::listData($categoryVarieties, 'variety_id', 'variety.name');

		return array($brandList, $classificationList, $connectionList, $gradeList, $materialList, $thicknessList, $typeList, $varietyList);
	}

	public function brandSelectionList($categoryId, $brandId)
	{
		$categoryBrand = CategoryBrand::model()->findByAttributes(array(
			'category_id' => $categoryId,
			'brand_id' => $brandId,
			));

		$categoryBrandBodies = array();
		$categoryBrandConnections = array();
		$categoryBrandDiscs = array();
		$categoryBrandHandlings = array();
		$categoryBrandTypes = array();
		if ($categoryBrand !== null)
		{
			$categoryBrandBodies = CategoryBrandBody::model()->findAllByAttributes(array('category_brand_id' => $categoryBrand->id));
			$categoryBrandConnections = CategoryBrandConnection::model()->findAllByAttributes(array('category_brand_id' => $categoryBrand->id));
			$categoryBrandDiscs = CategoryBrandDisc::model()->findAllByAttributes(array('category_brand_id' => $categoryBrand->id));
			$categoryBrandHandlings = CategoryBrandHandling::model()->findAllByAttributes(array('category_brand_id' => $categoryBrand->id));
			$categoryBrandTypes = CategoryBrandType::model()->findAllByAttributes(array('category_brand_id' => $categoryBrand->id));
		}

		$bodyList = CHtml::listData($categoryBrandBodies, 'body_type_id', 'bodyType.name');
		$connectionList = CHtml::listData($categoryBrandConnections, 'connection_id', 'connection.name');
		$discList = CHtml::listData($categoryBrandDiscs, 'disc_material_id', 'discMaterial.name');
		$handlingList = CHtml::listData($categoryBrandHandlings, 'handling_id', 'handling.name');
		$typeList = CHtml::listData($categoryBrandTypes, 'type_id', 'type.name');

		return array($bodyList, $connectionList, $discList, $handlingList, $typeList);
	}

	public function classificationSelectionList($categoryId, $classificationId)
	{
		$categoryClassification = CategoryClassification::model()->findByAttributes(array(
			'category_id' => $categoryId,
			'classification_id' => $classificationId,
		));

		$categoryClassificationConnections = array();
		$categoryClassificationMaterials = array();
		$categoryClassificationVarieties = array();
		if ($categoryClassification !== null)
		{
			$categoryClassificationConnections = CategoryClassificationConnection::model()->findAllByAttributes(array('category_classification_id' => $categoryClassification->id));
			$categoryClassificationMaterials = CategoryClassificationMaterial::model()->findAllByAttributes(array('category_classification_id' => $categoryClassification->id));
			$categoryClassificationVarieties = CategoryClassificationVariety::model()->findAllByAttributes(array('category_classification_id' => $categoryClassification->id));
		}

		$connectionList = CHtml::listData($categoryClassificationConnections, 'connection_id', 'connection.name');
		$materialList = CHtml::listData($categoryClassificationMaterials, 'material_id', 'material.name');
		$varietyList = CHtml::listData($categoryClassificationVarieties, 'variety_id', 'variety.name');

		return array($connectionList, $materialList, $varietyList);
	}

	public function materialSelectionList($categoryId, $materialId)
	{
		$categoryMaterial = CategoryMaterial::model()->findByAttributes(array(
			'category_id' => $categoryId,
			'material_id' => $materialId,
			));

		$categoryMaterialGrades = array();
		if ($categoryMaterial !== null)
		{
			$categoryMaterialGrades = CategoryMaterialGrade::model()->findAllByAttributes(array('category_material_id' => $categoryMaterial->id));
		}

		$gradeList = CHtml::listData($categoryMaterialGrades, 'grade_id', 'grade.name');

		return array($gradeList);
	}

	public function gradeSelectionList($categoryId, $materialId, $gradeId)
	{
		$categoryMaterial = CategoryMaterial::model()->findByAttributes(array(
			'category_id' => $categoryId,
			'material_id' => $materialId,
			));

		if ($categoryMaterial !== null)
		{
			$categoryMaterialGrade = CategoryMaterialGrade::model()->findByAttributes(array(
				'category_material_id' => $categoryMaterial->id,
				'grade_id' => $gradeId,
				));
		}

		$categoryMaterialGradeBrands = array();
		$categoryMaterialGradeThicknesses = array();
		if ($categoryMaterialGrade !== null)
		{
			$categoryMaterialGradeBrands = CategoryMaterialGradeBrand::model()->findAllByAttributes(array('category_material_grade_id' => $categoryMaterialGrade->id));
			$categoryMaterialGradeThicknesses = CategoryMaterialGradeThickness::model()->findAllByAttributes(array('category_material_grade_id' => $categoryMaterialGrade->id));
		}

		$brandList = CHtml::listData($categoryMaterialGradeBrands, 'brand_id', 'brand.name');
		$thicknessList = CHtml::listData($categoryMaterialGradeThicknesses, 'thickness_id', 'thickness.name');

		return array($brandList, $thicknessList);
	}
}