<?php

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $buying_price
 * @property string $selling_price
 * @property string $size
 * @property string $length
 * @property integer $drat
 * @property string $physical_thickness
 * @property string $connection_diameter
 * @property integer $category_id
 * @property integer $type_id
 * @property integer $brand_id
 * @property integer $material_id
 * @property integer $disc_material_id
 * @property integer $body_type_id
 * @property integer $connection_id
 * @property integer $grade_id
 * @property integer $classification_id
 * @property integer $thickness_id
 * @property integer $variety_id
 * @property integer $connection_material_id
 * @property integer $parameter_id
 * @property integer $range_id
 * @property integer $handling_id
 * @property integer $bellow_id
 * @property integer $unit_id
 * @property integer $is_inactive
 *
 * @property AdjustmentDetail[] $adjustmentDetails
 * @property DeliveryDetail[] $deliveryDetails
 * @property Inventory[] $inventories
 * @property Category $category
 * @property Thickness $thickness
 * @property Variety $variety
 * @property ConnectionMaterial $connectionMaterial
 * @property Parameter $parameter
 * @property Range $range
 * @property Bellow $bellow
 * @property Unit $unit
 * @property Handling $handling
 * @property Type $type
 * @property Brand $brand
 * @property Material $material
 * @property DiscMaterial $discMaterial
 * @property BodyType $bodyType
 * @property Connection $connection
 * @property Grade $grade
 * @property Classification $classification
 * @property PurchaseDetail[] $purchaseDetails
 * @property PurchaseReturnDetail[] $purchaseReturnDetails
 * @property ReceiveDetail[] $receiveDetails
 * @property SaleDetail[] $saleDetails
 * @property SaleReturnDetail[] $saleReturnDetails
 * @property TransferDetail[] $transferDetails
 */
class ProductBase extends ActiveRecord
{
	public function tableName()
	{
		return 'tblla_product';
	}

	public function rules()
	{
		return array(
			array('category_id, unit_id', 'required'),
			array('drat, category_id, type_id, brand_id, material_id, disc_material_id, body_type_id, connection_id, grade_id, classification_id, thickness_id, variety_id, connection_material_id, parameter_id, range_id, handling_id, bellow_id, unit_id, is_inactive', 'numerical', 'integerOnly'=>true),
			array('code, size, length', 'length', 'max'=>60),
			array('name', 'length', 'max'=>200),
			array('buying_price, selling_price, physical_thickness, connection_diameter', 'length', 'max'=>18),
			// The following rule is used by search().
			array('id, code, name, buying_price, selling_price, size, length, drat, physical_thickness, connection_diameter, category_id, type_id, brand_id, material_id, disc_material_id, body_type_id, connection_id, grade_id, classification_id, thickness_id, variety_id, connection_material_id, parameter_id, range_id, handling_id, bellow_id, unit_id, is_inactive', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'adjustmentDetails' => array(self::HAS_MANY, 'AdjustmentDetail', 'product_id'),
			'deliveryDetails' => array(self::HAS_MANY, 'DeliveryDetail', 'product_id'),
			'inventories' => array(self::HAS_MANY, 'Inventory', 'product_id'),
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'thickness' => array(self::BELONGS_TO, 'Thickness', 'thickness_id'),
			'variety' => array(self::BELONGS_TO, 'Variety', 'variety_id'),
			'connectionMaterial' => array(self::BELONGS_TO, 'ConnectionMaterial', 'connection_material_id'),
			'parameter' => array(self::BELONGS_TO, 'Parameter', 'parameter_id'),
			'range' => array(self::BELONGS_TO, 'Range', 'range_id'),
			'bellow' => array(self::BELONGS_TO, 'Bellow', 'bellow_id'),
			'unit' => array(self::BELONGS_TO, 'Unit', 'unit_id'),
			'handling' => array(self::BELONGS_TO, 'Handling', 'handling_id'),
			'type' => array(self::BELONGS_TO, 'Type', 'type_id'),
			'brand' => array(self::BELONGS_TO, 'Brand', 'brand_id'),
			'material' => array(self::BELONGS_TO, 'Material', 'material_id'),
			'discMaterial' => array(self::BELONGS_TO, 'DiscMaterial', 'disc_material_id'),
			'bodyType' => array(self::BELONGS_TO, 'BodyType', 'body_type_id'),
			'connection' => array(self::BELONGS_TO, 'Connection', 'connection_id'),
			'grade' => array(self::BELONGS_TO, 'Grade', 'grade_id'),
			'classification' => array(self::BELONGS_TO, 'Classification', 'classification_id'),
			'purchaseDetails' => array(self::HAS_MANY, 'PurchaseDetail', 'product_id'),
			'purchaseReturnDetails' => array(self::HAS_MANY, 'PurchaseReturnDetail', 'product_id'),
			'receiveDetails' => array(self::HAS_MANY, 'ReceiveDetail', 'product_id'),
			'saleDetails' => array(self::HAS_MANY, 'SaleDetail', 'product_id'),
			'saleReturnDetails' => array(self::HAS_MANY, 'SaleReturnDetail', 'product_id'),
			'transferDetails' => array(self::HAS_MANY, 'TransferDetail', 'product_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'buying_price' => 'Buying Price',
			'selling_price' => 'Selling Price',
			'size' => 'Size',
			'length' => 'Length',
			'drat' => 'Drat',
			'physical_thickness' => 'Physical Thickness',
			'connection_diameter' => 'Connection Diameter',
			'category_id' => 'Category',
			'type_id' => 'Type',
			'brand_id' => 'Brand',
			'material_id' => 'Material',
			'disc_material_id' => 'Disc Material',
			'body_type_id' => 'Body Type',
			'connection_id' => 'Connection',
			'grade_id' => 'Grade',
			'classification_id' => 'Classification',
			'thickness_id' => 'Thickness',
			'variety_id' => 'Variety',
			'connection_material_id' => 'Connection Material',
			'parameter_id' => 'Parameter',
			'range_id' => 'Range',
			'handling_id' => 'Handling',
			'bellow_id' => 'Bellow',
			'unit_id' => 'Unit',
			'is_inactive' => 'Is Inactive',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('t.code', $this->code, true);
		$criteria->compare('t.name', $this->name, true);
		$criteria->compare('buying_price', $this->buying_price, true);
		$criteria->compare('selling_price', $this->selling_price, true);
		$criteria->compare('size', $this->size, true);
		$criteria->compare('length', $this->length, true);
		$criteria->compare('drat', $this->drat);
		$criteria->compare('physical_thickness', $this->physical_thickness, true);
		$criteria->compare('connection_diameter', $this->connection_diameter, true);
		$criteria->compare('t.category_id', $this->category_id);
		$criteria->compare('type_id', $this->type_id);
		$criteria->compare('brand_id', $this->brand_id);
		$criteria->compare('material_id', $this->material_id);
		$criteria->compare('disc_material_id', $this->disc_material_id);
		$criteria->compare('body_type_id', $this->body_type_id);
		$criteria->compare('connection_id', $this->connection_id);
		$criteria->compare('grade_id', $this->grade_id);
		$criteria->compare('classification_id', $this->classification_id);
		$criteria->compare('thickness_id', $this->thickness_id);
		$criteria->compare('variety_id', $this->variety_id);
		$criteria->compare('connection_material_id', $this->connection_material_id);
		$criteria->compare('parameter_id', $this->parameter_id);
		$criteria->compare('range_id', $this->range_id);
		$criteria->compare('handling_id', $this->handling_id);
		$criteria->compare('bellow_id', $this->bellow_id);
		$criteria->compare('unit_id', $this->unit_id);
		$criteria->compare('is_inactive', $this->is_inactive);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}